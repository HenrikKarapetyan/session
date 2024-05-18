<?php

declare(strict_types=1);

namespace Henrik\Session;

use Hk\Contracts\Session\CookieInterface;

/**
 * Class Cookie.
 */
class Cookie implements CookieInterface
{
    private string $name;

    private string $value;
    /**
     * @var int
     */
    private int $expire = 0;
    /**
     * @var string
     */
    private string $path = '/';

    private string $domain;
    /**
     * @var bool
     */
    private bool $secure = false;
    /**
     * @var bool
     */
    private bool $httpOnly = true;

    /**
     * @param int $expire
     */
    public function setExpire(int $expire): void
    {
        if ($this->isValidTimeStamp($expire)) {
            $this->expire = $expire;
        }
    }

    /**
     * @param bool $expireSessionCookies
     *
     * @return bool
     */
    public function isExpired(bool $expireSessionCookies = false): bool
    {
        if (!$this->expire && $expireSessionCookies) {
            return true;
        }

        if (!$this->expire) {
            return false;
        }

        return $this->expire < time();
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @param string $domain
     */
    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @param bool $secure
     */
    public function setSecure(bool $secure): void
    {
        $this->secure = $secure;
    }

    /**
     * @param bool $httpOnly
     */
    public function setHttpOnly(bool $httpOnly): void
    {
        $this->httpOnly = $httpOnly;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getExpire(): int
    {
        return $this->expire;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @return bool
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * @param int $timestamp
     *
     * @return bool
     */
    private function isValidTimeStamp(int $timestamp): bool
    {
        return ($timestamp <= PHP_INT_MAX) && ($timestamp >= ~PHP_INT_MAX);
    }
}
