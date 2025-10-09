<div class="row">
	<div class="col-lg-5"></div>
	<form method="get" class="form-inline col-lg-7 pull-right list__filter text-right" role="form" action="index.php">
		<div class="form-group">
			<input type="text" name="filter[text]" value="<?php echo (checkVar($filter['text']) ? checkVar($filter['text']) :''); ?>" placeholder="По тексту" class="form-control">
		</div>
		<div class="form-group">
			<select name="filter[subscribe_id]" class="form-control">
				<option value="">По продукту</option>
				<?php foreach($subscribes as $subscribe):?>
				<option value="<?php echo $subscribe['id']; ?>" <?php echo (checkVar($filter['subscribe_id']) == $subscribe['id'] ? 'selected' : ''); ?>><?php echo $subscribe['title']; ?></option>			
				<?php endforeach; ?>			
			</select>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-success">Поиск</button>
		</div>
		<input type="hidden" name="view" value="orders">
	</form>
</div>
<table class="cat">
	<thead>
		<tr>
			<th width="60px">#</th>
			<th>Покупатель</th>
			<th class="hidden-xs">Товар</th>
			<th class="hidden-xs">Шаблон</th>
			<th width="40%">Реферальные данные</th>
			<th>Дата</th>
			<th class="del"></th>
		</tr>
	</thead>
	<tbody>
		<?php if (count($orders)): ?>
			<?php foreach($orders as $order): ?>
			<tr>
				<td><?php echo $order['id']; ?></td>
				<td>
					Имя : <?php echo $order['name']; ?><br>
					E-mail : <?php echo $order['email'] ; ?><br>
					Телефон : <?php echo $order['phone']; ?><br>
					<?php echo $order['note']; ?>
				</td>
				<td class="hidden-xs"><?php echo $order['subscribe_title']; ?></td>
				<td class="hidden-xs"><?php echo $order['template_title']; ?></td>
				<td><?php echo $order['referer_info']; ?></td>
				<td><?php echo $order['date']; ?></td>
				<td class="del"><a href="index.php?task=delete_order&order_id=<?php echo $order['id']; ?>"></a></td>
			</tr>			
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="7" align="center"><h4>Нет заказов</h4></td>
			</tr>
		<?php endif; ?>
	</tbody>		
</table>
<?php include ROOT.'/admin/templates/pagination.php'; ?>