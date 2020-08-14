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

$page['name'] = $muviko->translate('Switch_Profile');
$page['footer'] = false;

$profiles = $muviko->getProfiles();

if(isset($_GET['set_profile']) && $_GET['set_profile'] == 'true') {
	$is_kid = $_GET['kid'];
	$profile_id = $_GET['profile_id'];
	$profile_name = $_GET['profile_name'];
	$muviko->setProfile($profile_id,$is_kid);
	header('Location: index.php');
}

include($muviko->getHeaderPath());
include($muviko->getPagePath('select_profile'));
include($muviko->getFooterPath());