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
$episodes = $muviko->getEpisodes($default_season->id);
$suggestions = $muviko->getSuggestions($movie->movie_genres);
//echo hash('sha512','admin');
/* echo '<pre>';
print_r($default_season);
echo '</pre>';
echo '<pre>';
print_r($episodes);
echo '</pre>';
echo '<pre>';
print_r($seasons);
echo '</pre>';
echo '<pre>';
print_r($movie);
echo '</pre>'; */
// echo '<pre>';
// print_r($actors);
// echo '</pre>';
$page['name'] = $movie->movie_name;

$page['js'] = '<script> setPlayerSource('.$movie->id.','.$movie->is_series.','.$movie->is_embed.'); </script>';

if($movie->is_series == 1) {

	$page['js'] .= '<script> loadSeason('.$default_season->id.','.$default_season->season_number.'); </script>';
}

include($muviko->getHeaderPath());
include($muviko->getPagePath('video'));
include($muviko->getFooterPath());
