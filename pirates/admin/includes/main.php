<?php
$templates_stats = array();
$query = 'select t.*, (select count(o.id) from orders as o where o.template_id = t.id) as orders_count from templates as t';
$res = mysql_query($query);
while($row = mysql_fetch_array($res))
	$templates_stats[] = $row;
	
$subscribes_stats = array();
$query = 'select s.*, (select count(o.id) from orders as o where o.subscribe_id = s.id) as orders_count from subscribes as s';
$res = mysql_query($query);
while($row = mysql_fetch_array($res))
	$subscribes_stats[] = $row;	
	
$orders = array();
$query = 'select o.*, t.title as template_title, s.title as subscribe_title from orders as o
		  left join templates as t on t.id = o.template_id
 		  left join subscribes as s on s.id = o.subscribe_id
		  where o.date = "'.date('Y-m-d').'" order by id desc';
$res = mysql_query($query);
while($row = mysql_fetch_array($res))
{
	$row['referer'] = json_decode($row['referer'], true);
	$row['referer_info'] = '';
	$row['referer_info'] .= $lang['referer_info_utm_source'].' : '.$row['referer']['utm_source'].'<br>';
	$row['referer_info'] .= $lang['referer_info_keyword'].' : '.$row['referer']['keyword'].'<br>';
	$row['referer_info'] .= '<a href="#" data-toggle="modal" data-target="#order_referer_'.$row['id'].'">Детальная информация</a>';
    $row['referer_info'] .= '<div class="modal fade" id="order_referer_'.$row['id'].'" tabindex="-1" role="dialog" aria-hidden="true">';
    $row['referer_info'] .= '   <div class="modal-dialog">';
    $row['referer_info'] .= '        <div class="modal-content">';
    $row['referer_info'] .= '            <div class="modal-header">';
    $row['referer_info'] .= '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
    $row['referer_info'] .= '                <h4 class="modal-title">Детальная информация</h4>';
    $row['referer_info'] .= '            </div>';
    $row['referer_info'] .= '          	 <div class="modal-body">';
	foreach($row['referer'] as $key=>$val)
	{
		$row['referer_info'] .= '			<div class="form-horizontal">';
		$row['referer_info'] .= '				<div class="form-group">';
		$row['referer_info'] .= '					<label class="col-sm-4 control-label">'.$lang['referer_info_'.$key].' :</label>';
		$row['referer_info'] .= '					<div class="col-sm-8 controls">'.$val.'</div>';
		$row['referer_info'] .= '				</div>';
		$row['referer_info'] .= '			</div>';
	}
    $row['referer_info'] .= '          	 </div>';
    $row['referer_info'] .= '        </div>';
    $row['referer_info'] .= '  	 </div>';
    $row['referer_info'] .= '</div>	';
	$orders[] = $row;	
}
?>