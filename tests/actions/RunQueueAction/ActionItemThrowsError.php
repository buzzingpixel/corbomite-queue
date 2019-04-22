<?php

declare(strict_types=1);

namespace corbomite\tests\actions\RunQueueAction;

use Exception;

class ActionItemThrowsError
{
    public function __invoke() : void
    {
        throw new Exception('Test Message');
    }
}
