<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\queue\data\ActionQueueBatch;

use Atlas\Mapper\RecordSet;

/**
 * @method ActionQueueBatchRecord offsetGet($offset)
 * @method ActionQueueBatchRecord appendNew(array $fields = [])
 * @method ActionQueueBatchRecord|null getOneBy(array $whereEquals)
 * @method ActionQueueBatchRecordSet getAllBy(array $whereEquals)
 * @method ActionQueueBatchRecord|null detachOneBy(array $whereEquals)
 * @method ActionQueueBatchRecordSet detachAllBy(array $whereEquals)
 * @method ActionQueueBatchRecordSet detachAll()
 * @method ActionQueueBatchRecordSet detachDeleted()
 */
class ActionQueueBatchRecordSet extends RecordSet
{
}
