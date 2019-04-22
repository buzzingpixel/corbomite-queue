<?php

declare(strict_types=1);

use corbomite\queue\services\DeadBatchCheckService;

return [
    [
        'class' => DeadBatchCheckService::class,
        'runEvery' => 'Always',
    ],
];
