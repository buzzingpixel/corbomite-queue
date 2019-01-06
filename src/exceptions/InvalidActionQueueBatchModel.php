<?php
declare(strict_types=1);

namespace corbomite\queue\exceptions;

use Exception;
use Throwable;

class InvalidActionQueueBatchModel extends Exception
{
    public function __construct(
        string $message = 'Invalid action queue batch model',
        int $code = 500,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
