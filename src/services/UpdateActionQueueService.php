<?php

declare(strict_types=1);

namespace corbomite\queue\services;

use corbomite\db\Factory as OrmFactory;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatch;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatchRecord;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemSelect;
use corbomite\queue\interfaces\QueueApiInterface;
use DateTime;
use DateTimeZone;
use Throwable;
use function preg_match;

class UpdateActionQueueService
{
    /** @var QueueApiInterface */
    private $queueApi;
    /** @var OrmFactory */
    private $ormFactory;

    public function __construct(
        QueueApiInterface $queueApi,
        OrmFactory $ormFactory
    ) {
        $this->queueApi   = $queueApi;
        $this->ormFactory = $ormFactory;
    }

    public function __invoke(string $actionQueueGuid) : void
    {
        $this->update($actionQueueGuid);
    }

    public function update(string $actionQueueGuid, bool $markAsNotRunning = true) : void
    {
        try {
            $dateTime = new DateTime();
            $dateTime->setTimezone(new DateTimeZone('UTC'));

            $record = $this->fetchActionQueueBatchRecord($actionQueueGuid);

            if (! $record) {
                return;
            }

            if ($markAsNotRunning) {
                $record->is_running = false;
            }

            $totalItems = 0;
            $totalRun   = 0;

            foreach ($record->action_queue_items as $item) {
                $totalItems++;

                if (! $item->is_finished) {
                    continue;
                }

                $totalRun++;
            }

            if ($totalRun >= $totalItems && $record->is_finished) {
                $this->ormFactory->makeOrm()->persist($record);

                return;
            }

            if ($totalRun >= $totalItems && ! $record->is_finished) {
                $record->is_finished           = true;
                $record->finished_at           = $dateTime->format('Y-m-d H:i:s');
                $record->finished_at_time_zone = $dateTime->getTimezone()
                    ->getName();
                $record->percent_complete      = 100;

                $this->ormFactory->makeOrm()->persist($record);

                return;
            }

            $percentComplete = $totalRun / $totalItems * 100;
            $percentComplete = $percentComplete > 100 ? 100 : $percentComplete;
            $percentComplete = $percentComplete < 0 ? 0 : $percentComplete;

            $record->percent_complete = $percentComplete;

            $this->ormFactory->makeOrm()->persist($record);
        } catch (Throwable $e) {
        }
    }

    private function fetchActionQueueBatchRecord(
        string $actionQueueGuid
    ) : ?ActionQueueBatchRecord {
        if (! $this->isBinary($actionQueueGuid)) {
            $actionQueueGuid = $this->queueApi->uuidToBytes($actionQueueGuid);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        /** @var ActionQueueBatchRecord $actionQueueBatchRecord */
        $actionQueueBatchRecord = $this->ormFactory->makeOrm()
            ->select(ActionQueueBatch::class)
            ->where('guid = ', $actionQueueGuid)
            ->with([
                'action_queue_items' => static function (
                    ActionQueueItemSelect $selectReplies
                ) : void {
                    $selectReplies->orderBy('order_to_run ASC');
                },
            ])
            ->fetchRecord();

        return $actionQueueBatchRecord;
    }

    private function isBinary($str) : bool
    {
        return preg_match('~[^\x20-\x7E\t\r\n]~', $str) > 0;
    }
}
