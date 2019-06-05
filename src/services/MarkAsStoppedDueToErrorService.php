<?php

declare(strict_types=1);

namespace corbomite\queue\services;

use corbomite\db\Factory as OrmFactory;
use corbomite\queue\data\ActionQueueItem\ActionQueueItem;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemRecord;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;
use DateTime;
use DateTimeZone;
use Throwable;
use const PHP_EOL;

class MarkAsStoppedDueToErrorService
{
    /** @var OrmFactory */
    private $ormFactory;

    public function __construct(OrmFactory $ormFactory)
    {
        $this->ormFactory = $ormFactory;
    }

    public function __invoke(ActionQueueItemModelInterface $model, ?Throwable $e = null) : void
    {
        $this->markStopped($model, $e);
    }

    public function markStopped(ActionQueueItemModelInterface $model, ?Throwable $e = null) : void
    {
        try {
            $dateTime = new DateTime();
            $dateTime->setTimezone(new DateTimeZone('UTC'));

            $orm = $this->ormFactory->makeOrm();

            /** @var ActionQueueItemRecord $record */
            $record = $orm->select(ActionQueueItem::class)
                ->where('guid = ', $model->getGuidAsBytes())
                ->with(['action_queue_batch'])
                ->fetchRecord();

            $errorMessage = null;

            if ($e) {
                $errorMessage  = 'Error Code: ' . $e->getCode() . PHP_EOL . PHP_EOL;
                $errorMessage .= 'File: ' . $e->getFile() . PHP_EOL . PHP_EOL;
                $errorMessage .= 'Line: ' . $e->getLine() . PHP_EOL . PHP_EOL;
                $errorMessage .= 'Message: ' . $e->getMessage() . PHP_EOL . PHP_EOL;
                $errorMessage .= 'Trace: ' . $e->getTraceAsString();
            }

            $record->action_queue_batch->is_finished           = true;
            $record->action_queue_batch->finished_due_to_error = true;
            $record->action_queue_batch->error_message         = $errorMessage;
            $record->action_queue_batch->finished_at           = $dateTime
                ->format('Y-m-d H:i:s');
            $record->action_queue_batch->finished_at_time_zone = $dateTime
                ->getTimezone()
                ->getName();

            $orm->persist($record);
        } catch (Throwable $e) {
        }
    }
}
