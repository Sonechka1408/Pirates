<ul class="list-inline ksl-toolbar">
	<li>
		<button type="button" class="btn btn-warning adds" data-toggle="modal" data-target="#productNew">Добавить</button>
	</li>
</ul>
<table class="cat">
	<thead>
		<tr>
			<th width="60px">#</th>
			<th>Наименование</th>
			<th class="del"></th>
		</tr>
	</thead>		
	<tbody>
		<?php if (count($products)):?>
			<?php foreach($products as $product):?>
			<tr>
				<td><?php echo $product['id']; ?></td>
				<td>
					<a href="#" title="<?php echo $product['title']; ?>" data-toggle="modal" data-target="#productEdit<?php echo $product['id']; ?>"><?php echo $product['title']; ?></a>
					<div class="modal fade" id="productEdit<?php echo $product['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog">
					        <form method="post" class="modal-content form-horizontal" action="?task=update_prodduct" enctype="multipart/form-data">
					            <div class="modal-header clearfix">
					                <h4 class="modal-title pull-left">Редактор продукта</h4>
					                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
					                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
					            </div>
					          	<div class="modal-body row">
					          		<fieldset class="col-lg-6">
										<div class="form-group">
											<label class="col-lg-3 control-label">Название:</label>
											<div class="col-lg-4">
												<input name="product[title]" value="<?php echo $product['title']; ?>" class="form-control" type="text" required="required" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Оптом:</label>
											<div class="col-lg-4">
												<input name="product[price]" value="<?php echo $product['price']; ?>" class="form-control" type="text" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">В розницу:</label>
											<div class="col-lg-4">
												<input name="product[old_price]" value="<?php echo $product['old_price']; ?>" class="form-control" type="text" />
											</div>
										</div>										
										<div class="form-group">
											<label class="col-lg-3 control-label">Миниописание:</label>
											<div class="col-lg-12">
												<textarea name="product[introcontent]" class="form-control editor"><?php echo $product['introcontent']; ?></textarea>
											</div>
										</div>										
										<div class="form-group">
											<label class="col-lg-3 control-label">Описание:</label>
											<div class="col-lg-12">
												<textarea name="product[content]" class="form-control editor"><?php echo $product['content']; ?></textarea>
											</div>
										</div>
										<input type="hidden" name="product[id]" value="<?php echo $product['id']; ?>">
									</fieldset>
									<div class="col-lg-6">
										<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl" ng-init="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp">
											<legend class="module-head">
												Картинка
												<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd shw"><i></i></a>
											</legend>
											<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
											<div class="col-sm-8">
												<?php if(!empty($product['file'])): ?>
												<div class="col-sm-6">
													<?php echo $product['file']; ?>
												</div>
												<?php endif; ?>
												<div class="col-sm-6">
													<input type="file" name="product[file]" />
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
				<td class="del"><a href="index.php?task=delete_prodduct&product_id=<?php echo $product['id']; ?>"></a></td>
			</tr>			
			<?php endforeach;?>
		<?php else:?>
			<tr>
				<td colspan="3" align="center"><h4>Нет товаров</h4></td>
			</tr>
		<?php endif;?>
	</tbody>		
</table>
<?php include ROOT.'/admin/templates/pagination.php';?>
<div class="modal fade" id="productNew" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
        <form method="post" class="modal-content form-horizontal" action="?task=create_prodduct" enctype="multipart/form-data">
            <div class="modal-header clearfix">
                <h4 class="modal-title pull-left">Добавление продукта</h4>
                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
            </div>
          	<div class="modal-body row">
          		<fieldset class="col-lg-6">
					<div class="form-group">
						<label class="col-lg-3 control-label">Название:</label>
						<div class="col-lg-4">
							<input name="title" class="form-control" type="text" required="required" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Оптом:</label>
						<div class="col-lg-4">
							<input name="price" class="form-control" type="text" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">В розницу:</label>
						<div class="col-lg-4">
							<input name="old_price" value="" class="form-control" type="text" />
						</div>
					</div>						
					<div class="form-group">
						<label class="col-lg-3 control-label">Миниописание:</label>
						<div class="col-lg-12">
							<textarea name="introcontent" class="form-control editor"></textarea>
						</div>
					</div>					
					<div class="form-group">
						<label class="col-lg-3 control-label">Описание:</label>
						<div class="col-lg-12">
							<textarea name="content" class="form-control editor"></textarea>
						</div>
					</div>
				</fieldset>
				<div class="col-lg-6">
					<fieldset class="slide_module" ng-controller="HidesBloksCtrl as HBCtrl" ng-init="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp">
						<legend class="module-head">
							Картинка
							<a href="javascript:void(0);" ng-click="HBCtrl.collapseModalFieldsetWrapp = !HBCtrl.collapseModalFieldsetWrapp" ng-class="{'shw': HBCtrl.collapseModalFieldsetWrapp, 'hd':!HBCtrl.collapseModalFieldsetWrapp}" class="shw-hd shw"><i></i></a>
						</legend>
						<div class="form-group" ng-show="HBCtrl.collapseModalFieldsetWrapp">
						<div class="col-sm-8">
							<div class="col-sm-6">
								<input type="file" name="product[file]" accept="image/jpeg,image/png,image/gif" />
							</div>
						</div>
						</div>
					</fieldset>											
				</div>					
          	</div>
        </form>
  	</div>
</div>