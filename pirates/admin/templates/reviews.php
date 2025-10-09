<ul class="list-inline ksl-toolbar">
	<li>
		<button type="button" class="btn btn-warning adds" data-toggle="modal" data-target="#reviewNew">Добавить</button>
	</li>
</ul>
<table class="cat">
	<thead>
		<tr>
			<th width="60px">#</th>
			<th>Наименование</th>
			<th>Состояние</th>
			<th class="del"></th>
		</tr>
	</thead>
	<tbody>
		<?php if (count($reviews)):?>
			<?php foreach($reviews as $review):?>
			<tr>
				<td><?php echo $review['id']; ?></td>
				<td>
					<a href="#" title="<?php echo $review['title']; ?>" data-toggle="modal" data-target="#reviewEdit<?php echo $review['id']; ?>">
						<img src="/assets/images/review/<?php echo $review['avatar']; ?>" alt="<?php echo $review['title']; ?>" style="height: 50px;" />
						<?php echo $review['title']; ?>
					</a>
					<div class="modal fade" id="reviewEdit<?php echo $review['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog">
						    <form method="POST" class="modal-content form-horizontal" action="?task=update_review" enctype="multipart/form-data">
						        <div class="modal-header clearfix">
						            <h4 class="modal-title pull-left">Редактор отзыва</h4>
						            <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
						            <button type="submit" class="btn btn-success pull-right">Сохранить</button>
						        </div>
						      	<div class="modal-body row">
						      		<fieldset class="col-lg-6">
										<div class="form-group">
											<label class="col-lg-3 control-label">Название:</label>
											<div class="col-lg-4">
												<input name="review[title]" class="form-control" type="text" required="required" value="<?php echo $review['title']; ?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Состояние:</label>
											<div class="col-lg-4">
												<div class="checkbox">
													<label>
														<input type="checkbox" name="review[state]" <?php echo $review['state'] ? 'checked' : ''; ?> value="1" />
														Опубликован
													</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Имя пользователя:</label>
											<div class="col-lg-4">
												<input name="review[username]" class="form-control" type="text" value="<?php echo $review['username']; ?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Телефон:</label>
											<div class="col-lg-4">
												<input name="review[phone]" class="form-control" type="text" value="<?php echo $review['phone']; ?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Email:</label>
											<div class="col-lg-4">
												<input name="review[email]" class="form-control" type="text" value="<?php echo $review['email']; ?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Порядок:</label>
											<div class="col-lg-4">
												<input name="review[ordering]" class="form-control" type="text" value="<?php echo $review['ordering']; ?>" />
											</div>
										</div>										
										<div class="form-group">
											<label class="col-lg-3 control-label">Ссылка на соц. сеть:</label>
											<div class="col-lg-4">
												<input name="review[social_link]" class="form-control" type="text" value="<?php echo $review['social_link']; ?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">VK:</label>
											<div class="col-lg-4">
												<input name="review[vk]" class="form-control" type="text" value="<?php echo $review['vk']; ?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Twitter:</label>
											<div class="col-lg-4">
												<input name="review[tw]" class="form-control" type="text" value="<?php echo $review['tw']; ?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Facebook:</label>
											<div class="col-lg-4">
												<input name="review[fb]" class="form-control" type="text" value="<?php echo $review['fb']; ?>" />
											</div>
										</div>										
										<div class="form-group">
											<label class="col-lg-3 control-label">О пользователи:</label>
											<div class="col-lg-9">
												<textarea name="review[about]" class="form-control"><?php echo $review['about']; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Предыстория:</label>
											<div class="col-lg-9">
												<textarea name="review[prehistory]" class="form-control"><?php echo $review['prehistory']; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Краткое описание:</label>
											<div class="col-lg-9">
												<textarea name="review[introcontent]" class="form-control editor"><?php echo $review['introcontent']; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Описание:</label>
											<div class="col-lg-9">
												<textarea name="review[content]" class="form-control editor"><?php echo $review['content']; ?></textarea>
											</div>
										</div>
										<input type="hidden" name="review[id]" value="<?php echo $review['id']; ?>">
									</fieldset>
									<div class="col-lg-6">
										<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl" ng-init="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp">
											<legend class="module-head">
												Аватар
												<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd shw"><i></i></a>
											</legend>
											<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
											<div class="col-sm-8">
												<?php if(!empty($review['avatar'])): ?>
												<div class="col-sm-6">
													<img src="/assets/images/review/<?php echo $review['avatar']; ?>" width="200px">
												</div>
												<?php endif; ?>
												<div class="col-sm-6">
													<input type="file" name="review[image]" accept="image/jpeg,image/png,image/gif" />
												</div>
											</div>
											</div>
										</fieldset>
										<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl" ng-init="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp">
											<legend class="module-head">
												Письмо
												<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd shw"><i></i></a>
											</legend>
											<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
											<div class="col-sm-8">
												<?php if(!empty($review['file'])): ?>
												<div class="col-sm-6">
													<img src="/assets/images/review/<?php echo $review['file']; ?>" width="200px">
												</div>
												<?php endif; ?>
												<div class="col-sm-6">
													<input type="file" name="review[file]" accept="image/jpeg,image/png,image/gif" />
												</div>
											</div>
											</div>
										</fieldset>											
									</div>
					          	</div>
					        </form>
					  	</div>
					</div>
				</td>
				<td><?php echo $review['state']; ?></td>
				<td class="del"><a href="index.php?task=delete_review&review_id=<?php echo $review['id']; ?>"></a></td>
			</tr>			
			<?php endforeach;?>
		<?php else:?>
			<tr>
				<td colspan="4" align="center"><h4>Нет отзывов</h4></td>
			</tr>
		<?php endif;?>
	</tbody>		
