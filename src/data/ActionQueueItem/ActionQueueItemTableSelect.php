<?php
declare(strict_types=1);

namespace corbomite\queue\data\ActionQueueItem;

use Atlas\Table\TableSelect;

/**
 * @method ActionQueueItemRow|null fetchRow()
 * @method ActionQueueItemRow[] fetchRows()
 */
class ActionQueueItemTableSelect extends TableSelect
{
}
