<?php

use Henrik\Session\Session;

require '../vendor/autoload.php';

$session = new Session([], '../sess');

$session->start();

var_dump($session->getFlash('asd', 'asdasdasdasdasdsadas'));

var_dump($_COOKIE);