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

$episodes = $db->query("SELECT * FROM episodes WHERE season_id='".$season_id."' ORDER by id ASC");
$count = 1;
while($episode = $episodes->fetch_object()) {
echo 
'
<div class="episode col-md-4">
<a style="cursor:pointer;" onclick="loadEpisodepage('.$episode->id.'); return false;">
	<span><img src="'.UPLOADS_PATH.'/episodes/'.$episode->episode_thumbnail.'"></span>
<p class="title" style="color:white;"> '.$count.'. '.$episode->episode_name.' </p></a>
<p class="description"> '.substr($episode->episode_description,0,100).' </p>
</div>
';
$count++;
}
?>

<script>

function loadEpisodepage(episodeid) {
	location.href = '<?php echo UPLOADS_PATH;?>/../episode/'+episodeid;
}

</script>