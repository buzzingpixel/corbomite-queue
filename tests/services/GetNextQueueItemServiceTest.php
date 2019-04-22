<?php

declare(strict_types=1);

namespace corbomite\tests\services;

use Atlas\Mapper\MapperSelect;
use Atlas\Orm\Atlas;
use corbomite\db\Factory;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatchRecord;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemRecord;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemRecordSet;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemSelect;
use corbomite\queue\services\GetNextQueueItemService;
use Exception;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * TODO: this test does not test that everything is accurate, it merely
 * runs the method with 100% code coverage.
 * This is still useful as it will catch any problems as
 * PHP versions change
 */
class GetNextQueueItemServiceTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testThrows() : void
    {
        $ormFactory = self::createMock(Factory::class);

        $ormFactory->method('makeOrm')
            ->willThrowException(new Exception('test exception'));

        /** @noinspection PhpParamsInspection */
        $service = new GetNextQueueItemService($ormFactory);

        self::assertNull($service(true));
    }

    /**
     * @throws Throwable
     */
    public function testNoRecord() : void
    {
        $test = $this;

        $mapperSelect = self::createMock(MapperSelect::class);

        $mapperSelect->method('where')
            ->willReturn($mapperSelect);

        $mapperSelect->method('with')
            ->willReturnCallback(static function ($with) use ($test, $mapperSelect) {
                $actionItemSelect = $test->createMock(ActionQueueItemSelect::class);

                $actionItemSelect->method('where')
                    ->willReturn($actionItemSelect);

                $actionItemSelect->method('limit')
                    ->willReturn($actionItemSelect);

                $actionItemSelect->method('orderBy')
                    ->willReturn($actionItemSelect);

                $with['action_queue_items']($actionItemSelect);

                return $mapperSelect;
            });

        $mapperSelect->method('orderBy')
            ->willReturn($mapperSelect);

        $mapperSelect->method('fetchRecord')
            ->willReturn(null);

        $orm = self::createMock(Atlas::class);

        $orm->method('select')
            ->willReturn($mapperSelect);

        $ormFactory = self::createMock(Factory::class);

        $ormFactory->method('makeOrm')
            ->willReturn($orm);

        /** @noinspection PhpParamsInspection */
        $service = new GetNextQueueItemService($ormFactory);

        self::assertNull($service(true));
    }

    /**
     * @throws Throwable
     */
    public function testNoItem() : void
    {
        $recordSet = self::createMock(ActionQueueItemRecordSet::class);

        $recordSet->method('getOneBy')
            ->willReturn(null);

        $record = self::createMock(ActionQueueBatchRecord::class);

        $record->expects(self::at(0))
            ->method('__get')
            ->willReturn($recordSet);

        $mapperSelect = self::createMock(MapperSelect::class);

        $mapperSelect->method('where')
            ->willReturn($mapperSelect);

        $mapperSelect->method('with')
            ->willReturn($mapperSelect);

        $mapperSelect->method('orderBy')
            ->willReturn($mapperSelect);

        $mapperSelect->method('fetchRecord')
            ->willReturn($record);

        $orm = self::createMock(Atlas::class);

        $orm->method('select')
            ->willReturn($mapperSelect);

        $ormFactory = self::createMock(Factory::class);

        $ormFactory->method('makeOrm')
            ->willReturn($orm);

        /** @noinspection PhpParamsInspection */
        $service = new GetNextQueueItemService($ormFactory);

        self::assertNull($service(true));
    }

    /**
     * @throws Throwable
     */
    public function test() : void
    {
        $uuidFactory = (new Factory())->uuidFactoryWithOrderedTimeCodec();

        $uuid = $uuidFactory->uuid1();

        $itemRecord = self::createMock(ActionQueueItemRecord::class);

        // guid
        $itemRecord->expects(self::at(0))
            ->method('__get')
            ->willReturn($uuid->getBytes());

        // class
        $itemRecord->expects(self::at(1))
            ->method('__get')
            ->willReturn('classTest');

        // method
        $itemRecord->expects(self::at(2))
            ->method('__get')
            ->willReturn('methodTest');

        // context
        $itemRecord->expects(self::at(3))
            ->method('__get')
            ->willReturn('');

        $recordSet = self::createMock(ActionQueueItemRecordSet::class);

        $recordSet->method('getOneBy')
            ->willReturn($itemRecord);

        $record = self::createMock(ActionQueueBatchRecord::class);

        // action_queue_items
        $record->expects(self::at(0))
            ->method('__get')
            ->willReturn($recordSet);

        // has_started
        $record->expects(self::at(1))
            ->method('__get')
            ->willReturn(false);

        $mapperSelect = self::createMock(MapperSelect::class);

        $mapperSelect->method('where')
            ->willReturn($mapperSelect);

        $mapperSelect->method('with')
            ->willReturn($mapperSelect);

        $mapperSelect->method('orderBy')
            ->willReturn($mapperSelect);

        $mapperSelect->method('fetchRecord')
            ->willReturn($record);

        $orm = self::createMock(Atlas::class);

        $orm->method('select')
            ->willReturn($mapperSelect);

        $ormFactory = self::createMock(Factory::class);

        $ormFactory->method('makeOrm')
            ->willReturn($orm);

        /** @noinspection PhpParamsInspection */
        $service = new GetNextQueueItemService($ormFactory);

        self::assertNotNull($service(true));
    }
}
