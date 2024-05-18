<?php

declare(strict_types=1);

namespace Henrik\Session;

use henrik\container\Container;
use henrik\container\ContainerModes;
use henrik\container\exceptions\IdAlreadyExistsException;
use henrik\container\exceptions\UndefinedModeException;
use Hk\Contracts\Session\CookieManagerInterface;

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
     * @throws IdAlreadyExistsException
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