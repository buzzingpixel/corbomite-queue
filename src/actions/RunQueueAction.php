<?php

declare(strict_types=1);

namespace corbomite\queue\actions;

use corbomite\di\Di;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;
use corbomite\queue\QueueApi;
use Throwable;

class RunQueueAction
{
    /** @var Di */
    private $di;
    /** @var QueueApi */
    private $queueApi;

    public function __construct(Di $di)
    {
        $this->di = $di;

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->queueApi = $di->getFromDefinition(QueueApi::class);
    }

    public function __invoke() : ?int
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

    private function run(ActionQueueItemModelInterface $item) : ?int
    {
        $constructedClass = null;

        /** @noinspection PhpUnhandledExceptionInspection */
        if ($this->di->hasDefinition($item->class())) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $constructedClass = $this->di->makeFromDefinition($item->class());
        }

        if (! $constructedClass) {
            $class            = $item->class();
            $constructedClass = new $class();
        }

        $constructedClass->{$item->method()}($item->context());

        $this->queueApi->markItemAsRun($item);

        return null;
    }
}
