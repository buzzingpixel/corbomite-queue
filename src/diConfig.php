<?php
declare(strict_types=1);

use corbomite\di\Di;
use corbomite\queue\QueueApi;
use Symfony\Component\Console\Output\ConsoleOutput;
use corbomite\queue\actions\CreateMigrationsAction;
use corbomite\queue\services\AddBatchToQueueService;

return [
    CreateMigrationsAction::class => function () {
        return new CreateMigrationsAction(
            __DIR__ . '/migrations',
            new ConsoleOutput()
        );
    },
    QueueApi::class => function () {
        return new QueueApi(new Di());
    },
    AddBatchToQueueService::class => function () {
        return new AddBatchToQueueService();
    },
];
