<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\data\ActionQueueItem;

use Atlas\Table\TableSelect;

/**
 * @method ActionQueueItemRow|null fetchRow()
 * @method ActionQueueItemRow[] fetchRows()
 */
class ActionQueueItemTableSelect extends TableSelect
{
}
