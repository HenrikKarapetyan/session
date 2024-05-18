<?php

namespace Henrik\Session;

interface CookieInterface
{
    /**
     * @param int $expire
     */
    public function setExpire(int $expire): void;

    /**
     * @param bool $expireSessionCookies
     *
     * @return bool
     */
    public function isExpired(bool $expireSessionCookies = false): bool;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @param string $value
     */
    public function setValue(string $value): void;

    /**
     * @param string $path
     */
    public function setPath(string $path): void;

    /**
     * @param string $domain
     */
    public function setDomain(string $domain): void;

    /**
     * @param bool $secure
     */
    public function setSecure(bool $secure): void;

    /**
     * @param bool $httpOnly
     */
    public function setHttpOnly(bool $httpOnly): void;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @return int
     */
    public function getExpire(): int;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return string
     */
    public function getDomain(): string;

    /**
     * @return bool
     */
    public function isSecure(): bool;

    /**
     * @return bool
     */
    public function isHttpOnly(): bool;
}