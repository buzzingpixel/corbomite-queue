<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace corbomite\queue\data\ActionQueueBatch;

use Atlas\Table\Table;

/**
 * @method ActionQueueBatchRow|null fetchRow($primaryVal)
 * @method ActionQueueBatchRow[] fetchRows(array $primaryVals)
 * @method ActionQueueBatchTableSelect select(array $whereEquals = [])
 * @method ActionQueueBatchRow newRow(array $cols = [])
 * @method ActionQueueBatchRow newSelectedRow(array $cols)
 */
class ActionQueueBatchTable extends Table
{
    const DRIVER = 'mysql';

    const NAME = 'action_queue_batch';

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
        'name' => [
            'name' => 'name',
            'type' => 'varchar',
            'size' => 255,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'title' => [
            'name' => 'title',
            'type' => 'varchar',
            'size' => 255,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'has_started' => [
            'name' => 'has_started',
            'type' => 'tinyint',
            'size' => 3,
            'scale' => 0,
            'notnull' => true,
            'default' => '0',
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
        'finished_due_to_error' => [
            'name' => 'finished_due_to_error',
            'type' => 'tinyint',
            'size' => 3,
            'scale' => 0,
            'notnull' => true,
            'default' => '0',
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'percent_complete' => [
            'name' => 'percent_complete',
            'type' => 'float',
            'size' => 12,
            'scale' => null,
            'notnull' => true,
            'default' => '0',
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'added_at' => [
            'name' => 'added_at',
            'type' => 'datetime',
            'size' => null,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'added_at_time_zone' => [
            'name' => 'added_at_time_zone',
            'type' => 'varchar',
            'size' => 255,
            'scale' => null,
            'notnull' => true,
            'default' => null,
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
        'name',
        'title',
        'has_started',
        'is_finished',
        'finished_due_to_error',
        'percent_complete',
        'added_at',
        'added_at_time_zone',
        'finished_at',
        'finished_at_time_zone',
        'context',
    ];

    const COLUMN_DEFAULTS = [
        'id' => null,
        'guid' => null,
        'name' => null,
        'title' => null,
        'has_started' => '0',
        'is_finished' => '0',
        'finished_due_to_error' => '0',
        'percent_complete' => '0',
        'added_at' => null,
        'added_at_time_zone' => null,
        'finished_at' => 'NULL',
        'finished_at_time_zone' => 'NULL',
        'context' => 'NULL',
    ];

    const PRIMARY_KEY = [
        'id',
    ];

    const AUTOINC_COLUMN = 'id';

    const AUTOINC_SEQUENCE = null;
}
