<?php

declare(strict_types=1);

namespace corbomite\queue\models;

use corbomite\db\traits\UuidTrait;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class ActionQueueItemModel implements ActionQueueItemModelInterface
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

    /** @var bool */
    private $isFinished = false;

    public function isFinished(?bool $val = null) : bool
    {
        return $this->isFinished = $val ?? $this->isFinished;
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

    /** @var string */
    private $class = '';

    public function class(?string $val = null) : string
    {
        return $this->class = $val ?? $this->class;
    }

    /** @var string */
    private $method = '__invoke';

    public function method(?string $val = null) : string
    {
        return $this->method = $val ?? $this->method;
    }

    /** @var mixed[] */
    private $context = [];

    /**
     * @param mixed[]|null $val
     *
     * @return mixed[]
     */
    public function context(?array $val = null) : array
    {
        return $this->context = $val ?? $this->context;
    }
}
