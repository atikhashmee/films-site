<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);

$id = $_GET['id'];
$season_id = $_GET['season_id'];
$rid=$_GET['rid'];
if(isset($_GET['season']) AND $_GET['season']=='true'){
  $db->query("DELETE FROM episodes WHERE season_id='".$season_id."'");
	$db->query("DELETE FROM seasons WHERE id='".$season_id."'");

  $db->query("DELETE FROM my_watched_episodes WHERE season_id='".$season_id."'");

	$Mcount = $db->query("SELECT count(id) as count FROM seasons WHERE movie_id='".$rid."'");
  $d= $Mcount->fetch_object();
  if($d->count==0){
    $db->query("DELETE FROM my_watched WHERE movie_id='".$rid."'");
    
  }

 

  header('Location: season.php?id='.$rid);
}else{
$episodes = $db->query("SELECT * FROM episodes WHERE season_id='".$season_id."' ");
if($episodes->num_rows > 1) {

 	$db->query("DELETE FROM episodes WHERE id='".$id."'");
  $db->query("DELETE FROM my_watched_episodes WHERE episode_id='".$id."'");

} else {
  $db->query("DELETE FROM my_watched_episodes WHERE episode_id='".$id."'");
	$db->query("DELETE FROM episodes WHERE id='".$id."'");
	$db->query("DELETE FROM seasons WHERE id='".$season_id."'");
}

$Mcount = $db->query("SELECT count(id) as count FROM seasons WHERE movie_id='".$rid."'");
  $d= $Mcount->fetch_object();
  if($d->count==0){
    $db->query("DELETE FROM my_watched WHERE movie_id='".$rid."'");
    
  }

   header('Location: season.php?id='.$rid); 
}
exit;
