<?php

declare(strict_types=1);

use corbomite\db\Factory as DbFactory;
use corbomite\db\Factory as OrmFactory;
use corbomite\db\services\BuildQueryService;
use corbomite\di\Di;
use corbomite\queue\actions\CreateMigrationsAction;
use corbomite\queue\actions\RunQueueAction;
use corbomite\queue\QueueApi;
use corbomite\queue\services\AddBatchToQueueService;
use corbomite\queue\services\FetchBatchesService;
use corbomite\queue\services\GetNextQueueItemService;
use corbomite\queue\services\MarkAsStoppedDueToErrorService;
use corbomite\queue\services\MarkItemAsRunService;
use corbomite\queue\services\UpdateActionQueueService;
use Symfony\Component\Console\Output\ConsoleOutput;

return [
    CreateMigrationsAction::class => static function () {
        return new CreateMigrationsAction(
            __DIR__ . '/migrations',
            new ConsoleOutput()
        );
    },
    RunQueueAction::class => static function () {
        return new RunQueueAction(new Di());
    },
    QueueApi::class => static function () {
        return new QueueApi(
            new Di(),
            new DbFactory()
        );
    },
    AddBatchToQueueService::class => static function () {
        return new AddBatchToQueueService(new OrmFactory());
    },
    FetchBatchesService::class => static function () {
        return new FetchBatchesService(
            Di::get(BuildQueryService::class)
        );
    },
    GetNextQueueItemService::class => static function () {
        return new GetNextQueueItemService(new OrmFactory());
    },
    MarkAsStoppedDueToErrorService::class => static function () {
        return new MarkAsStoppedDueToErrorService(new OrmFactory());
    },
    MarkItemAsRunService::class => static function () {
        return new MarkItemAsRunService(
            new OrmFactory(),
            Di::get(UpdateActionQueueService::class)
        );
    },
    UpdateActionQueueService::class => static function () {
        return new UpdateActionQueueService(
            Di::get(QueueApi::class),
            new OrmFactory()
        );
    },
];
