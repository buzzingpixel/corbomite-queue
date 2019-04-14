<?php

declare(strict_types=1);

namespace corbomite\tests\actions\RunQueueAction;

use corbomite\queue\actions\RunQueueAction;
use corbomite\queue\interfaces\QueueApiInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Throwable;

class NoQueueItemTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function test() : void
    {
        $queueApi = self::createMock(QueueApiInterface::class);

        $queueApi->expects(self::once())
            ->method('getNextQueueItem')
            ->with(self::equalTo(true))
            ->willReturn(null);

        $di = self::createMock(ContainerInterface::class);

        $di->expects(self::once())
            ->method('get')
            ->with(self::equalTo(QueueApiInterface::class))
            ->willReturn($queueApi);

        /** @noinspection PhpParamsInspection */
        $action = new RunQueueAction($di);

        self::assertNull($action());
    }
}
