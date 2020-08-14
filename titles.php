<?php
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
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());

switch ($muviko->settings->footer_on_content_optimized_pages) {
	case 1:
		$page['footer'] = true;
		break;
	case 0:
		$page['footer'] = false;
		break;
	default:
		$page['footer'] = false;
		break;
}

$id = $_GET['id'];

$muviko->setMovie($id);
$movie = $muviko->getMovie(false,true);

$actors = $muviko->getActors(true);
$seasons = $muviko->getSeasons();
$default_season = $muviko->getDefaultSeason();
$defaultSeasonId = '';
if(!empty($default_season)){
	$defaultSeasonId = $default_season->id;
}
$episodes = $muviko->getEpisodes();
$suggestions = $muviko->getSuggestions($movie->movie_genres);
$page['name'] = $movie->movie_name;

$page['footer'] = true;
$page['js'] = '<script> setPlayerSource('.$movie->id.','.$movie->is_series.','.$movie->is_embed.'); </script>';
if($movie->is_series == 1) {

	$page['js'] .= '<script> loadSeason('.$default_season->id.','.$default_season->season_number.'); </script>';
}
//$fetchWacthedAllSeason = $db->query("SELECT * FROM all_season_watched WHERE movie_id=$movie->id AND `user_id`={$muviko->user->id}")->fetch_object();
include($muviko->getHeaderPath());
include($muviko->getPagePath('video'));
include($muviko->getFooterPath());
