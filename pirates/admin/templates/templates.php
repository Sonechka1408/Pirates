<ul class="list-inline ksl-toolbar">
	<li>
		<button type="button" class="btn btn-warning adds" data-toggle="modal" data-target="#new_template">Создать</button>
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
		<?php if (count($templates)):?>
			<?php foreach($templates as $template): ?>
			<tr>
				<td><?php echo $template['id']; ?></td>
				<td>
					<a href="index.php?view=code_editor&template_name=<?php echo $template['template_name']; ?>" title="Редактиовать"><?php echo $template['title']; ?></a>
				</td>
				<td class="del"><a href="index.php?task=delete_template&template_name=<?php echo $template['template_name']; ?>"></a></td>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="3" align="center"><h4>Нет шаблонов</h4></td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<?php include ROOT.'/admin/templates/pagination.php'; ?>
<div class="modal fade" id="new_template" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-normal-width">
        <form method="get" class="modal-content form-horizontal" action="index.php">
            <div class="modal-header clearfix">
                <h4 class="modal-title pull-left">Добавление продукта</h4>
                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
            </div>
          	<div class="modal-body">
				<fieldset>
					<div class="form-group">
						<label class="col-lg-3 control-label">Название :</label>
						<div class="col-lg-6">
							<input type="text" name="title" class="form-control" placeholder="Название" required="required" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Системное имя :</label>
						<div class="col-lg-6">
							<input type="text" name="template_name" class="form-control" placeholder="Системное имя" required="required" >
						</div>
					</div>
					<input type="hidden" name="task" value="create_new_template">
				</fieldset>
          	</div>
        </form>
  	</div>
</div>