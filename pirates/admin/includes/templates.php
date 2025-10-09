<?php
	$page = checkVar($_GET['page'], 0);
	$limitstart = $page * $config['page_limit'];

	$templates = array();
	$query = 'select sql_calc_found_rows * from templates order by id desc limit ' . $limitstart . ', ' . $config['page_limit'];
	$res = mysql_query($query);
	while ($row = mysql_fetch_assoc($res)) $templates[] = $row;
	$query = 'select found_rows()';
	$res = mysql_query($query);
	$total = mysql_result($res, 0);