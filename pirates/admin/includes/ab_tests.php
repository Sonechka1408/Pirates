<?php
$page = checkVar($_GET['page'], 0);
$limitstart = $page * $config['page_limit'];

$abTests = array();

$query = '
SELECT 
		SQL_CALC_FOUND_ROWS
        `b`.`id`,
        `t`.`id` AS `test_id`,
        `t`.`content`,
        `t`.`default`,
        `b`.`traffic`,
        `b`.`id` AS `block_id`,
        `b`.`title`
	FROM 
		`ksl_abtests_blocks` AS `tb`
	LEFT JOIN 
		`ksl_blocks` AS `b`
	ON
		`b`.`id`=`tb`.`block_id`
	LEFT JOIN 
		`ksl_ab_tests` AS `t`
	ON
		`t`.`id`=`tb`.`ab_test_id`
	ORDER BY `t`.`id` ASC 
	LIMIT ' . $limitstart . ', ' . $config['page_limit'];

$res = mysql_query($query);
$i = 0;
while ($row = mysql_fetch_assoc($res)) {

	$abTests[$row['id']]['id'] = $row['id'];
	$abTests[$row['id']]['traffic'] = $row['traffic'];
	$abTests[$row['id']]['block'] = array(
		'id' => $row['block_id'],
		'title' => $row['title']
	);

	unset($row['traffic']);
	unset($row['block_id']);
	unset($row['title']);

	$abTests[$row['id']]['abtests'][$i] = $row;
	$abTests[$row['id']]['abtests'][$i]['id'] = $row['test_id'];

	unset($row['test_id']);
	unset($abTests[$row['id']]['abtests'][$i]['test_id']);

	$i++;
}

$query = 'select found_rows()';
$res = mysql_query($query);
$total = mysql_result($res, 0);