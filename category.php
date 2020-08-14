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
$genre_id = $_GET['id'];
$genrename = $db->query("SELECT genre_name FROM genres WHERE id={$genre_id}");
$Gn = $genrename->fetch_object();

$genre_name = $Gn->genre_name;//$_GET['genre_name'];

$page['name'] = ucwords($genre_name,'-');
$page['footer'] = true;

if(!isset($_GET['filter'])) {
	$filter = 'ORDER BY gr.movie_id DESC';
	$option = 'oldest';
} else {
	if($_GET['filter'] == 'oldest') {
		$filter = 'ORDER BY gr.movie_id DESC';
		$option = 'oldest';
	} elseif($_GET['filter'] == 'newest') {
		$filter = 'ORDER BY gr.movie_id ASC';
		$option = 'newest';
	} elseif($_GET['filter'] == 'random') {
		$filter = 'ORDER BY RAND()';
		$option = 'random';
	} else {
		$filter = 'ORDER BY gr.movie_id DESC';
		$option = 'oldest';
	}
}

 $paged = isset($_GET['page'])?$_GET['page']:1;
$limit = 18;
$start_from = ($paged-1) * $limit;
$PAGE_LIMIT = " LIMIT $start_from,$limit";
$total_movies = $muviko->get_total_records("SELECT * FROM genres_relations as gr WHERE gr.genres_id= '{$genre_id}' {$filter} ");
// $total_movies = $muviko->get_total_records("SELECT * FROM movies WHERE movie_genres LIKE '%{$genre_id}%' OR movie_genres LIKE '%{$genre_id}' OR movie_genres LIKE '{$genre_id}%' {$filter} ");
// $movies = $db->query("SELECT * FROM movies WHERE movie_genres LIKE '%{$genre_id}%' OR movie_genres LIKE '%{$genre_id}' OR movie_genres LIKE '{$genre_id}%' {$filter} $PAGE_LIMIT");
// $movies_ = $db->query("SELECT * FROM movies WHERE movie_genres ");
//
// foreach ($movies_ as $movieid) {
// 	if(strpos($movieid['movie_genres'],',') !== false){
//
// 		$d =  explode(',',$movieid['movie_genres']);
// 	   foreach ($d as $Df) {
// 			  $id['id'][] = $Df;
// 			  $id['movie_id'][] = $movieid['id'];
// 			  $Mi = $movieid['id'];
// 				$db->query("insert into genres_relations(movie_id,genres_id) values($Mi,$Df)");
//      }
// 		  }else{
// 		    $id['id'][] =  $movieid['movie_genres'];
// 				$id['movie_id'][] = $movieid['id'];
// 			$Mg =	$movieid['movie_genres'];
// 				$Mi = 	$movieid['id'];
// 				$db->query("insert into genres_relations(movie_id,genres_id) values($Mi,$Mg)");
// 			}
// }

//echo implode('',$id);
// echo "<pre>";
// print_r($id);
// echo "<pre>";
//echo "SELECT * FROM movies WHERE movie_genres LIKE '%{$genre_id}%' OR movie_genres LIKE '%{$genre_id}' OR movie_genres LIKE '{$genre_id}%' {$filter} $PAGE_LIMIT";

$genre_movies = $db->query("SELECT * FROM genres_relations as gr LEFT JOIN movies as m ON (m.id=gr.movie_id) WHERE gr.genres_id = '{$genre_id}' {$filter} $PAGE_LIMIT");
//print_r($genres_r);
include($muviko->getHeaderPath());
include($muviko->getPagePath('category'));
include($muviko->getFooterPath());
