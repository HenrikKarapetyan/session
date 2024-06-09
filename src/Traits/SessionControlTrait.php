<?php

namespace Henrik\Session\Traits;

/**
 * @SuppressWarnings(PHPMD.Superglobals)
 */
trait SessionControlTrait
{
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