<?php
declare(strict_types=1);

namespace corbomite\queue\data\ActionQueueItem;

use Atlas\Mapper\Record;

/**
 * @method ActionQueueItemRow getRow()
 */
class ActionQueueItemRecord extends Record
{
    use ActionQueueItemFields;
}
