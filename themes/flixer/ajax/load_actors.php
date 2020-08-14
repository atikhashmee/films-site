<?php
session_set_cookie_params(172800);
session_start();
ini_set('display_errors', 1);
require('../../../core/config.php');
require('../../../core/system.php');
$core = new Core($db,$domain);
require($core->getExtendPath(true));
$muviko = new Muviko($db,$domain);
$muviko->startUserSession($_SESSION);
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());
$paged = $_GET['page']+1;
$genres = $muviko->getSections(3,$paged);
$count = 1;
$output = '';
echo "string";
  foreach($actors as $actor) { ?>
              <div class="actor col-md-2">
                <?php
                $picture = UPLOADS_PATH.'/actors/'.$actor->actor_picture;
                $style = '';
                if($actor->actor_picture=='' || !file_exists($picture)){
                    $picture = $muviko->getDomain().'/images/default-user.png';
                }
                ?>
                <a style="color:white;" href="actor_profile.php?name=<?=$actor->actor_nconst?>">
                  <span class="actor-pro-img"><img src="<?=$picture?>"></span>
                  <p class="title"><?=$actor->actor_name?><?php if($actor->actor_name=='') echo 'Unknown';?></p>
                </a>
              </div>
            <?php } 
echo json_encode(array('geners'=>$genres,'body'=>$output));
?>
