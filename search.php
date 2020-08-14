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

$page['name'] = $muviko->translate('Search');
$page['footer'] = false;
if(isset($_GET['q'])) {
	if($_GET['q']!='N;'){
	$query = $_GET['q'];
	}
	else {
	header('Location: index.php');
	exit;
}
} else {
	header('Location: index.php');
	exit;
}

$results = $muviko->searchMovie(trim($query));
$results_actor = $muviko->searchActors(trim($query));

include($muviko->getHeaderPath());
include($muviko->getPagePath('search'));
include($muviko->getFooterPath());
