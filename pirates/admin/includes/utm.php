<?php
$page = checkVar($_GET['page'], 0);
$limitstart = $page * $config['page_limit'];

$utmBlocks = array();

$query = '
SELECT 
		SQL_CALC_FOUND_ROWS
        `b`.`id`,
        `b`.`title`,
        `cb`.`block_id`,
        `c`.`id` AS `cid`,
        `c`.keyword,
        `c`.source,
        `c`.utm_campaign,
        `c`.utm_content,
        `c`.content
	FROM 
		`ksl_utm_content_blocks` AS `cb`
	LEFT JOIN 
		`ksl_blocks` AS `b`
	ON 
		`cb`.`block_id`=`b`.`id`
	LEFT JOIN 
		`ksl_utm_content` AS `c` 
	ON 
		`cb`.`utm_content_id`=`c`.`id`
	ORDER BY `b`.`id` ASC 
	LIMIT ' . $limitstart . ', ' . $config['page_limit'];

$res = mysql_query($query);
$i = 0;
while ($row = mysql_fetch_assoc($res)) {

	$utmBlocks[$row['id']]['id'] = $row['id'];
	$utmBlocks[$row['id']]['title'] = $row['title'];
	$utmBlocks[$row['id']]['terms'][] = $row['keyword'];

	$utmBlocks[$row['id']]['block'] = array(
		'id' => $row['block_id'],
		'title' => $row['title']
	);

	$utmBlocks[$row['id']]['utm'][$i] = $row;
	$utmBlocks[$row['id']]['utm'][$i]['id'] = $row['cid'];

	unset($utmBlocks[$row['id']]['utm'][$i]['cid']);
	unset($utmBlocks[$row['id']]['utm'][$i]['terms']);
	unset($utmBlocks[$row['id']]['utm'][$i]['title']);

	$i++;
}

$query = 'select found_rows()';
$res = mysql_query($query);
$total = mysql_result($res, 0);