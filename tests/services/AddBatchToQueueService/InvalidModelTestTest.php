<?php

declare(strict_types=1);

namespace corbomite\tests\services\AddBatchToQueueService;

use corbomite\db\Factory;
use corbomite\queue\exceptions\InvalidActionQueueBatchModel;
use corbomite\queue\interfaces\ActionQueueBatchModelInterface;
use corbomite\queue\models\ActionQueueItemModel;
use corbomite\queue\services\AddBatchToQueueService;
use PHPUnit\Framework\TestCase;
use Throwable;

class InvalidModelTestTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testInvalidName() : void
    {
        $model = self::createMock(ActionQueueBatchModelInterface::class);

        $model->expects(self::once())
            ->method('name')
            ->willReturn('');

        $ormFactory = self::createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $service = new AddBatchToQueueService($ormFactory);

        $exception = null;

        try {
            /** @noinspection PhpParamsInspection */
            $service($model);
        } catch (InvalidActionQueueBatchModel $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidActionQueueBatchModel::class, $exception);

        self::assertEquals(
            'Invalid action queue batch model',
            $exception->getMessage()
        );
    }

    /**
     * @throws Throwable
     */
    public function testInvalidTitle() : void
    {
        $model = self::createMock(ActionQueueBatchModelInterface::class);

        $model->expects(self::once())
            ->method('name')
            ->willReturn('nameTest');

        $model->expects(self::once())
            ->method('title')
            ->willReturn('');

        $ormFactory = self::createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $service = new AddBatchToQueueService($ormFactory);

        $exception = null;

        try {
            /** @noinspection PhpParamsInspection */
            $service($model);
        } catch (InvalidActionQueueBatchModel $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidActionQueueBatchModel::class, $exception);

        self::assertEquals(
            'Invalid action queue batch model',
            $exception->getMessage()
        );
    }

    /**
     * @throws Throwable
     */
    public function testInvalidItems() : void
    {
        $model = self::createMock(ActionQueueBatchModelInterface::class);

        $model->expects(self::once())
            ->method('name')
            ->willReturn('nameTest');

        $model->expects(self::once())
            ->method('title')
            ->willReturn('titleTest');

        $model->expects(self::once())
            ->method('items')
            ->willReturn([]);

        $ormFactory = self::createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $service = new AddBatchToQueueService($ormFactory);

        $exception = null;

        try {
            /** @noinspection PhpParamsInspection */
            $service($model);
        } catch (InvalidActionQueueBatchModel $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidActionQueueBatchModel::class, $exception);

        self::assertEquals(
            'Invalid action queue batch model',
            $exception->getMessage()
        );
    }

    /**
     * @throws Throwable
     */
    public function testInvalidItemsNotObject() : void
    {
        $model = self::createMock(ActionQueueBatchModelInterface::class);

        $model->expects(self::at(0))
            ->method('name')
            ->willReturn('nameTest');

        $model->expects(self::at(1))
            ->method('title')
            ->willReturn('titleTest');

        $model->expects(self::at(2))
            ->method('items')
            ->willReturn(['testing123']);

        $model->expects(self::at(3))
            ->method('items')
            ->willReturn(['testing123']);

        $ormFactory = self::createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $service = new AddBatchToQueueService($ormFactory);

        $service->test = true;

        $exception = null;

        try {
            /** @noinspection PhpParamsInspection */
            $service($model);
        } catch (InvalidActionQueueBatchModel $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidActionQueueBatchModel::class, $exception);

        self::assertEquals(
            'Invalid action queue batch model',
            $exception->getMessage()
        );
    }

    /**
     * @throws Throwable
     */
    public function testInvalidItemsNotInstance() : void
    {
        $model = self::createMock(ActionQueueBatchModelInterface::class);

        $model->expects(self::at(0))
            ->method('name')
            ->willReturn('nameTest');

        $model->expects(self::at(1))
            ->method('title')
            ->willReturn('titleTest');

        $model->expects(self::at(2))
            ->method('items')
            ->willReturn([new NotInstance()]);

        $model->expects(self::at(3))
            ->method('items')
            ->willReturn([new NotInstance()]);

        $ormFactory = self::createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $service = new AddBatchToQueueService($ormFactory);

        $service->test = true;

        $exception = null;

        try {
            /** @noinspection PhpParamsInspection */
            $service($model);
        } catch (InvalidActionQueueBatchModel $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidActionQueueBatchModel::class, $exception);

        self::assertEquals(
            'Invalid action queue batch model',
            $exception->getMessage()
        );
    }

    /**
     * @throws Throwable
     */
    public function testInvalidItemHasNoClass() : void
    {
        $model = self::createMock(ActionQueueBatchModelInterface::class);

        $model->expects(self::at(0))
            ->method('name')
            ->willReturn('nameTest');

        $model->expects(self::at(1))
            ->method('title')
            ->willReturn('titleTest');

        $model->expects(self::at(2))
            ->method('items')
            ->willReturn([new ActionQueueItemModel()]);

        $model->expects(self::at(3))
            ->method('items')
            ->willReturn([new ActionQueueItemModel()]);

        $ormFactory = self::createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $service = new AddBatchToQueueService($ormFactory);

        $service->test = true;

        $exception = null;

        try {
            /** @noinspection PhpParamsInspection */
            $service($model);
        } catch (InvalidActionQueueBatchModel $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidActionQueueBatchModel::class, $exception);

        self::assertEquals(
            'Invalid action queue batch model',
            $exception->getMessage()
        );
    }

    /**
     * @throws Throwable
     */
    public function testInvalidItemMethodNotExists() : void
    {
        $itemModel = new ActionQueueItemModel();

        $itemModel->class(NotInstance::class);

        $itemModel->method('noMethod');

        $model = self::createMock(ActionQueueBatchModelInterface::class);

        $model->expects(self::at(0))
            ->method('name')
            ->willReturn('nameTest');

        $model->expects(self::at(1))
            ->method('title')
            ->willReturn('titleTest');

        $model->expects(self::at(2))
            ->method('items')
            ->willReturn([$itemModel]);

        $model->expects(self::at(3))
            ->method('items')
            ->willReturn([$itemModel]);

        $ormFactory = self::createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $service = new AddBatchToQueueService($ormFactory);

        $exception = null;

        try {
            /** @noinspection PhpParamsInspection */
            $service($model);
        } catch (InvalidActionQueueBatchModel $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidActionQueueBatchModel::class, $exception);

        self::assertEquals(
            'Invalid action queue batch model',
            $exception->getMessage()
        );
    }
}
