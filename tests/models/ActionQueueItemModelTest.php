<?php

declare(strict_types=1);

namespace corbomite\tests\models;

use corbomite\queue\models\ActionQueueItemModel;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Throwable;

class ActionQueueItemModelTest extends TestCase
{
    public function testIsFinished() : void
    {
        $model = new ActionQueueItemModel();

        self::assertFalse($model->isFinished());

        self::assertTrue($model->isFinished(true));

        self::assertTrue($model->isFinished());

        self::assertFalse($model->isFinished(false));

        self::assertFalse($model->isFinished());

        $model = new ActionQueueItemModel(['isFinished' => true]);

        self::assertTrue($model->isFinished());
    }

    /**
     * @throws Throwable
     */
    public function testFinishedAt() : void
    {
        self::assertNull((new ActionQueueItemModel())->finishedAt());

        $dateTime = new DateTime('-20 years', new DateTimeZone('America/Adak'));

        $model = new ActionQueueItemModel();

        self::assertEquals(
            $dateTime->getTimestamp(),
            $model->finishedAt($dateTime)->getTimestamp()
        );

        self::assertEquals(
            $dateTime->getTimestamp(),
            $model->finishedAt()->getTimestamp()
        );

        self::assertEquals(
            (new DateTime())->getTimezone()->getName(),
            $model->finishedAt()->getTimezone()->getName()
        );

        self::assertInstanceOf(DateTimeImmutable::class, $model->finishedAt());
    }

    public function testClass() : void
    {
        $model = new ActionQueueItemModel();

        self::assertEmpty($model->class());

        self::assertEquals('testVal', $model->class('testVal'));

        self::assertEquals('testVal', $model->class());

        $model = new ActionQueueItemModel(['class' => 'fromConstructor']);

        self::assertEquals('fromConstructor', $model->class());
    }

    public function testMethod() : void
    {
        $model = new ActionQueueItemModel();

        self::assertEquals('__invoke', $model->method());

        self::assertEquals('testVal', $model->method('testVal'));

        self::assertEquals('testVal', $model->method());

        $model = new ActionQueueItemModel(['method' => 'fromConstructor']);

        self::assertEquals('fromConstructor', $model->method());
    }

    public function testContext() : void
    {
        $model = new ActionQueueItemModel();

        self::assertIsArray($model->context());

        self::assertEmpty($model->context());

        $test = ['foo' => 'bar'];

        self::assertEquals($test, $model->context($test));

        self::assertEquals($test, $model->context());

        $test2 = ['baz' => 'foo'];

        $model = new ActionQueueItemModel(['context' => $test2]);

        self::assertEquals($test2, $model->context());

        self::assertEquals($test, $model->context($test));

        self::assertEquals($test, $model->context());
    }
}
