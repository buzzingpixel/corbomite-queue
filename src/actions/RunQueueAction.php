<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\actions;

use Throwable;
use corbomite\di\Di;
use corbomite\queue\QueueApi;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;

class RunQueueAction
{
    private $di;
    private $queueApi;

    public function __construct(Di $di)
    {
        $this->di = $di;

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->queueApi = $di->getFromDefinition(QueueApi::class);
    }

    public function __invoke()
    {
        $item = $this->queueApi->getNextQueueItem(true);

        if (! $item) {
            return null;
        }

        try {
            return $this->run($item);
        } catch (Throwable $e) {
            $this->queueApi->markAsStoppedDueToError($item);
            return 1;
        }
    }

    private function run(ActionQueueItemModelInterface $item): ?int
    {
        $constructedClass = null;

        /** @noinspection PhpUnhandledExceptionInspection */
        if ($this->di->hasDefinition($item->class())) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $constructedClass = $this->di->makeFromDefinition($item->class());
        }

        if (! $constructedClass) {
            $class = $item->class();
            $constructedClass = new $class();
        }

        $constructedClass->{$item->method()}($item->context());

        $this->queueApi->markItemAsRun($item);

        return null;
    }
}
