<?php

declare(strict_types=1);

namespace corbomite\tests\services;

use Atlas\Mapper\MapperSelect;
use corbomite\db\Factory;
use corbomite\db\interfaces\BuildQueryInterface;
use corbomite\db\interfaces\QueryModelInterface;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatch;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatchRecord;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemRecord;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemSelect;
use corbomite\queue\models\ActionQueueBatchModel;
use corbomite\queue\models\ActionQueueItemModel;
use corbomite\queue\services\FetchBatchesService;
use PHPUnit\Framework\TestCase;
use Throwable;

class FetchBatchesServiceTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function test() : void
    {
        $uuidFactory = (new Factory())->uuidFactoryWithOrderedTimeCodec();

        $actionQueueItemRecordUuid = $uuidFactory->uuid1();

        $actionQueueItemRecord = self::createMock(ActionQueueItemRecord::class);

        $actionQueueItemRecord->expects(self::at(0))
            ->method('__get')
            ->with(self::equalTo('guid'))
            ->willReturn($actionQueueItemRecordUuid->getBytes());

        $actionQueueItemRecord->expects(self::at(1))
            ->method('__get')
            ->with(self::equalTo('is_finished'))
            ->willReturn(1);

        $actionQueueItemRecord->expects(self::at(2))
            ->method('__get')
            ->with(self::equalTo('finished_at'))
            ->willReturn('2010-04-11 22:22:49');

        $actionQueueItemRecord->expects(self::at(3))
            ->method('__get')
            ->with(self::equalTo('finished_at'))
            ->willReturn('2010-04-11 22:22:49');

        $actionQueueItemRecord->expects(self::at(4))
            ->method('__get')
            ->with(self::equalTo('finished_at_time_zone'))
            ->willReturn('US/Eastern');

        $actionQueueBatchRecordUuid = $uuidFactory->uuid1();

        $actionQueueBatchRecord = self::createMock(ActionQueueBatchRecord::class);

        $actionQueueBatchRecord->expects(self::at(0))
            ->method('__get')
            ->with(self::equalTo('guid'))
            ->willReturn($actionQueueBatchRecordUuid->getBytes());

        $actionQueueBatchRecord->expects(self::at(1))
            ->method('__get')
            ->with(self::equalTo('name'))
            ->willReturn('recordNameTest');

        $actionQueueBatchRecord->expects(self::at(2))
            ->method('__get')
            ->with(self::equalTo('title'))
            ->willReturn('recordTitleTest');

        $actionQueueBatchRecord->expects(self::at(3))
            ->method('__get')
            ->with(self::equalTo('has_started'))
            ->willReturn(1);

        $actionQueueBatchRecord->expects(self::at(4))
            ->method('__get')
            ->with(self::equalTo('is_running'))
            ->willReturn(1);

        $actionQueueBatchRecord->expects(self::at(5))
            ->method('__get')
            ->with(self::equalTo('assume_dead_after'))
            ->willReturn('2009-04-11 22:22:49');

        $actionQueueBatchRecord->expects(self::at(6))
            ->method('__get')
            ->with(self::equalTo('assume_dead_after_time_zone'))
            ->willReturn('US/Central');

        $actionQueueBatchRecord->expects(self::at(7))
            ->method('__get')
            ->with(self::equalTo('is_finished'))
            ->willReturn(1);

        $actionQueueBatchRecord->expects(self::at(8))
            ->method('__get')
            ->with(self::equalTo('percent_complete'))
            ->willReturn(52);

        $actionQueueBatchRecord->expects(self::at(9))
            ->method('__get')
            ->with(self::equalTo('added_at'))
            ->willReturn('2019-04-11 22:22:49');

        $actionQueueBatchRecord->expects(self::at(10))
            ->method('__get')
            ->with(self::equalTo('added_at_time_zone'))
            ->willReturn('US/Eastern');

        $actionQueueBatchRecord->expects(self::at(11))
            ->method('__get')
            ->with(self::equalTo('finished_at'))
            ->willReturn('2016-04-11 12:22:49');

        $actionQueueBatchRecord->expects(self::at(12))
            ->method('__get')
            ->with(self::equalTo('finished_at'))
            ->willReturn('2016-04-11 12:22:49');

        $actionQueueBatchRecord->expects(self::at(13))
            ->method('__get')
            ->with(self::equalTo('finished_at_time_zone'))
            ->willReturn('US/Central');

        $actionQueueBatchRecord->expects(self::at(14))
            ->method('__get')
            ->with(self::equalTo('action_queue_items'))
            ->willReturn([$actionQueueItemRecord]);

        $actionQueueItemSelect = self::createMock(ActionQueueItemSelect::class);

        $actionQueueItemSelect->expects(self::at(0))
            ->method('orderBy')
            ->with(self::equalTo('order_to_run ASC'));

        $mapperSelect = self::createMock(MapperSelect::class);

        $mapperSelect->expects(self::at(0))
            ->method('with')
            ->willReturnCallback(static function ($with) use ($actionQueueItemSelect, $mapperSelect) {
                self::assertArrayHasKey('action_queue_items', $with);

                $with['action_queue_items']($actionQueueItemSelect);

                return $mapperSelect;
            });

        $mapperSelect->expects(self::at(1))
            ->method('fetchRecords')
            ->willReturn([$actionQueueBatchRecord]);

        $params = self::createMock(QueryModelInterface::class);

        $buildQuery = self::createMock(BuildQueryInterface::class);

        $buildQuery->expects(self::at(0))
            ->method('build')
            ->with(
                self::equalTo(ActionQueueBatch::class),
                self::equalTo($params)
            )
            ->willReturn($mapperSelect);

        /** @noinspection PhpParamsInspection */
        $service = new FetchBatchesService($buildQuery);

        /** @noinspection PhpParamsInspection */
        $result = $service($params);

        $actionQueueBatchModel = $result[0];

        self::assertInstanceOf(
            ActionQueueBatchModel::class,
            $actionQueueBatchModel
        );

        self::assertEquals(
            $actionQueueBatchRecordUuid->toString(),
            $actionQueueBatchModel->guid()
        );

        self::assertEquals(
            'recordNameTest',
            $actionQueueBatchModel->name()
        );

        self::assertEquals(
            'recordTitleTest',
            $actionQueueBatchModel->title()
        );

        self::assertTrue($actionQueueBatchModel->hasStarted());
        self::assertTrue($actionQueueBatchModel->isFinished());

        self::assertEquals(
            (float) 52,
            $actionQueueBatchModel->percentComplete()
        );

        self::assertEquals(
            '2019-04-11 22:22:49',
            $actionQueueBatchModel->addedAt()->format('Y-m-d H:i:s')
        );

        self::assertEquals(
            'US/Eastern',
            $actionQueueBatchModel->addedAt()->getTimezone()->getName()
        );

        self::assertEquals(
            '2016-04-11 12:22:49',
            $actionQueueBatchModel->finishedAt()->format('Y-m-d H:i:s')
        );

        self::assertEquals(
            'US/Central',
            $actionQueueBatchModel->finishedAt()->getTimezone()->getName()
        );

        $items = $actionQueueBatchModel->items();

        self::assertIsArray($items);

        self::assertCount(1, $items);

        $item = $items[0];

        self::assertInstanceOf(ActionQueueItemModel::class, $item);

        self::assertEquals(
            $actionQueueItemRecordUuid->toString(),
            $item->guid()
        );

        self::assertTrue($item->isFinished());

        self::assertEquals(
            '2010-04-11 22:22:49',
            $item->finishedAt()->format('Y-m-d H:i:s')
        );

        self::assertEquals(
            'US/Eastern',
            $item->finishedAt()->getTimezone()->getName()
        );
    }
}
