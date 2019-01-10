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
use corbomite\queue\models\ActionQueueItemModel;
use corbomite\queue\data\ActionQueueItem\ActionQueueItem;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemRecord;

class MarkItemAsRunService
{
    private $ormFactory;
    private $updateActionQueue;

    public function __construct(
        OrmFactory $ormFactory,
        UpdateActionQueueService $updateActionQueue
    ) {
        $this->ormFactory = $ormFactory;
        $this->updateActionQueue = $updateActionQueue;
    }

    public function __invoke(ActionQueueItemModel $model): void
    {
        $this->markAsRun($model);
    }

    public function markAsRun(ActionQueueItemModel $model): void
    {
        try {
            $dateTime = new DateTime();
            $dateTime->setTimezone(new DateTimeZone('UTC'));

            $atlas = $this->ormFactory->makeOrm();

            /** @var ActionQueueItemRecord $record */
            $record = $atlas->select(ActionQueueItem::class)
                ->where('guid = ', $model->guid())
                ->with(['action_queue_batch'])
                ->fetchRecord();

            $record->is_finished = true;
            $record->finished_at = $dateTime->format('Y-m-d H:i:s');
            $record->finished_at_time_zone = $dateTime->getTimezone()
                ->getName();

            $atlas->persist($record);

            $this->updateActionQueue->update($record->action_queue_batch_guid);
        } catch (Throwable $e) {
        }
    }
}
