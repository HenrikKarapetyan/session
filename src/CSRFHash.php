<?php
/**
 * Author Henrik Karapetyan
 * Copyright (c) 2018.
 */

/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 6/17/2018
 * Time: 9:45 AM
 */

namespace henrik\session;

/**
 * Class CSRFHash
 * @package henrik\session\src
 */
class CSRFHash
{

    /**
     * @var $hash string
     */
    private $hash;

    /**
     * @param $value
     * @return bool
     * @throws \Exception
     */
    public function isValid($value)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($value, $this->getValue());
        }

        return $value === $this->getValue();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getValue()
    {
        if (empty($this->hash)) {
            $this->regenerateValue();
        }
        return $this->hash;
    }

    /**
     * @throws \Exception
     */
    public function regenerateValue()
    {
        $this->hash = hash('sha512', RandomHashGenerator::generate());
    }
}