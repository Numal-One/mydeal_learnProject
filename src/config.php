<?php
// старт сессии. дальше будем работать с сессиями.
session_start();

define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
define('PATH_SRC', ROOT_PATH . '/src/');
define('PATH_TPL', ROOT_PATH . '/templates/');

include_once(PATH_SRC . 'data.php');
include_once(PATH_SRC . 'functions.php');
include_once(PATH_SRC . 'controller.php');
?>



