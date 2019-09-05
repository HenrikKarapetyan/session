<?php

namespace henrik\session;


/**
 * Class Session
 * @package henrik\session
 */
class Session
{
    /**
     *
     */
    const FLASH_NEXT = 'Flash_Next';
    /**
     *
     */
    const FLASH_NOW = 'Flash_Now';

    /**
     * @var
     */
    protected $cookies;
    /**
     * @var array
     */
    protected $cookie_params = array();
    /**
     * @var null
     */
    protected $delete_cookie;
    /**
     * @var bool
     */
    protected $flash_moved = false;
    /**
     * @var string
     */
    protected $name = 'default-session';
    /**
     * @var
     */
    protected $segment_name;

    /**
     * Session constructor.
     * @param array $cookies
     * @param string $save_path
     * @param null $delete_cookie
     */
    public function __construct($cookies = [], $save_path = '', $delete_cookie = null)
    {
        $this->setName($this->name);
        $this->setCookies($cookies);
        $this->delete_cookie = $delete_cookie;
        $this->setDeleteCookie();
        $this->cookie_params = session_get_cookie_params();
        $this->setSavePath($save_path);
    }

    /**
     *
     */
    public function setDeleteCookie()
    {
        if (!$this->delete_cookie) {
            $this->delete_cookie = function ($name, $params) {
                setcookie($name, '', time() - 42000, $params['path'], $params['domain']);
            };
        }
    }

    /**
     * @return bool
     */
    public function isResumable()
    {
        return isset($this->cookies[$this->getName()]);
    }

    /**
     * @return bool
     */
    public function isStarted()
    {
        if (function_exists('session_status')) {
            $started = session_status() === PHP_SESSION_ACTIVE;
        } else {
            $started = $this->sessionStatus();
        }

        // if the session was started externally, move the flash values forward
        if ($started && !$this->flash_moved) {
            $this->moveFlash();
        }

        // done
        return $started;
    }

    /**
     * @return bool
     */
    protected function sessionStatus()
    {
        $setting = 'session.use_trans_sid';
        $current = ini_get($setting);
        $level = error_reporting(0);
        $result = ini_set($setting, $current);
        error_reporting($level);
        return $result !== $current;
    }

    /**
     * @return bool
     */
    public function start()
    {
        $result = session_start();
        if ($result) {
            $this->moveFlash();
        }
        return $result;
    }

    /**
     *
     */
    protected function moveFlash()
    {
        if (!isset($_SESSION[Session::FLASH_NEXT])) {
            $_SESSION[Session::FLASH_NEXT] = array();
        }
        $_SESSION[Session::FLASH_NOW] = $_SESSION[Session::FLASH_NEXT];
        $_SESSION[Session::FLASH_NEXT] = array();
        $this->flash_moved = true;
    }

    /**
     * @return bool
     */
    public function resume()
    {
        if ($this->isStarted()) {
            return true;
        }

        if ($this->isResumable()) {
            return $this->start();
        }

        return false;
    }

    /**
     *
     */
    public function clear()
    {
        return session_unset();
    }

    /**
     *
     */
    public function commit()
    {
        return session_write_close();
    }

    /**
     * @return bool
     */
    public function destroy()
    {
        if (!$this->isStarted()) {
            $this->start();
        }

        $name = $this->getName();
        $params = $this->getCookieParams();
        $this->clear();

        $destroyed = session_destroy();
        if ($destroyed) {
            call_user_func($this->delete_cookie, $name, $params);
        }

        return $destroyed;
    }

    // ======================================================================= //

    /**
     * @param $expire
     * @return int
     */
    public function setCacheExpire($expire)
    {
        return session_cache_expire($expire);
    }

    /**
     * @return int
     */
    public function getCacheExpire()
    {
        return session_cache_expire();
    }

    /**
     * @param $limiter
     * @return string
     */
    public function setCacheLimiter($limiter)
    {
        return session_cache_limiter($limiter);
    }

