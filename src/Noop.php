<?php
declare(strict_types=1);

namespace corbomite\queue;

class Noop
{
    public function __invoke()
    {
    }

    public function noop()
    {
    }
}
