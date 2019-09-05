<?php
require "../vendor/autoload.php";


use henrik\session\Cookie;
use henrik\session\CookieManager;
use henrik\session\Session;


$cookieManager = new CookieManager();
$cookieManager->newCookie(function (Cookie $cookie){
    $cookie->setDomain("localhost");
    $cookie->setValue("asdasdasdasdasdasdasd");
    $cookie->setName("sample");
    $cookie->setSecure(false);
    $cookie->setPath("/fghfghfgh");
    $cookie->setExpire(1569850644);
});
$cookieManager->newCookie(function (Cookie $cookie){
    $cookie->setDomain("localhost");
    $cookie->setName("sample2");
    $cookie->setValue("gfdffhfghfghfghfghfghgfhgfhtrytryr");
    $cookie->setSecure(true);
    $cookie->setPath("/dssdfsdf");
    $cookie->setExpire(1569850644);
});
$session = new Session($cookieManager->getCookies(),"../sess");

$session->start();

var_dump($session->getFlash("asd","asdasdasdasdasdsadas"));