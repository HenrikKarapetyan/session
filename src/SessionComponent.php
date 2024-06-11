<?php

declare(strict_types=1);

namespace Henrik\Session;

use Henrik\Contracts\BaseComponent;
use Henrik\Contracts\Enums\ServiceScope;
use Henrik\Contracts\Session\CookieManagerInterface;
use Henrik\Contracts\Session\SessionInterface;
use Henrik\Contracts\Utils\MarkersInterface;

class SessionComponent extends BaseComponent
{
    public function getServices(): array
    {
        return [
            ServiceScope::PROTOTYPE->value => [
                [
                    'id'    => SessionInterface::class,
                    'class' => Session::class,
                    'args'  => [
                        'sessionSavePath' => MarkersInterface::AS_SERVICE_PARAM_MARKER . 'sessionSavePath',
                        'sessionCookies'  => MarkersInterface::AS_SERVICE_PARAM_MARKER . 'cookies',
                        'sessionName'     => MarkersInterface::AS_SERVICE_PARAM_MARKER . 'sessionName',
                    ],
                ],
            ],
            ServiceScope::SINGLETON->value => [

                [
                    'id'    => CSRFHashInterface::class,
                    'class' => CSRFHash::class,
                ],

                [
                    'id'    => CSRFTokenInterface::class,
                    'class' => CSRFToken::class,
                ],

                [
                    'id'    => CookieManagerInterface::class,
                    'class' => CookieManager::class,
                ],
            ],
        ];
    }
}