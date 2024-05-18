<?php

use Henrik\Session\CookieManager;
use \Henrik\Session\Cookie;
use Henrik\Session\Session;

require '../vendor/autoload.php';

$cookieManager = new CookieManager();
$cookieManager->newCookie(function (Cookie $cookie) {
    $cookie->setDomain('localhost');
    $cookie->setValue('asdasdasdasdasdasdasd');
    $cookie->setName('sample');
    $cookie->setSecure(false);
    $cookie->setPath('/fghfghfgh');
    $cookie->setExpire(1569850644);
});
$cookieManager->newCookie(function (Cookie $cookie) {
    $cookie->setDomain('localhost');
    $cookie->setName('sample2');
    $cookie->setValue('gfdffhfghfghfghfghfghgfhgfhtrytryr');
    $cookie->setSecure(true);
    $cookie->setPath('/dssdfsdf');
    $cookie->setExpire(1569850644);
});

$session = new Session($cookieManager->getCookies(), '../sess');

$session->start();

var_dump($session->getFlash('asd', 'asdasdasdasdasdsadas'));