<?php
declare(strict_types=1);

use corbomite\queue\actions\CreateMigrationsAction;

return [
    'queue' => [
        'description' => 'Corbomite Queue Commands',
        'commands' => [
            'create-migrations' => [
                'description' => 'Adds migrations to create queue tables',
                'class' => CreateMigrationsAction::class,
            ],
        ],
    ],
];
