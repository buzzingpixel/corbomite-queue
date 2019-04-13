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
use corbomite\queue\data\ActionQueueItem\ActionQueueItem;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemRecord;

class MarkAsStoppedDueToErrorService
{
    private $ormFactory;

    public function __construct(OrmFactory $ormFactory)
    {
        $this->ormFactory = $ormFactory;
    }

    public function __invoke(ActionQueueItemModelInterface $model): void
    {
        $this->markStopped($model);
    }

    public function markStopped(ActionQueueItemModelInterface $model): void
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

            $record->action_queue_batch->is_finished = true;
            $record->action_queue_batch->finished_due_to_error = true;
            $record->action_queue_batch->finished_at = $dateTime
                ->format('Y-m-d H:i:s');
            $record->action_queue_batch->finished_at_time_zone = $dateTime
                ->getTimezone()
                ->getName();

            $orm->persist($record);
        } catch (Throwable $e) {
        }
    }
}
