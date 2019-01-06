<?php
declare(strict_types=1);

namespace corbomite\queue\models;

use DateTime;

class ActionQueueItemModel
{
    private $guid = '';

    public function guid(?string $guid = null): string
    {
        return $this->guid = $guid !== null ? $guid : $this->guid;
    }

    private $isFinished = false;

    public function isFinished(?bool $isFinished = null): bool
    {
        return $this->isFinished = $isFinished !== null ?
            $isFinished :
            $this->isFinished;
    }

    private $finishedAt;

    public function finishedAt(?DateTime $finishedAt = null): ?DateTime
    {
        return $this->finishedAt = $finishedAt !== null ?
            $finishedAt :
            $this->finishedAt;
    }

    private $class = '';

    public function class(?string $class = null): string
    {
        return $this->class = $class !== null ? $class : $this->class;
    }

    private $method = '';

    public function method(?string $method = null): string
    {
        return $this->method = $method !== null ? $method : $this->method;
    }

    private $context = [];

    public function context(?array $context = null): array
    {
        return $this->context = $context !== null ? $context : $this->context;
    }
}
