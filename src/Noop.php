<?php
declare(strict_types=1);

namespace corbomite\queue;

class Noop
{
    public function __invoke()
    {
        // var_dump('invoke');
    }

    public function noop()
    {
        // var_dump('noop');
    }
}
