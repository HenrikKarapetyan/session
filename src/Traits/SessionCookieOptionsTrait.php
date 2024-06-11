<?php

namespace Henrik\Session\Traits;

trait SessionCookieOptionsTrait
{
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
    public function setCookies(array $cookies): void
    {
        $this->cookies = $cookies;

        foreach ($cookies as $cookie) {
            setcookie(
                name: $cookie->getName(),
                value: $cookie->getValue(),
                secure: $cookie->getExpire(),
                path: $cookie->getPath(),
                domain: $cookie->getDomain(),
                httponly: $cookie->isHttpOnly(),
            );
        }
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
}