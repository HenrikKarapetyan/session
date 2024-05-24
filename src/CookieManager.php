<?php

declare(strict_types=1);

namespace Henrik\Session;

use Henrik\Container\Container;
use Henrik\Container\ContainerModes;
use Henrik\Container\Exceptions\KeyAlreadyExistsException;
use Henrik\Container\Exceptions\UndefinedModeException;
use Henrik\Contracts\Session\CookieManagerInterface;

/**
 * Class CookieManager.
 */
class CookieManager extends Container implements CookieManagerInterface
{
    /**
     * CookieManager constructor.
     *
     * @throws UndefinedModeException
     */
    public function __construct()
    {
        $this->changeMode(ContainerModes::SINGLE_VALUE_MODE);
    }

    /**
     * @param callable $callback
     *
     * @throws KeyAlreadyExistsException
     */
    public function newCookie(callable $callback): void
    {
        $cookie = new Cookie();
        $callback($cookie);
        $this->set($cookie->getName(), $cookie);
    }

    /**
     * @return array<string, mixed>
     */
    public function getCookies(): array
    {
        return $this->getAll();
    }
}