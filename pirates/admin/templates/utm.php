<?php $url = parse_url($_SERVER['HTTP_HOST']); ?>
<div class="row">
	<div class="col-lg-3">
		<?php include ROOT.'/admin/templates/module_blocks.php'; ?>
	</div>
	<div class="col-lg-9">
		<ul class="list-inline ksl-toolbar">
			<li>
				<button type="button" class="btn btn-warning adds" data-toggle="modal" data-target="#new_utm">Добавить</button>
			</li>
		</ul>
		<table class="cat">
			<thead>
				<tr>
					<th width="60px">#</th>
					<th>Блок</th>
					<th>Ключевые слова</th>
					<th class="del"></th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($utmBlocks)):?>
					<?php foreach($utmBlocks as $utmBlock):?>
					<?php $tmpKeywords = explode(',', $utmBlock['terms'][0]); ?>
					<tr>
						<td><?php echo $utmBlock['id']; ?></td>
						<td>
							<a href="#" data-toggle="modal" data-target="#utmEdit<?php echo $utmBlock['id']; ?>"><?php echo $utmBlock['title']; ?></a>
							<div class="modal fade" id="utmEdit<?php echo $utmBlock['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
							        <form method="POST" class="modal-content form-horizontal" action="?task=update_utm_content">
							            <div class="modal-header clearfix">
							                <h4 class="modal-title pull-left">Редактор UTM значения</h4>
							                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
							                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
							            </div>
							          	<div class="modal-body">
											<?php $i = 1; ?>
											<?php foreach ($utmBlock['utm'] as $utm): ?>
												<?php $tmpKeyword = array_slice(explode(',', $utm['keyword']), 0, 1); ?>
												<fieldset>
													<legend>UTM <?php echo $i; ?>
														<small class="js-updateUTMLink-preview">
															<a class="btn btn-link js-ToolTip" data-toggle="tooltip" data-placement="top" title="Изменения вступят в силу после сохранения" href="<?php echo 'http://' . $url['path'] . '/index.php?source=' . $utm['source'] . '&keyword=' . $tmpKeyword[0] . '&utm_campaign=' . $utm['utm_campaign'] . '&utm_content=' . $utm['utm_content']; ?>" target="_blank">Как это выглядит <i class="glyphicon glyphicon-share-alt"></i></a>
														</small>
													</legend>
													<div class="form-group">
														<div class="col-lg-12">
															<ul class="nav nav-tabs" role="tablist">
																<li class="active"><a href="#keyword_<?php echo $utm['id']; ?>" role="tab" data-toggle="tab">Ключевые слова</a></li>
																<li><a href="#source_<?php echo $utm['id']; ?>" role="tab" data-toggle="tab">Площадка</a></li>
																<li><a href="#utm_campaign_<?php echo $utm['id']; ?>" role="tab" data-toggle="tab">Компания</a></li>
																<li><a href="#utm_content_<?php echo $utm['id']; ?>" role="tab" data-toggle="tab">Объявление</a></li>
															</ul>

															<!-- Tab panes -->
															<div class="tab-content js-updateUTMLink-changeblock">
																<div class="tab-pane active" id="keyword_<?php echo $utm['id']; ?>">
																	<textarea name="utm[<?php echo $utm['id']; ?>][keyword]" data-name="keyword" class="form-control" placeholder="Ключевые слова"><?php echo $utm['keyword']; ?></textarea>
																</div>
																<div class="tab-pane" id="source_<?php echo $utm['id']; ?>">
																	<textarea name="utm[<?php echo $utm['id']; ?>][source]" data-name="source" class="form-control" placeholder="Площадка"><?php echo $utm['source']; ?></textarea>
																</div>
																<div class="tab-pane" id="utm_campaign_<?php echo $utm['id']; ?>">
																	<textarea name="utm[<?php echo $utm['id']; ?>][utm_campaign]" data-name="utm_campaign" class="form-control" placeholder="Компания"><?php echo $utm['utm_campaign']; ?></textarea>
																</div>
																<div class="tab-pane" id="utm_content_<?php echo $utm['id']; ?>">
																	<textarea name="utm[<?php echo $utm['id']; ?>][utm_content]" data-name="utm_content" class="form-control" placeholder="Объявление"><?php echo $utm['utm_content']; ?></textarea>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<div class="col-lg-12">
															<textarea name="utm[<?php echo $utm['id']; ?>][content]" class="form-control editor" placeholder="Значение в блоке"><?php echo $utm['content']; ?></textarea>
														</div>
													</div>
												</fieldset>
												<?php $i++; ?>
											<?php endforeach ?>
											<div class="form-group">
												<div class="col-lg-12">
													<button type="button" class="btn btn-success js-newUTM-Add">Добавить UTM</button>
												</div>
											</div>
											<input type="hidden" name="utm[block_id]" value="<?php echo $utmBlock['block']['id']; ?>">
							          	</div>
							        </form>
							  	</div>
							</div>
						</td>
						<td><?php echo implode(', ', array_slice($tmpKeywords, 0, 10)); ?>...</td>
						<td class="del"><a href="index.php?task=delete_utm_content&block_id=<?php echo $utmBlock['id']; ?>"></a></td>
					</tr>
					<?php endforeach;?>
				<?php else:?>
					<tr>
						<td colspan="3" align="center"><h4>Нет UTM контента</h4></td>
					</tr>
				<?php endif;?>
			</tbody>
		</table>
	</div>
