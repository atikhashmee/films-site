<?php
session_set_cookie_params(172800);
session_start();
require('../../../core/config.php');
require('../../../core/system.php');
$core = new Core($db,$domain);
require($core->getExtendPath(true));
$muviko = new Muviko($db,$domain);
$muviko->startUserSession($_SESSION);
$muviko->getLanguage('../../../');
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());

$movie_id = $_GET['movie_id'];
$user_id = $_SESSION['fl_user_id'];

$check = $db->query("SELECT * FROM my_list WHERE movie_id='".$movie_id."' AND user_id='".$user_id."' LIMIT 1");

if($check->num_rows >= 1) {
	$db->query("DELETE FROM my_list WHERE movie_id='".$movie_id."' AND user_id='".$user_id."'");
	echo '<i class="ti-plus"></i> <span>'.$muviko->translate('Add_List').'</span>';
} else { 
	$db->query("INSERT INTO my_list (movie_id,user_id,profile_id,time) VALUES ('".$movie_id."','".$user_id."','".$_SESSION['fl_profile']."','".time()."')");
	echo '<i class="ti-check"></i>  <span>'.$muviko->translate('Added_List').'</span>';
}