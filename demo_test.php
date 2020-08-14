<?php
$id = $_GET['val'];
function unique_obj($obj) {
    static $idList = array();
    if(empty($obj->id)){
        return true;
    }
    if(in_array($obj->id,$idList)) {
        return false;
    }
    $idList []= $obj->id;
    return true;
}
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
$muviko->setMovie($id);
$movie = $muviko->getMovie(false,true);
$actors = $muviko->getActors($movie=true,$movie_id=$id);

//$actors = $muviko->AllgetActors();
$actors = array_filter($actors,'unique_obj');
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());
 // $mov_id = $db->query("SELECT * FROM movies WHERE ");
	foreach($actors as $actor) {
        if(!empty($actor)){
            if($actor->actor_name!=''){
                $output = '<div class="actor col-md-2">';
                   $style = '';
                   $ext = pathinfo($actor->actor_img_url, PATHINFO_EXTENSION);
                   $picture = 'http://films.hopto.org/uploads/actors/'.$actor->actor_picture;
                   if($actor->actor_picture=='' && $actor->actor_img_url!='' && $ext !=''){
                       $picture = $actor->actor_img_url;
                   }
                   elseif ($actor->actor_picture!='') {
                     $picture = UPLOADS_PATH.'/actors/'.$actor->actor_picture;
                   }
                   else{
                       $picture = UPLOADS_PATH.'/actors/'.$actor->actor_picture;
                         if(!file_exists($picture)){
                       $picture = $muviko->getDomain().'/images/default-user.png';
                   }
                   }
              $output .=   '<a style="color:white;" href="actor_profile.php?name='.$actor->actor_nconst.'">
                     <span class="actor-pro-img"><img src="'.$picture.'"></span>
                     <p class="title">'.$actor->actor_name.'</p>
                   </a></div>';
            echo $output;
          }
        }

    }
?>
