<?php

declare(strict_types=1);

namespace Henrik\Session\Exceptions;

use Exception;
use Throwable;

/**
 * Class SessionException.
 */
class SessionException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}