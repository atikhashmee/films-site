<?php
session_set_cookie_params(172800);
session_start();
include "core/config.php";
include "core/system.php";
$core = new Core($db,$domain);
// require($core->getExtendPath(true));
//$muviko = new Muviko($db,$domain);
//$muviko->startUserSession($_SESSION);
// define('THEME_PATH', $core->getThemePath());
// define('UPLOADS_PATH', $core->getUploadsPath());

$id = $_GET['id'];
$is_series = $_GET['is_series'];
$is_embed = $_GET['is_embed'];
$results = array();
$result = $db->query("SELECT * FROM movies WHERE id='".$id."' LIMIT 1");
$results[] = $result->fetch_array();
//echo $results[0]['movie_source'].'<br/>';
	// if($is_embed == 0) {
	// echo json_encode($results);
	// } else {
	// 	echo $results[0]['movie_source'].'<br/>';
header('Content-Type:text/html');
	// echo '<iframe id="frameId" width="100%" height="100%" src="'.$results[0]['movie_source'].'" frameborder="0" scrolling="no" allowfullscreen=""></iframe>';
	//}
header("referer:https://drive.google.com");
header('Content-Length: ' . filesize(file_get_contents($results[0]['movie_source'])));
//$content =  file_get_contents($results[0]['movie_source']);
/*$process = curl_init($results[0]['movie_source']); 
curl_setopt($process, CURLOPT_HEADER, 0); 
curl_setopt($process, CURLOPT_REFERER, 'https://drive.google.com');
curl_setopt($process, CURLOPT_TIMEOUT, 30); 
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
$content = curl_exec($process); 
curl_close($process); 
$content = str_replace('/static/file/client/css', 'https://drive.google.com/static/file/client/css', $content);
$content = str_replace('/static/file/client/js', 'https://drive.google.com/static/file/client/js', $content);
$content = str_replace('get_video_info?docid=', 'https://drive.google.com/get_video_info?docid=', $content);
$content = preg_replace("/<\\/?meta(.|\\s)*?>/", '', $content);*/
ob_start();
echo '<iframe id="frameId" width="100%" height="100%" src="'.$results[0]['movie_source'].'" frameborder="0" scrolling="no" allowfullscreen=""></iframe>';//file_get_contents($results[0]['movie_source']);
/*$content  =  ob_get_clean();
$content = str_replace('/static/file/client/css', 'https://drive.google.com/static/file/client/css', $content);
$content = str_replace('/static/file/client/js', 'https://drive.google.com/static/file/client/js', $content);
$content = str_replace('get_video_info?docid=', 'https://drive.google.com/get_video_info?docid=', $content);
$content = preg_replace("/<\\/?meta(.|\\s)*?>/", '', $content);*/
echo $content;
	?>