<?php
declare(strict_types=1);

namespace corbomite\queue\services;

use DateTime;
use DateTimeZone;
use Ramsey\Uuid\UuidFactoryInterface;
use corbomite\db\Factory as OrmFactory;
use corbomite\queue\models\ActionQueueItemModel;
use corbomite\queue\models\ActionQueueBatchModel;
use corbomite\queue\data\ActionQueueItem\ActionQueueItem;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatch;
use corbomite\queue\exceptions\InvalidActionQueueBatchModel;

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
    public function __invoke(ActionQueueBatchModel $model): void
    {
        $this->add($model);
    }

    /**
     * @throws InvalidActionQueueBatchModel
     */
    public function add(ActionQueueBatchModel $model): void
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
                'action_queue_guid' => $model->guid(),
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
    private function validateModel(ActionQueueBatchModel $model): void
    {
        if (! $model->name() ||
            ! $model->title() ||
            ! $model->items() ||
            ! \is_array($model->items())
        ) {
            throw new InvalidActionQueueBatchModel();
        }

        foreach ($model->items() as $item) {
            if (! \is_object($item) ||
                \get_class($item) !== ActionQueueItemModel::class ||
                ! $item->class()
            ) {
                throw new InvalidActionQueueBatchModel();
            }

            if (! method_exists($item->class(), $item->method())) {
                throw new InvalidActionQueueBatchModel();
            }
        }
    }

    private function setGuids(ActionQueueBatchModel $model): void
    {
        $model->guid($this->uuidFactory->uuid4()->toString());

        foreach ($model->items() as $item) {
            $item->guid($this->uuidFactory->uuid4()->toString());
        }
    }
}
