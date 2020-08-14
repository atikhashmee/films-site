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
$paged = $_GET['page']+1;
$genres = $muviko->getSections(3,$paged);
$count = 1;
$output = '';
$loadedItem = 'loaded-'._gRS(6).time();
if($genres->num_rows > 0){
    while($genre = $genres->fetch_object()) {
        $category = $db->query("SELECT * FROM movies WHERE FIND_IN_SET('".$genre->id."', movie_genres) ORDER BY id DESC");
        if($category->num_rows >= 1) 
        { 
          $output .= '<div class="home-section">';
          $output .=$muviko->homeSectionHeading($genre->genre_name,'category/'.$genre->id.'/'.strtolower($genre->genre_name),false);
          $output .= '<div class="movie-slider-1 owl-theme loaded-ajax '.$loadedItem.'">';
          while($item = $category->fetch_object()) {
            //debug(array($genres,$paged,$loadedItem,$genre,$item));
            $movie_rating = isset($item->movie_rating)?$item->movie_rating:'';
           $output .= $muviko->newHomeMovieItem($item->id,$item->movie_thumb_image,$item->movie_name,$item->movie_year,$movie_rating,$item->is_series,$item->last_season,false);
          }
          $output .= '</div>';
          $output .= '</div>';
        }
    }
}
echo json_encode(array('geners'=>$genres,'body'=>$output,'page'=>$paged,'items_id'=>$loadedItem));
?>