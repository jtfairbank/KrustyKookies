<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/Twig/Autoloader.php');

Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(array(
  $_SERVER['DOCUMENT_ROOT'] . '/dynamic/template',
));

$twig = new Twig_Environment($loader, array(
  //'cache' => 'dynamic/cache',
));

?>