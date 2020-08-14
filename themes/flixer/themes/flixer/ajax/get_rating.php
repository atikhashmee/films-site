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

$movie_id = $_GET['movie_id'];

$rating = $db->query("SELECT AVG(rating) as rating FROM ratings WHERE movie_id='".$movie_id."'");

if($rating->num_rows >= 1) {
	$rating = $rating->fetch_object();
	$rating = $rating->rating;
	echo $rating;
} else {
	echo 0;
}