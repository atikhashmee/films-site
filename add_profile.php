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

$page['name'] = $muviko->translate('Add_Profile');
$page['footer'] = false;

$profile_avatar = rand(1,9);

if(isset($_POST['continue'])) {
	if(isset($_POST['is_kid'][0])) {
		$is_kid = 1;
	} else{
		$is_kid = 0;
	}
	$profile_name = $_POST['profile_name'];
	$profile_language = $_POST['profile_language'];
	$profile_avatar = $_POST['profile_avatar'];
	$db->query("INSERT INTO profiles (user_id,profile_name,profile_language,profile_avatar,is_kid) VALUES ('".$_SESSION['fl_user_id']."','".$profile_name."','".strtolower($profile_language)."','".$profile_avatar."','".$is_kid."')");
	header('Location: manage_profiles.php');
	exit;
}

include($muviko->getHeaderPath());
include($muviko->getPagePath('add_profile'));
include($muviko->getFooterPath());