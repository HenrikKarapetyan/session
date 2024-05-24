<?php

declare(strict_types=1);

namespace Henrik\Session;

use Henrik\Contracts\Session\CookieInterface;
use Henrik\Session\Exceptions\CannotParseCookieParamsException;
use InvalidArgumentException;

/**
 * Class Cookie.
 *
 * @SuppressWarnings(PHPMD)
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
     * @return string
     */
    public function __toString(): string
    {
        $cookie = $this->name . '=' . rawurlencode($this->value);

        if ($this->expire !== 0) {
            $cookie .= '; Expires=' . gmdate('r', $this->expire);
        }

        $cookie .= '; Domain=' . $this->domain;
        $cookie .= '; Path=' . $this->path;

        if ($this->secure) {
            $cookie .= '; Secure';
        }

        if ($this->httpOnly) {
            $cookie .= '; HttpOnly';
        }

        return $cookie;
    }

    /**
     * Creates a cookie from the contents of a Set-Cookie header.
     *
     * @param string $string
     *
     * @throws InvalidArgumentException         if the cookie string is not valid
     * @throws CannotParseCookieParamsException
     *
     * @return Cookie the cookie
     */
    public static function parse(string $string): self
    {
        $parts = preg_split('/;\s*/', $string);

        if ($parts) {
            $nameValue = explode('=', array_shift($parts), 2);

            if (count($nameValue) !== 2) {
                throw new InvalidArgumentException('The cookie string is not valid.');
            }

            [$name, $value] = $nameValue;

            if ($name === '') {
                throw new InvalidArgumentException('The cookie string is not valid.');
            }

            if ($value === '') {
                throw new InvalidArgumentException('The cookie string is not valid.');
            }

            $value    = rawurldecode($value);
            $expires  = 0;
            $path     = null;
            $domain   = null;
            $secure   = false;
            $httpOnly = false;

            foreach ($parts as $part) {
                switch (strtolower($part)) {
                    case 'secure':
                        $secure = true;

                        break;

                    case 'httponly':
                        $httpOnly = true;

                        break;

                    default:
                        $elements = explode('=', $part, 2);

                        if (count($elements) === 2) {
                            switch (strtolower($elements[0])) {
                                case 'expires':
                                    // Using @ to suppress the timezone warning, might not be the best thing to do.
                                    if (is_int($time = @strtotime($elements[1]))) {
                                        $expires = $time;
                                    }

                                    break;

                                case 'path':
                                    $path = $elements[1];

                                    break;

                                case 'domain':
                                    $domain = strtolower(ltrim($elements[1], '.'));
                            }
                        }
                }
            }

            return (new Cookie())->setExpire($expires)->setPath((string) $path)
                ->setDomain((string) $domain)
                ->setSecure($secure)
                ->setHttpOnly($httpOnly);
        }

        throw new CannotParseCookieParamsException();
    }

    /**
     * @param int $expire
     *
     * @return Cookie
     */
    public function setExpire(int $expire): self
    {
        if ($this->isValidTimeStamp($expire)) {
            $this->expire = $expire;
        }

        return $this;
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
     *
     * @return Cookie
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return Cookie
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param string $path
     *
     * @return Cookie
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $domain
     *
     * @return Cookie
     */
    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @param bool $secure
     *
     * @return Cookie
     */
    public function setSecure(bool $secure): self
    {
        $this->secure = $secure;

        return $this;
    }

    /**
     * @param bool $httpOnly
     *
     * @return Cookie
     */
    public function setHttpOnly(bool $httpOnly): self
    {
        $this->httpOnly = $httpOnly;

        return $this;
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