    /**
     * @param $cookies
     */
    public function setCookies($cookies)
    {
        $this->cookies = $cookies;
        /**
         * @var $cookie Cookie
         */
        foreach ($cookies as $id => $cookie) {
            setcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpire(), $cookie->getPath(), $cookie->getDomain());
        }
    }

    /**
     * @return string
     */
    public function getCacheLimiter()
    {
        return session_cache_limiter();
    }

    /**
     * @param array $params
     */
    public function setCookieParams(array $params)
    {
        $this->cookie_params = array_merge($this->cookie_params, $params);
        session_set_cookie_params(
            $this->cookie_params['lifetime'],
            $this->cookie_params['path'],
            $this->cookie_params['domain'],
            $this->cookie_params['secure'],
            $this->cookie_params['httponly']
        );
    }

    /**
     * @return array
     */
    public function getCookieParams()
    {
        return $this->cookie_params;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * @param $name
     * @return string
     */
    public function setName($name)
    {
        return session_name($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return session_name();
    }

    /**
     * @param $path
     * @return string
     */
    public function setSavePath($path)
    {
        return session_save_path($path);
    }

    /**
     * @return string
     */
    public function getSavePath()
    {
        return session_save_path();
    }

    /**
     * @return mixed
     */
    public function getSegmentName()
    {
        if (is_null($this->segment_name)) {
            $this->segment_name = "default";
        }
        return $this->segment_name;
    }

    /**
     * @param mixed $segment_name
     */
    public function setSegmentName($segment_name)
    {

        $this->segment_name = $segment_name;
    }


    /**
     *
     * Sets a flash value for the *next* request *and* the current one.
     *
     * @param string $key The key for the flash value.
     *
     * @param mixed $val The flash value itself.
     *
     */
    public function setFlashNow($key, $val)
    {
        $this->resumeOrStartSession();
        $_SESSION[Session::FLASH_NOW][$this->getSegmentName()][$key] = $val;
        $_SESSION[Session::FLASH_NEXT][$this->getSegmentName()][$key] = $val;
    }

    /**
     *
     * Gets the flash value for a key in the *next* request.
     *
     * @param string $key The key for the flash value.
     *
     * @param mixed $alt An alternative value to return if the key is not set.
     *
     * @return mixed The flash value itself.
     *
     */
    public function getFlashNext($key, $alt = null)
    {
        $this->resumeSession();
        return isset($_SESSION[Session::FLASH_NEXT][$this->getSegmentName()][$key])
            ? $_SESSION[Session::FLASH_NEXT][$this->getSegmentName()][$key]
            : $alt;
    }

    /**
     *
     * Returns the value of a key in the segment.
     *
     * @param string $key The key in the segment.
     *
     * @param mixed $alt An alternative value to return if the key is not set.
     *
     * @return mixed
     *
     */
    public function get($key, $alt = null)
    {
        $this->resumeSession();
        return isset($_SESSION[$this->getSegmentName()][$key])
            ? $_SESSION[$this->getSegmentName()][$key]
            : $alt;
    }


    /**
     *
     * Sets the value of a key in the segment.
     *
     * @param string $key The key to set.
     *
     * @param mixed $val The value to set it to.
     *
     */
    public function set($key, $val)
    {
        $this->resumeOrStartSession();
        $_SESSION[$this->getSegmentName()][$key] = $val;
    }


    /**
     *
     * Clear all data from the segment.
     *
     * @return void
     *
     */
    public function clearSegment()
    {
        if ($this->resumeSession()) {
            $_SESSION[$this->getSegmentName()] = array();
        }
    }

    /**
     *
     * Sets a flash value for the *next* request.
     *
     * @param string $key The key for the flash value.
     *
     * @param mixed $val The flash value itself.
     *
     */
    public function setFlash($key, $val)
    {
        $this->resumeOrStartSession();
        $_SESSION[Session::FLASH_NEXT][$this->getSegmentName()][$key] = $val;
    }


    /**
     *
     * Gets the flash value for a key in the *current* request.
     *
     * @param string $key The key for the flash value.
     *
     * @param mixed $alt An alternative value to return if the key is not set.
     *
     * @return mixed The flash value itself.
     *
     */
    public function getFlash($key, $alt = null)
    {
        $this->resumeSession();
        return isset($_SESSION[Session::FLASH_NOW][$this->getSegmentName()][$key])
            ? $_SESSION[Session::FLASH_NOW][$this->getSegmentName()][$key]
            : $alt;
    }

    /**
     *
     * Clears flash values for *only* the next request.
     *
     * @return void
     *
     */
    public function clearFlash()
    {
        if ($this->resumeSession()) {
            $_SESSION[Session::FLASH_NEXT][$this->getSegmentName()] = array();
        }
    }


    /**
     *
     * Clears flash values for *both* the next request *and* the current one.
     *
     * @return void
     *
     */
    public function clearFlashNow()
    {
        if ($this->resumeSession()) {
            $_SESSION[Session::FLASH_NOW][$this->getSegmentName()] = array();
            $_SESSION[Session::FLASH_NEXT][$this->getSegmentName()] = array();
        }
    }

    /**
     *
     * Retains all the current flash values for the next request; values that
     * already exist for the next request take precedence.
     *
     * @return void
     *
     */
    public function keepFlash()
    {
        if ($this->resumeSession()) {
            $_SESSION[Session::FLASH_NEXT][$this->getSegmentName()] = array_merge(
                $_SESSION[Session::FLASH_NEXT][$this->getSegmentName()],
                $_SESSION[Session::FLASH_NOW][$this->getSegmentName()]
            );
        }
    }


    /**
     *
     * Loads the segment only if the session has already been started, or if
     * a session is available (in which case it resumes the session first).
     *
     * @return bool
     *
     */
    protected function resumeSession()
    {
        if ($this->isStarted() || $this->resume()) {
            $this->load();
            return true;
        }

        return false;
    }

    /**
     *
     * Sets the segment properties to $_SESSION references.
     *
     * @return void
     *
     */
    protected function load()
    {
        if (!isset($_SESSION[$this->getSegmentName()])) {
            $_SESSION[$this->getSegmentName()] = [];
        }

        if (!isset($_SESSION[Session::FLASH_NOW][$this->getSegmentName()])) {
            $_SESSION[Session::FLASH_NOW][$this->getSegmentName()] = [];
        }

        if (!isset($_SESSION[Session::FLASH_NEXT][$this->getSegmentName()])) {
            $_SESSION[Session::FLASH_NEXT][$this->getSegmentName()] = [];
        }
    }


    /**
     *
     * Resumes a previous session, or starts a new one, and loads the segment.
     *
     * @return void
     *
     */
    protected function resumeOrStartSession()
    {
        if (!$this->resumeSession()) {
            $this->start();
            $this->load();
        }
    }

}
