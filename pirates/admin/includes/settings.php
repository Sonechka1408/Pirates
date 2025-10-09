<?php
	$settings = array();
	$query = 'select * from settings';
	$res = mysql_query($query);
	while($row = mysql_fetch_assoc($res)){
		$settings[] = $row;
	}

	$ignore_list = array(
		'email_subject' => true,
		'email_message' => true
	);