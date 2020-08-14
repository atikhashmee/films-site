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

$page['name'] = $muviko->translate('Change_Password');
$page['footer'] = true;

if(isset($_POST['save'])) {
	$current_password = $_POST['current_password'];
	$new_password = $_POST['new_password'];
	$confirm_password = $_POST['confirm_password'];
	$user = $muviko->getUser($muviko->user_id,false,'id');
	if(!empty($new_password)) {
		if($new_password === $confirm_password) {
			if($muviko->hashPassword($current_password) === $user->password) {
			$db->query("UPDATE users SET password='".$muviko->hashPassword($new_password)."' WHERE id='".$muviko->user_id."'");
			header('Location: settings');
			exit;
			} else {
				$error = "Incorrect Password";
			}
		} else {
			$error = 'Password does not match';
		}
	} else {
		$error = 'Please, enter a new password';
	}
}

include($muviko->getHeaderPath());
include($muviko->getPagePath('password'));
include($muviko->getFooterPath());