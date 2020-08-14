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

$page['name'] = $muviko->translate('Edit_Profile');
$page['footer'] = false;

$id = $_GET['id'];

$profile = $muviko->getProfile($id);

if(isset($_POST['save'])) {
	$profile_name = $_POST['profile_name'];
	$profile_language = $_POST['profile_language'];
	$is_kid = $_POST['is_kid'];
	if(!empty($profile_name) && !empty($profile_language)) {
		$db->query("UPDATE profiles SET profile_name='".$profile_name."',profile_language='".strtolower($profile_language)."',is_kid='".$is_kid."' WHERE id='".$id."'");
		header('Location: manage_profiles.php');
		exit;
	} else {
		$error = 'Please, fill all fields';
	}
}
include($muviko->getHeaderPath());
include($muviko->getPagePath('edit_profile'));
include($muviko->getFooterPath());