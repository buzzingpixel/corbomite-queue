<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\data\ActionQueueItem;

use Atlas\Mapper\MapperRelationships;
use corbomite\queue\data\ActionQueueBatch\ActionQueueBatch;

class ActionQueueItemRelationships extends MapperRelationships
{
    protected function define(): void
    {
        $this->manyToOne('action_queue_batch', ActionQueueBatch::class, [
            'action_queue_batch_guid' => 'guid',
        ]);
    }
}
