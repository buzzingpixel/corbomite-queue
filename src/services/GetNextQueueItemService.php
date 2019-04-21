<?php

declare(strict_types=1);

namespace corbomite\queue\services;

use corbomite\db\Factory as OrmFactory;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatch;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatchRecord;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemSelect;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;
use corbomite\queue\models\ActionQueueItemModel;
use Throwable;
use function is_array;
use function json_decode;

class GetNextQueueItemService
{
    /** @var OrmFactory */
    private $ormFactory;

    public function __construct(OrmFactory $ormFactory)
    {
        $this->ormFactory = $ormFactory;
    }

    public function __invoke(bool $markAsStarted = false) : ?ActionQueueItemModelInterface
    {
        return $this->get($markAsStarted);
    }

    public function get(bool $markAsStarted = false) : ?ActionQueueItemModelInterface
    {
        try {
            $actionQueueRecord = $this->fetchActionQueueBatchRecord();

            if (! $actionQueueRecord) {
                return null;
            }

            $item = $actionQueueRecord->action_queue_items->getOneBy(['is_finished' => 0]);

            if (! $item) {
                $actionQueueRecord->has_started      = true;
                $actionQueueRecord->is_finished      = true;
                $actionQueueRecord->percent_complete = 100;
                $this->ormFactory->makeOrm()->persist($actionQueueRecord);

                return null;
            }

            if ($markAsStarted && ! $actionQueueRecord->has_started) {
                $actionQueueRecord->has_started = true;
                $actionQueueRecord->is_running  = true;
                $this->ormFactory->makeOrm()->persist($actionQueueRecord);
            }

            $model = new ActionQueueItemModel();
            $model->setGuidAsBytes($item->guid);
            $model->isFinished(false);
            $model->class($item->class);
            $model->method($item->method);
            $context = json_decode($item->context, true) ?? [];
            $context = is_array($context) ? $context : [];
            $model->context($context);

            return $model;
        } catch (Throwable $e) {
            return null;
        }
    }

    private function fetchActionQueueBatchRecord() : ?ActionQueueBatchRecord
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @var ActionQueueBatchRecord $actionQueueBatchRecord */
        $actionQueueBatchRecord = $this->ormFactory->makeOrm()
            ->select(ActionQueueBatch::class)
            ->where('is_finished = ', 0)
            ->where('is_running = ', 0)
            ->with([
                'action_queue_items' => static function (
                    ActionQueueItemSelect $selectReplies
                ) : void {
                    $selectReplies
                        ->where('is_finished = ', 0)
                        ->limit(1)
                        ->orderBy('order_to_run ASC');
                },
            ])
            ->orderBy('added_at ASC')
            ->fetchRecord();

        return $actionQueueBatchRecord;
    }
}
