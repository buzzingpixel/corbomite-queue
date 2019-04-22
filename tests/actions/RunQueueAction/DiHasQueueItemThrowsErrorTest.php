<?php

declare(strict_types=1);

namespace corbomite\tests\actions\RunQueueAction;

use corbomite\queue\actions\RunQueueAction;
use corbomite\queue\interfaces\QueueApiInterface;
use corbomite\queue\models\ActionQueueItemModel;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Throwable;

class DiHasQueueItemThrowsErrorTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function test() : void
    {
        $actionQueueItemModel = new ActionQueueItemModel([
            'class' => ActionItemThrowsError::class,
        ]);

        $queueApi = self::createMock(QueueApiInterface::class);

        $queueApi->expects(self::once())
            ->method('getNextQueueItem')
            ->with(self::equalTo(true))
            ->willReturn($actionQueueItemModel);

        $queueApi->expects(self::once())
            ->method('markAsStoppedDueToError')
            ->with(self::equalTo($actionQueueItemModel));

        $di = self::createMock(ContainerInterface::class);

        $di->expects(self::at(0))
            ->method('get')
            ->with(self::equalTo(QueueApiInterface::class))
            ->willReturn($queueApi);

        $di->expects(self::at(1))
            ->method('has')
            ->with(self::equalTo(ActionItemThrowsError::class))
            ->willReturn(true);

        $di->expects(self::at(2))
            ->method('get')
            ->with(self::equalTo(ActionItemThrowsError::class))
            ->willReturn(new ActionItemThrowsError());

        /** @noinspection PhpParamsInspection */
        $action = new RunQueueAction($di);

        self::assertEquals(1, $action());
    }
}
