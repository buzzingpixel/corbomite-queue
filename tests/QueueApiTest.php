<?php

declare(strict_types=1);

namespace corbomite\tests;

use corbomite\db\Factory;
use corbomite\db\interfaces\QueryModelInterface;
use corbomite\db\models\QueryModel;
use corbomite\queue\interfaces\ActionQueueBatchModelInterface;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;
use corbomite\queue\models\ActionQueueBatchModel;
use corbomite\queue\models\ActionQueueItemModel;
use corbomite\queue\QueueApi;
use corbomite\queue\services\AddBatchToQueueService;
use corbomite\queue\services\FetchBatchesService;
use corbomite\queue\services\GetNextQueueItemService;
use corbomite\queue\services\MarkAsStoppedDueToErrorService;
use corbomite\queue\services\MarkItemAsRunService;
use corbomite\queue\services\UpdateActionQueueService;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Throwable;

class QueueApiTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testMakeQueryModel() : void
    {
        $queryModel = new QueryModel();

        $di = $this->createMock(ContainerInterface::class);

        $dbFactory = $this->createMock(Factory::class);

        $dbFactory->expects(self::once())
            ->method('makeQueryModel')
            ->willReturn($queryModel);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        self::assertSame($queryModel, $queueApi->makeQueryModel());
    }

    /**
     * @throws Throwable
     */
    public function testUuidToBytes() : void
    {
        $di = $this->createMock(ContainerInterface::class);

        $dbFactory = $this->createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        $uuidFactory = (new Factory())->uuidFactoryWithOrderedTimeCodec();

        $uuid = $uuidFactory->uuid1();

        self::assertEquals(
            $uuid->getBytes(),
            $queueApi->uuidToBytes($uuid->toString())
        );
    }

    /**
     * @throws Throwable
     */
    public function testMakeActionQueueBatchModel() : void
    {
        $di = $this->createMock(ContainerInterface::class);

        $dbFactory = $this->createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        $model = $queueApi->makeActionQueueBatchModel(['name' => 'test name']);

        self::assertInstanceOf(ActionQueueBatchModel::class, $model);

        self::assertEquals('test name', $model->name());
    }

    /**
     * @throws Throwable
     */
    public function testMakeActionQueueItemModel() : void
    {
        $di = $this->createMock(ContainerInterface::class);

        $dbFactory = $this->createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        $model = $queueApi->makeActionQueueItemModel(['class' => 'testClass']);

        self::assertInstanceOf(ActionQueueItemModel::class, $model);

        self::assertEquals('testClass', $model->class());
    }

    /**
     * @throws Throwable
     */
    public function testAddToQueue() : void
    {
        $model = $this->createMock(ActionQueueBatchModelInterface::class);

        $service = $this->createMock(AddBatchToQueueService::class);

        $service->expects(self::once())
            ->method('add')
            ->with(self::equalTo($model));

        $di = $this->createMock(ContainerInterface::class);

        $di->expects(self::once())
            ->method('get')
            ->with(self::equalTo(AddBatchToQueueService::class))
            ->willReturn($service);

        $dbFactory = $this->createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        /** @noinspection PhpParamsInspection */
        $queueApi->addToQueue($model);
    }

    /**
     * @throws Throwable
     */
    public function testGetNextQueueItem() : void
    {
        $actionQueueItemModel = self::createMock(ActionQueueItemModelInterface::class);

         $service = $this->createMock(GetNextQueueItemService::class);

        $service->expects(self::once())
            ->method('get')
            ->with(self::equalTo(true))
            ->willReturn($actionQueueItemModel);

        $di = $this->createMock(ContainerInterface::class);

        $di->expects(self::once())
            ->method('get')
            ->with(self::equalTo(GetNextQueueItemService::class))
            ->willReturn($service);

        $dbFactory = $this->createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        self::assertSame(
            $actionQueueItemModel,
            $queueApi->getNextQueueItem(true)
        );
    }

    /**
     * @throws Throwable
     */
    public function testMarkAsStoppedDueToError() : void
    {
        $model = $this->createMock(ActionQueueItemModelInterface::class);

        $service = $this->createMock(MarkAsStoppedDueToErrorService::class);

        $service->expects(self::once())
            ->method('markStopped')
            ->with(self::equalTo($model));

        $di = $this->createMock(ContainerInterface::class);

        $di->expects(self::once())
            ->method('get')
            ->with(self::equalTo(MarkAsStoppedDueToErrorService::class))
            ->willReturn($service);

        $dbFactory = $this->createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        /** @noinspection PhpParamsInspection */
        $queueApi->markAsStoppedDueToError($model);
    }

    /**
     * @throws Throwable
     */
    public function testMarkItemAsRun() : void
    {
        $model = $this->createMock(ActionQueueItemModelInterface::class);

        $service = $this->createMock(MarkItemAsRunService::class);

        $service->expects(self::once())
            ->method('markAsRun')
            ->with(self::equalTo($model));

        $di = $this->createMock(ContainerInterface::class);

        $di->expects(self::once())
            ->method('get')
            ->with(self::equalTo(MarkItemAsRunService::class))
            ->willReturn($service);

        $dbFactory = $this->createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        /** @noinspection PhpParamsInspection */
        $queueApi->markItemAsRun($model);
    }

    /**
     * @throws Throwable
     */
    public function testUpdateActionQueue() : void
    {
        $guid = 'asdftest';

        $service = $this->createMock(UpdateActionQueueService::class);

        $service->expects(self::once())
            ->method('update')
            ->with(self::equalTo($guid));

        $di = $this->createMock(ContainerInterface::class);

        $di->expects(self::once())
            ->method('get')
            ->with(self::equalTo(UpdateActionQueueService::class))
            ->willReturn($service);

        $dbFactory = $this->createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        /** @noinspection PhpParamsInspection */
        $queueApi->updateActionQueue($guid);
    }

    /**
     * @throws Throwable
     */
    public function testFetchOneBatchNoQueryModel() : void
    {
        $returnBatchModel = $this->createMock(ActionQueueBatchModelInterface::class);

        $queryModel = $this->createMock(QueryModelInterface::class);

        $queryModel->expects(self::once())
            ->method('addWhere')
            ->with(
                self::equalTo('is_finished'),
                self::equalTo(0)
            );

        $queryModel->expects(self::once())
            ->method('addOrder')
            ->with(
                self::equalTo('added_at'),
                self::equalTo('asc')
            );

        $queryModel->expects(self::once())
            ->method('limit')
            ->with(self::equalTo(1));

        $service = $this->createMock(FetchBatchesService::class);

        $service->expects(self::once())
            ->method('fetch')
            ->with(self::equalTo($queryModel))
            ->willReturn([$returnBatchModel]);

        $di = $this->createMock(ContainerInterface::class);

        $di->expects(self::once())
            ->method('get')
            ->with(self::equalTo(FetchBatchesService::class))
            ->willReturn($service);

        $dbFactory = $this->createMock(Factory::class);

        $dbFactory->expects(self::once())
            ->method('makeQueryModel')
            ->willReturn($queryModel);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        self::assertSame(
            $returnBatchModel,
            $queueApi->fetchOneBatch()
        );
    }

    /**
     * @throws Throwable
     */
    public function testFetchOneBatchWithQueryModel() : void
    {
        $queryModel = $this->createMock(QueryModelInterface::class);

        $queryModel->expects(self::once())
            ->method('limit')
            ->with(self::equalTo(1));

        $service = $this->createMock(FetchBatchesService::class);

        $service->expects(self::once())
            ->method('fetch')
            ->with(self::equalTo($queryModel))
            ->willReturn([]);

        $di = $this->createMock(ContainerInterface::class);

        $di->expects(self::once())
            ->method('get')
            ->with(self::equalTo(FetchBatchesService::class))
            ->willReturn($service);

        $dbFactory = $this->createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        /** @noinspection PhpParamsInspection */
        self::assertNull($queueApi->fetchOneBatch($queryModel));
    }

    /**
     * @throws Throwable
     */
    public function testFetchAllBatchesNoQueryModel() : void
    {
        $returnBatchModel = $this->createMock(ActionQueueBatchModelInterface::class);

        $queryModel = $this->createMock(QueryModelInterface::class);

        $queryModel->expects(self::once())
            ->method('addWhere')
            ->with(
                self::equalTo('is_finished'),
                self::equalTo(0)
            );

        $queryModel->expects(self::once())
            ->method('addOrder')
            ->with(
                self::equalTo('added_at'),
                self::equalTo('asc')
            );

        $queryModel->expects(self::never())
            ->method('limit');

        $service = $this->createMock(FetchBatchesService::class);

        $service->expects(self::once())
            ->method('fetch')
            ->with(self::equalTo($queryModel))
            ->willReturn([$returnBatchModel]);

        $di = $this->createMock(ContainerInterface::class);

        $di->expects(self::once())
            ->method('get')
            ->with(self::equalTo(FetchBatchesService::class))
            ->willReturn($service);

        $dbFactory = $this->createMock(Factory::class);

        $dbFactory->expects(self::once())
            ->method('makeQueryModel')
            ->willReturn($queryModel);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        self::assertSame(
            $returnBatchModel,
            $queueApi->fetchAllBatches()[0]
        );
    }

    /**
     * @throws Throwable
     */
    public function testFetchAllBatchesWithQueryModel() : void
    {
        $queryModel = $this->createMock(QueryModelInterface::class);

        $queryModel->expects(self::never())
            ->method('limit');

        $service = $this->createMock(FetchBatchesService::class);

        $service->expects(self::once())
            ->method('fetch')
            ->with(self::equalTo($queryModel))
            ->willReturn([]);

        $di = $this->createMock(ContainerInterface::class);

        $di->expects(self::once())
            ->method('get')
            ->with(self::equalTo(FetchBatchesService::class))
            ->willReturn($service);

        $dbFactory = $this->createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $queueApi = new QueueApi($di, $dbFactory);

        /** @noinspection PhpParamsInspection */
        self::assertEmpty($queueApi->fetchAllBatches($queryModel));
    }
}
