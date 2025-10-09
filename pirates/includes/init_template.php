<?php

$query = 'SELECT `value` FROM `settings` WHERE `name` = "timer"';
$res = mysql_query($query);
$timer = mysql_result($res, 0);

global $utmContent;

$pay_result = $_GET['pay_result'];
$utm_partner = checkVar($_GET['utm_partner']);
if (!empty($utm_partner)){
	$query = 'SELECT `username` FROM `ksl_partners` WHERE `id` = '.$utm_partner;
	$res = mysql_query($query);
	$utm_partner = mysql_result($res, 0);
}
$referer = array(
	'utm_source' => checkVar($_GET['utm_source']),
	'utm_medium' => checkVar($_GET['utm_medium']),
	'utm_campaign' => checkVar($_GET['utm_campaign']),
	'utm_content' => checkVar($_GET['utm_content']),
	'utm_partner' => $utm_partner,
	'keyword' => checkVar($_GET['keyword'], $utmContent['keyword']),
	'source_type' => checkVar($_GET['source_type']),
	'source' => checkVar($_GET['source']),
	'position_type' => checkVar($_GET['position_type']),
	'position' => checkVar($_GET['position']),
	'keyword' => checkVar($_GET['keyword']),
	'referer' => $_SERVER['HTTP_REFERER']
);

$utmContent = $referer;
if(is_array($referer)){
	$referer = json_encode($referer);
}
$_SESSION['referer'] = $referer;

if (isset($_GET['utm_source'])) {
	//header('Location: /');
}

$settings = array();
$query = 'SELECT `name`, `value` FROM `settings`';
$res = mysql_query($query);
while($row = mysql_fetch_assoc($res)){
    $settings[$row['name']] = $row['value'];
}

$diff = strtotime($settings['open_door']) - time();
$open_door_parts = explode('.', $settings['open_door']);
if ($diff > 0)
{
	$days = floor($diff / 3600 / 24);
}

$js = 'var template_id = ' . $template_id . ';';
//$js .= 'var timer = \'' . $timer . '\';';
//$js .= 'var liftoffTime = new Date('.$open_door_parts[2].', '.$open_door_parts[1].' - 1, '.$open_door_parts[0].');';