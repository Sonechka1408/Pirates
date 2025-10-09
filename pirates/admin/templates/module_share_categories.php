<?php include ROOT.'/admin/includes/module_share_categories.php'; ?>
<div class="module" ng-controller="HidesBloksCtrl as HBCtrl">
	<h4 class="clearfix module__title">
		Категории
		<a class="js-category_add" href="#" data-toggle="modal" data-target="#categoryNew"></a>
		<a class="sh hides" ng-class="{'hides':!HBCtrl.collapseCategoriesModuleWrapp, 'show':HBCtrl.collapseCategoriesModuleWrapp}" href="javascript:void(0);" ng-click="HBCtrl.collapseCategoriesModuleWrapp = ! HBCtrl.collapseCategoriesModuleWrapp"></a>
	</h4>
	<ul class="list-group" ng-show="!HBCtrl.collapseCategoriesModuleWrapp">
		<?php foreach($categories as $category): ?>
			<li class="list-group-item">
				<label class="cat-title">
					<?php echo $category['title']; ?>
					<div class="actions">
						<a href="#" class="js-category_edit" title="<?php echo $category['title']; ?>" data-toggle="modal" data-target="#categoryEdit<?php echo $category['id']; ?>">
							Редактировать
						</a>
						<a class="delete" href="index.php?task=delete_share_cat&cat_id=<?php echo $category['id']; ?>">Удалить</a>
					</div>
				</label>
				<div class="modal fade" id="categoryEdit<?php echo $category['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-normal-width">
				        <form method="get" class="modal-content form-horizontal" action="index.php">
				            <div class="modal-header clearfix">
				                <h4 class="modal-title pull-left">Редактор категории</h4>
				                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
				                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
				            </div>
				          	<div class="modal-body">
				          		<fieldset>
									<div class="form-group">
										<label class="col-sm-3 control-label">Название:</label>
										<div class="col-sm-6">
											<input name="category[title]" value="<?php echo $category['title']; ?>" class="form-control" type="text" required="required" />
										</div>
									</div>
								</fieldset>

								<input type="hidden" name="category[id]" value="<?php echo $category['id']; ?>">
								<input type="hidden" name="task" value="update_category">
				          	</div>
				        </form>
				  	</div>
				</div>
			</li>
		<?php endforeach ?>
	</ul>
	<div class="modal fade" id="categoryNew" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-normal-width">
	        <form method="get" class="modal-content form-horizontal" action="index.php">
	            <div class="modal-header clearfix">
	                <h4 class="modal-title pull-left">Добавление категории</h4>
	                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
	                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
	            </div>
	          	<div class="modal-body">
	          		<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Название:</label>
							<div class="col-sm-6">
								<input name="category[title]" class="form-control" type="text" required="required" />
							</div>
						</div>
	          		</fieldset>
					<input type="hidden" name="task" value="create_category">
	          	</div>
	        </form>
	  	</div>
	</div>
</div>