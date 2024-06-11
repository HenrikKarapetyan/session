<?php

namespace Henrik\Session\Traits;

/**
 * @SuppressWarnings(PHPMD.Superglobals)
 */
trait SegmentOperationsTrait
{
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
}