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

$id = $_GET['id'];
$is_series = $_GET['is_series'];
$is_embed = $_GET['is_embed'];

if($is_series == 0) {
	$results = array();
	$result = $db->query("SELECT * FROM movies WHERE id='".$id."' LIMIT 1");
	$results[] = $result->fetch_array();
	if($is_embed == 0) {
	echo json_encode($results);
	} else {
	 echo '<iframe width="100%" height="100%" src="'.$results[0]['movie_source'].'" frameborder="0" scrolling="no" allowfullscreen=""></iframe>';
	}
} else {
	$season_id = $db->query("SELECT * FROM seasons WHERE movie_id='".$id."' ORDER BY id ASC LIMIT 1");
	$season_id = $season_id->fetch_object();
	$season_id = $season_id->id;
	$episode = $db->query("SELECT * FROM episodes WHERE season_id='".$season_id."' ORDER BY id ASC LIMIT 1");
	$episode = $episode->fetch_object();
	$series_poster = $db->query("SELECT movie_poster_image FROM movies WHERE id='".$id."'");
	$series_poster = $series_poster->fetch_object();
	if($is_embed == 0) {
	$episode_index = $episode->episode_number;
	$playlist = $db->query("SELECT * FROM episodes WHERE season_id='".$season_id."' ORDER BY episode_number ASC");
	$result = [];
	while($item = $playlist->fetch_array()) {
		$result[] = $item;
	}
	echo json_encode(array('episode_index' => $episode_index, 'playlist' => $result, 'series_poster_image' => $series_poster->movie_poster_image));
	} else {
		echo '<iframe width="100%" height="100%" src="'.$episode->episode_source.'" frameborder="0" scrolling="no" allowfullscreen=""></iframe>';
	}
}
