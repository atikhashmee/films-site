<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db, $domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);
if (isset($_POST['add'])) {
    $make_actors = array();
    if(isset($_POST['is_series'])){
      //series test
      $imdbid = $_POST['IMDBID'] ;
      $movieOutputs = run_tmdb_curl($imdbid);

      $make_geners = array();
      foreach ($movieOutputs->tv_results as $movieOutput) {
          $checkmovie = "SELECT * FROM movies WHERE movie_name='" . $db->real_escape_string($movieOutput->name) . "'";
          $tv_id = $movieOutput->id;
          if ($checkmovie_r->num_rows > 0) {
              header('Location: films.php?error=Movie Already Exists!');
              exit;
          }
          if ($video_name != "") {
              $new_file_name = md5(mt_rand()) . '_video.mp4';
              $url = $video_name;
              $img = '../uploads/masonry_images/' . $new_file_name;
              file_put_contents($img, file_get_contents($url));
          }
          /*Geners Start*/
          $genres = $movieOutput->genre_ids;
          $catName = str_replace(' ', '-', $catName);
          foreach ($genres as $category) {
              $catName = json_decode(@file_get_contents("https://api.themoviedb.org/3/genre/{$category}?api_key=".TMDB_KEY))->name;

              if ($catName != '') {
              # code...
                  $catName = str_replace(' ', '-', $catName);
                  $sql = "SELECT * FROM genres WHERE genre_name LIKE '%" . $catName . "%'";
                  $result = $db->query($sql);
                  if ($result->num_rows <= 0) {
                        $catName = str_replace(' ', '-', $catName);
                      $newinsert = $db->query("INSERT INTO genres(genre_name,is_kid_friendly) VALUES ('" . $catName . "','" . $is_kid_friendly . "')");
                //         $newinsert = $db->query("INSERT INTO genres(is_kid_friendly) VALUES ('" . $is_kid_friendly . "')");
                      $gener_id = $db->insert_id;
                  } else {
                      while ($row = $result->fetch_assoc()) {
                          $gener_id = $row['id'];
                      }
                  }
              }
              $make_geners[] = $gener_id;
              //$make_geners[][]

              //$make_geners[] = 25;
          }
          array_push($make_geners,25);

          $genres_video = implode(",", $make_geners);

          /*Geners Ends*/
          /*Actors Starts*/
          $json = runCurl("https://api.themoviedb.org/3/tv/{$tv_id}/credits?api_key=".TMDB_KEY."&language=en-US");
          $array = get_object_vars($json);

          foreach ($array as $casts) {
              foreach ($casts as $cast) {
                  $name_c = $cast->name;
                  $nconst = $cast->id;
                  $name_c = $cast->name;
                  $nconst = $cast->id;
                  $json = runCurl("https://api.themoviedb.org/3/person/{$nconst}?api_key=".TMDB_KEY."&language=en-US");
                  $array = get_object_vars($json);

                  $aname = $array['name'];
                  $birthday = $array['birthday'];
                  $place_of_birth = $array['place_of_birth'];
                  $biography = addslashes($array['biography']);
                  $imdb_id = $array['imdb_id'];
                  $actor_img = $array['profile_path'];
                  $im_url_c = "https://image.tmdb.org/t/p/original" . $actor_img;
                  if ($actor_img != '') {
                      $extension = strtolower(end(explode('.', $actor_img)));
                      $c_actor = generate_postname($aname) . '_actor' . time() . '.' . $extension;
                      $ur11 = $im_url_c;
                      resize(file_get_contents($im_url_c), $c_actor, UPLOAD_PATH . 'actors/', 50);
                      $img = UPLOAD_PATH . 'actors/' . $c_actor;
                  } else {
                      $c_actor = "";
                  }
                  $sql1 = "SELECT * FROM actors WHERE actor_name LIKE '%" . $aname . "%'";
                  $result1 = $db->query($sql1);
                  if ($result1->num_rows <= 0) {
                     $db->query("INSERT INTO actors (actor_name,actor_picture,actor_nconst,birthday,place_of_birth,biography,actor_img_url,imdbid) VALUES ('" . $aname . "','" . $c_actor . "','" . $nconst . "','" . $birthday . "','" . $place_of_birth . "','" . $biography . "','" . $im_url_c . "','" . $imdb_id . "')");
                      $actor_id = $db->insert_id;
                  } else {
                      while ($row1 = $result1->fetch_assoc()) {
                          $actor_id = $row1['id'];
                        $a = "UPDATE actors SET actor_name = '$aname',actor_picture ='$c_actor',actor_nconst = '$nconst',birthday='$birthday', place_of_birth='$place_of_birth',biography='$biography',actor_img_url='$im_url_c',imdbid='$imdb_id' WHERE id = '$actor_id'";
                          $db->query($a);
                      }
                  }
                  $make_actors[] = $actor_id;
              }
          }
          $makeactors = implode(",", $make_actors);
          $actors = explode(",", $makeactors);

          /*Actors Ends*/
          $description = $movieOutput->overview;
          $video_name = $db->real_escape_string($movieOutput->name);
          $video_description = $db->real_escape_string($description);
          $video_categories = $genres_video ;
          $is_kid_friendly = 0;
          $video_format = $_POST['video_format'];
          $source = $_POST['video_url'];
          $im_url = "https://image.tmdb.org/t/p/original" . $movieOutput->poster_path;
          if ($im_url != "") {
              $extension = strtolower(end(explode('.', $movieOutput->poster_path)));
              $new_file_name_2 = generate_postname($movieOutput->name) . '_poster' . time() . '.' . $extension;
              $url = $im_url;
              $img2 = '../uploads/poster_images/' . $new_file_name_2;
              $poster = '../uploads/masonry_images/' . $new_file_name_2;
              file_put_contents($img2, file_get_contents($im_url));
              file_put_contents($poster, file_get_contents($im_url));
              resize($im_url, $new_file_name_2, '../uploads/masonry_images/');
          }
          $json = runCurl("https://api.themoviedb.org/3/movie/{$imdbid}/alternative_titles?api_key=".TMDB_KEY);
          $ary = get_object_vars($json);
          $s = $ary['titles'];
          $key = array();
          $features = '';
          foreach ($s as $value) {
              if ($value->iso_3166_1 == 'US' || $value->iso_3166_1 == 'USA') {
                  $key[] = $value->iso_3166_1;
                  $features = $value->title;
                  $features = str_replace("'", $features);
              }
          }
          $rating = $movieOutput->vote_average;
          if (isset($movie->rating)) {
              $rating = round((($movie->rating) / 2), 1);
          }
          $db->query("INSERT INTO movies (movie_name,movie_plot,movie_genres,movie_poster_image,movie_thumb_image,movie_plays,movie_source,movie_rating,movie_year,from_type,imdbid,is_embed,is_series,alternative_titles)
          VALUES ('{$video_name}','{$video_description}','{$video_categories}','{$new_file_name_2}','{$new_file_name_2}','0','{$source}','{$rating}','{$movie->year}','film','{$imdbid}','{$video_format}','1','{$features}')");
          $movie_id = $db->insert_id;
          $db->query("INSERT INTO ratings(movie_id,user_id,rating) VALUES ('{$movie_id}','22','{$rating}')");
          if(!empty($actors)){
          foreach ($actors as $actor => $actor_id) {
              $db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('{$movie_id}','{$actor_id}')");
          }
          }

          if(!empty($make_geners)){

            foreach ($make_geners as $make_gener => $geners_id) {

                $db->query("INSERT INTO genres_relations(movie_id,genres_id) values($movie_id,$geners_id)");
            }
          }
          $db->query("UPDATE movies SET all_starcast = 'yes' WHERE id = '{$movie_id}'");
          header('Location: films.php?success=1');
          exit();
      }
    }
    else{

        $movieOutput = run_tmdb_curl($imdbid); //->movie_results[0]
        print_r($movieOutputs);
        if (!empty($movieOutput)) {
            $checkmovie = "SELECT * FROM movies WHERE movie_name='" . $db->real_escape_string($movieOutput->title) . "'";
            if ($checkmovie_r->num_rows > 0) {
                header('Location: films.php?error=Movie Already Exists!');
                exit;
            }
            if ($video_name != "") {
                $new_file_name = md5(mt_rand()) . '_video.mp4';
                $url = $video_name;
                $img = '../uploads/masonry_images/' . $new_file_name;
                file_put_contents($img, file_get_contents($url));
            }
            /*Geners Start*/
            $genres = $movieOutput->genre_ids;
            $catName = str_replace(' ', '-', $catName);
            foreach ($genres as $category) {
                $catName = runCurl("https://api.themoviedb.org/3/genre/{$category}?api_key=".TMDB_KEY)->name;
                $catName = str_replace(' ', '-', $catName);
                $sql = "SELECT * FROM genres WHERE genre_name LIKE '%" . $catName . "%'";
                $result = $db->query($sql);
                if ($result->num_rows <= 0) {
                    $catName = str_replace(' ', '-', $catName);
                    $newinsert = $db->query("INSERT INTO genres(genre_name,is_kid_friendly) VALUES ('" . $catName . "','" . $is_kid_friendly . "')");
                    $gener_id = $db->insert_id;
                } else {
                    while ($row = $result->fetch_assoc()) {
                        $gener_id = $row['id'];
                    }
                }
                $make_geners[] = $gener_id;
            }
            $genres_video = implode(",", $make_geners);
            /*Geners Ends*/
            /*Actors Starts*/
            $json = runCurl("https://api.themoviedb.org/3/movie/{$imdbid}/credits?api_key=".TMDB_KEY."&external_source=imdb_id");
            $array1 = get_object_vars($json);
            foreach ($array1 as $casts) {
                foreach ($casts as $cast) {
                    $name_c = $cast->name;
                    $nconst = $cast->id;
                    $name_c = $cast->name;
                    $nconst = $cast->id;
                    $json = runCurl("https://api.themoviedb.org/3/person/{$nconst}?api_key=".TMDB_KEY."&language=en-US");
                    $array = get_object_vars($json);
                    $aname = $array['name'];
                    $birthday = $array['birthday'];
                    $place_of_birth = $array['place_of_birth'];
                    $biography = addslashes($array['biography']);
                    $imdb_id = $array['imdb_id'];
                    $actor_img = $array['profile_path'];
                    $im_url_c = "https://image.tmdb.org/t/p/original" . $actor_img;
                    if ($actor_img != '') {
                        $extension = strtolower(end(explode('.', $actor_img)));
                        $c_actor = generate_postname($aname) . '_actor' . time() . '.' . $extension;
                        $ur11 = $im_url_c;
                        resize(file_get_contents($im_url_c), $c_actor, UPLOAD_PATH . 'actors/', 50);
                        $img = UPLOAD_PATH . 'actors/' . $c_actor;
                    } else {
                        $c_actor = "";
                    }
                    $sql1 = "SELECT * FROM actors WHERE actor_name LIKE '%" . $aname . "%'";
                    $result1 = $db->query($sql1);
                    if ($result1->num_rows <= 0) {

                        $db->query("INSERT INTO actors (actor_name,actor_picture,actor_nconst,birthday,place_of_birth,biography,actor_img_url,imdbid) VALUES ('" . $aname . "','" . $c_actor . "','" . $nconst . "','" . $birthday . "','" . $place_of_birth . "','" . $biography . "','" . $im_url_c . "','" . $imdb_id . "')");
                        $actor_id = $db->insert_id;
                    } else {
                        while ($row1 = $result1->fetch_assoc()) {
                            $actor_id = $row1['id'];
                            $a = "UPDATE actors SET actor_name = '$aname',actor_picture ='$c_actor',actor_nconst = '$nconst',birthday='$birthday', place_of_birth='$place_of_birth',biography='$biography',actor_img_url='$im_url_c',imdbid='$imdb_id' WHERE id = '$actor_id'";
                            $db->query($a);
                        }
                    }
                    $make_actors[] = $actor_id;
                }
            }
            $makeactors = implode(",", $make_actors);
            $actors = explode(',', $makeactors);

            /*Actors Ends*/
            $description = $movieOutput->overview;
            $video_name = $db->real_escape_string($movieOutput->title);
            $video_description = $db->real_escape_string($description);
            $video_categories = $genres_video;
            $is_kid_friendly = 0;
            $video_format = $_POST['video_format'];
            $source = $_POST['video_url'];
            $im_url = "https://image.tmdb.org/t/p/original" . $movieOutput->poster_path;
            if ($im_url != "") {
                $extension = strtolower(end(explode('.', $movieOutput->poster_path)));
                $new_file_name_2 = generate_postname($movieOutput->title) . '_poster' . time() . '.' . $extension;
                $url = $im_url;
                $img2 = '../uploads/poster_images/' . $new_file_name_2;
                file_put_contents($img2, file_get_contents($im_url));
                resize($im_url, $new_file_name_2, '../uploads/masonry_images/');
            }
            else {
                $json = runCurl("https://api.themoviedb.org/3/movie/{$imdbid}/alternative_titles?api_key=".TMDB_KEY);
                $ary = get_object_vars($json);
                $s = $ary['titles'];
                $key = array();
                $features = '';
                foreach ($s as $value) {
                    if ($value->iso_3166_1 == 'US' || $value->iso_3166_1 == 'USA') {
                        $key[] = $value->iso_3166_1;
                        $features = $value->title;
                        $features = str_replace("'", $features);
                    }
                }
                $rating = $movieOutput->vote_average;
                if (isset($movie->rating)) {
                    $rating = round((($movie->rating) / 2), 1);
                }
                $db->query("INSERT INTO movies (movie_name,movie_plot,movie_genres,movie_poster_image,movie_thumb_image,movie_plays,movie_source,movie_rating,movie_year,from_type,imdbid,is_embed,alternative_titles)
                VALUES ('{$video_name}','{$video_description}','{$video_categories}','{$new_file_name_2}','{$new_file_name_2}','0','{$source}','{$rating}','{$movie->year}','film','{$imdbid}','{$video_format}','{$features}')");
                $movie_id = $db->insert_id;
                $db->query("INSERT INTO ratings(movie_id,user_id,rating) VALUES ('{$movie_id}','22','{$rating}')");
                if(!empty($actors)){
                foreach ($actors as $actor => $actor_id) {
                    $db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('{$movie_id}','{$actor_id}')");
                }
                }

                if(!empty($make_geners)){

                  foreach ($make_geners as $Mg => $Gi) {
                      $db->query("INSERT INTO genres_relations(movie_id,genres_id) values($movie_id,$Gi)");
                  }
                }
                $db->query("UPDATE movies SET all_starcast = 'yes' WHERE id = '{$movie_id}'");
                header('Location: films.php?success=1');
                exit();
            }
        }
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
 <meta charset="utf-8" />
 <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
 <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
 <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
 <title>Admin Panel</title>
 <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
 <meta name="viewport" content="width=device-width" />
 <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
 <link href="assets/css/animate.min.css" rel="stylesheet"/>
 <link href="assets/css/theme.css" rel="stylesheet"/>
 <link href="assets/css/chosen.min.css" rel="stylesheet"/>
 <link href="assets/css/chosen-bootstrap.css" rel="stylesheet"/>
 <link href="assets/css/plugins.css" rel="stylesheet"/>
 <link href="assets/css/style.css" rel="stylesheet"/>
 <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
 <link href="assets/css/themify-icons.css" rel="stylesheet">
<style>
#loading-img {
    background: url(assets/images/loader.gif) center center no-repeat;
    height: 100%;
    z-index: 20000000000000;
}

.overlay {
    background: #e9e9e9;
    display: none;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    opacity: 1;
}
</style>
</head>
<body>
    <div class="wrapper">
    <?php require_once "header.php"; ?>
    <div class="main-panel">
        <nav class="navbar navbar-default">

            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="#">Add Film</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?= $admin->getDomain() ?>">
                                <i class="ti-arrow-left"></i>
                                <p>Back to User Area</p>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container-fluid">
              <div class="card">
                <div class="header">
                    <h4 class="title">New Film</h4>
                </div>
				<div class="overlay">
					<div id="loading-img"></div>
				</div>
                <div class="content">

                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">IMDB Film ID</div>
                                    <div class="panel-body"> <input type="text" name="IMDBID" id="imdbidnew" class="form-control border-input" placeholder="Enter IMDB Film ID for this film" required> </div>
                                </div>
                            </div>
                        </div>
						<div class="row">


						<div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Video Source</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label> Video Format </label>
                                            <div class="clearfix"></div>
                                            <select id="video_format" name="video_format" class="form-control" >
                                               <option value="1"> Embed code </option>
                                                <option value="0"> Video file </option>

                                            </select>
                                            <br>
                                            <div id="video_file_div">
                                                <div class="form-group">
                                                       <label> Video URL (MP4)</label>
                                                        <textarea  name="video_url" class="form-control border-input" placeholder="E.g. https://www.youtube.com/watch?v=E5ln4uR4TwQ " ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="is_series" class="checkbox"><input type="checkbox" name="is_series" id="is_series"/>  <span>Is this a Series</span> </label>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>

                        <button type="button" class="btn btn-success btn-fill btn-wd" id="submit-id">View Film Info</button>
						<button type="submit" name="add" class="btn btn-success btn-fill btn-wd" >Save</button>
						<div class="row">
                            <div class="col-lg-12" id="imdbidoverview">
                            </div>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container-fluid">
        <div class="copyright pull-left">
		&nbsp;
        </div>
    </div>
</footer>
</div>
</div>
</body>
<script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap-checkbox-radio.js"></script>
<script src="assets/js/chartist.min.js"></script>
<script src="assets/js/bootstrap-notify.js"></script>
<script type="text/javascript" src="assets/js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script src="assets/js/theme.js"></script>
<script>
$(document).ready(function(){

	$("#submit-id").click(function(event){
		$(".overlay").show();
		var imdbid = $("#imdbidnew").val();
		$.post( "ajax_imdb.php", { "imdbid": imdbid})
		  .done(function( data ) {
		  $(".overlay").hide();
		  if(data.trim() == "no") {
		  	alert("No Data Found!");
		  	event.preventDefault();
			return false;
		  }
		  else {
		  	$("#imdbidoverview").html(data);
		  }

		});
	});

});


</script>
</html>
