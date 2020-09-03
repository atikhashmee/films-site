<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db, $domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);
$movies = $admin->getFilms();
if (isset($_POST['add'])) {
  $video_id = $_POST['video_id'];
  $date = date('Y-m-d H:i:s');
  $db->query("UPDATE movies SET created_date = '" . $date . "' WHERE id = '" . $video_id . "'");
  $video_format = $_POST['video_format'];
  $video_embed_code = $_POST['video_embed_code'];
  $video_file_mp4 = $_POST['video_file_mp4'];
  if ($video_format == 1) {
    $source = $video_embed_code;
  } else {
    $source = $video_file_mp4;
  }
  $episode_number = $_POST['episode_number'];
  $season_sub_id = $_POST['season_sub_id'];
  $echeck = "SELECT * FROM episodes WHERE episode_number='" . $episode_number . "'";
  $echeck = $db->query($echeck);
  $echeck->num_rows;
  if ($echeck->num_rows > 0) {
    header('Location: iepisodes.php?error=Episode Already Exists!');
    exit;
  }
  $season_number = $_POST['season_number'];
  $checkseason = "SELECT * FROM seasons WHERE season_number='" . $season_number . "' AND movie_id='" . $video_id . "'";
  $checkseason = $db->query($checkseason);
  if ($checkseason->num_rows <= 0) {
    $db->query("INSERT INTO seasons (movie_id,season_number) VALUES ('" . $video_id . "','" . $season_number . "')");
    $season_id = $db->insert_id;
  } else {
    $season = $checkseason->fetch_assoc();
    $season_id = $season['id'];
  }
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.themoviedb.org/3/find/{$episode_number}?api_key=".TMDB_KEY."&language=en-US&external_source=imdb_id",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  ));
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    $json = json_decode($response);
    $array = get_object_vars($json);
   if(empty($array['tv_episode_results']))
    {
        _db()->insert('episodes', [
            'season_id' => $season_id,
            'movie_id' => $video_id,
            'episode_number' => $episode_number,
            'season_sub_id' => $season_sub_id,
            'episode_name' => 'No Name',
            'episode_description' => 'Discription Coming Soon.',
            'episode_thumbnail' => '814512c0eb40e98a0f15cf493f8c6096maxresdefault-1.jpg',
            'episode_source' => $source,
            'is_embed' => '0',
            'actor_id' => '',
            'ratings' => '',
        ]);
    }
   foreach ($array['tv_episode_results'] as $episode) {
      $name = addslashes($episode->name);
      $id = $episode->id;
      $vote_count = $episode->vote_count;
      $vote_average = $episode->vote_average;
      $first_air_date = $episode->first_air_date;
      $poster_path = $episode->still_path;
      $overview = addslashes($episode->overview);
      $genre_ids = $episode->genre_ids;
      $origin_country = $episode->origin_country;
      $episode_num_cast = $episode->episode_number;
      $episode_num_cast = $episode->episode_number;
      $season_number_cast = $episode->season_number;
      $show_id = $episode->show_id;
      $make_actors = array();
      $actors = json_decode(@file_get_contents("https://api.themoviedb.org/3/tv/{$show_id}/season/{$season_number_cast}/episode/{$episode_num_cast}/credits?api_key=".TMDB_KEY))->cast;
   foreach ($actors as $actor) {
        $nconst = $actor->id;
        $actor_info = json_decode(@file_get_contents("https://api.themoviedb.org/3/person/{$nconst}?api_key=".TMDB_KEY."&language=en-US"));
        $aname = $actor_info->name;
        $birthday = $actor_info->birthday;
        $place_of_birth = $actor_info->place_of_birth;
        $biography = addslashes($actor_info->biography);
        $imdb_id = $actor_info->imdb_id;
        $actor_img = $actor_info->profile_path;
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
      $makeactors = implode(",", $make_actors);
      $im_url = "https://image.tmdb.org/t/p/original" . $poster_path;
      if ($im_url != "") {
        $extension = strtolower(end(explode('.', $movieOutput->poster_path)));
        $new_file_name_2 = generate_postname($movieOutput->title) . '_poster' . time() . '.' . $extension;
        $url = $im_url;
        $img2 = '../uploads/episodes/' . $new_file_name_2;
        file_put_contents($img2, file_get_contents($im_url));
        resize($im_url, $new_file_name_2, '../uploads/episodes/');
      }

      _db()->insert('episodes', [
            'season_id' => $season_id,
            'movie_id' => $video_id,
            'episode_number' => $episode_number,
            'season_sub_id' => $season_sub_id,
            'episode_name' => $name,
            'episode_description' => $overview,
            'episode_thumbnail' => $new_file_name_2,
            'episode_source' => $source,
            'is_embed' => 0,
            'actor_id' => $makeactors,
            'ratings' => $vote_average,
      ]);
      $actors = $make_actors;
      $movie_id = $db->insert_id;
      $db->query("delete from my_watched where movie_id='".$video_id."'");
      $db->query("INSERT INTO ratings(movie_id,user_id,rating) VALUES ('{$movie_id}','22','{$rating}')"); 
      $sql = "SELECT * FROM actor_relations WHERE actor_id='" . $actor_id . "' AND movie_id='" . $movie_id . "'";
      $result = $db->query($sql);
      if ($result->num_rows <= 0) {
        foreach ($actors as $actor => $actor_id) {
          $db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('{$movie_id}','{$actor_id}')");
        }
      }
      $db->query("UPDATE movies SET all_starcast = 'yes' WHERE id = '{$movie_id}'");
      header('Location: iepisodes.php?success=1');
      exit;
    }
  }
}
$getSeriesId = $db->query("SELECT id FROM genres WHERE genre_name LIKE '%Series%'")->fetch_assoc()['id'];
//debug($getSeriesId);
$movies = $db->query("SELECT * FROM movies WHERE movie_genres LIKE '%$getSeriesId%'");
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
    <link href="assets/css/animate.min.css" rel="stylesheet" />
    <link href="assets/css//bootstrap-select.min.css" rel="stylesheet" />
    <link href="assets/css/theme.css" rel="stylesheet" />
    <link href="assets/css/plugins.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
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

    li {
        list-style: none;
    }

    .panel-success .panel-heading {
        background-color: #98CB00 !important;
    }

    .dropdown-toggle {
        border-radius: 6px !important;
    }
    </style>
