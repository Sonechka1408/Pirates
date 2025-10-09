<?php define('ROOT', dirname(__FILE__));

	ini_set('display_errors', 0);
	error_reporting(E_ALL);

	header('Content-Type: text/html; charset=utf-8;');
	session_start();

	include ROOT.'/includes/config.php';
	include ROOT.'/includes/functions.php';
	include ROOT.'/includes/init.php';

	list($template_id, $template_name) = getTemplate();
	include ROOT.'/includes/init_template.php';
	if(isset($_GET['tmpl']) && !empty($_GET['tmpl'])){
		$template_name = $_GET['tmpl'];
	} else {
		$template_name = 'game';
	}
	include ROOT.'/templates/'.$template_name.'.php';

	$sessionId = session_id();
	setSession($sessionId);