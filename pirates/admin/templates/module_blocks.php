<?php include ROOT.'/admin/includes/module_blocks.php'; ?>
<div class="module" ng-controller="HidesBloksCtrl as HBCtrl">
	<h4 class="clearfix module__title">
		Блоки
		<a class="js-category_add" href="#" data-toggle="modal" data-target="#blockNew"></a>
		<a class="sh hides" ng-class="{'hides':!HBCtrl.collapseCategoriesModuleWrapp, 'show':HBCtrl.collapseCategoriesModuleWrapp}" href="javascript:void(0);" ng-click="HBCtrl.collapseCategoriesModuleWrapp = ! HBCtrl.collapseCategoriesModuleWrapp"></a>
	</h4>
	<ul class="list-group" ng-show="!HBCtrl.collapseCategoriesModuleWrapp">
		<?php foreach($blocks as $block): ?>
			<li class="list-group-item">
				<?php $blocksStatistic['common'] += $common = $block['traffic']; ?>
				<?php $blocksStatistic['current'] += $current = getStatisticPercentDisplayBlock($block['id']); ?>
				<?php echo $block['title']; ?>
				<div class="actions">
					<a href="#" class="js-block_edit" title="<?php echo $block['title']; ?>" data-toggle="modal" data-target="#blockEdit<?php echo $block['id']; ?>">
						Редактировать
					</a>
					<a class="delete" href="index.php?task=delete_block&block_id=<?php echo $block['id']; ?>">Удалить</a>
				</div>
				<div class="modal fade" id="blockEdit<?php echo $block['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-normal-width">
				        <form method="get" class="modal-content form-horizontal" action="index.php">
				            <div class="modal-header clearfix">
				                <h4 class="modal-title pull-left">Редактор блока</h4>
				                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
				                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
				            </div>
				          	<div class="modal-body">
								<div class="form-group">
									<label class="col-lg-3 control-label">Название:</label>
									<div class="col-lg-9">
										<input name="title" value="<?php echo $block['title']; ?>" class="form-control" type="text" required="required" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Контен по умолчанию:</label>
									<div class="col-lg-9">
										<textarea name="ab_test[content]" class="form-control editor" placeholder="Значение в блоке"><?php echo $block['content']; ?></textarea>
									</div>
								</div>

								<input type="hidden" name="ab_test[id]" value="<?php echo $block['tid']; ?>">
								<input type="hidden" name="block_id" value="<?php echo $block['id']; ?>">
								<input type="hidden" name="task" value="update_block">
				          	</div>
				        </form>
				  	</div>
				</div>
			</li>
		<?php endforeach ?>
	</ul>
	<!--
	<div class="row">
		<div class="col-sm-12">
			<div class="progress">
				<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $blocksStatistic['common']; ?>" aria-valuemin="0" aria-valuemax="<?php echo $blocksStatistic['common']; ?>" style="width: <?php echo $blocksStatistic['common']; ?>%;">
					<?php echo $blocksStatistic['current']; ?>% / <?php echo $blocksStatistic['common']; ?>%
				</div>
			</div>
			<div>
				<div>Тестируемый трафик - <span class="label label-primary"><?php echo $blocksStatistic['common']; ?>%</span></div>
				<div>Прошедший тесты трафик - <span class="label label-info"><?php echo $blocksStatistic['current']; ?>%</span></div>
			</div>
		</div>
	</div>-->
	<div class="modal fade" id="blockNew" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-normal-width">
	        <form method="get" class="modal-content form-horizontal" action="index.php">
	            <div class="modal-header clearfix">
	                <h4 class="modal-title pull-left">Добавление блока</h4>
	                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
	                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
	            </div>
	          	<div class="modal-body">
					<div class="form-group">
						<label class="col-lg-3 control-label">Название:</label>
						<div class="col-lg-9">
							<input name="title" class="form-control" type="text" required="required" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Контен по умолчанию:</label>
						<div class="col-lg-9">
							<textarea name="content" class="form-control editor" placeholder="Значение в блоке"></textarea>
						</div>
					</div>
					<input type="hidden" name="task" value="create_block">
	          	</div>
	        </form>
	  	</div>
	</div>
</div>