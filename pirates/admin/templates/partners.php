<ul class="list-inline ksl-toolbar">
	<li>
		<button type="button" class="btn btn-warning adds" data-toggle="modal" data-target="#partnerNew">Добавить</button>
	</li>
</ul>
<table class="cat">
	<thead>
		<tr>
			<th width="60px">#</th>
			<th>Наименование</th>
			<th>Реферальная ссылка</th>
			<th class="del"></th>
		</tr>
	</thead>		
	<tbody>
		<?php if (count($partners)):?>
			<?php foreach($partners as $partner):?>
			<tr>
				<td><?php echo $partner['id']; ?></td>
				<td>
					<a href="#" title="<?php echo $partner['username']; ?>" data-toggle="modal" data-target="#partnerEdit<?php echo $partner['id']; ?>"><?php echo $partner['username']; ?></a>
					<div class="modal fade" id="partnerEdit<?php echo $partner['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog">
					        <form method="POST" class="modal-content form-horizontal" action="index.php?task=update_partner">
					            <div class="modal-header clearfix">
					                <h4 class="modal-title pull-left">Редактор партнера</h4>
					                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
					                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
					            </div>
					          	<div class="modal-body row">
					          		<fieldset class="col-lg-6">
										<div class="form-group">
											<label class="col-lg-3 control-label">Название:</label>
											<div class="col-lg-4">
												<input name="partner[username]" value="<?php echo $partner['username']; ?>" class="form-control" type="text" required="required" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Цена:</label>
											<div class="col-lg-4">
												<input name="partner[password]" class="form-control" type="password" />
											</div>
										</div>
										<input type="hidden" name="partner[id]" value="<?php echo $partner['id']; ?>">
									</fieldset>
					          	</div>
					        </form>
					  	</div>
					</div>
				</td>
				<td><?php echo 'http://' . $url['path'] . '/index.php?utm_partner=' . $partner['id']; ?></td>
				<td class="del"><a href="index.php?task=delete_partner&partner_id=<?php echo $partner['id']; ?>"></a></td>
			</tr>			
			<?php endforeach;?>
		<?php else:?>
			<tr>
				<td colspan="4" align="center"><h4>Нет партнеров</h4></td>
			</tr>
		<?php endif;?>
	</tbody>		
</table>
<?php include ROOT.'/admin/templates/pagination.php';?>
<div class="modal fade" id="partnerNew" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
        <form method="POST" class="modal-content form-horizontal" action="index.php?task=create_partner">
            <div class="modal-header clearfix">
                <h4 class="modal-title pull-left">Добавление партнера</h4>
                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
            </div>
          	<div class="modal-body row">
          		<fieldset class="col-lg-6">
					<div class="form-group">
						<label class="col-lg-3 control-label">Имя пользователя:</label>
						<div class="col-lg-4">
							<input name="partner[username]" class="form-control" type="text" required="required" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Пароль:</label>
						<div class="col-lg-4">
							<input name="partner[password]" class="form-control" type="password" />
						</div>
					</div>
				</fieldset>
          	</div>
        </form>
  	</div>
</div>