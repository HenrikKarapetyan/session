<?php

$session = require 'main.php';

$session->start();
$session->setFlash('xxx', 'xxxxxxxx');
var_dump($session->getFlash('xxx'));

var_dump($_COOKIE);