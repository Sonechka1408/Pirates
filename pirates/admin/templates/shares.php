<div class="row">
	<div class="col-lg-3">
		<?php include ROOT.'/admin/templates/module_share_categories.php'; ?>
	</div>
	<div class="col-lg-9">
		<ul class="list-inline ksl-toolbar">
			<li>
				<button type="button" class="btn btn-warning adds" data-toggle="modal" data-target="#shareNew">Добавить</button>
			</li>
		</ul>
		<table class="cat">
			<thead>
				<tr>
					<th width="50px">#</th>
					<th>Наименование</th>
					<th class="hidden-xs">Категория</th>
					<th>Новая цена</th>
					<th>Старая цена</th>
					<th class="hidden-xs">Состояние</th>
					<th class="del"></th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($shares)):?>
					<?php foreach($shares as $share):?>
					<tr>
						<td><?php echo $share['id']; ?></td>
						<td>
							<a href="#" title="<?php echo $share['title']; ?>" data-toggle="modal" data-target="#shareEdit<?php echo $share['id']; ?>">
								<?php echo $share['title']; ?>
							</a>
							<div class="modal fade" id="shareEdit<?php echo $share['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
							        <form method="POST" class="modal-content form-horizontal" action="?task=update_share" enctype="multipart/form-data">
							            <div class="modal-header clearfix">
							                <h4 class="modal-title pull-left">Редактор акции</h4>
							                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
							                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
							            </div>
							          	<div class="modal-body row">
							          		<fieldset class="col-lg-6">
												<div class="form-group">
													<label class="col-lg-3 control-label">Название:</label>
													<div class="col-lg-4">
														<input name="share[title]" class="form-control" type="text" value="<?php echo $share['title']; ?>" required="required" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-3 control-label">Категория:</label>
													<div class="col-lg-4">
														<select type="text" name="share[cid]" class="form-control">
															<?php foreach($categories as $category): ?>
																<option value="<?php echo $category['id']; ?>"<?php echo $share['category_id'] == $category['id'] ? 'selected' : ''; ?>><?php echo $category['title']; ?></option>
															<?php endforeach ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-3 control-label">Время действия:</label>
													<div class="col-lg-4">
														<input name="share[life_time]" class="form-control" type="text" value="<?php echo $share['life_time']; ?>" />
													</div>
												</div>		
												<div class="form-group">
													<label class="col-lg-3 control-label">Время фиксирования:</label>
													<div class="col-lg-4">
														<input name="share[fix_time]" class="form-control" type="text" value="<?php echo $share['fix_time']; ?>" />
													</div>
												</div>													
												<div class="form-group">
													<label class="col-lg-3 control-label">Новая цена:</label>
													<div class="col-lg-2">
														<input name="share[price]" class="form-control" type="text" value="<?php echo $share['price']; ?>" />
													</div>
													<div class="col-lg-2">
														<select class="form-control">
															<option>Рубли</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-3 control-label">Старая цена:</label>
													<div class="col-lg-2">
														<input name="share[old_price]" class="form-control" type="text" value="<?php echo $share['old_price']; ?>" />
													</div>
													<div class="col-lg-2">
														<select class="form-control">
															<option>Рубли</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-3 control-label">Состояние:</label>
													<div class="col-lg-5">
														<div class="checkbox">
															<label>
																<input type="checkbox" name="share[state]" <?php echo $share['state'] ? 'checked' : ''; ?> value="1" />
																Опубликован
															</label>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-lg-3 control-label">Миниописание:</label>
													<div class="col-lg-9">
														<textarea name="share[introcontent]" class="form-control editor" placeholder="Миниописание"><?php echo $share['introcontent']; ?></textarea>
													</div>
												</div>
						          			</fieldset>
											<div class="col-lg-6">
												<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl" ng-init="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp">
													<legend class="module-head">
														Изображение
														<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd shw"><i></i></a>
													</legend>
													<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
														<div class="col-lg-12">
															<?php if(!empty($share['image'])): ?>
															<div class="image-preview">
																<img src="/assets/images/shares/<?php echo $share['image']; ?>" width="200px">
															</div>
															<?php endif; ?>
															<div class="image-load">
																<input type="file" name="share[image]" accept="image/jpeg,image/png,image/gif" />
															</div>
														</div>
													</div>
												</fieldset>
												<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl" ng-init="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp">
													<legend class="module-head">
														Ключевые слова
														<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd shw"><i></i></a>
													</legend>
													<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
														<div class="col-lg-12">
															<textarea name="share[keyword]" class="form-control" placeholder="Ключевые слова"><?php echo $share['keyword']; ?></textarea>
														</div>
													</div>
												</fieldset>

												<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl">
													<legend class="module-head">
														Площадка
														<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd hd"><i></i></a>
													</legend>
													<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
														<div class="col-lg-12">
															<textarea name="share[source]" class="form-control" placeholder="Площадка"><?php echo $share['source']; ?></textarea>
														</div>
													</div>
												</fieldset>

												<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl">
													<legend class="module-head">
														Компания
														<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd hd"><i></i></a>
													</legend>
													<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
														<div class="col-lg-12">
															<textarea name="share[utm_campaign]" class="form-control" placeholder="Компания"><?php echo $share['utm_campaign']; ?></textarea>
														</div>
													</div>
												</fieldset>

												<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl">
													<legend class="module-head">
														Объявление
														<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd hd"><i></i></a>
													</legend>
													<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
														<div class="col-lg-12">
															<textarea name="share[utm_content]" class="form-control" placeholder="Объявление"><?php echo $share['utm_content']; ?></textarea>
														</div>
													</div>
												</fieldset>
											</div>
											<input type="hidden" name="share[id]" value="<?php echo $share['id']; ?>">
							          	</div>
							        </form>
							  	</div>
							</div>
						</td>
						<td class="hidden-xs"><?php echo $share['category_title']; ?></td>
						<td><?php echo number_format($share['price'], 0, '.', ' '); ?> руб.</td>
						<td><?php echo number_format($share['old_price'], 0, '.', ' '); ?> руб.</td>
						<td class="hidden-xs"><?php echo $share['state']; ?></td>
						<td class="del"><a href="index.php?task=delete_share&share_id=<?php echo $share['id']; ?>"></a></td>
					</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="7" align="center"><h4>Нет акций</h4></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
