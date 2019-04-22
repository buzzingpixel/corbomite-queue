<?php

declare(strict_types=1);

namespace corbomite\tests;

use corbomite\queue\services\DeadBatchCheckService;
use PHPUnit\Framework\TestCase;

class ScheduleConfigTest extends TestCase
{
    public function test() : void
    {
        $config = require TESTING_APP_PATH . '/src/scheduleConfig.php';

        self::assertEquals(
            [
                [
                    'class' => DeadBatchCheckService::class,
                    'runEvery' => 'Always',
                ],
            ],
            $config
        );
    }
}
