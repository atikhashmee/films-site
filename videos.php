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

$page['name'] = $muviko->translate('Videos');
$page['footer'] = true;

if(!isset($_GET['filter'])) {
	$filter = 'ORDER BY id DESC';
	$option = 'newest';
} else {
	if($_GET['filter'] == 'newest') {
		$filter = 'ORDER BY id DESC';
		$option = 'newest';
	} elseif($_GET['filter'] == 'oldest') {
		$filter = 'ORDER BY id ASC';
		$option = 'oldest';
	} elseif($_GET['filter'] == 'random') {
		$filter = 'ORDER BY RAND()';
		$option = 'random';
	} else {
		$filter = 'ORDER BY id DESC';
		$option = 'newest';
	}
}
$paged = isset($_GET['page'])?$_GET['page']:1;
$limit = 18;
$start_from = ($paged-1) * $limit;
$PAGE_LIMIT = " LIMIT $start_from,$limit";
$total_records = $muviko->get_total_records("SELECT * FROM movies {$filter}");
$movies = $db->query("SELECT * FROM movies {$filter} {$PAGE_LIMIT}");

include($muviko->getHeaderPath());
require($muviko->getPagePath('videos'));
include($muviko->getFooterPath());