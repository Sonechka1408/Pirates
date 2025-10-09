<?php
    $templateName = checkVar($_GET['template_name']);
    if (!empty($templateName)) {
        $templateHtml = file_get_contents(ROOT . '/templates/' . $templateName . '.php');
		$templateHtml = str_replace(array('<textarea', '/textarea>'), array('<ttextarea', '/ttextarea>'), $templateHtml);
    }