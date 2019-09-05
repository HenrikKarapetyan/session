<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 2/4/2018
 * Time: 4:37 PM
 */

namespace henrik\session;


/**
 * Class Cookie
 * @package henrik\session
 */
class Cookie
{
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $value;
    /**
     * @var int
     */
    private $expire = 0; // old value is '0'
    /**
     * @var string
     */
    private $path = '/';
    /**
     * @var
     */
    private $domain;
    /**
     * @var bool
     */
    private $secure = false;
    /**
     * @var bool
     */
    private $httpOnly = true;


    /**
     * @param $expire
     */
    public function setExpire($expire)
    {
        $this->expire = null;
        if ($expire !== null) {
            $this->expire = $this->isValidTimeStamp($expire) ? $expire : strtotime($expire);
        }
    }

    /**
     * @param bool $expire_session_cookies
     * @return bool
     */
    public function isExpired($expire_session_cookies = false)
    {
        if (!$this->expire && $expire_session_cookies) {
            return true;
        } else if (!$this->expire) {
            // FIXME Usage of ELSE IF is discouraged; use ELSEIF instead
            return false;
        }

        return $this->expire < time();
    }

    /**
     * @param $timestamp
     * @return bool
     */
    private function isValidTimeStamp($timestamp)
    {
        return (((int)$timestamp === $timestamp) ||
                ((string)(int)$timestamp === $timestamp)) &&
            ($timestamp <= PHP_INT_MAX) &&
            ($timestamp >= ~PHP_INT_MAX);
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @param bool $secure
     */
    public function setSecure($secure)
    {
        $this->secure = $secure;
    }

    /**
     * @param $httpOnly
     */
    public function setHttpOnly($httpOnly)
    {
        $this->httpOnly = $httpOnly;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return bool
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * @return bool
     */
    public function getHttpOnly()
    {
        return $this->httpOnly;
    }
}
