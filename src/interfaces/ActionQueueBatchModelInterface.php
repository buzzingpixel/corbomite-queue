<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\interfaces;

use DateTime;
use corbomite\db\interfaces\UuidModelInterface;

interface ActionQueueBatchModelInterface
{
    /**
     * Sets incoming properties from the incoming array
     * @param array $props
     */
    public function __construct(array $props = []);

    /**
     * Returns value. Sets value if incoming argument set.
     * @param string|null $val
     * @return string
     */
    public function guid(?string $val = null): string;

    /**
     * Gets the UuidModel for the guid
     * @return UuidModelInterface
     */
    public function guidAsModel(): UuidModelInterface;

    /**
     * Gets the GUID as bytes for saving to the database in binary
     * @return string
     */
    public function getGuidAsBytes(): string;

    /**
     * Sets the GUID from bytes coming from the database binary column
     * @param string $bytes
     */
    public function setGuidAsBytes(string $bytes);

    /**
     * Returns value. Sets value if incoming argument set.
     * @param string|null $val
     * @return string
     */
    public function name(?string $val = null): string;

    /**
     * Returns value. Sets value if incoming argument set.
     * @param string|null $val
     * @return string
     */
    public function title(?string $val = null): string;

    /**
     * Returns value. Sets value if incoming argument set.
     * @param bool|null $val
     * @return bool
     */
    public function hasStarted(?bool $val = null): bool;

    /**
     * Returns value. Sets value if incoming argument set.
     * @param bool|null $val
     * @return bool
     */
    public function isFinished(?bool $val = null): bool;

    /**
     * Returns value. Sets value if incoming argument set.
     * @param bool|null $val
     * @return int
     */
    public function percentComplete(?float $val = null): float;

    /**
     * Returns value. Sets value if incoming argument set.
     * @param DateTime|null $val
     * @return DateTime|null
     */
    public function addedAt(?DateTime $val = null): ?DateTime;

    /**
     * Returns value. Sets value if incoming argument set.
     * @param DateTime|null $val
     * @return DateTime|null
     */
    public function finishedAt(?DateTime $val = null): ?DateTime;

    /**
     * Returns value. Sets value if incoming argument set.
     * @param array|null $val
     * @return array
     */
    public function context(?array $val = null): array;

    /**
     * Returns value. Sets value if incoming argument set.
     * @param array|null $val
     * @return ActionQueueItemModelInterface[]
     */
    public function items(?array $val = null): array;

    /**
     * Adds a queue item to the batch
     * @param ActionQueueItemModelInterface $val
     * @return mixed
     */
    public function addItem(ActionQueueItemModelInterface $val);
}
