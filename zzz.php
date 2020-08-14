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

$page['name'] = $muviko->settings->website_title;
$page['footer'] = true;

$featured_movie = $muviko->getFeaturedMovie();
$paged = isset($_GET['page'])?$_GET['page']:1;
$genres = $muviko->getSections(3,$paged);
$my_list = $muviko->getMyList();

//$total_Geners = $muviko->get_total_records("SELECT id FROM genres AS genre WHERE EXISTS (SELECT id FROM movies WHERE movie_genres LIKE CONCAT('%', genre.id ,'%'))");

include($muviko->getHeaderPath());
include($muviko->getPagePath('index'));
include($muviko->getFooterPath());