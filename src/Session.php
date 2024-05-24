<?php

declare(strict_types=1);

namespace Henrik\Session;

use Henrik\Contracts\Session\CookieInterface;
use Henrik\Contracts\Session\SessionInterface;
use Henrik\Session\Traits\SessionFlashTrait;

/**
 * Class Session.
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class Session implements SessionInterface
{
    use SessionFlashTrait;

    /**
     * @var array<CookieInterface>
     */
    protected array $cookies;
    /**
     * @var array<int|string, bool|string|int>
     */
    protected array $cookieParams = [];
    /**
     * @var callable|null
     */
    protected $deleteCookie;

    /**
     * @var string
     */
    protected string $name = 'default';

    protected ?string $segmentName = null;

    /**
     * Session constructor.
     *
     * @param array<CookieInterface> $cookies
     * @param string                 $savePath
     * @param callable|null          $deleteCookie
     */
    public function __construct(array $cookies = [], string $savePath = '/', ?callable $deleteCookie = null)
    {
        $this->setName($this->name);
        $this->setCookies($cookies);
        $this->deleteCookie = $deleteCookie;
        $this->setDeleteCookie();
        $this->cookieParams = session_get_cookie_params();
        $this->setSavePath($savePath);
    }

    /**
     * {@inheritDoc}
     */
    public function setDeleteCookie(): void
    {
        if (!$this->deleteCookie) {
            $this->deleteCookie = function ($name, $params) {
                setcookie($name, '', time() - 42000, $params['path'], $params['domain']);
            };
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isResumable(): bool
    {
        return isset($this->cookies[$this->getName()]);
    }

    /**
     * {@inheritDoc}
     */
    public function isStarted(): bool
    {
        $started = $this->sessionStatus();

        if (function_exists('session_status')) {
            $started = session_status() === PHP_SESSION_ACTIVE;
        }

        // if the session was started externally, move the flash values forward
        if ($started && !$this->flashMoved) {
            $this->moveFlash();
        }

        return $started;
    }

    /**
     * {@inheritDoc}
     */
    public function start(): bool
    {
        $result = session_start();

        if ($result) {
            $this->moveFlash();
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function resume(): bool
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
     * {@inheritDoc}
     */
    public function clear(): bool
    {
        return session_unset();
    }

    /**
     * {@inheritDoc}
     */
    public function commit(): bool
    {
        return session_write_close();
    }

    /**
     * {@inheritDoc}
     */
    public function destroy(): bool
    {
        if (!$this->isStarted()) {
            $this->start();
        }

        $name   = $this->getName();
        $params = $this->getCookieParams();
        $this->clear();

        $destroyed = session_destroy();

        if ($destroyed && $this->deleteCookie) {
            call_user_func($this->deleteCookie, $name, $params);
        }

        return $destroyed;
    }

    /**
     * {@inheritDoc}
     */
    public function setCacheExpire(int $expire): false|int
    {
        return session_cache_expire($expire);
    }

    /**
     * {@inheritDoc}
     */
    public function getCacheExpire(): false|int
    {
        return session_cache_expire();
    }

    /**
     * {@inheritDoc}
     */
    public function setCacheLimiter(string $limiter): false|string
    {
        return session_cache_limiter($limiter);
    }

    /**
     * {@inheritDoc}
     */
    public function setCookies(array $cookies): void
    {
        $this->cookies = $cookies;

        foreach ($cookies as $cookie) {
            setcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpire(), $cookie->getPath(), $cookie->getDomain());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getCacheLimiter(): false|string
    {
        return session_cache_limiter();
    }

    /**
     * {@inheritDoc}
     */
    public function setCookieParams(array $params): void
    {
        $this->cookieParams = array_merge($this->cookieParams, $params);
        session_set_cookie_params(
            (int) $this->cookieParams['lifetime'],
            (string) $this->cookieParams['path'],
            (string) $this->cookieParams['domain'],
            (bool) $this->cookieParams['secure'],
            (bool) $this->cookieParams['httponly']
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): false|string
    {
        return session_id();
    }

    /**
     * {@inheritDoc}
     */
    public function setName(string $name): false|string
    {
        return session_name($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): false|string
    {
        return session_name();
    }

    /**
     * {@inheritDoc}
     */
    public function setSavePath(string $path): false|string
    {
        return session_save_path($path);
    }

    /**
     * {@inheritDoc}
     */
    public function getSavePath(): false|string
    {
        return session_save_path();
    }

    /**
     * {@inheritDoc}
     */
    public function getSegmentName(): ?string
    {
        if (is_null($this->segmentName)) {
            $this->segmentName = 'default';
        }

        return $this->segmentName;
    }

    /**
     * {@inheritDoc}
     */
    public function setSegmentName(string $segmentName): void
    {

        $this->segmentName = $segmentName;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key, mixed $alt = null): mixed
    {
        if (!$this->resumeSession()) {
            $this->start();
        }

        return isset($_SESSION[$this->getSegmentName()][$key])
            ? $_SESSION[$this->getSegmentName()][$key]
            : $alt;
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $key, mixed $val): void
    {
        $this->resumeOrStartSession();
        $_SESSION[$this->getSegmentName()][$key] = $val;
    }

    /**
     * {@inheritDoc}
     */
    public function clearSegment(): void
    {
        if ($this->resumeSession()) {
            $_SESSION[$this->getSegmentName()] = [];
        }
    }

    /**
     * @return bool
     */
    protected function sessionStatus(): bool
    {
        $setting = 'session.use_trans_sid';
        $current = ini_get($setting);
        $level   = error_reporting(0);
        $result  = ini_set($setting, $current);
        error_reporting($level);

        return $result !== $current;
    }

    /**
     * Loads the segment only if the session has already been started, or if
     * a session is available (in which case it resumes the session first).
     *
     * @return bool
     */
    protected function resumeSession(): bool
    {
        if ($this->isStarted() || $this->resume()) {
            $this->load();

            return true;
        }

        return false;
    }

    /**
     * Sets the segment properties to $_SESSION references.
     *
     * @return void
     */
    protected function load(): void
    {
        if (!isset($_SESSION[$this->getSegmentName()])) {
            $_SESSION[$this->getSegmentName()] = [];
        }

        $this->loadFlashes();
    }

    /**
     * Resumes a previous session, or starts a new one, and loads the segment.
     *
     * @return void
     */
    protected function resumeOrStartSession(): void
    {
        if (!$this->resumeSession()) {
            $this->start();
            $this->load();
        }
    }
}
