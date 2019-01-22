<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\models;

use DateTime;
use corbomite\queue\interfaces\ActionQueueBatchModelInterface;
use \corbomite\queue\interfaces\ActionQueueItemModelInterface;

class ActionQueueBatchModel implements ActionQueueBatchModelInterface
{
    public function __construct(array $props = [])
    {
        foreach ($props as $key => $val) {
            $this->{$key}($val);
        }
    }

    private $guid = '';

    public function guid(?string $val = null): string
    {
        return $this->guid = $val ?? $this->guid;
    }

    private $name = '';

    public function name(?string $val = null): string
    {
        return $this->name = $val ?? $this->name;
    }

    private $title = '';

    public function title(?string $val = null): string
    {
        return $this->title = $val ?? $this->title;
    }

    private $hasStarted = false;

    public function hasStarted(?bool $val = null): bool
    {
        return $this->hasStarted = $val ?? $this->hasStarted;
    }

    private $isFinished = false;

    public function isFinished(?bool $val = null): bool
    {
        return $this->isFinished = $val ?? $this->isFinished;
    }

    private $percentComplete = 0;

    public function percentComplete(?bool $val = null): int
    {
        return $this->percentComplete = $val ?? $this->percentComplete;
    }

    /** @var DateTime|null */
    private $addedAt;

    public function addedAt(?DateTime $addedAt = null): ?DateTime
    {
        return $this->addedAt = $addedAt ?? $this->addedAt;
    }

    /** @var DateTime|null */
    private $finishedAt;

    public function finishedAt(?DateTime $val = null): ?DateTime
    {
        return $this->finishedAt = $val ?? $this->finishedAt;
    }

    private $context = [];

    public function context(?array $val = null): array
    {
        return $this->context = $val ?? $this->context;
    }

    private $items = [];

    /**
     * @return ActionQueueItemModelInterface[]
     */
    public function items(?array $val = null): array
    {
        if (\is_array($val)) {
            foreach ($val as $item) {
                if ($item instanceof ActionQueueItemModelInterface) {
                    continue;
                }

                throw new \InvalidArgumentException(
                    '$items must be an array of ActionQueueItemModelInterface'
                );
            }
        }

        return $this->items = $val ?? $this->items;
    }

    public function addItem(ActionQueueItemModelInterface $val)
    {
        $this->items[] = $val;
    }
}
