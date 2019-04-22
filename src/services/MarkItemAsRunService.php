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

class MarkItemAsRunService
{
    /** @var OrmFactory */
    private $ormFactory;
    /** @var UpdateActionQueueService */
    private $updateActionQueue;

    public function __construct(
        OrmFactory $ormFactory,
        UpdateActionQueueService $updateActionQueue
    ) {
        $this->ormFactory        = $ormFactory;
        $this->updateActionQueue = $updateActionQueue;
    }

    public function __invoke(ActionQueueItemModelInterface $model) : void
    {
        $this->markAsRun($model);
    }

    public function markAsRun(ActionQueueItemModelInterface $model) : void
    {
        try {
            $dateTime = new DateTime();
            $dateTime->setTimezone(new DateTimeZone('UTC'));

            $atlas = $this->ormFactory->makeOrm();

            /** @var ActionQueueItemRecord $record */
            $record = $atlas->select(ActionQueueItem::class)
                ->where('guid = ', $model->getGuidAsBytes())
                ->with(['action_queue_batch'])
                ->fetchRecord();

            $record->is_finished           = true;
            $record->finished_at           = $dateTime->format('Y-m-d H:i:s');
            $record->finished_at_time_zone = $dateTime->getTimezone()
                ->getName();

            $atlas->persist($record);

            $this->updateActionQueue->update($record->action_queue_batch_guid);
        } catch (Throwable $e) {
        }
    }
}
