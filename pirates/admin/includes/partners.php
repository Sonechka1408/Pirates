<?php
	$page = checkVar($_GET['page'], 0);
	$limitstart = $page * $config['page_limit'];
	$partners = array();
	
	$query = '
		SELECT 
				SQL_CALC_FOUND_ROWS
		        `p`.`id`,
		        `p`.`username`,
		        `p`.`password`
			FROM 
				`ksl_partners` AS `p`
			ORDER BY `p`.`id` ASC 
			LIMIT ' . $limitstart . ', ' . $config['page_limit']
	;

	$res = mysql_query($query);
	while($row = mysql_fetch_array($res)){
		$partners[] = $row;
	}

	$query = 'SELECT FOUND_ROWS()';
	$res = mysql_query($query);
	$total = mysql_result($res, 0);
	$url = parse_url($_SERVER['HTTP_HOST']);