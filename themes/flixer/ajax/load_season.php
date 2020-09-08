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
$season_id = $_GET['season_id'];
$sesion_sql = "SELECT season_number, movie_id, A.session_img FROM seasons 
left Join (SELECT id, movie_poster_image as session_img FROM movies ) as A ON seasons.movie_id = A.id 
 WHERE seasons.id='".$season_id."'";
$seasons = $db->query($sesion_sql);
$season = $seasons->fetch_object();

if($season->season_number < 10)
{ 
	$sN = 'Season  0'.$season->season_number;
}
else
{ 
	$sN = 'Season '.$season->season_number;
}
$episodes = $db->query("SELECT * FROM episodes WHERE season_id='".$season_id."' ORDER by id ASC");
$count = 1;
while($episode = $episodes->fetch_object()) {
	//echo strpos($episode->episode_thumbnail,"maxresdefault-1.jpg");
	$imgtype  = @exif_imagetype($episode->episode_thumbnail);
	if ($imgtype === false) 
	{
		$eI = UPLOADS_PATH.'/poster_images/'.$season->session_img ;
	}
	else
	{

		if(strpos($episode->episode_thumbnail,"maxresdefault-1.jpg") !== false){
			 $eI = getposterImg($episode->movie_id);
		}else{
			 $eI = UPLOADS_PATH.'/episodes/'.$episode->episode_thumbnail ;
		}
	}
$check = $db->query("SELECT * FROM my_watched_episodes WHERE episode_id='".$episode->id."' AND user_id='".$muviko->user->id."'");
echo
'
<div class="episode col-md-4">';
echo '<a style="cursor:pointer;" onclick="loadEpisodepage('.$episode->id.'); return false;">
	<span><img src="'.$eI.'"></span>';
	if($check->num_rows!=0){
			//echo '<a style="color:red;">watch</a>';
		echo '<div class="ribbon ribbon-top-left"><span>watched</span></div>';
	}
	if($episode->episode_name!=''){
		$epName = $episode->episode_name;
	}
	$newEpisode_name = 'Episode '.$episode->season_sub_id;
	/*  if($count < 10)
	 {
	     $newEpisode_name = 'Episode 0'.$count; 
	     
	 }
	 else
	 {
	     $newEpisode_name='Episode '.$count;
	     
	 } */

echo '<p class="title" title="'.$episode->episode_name.'" style="color:white;">  '.$sN.' | '.$newEpisode_name.' <br>'.$epName.' </p></a>
<p class="description"> '.substr($episode->episode_description,0,100).' </p>
</div>
';
$count++;
}
?>
<script>
function loadEpisodepage(episodeid) {
	location.href = '<?php echo UPLOADS_PATH;?>/../episode.php?id='+episodeid;
}
</script>
