<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

use corbomite\queue\actions\RunQueueAction;
use corbomite\queue\actions\CreateMigrationsAction;

return [
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
];
