<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\models;

use DateTime;
use corbomite\db\traits\UuidTrait;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;

class ActionQueueItemModel implements ActionQueueItemModelInterface
{
    use UuidTrait;

    public function __construct(array $props = [])
    {
        foreach ($props as $key => $val) {
            $this->{$key}($val);
        }
    }

    private $isFinished = false;

    public function isFinished(?bool $val = null): bool
    {
        return $this->isFinished = $val ?? $this->isFinished;
    }

    /** @var DateTime|null */
    private $finishedAt;

    public function finishedAt(?DateTime $val = null): ?DateTime
    {
        return $this->finishedAt = $val ?? $this->finishedAt;
    }

    private $class = '';

    public function class(?string $val = null): string
    {
        return $this->class = $val ?? $this->class;
    }

    private $method = '__invoke';

    public function method(?string $val = null): string
    {
        return $this->method = $val ?? $this->method;
    }

    private $context = [];

    public function context(?array $val = null): array
    {
        return $this->context = $val ?? $this->context;
    }
}
