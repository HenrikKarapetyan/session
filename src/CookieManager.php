<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 2/4/2018
 * Time: 5:01 PM
 */

namespace henrik\session;


use henrik\container\Container;
use henrik\container\ContainerModes;

/**
 * Class CookieManager
 * @package henrik\session
 */
class CookieManager extends Container
{


    /**
     * CookieManager constructor.
     * @throws \henrik\container\UndefinedModeException
     */
    public function __construct()
    {
        $this->change_mode(ContainerModes::SINGLE_VALUE_MODE);
    }

    /**
     * @param callable $callback
     * @throws \henrik\container\exceptions\IdAlreadyExistsException
     * @throws \henrik\container\exceptions\TypeException
     */
    public function newCookie(callable $callback)
    {
        $cookie = new Cookie();
        $callback($cookie);
        $this->set($cookie->getName(), $cookie);
    }

    /**
     * @return array
     */
    public function getCookies()
    {
        return $this->getAll();
    }


}