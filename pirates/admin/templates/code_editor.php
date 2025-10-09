<form action="index.php?task=saveTemplateHtml&templateName=<?php echo $templateName; ?>" method="POST">
	<ul class="list-inline ksl-toolbar">
		<li>
			<button type="submit" class="btn btn-success">Сохранить</button>
		</li>
	</ul>

	<textarea name="templateHtml" class="hide"><?php echo $templateHtml; ?></textarea>
	<div id="code_editor"></div>
</form>
<script src="//cdn.jsdelivr.net/ace/1.1.7/min/ace.js"></script>
<script src="//cdn.jsdelivr.net/ace/1.1.7/min/ext-searchbox.js"></script>
<script src="//cdn.jsdelivr.net/ace/1.1.7/min/ext-static_highlight.js"></script>
<script src="//cdn.jsdelivr.net/ace/1.1.7/min/ext-textarea.js"></script>
<script src="//cdn.jsdelivr.net/ace/1.1.7/min/mode-html.js"></script>
<script type="text/javascript">
    var editor = ace.edit("code_editor");
    var template = jQuery('textarea[name="templateHtml"]');
    var templateHtml = template.val();

    editor.setTheme("ace/theme/tomorrow_night_eighties");
    editor.getSession().setMode("ace/mode/javascript");
    editor.getSession().setMode("ace/mode/html");
    editor.getSession().setMode("ace/mode/php");

    editor.getSession().setValue(templateHtml);

	editor.getSession().on('change', function () {
	    template.text(editor.getSession().getValue());
	});

</script>