<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

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
