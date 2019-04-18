<?php

declare(strict_types=1);

namespace corbomite\tests\models;

use corbomite\queue\models\ActionQueueBatchModel;
use corbomite\queue\models\ActionQueueItemModel;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Throwable;

class ActionQueueBatchModelTest extends TestCase
{
    public function testName() : void
    {
        $model = new ActionQueueBatchModel();

        self::assertEmpty($model->name());

        self::assertEquals('testVal', $model->name('testVal'));

        self::assertEquals('testVal', $model->name());

        $model = new ActionQueueBatchModel(['name' => 'fromConstructor']);

        self::assertEquals('fromConstructor', $model->name());
    }

    public function testTitle() : void
    {
        $model = new ActionQueueBatchModel();

        self::assertEmpty($model->title());

        self::assertEquals('testVal', $model->title('testVal'));

        self::assertEquals('testVal', $model->title());

        $model = new ActionQueueBatchModel(['title' => 'fromConstructor']);

        self::assertEquals('fromConstructor', $model->title());
    }

    public function testHasStarted() : void
    {
        $model = new ActionQueueBatchModel();

        self::assertFalse($model->hasStarted());

        self::assertTrue($model->hasStarted(true));

        self::assertTrue($model->hasStarted());

        self::assertFalse($model->hasStarted(false));

        self::assertFalse($model->hasStarted());

        $model = new ActionQueueBatchModel(['hasStarted' => true]);

        self::assertTrue($model->hasStarted());
    }

    public function testIsFinished() : void
    {
        $model = new ActionQueueBatchModel();

        self::assertFalse($model->isFinished());

        self::assertTrue($model->isFinished(true));

        self::assertTrue($model->isFinished());

        self::assertFalse($model->isFinished(false));

        self::assertFalse($model->isFinished());

        $model = new ActionQueueBatchModel(['isFinished' => true]);

        self::assertTrue($model->isFinished());
    }

    public function testPercentComplete() : void
    {
        $model = new ActionQueueBatchModel();

        self::assertEquals(0.0, $model->percentComplete());

        self::assertEquals(1.7, $model->percentComplete(1.7));

        self::assertEquals(1.7, $model->percentComplete());

        $model = new ActionQueueBatchModel(['percentComplete' => 8.9]);

        self::assertEquals(8.9, $model->percentComplete());
    }

    /**
     * @throws Throwable
     */
    public function testAddedAt() : void
    {
        self::assertNull((new ActionQueueBatchModel())->addedAt());

        $dateTime = new DateTime('+20 years', new DateTimeZone('America/Adak'));

        $model = new ActionQueueBatchModel();

        self::assertEquals(
            $dateTime->getTimestamp(),
            $model->addedAt($dateTime)->getTimestamp()
        );

        self::assertEquals(
            $dateTime->getTimestamp(),
            $model->addedAt()->getTimestamp()
        );

        self::assertEquals(
            (new DateTime())->getTimezone()->getName(),
            $model->addedAt()->getTimezone()->getName()
        );

        self::assertInstanceOf(DateTimeImmutable::class, $model->addedAt());
    }

    /**
     * @throws Throwable
     */
    public function testFinishedAt() : void
    {
        self::assertNull((new ActionQueueBatchModel())->finishedAt());

        $dateTime = new DateTime('-20 years', new DateTimeZone('America/Adak'));

        $model = new ActionQueueBatchModel();

        self::assertEquals(
            $dateTime->getTimestamp(),
            $model->finishedAt($dateTime)->getTimestamp()
        );

        self::assertEquals(
            $dateTime->getTimestamp(),
            $model->finishedAt()->getTimestamp()
        );

        self::assertEquals(
            'America/Adak',
            $model->finishedAt()->getTimezone()->getName()
        );

        self::assertInstanceOf(DateTimeImmutable::class, $model->finishedAt());
    }

    public function testContext() : void
    {
        $model = new ActionQueueBatchModel();

        self::assertIsArray($model->context());

        self::assertEmpty($model->context());

        $test = ['foo' => 'bar'];

        self::assertEquals($test, $model->context($test));

        self::assertEquals($test, $model->context());

        $test2 = ['baz' => 'foo'];

        $model = new ActionQueueBatchModel(['context' => $test2]);

        self::assertEquals($test2, $model->context());

        self::assertEquals($test, $model->context($test));

        self::assertEquals($test, $model->context());
    }

    public function testItemsInvalid() : void
    {
        $model = new ActionQueueBatchModel();

        self::assertIsArray($model->items());

        self::assertEmpty($model->items());

        $test = [
            new ActionQueueItemModel(),
            'bar',
        ];

        $exception = null;

        try {
            $model->items($test);
        } catch (InvalidArgumentException $e) {
            $exception = $e;
        }

        self::assertInstanceOf(InvalidArgumentException::class, $exception);

        self::assertEquals(
            '$items must be an array of ActionQueueItemModelInterface',
            $exception->getMessage()
        );
    }

    public function testItems() : void
    {
        $test = [
            new ActionQueueItemModel(),
            new ActionQueueItemModel(),
        ];

        $model = new ActionQueueBatchModel(['items' => $test]);

        self::assertEquals($test, $model->items());
    }

    public function testAddItem() : void
    {
        $item1 = new ActionQueueItemModel();

        $item2 = new ActionQueueItemModel();

        $model = new ActionQueueBatchModel();

        self::assertIsArray($model->items());

        self::assertEmpty($model->items());

        $model->addItem($item1);

        self::assertSame($model->items()[0], $item1);

        $model->addItem($item2);

        self::assertSame($model->items()[0], $item1);

        self::assertSame($model->items()[1], $item2);
    }
}
