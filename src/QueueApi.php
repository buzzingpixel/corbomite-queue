<?php

declare(strict_types=1);

namespace corbomite\queue;

use corbomite\db\Factory as DbFactory;
use corbomite\db\interfaces\QueryModelInterface;
use corbomite\db\models\UuidModel;
use corbomite\queue\exceptions\InvalidActionQueueBatchModel;
use corbomite\queue\interfaces\ActionQueueBatchModelInterface;
use corbomite\queue\interfaces\ActionQueueItemModelInterface;
use corbomite\queue\interfaces\QueueApiInterface;
use corbomite\queue\models\ActionQueueBatchModel;
use corbomite\queue\models\ActionQueueItemModel;
use corbomite\queue\services\AddBatchToQueueService;
use corbomite\queue\services\FetchBatchesService;
use corbomite\queue\services\GetNextQueueItemService;
use corbomite\queue\services\MarkAsStoppedDueToErrorService;
use corbomite\queue\services\MarkItemAsRunService;
use corbomite\queue\services\UpdateActionQueueService;
use Psr\Container\ContainerInterface;
use Throwable;

class QueueApi implements QueueApiInterface
{
    /** @var ContainerInterface */
    private $di;
    /** @var DbFactory */
    private $dbFactory;

    public function __construct(ContainerInterface $di, DbFactory $dbFactory)
    {
        $this->di        = $di;
        $this->dbFactory = $dbFactory;
    }

    public function makeQueryModel() : QueryModelInterface
    {
        return $this->dbFactory->makeQueryModel();
    }

    public function uuidToBytes(string $string) : string
    {
        return (new UuidModel($string))->toBytes();
    }

    /**
     * @param mixed[] $props
     */
    public function makeActionQueueBatchModel(array $props = []) : ActionQueueBatchModelInterface
    {
        return new ActionQueueBatchModel($props);
    }

    /**
     * @param mixed[] $props
     */
    public function makeActionQueueItemModel(array $props = []) : ActionQueueItemModelInterface
    {
        return new ActionQueueItemModel($props);
    }

    /**
     * @throws InvalidActionQueueBatchModel
     */
    public function addToQueue(ActionQueueBatchModelInterface $model) : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(AddBatchToQueueService::class);
        $service->add($model);
    }

    public function getNextQueueItem(bool $markAsStarted = false) : ?ActionQueueItemModelInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(GetNextQueueItemService::class);

        return $service->get($markAsStarted);
    }

    public function markAsStoppedDueToError(ActionQueueItemModelInterface $model, ?Throwable $e = null) : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(MarkAsStoppedDueToErrorService::class);
        $service->markStopped($model, $e);
    }

    public function markItemAsRun(ActionQueueItemModelInterface $model) : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(MarkItemAsRunService::class);
        $service->markAsRun($model);
    }

    public function updateActionQueue(string $actionQueueGuid) : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(UpdateActionQueueService::class);
        $service->update($actionQueueGuid);
    }

    public function fetchOneBatch(?QueryModelInterface $queryModel = null) : ?ActionQueueBatchModelInterface
    {
        if (! $queryModel) {
            $queryModel = $this->makeQueryModel();
            $queryModel->addWhere('is_finished', 0);
            $queryModel->addOrder('added_at', 'asc');
        }

        $queryModel->limit(1);

        return $this->fetchAllBatches($queryModel)[0] ?? null;
    }

    /**
     * @return ActionQueueBatchModelInterface[]
     */
    public function fetchAllBatches(?QueryModelInterface $queryModel = null) : array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $service = $this->di->get(FetchBatchesService::class);

        if (! $queryModel) {
            $queryModel = $this->makeQueryModel();
            $queryModel->addWhere('is_finished', '0');
            $queryModel->addOrder('added_at', 'asc');
        }

        return $service->fetch($queryModel);
    }
}
