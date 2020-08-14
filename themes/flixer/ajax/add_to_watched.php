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

$episode_id = $_GET['movie_id'];
$user_id = $_SESSION['fl_user_id'];
$episode_no =$_GET['ep_no'];

$episode = $db->query("SELECT * FROM episodes WHERE id='".$episode_id."'");
$episode = $episode->fetch_object();
$movie_id = $episode->movie_id;
$season_id = $episode->season_id;
$movietypequery = $db->query("SELECT is_series FROM movies WHERE id='" . $movie_id . "' ");
$movietype = $movietypequery->fetch_object();

if($movietype->is_series==1){

	$check = $db->query("SELECT * FROM my_watched_episodes WHERE movie_id='".$movie_id."' AND user_id='".$user_id."' AND episode_id='".$episode_id."' AND season_id='".$season_id."' LIMIT 1");
 
	if($check->num_rows >= 1) {
  	$db->query("DELETE FROM my_watched_episodes WHERE movie_id='".$movie_id."' AND user_id='".$user_id."' AND episode_id='".$episode_id."' AND season_id='".$season_id."'");
  	echo '<i class="ti-plus"></i> <span>Mark as Watched</span>';
  } else {
  	$db->query("INSERT INTO my_watched_episodes (movie_id,episode_id,user_id,season_id) VALUES ('".$movie_id."','".$episode_id."','".$user_id."','".$season_id."' )");
  	echo '<i class="ti-check"></i>  <span>Remove Watched</span>';
  }

	$watchquery =  $db->query("select count(id) as watchedep from my_watched_episodes where movie_id='".$movie_id."' AND user_id='".$user_id."' AND episode_id!='' group by movie_id,user_id");
    $watchedep = $watchquery->fetch_object();
	
	$totquery =  $db->query("select count(id) as totep from episodes where movie_id='".$movie_id."'  group by movie_id");
	$totep = $totquery->fetch_object();
	
	if($totep->totep == $watchedep->watchedep){
		$db->query("INSERT INTO my_watched (movie_id,user_id) VALUES ('".$movie_id."','".$user_id."')");
	}else{
		$db->query("DELETE FROM my_watched WHERE movie_id='".$movie_id."' AND user_id='".$user_id."'");
	}

}
// else{
//
//  $check = $db->query("SELECT * FROM my_watched WHERE movie_id='".$movie_id."' AND user_id='".$user_id."' LIMIT 1");
//  if($check->num_rows >= 1) {
//  	$db->query("DELETE FROM my_watched WHERE movie_id='".$movie_id."' AND user_id='".$user_id."'");
//  	echo '<i class="ti-plus"></i> <span>Mark as Watched</span>';
//  } else {
//  	$db->query("INSERT INTO my_watched (movie_id,user_id) VALUES ('".$movie_id."','".$user_id."')");
//  	echo '<i class="ti-check"></i>  <span>Remove Watched</span>';
//  }
//
// }

//
// if(isset($_GET['episode']) && $_GET['episode'] == "episode") {
// 	$check = $db->query("SELECT * FROM my_watched WHERE movie_id='".$movie_id."' AND user_id='".$user_id."' AND `type`='episode' LIMIT 1");
//
// 	if($check->num_rows <= 0) {
// 		$db->query("INSERT INTO my_watched (movie_id,user_id,type) VALUES ('".$movie_id."','".$user_id."','episode')");
// 		echo '<i class="ti-check"></i>  <span>Remove Watched</span>';
// 	}
// 	else {
// 		$db->query("DELETE FROM my_watched WHERE movie_id='".$movie_id."' AND user_id='".$user_id."'  AND `type`='episode' ");
// 		echo '<i class="ti-plus"></i>  <span> Mark as Watched </span>';
// 	}
//
// }
// else {
// 	$check = $db->query("SELECT * FROM my_watched WHERE movie_id='".$movie_id."' AND user_id='".$user_id."' LIMIT 1");
//
// 	if($check->num_rows <= 0) {
// 		$db->query("INSERT INTO my_watched (movie_id,user_id) VALUES ('".$movie_id."','".$user_id."')");
// 		echo '<i class="ti-check"></i>  <span>Remove Watched</span>';
// 	}
// 	else {
// 		$db->query("DELETE FROM my_watched WHERE movie_id='".$movie_id."' AND user_id='".$user_id."'");
// 		echo '<i class="ti-plus"></i>  <span> Mark as Watched </span>';
// 	}
// }
