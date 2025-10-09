<?php
	$page = checkVar($_GET['page'], 0);
	$limitstart = $page * $config['page_limit'];
	$reviews = array();
	
	$query = '
		SELECT 
				SQL_CALC_FOUND_ROWS
		        `r`.*
			FROM 
				`ksl_reviews` AS `r`
			ORDER BY `r`.`ordering` ASC 
			LIMIT ' . $limitstart . ', ' . $config['page_limit']
	;

	$res = mysql_query($query);
	while($row = mysql_fetch_array($res)){
		if(!file_exists(ROOT . '/assets/images/review/' . $row['avatar']) || empty($row['avatar'])){
			$row['avatar'] = 'default-avatar.jpg';
		}
		$reviews[] = $row;
	}

	$query = 'select found_rows()';
	$res = mysql_query($query);
	$total = mysql_result($res, 0);