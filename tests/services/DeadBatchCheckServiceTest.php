<?php

declare(strict_types=1);

namespace corbomite\tests\services;

use Atlas\Mapper\MapperSelect;
use Atlas\Orm\Atlas;
use corbomite\db\Factory;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatch;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatchRecord;
use corbomite\queue\services\DeadBatchCheckService;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Throwable;

class DeadBatchCheckServiceTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testContinue() : void
    {
        $fiveMinutesFuture = new DateTimeImmutable(
            '+ 5 minutes',
            new DateTimeZone('UTC')
        );

        $record = $this->createMock(ActionQueueBatchRecord::class);

        $record->expects(self::at(0))
            ->method('__get')
            ->with(self::equalTo('assume_dead_after'))
            ->willReturn($fiveMinutesFuture->format('Y-m-d H:i:s'));

        $record->expects(self::at(1))
            ->method('__get')
            ->with(self::equalTo('assume_dead_after_time_zone'))
            ->willReturn('UTC');

        $mapperSelect = $this->createMock(MapperSelect::class);

        $mapperSelect->expects(self::at(0))
            ->method('where')
            ->with(self::equalTo('is_running = '), self::equalTo(1))
            ->willReturn($mapperSelect);

        $mapperSelect->expects(self::at(1))
            ->method('andWhere')
            ->with(self::equalTo('is_finished = '), self::equalTo(0))
            ->willReturn($mapperSelect);

        $mapperSelect->expects(self::at(2))
            ->method('fetchRecords')
            ->willReturn([$record]);

        $orm = $this->createMock(Atlas::class);

        $orm->expects(self::once())
            ->method('select')
            ->with(self::equalTo(ActionQueueBatch::class))
            ->willReturn($mapperSelect);

        $ormFactory = $this->createMock(Factory::class);

        $ormFactory->expects(self::once())
            ->method('makeOrm')
            ->willReturn($orm);

        /** @noinspection PhpParamsInspection */
        $service = new DeadBatchCheckService($ormFactory);

        $service();
    }

    /**
     * @throws Throwable
     */
    public function test() : void
    {
        $record = $this->createMock(ActionQueueBatchRecord::class);

        $record->expects(self::at(0))
            ->method('__get')
            ->with(self::equalTo('assume_dead_after'))
            ->willReturn('2019-04-21 20:44:41');

        $record->expects(self::at(1))
            ->method('__get')
            ->with(self::equalTo('assume_dead_after_time_zone'))
            ->willReturn('UTC');

        $record->expects(self::at(2))
            ->method('__get')
            ->with(self::equalTo('added_at'))
            ->willReturn('2019-04-21 20:39:41');

        $record->expects(self::at(3))
            ->method('__get')
            ->with(self::equalTo('added_at_time_zone'))
            ->willReturn('UTC');

        $record->expects(self::at(4))
            ->method('__get')
            ->with(self::equalTo('initial_assume_dead_after'))
            ->willReturn('2019-04-21 20:44:41');

        $record->expects(self::at(5))
            ->method('__get')
            ->with(self::equalTo('initial_assume_dead_after_time_zone'))
            ->willReturn('UTC');

        $record->expects(self::at(6))
            ->method('__set')
            ->with(
                self::equalTo('is_running'),
                self::equalTo(0)
            );

        $startTime = new DateTimeImmutable(
            '2019-04-21 20:39:41',
            new DateTimeZone('UTC')
        );

        $initialDeadTime = new DateTimeImmutable(
            '2019-04-21 20:44:41',
            new DateTimeZone('UTC')
        );

        $newAssumeDeadAfter = (new DateTimeImmutable(
            'now',
            new DateTimeZone('UTC')
        ))->add($startTime->diff($initialDeadTime));

        $record->expects(self::at(7))
            ->method('__set')
            ->with(
                self::equalTo('assume_dead_after'),
                self::equalTo($newAssumeDeadAfter->format('Y-m-d H:i:s'))
            );

        $record->expects(self::at(8))
            ->method('__set')
            ->with(
                self::equalTo('assume_dead_after_time_zone'),
                self::equalTo('UTC')
            );

        $mapperSelect = $this->createMock(MapperSelect::class);

        $mapperSelect->expects(self::at(0))
            ->method('where')
            ->with(self::equalTo('is_running = '), self::equalTo(1))
            ->willReturn($mapperSelect);

        $mapperSelect->expects(self::at(1))
            ->method('andWhere')
            ->with(self::equalTo('is_finished = '), self::equalTo(0))
            ->willReturn($mapperSelect);

        $mapperSelect->expects(self::at(2))
            ->method('fetchRecords')
            ->willReturn([$record]);

        $orm = $this->createMock(Atlas::class);

        $orm->expects(self::at(0))
            ->method('select')
            ->with(self::equalTo(ActionQueueBatch::class))
            ->willReturn($mapperSelect);

        $orm->expects(self::at(1))
            ->method('persist')
            ->with(self::equalTo($record));

        $ormFactory = $this->createMock(Factory::class);

        $ormFactory->expects(self::at(0))
            ->method('makeOrm')
            ->willReturn($orm);

        $ormFactory->expects(self::at(1))
            ->method('makeOrm')
            ->willReturn($orm);

        /** @noinspection PhpParamsInspection */
        $service = new DeadBatchCheckService($ormFactory);

        $service();
    }
}
