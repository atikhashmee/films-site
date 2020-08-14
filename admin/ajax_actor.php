<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);

 $search = (isset($_GET["keyword"]))?$_GET["keyword"]:'';
 $id = (isset($_GET["mid"]))?$_GET["mid"]:'';

if(!empty($search) && !empty($id)){
   
   $actors13 = $db->query("SELECT * FROM actor_relations WHERE movie_id=$id ");
    while($actor13= $actors13->fetch_object()){
          $TT11[]=$actor13->actor_id;
    }
  
    $yy=implode(',',$TT11);
    if(empty($yy)){
      $yy= 0; 
    }
    
      $actors14 = $db->query("SELECT * FROM actors WHERE id NOT IN ($yy) AND (actor_name like '$search%' OR imdbid like '$search%')");
    while($actorsData= $actors14->fetch_object()){
        $actorArray[]=$actorsData;
    }
       echo json_encode($actorArray);
}
 


?>