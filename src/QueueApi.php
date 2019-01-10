<?php
declare(strict_types=1);

namespace corbomite\queue;

use corbomite\di\Di;
use corbomite\queue\models\ActionQueueItemModel;
use corbomite\queue\models\ActionQueueBatchModel;
use corbomite\queue\services\AddBatchToQueueService;
use corbomite\queue\services\GetNextQueueItemService;
use corbomite\queue\exceptions\InvalidActionQueueBatchModel;
use corbomite\queue\services\MarkAsStoppedDueToErrorService;

class QueueApi
{
    private $di;

    public function __construct(Di $di)
    {
        $this->di = $di;
    }

    public function makeActionQueueBatchModel(array $props = []): ActionQueueBatchModel
    {
        return new ActionQueueBatchModel($props);
    }

    public function makeActionQueueItemModel(array $props = []): ActionQueueItemModel
    {
        return new ActionQueueItemModel($props);
    }

    /**
     * @throws InvalidActionQueueBatchModel
     */
    public function addToQueue(ActionQueueBatchModel $model): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(AddBatchToQueueService::class);
        $service($model);
    }

    public function getNextQueueItem(bool $markAsStarted = false): ?ActionQueueItemModel
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(GetNextQueueItemService::class);
        return $service($markAsStarted);
    }

    public function markAsStoppedDueToError(ActionQueueItemModel $model): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(MarkAsStoppedDueToErrorService::class);
        $service($model);
    }
}
