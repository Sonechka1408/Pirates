<?php
	function getTemplate() {

	    $templateInfo = array();
	    $template_id = $_SESSION['tid'];

	    if($template_id > 0){
	    	$templateInfo = _getTamplate($template_id);
	    }else{
		    $templateInfo = _getTamplate();
	    }
	    $_SESSION['tid'] = $templateInfo[0];
	    return $templateInfo;
	}

	function _getTamplate($tid = 0){
	    $query = '
	    	SELECT 
	    		`id`, 
	    		`template_name`
    		FROM 
    			`templates`
		';

		if($tid > 0){
			$query .= '
				WHERE
					`id`=' . $tid
			;
		}else{
			$query .= '
				ORDER BY 
					RAND() 
			';
		}
		$query .= ' LIMIT 1';
	    $res = mysql_query($query);
	    if (!$res) die('Ошибка подключения шаблона');
	    
	    if (mysql_num_rows($res) != 1) die('Ошибка подключения шаблона');
	    
	    $template_id = mysql_result($res, 0, 'id');
	    $template_name = mysql_result($res, 0, 'template_name');

	    return array($template_id, $template_name);
	}

	function setSession($sessionId){
		if(!empty($sessionId)){
	        $query = '
		        INSERT INTO
		        	`ksl_sessions` (
		        		`session_id`
        			)
				VALUES(
					\'' . mysql_real_escape_string($sessionId) . '\'
				)
		        ON DUPLICATE KEY 
		        UPDATE 
		        	`session_id`=VALUES(`session_id`)
			';
	        mysql_query($query);

	        $query = '
		        SELECT
		        	`session_id`
	        	FROM
		        	`ksl_block_display_statistic`
				WHERE
					`session_id`=\'' . mysql_real_escape_string($sessionId) . '\'
			';
	        $result = mysql_query($query);

	        $blocksLength = count($_SESSION['blocks']) - 1;
	        if(mysql_num_rows($result) != $blocksLength + 1){
		        $query = '
			        DELETE FROM
			        	`ksl_block_display_statistic`
					WHERE
						`session_id`=\'' . mysql_real_escape_string($sessionId) . '\'
				';
		        $result = mysql_query($query);

		        $query = '
			        INSERT INTO
			        	`ksl_block_display_statistic` (
			        		`session_id`,
			        		`block_id`,
			        		`abtest_id`,
			        		`utmcontent_id`
	        			)VALUES
				';
				$i = 0;
				foreach ($_SESSION['blocks'] as $blockId) {
					$query .= '
						(
							\'' . mysql_real_escape_string($sessionId) . '\',
							' . clearIntMysqlVar($blockId) . ',
							' . clearIntMysqlVar(array_first_element($_SESSION['ABTests'][$blockId])) . ',
							' . clearIntMysqlVar(array_first_element($_SESSION['UTMContent'][$blockId])) . '
						)
					';
					if($blocksLength != $i){
						$query .= ',';
					}
					$i++;
				}
		        mysql_query($query);
	        }

	        return true;
		}
		return false;
	}

	function array_first_element($arr) {
		list($k) = array_keys($arr);
		return $arr[$k];
	}

	function clearIntMysqlVar($var){
		$var = mysql_real_escape_string($var)?$var:0;
		return $var;
	}

	function getPaymetForm($desc, $out_summ, $sbscrbId) {
	    global $config;
	    // 2.
	    // Оплата заданной суммы с выбором валюты на сайте ROBOKASSA
	    // Payment of the set sum with a choice of currency on site ROBOKASSA
	    
	    // регистрационная информация (логин, пароль #1)
	    // registration info (login, password #1)
	    $mrh_login = $config['mrh_login'];
	    $mrh_pass1 = $config['mrh_pass1'];
	    // номер заказа
	    // number of order
	    $inv_id = 0;
	    // описание заказа
	    // order description
	    $inv_desc = $desc;
	    // сумма заказа
	    // sum of order
	    //$out_summ = "8.96";
	    
	    // тип товара
	    // code of goods
	    $shp_item = $sbscrbId;
	    // предлагаемая валюта платежа
	    // default payment e-currency
	    $in_curr = "";
	    // язык
	    // language
	    $culture = "ru";
	    // формирование подписи
	    // generate signature
	    $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
	    // форма оплаты товара
	    return "<input type=hidden name=MrchLogin value=$mrh_login>" . "<input type=hidden name=OutSum value=$out_summ>" . "<input type=hidden name=InvId value=$inv_id>" . "<input type=hidden name=Desc value='$inv_desc'>" . "<input type=hidden name=SignatureValue value=$crc>" . "<input type=hidden name=Shp_item value='$shp_item'>" . "<input type=hidden name=IncCurrLabel value=$in_curr>" . "<input type=hidden name=Culture value=$culture>" . "<input type=\"submit\" class=\"btn btn-block btn-lg btn-success\" value=\"Перейти к оплате\">" . "</form>";
	    // payment form
	    
	}

	function checkPayStatus() {
	    global $config;
	    // регистрационная информация (пароль #1)
	    // registration info (password #1)
	    $mrh_pass1 = $config['mrh_pass1'];
	    // чтение параметров
	    // read parameters
	    $out_summ = $_REQUEST["OutSum"];
	    $inv_id = $_REQUEST["InvId"];
	    $shp_item = $_REQUEST["Shp_item"];
	    $crc = $_REQUEST["SignatureValue"];
	    
	    $crc = strtoupper($crc);
	    
	    $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item"));
	    // проверка корректности подписи
	    // check signature
	    $flag = true;
	    if ($my_crc != $crc) {
	        $flag = false;
	    }
	    
	    return $flag;
	}

	function checkVar($var, $default = null) {
	    $tmp = $default;
	    if (isset($var) && !empty($var)) {
	        $tmp = $var;
	    }
	    return $tmp;
	}

	function checkLogin() {
	    $login = checkVar($_POST['login']);
	    $password = checkVar($_POST['password']);
	    if (!empty($login) && !empty($password)) {
	        $query = 'select value from settings where name = "password"';
	        $res = mysql_query($query);
	        $db_password = mysql_result($res, 0);
	        if ($login == 'admin' && $password == $db_password) $_SESSION['login'] = true;
	    }
	    
	    if (!isset($_SESSION['login'])) return false;
	    
	    return true;
	}
	
	function checkLoginPartner() {
	    $login    = checkVar($_POST['login']);
	    $password = checkVar($_POST['password']);

	    if (!empty($login) && !empty($password)) {
			$query = '
				SELECT 
				        `p`.`id`,
				        `p`.`username`,
				        `p`.`password`
					FROM 
						`ksl_partners` AS `p`
					WHERE
						`p`.`username`=\'' . mysql_real_escape_string($login) . '\'
			';
	        $result = mysql_query($query);
	        if(mysql_num_rows($result) > 0){
	        	
	        	$partner                   = mysql_fetch_assoc($result);
	        	$_SESSION['login_partner'] = false;
	        	$_SESSION['utm_partner']   = $partner['id'];

				if (password_verify($password, $partner['password'])) {
				    $_SESSION['login_partner'] = true;
				}
	        }
	    }
	    
	    if (!isset($_SESSION['login_partner'])) return false;
	    
	    return true;
	}	

	function getProduct($id, $shares = array()){
		$product = array();
		if($id > 0){
			$query = '
				SELECT 
					`id`,
					`title`,
					`price`,
					`introcontent`,
					`content`
				FROM 
					`subscribes`
				WHERE
					`id`=' . mysql_real_escape_string($id) . '
				ORDER BY `id`
			';
			$res = mysql_query($query);
			$product = mysql_fetch_object($res);
			
			foreach($shares as $share){
				if ($share){
					$product->price -= $share['price'];
				}
			}
			if ($product->price < 0){
				$product->price = 0;
			}
		}
		return $product;
	}
	
	function getProducts($shares = array()){
		$products = array();

		$query = '
			SELECT 
				*
			FROM 
				`subscribes`
			ORDER BY `id`
		';
		$res = mysql_query($query);
		while($product = mysql_fetch_array($res)){
			foreach($shares as $share){
				if ($share){
					$product['price'] -= $share['price'];
				}
			}
			if ($product['price'] < 0)
				$product['price'] = 0;
			$products[] = $product;
		}
		return $products;
	}	

	function getReviews(){
		$reviews = array();
		$query = '
			SELECT 
		        `r`.*
			FROM 
				`ksl_reviews` AS `r`
			WHERE
				`r`.`state`=1
			ORDER BY `r`.`ordering`
		';
		$res = mysql_query($query);
		while($row = mysql_fetch_object($res)){
			if(!file_exists(ROOT . '/assets/images/review/' . $row->avatar) || empty($row->avatar)){
				$row->avatar = 'default-avatar.jpg';
			}
			$reviews[] = $row;
		}
		return $reviews;
	}

	function getBlock($blockId){
		$utm_id = 0;
		/*if(isset($_SESSION['UTMContent'][$blockId])){
			$utm_id = array_first_element($_SESSION['UTMContent'][$blockId]);
		}*/
		$block = getUTMContent($blockId, $utm_id);

		//$_SESSION['ABTests'] = null;
		//$_SESSION['UTMContent'] = null;
		
		$_SESSION['blocks'][$blockId] = $blockId;
		if(isset($block->data['id']) && !empty($block->data['id'])){
			$_SESSION['UTMContent'][$blockId][$block->data['id']] = $block->data['id'];
		}
		
		if(!$block){
			$test_id = 0;
			if(isset($_SESSION['ABTests'][$blockId])){
				$test_id = array_first_element($_SESSION['ABTests'][$blockId]);
			}else{
				if(checkRightDisplayABTest($blockId)){
					$block = getABTests($blockId, 0, true);
				}
			}

			if(!$block){
				$block = getABTests($blockId, $test_id);
			}

			if(isset($block->data['test_id']) && !empty($block->data['test_id'])){
				$_SESSION['ABTests'][$blockId][$block->data['test_id']] = $block->data['test_id'];
			}
		}

		return $block;
	}

	function checkRightDisplayABTest($blockId){
		if($blockId > 0){
			$blockInfo  = getBlockInfo($blockId);
			$countUsers = getCountUsers(); // ВСего пользователей
			$countDisplayBlock = getBlockDisplayStatistic($blockId); // Сколько раз уже показан блок

			$allowableNumber = $blockInfo['traffic'] * $countUsers / 100;
			$allowableNumber = ceil($allowableNumber); // допустимое колво людей для текущего блока
			if($allowableNumber <= 0){
				$allowableNumber = 1;
			}
/*
	echo '<br>';
	echo 'ID Блока - '. $blockId;
	echo '<br>';
	echo 'Весь трафик - '. $countUsers;
	echo '<br>';
	echo 'Допустпимое колво отображений блока - '. $allowableNumber;
	echo '<br>';
	echo 'Сколько раз уже показан блок - '. $countDisplayBlock;
	echo '<br>';
	echo '% трафика допустимый блоку - '. $blockInfo['traffic'] . '%';
	echo '<br>';*/
	//print_r($blockInfo);

			if($countDisplayBlock < $allowableNumber){
				return true;
			}

		}
		return false;
	}

	function getStatisticPercentDisplayBlock($blockId, $precision = 2){
		$percent = 0;
		if($blockId > 0){
			$percent = round(getBlockDisplayStatistic($blockId) * getCountUsers() / 100, $precision);
		}
		return $percent;
	}

	function getBlockInfo($blockId){
		$block = array();

		if($blockId > 0){			
			$query = '
			SELECT 
			        `b`.`id`,
			        `b`.`title`,
			        `b`.`traffic`
				FROM 
					`ksl_blocks` AS `b`
				WHERE
					`b`.`id`=' . mysql_real_escape_string($blockId)
			;
			$res = mysql_query($query);
			$block = mysql_fetch_assoc($res);
		}
		return $block;
	}

	function getABTests($blockId, $test_id = 0, $rand = false){
		$abTests = new stdClass;

		if($blockId > 0){
			$query = '
			SELECT 
			        `b`.`id`,
			        `t`.`id` AS `test_id`,
			        `t`.`content`,
			        `t`.`default`,
			        `b`.`traffic`,
			        `b`.`id` AS `block_id`,
			        `b`.`title` AS `block_title`
				FROM 
					`ksl_abtests_blocks` AS `tb`
				LEFT JOIN 
					`ksl_blocks` AS `b`
				ON
					`b`.`id`=`tb`.`block_id`
				LEFT JOIN 
					`ksl_ab_tests` AS `t`
				ON
					`t`.`id`=`tb`.`ab_test_id`
				WHERE
					`b`.`id`=' . mysql_real_escape_string($blockId) . '
			';

			if($test_id > 0){
				$query .= '
					AND
						`t`.`id`=' . mysql_real_escape_string($test_id) . '
				';
			}else{
				if($rand){
					$query .= '
						AND
							`t`.`default`=0
						ORDER BY 
							RAND()
					';
				}else{
					$query .= '
						AND
							`t`.`default`=1
						ORDER BY 
							`t`.`id` ASC 
					';
				}
			}

			$query .= ' LIMIT 0, 1';

			$res = mysql_query($query);
			if(mysql_num_rows($res) > 0){
				while ($row = mysql_fetch_assoc($res)) {
					$abTests->id = $row['block_id'];
					$abTests->data = $row;
				}
				return $abTests;
			}
		}
		return false;
	}

	function getCountUsers(){
		$query = '
		SELECT 
				SQL_CALC_FOUND_ROWS `id`
			FROM 
				`ksl_sessions`
		';
		$res = mysql_query($query);
		$query = 'SELECT FOUND_ROWS()';
		$res = mysql_query($query);
		return $total = mysql_result($res, 0);
	}

	function getBlockDisplayStatistic($blockId){
		if($blockId > 0){
	        $query = '
				SELECT 
					SQL_CALC_FOUND_ROWS `t`.`id`
				FROM 
					`ksl_block_display_statistic` AS `s`
		        LEFT JOIN 
		        	`ksl_abtests_blocks` AS `ab` ON `s`.`block_id`=`ab`.`block_id`
		        LEFT JOIN 
		        	`ksl_ab_tests` AS `t` ON `t`.`id`=`ab`.`ab_test_id`
		        WHERE
		        	`t`.`default`=0
	        	AND
					`s`.`block_id`=\'' . mysql_real_escape_string($blockId) . '\'
		        GROUP BY 
		        	`t`.`id`
			';
			$res = mysql_query($query);
			$query = 'SELECT FOUND_ROWS()';
			$res = mysql_query($query);
			return $total = mysql_result($res, 0);
		}
		return 0;
	}

	function getStatisticOrdersCount(){
        $query = '
			SELECT 
				SQL_CALC_FOUND_ROWS `o`.`id`
			FROM 
				`orders` AS `o`
		';
		$res = mysql_query($query);
		$query = 'SELECT FOUND_ROWS()';
		$res = mysql_query($query);
		return $total = mysql_result($res, 0);
	}

	function getStatisticOrdersABTest($testId){
		if($testId > 0){
	        $query = '
				SELECT 
					SQL_CALC_FOUND_ROWS `t`.`id`
				FROM 
					`ksl_block_display_statistic` AS `s`
		        INNER JOIN 
		        	`orders` AS `o` ON `o`.`session_id`=`s`.`session_id`
		        LEFT JOIN 
		            `ksl_ab_tests` AS `t` ON `t`.`id`=`s`.`abtest_id`
		        WHERE
		            `t`.`id`=' . mysql_real_escape_string($testId) . '
			';
			$res = mysql_query($query);
			$query = 'SELECT FOUND_ROWS()';
			$res = mysql_query($query);
			return $total = mysql_result($res, 0);
		}
		return 0;
	}

	function getUTMContent($blockId, $utm_id = 0){
		global $utmContent;
		$utmBlocks = new stdClass;

		if($blockId > 0 && !empty($utmContent['keyword'])){
			$query = '
			SELECT 
			        `cb`.`block_id`,
			        `c`.`id` AS `cid`,
			        `c`.`keyword`,
			        `c`.`source`,
			        `c`.`utm_campaign`,
			        `c`.`utm_content`,
			        `c`.`content`
				FROM 
					`ksl_utm_content_blocks` AS `cb`
				LEFT JOIN 
					`ksl_utm_content` AS `c` 
				ON 
					`cb`.`utm_content_id`=`c`.`id`
				WHERE
					`cb`.`block_id`=' . mysql_real_escape_string($blockId) . '
			';

			if($utm_id > 0){
				$query .= '
					AND
						`c`.`id`=\'' . mysql_real_escape_string($utm_id) . '\'
				';
			}else{
				if(!empty($utmContent['keyword'])){
					$query .= '
						AND
							`c`.`keyword` LIKE \'' . mysql_real_escape_string(strtolower($utmContent['keyword'])) . '%\' AND (

								`c`.`keyword` LIKE \'' . mysql_real_escape_string(strtolower($utmContent['keyword'])) . '%\'
					';
				}

				if(!empty($utmContent['source'])){
					$query .= '
						OR
							`c`.`source`=\'' . mysql_real_escape_string($utmContent['source']) . '\'
					';
				}
				if(!empty($utmContent['utm_campaign'])){
					$query .= '
						OR
							`c`.`utm_campaign` LIKE \'%' . mysql_real_escape_string(strtolower($utmContent['utm_campaign'])) . '%\'
					';
				}
				if(!empty($utmContent['utm_content'])){
					$query .= '
						OR
							`c`.`utm_content`=\'' . mysql_real_escape_string($utmContent['utm_content']) . '\'
					';
				}
				$query .= ')';
			}

			$query .= ' LIMIT 0, 1';

			$res = mysql_query($query);
			if(mysql_num_rows($res) > 0){
				while ($row = mysql_fetch_assoc($res)) {

					$utmBlocks->id = $row['block_id'];

					$utmBlocks->data = $row;
					$utmBlocks->data['id'] = $row['cid'];

					unset($utmBlocks->data['cid']);
				}
				return $utmBlocks;
			}
		}
		return false;
	}

	function _getShares($cid = 0, &$shareCurrents, &$sharePrevs, &$shareNexts){
		
		global $utmContent, $sharesInfo;
		$shares = array();

		$query = '
			SELECT 
			        `s`.`id`,
			        `s`.`title`,
			        `s`.`price`,
			        `s`.`old_price`,
			        `s`.`image`,
			        `s`.`introcontent`,
			        `s`.`keyword`,
			        `s`.`source`,
			        `s`.`utm_campaign`,
			        `s`.`utm_content`,
			        `s`.`view_date`,
			        `s`.`state`,
			        `c`.`id` AS `category_id`,
			        `c`.`title` AS `category_title`
				FROM 
					`ksl_category_shares` AS `cs`
				LEFT JOIN 
					`ksl_shares` AS `s`
				ON
					`cs`.`share_id` = `s`.`id`
				LEFT JOIN 
					`ksl_categories` AS `c`
				ON
					`cs`.`cid` = `c`.`id`
				WHERE
					`s`.`state` = 1
		';

		if($cid > 0){
			$query .= '
				AND
					`c`.`id` = \'' . mysql_real_escape_string(checkVar($cid)) . '\'
			';
		}

		$query .= '
			ORDER BY 
				`s`.`view_date` ASC 
		';

		$res = mysql_query($query);
		$i = 0;
		$sharesInfo['update'] = false;

		while($row = mysql_fetch_assoc($res)){

			$shares[$i] = (object)$row;
			$shares[$i]->category = new stdClass;
			$shares[$i]->category->id = $row->category_id;
			$shares[$i]->category->title = $row->category_title;

			calcShareInfo($shares[$i], $sharesInfo['update'], $shareCurrents, $sharePrevs, $shareNexts);
			if($shares[$i]->current){
				$sharesInfo['current'] = $i;
			}

			unset($row->category_id);
			unset($row->category_title);
			unset($shares[$i]->category_id);
			unset($shares[$i]->category_title);

			$i++;
		}
		return $shares;
	}

	function getShares($cid = 0){
		global $utmContent, $sharesInfo;
		
		$shareCurrents = array();
		$sharePrevs = array();
		$shareNexts = array();
		$shares = array();
		$sharesInfo = array(
			'current' => false,
			'utm' => false,
			'update' => false
		);
		
		_getShares($cid, $shareCurrents, $sharePrevs, $shareNexts);
		if (!$sharesInfo['update']) {
			$query = '
				UPDATE `ksl_shares` AS `s` JOIN
				(
				  SELECT `id`, @n := @n + 1 `rnum`
				    FROM `ksl_shares` CROSS JOIN (SELECT @n := 0) i
				   ORDER BY `id`
				) AS `t2` ON `s`.id = `t2`.`id` CROSS JOIN
				(
				  SELECT MAX(`view_date`) `view_date` FROM `ksl_shares`
				) AS `q`
				   SET `s`.`view_date` = `q`.`view_date` + INTERVAL `t2`.`rnum` DAY
			';
			mysql_query($query);
			_getShares($cid, $shareCurrents, $sharePrevs, $shareNexts);
		}

		$shares = array_merge($shares, $shareCurrents);
		if(empty($shareNexts)){
			$shareNexts = arrayCopy($sharePrevs);
			$shareNexts = _setPrevsShares($shareNexts, true);
		}
		if(empty($sharePrevs)){
			$sharePrevs = arrayCopy($shareNexts);
			$sharePrevs = _setPrevsShares($sharePrevs);
		}

		$shares = array_merge($shares, $shareNexts);
		$shares = array_merge($shares, $sharePrevs);

		foreach ($shares as $key => $share) {
			if(getShareByUTM($share)){
				$sharesInfo['utm'] = $key;
			}
		}

		if($sharesInfo['utm']){

			$UTMKey = $sharesInfo['utm'];
			$currentKey = 0;

			$shares[$currentKey]->view_date = $shares[$UTMKey]->view_date;
			$shares[$UTMKey]->view_date = date('Y-m-d');

			list($shares[$currentKey], $shares[$UTMKey]) = array($shares[$UTMKey], $shares[$currentKey]);

			calcShareInfo($shares[$currentKey]);
			calcShareInfo($shares[$UTMKey]);
		}

		return $shares;
	}

	function arrayCopy(array $array) {
	    $result = array();
	    foreach ($array as $key => $val) {
	        if (is_array($val)) {
	            $result[$key] = arrayCopy($val);
	        } elseif (is_object($val)) {
	            $result[$key] = clone $val;
	        } else {
	            $result[$key] = $val;
	        }
	    }
	    return $result;
	}

	function _setPrevsShares($shares, $next = false){
		if(!empty($shares)){
			$date = new DateTime();
			foreach ($shares as $key => &$share) {
				if($next){
					$date->modify('+1 day');
				}else{
					$date->modify('-1 day');
				}
				$share->view_date = $date->format('Y-m-d');
				calcShareInfo($share);
			}
		}
		return $shares;
	}

	function getShareByUTM($share){
		global $utmContent;
		
		$shareKeyWord = explode(',', $share->keyword);
		$UTMKeyWord = explode(',', $utmContent['keyword']);

		foreach ($UTMKeyWord as $utm) {
			$utm = mb_strtolower(trim($utm));
			foreach ($shareKeyWord as $shareUTM) {
				$shareUTM = mb_strtolower(trim($shareUTM));
				if(!empty($shareUTM)){
					if(mb_strstr($shareUTM, $utm)){
						return true;
					}
				}
			}
		}
		return false;
	}

	function calcShareInfo(&$share, &$flag, &$shareCurrents = array(), &$sharePrevs = array(), &$shareNexts = array()){
		$date = new DateTime();
		$currentDate = new DateTime('now');
		$currentDate = DateTime::createFromFormat('Y-m-d', $currentDate->format('Y-m-d'));
		$view_date = DateTime::createFromFormat('Y-m-d', $share->view_date);
		$share->view_date = $view_date->format('Y-m-d');
		
		$share->diff_time = 0;
		$share->previous = false;
		$share->current = false;
		$share->next = false;

		if($currentDate == $view_date){
			$share->current = true;
			$shareCurrents[] = $share;
		}elseif($currentDate > $view_date){
			$share->previous = true;
			$share->diff_time = dateDiff($view_date, $currentDate);
			$sharePrevs[] = $share;
		}elseif($currentDate < $view_date){
			$share->next = true;
			$share->diff_time = dateDiff($currentDate, $view_date);
			$shareNexts[] = $share;
		}

		if($share->current){
			$flag = true;
		}
	}

	function dateDiff($date1, $date2, $abs = false){
        $diff = $date2->getTimestamp() - $date1->getTimestamp();
        if($abs){
            $diff = abs($diff);
        }
        return $diff;
    }