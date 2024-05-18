<?php

namespace Henrik\Session;

use henrik\sl\ServiceScope;
use Hk\Contracts\ComponentInterface;
use Hk\Contracts\Session\CookieManagerInterface;
use Hk\Contracts\Session\SessionInterface;

class SessionComponent implements ComponentInterface
{
    public function getServices(): array
    {
        return [
            ServiceScope::SINGLETON->value => [
                [
                    'id'     => SessionInterface::class,
                    'class'  => Session::class,
                    'params' => [
                        'savePath' => __DIR__ . '/../../../var/session',
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
}