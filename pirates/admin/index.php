<?php 

define('ROOT', dirname(__DIR__));
define('ROOT_ADMIN', dirname(__FILE__));

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');
session_start();

include ROOT . '/includes/config.php';
include ROOT . '/includes/password.php';
include ROOT . '/includes/functions.php';
include ROOT . '/includes/init.php';
include ROOT_ADMIN . '/includes/lang.php';
include ROOT_ADMIN . '/includes/tasks.php';

if (checkLogin()) {
    $task = checkVar($_GET['task']);
    if (!empty($task)) $task();
    
    $view = checkVar($_GET['view'], 'main');
    include ROOT_ADMIN . '/includes/' . $view . '.php';
    include ROOT_ADMIN . '/templates/default.php';
} else {
    include ROOT_ADMIN . '/templates/login.php';
}