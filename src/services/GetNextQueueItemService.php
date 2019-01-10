<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\services;

use Throwable;
use corbomite\db\Factory as OrmFactory;
use corbomite\queue\models\ActionQueueItemModel;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatch;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemSelect;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatchRecord;

class GetNextQueueItemService
{
    private $ormFactory;

    public function __construct(OrmFactory $ormFactory)
    {
        $this->ormFactory = $ormFactory;
    }

    public function __invoke(bool $markAsStarted = false): ?ActionQueueItemModel
    {
        return $this->get($markAsStarted);
    }

    public function get(bool $markAsStarted = false): ?ActionQueueItemModel
    {
        try {
            $actionQueueRecord = $this->fetchActionQueueBatchRecord();

            if (! $actionQueueRecord) {
                return null;
            }

            $item = $actionQueueRecord->action_queue_items->getOneBy([
                'is_finished' => 0
            ]);

            if (! $item) {
                $actionQueueRecord->has_started = true;
                $actionQueueRecord->is_finished = true;
                $actionQueueRecord->percent_complete = 100;
                $this->ormFactory->makeOrm()->persist($actionQueueRecord);
                return null;
            }

            if ($markAsStarted && ! $actionQueueRecord->has_started) {
                $actionQueueRecord->has_started = true;
                $this->ormFactory->makeOrm()->persist($actionQueueRecord);
            }

            $model = new ActionQueueItemModel([
                'guid' => $item->guid,
                'isFinished' => false,
                'class' => $item->class,
                'method' => $item->method,
                'context' => json_decode($item->context, true) ?? [],
            ]);

            return $model;
        } catch (Throwable $e) {
            return null;
        }
    }

    private function fetchActionQueueBatchRecord(): ?ActionQueueBatchRecord
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @var ActionQueueBatchRecord $actionQueueBatchRecord */
        $actionQueueBatchRecord = $this->ormFactory->makeOrm()
            ->select(ActionQueueBatch::class)
            ->where('is_finished = ', 0)
            ->with([
                'action_queue_items' => function (
                    ActionQueueItemSelect $selectReplies
                ) {
                    $selectReplies
                        ->where('is_finished = ', 0)
                        ->limit(1)
                        ->orderBy('order_to_run ASC');
                }
            ])
            ->orderBy('added_at ASC')
            ->fetchRecord();

        return $actionQueueBatchRecord;
    }
}
