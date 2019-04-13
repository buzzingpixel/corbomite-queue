<?php

declare(strict_types=1);

namespace corbomite\queue\interfaces;

use corbomite\db\interfaces\QueryModelInterface;
use corbomite\queue\exceptions\InvalidActionQueueBatchModel;

interface QueueApiInterface
{
    /**
     * Creates a Fetch User Params Model
     */
    public function makeQueryModel() : QueryModelInterface;

    /**
     * Converts a UUID to bytes for database queries
     */
    public function uuidToBytes(string $string) : string;

    /**
     * Makes an action queue batch model
     *
     * @param mixed[] $props
     */
    public function makeActionQueueBatchModel(array $props = []) : ActionQueueBatchModelInterface;

    /**
     * Makes an action queue item model
     *
     * @param mixed[] $props
     */
    public function makeActionQueueItemModel(array $props = []) : ActionQueueItemModelInterface;

    /**
     * Adds a batch to the queue
     *
     * @return mixed
     *
     * @throws InvalidActionQueueBatchModel
     */
    public function addToQueue(ActionQueueBatchModelInterface $model);

    /**
     * Gets the next queue item to run if there is one
     */
    public function getNextQueueItem(bool $markAsStarted = false) : ?ActionQueueItemModelInterface;

    /**
     * Marks an item as stopped due to error
     *
     * @return mixed
     */
    public function markAsStoppedDueToError(ActionQueueItemModelInterface $model);

    /**
     * Marks an item as run
     *
     * @return mixed
     */
    public function markItemAsRun(ActionQueueItemModelInterface $model);

    /**
     * Updates the batch item of the specified guid's status
     *
     * @return mixed
     */
    public function updateActionQueue(string $actionQueueGuid);

    /**
     * Fetches one matching batch based on query model settings
     */
    public function fetchOneBatch(?QueryModelInterface $queryModel = null) : ?ActionQueueBatchModelInterface;

    /**
     * Fetches all matching batches based on query model settings
     *
     * @return ActionQueueBatchModelInterface[]
     */
    public function fetchAllBatches(?QueryModelInterface $queryModel = null) : array;
}
