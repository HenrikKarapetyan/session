<?php

$session = require 'main.php';

$session->start();
$session->setFlash('xxx', 'xxxxxxxx');
var_dump($session->getFlash('asd'));

var_dump($_COOKIE);