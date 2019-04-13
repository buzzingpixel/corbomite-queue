#!/usr/bin/env php
<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

use corbomite\di\Di;
use corbomite\cli\Kernel;

define('ENTRY_POINT', 'app');
define('APP_BASE_PATH', __DIR__);
define('APP_VENDOR_PATH', APP_BASE_PATH . '/vendor');

putenv('DB_HOST=db');
putenv('DB_DATABASE=site');
putenv('DB_USER=site');
putenv('DB_PASSWORD=secret');
putenv('CORBOMITE_DB_DATA_NAMESPACE=corbomite\queue\data');
putenv('CORBOMITE_DB_DATA_DIRECTORY=./src/data');

require APP_VENDOR_PATH . '/autoload.php';

// $uuid = (new Ramsey\Uuid\UuidFactory())->uuid1();
// $uuid2 = (new Ramsey\Uuid\UuidFactory())->uuid1();
//
// var_dump($uuid->toString(), $uuid2->toString());
// die;

/** @noinspection PhpUnhandledExceptionInspection */
$queueApi = Di::diContainer()->get(\corbomite\queue\QueueApi::class);

// $batchModel = $queueApi->makeActionQueueBatchModel();
// $itemModel1 = $queueApi->makeActionQueueItemModel();
// $itemModel2 = $queueApi->makeActionQueueItemModel();
// $itemModel1->class(\corbomite\queue\Noop::class);
// $itemModel2->class(\corbomite\queue\Noop::class);
// $itemModel2->method('noop');
// $batchModel->name('test_name');
// $batchModel->title('Test Name');
// $batchModel->addItem($itemModel1);
// $batchModel->addItem($itemModel2);
// $queueApi->addToQueue($batchModel);
// die;

// var_dump($queueApi->fetchOneBatch()->guid());
// var_dump($queueApi->fetchOneBatch()->items()[0]->guid());
// var_dump($queueApi->fetchOneBatch()->items()[1]->guid());
// die;

// var_dump($queueApi->getNextQueueItem());
// die;

// $queueApi->updateActionQueue('409dccd2-1e97-11e9-afe1-0242c0a8e004');
// die;

/** @noinspection PhpUnhandledExceptionInspection */
Di::diContainer()->get(Kernel::class)($argv);
exit();
