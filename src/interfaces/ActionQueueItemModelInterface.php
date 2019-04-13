<?php

declare(strict_types=1);

namespace corbomite\queue\interfaces;

use corbomite\db\interfaces\UuidModelInterface;
use DateTime;

interface ActionQueueItemModelInterface
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
    public function isFinished(?bool $val = null) : bool;

    /**
     * Returns value. Sets value if incoming argument set.
     */
    public function finishedAt(?DateTime $val = null) : ?DateTime;

    /**
     * Returns value. Sets value if incoming argument set.
     *
     * @param string|null $val
     */
    public function class(?string $class = null) : string;

    /**
     * Returns value. Sets value if incoming argument set.
     *
     * @param string|null $val
     */
    public function method(?string $method = null) : string;

    /**
     * Returns value. Sets value if incoming argument set.
     *
     * @param mixed[]|null $context
     *
     * @return mixed[]
     */
    public function context(?array $context = null) : array;
}
