<?php

declare(strict_types=1);

namespace Henrik\Session;

use Henrik\Contracts\Session\CookieInterface;
use Henrik\Contracts\Session\SessionInterface;
use Henrik\Filesystem\Filesystem;
use Henrik\Session\Traits\SessionControlTrait;
use Henrik\Session\Traits\SessionCookieOptionsTrait;
use Henrik\Session\Traits\SessionFlashTrait;

/**
 * Class Session.
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class Session implements SessionInterface
{
    use SessionControlTrait;
    use SessionFlashTrait;
    use SessionCookieOptionsTrait;

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
    public function getCacheLimiter(): false|string
    {
        return session_cache_limiter();
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
        if (!is_dir($path)) {
            Filesystem::mkdir($path);
        }

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
}
