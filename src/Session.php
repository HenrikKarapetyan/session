<?php

declare(strict_types=1);

namespace Henrik\Session;

use Henrik\Contracts\Session\CookieInterface;
use Henrik\Contracts\Session\SessionInterface;
use Henrik\Contracts\Session\SessionSegmentInterface;
use Henrik\Session\Traits\SegmentOperationsTrait;
use Henrik\Session\Traits\SessionControlTrait;
use Henrik\Session\Traits\SessionCookieOptionsTrait;
use Henrik\Session\Traits\SessionFlashTrait;
use Henrik\Session\Traits\SessionPropsTrait;

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
    use SessionPropsTrait;
    use SegmentOperationsTrait;

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
     * @param string                 $name
     * @param string                 $savePath
     * @param callable|null          $deleteCookie
     */
    public function __construct(array $cookies = [], string $name = 'default', string $savePath = '/', ?callable $deleteCookie = null)
    {
        $this->setName($name);
        $this->setCookies($cookies);
        $this->deleteCookie = $deleteCookie;
        $this->setDeleteCookie();
        $this->cookieParams = session_get_cookie_params();
        $this->setSavePath($savePath);
    }

    public function setSegment(SessionSegmentInterface $segment): void
    {
        $this->setSegmentName($segment->getSegmentName());
    }
}
