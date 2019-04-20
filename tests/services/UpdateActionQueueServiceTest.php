<?php

declare(strict_types=1);

namespace corbomite\tests\services;

use Atlas\Mapper\MapperSelect;
use Atlas\Orm\Atlas;
use corbomite\db\Factory;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatchRecord;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemRecord;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemSelect;
use corbomite\queue\interfaces\QueueApiInterface;
use corbomite\queue\services\UpdateActionQueueService;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * TODO: this test does not test that everything is accurate, it merely
 * runs the method with 100% code coverage.
 * This is still useful as it will catch any problems as
 * PHP versions change
 */
class UpdateActionQueueServiceTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testThrowsError() : void
    {
        $queueApi = $this->createMock(QueueApiInterface::class);

        $ormFactory = $this->createMock(Factory::class);

        /** @noinspection PhpParamsInspection */
        $service = new UpdateActionQueueService($queueApi, $ormFactory);

        self::assertNull($service('asdf'));
    }

    /**
     * @throws Throwable
     */
    public function testNoRecord() : void
    {
        $thisTest = $this;

        $mapperSelect = self::createMock(MapperSelect::class);

        $mapperSelect->method('where')
            ->willReturn($mapperSelect);

        $mapperSelect->method('with')
            ->willReturnCallback(static function ($with) use ($thisTest, $mapperSelect) {
                $with['action_queue_items']($thisTest->createMock(ActionQueueItemSelect::class));

                return $mapperSelect;
            });

        $orm = $this->createMock(Atlas::class);

        $orm->method('select')
            ->willReturn($mapperSelect);

        $queueApi = $this->createMock(QueueApiInterface::class);

        $ormFactory = $this->createMock(Factory::class);

        $ormFactory->method('makeOrm')
            ->willReturn($orm);

        /** @noinspection PhpParamsInspection */
        $service = new UpdateActionQueueService($queueApi, $ormFactory);

        self::assertNull($service('asdf'));
    }

    /**
     * @throws Throwable
     */
    public function testTotalRunIsFinished() : void
    {
        $actionQueueItemRecord = $this->createMock(ActionQueueItemRecord::class);

        // is_finished
        $actionQueueItemRecord->expects(self::at(0))
            ->method('__get')
            ->willReturn(true);

        $record = $this->createMock(ActionQueueBatchRecord::class);

        // action_queue_items
        $record->expects(self::at(0))
            ->method('__get')
            ->willReturn([$actionQueueItemRecord]);

        // is_finished
        $record->expects(self::at(1))
            ->method('__get')
            ->willReturn(true);

        $mapperSelect = self::createMock(MapperSelect::class);

        $mapperSelect->method('where')
            ->willReturn($mapperSelect);

        $mapperSelect->method('with')
            ->willReturn($mapperSelect);

        $mapperSelect->method('fetchRecord')
            ->willReturn($record);

        $orm = $this->createMock(Atlas::class);

        $orm->method('select')
            ->willReturn($mapperSelect);

        $queueApi = $this->createMock(QueueApiInterface::class);

        $ormFactory = $this->createMock(Factory::class);

        $ormFactory->method('makeOrm')
            ->willReturn($orm);

        /** @noinspection PhpParamsInspection */
        $service = new UpdateActionQueueService($queueApi, $ormFactory);

        self::assertNull($service('asdf'));
    }

    /**
     * @throws Throwable
     */
    public function testTotalRunIsNotFinished() : void
    {
        $actionQueueItemRecord = $this->createMock(ActionQueueItemRecord::class);

        // is_finished
        $actionQueueItemRecord->expects(self::at(0))
            ->method('__get')
            ->willReturn(true);

        $record = $this->createMock(ActionQueueBatchRecord::class);

        // action_queue_items
        $record->expects(self::at(0))
            ->method('__get')
            ->willReturn([$actionQueueItemRecord]);

        // is_finished
        $record->expects(self::at(1))
            ->method('__get')
            ->willReturn(false);

        // is_finished
        $record->expects(self::at(2))
            ->method('__get')
            ->willReturn(false);

        $mapperSelect = self::createMock(MapperSelect::class);

        $mapperSelect->method('where')
            ->willReturn($mapperSelect);

        $mapperSelect->method('with')
            ->willReturn($mapperSelect);

        $mapperSelect->method('fetchRecord')
            ->willReturn($record);

        $orm = $this->createMock(Atlas::class);

        $orm->method('select')
            ->willReturn($mapperSelect);

        $queueApi = $this->createMock(QueueApiInterface::class);

        $ormFactory = $this->createMock(Factory::class);

        $ormFactory->method('makeOrm')
            ->willReturn($orm);

        /** @noinspection PhpParamsInspection */
        $service = new UpdateActionQueueService($queueApi, $ormFactory);

        self::assertNull($service('asdf'));
    }

    /**
     * @throws Throwable
     */
    public function test() : void
    {
        $actionQueueItemRecord = $this->createMock(ActionQueueItemRecord::class);

        // is_finished
        $actionQueueItemRecord->expects(self::at(0))
            ->method('__get')
            ->willReturn(false);

        $record = $this->createMock(ActionQueueBatchRecord::class);

        // action_queue_items
        $record->expects(self::at(0))
            ->method('__get')
            ->willReturn([$actionQueueItemRecord]);

        // is_finished
        $record->expects(self::at(1))
            ->method('__get')
            ->willReturn(false);

        $mapperSelect = self::createMock(MapperSelect::class);

        $mapperSelect->method('where')
            ->willReturn($mapperSelect);

        $mapperSelect->method('with')
            ->willReturn($mapperSelect);

        $mapperSelect->method('fetchRecord')
            ->willReturn($record);

        $orm = $this->createMock(Atlas::class);

        $orm->method('select')
            ->willReturn($mapperSelect);

        $queueApi = $this->createMock(QueueApiInterface::class);

        $ormFactory = $this->createMock(Factory::class);

        $ormFactory->method('makeOrm')
            ->willReturn($orm);

        /** @noinspection PhpParamsInspection */
        $service = new UpdateActionQueueService($queueApi, $ormFactory);

        self::assertNull($service('asdf'));
    }
}