</table>
<?php include ROOT.'/admin/templates/pagination.php';?>
<div class="modal fade" id="reviewNew" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
	    <form method="POST" class="modal-content form-horizontal" action="?task=create_review" enctype="multipart/form-data">
	        <div class="modal-header clearfix">
	            <h4 class="modal-title pull-left">Добавление отзыва</h4>
	            <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
	            <button type="submit" class="btn btn-success pull-right">Сохранить</button>
	        </div>
	      	<div class="modal-body row">
	      		<fieldset class="col-lg-6">
					<div class="form-group">
						<label class="col-lg-3 control-label">Название:</label>
						<div class="col-lg-4">
							<input name="review[title]" class="form-control" type="text" required="required" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Состояние:</label>
						<div class="col-lg-4">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="review[state]" value="1" />
									Опубликован
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Имя пользователя:</label>
						<div class="col-lg-4">
							<input name="review[username]" class="form-control" type="text" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Телефон:</label>
						<div class="col-lg-4">
							<input name="review[phone]" class="form-control" type="text" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Email:</label>
						<div class="col-lg-4">
							<input name="review[email]" class="form-control" type="text" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Порядок:</label>
						<div class="col-lg-4">
							<input name="review[ordering]" class="form-control" type="text" />
						</div>
					</div>					
					<div class="form-group">
						<label class="col-lg-3 control-label">Ссылка на соц. сеть:</label>
						<div class="col-lg-4">
							<input name="review[social_link]" class="form-control" type="text" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">VK:</label>
						<div class="col-lg-4">
							<input name="review[vk]" class="form-control" type="text" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Twitter:</label>
						<div class="col-lg-4">
							<input name="review[tw]" class="form-control" type="text" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Facebook:</label>
						<div class="col-lg-4">
							<input name="review[fb]" class="form-control" type="text" />
						</div>
					</div>					
					<div class="form-group">
						<label class="col-lg-3 control-label">О пользователи:</label>
						<div class="col-lg-9">
							<textarea name="review[about]" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Предыстория:</label>
						<div class="col-lg-9">
							<textarea name="review[prehistory]" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Краткое описание:</label>
						<div class="col-lg-9">
							<textarea name="review[introcontent]" class="form-control editor"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Описание:</label>
						<div class="col-lg-9">
							<textarea name="review[content]" class="form-control editor"></textarea>
						</div>
					</div>
				</fieldset>
				<div class="col-lg-6">
					<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl" ng-init="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp">
						<legend class="module-head">
							Аватар
							<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd shw"><i></i></a>
						</legend>
						<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
							<div class="col-lg-4">
								<input type="file" name="review[image]" accept="image/jpeg,image/png,image/gif" />
							</div>
						</div>
					</fieldset>
					<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl" ng-init="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp">
						<legend class="module-head">
							Письмо
							<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd shw"><i></i></a>
						</legend>
						<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
							<div class="col-lg-4">
								<input type="file" name="review[file]" accept="image/jpeg,image/png,image/gif" />
							</div>
						</div>
					</fieldset>						
				</div>
          	</div>
        </form>
  	</div>
</div>