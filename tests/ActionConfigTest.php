<?php

declare(strict_types=1);

namespace corbomite\tests;

use corbomite\queue\actions\CreateMigrationsAction;
use corbomite\queue\actions\RunQueueAction;
use PHPUnit\Framework\TestCase;

class ActionConfigTest extends TestCase
{
    public function test() : void
    {
        $config = require TESTING_APP_PATH . '/src/actionConfig.php';

        self::assertEquals(
            [
                'queue' => [
                    'description' => 'Corbomite Queue Commands',
                    'commands' => [
                        'create-migrations' => [
                            'description' => 'Adds migrations to create queue tables',
                            'class' => CreateMigrationsAction::class,
                        ],
                        'run' => [
                            'description' => 'Runs next item in queue (use bash while loop and supervisor to run every second)',
                            'class' => RunQueueAction::class,
                        ],
                    ],
                ],
            ],
            $config
        );
    }
}
