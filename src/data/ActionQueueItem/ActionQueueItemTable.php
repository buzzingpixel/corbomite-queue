<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace corbomite\queue\data\ActionQueueItem;

use Atlas\Table\Table;

/**
 * @method ActionQueueItemRow|null fetchRow($primaryVal)
 * @method ActionQueueItemRow[] fetchRows(array $primaryVals)
 * @method ActionQueueItemTableSelect select(array $whereEquals = [])
 * @method ActionQueueItemRow newRow(array $cols = [])
 * @method ActionQueueItemRow newSelectedRow(array $cols)
 */
class ActionQueueItemTable extends Table
{
    const DRIVER = 'mysql';

    const NAME = 'action_queue_items';

    const COLUMNS = [
        'id' => [
            'name' => 'id',
            'type' => 'int',
            'size' => 10,
            'scale' => 0,
            'notnull' => true,
            'default' => null,
            'autoinc' => true,
            'primary' => true,
            'options' => null,
        ],
        'guid' => [
            'name' => 'guid',
            'type' => 'varchar',
            'size' => 255,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'order_to_run' => [
            'name' => 'order_to_run',
            'type' => 'int',
            'size' => 10,
            'scale' => 0,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'action_queue_guid' => [
            'name' => 'action_queue_guid',
            'type' => 'varchar',
            'size' => 255,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'is_finished' => [
            'name' => 'is_finished',
            'type' => 'tinyint',
            'size' => 3,
            'scale' => 0,
            'notnull' => true,
            'default' => '0',
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'finished_at' => [
            'name' => 'finished_at',
            'type' => 'datetime',
            'size' => null,
            'scale' => null,
            'notnull' => false,
            'default' => 'NULL',
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'finished_at_time_zone' => [
            'name' => 'finished_at_time_zone',
            'type' => 'varchar',
            'size' => 255,
            'scale' => null,
            'notnull' => false,
            'default' => 'NULL',
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'class' => [
            'name' => 'class',
            'type' => 'text',
            'size' => 65535,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'method' => [
            'name' => 'method',
            'type' => 'text',
            'size' => 65535,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'context' => [
            'name' => 'context',
            'type' => 'text',
            'size' => 65535,
            'scale' => null,
            'notnull' => false,
            'default' => 'NULL',
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
    ];

    const COLUMN_NAMES = [
        'id',
        'guid',
        'order_to_run',
        'action_queue_guid',
        'is_finished',
        'finished_at',
        'finished_at_time_zone',
        'class',
        'method',
        'context',
    ];

    const COLUMN_DEFAULTS = [
        'id' => null,
        'guid' => null,
        'order_to_run' => null,
        'action_queue_guid' => null,
        'is_finished' => '0',
        'finished_at' => 'NULL',
        'finished_at_time_zone' => 'NULL',
        'class' => null,
        'method' => null,
        'context' => 'NULL',
    ];

    const PRIMARY_KEY = [
        'id',
    ];

    const AUTOINC_COLUMN = 'id';

    const AUTOINC_SEQUENCE = null;
}
