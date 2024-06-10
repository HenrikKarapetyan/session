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
                    'id'     => SessionInterface::class,
                    'class'  => Session::class,
                    'params' => [
                        'savePath' => MarkersInterface::AS_SERVICE_PARAM_MARKER . 'sessionSavePath',
                        'cookies'  => MarkersInterface::AS_SERVICE_PARAM_MARKER . 'cookies',
                        'name'     => MarkersInterface::AS_SERVICE_PARAM_MARKER . 'sessionName',
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