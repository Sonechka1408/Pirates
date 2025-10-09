<?php if ($total > $config['page_limit']):?>
<div class="pagination pagination-toolbar">
	<ul class="pagination-list">
		<?php for($k = 1;$k <= ceil($total / $config['page_limit']);$k++):?>
			<?php
			$link = '?view='.$view.'&page='.($k-1);
			if (!empty($filter) && is_array($filter))
				foreach($filter as $key=>$val)
					if (!empty($val))
						$link .= '&filter['.$key.']='.$val;
			?>
			<li class="<?php echo ($k-1 == $page ? 'active' : '');?>"><a href="<?php echo $link;?>"><?php echo $k;?></a></li>
		<?php endfor;?>
	</ul>
</div>
<?php endif;?>