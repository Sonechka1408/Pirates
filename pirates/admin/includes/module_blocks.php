<?php 
	$blocks = array();

	$query = '
		SELECT 
	        `b`.`id`,
	        `b`.`title`,
	        `b`.`traffic`,
	        `t`.`id` AS `tid`,
	        `t`.`content`
		FROM 
			`ksl_blocks` AS `b`
        LEFT JOIN 
        	`ksl_abtests_blocks` AS `ab` ON `b`.`id`=`ab`.`block_id`
        LEFT JOIN 
        	`ksl_ab_tests` AS `t` ON `t`.`id`=`ab`.`ab_test_id`
        WHERE
        	`t`.`default`=1
	';
	$res = mysql_query($query);
	$i = 0;
	while ($row = mysql_fetch_assoc($res)) {
		$blocks[$i] = new stdClass;
		$blocks[$i] = $row;

		$i++;
	}

	$blocksStatistic = array(
		'common' => 0,
		'current' => 0
	);