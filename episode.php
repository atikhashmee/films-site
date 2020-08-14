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
$muviko->setEpisode($id);
$movie = $muviko->getMovie1(false,true);
$actors = $muviko->getActors1($movie->actor_id);
$DSID = '';
if(isset($default_season->id)){
	$DSID = $default_season->id;
}
$episodes = $muviko->getEpisodes($DSID);
//$suggestions = $muviko->getSuggestions($movie->movie_genres);
$page['name'] = $movie->episode_name;

		$a = $movie->episode_source;


if (strpos($a, 'drive.google.com') !== false) {

	$page['js'] = '<script> setPlayerSource1('.$movie->id.',1); </script>';
	}
	else {
	$page['js'] = '<script> setPlayerSource1('.$movie->id.','.$movie->is_embed.'); </script>';
	}
	$page['footer'] = true;


include($muviko->getHeaderPath());
include($muviko->getPagePath('episode'));
include($muviko->getFooterPath());
