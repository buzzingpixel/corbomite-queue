<?php

declare(strict_types=1);

namespace corbomite\tests\services;

use Atlas\Mapper\MapperSelect;
use Atlas\Orm\Atlas;
use corbomite\db\Factory;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatchRecord;
use corbomite\queue\data\ActionQueueItem\ActionQueueItemRecord;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;
use corbomite\queue\services\MarkAsStoppedDueToErrorService;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * TODO: this test does not test that everything is accurate, it merely
 * runs the method with 100% code coverage.
 * This is still useful as it will catch any problems as
 * PHP versions change
 */
class MarkAsStoppedDueToErrorServiceTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testThrowsError() : void
    {
        $ormFactory = $this->createMock(Factory::class);

        $model = $this->createMock(ActionQueueItemModelInterface::class);

        /** @noinspection PhpParamsInspection */
        $service = new MarkAsStoppedDueToErrorService($ormFactory);

        /** @noinspection PhpParamsInspection */
        self::assertNull($service($model));
    }

    /**
     * @throws Throwable
     */
    public function test() : void
    {
        $batchRecord = $this->createMock(ActionQueueBatchRecord::class);

        $record = $this->createMock(ActionQueueItemRecord::class);

        $record->method('__get')
            ->willReturn($batchRecord);

        $mapperSelect = $this->createMock(MapperSelect::class);

        $mapperSelect->method('where')
            ->willReturn($mapperSelect);

        $mapperSelect->method('with')
            ->willReturn($mapperSelect);

        $mapperSelect->method('fetchRecord')
            ->willReturn($record);

        $orm = $this->createMock(Atlas::class);

        $orm->method('select')
            ->willReturn($mapperSelect);

        $ormFactory = $this->createMock(Factory::class);

        $ormFactory->method('makeOrm')
            ->willReturn($orm);

        $model = $this->createMock(ActionQueueItemModelInterface::class);

        /** @noinspection PhpParamsInspection */
        $service = new MarkAsStoppedDueToErrorService($ormFactory);

        /** @noinspection PhpParamsInspection */
        self::assertNull($service($model));
    }
}
