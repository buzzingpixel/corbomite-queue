<?php
declare(strict_types=1);

namespace corbomite\queue\data\ActionQueueItem;

use Atlas\Mapper\MapperSelect;

/**
 * @method ActionQueueItemRecord|null fetchRecord()
 * @method ActionQueueItemRecord[] fetchRecords()
 * @method ActionQueueItemRecordSet fetchRecordSet()
 */
class ActionQueueItemSelect extends MapperSelect
{
}
