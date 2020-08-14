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

$db->query("DELETE FROM movies WHERE id='".$id."'");
$db->query("DELETE FROM actor_relations WHERE movie_id='".$id."'");
$db->query("DELETE FROM genres_relations WHERE movie_id='".$id."'");
if(isset($_GET['is_film']) && $_GET['is_film'] == "true"){
   header('Location: films.php');
}
else{
    header('Location: videos.php');
}
exit;
