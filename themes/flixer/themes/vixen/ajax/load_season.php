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

$episodes = $db->query("SELECT * FROM episodes WHERE season_id='".$season_id."' ORDER by episode_number ASC");
while($episode = $episodes->fetch_object()) {
echo 
'
<li onclick="loadEpisode('.$episode->id.','.$episode->is_embed.'); return false;"> 
<a href="#"> '.$episode->episode_number.'. '.$episode->episode_name.' </a>
</li>
';
}