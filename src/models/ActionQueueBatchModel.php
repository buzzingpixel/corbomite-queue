<?php

declare(strict_types=1);

namespace corbomite\queue\models;

use corbomite\db\traits\UuidTrait;
use corbomite\queue\interfaces\ActionQueueBatchModelInterface;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use function is_array;

class ActionQueueBatchModel implements ActionQueueBatchModelInterface
{
    use UuidTrait;

    /**
     * @param mixed[] $props
     */
    public function __construct(array $props = [])
    {
        foreach ($props as $key => $val) {
            $this->{$key}($val);
        }
    }

    /** @var string */
    private $name = '';

    public function name(?string $val = null) : string
    {
        return $this->name = $val ?? $this->name;
    }

    /** @var string */
    private $title = '';

    public function title(?string $val = null) : string
    {
        return $this->title = $val ?? $this->title;
    }

    /** @var bool */
    private $hasStarted = false;

    public function hasStarted(?bool $val = null) : bool
    {
        return $this->hasStarted = $val ?? $this->hasStarted;
    }

    /** @var bool */
    private $isFinished = false;

    public function isFinished(?bool $val = null) : bool
    {
        return $this->isFinished = $val ?? $this->isFinished;
    }

    /** @var float */
    private $percentComplete = 0.0;

    public function percentComplete(?float $val = null) : float
    {
        return $this->percentComplete = $val ?? $this->percentComplete;
    }

    /** @var DateTimeInterface|null */
    private $addedAt;

    public function addedAt(?DateTimeInterface $val = null) : ?DateTimeInterface
    {
        if (! $val) {
            return $this->addedAt;
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->addedAt = (new DateTimeImmutable())
            ->setTimestamp($val->getTimestamp());

        return $this->addedAt;
    }

    /** @var DateTime|null */
    private $finishedAt;

    public function finishedAt(?DateTimeInterface $val = null) : ?DateTimeInterface
    {
        if (! $val) {
            return $this->finishedAt;
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->finishedAt = (new DateTimeImmutable())
            ->setTimestamp($val->getTimestamp());

        return $this->finishedAt;
    }

    /** @var mixed[] */
    private $context = [];

    /**
     * @param mixed[] $val
     *
     * @return mixed[]
     */
    public function context(?array $val = null) : array
    {
        return $this->context = $val ?? $this->context;
    }

    /** @var ActionQueueItemModelInterface[] */
    private $items = [];

    /**
     * @param ActionQueueItemModelInterface[]|null $val
     *
     * @return ActionQueueItemModelInterface[]
     */
    public function items(?array $val = null) : array
    {
        if (is_array($val)) {
            foreach ($val as $item) {
                if ($item instanceof ActionQueueItemModelInterface) {
                    continue;
                }

                throw new InvalidArgumentException(
                    '$items must be an array of ActionQueueItemModelInterface'
                );
            }
        }

        return $this->items = $val ?? $this->items;
    }

    public function addItem(ActionQueueItemModelInterface $val) : void
    {
        $this->items[] = $val;
    }
}
