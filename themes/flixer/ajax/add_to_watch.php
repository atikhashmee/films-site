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
$episode_id =$_GET['episode_id'];

$movietypequery = $db->query("SELECT is_series FROM movies WHERE id='" . $movie_id . "' ");

$movietype = $movietypequery->fetch_object();

if($movietype->is_series==1){

	$check = $db->query("SELECT * FROM my_watched_episodes WHERE movie_id='".$movie_id."' AND user_id='".$user_id."' AND episode_id='".$episode_id."' LIMIT 1");
  if($check->num_rows >= 1) {
  	$db->query("DELETE FROM my_watched_episodes WHERE movie_id='".$movie_id."' AND user_id='".$user_id."' AND episode_id='".$episode_id."'");
  	echo '<i class="ti-plus"></i> <span>Mark as Watched</span>';
  } else {
  	$db->query("INSERT INTO my_watched_episodes (movie_id,episode_id,user_id) VALUES ('".$movie_id."','".$episode_id."','".$user_id."')");
  	echo '<i class="ti-check"></i>  <span>Remove Watched</span>';
  }

}else{

 $check = $db->query("SELECT * FROM my_watched WHERE movie_id='".$movie_id."' AND user_id='".$user_id."' LIMIT 1");
 if($check->num_rows >= 1) {
 	$db->query("DELETE FROM my_watched WHERE movie_id='".$movie_id."' AND user_id='".$user_id."'");
 	echo '<i class="ti-plus"></i> <span>Mark as Watched</span>';
 } else {
 	$db->query("INSERT INTO my_watched (movie_id,user_id) VALUES ('".$movie_id."','".$user_id."')");
 	echo '<i class="ti-check"></i>  <span>Remove Watched</span>';
 }

}
