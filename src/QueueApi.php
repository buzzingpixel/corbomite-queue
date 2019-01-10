<?php
declare(strict_types=1);

namespace corbomite\queue;

use corbomite\di\Di;
use corbomite\queue\models\ActionQueueItemModel;
use corbomite\queue\models\ActionQueueBatchModel;
use corbomite\queue\services\AddBatchToQueueService;
use corbomite\queue\services\GetNextQueueItemService;

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

    public function addToQueue(ActionQueueBatchModel $model): void
    {
        $service = $this->di->getFromDefinition(AddBatchToQueueService::class);
        $service($model);
    }

    public function getNextQueueItem(bool $markAsStarted = false): ?ActionQueueItemModel
    {
        $service = $this->di->getFromDefinition(GetNextQueueItemService::class);
        return $service($markAsStarted);
    }
}
