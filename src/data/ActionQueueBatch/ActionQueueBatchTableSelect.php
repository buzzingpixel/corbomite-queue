<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\data\ActionQueueBatch;

use Atlas\Table\TableSelect;

/**
 * @method ActionQueueBatchRow|null fetchRow()
 * @method ActionQueueBatchRow[] fetchRows()
 */
class ActionQueueBatchTableSelect extends TableSelect
{
}
