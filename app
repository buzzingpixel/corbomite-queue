#!/usr/bin/env php
<?php
declare(strict_types=1);

use corbomite\di\Di;
use corbomite\cli\Kernel;

define('ENTRY_POINT', 'app');
define('APP_BASE_PATH', __DIR__);
define('APP_VENDOR_PATH', APP_BASE_PATH . '/vendor');

require APP_VENDOR_PATH . '/autoload.php';

/** @noinspection PhpUnhandledExceptionInspection */
Di::get(Kernel::class)($argv);
exit();
