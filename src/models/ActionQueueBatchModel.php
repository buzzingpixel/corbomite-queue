<?php
declare(strict_types=1);

namespace corbomite\queue\models;

use DateTime;

class ActionQueueBatchModel
{
    public function __construct(array $props = [])
    {
        foreach ($props as $key => $val) {
            $this->{$key}($val);
        }
    }

    private $guid = '';

    public function guid(?string $guid = null): string
    {
        return $this->guid = $guid !== null ? $guid : $this->guid;
    }

    private $name = '';

    public function name(?string $name = null): string
    {
        return $this->name = $name !== null ? $name : $this->name;
    }

    private $title = '';

    public function title(?string $title = null): string
    {
        return $this->title = $title !== null ? $title : $this->title;
    }

    private $hasStarted = false;

    public function hasStarted(?bool $hasStarted = null): bool
    {
        return $this->hasStarted = $hasStarted !== null ?
            $hasStarted :
            $this->hasStarted;
    }

    private $isFinished = false;

    public function isFinished(?bool $isFinished = null): bool
    {
        return $this->isFinished = $isFinished !== null ?
            $isFinished :
            $this->isFinished;
    }

    private $percentComplete = 0;

    public function percentComplete(?bool $percentComplete = null): int
    {
        return $this->percentComplete = $percentComplete !== null ?
            $percentComplete :
            $this->percentComplete;
    }

    private $addedAt;

    public function addedAt(?DateTime $addedAt = null): ?DateTime
    {
        return $this->addedAt = $addedAt !== null ? $addedAt : $this->addedAt;
    }

    private $finishedAt;

    public function finishedAt(?DateTime $finishedAt = null): ?DateTime
    {
        return $this->finishedAt = $finishedAt !== null ?
            $finishedAt :
            $this->finishedAt;
    }

    private $context = [];

    public function context(?array $context = null): array
    {
        return $this->context = $context !== null ? $context : $this->context;
    }

    private $items = [];

    /**
     * @return ActionQueueItemModel[]
     */
    public function items(?array $items = null): array
    {
        if (\is_array($items)) {
            foreach ($items as $item) {
                if ($item instanceof ActionQueueItemModel) {
                    continue;
                }

                throw new \InvalidArgumentException(
                    '$items must be an array of ActionQueueItemModel'
                );
            }
        }

        return $this->items = $items !== null ? $items : $this->items;
    }
}
