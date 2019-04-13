<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\services;

use DateTime;
use DateTimeZone;
use corbomite\queue\models\ActionQueueItemModel;
use corbomite\db\interfaces\BuildQueryInterface;
use corbomite\db\interfaces\QueryModelInterface;
use corbomite\queue\models\ActionQueueBatchModel;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatch;
use corbomite\queue\interfaces\ActionQueueBatchModelInterface;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemRecord;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemSelect;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatchRecord;

class FetchBatchesService
{
    private $buildQuery;

    public function __construct(
        BuildQueryInterface $buildQuery
    ) {
        $this->buildQuery = $buildQuery;
    }

    /**
     * @return ActionQueueBatchModelInterface[]
     */
    public function __invoke(QueryModelInterface $params): array
    {
        return $this->fetch($params);
    }

    /**
     * @return ActionQueueBatchModelInterface[]
     */
    public function fetch(QueryModelInterface $params): array
    {
        $models = [];

        foreach ($this->fetchResults($params) as $record) {
            $model = new ActionQueueBatchModel();

            $model->setGuidAsBytes($record->guid);
            $model->name($record->name);
            $model->title($record->title);
            $model->hasStarted((bool) $record->has_started);
            $model->isFinished((bool) $record->is_finished);
            $model->percentComplete((float) $record->percent_complete);
            /** @noinspection PhpUnhandledExceptionInspection */
            $model->addedAt(new DateTime(
                $record->added_at,
                new DateTimeZone($record->added_at_time_zone)
            ));

            if ($record->finished_at) {
                /** @noinspection PhpUnhandledExceptionInspection */
                $model->finishedAt(new DateTime(
                    $record->finished_at,
                    new DateTimeZone($record->finished_at_time_zone)
                ));
            }

            foreach ($record->action_queue_items as $item) {
                /** @var ActionQueueItemRecord $item */

                $itemModel = new ActionQueueItemModel();

                $itemModel->setGuidAsBytes($item->guid);
                $itemModel->isFinished((bool) $item->is_finished);

                if ($item->finished_at) {
                    /** @noinspection PhpUnhandledExceptionInspection */
                    $itemModel->finishedAt(new DateTime(
                        $item->finished_at,
                        new DateTimeZone($item->finished_at_time_zone)
                    ));
                }

                $model->addItem($itemModel);
            }

            $models[] = $model;
        }

        return $models;
    }

    /**
     * @param $params
     * @return ActionQueueBatchRecord[]
     */
    private function fetchResults($params): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->buildQuery->build(ActionQueueBatch::class, $params)
            ->with([
                'action_queue_items' => function (ActionQueueItemSelect $select) {
                    $select->orderBy('order_to_run ASC');
                }
            ])
            ->fetchRecords();
    }
}
