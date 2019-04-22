<?php

declare(strict_types=1);

namespace corbomite\tests\actions\RunQueueAction;

use corbomite\queue\actions\RunQueueAction;
use corbomite\queue\interfaces\QueueApiInterface;
use corbomite\queue\models\ActionQueueItemModel;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Throwable;
use function defined;

class DiDoesNotHaveQueueItemDoesNotThrowErrorTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function test() : void
    {
        $actionQueueItemModel = new ActionQueueItemModel([
            'class' => ActionItemDoesNotThrowError::class,
            'method' => 'customMethod',
        ]);

        $queueApi = self::createMock(QueueApiInterface::class);

        $queueApi->expects(self::once())
            ->method('getNextQueueItem')
            ->with(self::equalTo(true))
            ->willReturn($actionQueueItemModel);

        $queueApi->expects(self::once())
            ->method('markItemAsRun')
            ->with(self::equalTo($actionQueueItemModel));

        $di = self::createMock(ContainerInterface::class);

        $di->expects(self::at(0))
            ->method('get')
            ->with(self::equalTo(QueueApiInterface::class))
            ->willReturn($queueApi);

        $di->expects(self::at(1))
            ->method('has')
            ->with(self::equalTo(ActionItemDoesNotThrowError::class))
            ->willReturn(false);

        /** @noinspection PhpParamsInspection */
        $action = new RunQueueAction($di);

        self::assertNull($action());

        self::assertTrue(defined('CUSTOM_METHOD_ACTION_HAS_RUN'));

        self::assertTrue(CUSTOM_METHOD_ACTION_HAS_RUN);
    }
}
