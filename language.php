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

$page['name'] = $muviko->translate('Change_Language');
$page['footer'] = true;

if(isset($_POST['save'])) {
	$language = $_POST['language'];
	$language = $language[0];
	$db->query("UPDATE profiles SET profile_language='".$language."' WHERE id='".$_SESSION['fl_profile']."'");
	$_SESSION['fl_language'] = $language;
	header('Location: language');
	exit;
}

include($muviko->getHeaderPath());
include($muviko->getPagePath('language'));
include($muviko->getFooterPath());