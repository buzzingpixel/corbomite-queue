<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\data\ActionQueueItem;

use Atlas\Mapper\Record;

/**
 * @method ActionQueueItemRow getRow()
 */
class ActionQueueItemRecord extends Record
{
    use ActionQueueItemFields;
}
