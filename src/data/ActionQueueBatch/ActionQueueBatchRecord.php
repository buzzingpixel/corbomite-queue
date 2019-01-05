<?php
declare(strict_types=1);

namespace corbomite\queue\data\ActionQueueBatch;

use Atlas\Mapper\Record;

/**
 * @method ActionQueueBatchRow getRow()
 */
class ActionQueueBatchRecord extends Record
{
    use ActionQueueBatchFields;
}
