<?php

declare(strict_types=1);

namespace corbomite\queue\interfaces;

use corbomite\db\interfaces\UuidModelInterface;
use DateTimeInterface;

interface ActionQueueBatchModelInterface
{
    /**
     * Sets incoming properties from the incoming array
     *
     * @param mixed[] $props
     */
    public function __construct(array $props = []);

    /**
     * Returns value. Sets value if incoming argument set.
     */
    public function guid(?string $val = null) : string;

    /**
     * Gets the UuidModel for the guid
     */
    public function guidAsModel() : UuidModelInterface;

    /**
     * Gets the GUID as bytes for saving to the database in binary
     */
    public function getGuidAsBytes() : string;

    /**
     * Sets the GUID from bytes coming from the database binary column
     *
     * @return mixed
     */
    public function setGuidAsBytes(string $bytes);

    /**
     * Returns value. Sets value if incoming argument set.
     */
    public function name(?string $val = null) : string;

    /**
     * Returns value. Sets value if incoming argument set.
     */
    public function title(?string $val = null) : string;

    /**
     * Returns value. Sets value if incoming argument set.
     */
    public function hasStarted(?bool $val = null) : bool;

    /**
     * Returns value. Sets value if incoming argument set.
     */
    public function isRunning(?bool $val = null) : bool;

    /**
     * Returns value. Sets value if incoming argument set.
     */
    public function isFinished(?bool $val = null) : bool;

    /**
     * Returns value. Sets value if incoming argument set.
     *
     * @param bool|null $val
     */
    public function percentComplete(?float $val = null) : float;

    /**
     * Returns value. Sets value if incoming argument set.
     */
    public function addedAt(?DateTimeInterface $val = null) : ?DateTimeInterface;

    /**
     * Returns value. Sets value if incoming argument set.
     */
    public function finishedAt(?DateTimeInterface $val = null) : ?DateTimeInterface;

    /**
     * Returns value. Sets value if incoming argument set.
     *
     * @param mixed[]|null $val
     *
     * @return mixed[]
     */
    public function context(?array $val = null) : array;

    /**
     * Returns value. Sets value if incoming argument set.
     *
     * @param ActionQueueItemModelInterface[]|null $val
     *
     * @return ActionQueueItemModelInterface[]
     */
    public function items(?array $val = null) : array;

    /**
     * Adds a queue item to the batch
     *
     * @return mixed
     */
    public function addItem(ActionQueueItemModelInterface $val);
}
