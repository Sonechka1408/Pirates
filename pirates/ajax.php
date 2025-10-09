<?php define('ROOT', dirname(__FILE__));

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8;');
session_start();
include ROOT . '/includes/config.php';
include ROOT . '/includes/functions.php';
include ROOT . '/includes/init.php';
include ROOT . '/includes/mail.php';
include ROOT . '/admin/includes/lang.php';

$task = checkVar($_GET['task']);
switch ($task) {
	case 'vk_share':



		break;
	case 'save_order':
		$name         = mysql_real_escape_string(checkVar($_GET['name']));
		$email        = mysql_real_escape_string(checkVar($_GET['email']));
		$phone        = mysql_real_escape_string(checkVar($_GET['phone']));
		$note         = mysql_real_escape_string(checkVar($_GET['note']));
		$subscribe_id = (int) checkVar($_GET['subscribe_id']);
		$template_id  = (int) checkVar($_GET['template_id']);
		$referer      = mysql_real_escape_string($_SESSION['referer']);

		if (!empty($subscribe_id) && !empty($template_id)) {
			$query = 'insert into orders (name, email, phone, subscribe_id, template_id, referer, date, `session_id`, `note`) values ("' . $name . '", "' . $email . '", "' . $phone . '", ' . $subscribe_id . ', ' . $template_id . ', "' . $referer . '", NOW(), \'' . session_id() . '\', "' . $note . '")';
			mysql_query($query);
			$order_id        = mysql_insert_id();
			$query           = 'select value from settings where name = "qtsms_host"';
			$res             = mysql_query($query);
			$qtsms_host      = mysql_result($res, 0);
			$query           = 'select value from settings where name = "qtsms_login"';
			$res             = mysql_query($query);
			$qtsms_login     = mysql_result($res, 0);
			$query           = 'select value from settings where name = "qtsms_password"';
			$res             = mysql_query($query);
			$qtsms_password  = mysql_result($res, 0);
			$query           = 'select value from settings where name = "qtsms_phone"';
			$res             = mysql_query($query);
			$qtsms_phone     = mysql_result($res, 0);
			$query           = 'select value from settings where name = "qtsms_sender"';
			$res             = mysql_query($query);
			$qtsms_sender    = mysql_result($res, 0);
			$query           = 'SELECT `title` FROM `subscribes` WHERE `id` = ' . $subscribe_id;
			$res             = mysql_query($query);
			$subscribe_title = mysql_result($res, 0);

			$query          = 'SELECT `title` FROM `templates` WHERE `id` = ' . $template_id;
			$res            = mysql_query($query);
			$template_title = mysql_result($res, 0);

			if (!empty($qtsms_host) && !empty($qtsms_login) && !empty($qtsms_password) && !empty($qtsms_phone) && !empty($qtsms_sender)) {
				include ROOT . '/includes/QTSMS.class.php';

				$sms                       = new QTSMS($qtsms_login, $qtsms_password, $qtsms_host);
				$settings['phone_message'] = str_replace('{order_id}', $order_id, $settings['phone_message']);
				$settings['phone_message'] = str_replace('{name}', $name, $settings['email_message']);
				$settings['phone_message'] = str_replace('{email}', $email, $settings['email_message']);
				$settings['phone_message'] = str_replace('{phone}', $phone, $settings['email_message']);
				$settings['phone_message'] = str_replace('{subscribe_title}', $subscribe_title, $settings['email_message']);
				$settings['phone_message'] = str_replace('{template_title}', $template_title, $settings['email_message']);
				$period                    = 600;

				$result = $sms->post_message($settings['phone_message'], $qtsms_phone, $qtsms_sender, '', $period);
			}

			$query       = 'SELECT `value` FROM `settings` WHERE `name` = \'admin_email\'';
			$res         = mysql_query($query);
			$admin_email = mysql_result($res, 0);

			if (!empty($admin_email)) {
				$settings = array();
				$query    = 'SELECT `name`, `value` FROM `settings` WHERE `name` = \'email_message\' OR `name` = \'email_subject\'';
				$res      = mysql_query($query);
				while ($row = mysql_fetch_assoc($res)) {
					$settings[$row['name']] = $row['value'];
				}

				$settings['email_subject'] = str_replace('{order_id}', $order_id, $settings['email_subject']);
				$settings['email_message'] = str_replace('{name}', $name, $settings['email_message']);
				$settings['email_message'] = str_replace('{email}', $email, $settings['email_message']);
				$settings['email_message'] = str_replace('{phone}', $phone, $settings['email_message']);
				$settings['email_message'] = str_replace('{subscribe_title}', $subscribe_title, $settings['email_message']);
				$settings['email_message'] = str_replace('{template_title}', $template_title, $settings['email_message']);

				$headers = 'From: ' . $admin_email . "\r\n";

				$message = '
                    <html>
                        <head>
                            <title>Новый заказ № ' . $order_id . '</title>
                        </head>
                        <body>
                            <h1>Новый заказ № ' . $order_id . '</h1>
                            <div style="margin-top: 15px;">
                                <p>Имя: ' . $name . '</p>
                                <p>E-Mail: ' . $email . '</p>
                                <p>Телефон: ' . $phone . '</p>
								' . (!empty($note) ? '<p>Заметка: ' . $note . '</p>' : '') . '
                                <hr />
                                <p>Подписка: ' . $subscribe_title . '</p>
                                <p>Шаблон: ' . $template_title . '</p>
                            </div>
                        </body>
                    </html>
                ';

				$mail   = new KSLandingMail;
				$result = $mail
					->set('to', $admin_email)
					->set('subject', $settings['email_subject'])
					->set('message', $message)
					->setHeaders($headers)
					->send();

				if ($email) {
					$message = '
                        <html>
                            <head>
                                <title>Новый заказ № ' . $order_id . '</title>
                            </head>
                            <body>
                                <h1>Новый заказ № ' . $order_id . '</h1>
                                <div style="margin-top: 15px;">
                                    ' . $settings['email_message'] . '
                                </div>
                            </body>
                        </html>
                    ';
					$headers = 'From: ' . $email . "\r\n";
					$mail    = new KSLandingMail;
					$result  = $mail
						->set('to', $email)
						->set('subject', $settings['email_subject'])
						->set('message', $message)
						->setHeaders($headers)
						->send();
				}
			}

			$query           = 'select value from settings where name = "bitrix_login"';
			$res             = mysql_query($query);
			$bitrix_login    = mysql_result($res, 0);
			$query           = 'select value from settings where name = "bitrix_password"';
			$res             = mysql_query($query);
			$bitrix_password = mysql_result($res, 0);
			$query           = 'select value from settings where name = "bitrix_url"';
			$res             = mysql_query($query);
			$bitrix_url      = mysql_result($res, 0);
			if ($bitrix_login && $bitrix_password) {
				$comments = '';
				$referer  = json_decode($_SESSION['referer'], true);
				foreach ($referer as $key => $val) {
					$comments .= $lang['referer_info_' . $key] . ': ' . $val . '<br>';
				}

				$vars     = array(
					'LOGIN'      => $bitrix_login,
					'PASSWORD'   => $bitrix_password,
					'TITLE'      => 'Новая заявка № ' . $order_id,
					'SOURCE_ID'  => 'WEB',
					'NAME'       => $name,
					'EMAIL_HOME' => $email,
					'PHONE_HOME' => $phone,
					'COMMENTS'   => $comments
				);
				$vars     = http_build_query($vars);
				$context  = array(
					'http' => array(
						'method'  => 'POST',
						'header'  => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
						'content' => $vars,
					),
				);
				$context  = stream_context_create($context);
				$responce = file_get_contents($bitrix_url . '/crm/configs/import/lead.php', false, $context);
			}
		}
		break;
	case
	'server_time':
		$_SESSION['client_hours'] = checkVar($_GET['client_hours']);
		break;
	case 'fix_share':
		$share_id                           = checkVar($_GET['share_id']);
		$_SESSION['fix_share_' . $share_id] = time();
		break;
	case 'save_review':
		$review             = array();
		$review['username'] = checkVar($_POST['username']);
		$review['content']  = checkVar($_POST['content']);
		$review['phone']    = checkVar($_POST['phone']);
		$review['email']    = checkVar($_POST['email']);

		if (!empty($review['username']) && !empty($review['email'])) {
			$query = 'update ksl_reviews set ordering=ordering+1';
			mysql_query($query);
			$query = '
                INSERT INTO 
                    `ksl_reviews` (
                        `title`,
						`avatar`,
                        `username`,
						`introcontent`,
                        `content`,
						`about`,
						`prehistory`,
                        `email`,
                        `phone`,
						`ordering`,
                        `state`
                    ) 
                VALUES (
                    \'' . mysql_real_escape_string(checkVar($review['username'])) . '\',
					\'\',
                    \'' . mysql_real_escape_string(checkVar($review['username'])) . '\',
					\'\',
                    \'' . mysql_real_escape_string(checkVar($review['content'])) . '\',
					\'\',
					\'\',
                    \'' . mysql_real_escape_string(checkVar($review['email'])) . '\',
                    \'' . mysql_real_escape_string(checkVar($review['phone'])) . '\',
					0,
                    0
                )
            ';
			mysql_query($query);
			$review_id = mysql_insert_id();

			$query       = 'SELECT `value` FROM `settings` WHERE `name` = \'admin_email\'';
			$res         = mysql_query($query);
			$admin_email = mysql_result($res, 0);
			if (!empty($admin_email)) {
				$headers = 'From: ' . $admin_email . "\r\n";

				$message = '
                    <html>
                        <head>
                            <title>Новый отзыв № ' . $review_id . '</title>
                        </head>
                        <body>
                            <h1>Новый отзыв № ' . $review_id . '</h1>
                            <div style="margin-top: 15px;">
                                <p>Имя: ' . $review['username'] . '</p>
                                <p>Отзыв: ' . $review['content'] . '</p>
                            </div>
                        </body>
                    </html>
                ';

				$mail   = new KSLandingMail;
				$result = $mail
					->set('to', $admin_email)
					->set('subject', 'Новый отзыв')
					->set('message', $message)
					->setHeaders($headers)
					->send();
			}
		}
		break;
}
