<?php
declare(strict_types=1);

namespace corbomite\queue\data\ActionQueueBatch;

use Atlas\Mapper\MapperSelect;

/**
 * @method ActionQueueBatchRecord|null fetchRecord()
 * @method ActionQueueBatchRecord[] fetchRecords()
 * @method ActionQueueBatchRecordSet fetchRecordSet()
 */
class ActionQueueBatchSelect extends MapperSelect
{
}
