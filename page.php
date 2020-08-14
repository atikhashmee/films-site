<?php
session_set_cookie_params(172800);
session_start();
require('core/config.php');
require('core/system.php');
$core = new Core($db,$domain);
require($core->getExtendPath());
$muviko = new Muviko($db,$domain);
$muviko->startUserSession($_SESSION);
$muviko->verifySession(false);
$muviko->getLanguage();
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());

$page['footer'] = true;

$id = $_GET['id'];

$custom_page = $muviko->getPage($id);

$page['name'] = $custom_page->page_name;

include($muviko->getHeaderPath());
include($muviko->getPagePath('page'));
include($muviko->getFooterPath());