<?php

declare(strict_types=1);

namespace corbomite\queue\actions;

use corbomite\queue\interfaces\ActionQueueItemModelInterface;
use corbomite\queue\interfaces\QueueApiInterface;
use Psr\Container\ContainerInterface;
use Throwable;

class RunQueueAction
{
    /** @var ContainerInterface */
    private $di;
    /** @var QueueApiInterface */
    private $queueApi;

    public function __construct(ContainerInterface $di)
    {
        $this->di = $di;

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->queueApi = $di->get(QueueApiInterface::class);
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
        if ($this->di->has($item->class())) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $constructedClass = $this->di->get($item->class());
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
