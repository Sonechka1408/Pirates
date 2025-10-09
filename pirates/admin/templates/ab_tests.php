<div class="row">
	<div class="col-lg-3">
		<?php include ROOT.'/admin/templates/module_blocks.php'; ?>
	</div>
	<div class="col-lg-9">
		<ul class="list-inline ksl-toolbar">
			<li>
				<button type="button" class="btn btn-warning adds" data-toggle="modal" data-target="#new_abtest">Добавить</button>
			</li>
		</ul>
		<table class="cat">
			<thead>
				<tr>
					<th width="60px">#</th>
					<th>Блок</th>
					<th>Трафик</th>
					<th class="del"></th>
				</tr>
			</thead>		
			<tbody>
				<?php if (count($abTests)): ?>
					<?php foreach($abTests as $abTest): ?>
					<tr>
						<td><?php echo $abTest['id']; ?></td>
						<td>
							<a href="#" data-toggle="modal" data-target="#abTestEdit<?php echo $abTest['id']; ?>"><?php echo $abTest['block']['title']; ?></a>
							<div class="modal fade" id="abTestEdit<?php echo $abTest['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
							        <form method="get" class="modal-content form-horizontal" action="index.php">
							            <div class="modal-header clearfix">
							                <h4 class="modal-title pull-left">Редактор A / B теста</h4>
							                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
							                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
							            </div>
							          	<div class="modal-body">
											<div class="form-group">
												<label class="col-lg-3 control-label">Процент трафика:</label>
												<div class="col-lg-1 input-group pull-left">
													<input type="text" name="ab_tests[traffic]" class="form-control js-availableTraffic-change" value="<?php echo $abTest['traffic']; ?>" required="required" />
													<span class="input-group-addon">%</span>
												</div>
												<span class="help-block col-lg-3">
													Доступно - <span class="label label-primary js-availableTraffic-value"><?php echo 100 - $blocksStatistic['common']; ?>%</span>
												</span>
									
											</div>
											<?php $i = 1; ?>
											<?php foreach ($abTest['abtests'] as $abtest): ?>
												<fieldset>
													<legend>A / B тест <?php echo $i; ?></legend>
													<div class="form-group">
														<blockquote class="col-lg-3 radio text-center">
															<label>
																<input type="radio" name="ab_tests[default]"<?php echo $abtest['default']?' checked':''; ?> value="<?php echo $abtest['id']; ?>"> По умолчанию
															</label>
															<div class="lead">
																<?php echo round(getStatisticOrdersABTest($abtest['id']) / getStatisticOrdersCount() * 100, 2); ?>% заказов
															</div>
															<div class="">
																<a href="javascript:void(0);" class="js-ABTest-remvoe" data-id="<?php echo $abtest['id']; ?>">
																	<i class="glyphicon glyphicon-remove"></i>
																	Удалить
																</a>
															</div>
														</blockquote>
														<div class="col-lg-9">
															<textarea name="ab_tests[content][<?php echo $abtest['id']; ?>][content]" class="form-control editor" placeholder="Значение в блоке"><?php echo $abtest['content']; ?></textarea>
														</div>
													</div>
													<input type="hidden" name="ab_tests[content][<?php echo $abtest['id']; ?>][id]" value="<?php echo $abtest['id']; ?>">
												</fieldset>
												<?php $i++; ?>
											<?php endforeach ?>
											<div class="form-group">
												<div class="col-lg-12">
													<button type="button" class="btn btn-success js-newUTM-Add">Добавить A / B тест</button>
												</div>
											</div>
											<input type="hidden" name="ab_tests[block_id]" value="<?php echo $abTest['block']['id']; ?>">
											<input type="hidden" name="task" value="update_ab_tests">
							          	</div>
							        </form>
							  	</div>
							</div>
						</td>
						<td><?php echo $abTest['traffic']; ?>%</td>
						<td class="del"><a href="index.php?task=delete_ab_test&block_id=<?php echo $abTest['id']; ?>"></a></td>
					</tr>
					<?php endforeach;?>
				<?php else:?>
					<tr>
						<td colspan="4" align="center"><h4>Нет A / B тестов</h4></td>
					</tr>
				<?php endif;?>
			</tbody>
		</table>
	</div>
</div>
<?php include ROOT.'/admin/templates/pagination.php';?>
<div class="modal fade" id="new_abtest" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
        <form method="get" class="modal-content form-horizontal" action="index.php">
            <div class="modal-header clearfix">
                <h4 class="modal-title pull-left">Добавление A / B теста</h4>
                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
            </div>
          	<div class="modal-body">
				<div class="form-group">
					<label class="col-lg-3 control-label">Процент трафика:</label>
					<div class="col-lg-3 input-group">
						<input type="text" name="ab_tests[traffic]" class="form-control" required="required" />
						<span class="input-group-addon js-availableTraffic">100%</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Блок:</label>
					<div class="col-lg-3">
						<select type="text" name="ab_tests[block_id]" class="form-control">
							<?php foreach($blocks as $block): ?>
								<option value="<?php echo $block['id']; ?>"><?php echo $block['title']; ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
				<fieldset class="js-newUTM-Fields">
					<legend>A / B тест </legend>
					<div class="form-group">
						<div class="col-lg-3 radio">
							<label>
								<input type="radio" name="ab_tests[default][]" checked> По умолчанию
							</label>
						</div>
						<div class="col-lg-9">
							<textarea name="ab_tests[content][]" class="form-control editor" placeholder="Значение в блоке"></textarea>
						</div>
					</div>
				</fieldset>
				<div class="form-group">
					<div class="col-lg-12">
						<button type="button" class="btn btn-success js-newUTM-Add">Добавить A / B тест</button>
					</div>
				</div>
				<input type="hidden" name="task" value="create_ab_tests">
          	</div>
        </form>
  	</div>
</div>