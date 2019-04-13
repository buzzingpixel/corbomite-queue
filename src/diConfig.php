<?php

declare(strict_types=1);

use Composer\Autoload\ClassLoader;
use corbomite\db\Factory as DbFactory;
use corbomite\db\Factory as OrmFactory;
use corbomite\db\services\BuildQueryService;
use corbomite\queue\actions\CreateMigrationsAction;
use corbomite\queue\actions\RunQueueAction;
use corbomite\queue\PhpCalls;
use corbomite\queue\QueueApi;
use corbomite\queue\services\AddBatchToQueueService;
use corbomite\queue\services\FetchBatchesService;
use corbomite\queue\services\GetNextQueueItemService;
use corbomite\queue\services\MarkAsStoppedDueToErrorService;
use corbomite\queue\services\MarkItemAsRunService;
use corbomite\queue\services\UpdateActionQueueService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;

return [
    CreateMigrationsAction::class => static function () {
        $appBasePath = null;

        if (defined('APP_BASE_PATH')) {
            $appBasePath = APP_BASE_PATH;
        }

        if (! $appBasePath) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $reflection = new ReflectionClass(ClassLoader::class);

            $appBasePath = dirname($reflection->getFileName(), 3);
        }

        return new CreateMigrationsAction(
            __DIR__ . '/migrations',
            new ConsoleOutput(),
            $appBasePath,
            new Filesystem(),
            new PhpCalls()
        );
    },
    RunQueueAction::class => static function (ContainerInterface $di) {
        return new RunQueueAction($di);
    },
    QueueApi::class => static function (ContainerInterface $di) {
        return new QueueApi(
            $di,
            new DbFactory()
        );
    },
    AddBatchToQueueService::class => static function () {
        return new AddBatchToQueueService(new OrmFactory());
    },
    FetchBatchesService::class => static function (ContainerInterface $di) {
        return new FetchBatchesService(
            $di->get(BuildQueryService::class)
        );
    },
    GetNextQueueItemService::class => static function () {
        return new GetNextQueueItemService(new OrmFactory());
    },
    MarkAsStoppedDueToErrorService::class => static function () {
        return new MarkAsStoppedDueToErrorService(new OrmFactory());
    },
    MarkItemAsRunService::class => static function (ContainerInterface $di) {
        return new MarkItemAsRunService(
            new OrmFactory(),
            $di->get(UpdateActionQueueService::class)
        );
    },
    UpdateActionQueueService::class => static function (ContainerInterface $di) {
        return new UpdateActionQueueService(
            $di->get(QueueApi::class),
            new OrmFactory()
        );
    },
];
