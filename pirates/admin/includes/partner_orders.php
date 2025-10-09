<?php
	$page        = checkVar($_GET['page'], 0);
	$filter      = checkVar($_GET['filter'], array());
	$utm_partner = checkVar($_SESSION['utm_partner'], 0);
	$limitstart  = $page * $config['page_limit'];
	$where       = 'WHERE 1';
	$orders      = array();

	if($utm_partner > 0){
		if (isset($filter['text']) && !empty($filter['text'])){
			$where.= ' AND (
				name LIKE "%' . $filter['text'] . '%" OR email LIKE "%' . $filter['text'] . '%" OR phone LIKE "%' . $filter['text'] . '%" OR referer LIKE "%' . $filter['text'] . '%)';
		}

		$where .= ' AND (`referer` LIKE  "%\"utm_partner\":' . $utm_partner . '%")';
		

		if (isset($filter['subscribe_id']) && !empty($filter['subscribe_id'])) $where.= ' AND subscribe_id = ' . (int)$filter['subscribe_id'];
		$subscribes = array();
		$query = 'SELECT SQL_CALC_FOUND_ROWS * FROM subscribes ORDER BY id DESC';
		$res = mysql_query($query);
		while ($row = mysql_fetch_array($res)) $subscribes[] = $row;
		$query = 'SELECT SQL_CALC_FOUND_ROWS o.*, t.title AS template_title, s.title AS subscribe_title FROM orders AS o
		LEFT JOIN templates AS t ON t.id = o.template_id
		LEFT JOIN subscribes AS s ON s.id = o.subscribe_id
		' . $where . ' ORDER BY id DESC LIMIT ' . $limitstart . ', ' . $config['page_limit'];
		$res = mysql_query($query);
		while ($row = mysql_fetch_array($res)) {
		    $row['referer'] = json_decode($row['referer'], true);
		    $row['referer_info'] = '';
		    $row['referer_info'].= $lang['referer_info_utm_source'] . ': ' . $row['referer']['utm_source'] . '<br>';
		    $row['referer_info'].= $lang['referer_info_keyword'] . ': ' . $row['referer']['keyword'] . '<br>';
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
		                                $row['referer_info'].= '					<label class="col-sm-4 control-label">' . $lang['referer_info_' . $key] . ':</label>';
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
		$query = 'SELECT FOUND_ROWS()';
		$res = mysql_query($query);
		$total = mysql_result($res, 0);
	}