</head>
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
                        <a class="navbar-brand" href="#">Add Episode</a>
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
                            <h4 class="title">New Episode</h4>
                        </div>
                        <div class="overlay">
                            <div id="loading-img"></div>
                        </div>
                        <div class="content">

                            <?php if (isset($error)) { ?> <div class="alert alert-danger"> <?= $error ?> </div>
                            <?php
                                                                                  } ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading panel-title">Video</div>
                                            <div class="panel-body">
                                                <!--  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"> -->
                                                <!--     <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
   -->
                                                <!-- $(function() {
  $('.selectpicker').selectpicker();
}); -->
                                                <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" /> -->


                                                <select name="video_id" class="form-control selectpicker"
                                                    id="select-country" data-live-search="true" required>
                                                    <?php
    while ($movie = $movies->fetch_object()) {
      if ($movie->is_series == 1) {
        echo '<option value="' . $movie->id . '"> ' . $movie->movie_name . ' (' . $movie->movie_year . ')' . ' ' . $movie->imdbid . ' </option>';
      }
    }
    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading panel-title">IMDB Episode ID</div>
                                            <div class="panel-body"> <input type="text" name="episode_number"
                                                    class="form-control border-input"
                                                    placeholder="Enter a number for this episode" id="imdbidnew"
                                                    required> </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading panel-title">Season Number</div>
                                            <div class="panel-body"> <input type="text" name="season_number"
                                                    class="form-control border-input"
                                                    placeholder="What season does this episode belong to?" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading panel-title">Episode Number</div>
                                            <div class="panel-body"> <input type="text" name="season_sub_id" class="form-control border-input" placeholder="Number of the current Episode" required> </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading panel-title">Episode Source</div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label> Episode Format </label>
                                                    <div class="clearfix"></div>
                                                    <select id="video_format" name="video_format" class="form-control"
                                                        onChange="changeVideoFormat()">
                                                        <option value="1"> Embed code </option>
                                                        <option value="0"> Video file </option>
                                                    </select>
                                                    <br>
                                                    <div id="video_file_div" style="display:none;">
                                                        <div class="form-group">
                                                            <label> Video URL (MP4)</label>
                                                            <input type="text" name="video_file_mp4"
                                                                class="form-control">
                                                            <p class="help-block"> <b class="text-danger"> <i
                                                                        class="fa fa-youtube-play"></i> YouTube </b>
                                                                links are supported </p>
                                                        </div>
                                                    </div>
                                                    <div id="video_embed_div">
                                                        <div class="form-group">
                                                            <label> Embed code </label>
                                                            <textarea name="video_embed_code"
                                                                class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success btn-fill btn-wd" id="submit-id">View
                                    Episode Info</button>
                                <button type="submit" name="add" class="btn btn-success btn-fill btn-wd">Add
                                    Episode</button>

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
<script src="assets/js/jquery.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap-checkbox-radio.js"></script>
<script src="assets/js/chartist.min.js"></script>
<script src="assets/js/bootstrap-notify.js"></script>
<script src="assets/js/bootstrap-select.min.js"></script>
<!-- <script type="text/javascript" src="assets/js/chosen.jquery.min.js"></script> -->
<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script> -->
<script src="assets/js/theme.js"></script>
<!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> -->
<!-- <script src="//netsh.pp.ua/upwork-demo/1/js/typeahead.js"></script> -->

<script>
// $('.chosen').chosen();
function changeVideoFormat() {
    var format = $('#video_format').val();
    if (format == 0) {
        $('#video_file_div').show();
        $('#video_embed_div').hide();
    } else {
        $('#video_file_div').hide();
        $('#video_embed_div').show();
    }
}
</script>

<script>
$(document).ready(function() {

    $("#submit-id").click(function(event) {
        $(".overlay").show();
        var imdbid = $("#imdbidnew").val();
        $.post("ajax_imdb.php", {
                "imdbid": imdbid
            })
            .done(function(data) {
                $(".overlay").hide();
                if (data.trim() == "no") {
                    alert("No Data Found!");
                    event.preventDefault();
                    return false;
                } else {
                    $("#imdbidoverview").html(data);
                }

            });
    });

});
</script>

</html>