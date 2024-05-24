<?php

namespace Henrik\Session\Traits;

/**
 * @SuppressWarnings(PHPMD.Superglobals)
 */
trait SessionFlashTrait
{
    public const FLASH_NEXT = 'FLASH_NEXT';
    public const FLASH_NOW  = 'FLASH_NOW';

    /**
     * @var bool
     */
    protected bool $flashMoved = false;

    /**
     * {@inheritDoc}
     */
    public function setFlash(string $key, mixed $val): void
    {
        $this->resumeOrStartSession();
        $_SESSION[self::FLASH_NEXT][$this->getSegmentName()][$key] = $val;
    }

    /**
     * {@inheritDoc}
     */
    public function getFlash(string $key, mixed $alt = null): mixed
    {
        $this->resumeSession();

        return isset($_SESSION[self::FLASH_NOW][$this->getSegmentName()][$key])
            ? $_SESSION[self::FLASH_NOW][$this->getSegmentName()][$key]
            : $alt;
    }

    /**
     * {@inheritDoc}
     */
    public function clearFlash(): void
    {
        if ($this->resumeSession()) {
            $_SESSION[self::FLASH_NEXT][$this->getSegmentName()] = [];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function clearFlashNow(): void
    {
        if ($this->resumeSession()) {
            $_SESSION[self::FLASH_NOW][$this->getSegmentName()]  = [];
            $_SESSION[self::FLASH_NEXT][$this->getSegmentName()] = [];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function keepFlash(): void
    {
        if ($this->resumeSession()) {
            $_SESSION[self::FLASH_NEXT][$this->getSegmentName()] = array_merge(
                $_SESSION[self::FLASH_NEXT][$this->getSegmentName()],
                $_SESSION[self::FLASH_NOW][$this->getSegmentName()]
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setFlashNow(string $key, mixed $val): void
    {
        $this->resumeOrStartSession();
        $_SESSION[self::FLASH_NOW][$this->getSegmentName()][$key]  = $val;
        $_SESSION[self::FLASH_NEXT][$this->getSegmentName()][$key] = $val;
    }

    /**
     * {@inheritDoc}
     */
    public function getFlashNext(string $key, mixed $alt = null): mixed
    {
        $this->resumeSession();

        return isset($_SESSION[self::FLASH_NEXT][$this->getSegmentName()][$key])
            ? $_SESSION[self::FLASH_NEXT][$this->getSegmentName()][$key]
            : $alt;
    }

    public function loadFlashes(): void
    {
        if (!isset($_SESSION[self::FLASH_NOW][$this->getSegmentName()])) {
            $_SESSION[self::FLASH_NOW][$this->getSegmentName()] = [];
        }

        if (!isset($_SESSION[self::FLASH_NEXT][$this->getSegmentName()])) {
            $_SESSION[self::FLASH_NEXT][$this->getSegmentName()] = [];
        }
    }

    protected function moveFlash(): void
    {
        if (!isset($_SESSION[self::FLASH_NEXT])) {
            $_SESSION[self::FLASH_NEXT] = [];
        }
        $_SESSION[self::FLASH_NOW]  = $_SESSION[self::FLASH_NEXT];
        $_SESSION[self::FLASH_NEXT] = [];
        $this->flashMoved           = true;
    }
}