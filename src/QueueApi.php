<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue;

use corbomite\di\Di;
use corbomite\db\Factory as DbFactory;
use corbomite\db\interfaces\QueryModelInterface;
use corbomite\queue\models\ActionQueueItemModel;
use corbomite\queue\models\ActionQueueBatchModel;
use corbomite\queue\services\FetchBatchesService;
use corbomite\queue\interfaces\QueueApiInterface;
use corbomite\queue\services\MarkItemAsRunService;
use corbomite\queue\services\AddBatchToQueueService;
use corbomite\queue\services\GetNextQueueItemService;
use corbomite\queue\services\UpdateActionQueueService;
use corbomite\queue\exceptions\InvalidActionQueueBatchModel;
use corbomite\queue\services\MarkAsStoppedDueToErrorService;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;
use corbomite\queue\interfaces\ActionQueueBatchModelInterface;

class QueueApi implements QueueApiInterface
{
    private $di;
    private $dbFactory;

    public function __construct(Di $di, DbFactory $dbFactory)
    {
        $this->di = $di;
        $this->dbFactory = $dbFactory;
    }

    public function makeQueryModel(): QueryModelInterface
    {
        return $this->dbFactory->makeQueryModel();
    }

    public function makeActionQueueBatchModel(array $props = []): ActionQueueBatchModelInterface
    {
        return new ActionQueueBatchModel($props);
    }

    public function makeActionQueueItemModel(array $props = []): ActionQueueItemModelInterface
    {
        return new ActionQueueItemModel($props);
    }

    /**
     * @throws InvalidActionQueueBatchModel
     */
    public function addToQueue(ActionQueueBatchModelInterface $model): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(AddBatchToQueueService::class);
        $service->add($model);
    }

    public function getNextQueueItem(bool $markAsStarted = false): ?ActionQueueItemModelInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(GetNextQueueItemService::class);
        return $service->get($markAsStarted);
    }

    public function markAsStoppedDueToError(ActionQueueItemModelInterface $model): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(MarkAsStoppedDueToErrorService::class);
        $service->markStopped($model);
    }

    public function markItemAsRun(ActionQueueItemModelInterface $model): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(MarkItemAsRunService::class);
        $service->markAsRun($model);
    }

    public function updateActionQueue(string $actionQueueGuid): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(UpdateActionQueueService::class);
        $service->update($actionQueueGuid);
    }

    public function fetchOneBatch(?QueryModelInterface $queryModel = null): ?ActionQueueBatchModelInterface
    {
        if (! $queryModel) {
            $queryModel = $this->makeQueryModel();
            $queryModel->addWhere('is_finished', 0);
            $queryModel->addOrder('added_at', 'asc');
        }

        $queryModel->limit(1);

        return $this->fetchAllBatches($queryModel)[0] ?? null;
    }

    public function fetchAllBatches(?QueryModelInterface $queryModel = null): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->getFromDefinition(FetchBatchesService::class);

        if (! $queryModel) {
            $queryModel = $this->makeQueryModel();
            $queryModel->addWhere('is_finished', '0');
            $queryModel->addOrder('added_at', 'asc');
        }

        return $service->fetch($queryModel);
    }
}
