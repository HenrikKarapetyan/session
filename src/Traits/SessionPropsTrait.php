<?php

namespace Henrik\Session\Traits;

use Henrik\Filesystem\Filesystem;

trait SessionPropsTrait
{
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