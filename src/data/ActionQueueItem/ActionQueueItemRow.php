<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace corbomite\queue\data\ActionQueueItem;

use Atlas\Table\Row;

/**
 * @property mixed $guid binary(16) NOT NULL
 * @property mixed $action_queue_batch_guid binary(16) NOT NULL
 * @property mixed $order_to_run int(10,0) NOT NULL
 * @property mixed $is_finished tinyint(3,0) NOT NULL
 * @property mixed $finished_at datetime
 * @property mixed $finished_at_time_zone varchar(255)
 * @property mixed $class text(65535) NOT NULL
 * @property mixed $method text(65535) NOT NULL
 * @property mixed $context text(65535)
 */
class ActionQueueItemRow extends Row
{
    protected $cols = [
        'guid' => null,
        'action_queue_batch_guid' => null,
        'order_to_run' => null,
        'is_finished' => 0,
        'finished_at' => 'NULL',
        'finished_at_time_zone' => 'NULL',
        'class' => null,
        'method' => null,
        'context' => 'NULL',
    ];
}
