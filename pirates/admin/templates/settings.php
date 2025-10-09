<div class="module">
	<h3 class="module-head">Настройки</h3>
	<form method="get" class="form-horizontal clearfix graybox" action="index.php">

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#main" role="tab" data-toggle="tab">Основые</a></li>
			<li><a href="#mail" role="tab" data-toggle="tab">Почта</a></li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane fade in active" id="main">
				<div class="col-sm-6">
					<?php //print_r($ignore_list); ?>
					<?php foreach($settings as $setting): ?>
						<?php //var_dump(!array_key_exists($setting, $ignore_list)) ?>
						<?php if(!array_key_exists($setting['name'], $ignore_list)): ?>
							<?php if($setting['name'] != 'phone_message'): ?>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="text-align:left;"><?php echo $lang['setting_'.$setting['name']];?> :</label>
									<div class="col-sm-8">
										<input type="text" name="settings[<?php echo $setting['id'];?>]" class="form-control" value="<?php echo $setting['value'];?>" />
									</div>
								</div>
							<?php elseif($setting['name'] == 'phone_message'): ?>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="text-align:left;"><?php echo $lang['setting_'.$setting['name']];?> :</label>
									<div class="col-sm-8">
										<textarea type="text" name="settings[<?php echo $setting['id'];?>]" class="form-control editor"><?php echo $setting['value']; ?></textarea>
									</div>
								</div>
							<?php endif; ?>
					<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="tab-pane fade" id="mail">
				<div class="col-sm-9">
					<?php foreach($settings as $setting):?>
						<?php if(array_key_exists($setting['name'], $ignore_list)): ?>
							<?php if($setting['name'] != 'email_message'): ?>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="text-align:left;"><?php echo $lang['setting_'.$setting['name']];?> :</label>
									<div class="col-sm-8">
										<input type="text" name="settings[<?php echo $setting['id'];?>]" class="form-control" value="<?php echo $setting['value'];?>" />
									</div>
								</div>
							<?php elseif($setting['name'] == 'email_message'): ?>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="text-align:left;"><?php echo $lang['setting_'.$setting['name']];?> :</label>
									<div class="col-sm-8">
										<textarea type="text" name="settings[<?php echo $setting['id'];?>]" class="form-control editor"><?php echo $setting['value']; ?></textarea>
									</div>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach;?>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-4 col-sm-8">
				<button type="submit" class="btn btn-default">Сохранить</button>
			</div>
		</div>
		<input type="hidden" name="task" value="save_settings">
	</form>
</div>