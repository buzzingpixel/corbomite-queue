<?php
declare(strict_types=1);

use corbomite\di\Di;
use corbomite\queue\QueueApi;
use corbomite\queue\services\AddBatchToQueueService;

return [
    QueueApi::class => function () {
        return new QueueApi(new Di());
    },
    AddBatchToQueueService::class => function () {
        return new AddBatchToQueueService();
    },
];
