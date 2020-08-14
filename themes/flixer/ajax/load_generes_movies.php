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
$page = ($_GET['page'] > 1)?$_GET['page']:1;
$limit = 8;
$start_from = ($page-1) * $limit;
$PAGE_LIMIT = " LIMIT $start_from,$limit";
$category = $db->query("SELECT * FROM movies WHERE FIND_IN_SET('".$_GET['mi']."', movie_genres) ORDER BY id DESC ".$PAGE_LIMIT."");
$output = '';
while($item = $category->fetch_object()) {
  //debug(array($genres,$paged,$loadedItem,$genre,$item));
  $movie_rating = isset($item->movie_rating)?$item->movie_rating:'';
  $output .= $muviko->newHomeMovieItem($item->id,$item->movie_thumb_image,$item->movie_name,$item->movie_year,$movie_rating,$item->is_series,$item->last_season,false);
}
echo json_encode(array('body'=>$output,'page'=>$page));
?>
