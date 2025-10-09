<?php
	$page = checkVar($_GET['page'], 0);
	$limitstart = $page * $config['page_limit'];
	$shares = array();
	
	$query = '
		SELECT 
				SQL_CALC_FOUND_ROWS
		        `s`.`id`,
		        `s`.`title`,
				`s`.`life_time`,
				`s`.`fix_time`,
		        `s`.`price`,
		        `s`.`old_price`,
		        `s`.`image`,
		        `s`.`introcontent`,
		        `s`.`keyword`,
		        `s`.`source`,
		        `s`.`utm_campaign`,
		        `s`.`utm_content`,
		        `s`.`state`,
		        `c`.`id` AS `category_id`,
		        `c`.`title` AS `category_title`
			FROM 
				`ksl_category_shares` AS `cs`
			LEFT JOIN 
				`ksl_shares` AS `s`
			ON
				`cs`.`share_id` = `s`.`id`
			LEFT JOIN 
				`ksl_categories` AS `c`
			ON
				`cs`.`cid` = `c`.`id`
			ORDER BY `s`.`id` ASC 
			LIMIT ' . $limitstart . ', ' . $config['page_limit']
	;

	$res = mysql_query($query);
	while($row = mysql_fetch_assoc($res)){
		$shares[] = $row;
	}

	$query = 'select found_rows()';
	$res = mysql_query($query);
	$total = mysql_result($res, 0);