<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\data\ActionQueueBatch;

use Atlas\Mapper\Record;

/**
 * @method ActionQueueBatchRow getRow()
 */
class ActionQueueBatchRecord extends Record
{
    use ActionQueueBatchFields;
}
