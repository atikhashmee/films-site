<?php
session_set_cookie_params(17200);
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
// print_r($genres);
$count = 1;
$output = '';
$loadedItem = 'loaded-'._gRS(6).time();
if($genres->num_rows > 0){
    while($genre = $genres->fetch_object()) {
        $category = $db->query("SELECT * FROM movies WHERE FIND_IN_SET('".$genre->id."', movie_genres) ORDER BY created_date DESC");
      // print_r($category);
        if($category->num_rows >= 1)
        {
          

          $output .= '<div class="home-section" page="1">';
          $output .=$muviko->homeSectionHeading($genre->genre_name,'category/'.$genre->id.'/'.strtolower($genre->genre_name),false);
          $output .= '<div class="movie-slider-1 owl-theme loaded-ajax owl-carousel '.$loadedItem.'" id="'.$loadedItem.'">';
          while($item = $category->fetch_object()) {
            //print_r($item->id);
            //debug(array($genres,$paged,$loadedItem,$genre,$item));
            $movie_rating = isset($item->movie_rating)?$item->movie_rating:'';
           // echo $watch = $muviko->$item->watch;
           $output .= $muviko->newHomeMovieItem($item->id,$item->movie_thumb_image,$item->movie_name,$item->movie_year,$movie_rating,$item->is_series,$item->last_season,$item->watch,$item->movie_year,false);
          }
          $output .= '</div>';
          $output .= '<div class="o-n"><div class="o-prev"><i class="ti-angle-left icon-white"></i></div><div class="o-next"><i class="ti-angle-right icon-white"></i></div></div>';
          $output .= '</div>';
        }
    }
}
echo json_encode(array('geners'=>$genres,'body'=>$output,'page'=>$paged,'items_id'=>$loadedItem));
?>
