<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\services;

use DateTime;
use Throwable;
use DateTimeZone;
use corbomite\db\Factory as OrmFactory;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatch;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemSelect;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatchRecord;

class UpdateActionQueueService
{
    private $ormFactory;

    public function __construct(OrmFactory $ormFactory)
    {
        $this->ormFactory = $ormFactory;
    }

    public function __invoke(string $actionQueueGuid): void
    {
        $this->update($actionQueueGuid);
    }

    public function update(string $actionQueueGuid): void
    {
        try {
            $dateTime = new DateTime();
            $dateTime->setTimezone(new DateTimeZone('UTC'));

            $record = $this->fetchActionQueueBatchRecord($actionQueueGuid);

            if (! $record) {
                return;
            }

            $totalItems = $record->action_queue_items->count();
            $totalRun = 0;

            foreach ($record->action_queue_items as $item) {
                if (! $item->is_finished) {
                    continue;
                }

                $totalRun++;
            }

            if ($totalRun >= $totalItems && $record->is_finished) {
                return;
            }

            if ($totalRun >= $totalItems && ! $record->is_finished) {
                $record->is_finished = true;
                $record->finished_at = $dateTime->format('Y-m-d H:i:s');
                $record->finished_at_time_zone = $dateTime->getTimezone()
                    ->getName();
                $record->percent_complete = 100;

                $this->ormFactory->makeOrm()->persist($record);
                return;
            }

            $percentComplete = ($totalRun / $totalItems) * 100;
            $percentComplete = $percentComplete > 100 ? 100 : $percentComplete;
            $percentComplete = $percentComplete < 0 ? 0 : $percentComplete;

            $record->percent_complete = $percentComplete;

            $this->ormFactory->makeOrm()->persist($record);
        } catch (Throwable $e) {
        }
    }

    private function fetchActionQueueBatchRecord(
        string $actionQueueGuid
    ): ?ActionQueueBatchRecord {
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @var ActionQueueBatchRecord $actionQueueBatchRecord */
        $actionQueueBatchRecord = $this->ormFactory->makeOrm()
            ->select(ActionQueueBatch::class)
            ->where('guid = ', $actionQueueGuid)
            ->with([
                'action_queue_items' => function (
                    ActionQueueItemSelect $selectReplies
                ) {
                    $selectReplies->orderBy('order_to_run ASC');
                }
            ])
            ->fetchRecord();

        return $actionQueueBatchRecord;
    }
}
