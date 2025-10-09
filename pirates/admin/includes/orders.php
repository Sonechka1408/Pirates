<?php

$page = checkVar($_GET['page'], 0);
$filter = checkVar($_GET['filter'], array());
$limitstart = $page * $config['page_limit'];
$where = 'where 1';
if (isset($filter['text']) && !empty($filter['text'])) $where.= ' and (name like "%' . $filter['text'] . '%" or email like "%' . $filter['text'] . '%" or phone like "%' . $filter['text'] . '%" or referer like "%' . $filter['text'] . '%")';
if (isset($filter['subscribe_id']) && !empty($filter['subscribe_id'])) $where.= ' and subscribe_id = ' . (int)$filter['subscribe_id'];

$subscribes = array();
$query = 'select sql_calc_found_rows * from subscribes order by id desc';
$res = mysql_query($query);
while ($row = mysql_fetch_array($res)) $subscribes[] = $row;

$orders = array();
$query = 'select sql_calc_found_rows o.*, t.title as template_title, s.title as subscribe_title from orders as o
		  left join templates as t on t.id = o.template_id
 		  left join subscribes as s on s.id = o.subscribe_id
		  ' . $where . ' order by id desc limit ' . $limitstart . ', ' . $config['page_limit'];
$res = mysql_query($query);
while ($row = mysql_fetch_array($res)) {
    $row['referer'] = json_decode($row['referer'], true);
    $row['referer_info'] = '';
    $row['referer_info'].= $lang['referer_info_utm_source'] . ' : ' . $row['referer']['utm_source'] . '<br>';
    $row['referer_info'].= $lang['referer_info_keyword'] . ' : ' . $row['referer']['keyword'] . '<br>';
    $row['referer_info'].= '<a href="#" data-toggle="modal" data-target="#order_referer_' . $row['id'] . '">Детальная информация</a>';
    $row['referer_info'].= '<div class="modal fade" id="order_referer_' . $row['id'] . '" tabindex="-1" role="dialog" aria-hidden="true">';
    $row['referer_info'].= '   <div class="modal-dialog modal-normal-width">';
    $row['referer_info'].= '        <form method="get" class="modal-content form-horizontal">';
    $row['referer_info'].= '            <div class="modal-header clearfix">';
    $row['referer_info'].= '                <h4 class="modal-title pull-left">Детальная информация</h4>';
    $row['referer_info'].= '                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>';
    $row['referer_info'].= '            </div>';
    $row['referer_info'].= '          	 <div class="modal-body">';
    foreach ($row['referer'] as $key => $val) {
        $row['referer_info'].= '			<div class="form-horizontal">';
        $row['referer_info'].= '				<div class="form-group">';
        $row['referer_info'].= '					<label class="col-sm-4 control-label">' . $lang['referer_info_' . $key] . ' :</label>';
        $row['referer_info'].= '					<div class="col-sm-8 controls">' . $val . '</div>';
        $row['referer_info'].= '				</div>';
        $row['referer_info'].= '			</div>';
    }
    $row['referer_info'].= '          	 </div>';
    $row['referer_info'].= '        </form>';
    $row['referer_info'].= '  	 </div>';
    $row['referer_info'].= '</div>	';
    $orders[] = $row;
}
$query = 'select found_rows()';
$res = mysql_query($query);
$total = mysql_result($res, 0);