</div>
<?php include ROOT.'/admin/templates/pagination.php'; ?>
<div class="modal fade" id="new_utm" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
        <form method="POST" class="modal-content form-horizontal" action="?task=create_utm_content">
            <div class="modal-header clearfix">
                <h4 class="modal-title pull-left">Добавление UTM значения</h4>
                <button type="button" class="btn close" data-dismiss="modal" aria-hidden="true"></button>
                <button type="submit" class="btn btn-success pull-right">Сохранить</button>
            </div>
          	<div class="modal-body">
				<div class="form-group">
					<label class="col-lg-1 control-label">Блок:</label>
					<div class="col-lg-2">
						<select type="text" name="utm[block_id]" class="form-control">
							<?php foreach($blocks as $block): ?>
								<option value="<?php echo $block['id']; ?>"><?php echo $block['title']; ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
				<fieldset class="js-newUTM-Fields">
					<div class="form-group">
						<div class="col-lg-12">
							<ul class="nav nav-tabs" role="tablist">
								<li class="active"><a href="#keyword" role="tab" data-toggle="tab">Ключевые слова</a></li>
								<li><a href="#source" role="tab" data-toggle="tab">Площадка</a></li>
								<li><a href="#utm_campaign" role="tab" data-toggle="tab">Компания</a></li>
								<li><a href="#utm_content" role="tab" data-toggle="tab">Объявление</a></li>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div class="tab-pane active keyword">
									<textarea name="utm[keyword][]" class="form-control" placeholder="Ключевые слова"></textarea>
								</div>
								<div class="tab-pane source">
									<textarea name="utm[source][]" class="form-control" placeholder="Площадка"></textarea>
								</div>
								<div class="tab-pane utm_campaign">
									<textarea name="utm[utm_campaign][]" class="form-control" placeholder="Компания"></textarea>
								</div>
								<div class="tab-pane utm_content">
									<textarea name="utm[utm_content][]" class="form-control" placeholder="Объявление"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-12">
							<textarea name="utm[content][]" class="form-control editor" placeholder="Значение в блоке"></textarea>
						</div>
					</div>
				</fieldset>
				<div class="form-group">
					<div class="col-lg-12">
						<button type="button" class="btn btn-success js-newUTM-Add">Добавить UTM</button>
					</div>
				</div>
          	</div>
        </form>
  	</div>
</div>