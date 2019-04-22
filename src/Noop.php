<?php

declare(strict_types=1);

namespace corbomite\queue;

class Noop
{
    public function __invoke() : void
    {
        // var_dump('invoke');
        // die;
    }

    public function noop() : void
    {
        // var_dump('noop');
        // die;
    }
}
