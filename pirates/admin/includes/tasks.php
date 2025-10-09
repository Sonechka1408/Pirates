<?php
function create_new_template() {
    $title = checkVar($_GET['title']);
    $template_name = checkVar($_GET['template_name']);
    if (!empty($title) && !empty($template_name)) {
        copy(ROOT . '/templates/default.php', ROOT . '/templates/' . $template_name . '.php');
        $query = 'insert into templates (`title`, `template_name`) values ("' . $title . '", "' . $template_name . '")';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function saveTemplateHtml(){
	$templateName = checkVar($_GET['templateName']);
	$templateHtml = checkVar($_POST['templateHtml']);
	
	$templateHtml = str_replace('ttextarea', 'textarea', $templateHtml);

	if($templateName && $templateHtml){
		$template = fopen(ROOT . '/templates/' . $templateName . '.php', 'w');
		fwrite($template, $templateHtml);
		fclose($template);
	}
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function delete_template() {
    $template_name = checkVar($_GET['template_name']);

    if (!empty($template_name)) {
        $query = '
	        DELETE FROM
	        	`templates`
			WHERE
				`template_name`=' . mysql_escape_string($template_name) . '
		';
        mysql_query($query);
		unlink(ROOT . '/templates/' . $template_name . '.php');
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function save_settings() {
    $settings = checkVar($_GET['settings']);
    foreach ($settings as $key => $val) {
        $query = 'update settings set value = "' . mysql_escape_string($val) . '" where id = ' . $key;
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function logout() {
    session_destroy();
    header('Location: /admin/');
}

function logoutPartner() {
    session_destroy();
    header('Location: /admin/');
}

function create_utm_content() {
    $utm = checkVar($_POST['utm']);
	$utmArray = array();

	foreach ($utm as $key => $utmItem) {
		foreach ($utmItem as $keyValue => $utmItemValue) {
			$utmArray[$keyValue][$key] = $utmItemValue;
		}
	}

    if (!empty($utmArray)) {
    	foreach ($utmArray as $key => $utmItem) {
    		if(!empty($utmItem['keyword'])){
		        $query = '
		        INSERT INTO 
		    	`ksl_utm_content` (
					`keyword`, 
					`source`, 
					`utm_campaign`, 
					`utm_content`, 
					`content`
				) 
		        VALUES (
		        	\'' . mysql_escape_string($utmItem['keyword']) . '\', 
		        	\'' . mysql_escape_string($utmItem['source']) . '\', 
		        	\'' . mysql_escape_string($utmItem['utm_campaign']) . '\', 
		        	\'' . mysql_escape_string($utmItem['utm_content']) . '\', 
		        	\'' . mysql_escape_string($utmItem['content']) . '\'
		        )';
		        mysql_query($query);
		        $utm_content_id = mysql_insert_id();

				$query = 'INSERT INTO `ksl_utm_content_blocks` (`id`, `utm_content_id`, `block_id`) VALUES (NULL, ' . $utm_content_id . ', ' . $utm['block_id'] . ')';
				mysql_query($query);
    		}
    	}
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function update_utm_content() {
    $utm = checkVar($_POST['utm']);
    $block_id = checkVar($utm['block_id']);
    $utmNewArray = array();
    $utmArray = array();
    $UTMIds = array();

	foreach ($utm as $key => $utmItem) {
		if(!is_numeric($key)){
			foreach ($utmItem as $keyValue => $utmItemValue) {
				$utmNewArray[$keyValue][$key] = $utmItemValue;
			}
		}else{
			$utmArray[$key] = $utmItem;
		}
	}

    if (!empty($utmArray)) {
    	foreach ($utmArray as $key => $utmItem) {
	        $query = '
		        UPDATE 
		        	`ksl_utm_content` 
		        SET 
		        	`keyword`=\'' . mysql_escape_string($utmItem['keyword']) . '\', 
					`source`=\'' . mysql_escape_string($utmItem['source']) . '\',
					`utm_campaign`=\'' . mysql_escape_string($utmItem['utm_campaign']) . '\',
					`utm_content`=\'' . mysql_escape_string($utmItem['utm_content']) . '\', 
					`content`=\'' . mysql_escape_string($utmItem['content']) . '\'
				WHERE
					`id`=' . mysql_escape_string($key) . '
			';
	        mysql_query($query);
	        $UTMIds[] = $key;
    	}
    }
    updateConnectionsUTMContent($block_id, $UTMIds);
    if(!empty($utmNewArray)){
    	setUTMContent($utmNewArray, $block_id);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function delete_utm_content() {
    $block_id = checkVar($_GET['block_id']);

    if (!empty($block_id)) {
        $query = '
	        SELECT utm_content_id FROM
	        	`ksl_utm_content_blocks`
			WHERE
				`block_id`=' . mysql_escape_string($block_id) . '
		';
        $res = mysql_query($query);
		while($row = mysql_fetch_array($res)){
			$query = '
				DELETE FROM
					`ksl_utm_content`
				WHERE
					`id`=' . mysql_escape_string($row['utm_content_id']) . '
			';
			mysql_query($query);	

			$query = '
				DELETE FROM
					`ksl_block_display_statistic`
				WHERE
					`block_id`=' . mysql_escape_string($block_id) . ' AND `utmcontent_id`=' . mysql_escape_string($row['utm_content_id']) . '
			';
			mysql_query($query);			
		}
		
        $query = '
	        DELETE FROM
	        	`ksl_utm_content_blocks`
			WHERE
				`block_id`=' . mysql_escape_string($block_id) . '
		';
        mysql_query($query);
	}
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function updateConnectionsUTMContent($blockId, $UTMIds){
	if($blockId > 0 && !empty($UTMIds)){
        $query = '
	        UPDATE 
	        	`ksl_utm_content_blocks` 
	        SET 
	        	`block_id`=' . mysql_escape_string($blockId) . '
			WHERE
				`utm_content_id` IN (' . implode(', ', $UTMIds) . ')
		';
        mysql_query($query);

        return true;
	}
	return false;
}

function setUTMContent($data, $block_id){
    if (!empty($data)) {
    	foreach ($data as $key => $utm) {
	        $query = '
	        INSERT INTO 
	    	`ksl_utm_content` (
				`keyword`, 
				`source`, 
				`utm_campaign`, 
				`utm_content`, 
				`content`
			) 
	        VALUES (
	        	\'' . mysql_escape_string($utm['keyword']) . '\', 
	        	\'' . mysql_escape_string($utm['source']) . '\', 
	        	\'' . mysql_escape_string($utm['utm_campaign']) . '\', 
	        	\'' . mysql_escape_string($utm['utm_content']) . '\', 
	        	\'' . mysql_escape_string($utm['content']) . '\'
	        )';
	        mysql_query($query);
	        $utm_content_id = mysql_insert_id();

			$query = 'INSERT INTO `ksl_utm_content_blocks` (`id`, `utm_content_id`, `block_id`) VALUES (NULL, ' . $utm_content_id . ', ' . $block_id . ')';
			mysql_query($query);
    	}
    	return true;
    }
    return false;
}

function update_partner() {
	$partner = checkVar($_POST['partner']);

    if (!empty($partner) && $partner['id'] > 0) {
        $query = '
	        UPDATE 
	        	`ksl_partners` 
	        SET 
	        	`username`=\'' . mysql_escape_string($partner['username']) . '\'
		';

		$password = mysql_escape_string(checkVar($partner['password']));
		if($password){
			$hash = password_hash($password, PASSWORD_DEFAULT);
			$query .= ',`password`=\'' . $hash . '\'';
		}
		$query .= '
			WHERE
				`id`=' . mysql_escape_string($partner['id']) . '
		';

        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function create_partner() {
    $partner = checkVar($_POST['partner']);

    if (!empty($partner)) {

		$query = '
			SELECT 
			        `p`.`id`
				FROM 
					`ksl_partners` AS `p`
				WHERE
					`p`.`username`=\'' . mysql_escape_string(checkVar($partner['username'])) . '\'
		';

        $res = mysql_query($query);

        if(!mysql_num_rows($res)){
        	$password = mysql_escape_string(checkVar($partner['password']));
        	if($password){
	        	$hash = password_hash($password, PASSWORD_DEFAULT);
		        $query = '
			        INSERT INTO 
			        	`ksl_partners` (
			        		`username`,
			        		`password`
						) 
			        VALUES (
			        	\'' . mysql_escape_string(checkVar($partner['username'])) . '\',
			        	\'' . $hash . '\'
		        	)
				';
		        mysql_query($query);
        	}
        }
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function delete_partner() {
    $partner_id = checkVar($_GET['partner_id']);

    if (!empty($partner_id)) {
        $query = '
	        DELETE FROM
	        	`ksl_partners`
			WHERE
				`id`=' . mysql_escape_string($partner_id) . '
		';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function create_category() {
    $category = checkVar($_GET['category']);

    if (!empty($category)) {
        $query = '
	        INSERT INTO 
	        	`ksl_categories` (
	        		`title`
				) 
	        VALUES (
	        	\'' . mysql_escape_string(checkVar($category['title'])) . '\'
        	)
		';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function update_category() {
    $category = checkVar($_GET['category']);

    if (!empty($category) && $category['id'] > 0) {
        $query = '
	        UPDATE 
	        	`ksl_categories` 
	        SET 
	        	`title`=\'' . mysql_escape_string($category['title']) . '\'
			WHERE
				`id`=' . mysql_escape_string($category['id']) . '
		';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function delete_share_cat() {
    $cat_id = checkVar($_GET['cat_id']);

    if (!empty($cat_id)) {
        $query = '
	        DELETE FROM
	        	`ksl_categories`
			WHERE
				`id`=' . mysql_escape_string($cat_id) . '
		';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function update_block() {
    $block_id = checkVar($_GET['block_id']);
    $title = checkVar($_GET['title']);
    $ab_test = checkVar($_GET['ab_test'], 0);

    if (!empty($title) && $block_id > 0) {
        $query = '
	        UPDATE 
	        	`ksl_blocks` 
	        SET 
	        	`title`=\'' . mysql_escape_string($title) . '\'
			WHERE
				`id`=' . mysql_escape_string($block_id) . '
		';
        mysql_query($query);

        if($ab_test['id'] > 0){
	        $query = '
		        UPDATE 
		        	`ksl_ab_tests` 
		        SET 
		        	`content`=\'' . mysql_escape_string($ab_test['content']) . '\'
				WHERE
					`id`=' . mysql_escape_string($ab_test['id']) . '
			';
	        mysql_query($query);
        }
    }
    //exit();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function create_block() {
    $title = checkVar($_GET['title']);
    $traffic = checkVar($_GET['traffic'], 0);
    $content = checkVar($_GET['content'], null);

    if (!empty($title)) {
        $query = '
	        INSERT INTO 
	        	`ksl_blocks` (
	        		`title`,
	        		`traffic`
				) 
	        VALUES (
	        	\'' . mysql_escape_string($title) . '\',
	        	\'' . mysql_escape_string($traffic) . '\'
        	)
		';
        mysql_query($query);
        $block_id = mysql_insert_id();
    }

    if($block_id > 0){
	    $abTestContent[] = $content;
	    setABTests($abTestContent, $block_id, true);
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function delete_block() {
    $block_id = checkVar($_GET['block_id']);

    if (!empty($block_id)) {
        $query = '
	        SELECT utm_content_id FROM
	        	`ksl_utm_content_blocks`
			WHERE
				`block_id`=' . mysql_escape_string($block_id) . '
		';
        $res = mysql_query($query);
		while($row = mysql_fetch_array($res)){
			$query = '
				DELETE FROM
					`ksl_utm_content`
				WHERE
					`id`=' . mysql_escape_string($row['utm_content_id']) . '
			';
			mysql_query($query);	
		}
		
        $query = '
	        DELETE FROM
	        	`ksl_utm_content_blocks`
			WHERE
				`block_id`=' . mysql_escape_string($block_id) . '
		';
        mysql_query($query);
		
        $query = '
	        SELECT ab_test_id FROM
	        	`ksl_abtests_blocks`
			WHERE
				`block_id`=' . mysql_escape_string($block_id) . '
		';
        $res = mysql_query($query);
		while($row = mysql_fetch_array($res)){
			$query = '
				DELETE FROM
					`ksl_ab_tests`
				WHERE
					`id`=' . mysql_escape_string($row['ab_test_id']) . '
			';
			mysql_query($query);	
		}
		
        $query = '
	        DELETE FROM
	        	`ksl_abtests_blocks`
			WHERE
				`block_id`=' . mysql_escape_string($block_id) . '
		';
        mysql_query($query);		
		
		$query = '
			DELETE FROM
				`ksl_block_display_statistic`
			WHERE
				`block_id`=' . mysql_escape_string($block_id) . '
		';
		mysql_query($query);			
	}
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function create_review() {
    $review = $_POST['review'];

    if (!empty($review['title'])) {

	    if(!$imageName = loadShareImage('review')){
	        $imageName = null;
	    }
	    if(!$fileName = loadShareFile('review')){
	        $fileName = null;
	    }			

        $query = '
	        INSERT INTO 
	        	`ksl_reviews` (
			        `title`,
			        `avatar`,
					`file`,
			        `username`,
			        `phone`,
			        `email`,
			        `ordering`,
			        `introcontent`,
			        `content`,
			        `social_link`,
			        `about`,
			        `prehistory`,
			        `state`,
					`vk`,
					`tw`,
					`fb`
				) 
	        VALUES (
	        	\'' . mysql_escape_string(checkVar($review['title'])) . '\',
	        	\'' . mysql_escape_string(checkVar($imageName)) . '\',
				\'' . mysql_escape_string(checkVar($fileName)) . '\',
	        	\'' . mysql_escape_string(checkVar($review['username'])) . '\',
	        	\'' . mysql_escape_string(checkVar($review['phone'])) . '\',
	        	\'' . mysql_escape_string(checkVar($review['email'])) . '\',
	        	\'' . mysql_escape_string(checkVar($review['ordering'])) . '\',
	        	\'' . mysql_escape_string(checkVar($review['introcontent'])) . '\',
	        	\'' . mysql_escape_string(checkVar($review['content'])) . '\',
	        	\'' . mysql_escape_string(checkVar($review['social_link'])) . '\',
	        	\'' . mysql_escape_string(checkVar($review['about'])) . '\',
	        	\'' . mysql_escape_string(checkVar($review['prehistory'])) . '\',
	        	\'' . mysql_escape_string(checkVar($review['state'])) . '\',
	        	\'' . mysql_escape_string(checkVar($review['vk'])) . '\',
	        	\'' . mysql_escape_string(checkVar($review['tw'])) . '\',
	        	\'' . mysql_escape_string(checkVar($review['fb'])) . '\'
        	)
		';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function update_review() {
    $review = checkVar($_POST['review']);

    if (!empty($review['title']) && $review['id'] > 0) {

        $query = '
	        UPDATE 
	        	`ksl_reviews`
	        SET 
	        	`title`=\'' . mysql_escape_string(checkVar($review['title'])) . '\',
		';

        if($imageName = loadShareImage('review')){
	        $query .= '
	        	`avatar`=\'' . mysql_escape_string(checkVar($imageName)) . '\',
	        ';
        }
        if($fileName = loadShareFile('review')){
	        $query .= '
	        	`file`=\'' . mysql_escape_string(checkVar($fileName)) . '\',
	        ';
        }			

        $query .= '
	        	`username`=\'' . mysql_escape_string(checkVar($review['username'])) . '\',
	        	`phone`=\'' . mysql_escape_string(checkVar($review['phone'])) . '\',
	        	`email`=\'' . mysql_escape_string(checkVar($review['email'])) . '\',
	        	`ordering`=\'' . mysql_escape_string(checkVar($review['ordering'])) . '\',
	        	`introcontent`=\'' . mysql_escape_string(checkVar($review['introcontent'])) . '\',
	        	`content`=\'' . mysql_escape_string(checkVar($review['content'])) . '\',
	        	`social_link`=\'' . mysql_escape_string(checkVar($review['social_link'])) . '\',
	        	`about`=\'' . mysql_escape_string(checkVar($review['about'])) . '\',
	        	`prehistory`=\'' . mysql_escape_string(checkVar($review['prehistory'])) . '\',
	        	`vk`=\'' . mysql_escape_string(checkVar($review['vk'])) . '\',
	        	`tw`=\'' . mysql_escape_string(checkVar($review['tw'])) . '\',
	        	`fb`=\'' . mysql_escape_string(checkVar($review['fb'])) . '\',
	        	`state`=\'' . mysql_escape_string(checkVar($review['state'], 0)) . '\'
			WHERE
				`id`=' . mysql_escape_string(checkVar($review['id'])) . '
        ';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function delete_review() {
    $review_id = checkVar($_GET['review_id']);

    if (!empty($review_id)) {
        $query = '
	        DELETE FROM
	        	`ksl_reviews`
			WHERE
				`id`=' . mysql_escape_string($review_id) . '
		';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function getMaxShareDate(){
	$query = '
		SELECT 
			`view_date`
		FROM 
			`ksl_shares`
		ORDER BY 
			`view_date` DESC
		LIMIT 0, 1
	';
	$res = mysql_query($query);
	if (mysql_num_rows($res) > 0){
		$row = mysql_fetch_array($res);
		$maxViewDate =  $row['view_date'];
	} else {
		$maxViewDate = date('d.m.Y');
	}
	return $maxViewDate;
}

function create_share() {
    $share = $_POST['share'];

    if (!empty($share['title'])) {

		$maxViewDate = getMaxShareDate();

        if(!$imageName = loadShareImage('share')){
	        $imageName = null;
        }

        $query = '
	        INSERT INTO 
	        	`ksl_shares` (
			        `title`,
			        `price`,
					`life_time`,
					`fix_time`,
			        `old_price`,
			        `image`,
			        `keyword`,
			        `source`,
			        `utm_campaign`,
			        `utm_content`,
			        `view_date`,
					`introcontent`,
			        `state`
				) 
	        VALUES (
	        	\'' . mysql_escape_string(checkVar($share['title'])) . '\',
	        	\'' . mysql_escape_string(checkVar($share['price'], 0)) . '\',
	        	\'' . mysql_escape_string(checkVar($share['life_time'])) . '\',
	        	\'' . mysql_escape_string(checkVar($share['fix_time'])) . '\',
	        	\'' . mysql_escape_string(checkVar($share['old_price'], 0)) . '\',
	        	\'' . mysql_escape_string(checkVar($imageName)) . '\',
	        	\'' . mysql_escape_string(checkVar($share['keyword'])) . '\',
	        	\'' . mysql_escape_string(checkVar($share['source'])) . '\',
	        	\'' . mysql_escape_string(checkVar($share['utm_campaign'])) . '\',
	        	\'' . mysql_escape_string(checkVar($share['utm_content'])) . '\',
	        	DATE(\'' . mysql_escape_string($maxViewDate) . '\' + INTERVAL 1 DAY),
	        	\'' . mysql_escape_string(checkVar($share['introcontent'])) . '\',
	        	\'' . mysql_escape_string(checkVar($share['state'], 0)) . '\'
        	)
		';

        mysql_query($query);
        $share_id = mysql_insert_id();

        $query = '
	        INSERT INTO 
	        	`ksl_category_shares` (
			        `cid`,
			        `share_id`
				) 
	        VALUES (
	        	\'' . mysql_escape_string(checkVar($share['cid'])) . '\',
	        	\'' . mysql_escape_string(checkVar($share_id, 0)) . '\'
        	)
		';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function update_share() {
    $share = checkVar($_POST['share']);

    if (!empty($share['title']) && $share['id'] > 0) {
        $query = '
	        UPDATE 
	        	`ksl_shares`
	        SET 
	        	`title`=\'' . mysql_escape_string(checkVar($share['title'])) . '\',
	        	`life_time`=\'' . mysql_escape_string(checkVar($share['life_time'])) . '\',
	        	`fix_time`=\'' . mysql_escape_string(checkVar($share['fix_time'])) . '\',
	        	`price`=\'' . mysql_escape_string(checkVar($share['price'], 0)) . '\',
	        	`old_price`=\'' . mysql_escape_string(checkVar($share['old_price'], 0)) . '\',
	        	`keyword`=\'' . mysql_escape_string(checkVar($share['keyword'])) . '\',
	        	`source`=\'' . mysql_escape_string(checkVar($share['source'])) . '\',
	        	`utm_campaign`=\'' . mysql_escape_string(checkVar($share['utm_campaign'])) . '\',
	        	`utm_content`=\'' . mysql_escape_string(checkVar($share['utm_content'])) . '\',
	        	`introcontent`=\'' . mysql_escape_string(checkVar($share['introcontent'])) . '\',
		';

        if($imageName = loadShareImage('share')){
	        $query .= '
	        	`image`=\'' . mysql_escape_string(checkVar($imageName)) . '\',
	        ';
        }

        $query .= '
	        	`state`=\'' . mysql_escape_string(checkVar($share['state'], 0)) . '\'
			WHERE
				`id`=' . mysql_escape_string(checkVar($share['id'])) . '
        ';

        mysql_query($query);

        $query = '
        	UPDATE 
    			`ksl_category_shares` 
    		SET
        		`cid`=\'' . mysql_escape_string(checkVar($share['cid'])) . '\',
        		`share_id`=\'' . mysql_escape_string(checkVar($share['id'])) . '\'
    		WHERE
    			`share_id`=\'' . mysql_escape_string(checkVar($share['id'])) . '\'
		';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function delete_share() {
    $share_id = checkVar($_GET['share_id']);

    if (!empty($share_id)) {
        $query = '
	        DELETE FROM
	        	`ksl_category_shares`
			WHERE
				`share_id`=' . mysql_escape_string($share_id) . '
		';
        mysql_query($query);
		
        $query = '
	        DELETE FROM
	        	`ksl_shares`
			WHERE
				`id`=' . mysql_escape_string($share_id) . '
		';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function loadShareImage($folder){
	if(!empty($folder) && !empty($_FILES[$folder]['name']['image'])){
		$uploaddir = ROOT . '/assets/images/' . $folder . '/';
		$fileInfo = pathinfo($_FILES[$folder]['name']['image']);
		$imageName = uniqid() . '.' . time() . '.' . $fileInfo['extension'];
		$uploadfile = $uploaddir . $imageName;

		if (move_uploaded_file($_FILES[$folder]['tmp_name']['image'], $uploadfile)) {
		    return $imageName;
		}
	}

	return false;
}

function loadShareFile($folder){
	if(!empty($folder) && !empty($_FILES[$folder]['name']['file'])){
		$uploaddir = ROOT . '/assets/images/' . $folder . '/';
		$fileInfo = pathinfo($_FILES[$folder]['name']['file']);
		$imageName = $_FILES[$folder]['name']['file']; //uniqid() . '.' . time() . '.' . $fileInfo['extension'];
		$uploadfile = $uploaddir . $imageName;

		if (move_uploaded_file($_FILES[$folder]['tmp_name']['file'], $uploadfile)) {
		    return $imageName;
		}
	}

	return false;
}

function create_prodduct() {
    $title = checkVar($_POST['title']);
    $price = checkVar($_POST['price'], 0);
	$old_price = checkVar($_POST['old_price'], 0);
	$introcontent = checkVar($_POST['introcontent']);
    $content = checkVar($_POST['content']);

    if (!empty($title)) {
	    if(!$fileName = loadShareFile('product')){
	        $fileName = null;
	    }		
        $query = '
	        INSERT INTO 
	        	`subscribes` (
	        		`title`,
	        		`price`,
					`old_price`,
					`introcontent`,
	        		`content`,
					`file`
				) 
	        VALUES (
	        	\'' . mysql_escape_string($title) . '\',
	        	\'' . mysql_escape_string($price) . '\',
				\'' . mysql_escape_string($old_price) . '\',
				\'' . mysql_escape_string($introcontent) . '\',
	        	\'' . mysql_escape_string($content) . '\',
				\'' . mysql_escape_string($fileName) . '\'
        	)
		';

        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function update_prodduct() {
    $product = checkVar($_POST['product']);

    if (!empty($product['title']) && $product['id'] > 0) {
        $query = '
	        UPDATE 
	        	`subscribes`
	        SET 
	        	`title`=\'' . mysql_escape_string($product['title']) . '\',
	        	`price`=\'' . mysql_escape_string(checkVar($product['price'], 0)) . '\',
				`old_price`=\'' . mysql_escape_string(checkVar($product['old_price'], 0)) . '\',
				`introcontent`=\'' . mysql_escape_string($product['introcontent']) . '\',
		';
        if($fileName = loadShareFile('product')){
	        $query .= '
	        	`file`=\'' . mysql_escape_string($fileName) . '\',
	        ';
        }	
		$query .= '
	        	`content`=\'' . mysql_escape_string($product['content']) . '\'
			WHERE
				`id`=' . mysql_escape_string($product['id']) . '
		';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function delete_prodduct() {
    $product_id = checkVar($_GET['product_id']);

    if (!empty($product_id)) {
        $query = '
	        DELETE FROM
	        	`subscribes`
			WHERE
				`id`=' . mysql_escape_string($product_id) . '
		';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function create_ab_tests(){
	$default = false;
    $ab_tests = checkVar($_GET['ab_tests']);

    if (!empty($ab_tests)) {

    	if(isset($ab_tests['default'][$key]) && !empty($ab_tests['default'][$key])){
			$query = '
				UPDATE 
					`ksl_abtests_blocks` AS `tb`
				LEFT OUTER JOIN
				    `ksl_ab_tests` AS `t` ON `t`.`id`=`tb`.`ab_test_id`
				SET 
					`t`.default = 0
				WHERE 
					`tb`.`block_id`=' . mysql_escape_string($ab_tests['block_id']) . '
				AND 
					`t`.default = 1
			';
			mysql_query($query);
		}
		$query = '
			UPDATE 
				`ksl_blocks` 
			SET 
				`traffic` = \'' . mysql_escape_string($ab_tests['traffic']) . '\'
			WHERE
				`id`=' . mysql_escape_string($ab_tests['block_id'])
		;
		mysql_query($query);
		
    	foreach ($ab_tests['content'] as $key => $content) {
    		if(isset($ab_tests['default'][$key]) && !empty($ab_tests['default'][$key]) && !$default){
    			$default = true;
    		}else{
    			$default = false;
    		}

	        $query = '
		        INSERT INTO 
			    	`ksl_ab_tests` (
						`content`
				';
				if($default){
					$query .= ', `default`';
				}
				$query .= ') 
			        VALUES (
			        	\'' . mysql_escape_string($content) . '\'

			';
	        if($default){
	        	$query .= ', 1';
	        }
	        $query .= '
		        )
			';
	        mysql_query($query);
	        $ab_test_id = mysql_insert_id();

			$query = 'INSERT INTO `ksl_abtests_blocks` (`id`, `ab_test_id`, `block_id`) VALUES (NULL, ' . $ab_test_id . ', ' . $ab_tests['block_id'] . ')';
			mysql_query($query);
    	}
    }

	header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function update_ab_tests() {
    $ab_tests = checkVar($_GET['ab_tests']);
    $ABTestNewArray = array();
    $ABTestNewArray = array();
    $testIds = array();

	foreach ($ab_tests['content'] as $key => $content) {
		if(!isset($content['id'])){
			$ABTestNewArray[] = $ab_tests['content'][$key];
			unset($ab_tests['content'][$key]);
		}else{
			$ABTest[] = $content;
		}
	}

	if($ab_tests['block_id'] > 0){
        $query = '
	        UPDATE 
	        	`ksl_blocks` 
	        SET 
	        	`traffic`=\'' . mysql_escape_string($ab_tests['traffic']) . '\'
			WHERE
				`id`=' . mysql_escape_string($ab_tests['block_id']) . '
		';
        mysql_query($query);
	}

    if (!empty($ABTest)) {
    	foreach ($ABTest as $key => $ABTest) {
	        $query = '
		        UPDATE 
		        	`ksl_ab_tests` 
		        SET 
		        	`content`=\'' . mysql_escape_string($ABTest['content']) . '\'
				WHERE
					`id`=' . mysql_escape_string($ABTest['id']) . '
			';
	        mysql_query($query);
	        $testIds[] = $ABTest['id'];
    	}
    }
    updateConnectionsABTest($ab_tests['block_id'], $testIds);
    if(!empty($ABTestNewArray)){
    	setABTests($ABTestNewArray, $ab_tests['block_id']);
    }
    setDefaultABTest($ab_tests['block_id'], $ab_tests['default']);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function delete_ab_test() {
    $block_id = checkVar($_GET['block_id']);

    if (!empty($block_id)) {
        $query = '
	        SELECT ab_test_id FROM
	        	`ksl_abtests_blocks`
			WHERE
				`block_id`=' . mysql_escape_string($block_id) . '
		';
        $res = mysql_query($query);
		while($row = mysql_fetch_array($res)){
			$query = '
				DELETE FROM
					`ksl_ab_tests`
				WHERE
					`id`=' . mysql_escape_string($row['ab_test_id']) . '
			';
			mysql_query($query);	

			$query = '
				DELETE FROM
					`ksl_block_display_statistic`
				WHERE
					`block_id`=' . mysql_escape_string($block_id) . ' AND `abtest_id`=' . mysql_escape_string($row['ab_test_id']) . '
			';
			mysql_query($query);			
		}
		
        $query = '
	        DELETE FROM
	        	`ksl_abtests_blocks`
			WHERE
				`block_id`=' . mysql_escape_string($block_id) . '
		';
        mysql_query($query);
	}
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function setABTests($data, $block_id, $default = false){
    if (!empty($data) && $block_id > 0) {
    	foreach ($data as $key => $content) {
	        $query = '
		        INSERT INTO 
			    	`ksl_ab_tests` (
						`content`
				) 
		        VALUES (
		        	\'' . mysql_escape_string($content) . '\'

		        )
			';

	        mysql_query($query);
	        $ab_test_id = mysql_insert_id();
			if($default){
		        $query = '
			        UPDATE 
			        	`ksl_ab_tests` 
			        SET 
			        	`default`=1
					WHERE
						`id`=' . mysql_escape_string($ab_test_id) . '
				';
		        mysql_query($query);
			}

			$query = 'INSERT INTO `ksl_abtests_blocks` (`id`, `ab_test_id`, `block_id`) VALUES (NULL, ' . $ab_test_id . ', ' . $block_id . ')';
			mysql_query($query);
    	}
    	return true;
    }
    return false;
}

function setDefaultABTest($blockId, $testId){
	if($blockId > 0 && $testId > 0){
		$query = '
			UPDATE 
				`ksl_abtests_blocks` AS `tb`
			LEFT OUTER JOIN
			    `ksl_ab_tests` AS `t` ON `t`.`id`=`tb`.`ab_test_id`
			SET 
				`t`.`default` = 0
			WHERE 
				`tb`.`block_id`=' . mysql_escape_string($blockId) . '
			AND 
				`t`.default = 1
		';
		mysql_query($query);

        $query = '
	        UPDATE 
	        	`ksl_ab_tests` 
	        SET 
	        	`default`=1
			WHERE
				`id`=' . mysql_escape_string($testId) . '
		';
        mysql_query($query);

        return true;
	}
	return false;
}

function updateConnectionsABTest($blockId, $testIds){
	if($blockId > 0 && !empty($testIds)){
        $query = '
	        UPDATE 
	        	`ksl_abtests_blocks` 
	        SET 
	        	`block_id`=' . mysql_escape_string($blockId) . '
			WHERE
				`ab_test_id` IN (' . implode(', ', $testIds) . ')
		';
        mysql_query($query);

        return true;
	}
	return false;
}

function removeABTest(){
	$id = checkVar($_GET['id']);
	if($id > 0){
		$query = '
			DELETE FROM
				`ksl_ab_tests`
			WHERE
				`id`=' . mysql_escape_string($id) . '
		';
		mysql_query($query);
		$query = '
			DELETE FROM
				`ksl_block_display_statistic`
			WHERE
				`abtest_id`=' . mysql_escape_string($id) . '
		';
		mysql_query($query);
	}
	exit();
}

function delete_order() {
    $order_id = checkVar($_GET['order_id']);

    if (!empty($order_id)) {
        $query = '
	        DELETE FROM
	        	`orders`
			WHERE
				`id`=' . mysql_escape_string($order_id) . '
		';
        mysql_query($query);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}