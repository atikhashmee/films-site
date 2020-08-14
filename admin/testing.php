<?php
require_once "../core/system.php";
//$url = "https://api.themoviedb.org/3/movie/tt0227984/alternative_titles?api_key=".TMDB_KEY;
$url = "https://api.themoviedb.org/3/movie/tt0227984/credits?api_key=".TMDB_KEY."&external_source=imdb_id";
$CastOutput = runCurl($url)->cast;//json_decode(@file_get_contents($credits))->cast;
if(!empty($CastOutput)){
  foreach($CastOutput as $cast) {
    $name_c = $cast->name;
    $nconst = $cast->id;
    $json = runCurl("https://api.themoviedb.org/3/person/{$nconst}?api_key=".TMDB_KEY."&language=en-US");
    $array = get_object_vars($json);
    $aname = $array['name'];
    $birthday = $array['birthday'];
    $place_of_birth = $array['place_of_birth'];
    $biography = $array['biography'];
    $imdb_id = $array['imdb_id'];
    $actor_img = $array['profile_path'];
    debug($array);
  }
}

?>
