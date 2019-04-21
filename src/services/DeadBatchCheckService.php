<?php

declare(strict_types=1);

namespace corbomite\queue\services;

use corbomite\db\Factory;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatch;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatchRecord;
use DateTimeImmutable;
use DateTimeZone;
use Throwable;
use function time;

class DeadBatchCheckService
{
    /** @var Factory */
    private $ormFactory;

    public function __construct(Factory $ormFactory)
    {
        $this->ormFactory = $ormFactory;
    }

    /**
     * @throws Throwable
     */
    public function __invoke() : void
    {
        $currentTimestamp = time();

        foreach ($this->fetchActionQueueBatchRecords() as $record) {
            $assumeDeadAfter = new DateTimeImmutable(
                $record->assume_dead_after,
                new DateTimeZone($record->assume_dead_after_time_zone)
            );

            if ($assumeDeadAfter->getTimestamp() > $currentTimestamp) {
                continue;
            }

            $startTime = new DateTimeImmutable(
                $record->added_at,
                new DateTimeZone($record->added_at_time_zone)
            );

            $initialDeadTime = new DateTimeImmutable(
                $record->initial_assume_dead_after,
                new DateTimeZone($record->initial_assume_dead_after_time_zone)
            );

            $newAssumeDeadAfter = (new DateTimeImmutable(
                'now',
                new DateTimeZone('UTC')
            ))->add($startTime->diff($initialDeadTime));

            $record->is_running = 0;

            $record->assume_dead_after = $newAssumeDeadAfter->format('Y-m-d H:i:s');

            $record->assume_dead_after_time_zone = $newAssumeDeadAfter->getTimezone()->getName();

            $this->ormFactory->makeOrm()->persist($record);
        }
    }

    /**
     * @return ActionQueueBatchRecord[]
     */
    private function fetchActionQueueBatchRecords() : array
    {
        return $this->ormFactory->makeOrm()
            ->select(ActionQueueBatch::class)
            ->where('is_running = ', 1)
            ->andWhere('is_finished = ', 0)
            ->fetchRecords();
    }
}
