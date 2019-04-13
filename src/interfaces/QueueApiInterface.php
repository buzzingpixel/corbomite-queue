<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\interfaces;

use corbomite\db\interfaces\QueryModelInterface;
use corbomite\queue\exceptions\InvalidActionQueueBatchModel;

interface QueueApiInterface
{
    /**
     * Creates a Fetch User Params Model
     * @param array $props
     * @return QueryModelInterface
     */
    public function makeQueryModel(): QueryModelInterface;

    /**
     * Converts a UUID to bytes for database queries
     * @param string $string
     * @return string
     */
    public function uuidToBytes(string $string): string;

    /**
     * Makes an action queue batch model
     * @param array $props
     * @return ActionQueueBatchModelInterface
     */
    public function makeActionQueueBatchModel(array $props = []): ActionQueueBatchModelInterface;

    /**
     * Makes an action queue item model
     * @param array $props
     * @return ActionQueueItemModelInterface
     */
    public function makeActionQueueItemModel(array $props = []): ActionQueueItemModelInterface;

    /**
     * Adds a batch to the queue
     * @param ActionQueueBatchModelInterface $model
     * @throws InvalidActionQueueBatchModel
     */
    public function addToQueue(ActionQueueBatchModelInterface $model);

    /**
     * Gets the next queue item to run if there is one
     * @param bool $markAsStarted
     * @return ActionQueueItemModelInterface|null
     */
    public function getNextQueueItem(bool $markAsStarted = false): ?ActionQueueItemModelInterface;

    /**
     * Marks an item as stopped due to error
     * @param ActionQueueItemModelInterface $model
     */
    public function markAsStoppedDueToError(ActionQueueItemModelInterface $model);

    /**
     * Marks an item as run
     * @param ActionQueueItemModelInterface $model
     */
    public function markItemAsRun(ActionQueueItemModelInterface $model);

    /**
     * Updates the batch item of the specified guid's status
     * @param string $actionQueueGuid
     */
    public function updateActionQueue(string $actionQueueGuid);

    /**
     * Fetches one matching batch based on query model settings
     * @param QueryModelInterface|null $queryModel
     * @return ActionQueueBatchModelInterface|null
     */
    public function fetchOneBatch(?QueryModelInterface $queryModel = null): ?ActionQueueBatchModelInterface;

    /**
     * Fetches all matching batches based on query model settings
     * @param QueryModelInterface $queryModel
     * @return ActionQueueBatchModelInterface[]
     */
    public function fetchAllBatches(?QueryModelInterface $queryModel = null): array;
}
