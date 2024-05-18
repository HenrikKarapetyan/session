<?php

namespace Henrik\Session;

interface SessionInterface
{
    /**
     * @return void
     */
    public function setDeleteCookie(): void;

    /**
     * @return bool
     */
    public function isResumable(): bool;

    /**
     * @return bool
     */
    public function isStarted(): bool;

    /**
     * @return bool
     */
    public function start(): bool;

    /**
     * @return bool
     */
    public function resume(): bool;

    /**
     * @return bool
     */
    public function clear(): bool;

    /**
     * @return bool
     */
    public function commit(): bool;

    /**
     * @return bool
     */
    public function destroy(): bool;

    /**
     * @param int $expire
     *
     * @return false|int
     */
    public function setCacheExpire(int $expire): false|int;

    /**
     * @return false|int
     */
    public function getCacheExpire(): false|int;

    /**
     * @param string $limiter
     *
     * @return false|string
     */
    public function setCacheLimiter(string $limiter): false|string;

    /**
     * @param array<CookieInterface> $cookies
     */
    public function setCookies(array $cookies): void;

    /**
     * @return false|string
     */
    public function getCacheLimiter(): false|string;

    /**
     * @param array<int|string, bool|string|int> $params
     */
    public function setCookieParams(array $params): void;

    /**
     * @return array<int|string, bool|string|int>
     */
    public function getCookieParams(): array;

    /**
     * @return false|string
     */
    public function getId(): false|string;

    /**
     * @param string $name
     *
     * @return false|string
     */
    public function setName(string $name): false|string;

    /**
     * @return false|string
     */
    public function getName(): false|string;

    /**
     * @param string $path
     *
     * @return false|string
     */
    public function setSavePath(string $path): false|string;

    /**
     * @return false|string
     */
    public function getSavePath(): false|string;

    /**
     * @return string|null
     */
    public function getSegmentName(): ?string;

    /**
     * @param string $segmentName
     */
    public function setSegmentName(string $segmentName): void;

    /**
     * Sets a flash value for the *next* request *and* the current one.
     *
     * @param string $key the key for the flash value
     * @param mixed  $val the flash value itself
     */
    public function setFlashNow(string $key, mixed $val): void;

    /**
     * Gets the flash value for a key in the *next* request.
     *
     * @param string $key the key for the flash value
     * @param mixed  $alt an alternative value to return if the key is not set
     *
     * @return mixed the flash value itself
     */
    public function getFlashNext(string $key, mixed $alt = null): mixed;

    /**
     * Returns the value of a key in the segment.
     *
     * @param string $key the key in the segment
     * @param mixed  $alt an alternative value to return if the key is not set
     *
     * @return mixed
     */
    public function get(string $key, mixed $alt = null): mixed;

    /**
     * Sets the value of a key in the segment.
     *
     * @param string $key the key to set
     * @param mixed  $val the value to set it to
     */
    public function set(string $key, mixed $val): void;

    /**
     * Clear all data from the segment.
     *
     * @return void
     */
    public function clearSegment(): void;

    /**
     * Sets a flash value for the *next* request.
     *
     * @param string $key the key for the flash value
     * @param mixed  $val the flash value itself
     */
    public function setFlash(string $key, mixed $val): void;

    /**
     * Gets the flash value for a key in the *current* request.
     *
     * @param string $key the key for the flash value
     * @param mixed  $alt an alternative value to return if the key is not set
     *
     * @return mixed the flash value itself
     */
    public function getFlash(string $key, mixed $alt = null): mixed;

    /**
     * Clears flash values for *only* the next request.
     *
     * @return void
     */
    public function clearFlash(): void;

    /**
     * Clears flash values for *both* the next request *and* the current one.
     *
     * @return void
     */
    public function clearFlashNow(): void;

    /**
     * Retains all the current flash values for the next request; values that
     * already exist for the next request take precedence.
     *
     * @return void
     */
    public function keepFlash(): void;
}