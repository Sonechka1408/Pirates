<?php
	ini_set('session.gc_maxlifetime', 3600);
	ini_set('session.cookie_lifetime', 3600);

	mysql_connect($config['db_host'], $config['db_user'], $config['db_pass']);
	mysql_select_db($config['db_name']);
	mysql_query('set names utf8');