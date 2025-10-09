<?php 
	$categories = array();

	$query = '
		SELECT 
	        `с`.`id`,
	        `с`.`title`
		FROM 
			`ksl_categories` AS `с`
	';
	$res = mysql_query($query);
	$i = 0;
	while ($row = mysql_fetch_assoc($res)) {
		$categories[$i] = new stdClass;
		$categories[$i] = $row;

		$i++;
	}