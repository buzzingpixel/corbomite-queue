<?php

declare(strict_types=1);

namespace corbomite\tests\actions\RunQueueAction;

use function define;

class ActionItemDoesNotThrowError
{
    public function customMethod() : void
    {
        define('CUSTOM_METHOD_ACTION_HAS_RUN', true);
    }
}
