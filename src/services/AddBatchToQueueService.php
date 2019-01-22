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
use Ramsey\Uuid\UuidFactoryInterface;
use corbomite\db\Factory as OrmFactory;
use corbomite\queue\data\ActionQueueItem\ActionQueueItem;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatch;
use corbomite\queue\exceptions\InvalidActionQueueBatchModel;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;
use corbomite\queue\interfaces\ActionQueueBatchModelInterface;

class AddBatchToQueueService
{
    private $ormFactory;
    private $uuidFactory;

    public function __construct(
        OrmFactory $ormFactory,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->ormFactory = $ormFactory;
        $this->uuidFactory = $uuidFactory;
    }

    /**
     * @throws InvalidActionQueueBatchModel
     */
    public function __invoke(ActionQueueBatchModelInterface $model): void
    {
        $this->add($model);
    }

    /**
     * @throws InvalidActionQueueBatchModel
     */
    public function add(ActionQueueBatchModelInterface $model): void
    {
        $orm = $this->ormFactory->makeOrm();

        /** @noinspection PhpUnhandledExceptionInspection */
        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $this->validateModel($model);
        $this->setGuids($model);

        $items = $orm->newRecordSet(ActionQueueItem::class);

        $order = 1;

        foreach ($model->items() as $item) {
            $items->appendNew([
                'guid' => $item->guid(),
                'order_to_run' => $order,
                'action_queue_batch_guid' => $model->guid(),
                'is_finished' => false,
                'finished_at' => null,
                'finished_at_time_zone' => null,
                'class' => $item->class(),
                'method' => $item->method(),
                'context' => \json_encode($item->context()),
            ]);

            $order++;
        }

        $record = $orm->newRecord(ActionQueueBatch::class);
        $record->guid = $model->guid();
        $record->name = $model->name();
        $record->title = $model->title();
        $record->has_started = false;
        $record->is_finished = false;
        $record->finished_due_to_error = false;
        $record->percent_complete = 0;
        $record->added_at = $dateTime->format('Y-m-d H:i:s');
        $record->added_at_time_zone = $dateTime->getTimezone()->getName();
        $record->finished_at = null;
        $record->finished_at_time_zone = null;
        $record->context = \json_encode($model->context());
        $record->action_queue_items = $items;

        $orm->persist($record);
    }

    /**
     * @throws InvalidActionQueueBatchModel
     */
    private function validateModel(ActionQueueBatchModelInterface $model): void
    {
        if (! $model->name() ||
            ! $model->title() ||
            ! $model->items() ||
            ! \is_array($model->items())
        ) {
            throw new InvalidActionQueueBatchModel();
        }

        foreach ($model->items() as $item) {
            $instance = $item instanceof ActionQueueItemModelInterface;

            if (! \is_object($item) || ! $instance || ! $item->class()) {
                throw new InvalidActionQueueBatchModel();
            }

            if (! method_exists($item->class(), $item->method())) {
                throw new InvalidActionQueueBatchModel();
            }
        }
    }

    private function setGuids(ActionQueueBatchModelInterface $model): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $model->guid($this->uuidFactory->uuid4()->toString());

        foreach ($model->items() as $item) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $item->guid($this->uuidFactory->uuid4()->toString());
        }
    }
}
