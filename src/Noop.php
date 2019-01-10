<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

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
