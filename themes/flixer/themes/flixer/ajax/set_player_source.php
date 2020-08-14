<?php
session_set_cookie_params(172800);
session_start();
require('../../../core/config.php');
require('../../../core/system.php');
$core = new Core($db,$domain);
require($core->getExtendPath(true));
$muviko = new Muviko($db,$domain);
$muviko->startUserSession($_SESSION);
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());

$id = $_GET['id'];
$is_series = $_GET['is_series'];
$is_embed = $_GET['is_embed'];
$results = array();
	$result = $db->query("SELECT * FROM movies WHERE id='".$id."' LIMIT 1");
	$results[] = $result->fetch_array();
	if($is_embed == 0) {
	echo json_encode($results);
	} else {
	 echo '<iframe width="100%" height="100%" src="'.$results[0]['movie_source'].'" frameborder="0" scrolling="no" allowfullscreen=""></iframe>';
	}