<?php
declare(strict_types=1);

namespace corbomite\queue\data\ActionQueueBatch;

use Atlas\Table\TableSelect;

/**
 * @method ActionQueueBatchRow|null fetchRow()
 * @method ActionQueueBatchRow[] fetchRows()
 */
class ActionQueueBatchTableSelect extends TableSelect
{
}
