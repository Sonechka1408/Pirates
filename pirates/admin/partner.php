<?php 

	define('ROOT', dirname(__DIR__));
	define('ROOT_ADMIN', dirname(__FILE__));
	
	/*ini_set('display_errors', 1);
	error_reporting(E_ALL);*/

	header('Content-Type: text/html; charset=utf-8');
	session_start();

	include ROOT . '/includes/config.php';
	include ROOT . '/includes/password.php';
	include ROOT . '/includes/functions.php';
	include ROOT . '/includes/init.php';
	include ROOT_ADMIN . '/includes/lang.php';
	include ROOT_ADMIN . '/includes/tasks.php';

	$task = checkVar($_GET['task']);
	if (!empty($task)) $task();

	if (checkLoginPartner()) {
		include ROOT_ADMIN . '/includes/partner_orders.php';
		include ROOT_ADMIN . '/templates/partner_orders.php';
	} else {
    	include ROOT_ADMIN . '/templates/partner_login.php';
	}