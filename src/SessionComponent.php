<?php

namespace Henrik\Session;

use Hk\Contracts\BaseComponent;
use Hk\Contracts\Enums\ServiceScope;
use Hk\Contracts\Session\CookieManagerInterface;
use Hk\Contracts\Session\SessionInterface;

class SessionComponent extends BaseComponent
{
    public function getServices(): array
    {
        return [
            ServiceScope::SINGLETON->value => [
                [
                    'id'     => SessionInterface::class,
                    'class'  => Session::class,
                    'params' => [
                        'savePath' => $this->getBasePath() . '/var/session/',
                    ],
                ],

                [
                    'id'    => CookieManagerInterface::class,
                    'class' => CookieManager::class,
                ],
            ],
        ];
    }
}