<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

use corbomite\di\Di;
use Ramsey\Uuid\UuidFactory;
use corbomite\queue\QueueApi;
use corbomite\db\Factory as DbFactory;
use corbomite\db\Factory as OrmFactory;
use corbomite\queue\actions\RunQueueAction;
use corbomite\db\services\BuildQueryService;
use corbomite\queue\services\FetchBatchesService;
use corbomite\queue\services\MarkItemAsRunService;
use Symfony\Component\Console\Output\ConsoleOutput;
use corbomite\queue\actions\CreateMigrationsAction;
use corbomite\queue\services\AddBatchToQueueService;
use corbomite\queue\services\GetNextQueueItemService;
use corbomite\queue\services\UpdateActionQueueService;
use corbomite\queue\services\MarkAsStoppedDueToErrorService;

return [
    CreateMigrationsAction::class => function () {
        return new CreateMigrationsAction(
            __DIR__ . '/migrations',
            new ConsoleOutput()
        );
    },
    RunQueueAction::class => function () {
        return new RunQueueAction(new Di());
    },
    QueueApi::class => function () {
        return new QueueApi(
            new Di(),
            new DbFactory()
        );
    },
    AddBatchToQueueService::class => function () {
        return new AddBatchToQueueService(new OrmFactory(), new UuidFactory());
    },
    FetchBatchesService::class => function () {
        return new FetchBatchesService(
            Di::get(BuildQueryService::class)
        );
    },
    GetNextQueueItemService::class => function () {
        return new GetNextQueueItemService(new OrmFactory());
    },
    MarkAsStoppedDueToErrorService::class => function () {
        return new MarkAsStoppedDueToErrorService(new OrmFactory());
    },
    MarkItemAsRunService::class => function () {
        return new MarkItemAsRunService(
            new OrmFactory(),
            Di::get(UpdateActionQueueService::class)
        );
    },
    UpdateActionQueueService::class => function () {
        return new UpdateActionQueueService(new OrmFactory());
    },
];
