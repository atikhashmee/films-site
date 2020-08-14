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

$page['name'] = $muviko->translate('Change_Phone');
$page['footer'] = true;

if(isset($_POST['save'])) {
	$phone = $_POST['phone'];
	$phone_country_code = $_POST['phone_country_code'];
	$confirm_password = $_POST['confirm_password'];
	if(!empty($phone)) {
		if($muviko->hashPassword($confirm_password) === $muviko->user->password) {
			$db->query("UPDATE users SET phone='".str_replace(' ', '', $phone)."',phone_country_code='".$phone_country_code."' WHERE id='".$muviko->user->id."'");
			$_SESSION['fl_phone'] = $phone;
			header('Location: '.$muviko->getDomain().'/settings');
			exit;
		} else {
			$error = 'Incorrect Password';
		}
	} else {
		$error = 'Please, enter a new phone number';
	}
}

include($muviko->getHeaderPath());
include($muviko->getPagePath('phone'));
include($muviko->getFooterPath());