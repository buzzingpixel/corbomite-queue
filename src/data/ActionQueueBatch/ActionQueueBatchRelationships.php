<?php
declare(strict_types=1);

namespace corbomite\queue\data\ActionQueueBatch;

use Atlas\Mapper\MapperRelationships;
use corbomite\queue\data\ActionQueueItem\ActionQueueItem;

class ActionQueueBatchRelationships extends MapperRelationships
{
    protected function define(): void
    {
        $this->oneToMany('action_queue_items', ActionQueueItem::class, [
            'guid' => 'action_queue_batch_guid',
        ]);
    }
}
