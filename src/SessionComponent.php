<?php

namespace Henrik\Session;

use Hk\Contracts\ComponentInterface;
use Hk\Contracts\Enums\ServiceScope;
use Hk\Contracts\Session\CookieManagerInterface;
use Hk\Contracts\Session\SessionInterface;

class SessionComponent implements ComponentInterface
{
    private string $basePath = '';

    public function getServices(): array
    {
        return [
            ServiceScope::SINGLETON->value => [
                [
                    'id'     => SessionInterface::class,
                    'class'  => Session::class,
                    'params' => [
                        'savePath' => __DIR__ . $this->getBasePath() . '/var/session',
                    ],
                ],

                [
                    'id'    => CookieManagerInterface::class,
                    'class' => CookieManager::class,
                ],
            ],
        ];
    }

    public function getControllersPath(): string
    {
        return '';
    }

    public function getTemplatesPath(): string
    {
        return '';
    }

    public function getEventSubscribers(): array
    {
        return [];
    }

    public function dependsOn(): array
    {
        return [];
    }

    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }
}