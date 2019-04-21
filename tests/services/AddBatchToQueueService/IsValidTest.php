<?php

declare(strict_types=1);

namespace corbomite\tests\services\AddBatchToQueueService;

use Atlas\Mapper\RecordSet;
use corbomite\db\Factory;
use corbomite\db\Orm;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatch;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatchRecord;
use corbomite\queue\data\ActionQueueItem\ActionQueueItem;
use corbomite\queue\interfaces\ActionQueueBatchModelInterface;
use corbomite\queue\models\ActionQueueItemModel;
use corbomite\queue\services\AddBatchToQueueService;
use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Throwable;
use function json_encode;

class IsValidTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function test() : void
    {
        $testContext = [
            'foo' => 'bar',
            'baz' => 'foo',
        ];

        $itemModel = self::createMock(ActionQueueItemModel::class);

        $itemModel
            ->expects(self::any())
            ->method('getGuidAsBytes')
            ->willReturn('itemGuidAsBytes');

        $itemModel
            ->expects(self::any())
            ->method('class')
            ->willReturn(NotInstance::class);

        $itemModel
            ->expects(self::any())
            ->method('method')
            ->willReturn('__invoke');

        $itemModel
            ->expects(self::any())
            ->method('context')
            ->willReturn($testContext);

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

        $model->expects(self::at(4))
            ->method('getGuidAsBytes')
            ->willReturn('getGuidAsBytesTest');

        $model->expects(self::at(5))
            ->method('items')
            ->willReturn([$itemModel]);

        $model->expects(self::at(6))
            ->method('name')
            ->willReturn('nameTest');

        $model->expects(self::at(7))
            ->method('title')
            ->willReturn('titleTest');

        $dateTimeAssumeDead = new DateTime('+5 minutes');
        $dateTimeAssumeDead->setTimezone(new DateTimeZone('UTC'));

        $model->expects(self::at(8))
            ->method('assumeDeadAfter')
            ->willReturn($dateTimeAssumeDead);

        $model->expects(self::at(9))
            ->method('assumeDeadAfter')
            ->willReturn($dateTimeAssumeDead);

        $modelContext = [
            'bar' => 'baz',
            'foo' => 'bar',
        ];

        $model->expects(self::at(10))
            ->method('context')
            ->willReturn($modelContext);

        $recordSet = self::createMock(RecordSet::class);

        $recordSet->expects(self::once())
            ->method('appendNew')
            ->with([
                'guid' => 'itemGuidAsBytes',
                'action_queue_batch_guid' => 'getGuidAsBytesTest',
                'order_to_run' => 1,
                'is_finished' => false,
                'finished_at' => null,
                'finished_at_time_zone' => null,
                'class' => NotInstance::class,
                'method' => '__invoke',
                'context' => json_encode($testContext),
            ]);

        $record = self::createMock(ActionQueueBatchRecord::class);

        $record->expects(self::at(0))
            ->method('__set')
            ->with(
                self::equalTo('guid'),
                self::equalTo('getGuidAsBytesTest')
            );

        $record->expects(self::at(1))
            ->method('__set')
            ->with(
                self::equalTo('name'),
                self::equalTo('nameTest')
            );

        $record->expects(self::at(2))
            ->method('__set')
            ->with(
                self::equalTo('title'),
                self::equalTo('titleTest')
            );

        $record->expects(self::at(3))
            ->method('__set')
            ->with(
                self::equalTo('has_started'),
                self::equalTo(false)
            );

        $record->expects(self::at(4))
            ->method('__set')
            ->with(
                self::equalTo('is_running'),
                self::equalTo(false)
            );

        $record->expects(self::at(5))
            ->method('__set')
            ->with(
                self::equalTo('assume_dead_after'),
                self::equalTo($dateTimeAssumeDead->format('Y-m-d H:i:s'))
            );

        $record->expects(self::at(6))
            ->method('__set')
            ->with(
                self::equalTo('assume_dead_after_time_zone'),
                self::equalTo($dateTimeAssumeDead->getTimezone()->getName())
            );

        $record->expects(self::at(7))
            ->method('__get')
            ->with(self::equalTo('assume_dead_after'))
            ->willReturn('assume_dead_after_test');

        $record->expects(self::at(8))
            ->method('__set')
            ->with(
                self::equalTo('initial_assume_dead_after'),
                self::equalTo('assume_dead_after_test')
            );

        $record->expects(self::at(9))
            ->method('__get')
            ->with(self::equalTo('assume_dead_after_time_zone'))
            ->willReturn('initial_assume_dead_after_time_zone_test');

        $record->expects(self::at(10))
            ->method('__set')
            ->with(
                self::equalTo('initial_assume_dead_after_time_zone'),
                self::equalTo('initial_assume_dead_after_time_zone_test')
            );

        $record->expects(self::at(11))
            ->method('__set')
            ->with(
                self::equalTo('is_finished'),
                self::equalTo(false)
            );

        $record->expects(self::at(12))
            ->method('__set')
            ->with(
                self::equalTo('finished_due_to_error'),
                self::equalTo(false)
            );

        $record->expects(self::at(13))
            ->method('__set')
            ->with(
                self::equalTo('percent_complete'),
                self::equalTo(0)
            );

        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $record->expects(self::at(14))
            ->method('__set')
            ->with(
                self::equalTo('added_at'),
                self::equalTo($dateTime->format('Y-m-d H:i:s'))
            );

        $record->expects(self::at(15))
            ->method('__set')
            ->with(
                self::equalTo('added_at_time_zone'),
                self::equalTo($dateTime->getTimezone()->getName())
            );

        $record->expects(self::at(16))
            ->method('__set')
            ->with(
                self::equalTo('finished_at'),
                self::equalTo(null)
            );

        $record->expects(self::at(17))
            ->method('__set')
            ->with(
                self::equalTo('finished_at_time_zone'),
                self::equalTo(null)
            );

        $record->expects(self::at(18))
            ->method('__set')
            ->with(
                self::equalTo('context'),
                self::equalTo(json_encode($modelContext))
            );

        $record->expects(self::at(19))
            ->method('__set')
            ->with(
                self::equalTo('action_queue_items'),
                self::equalTo($recordSet)
            );

        $orm = self::createMock(Orm::class);

        $orm->expects(self::once())
            ->method('newRecordSet')
            ->with(ActionQueueItem::class)
            ->willReturn($recordSet);

        $orm->expects(self::once())
            ->method('newRecord')
            ->with(ActionQueueBatch::class)
            ->willReturn($record);

        $orm->expects(self::once())
            ->method('persist')
            ->with($record);

        $ormFactory = self::createMock(Factory::class);

        $ormFactory->expects(self::once())
            ->method('makeOrm')
            ->willReturn($orm);

        /** @noinspection PhpParamsInspection */
        $service = new AddBatchToQueueService($ormFactory);

        /** @noinspection PhpParamsInspection */
        $service($model);
    }
}
