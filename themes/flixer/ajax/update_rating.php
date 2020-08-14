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
$user_id = $_SESSION['fl_user_id'];
$rating = $_GET['rating'];

$check = $db->query("SELECT * FROM ratings WHERE movie_id='".$movie_id."' AND user_id='".$user_id."' LIMIT 1");

if($check->num_rows >= 1) {
	$db->query("UPDATE ratings SET rating='".$rating."' WHERE movie_id='".$movie_id."' AND user_id='".$user_id."'");
} else { 
	$db->query("INSERT INTO ratings (movie_id,user_id,rating) VALUES ('".$movie_id."','".$user_id."','".$rating."')");
}