<?php include ROOT.'/admin/templates/pagination.php';?>
<div class="modal fade" id="shareNew" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<form method="POST" class="modal-content form-horizontal" action="?task=create_share" enctype="multipart/form-data">
            <div class="modal-header clearfix">
                <h4 class="modal-title pull-left">Добавление акции</h4>
                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
            </div>
          	<div class="modal-body row">
				<fieldset class="col-lg-6">
					<div class="form-group">
						<label class="col-lg-3 control-label">Название:</label>
						<div class="col-lg-4">
							<input name="share[title]" class="form-control" type="text" required="required" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Категория:</label>
						<div class="col-lg-4">
							<select type="text" name="share[cid]" class="form-control">
								<?php foreach($categories as $category): ?>
									<option value="<?php echo $category['id']; ?>"><?php echo $category['title']; ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Время действия:</label>
						<div class="col-lg-4">
							<input name="share[life_time]" class="form-control" type="text" />
						</div>
					</div>		
					<div class="form-group">
						<label class="col-lg-3 control-label">Время фиксирования:</label>
						<div class="col-lg-4">
							<input name="share[fix_time]" class="form-control" type="text" />
						</div>
					</div>						
					<div class="form-group">
						<label class="col-lg-3 control-label">Новая цена:</label>
						<div class="col-lg-2">
							<input name="share[price]" class="form-control" type="text" />
						</div>
						<div class="col-lg-2">
							<select class="form-control">
								<option>Рубли</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Старая цена:</label>
						<div class="col-lg-2">
							<input name="share[old_price]" class="form-control" type="text" />
						</div>
						<div class="col-lg-2">
							<select class="form-control">
								<option>Рубли</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Состояние:</label>
						<div class="col-lg-5">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="share[state]" value="1" />
									Опубликован
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Миниописание:</label>
						<div class="col-lg-9">
							<textarea name="share[introcontent]" class="form-control editor" placeholder="Миниописание"></textarea>
						</div>
					</div>
      			</fieldset>
				<div class="col-lg-6">
					<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl" ng-init="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp">
						<legend class="module-head">
							Изображение
							<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd shw"><i></i></a>
						</legend>
						<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
							<div class="col-lg-12">
								<div class="image-load">
									<input type="file" name="share[image]" accept="image/jpeg,image/png,image/gif" />
								</div>
							</div>
						</div>
					</fieldset>
					<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl" ng-init="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp">
						<legend class="module-head">
							Ключевые слова
							<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd shw"><i></i></a>
						</legend>
						<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
							<div class="col-lg-12">
								<textarea name="share[keyword]" class="form-control" placeholder="Ключевые слова"></textarea>
							</div>
						</div>
					</fieldset>

					<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl">
						<legend class="module-head">
							Площадка
							<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd hd"><i></i></a>
						</legend>
						<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
							<div class="col-lg-12">
								<textarea name="share[source]" class="form-control" placeholder="Площадка"></textarea>
							</div>
						</div>
					</fieldset>

					<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl">
						<legend class="module-head">
							Компания
							<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd hd"><i></i></a>
						</legend>
						<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
							<div class="col-lg-12">
								<textarea name="share[utm_campaign]" class="form-control" placeholder="Компания"></textarea>
							</div>
						</div>
					</fieldset>

					<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl">
						<legend class="module-head">
							Объявление
							<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd hd"><i></i></a>
						</legend>
						<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
							<div class="col-lg-12">
								<textarea name="share[utm_content]" class="form-control" placeholder="Объявление"></textarea>
							</div>
						</div>
					</fieldset>
				</div>
          	</div>
        </form>
  	</div>
</div>