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

$episode_id = $_GET['episode_id'];
$is_embed = $_GET['is_embed'];

$episode = $db->query("SELECT * FROM episodes WHERE id='".$episode_id."' LIMIT 1");
$episode = $episode->fetch_object();
$episode_index = $episode->episode_number;

if($is_embed == 0) {
$series_poster = $db->query("SELECT movie_poster_image FROM movies WHERE id='".$episode->movie_id."'");
$series_poster = $series_poster->fetch_object();

$playlist = $db->query("SELECT * FROM episodes WHERE season_id='".$episode->season_id."' ORDER BY episode_number ASC");

$result = [];
while($item = $playlist->fetch_array()) {
    $result[] = $item;
}

echo json_encode(array('episode_index' => $episode_index, 'playlist' => $result, 'series_poster_image' => $series_poster->movie_poster_image));

} else {
	echo '<iframe width="100%" height="100%" src="'.$episode->episode_source.'" frameborder="0" scrolling="no" allowfullscreen=""></iframe>';
}

