<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\interfaces;

use corbomite\queue\exceptions\InvalidActionQueueBatchModel;

interface QueueApiInterface
{
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
}
