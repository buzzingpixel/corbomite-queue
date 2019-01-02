<?php
declare(strict_types=1);

namespace corbomite\queue;

use corbomite\di\Di;

use corbomite\queue\models\ActionQueueBatchModel;
use corbomite\queue\services\AddBatchToQueueService;

class QueueApi
{
    private $di;

    public function __construct(Di $di)
    {
        $this->di = $di;
    }

    public function addToQueue(ActionQueueBatchModel $model): void
    {
        /** @var AddBatchToQueueService $service */
        $service = $this->di->getFromDefinition(AddBatchToQueueService::class);
        $service($model);
    }
}
