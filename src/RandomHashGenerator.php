<?php

declare(strict_types=1);

namespace Henrik\Session;

use Exception;
use Henrik\Session\Exceptions\HashGenerationException;

/**
 * Class RandomHashGenerator.
 */
class RandomHashGenerator
{
    /**
     * @throws Exception
     *
     * @return string
     */
    public static function generate(): string
    {
        $bytes = 32;

        if (function_exists('random_bytes')) {
            return random_bytes($bytes);
        }

        if (extension_loaded('openssl')) {
            return openssl_random_pseudo_bytes($bytes);
        }

        $message = 'Cannot generate cryptographically secure random values. '
            . "Please install extension 'openssl' or 'mcrypt', or use "
            . 'another cryptographically secure implementation.';

        throw new HashGenerationException($message);
    }
}