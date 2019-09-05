<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 1/24/2018
 * Time: 1:15 PM
 */

namespace henrik\session;

use henrik\session\exceptions\HashGenerationException;

/**
 * Class RandomHashGenerator
 * @package sparrow\security
 */
class RandomHashGenerator
{
    /**
     * RandomValueGenerator constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function generate()
    {
        $bytes = 32;

        if (function_exists('random_bytes')) {
            return random_bytes($bytes);
        }

        if (extension_loaded('openssl')) {
            return openssl_random_pseudo_bytes($bytes);
        }

        $message = "Cannot generate cryptographically secure random values. "
            . "Please install extension 'openssl' or 'mcrypt', or use "
            . "another cryptographically secure implementation.";

        throw new HashGenerationException($message);
    }
}