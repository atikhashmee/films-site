<?php
require('core/config.php');
//require('admin/class_IMDb.php');
$movieQuery = "select * from movies WHERE id BETWEEN 776 AND 853";// Limit 488,645//494
//$movieQuery = "select * from actors WHERE id BETWEEN 19476 AND 19496";//  
$movies = $db->query($movieQuery);
while($Single_movie = $movies->fetch_object()){
    //$imdb = new IMDb(false);
    //$imdb->summary=false;
    //$movie = $imdb->find_by_id(trim($Single_movie->imdbid));
   // debug($movie);
    /*
    $im_url = $movie->image->url;
    $img = file_get_contents($im_url);
    $exploded = explode('.',$im_url);
    $extension = end($exploded);
    $extension = strtolower($extension);*/
    $new_file_name_2 = '';
    $movie = run_curl($Single_movie->imdbid)->movie_results[0];//->person_results[0]//->actor_nconst
    if($movie->poster_path != ''){
      $im_url = "https://image.tmdb.org/t/p/original".$movie->poster_path;//profile_path
      $img = file_get_contents($im_url);
      $exploded = explode('.',$im_url);
      $extension = end($exploded);
      $extension = strtolower($extension);
      $new_file_name_2 = generate_postname($movie->title).'.'.$extension;//->name//
      file_put_contents('uploads/poster_images/'.$new_file_name_2, $img);
      resize($img,$new_file_name_2,'uploads/masonry_images/');//uploads/actors/
    }
    $db->query("UPDATE movies SET movie_poster_image='$new_file_name_2',movie_thumb_image='$new_file_name_2' WHERE imdbid='$Single_movie->imdbid'");
    //$db->query("UPDATE actors SET actor_picture='$new_file_name_2' WHERE actor_nconst='$Single_movie->actor_nconst'");
    debug($movie);
    debug($im_url);
    debug($new_file_name_2);
    debug($Single_movie);
}
function resize($fileName,$fileRealName,$uploadPath='uploads/actors/',$quality = 20){
  $image = imagecreatefromstring($fileName);
  $path = $uploadPath.$fileRealName;
  //$tmp = imagecreatetruecolor($width, $height);
  //imagecopyresampled($tmp, $image,0, 0,$x, 0,$width, $height,$w, $h);
  imagejpeg($image, $path, $quality);
    
  return $path;
  imagedestroy($image);
  imagedestroy($tmp);
  
}
function debug($value){
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}
function generate_postname($input, $replace = '-', $remove_words = true, $words_array = array()) {
	$return = trim(preg_replace('/ \+/', ' ', preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($input))));
	if($remove_words) { $return = remove_words($return, $replace, $words_array); }
	return str_replace(' ', $replace, $return);
}
function remove_words($input,$replace,$words_array = array(),$unique_words = true){
	$input_array = explode(' ',$input);
	$return = array();
	foreach($input_array as $word)
	{
		if(!in_array($word,$words_array) && ($unique_words ? !in_array($word,$return) : true))
		{
			$return[] = $word;
		}
	}
	return implode($replace,$return);
}
function run_curl($imdbId){
    $url = "https://api.themoviedb.org/3/find/$imdbId?api_key=bc457c6e89c45bbb2f34a7bdd23688cf&external_source=imdb_id";
    /*$ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $output =curl_exec($ch);
    curl_close($ch);
    debug($url);
    debug($output);*/
    $output = @file_get_contents($url);
    return json_decode($output);//[''][0]
}
?>