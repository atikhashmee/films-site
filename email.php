<?php
session_set_cookie_params(172800);
session_start();
require('core/config.php');
require('core/system.php');
$core = new Core($db,$domain);
require($core->getExtendPath());
$muviko = new Muviko($db,$domain);
$muviko->startUserSession($_SESSION);
$muviko->verifySession(true);
$muviko->getLanguage();
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());

$page['name'] = $muviko->translate('Change_Email');
$page['footer'] = true;

if(isset($_POST['save'])) {
	$new_email = $_POST['new_email'];
	$confirm_email = $_POST['confirm_email'];
	if(!empty($new_email)) {
		if($new_email === $confirm_email) {
			$db->query("UPDATE users SET email='".$new_email."' WHERE id='".$muviko->user_id."'");
			$_SESSION['fl_email'] = $new_email;
				header('Location: settings');
	exit;
		} else {
			$error = 'Email does not match';
		}
	} else {
		$error = 'Please, enter a new email';
	}
}

include($muviko->getHeaderPath());
include($muviko->getPagePath('email'));
include($muviko->getFooterPath());