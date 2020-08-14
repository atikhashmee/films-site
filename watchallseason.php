<?php
 $mV = $_POST['mV'];
 $user = $_POST['user'];

 session_set_cookie_params(172800);
 session_start();
 require('core/config.php');
 require('core/system.php');
//  $core = new Core($db,$domain);
//  require($core->getExtendPath());

$seasons = $db->query("SELECT * FROM seasons WHERE movie_id='".$mV."'");

while($season = $seasons->fetch_object()) {
  
    $season_id = $season->id;
    $season_no = $season->season_number;

    $episodes = $db->query("SELECT * FROM episodes WHERE season_id='".$season_id."'");

    while($episode = $episodes->fetch_object()) {
      
        $ep_id= $episode->id;
        $check =  $db->query("SELECT * FROM my_watched_episodes WHERE movie_id='".$mV."' AND episode_id='".$ep_id."' AND user_id='".$user."'");
        if($check->num_rows > 0){
            echo "update";
        }else{
            $db->query("INSERT INTO `my_watched_episodes`(`movie_id`, `episode_id`, `user_id`) VALUES ($mV,$ep_id,$user)");
        }
    }

}
$db->query("INSERT INTO all_season_watched(`user_id`,`movie_id`) VALUES($user,$mV)");
$checkS =  $db->query("SELECT * FROM my_watched WHERE movie_id='".$mV."'");
        if($checkS->num_rows > 0){
            echo "update";
        }else{
            $db->query("INSERT INTO `my_watched`(`movie_id`, `user_id`) VALUES ($mV,$user)");
        }   
?>
