<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);

$movies = $admin->getMovies();
$actors = $db->query("SELECT * FROM actors");
//$actors = $admin->getActors();

if(isset($_POST['add'])) {
    $video_id = $_POST['video_id'];
    $episode_name = $_POST['episode_name'];
    $episode_number = $_POST['episode_number'];
    $season_number = $_POST['season_number'];
    $episode_description = $db->real_escape_string($_POST['episode_description']);
    $video_format = $_POST['video_format'];
    $video_embed_code = $_POST['video_embed_code'];
    $video_file_mp4 = $_POST['video_file_mp4'];
    $actors = $_POST['actors'];
    $actors = implode(',', $actors);
    $rating = $_POST['rating'];
    if($video_format == 1) {
        $source = $video_embed_code;
    } else {
        $source = $video_file_mp4;
    }
    // Thumbnail Photo
    if(isset($_FILES['episode_thumbnail']['name'])) {
        $extension = strtolower(end(explode('.', $_FILES['episode_thumbnail']['name'])));
        if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
            if(!$_FILES['episode_thumbnail']['error']) {
                $new_file_name = md5(mt_rand()).$_FILES['episode_thumbnail']['name'];
                if($_FILES['episode_thumbnail']['size'] > (10024000)) {
                    $valid_file = false;
                    $error = 'Oops! One of the photos you uploaded is too large';
                } else {
                    $valid_file = true;
                }
                if($valid_file) {
                    move_uploaded_file($_FILES['episode_thumbnail']['tmp_name'], '../uploads/episodes/'.$new_file_name);
                    $uploaded = true;
                }
            }
            else {
                $error = 'Error occured:  '.$_FILES['episode_thumbnail']['error'];
            }
        }
    }
    $admin->db->query("UPDATE movies SET is_series='1' WHERE id='".$video_id."'");
    $season = $db->query("SELECT id FROM seasons WHERE movie_id='".$video_id."' AND season_number='".$season_number."'");
    if($season->num_rows >= 1) {
        $season = $season->fetch_object();
      $season_id = $season->id;
    } else {
        $db->query("INSERT INTO seasons (movie_id,season_number) VALUES ('".$video_id."','".$season_number."')");
        $season_id = $db->insert_id;
    }
    $db->query("INSERT INTO episodes (season_id,movie_id,episode_number,episode_name,episode_description,episode_thumbnail,episode_source,is_embed,actor_id,ratings)
        VALUES ('".$season_id."','".$video_id."','".$episode_number."','".$episode_name."','".$episode_description."','".$new_file_name."','".$source."','".$is_embed."','".$actors."','".$rating."')");
    $db->query("delete from my_watched where movie_id='".$video_id."'");
    header('Location: episodes.php?success=1');
    exit;
}
$getSeriesId = $db->query("SELECT id FROM genres WHERE genre_name LIKE '%Series%'")->fetch_assoc()['id'];
//debug($getSeriesId);
$movies = $db->query("SELECT * FROM movies WHERE movie_genres LIKE '%$getSeriesId%'");
// season_id,movie_id,episode_number,episode_name,episode_description,episode_thumbnail,episode_source,is_embed,actor_id,ratings
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
 <link href="assets/css/bootstrap-select.min.css" rel="stylesheet" />
 <link href="assets/css/theme.css" rel="stylesheet"/>
 <link href="assets/css/chosen.min.css" rel="stylesheet"/>
 <link href="assets/css/chosen-bootstrap.css" rel="stylesheet"/>
 <link href="assets/css/plugins.css" rel="stylesheet"/>
 <link href="assets/css/style.css" rel="stylesheet"/>
 <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
 <link href="assets/css/themify-icons.css" rel="stylesheet">
 <style>
   .panel-success .panel-heading{
  background-color:   #98CB00 !important;
}
.dropdown-toggle{
  border-radius: 6px !important;
}
 </style>
</head>
<body>
    <div class="wrapper">
    <?php require_once "header.php";?>
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
                            <a href="<?=$admin->getDomain()?>">
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
                <div class="content">

                    <?php if(isset($error)) { ?> <div class="alert alert-danger"> <?=$error?> </div> <?php } ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Video</div>
                                    <div class="panel-body">
                                        <select name="video_id" name="video_id" class="form-control selectpicker"
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
                                    <div class="panel-heading panel-title">Episode Name</div>
                                    <div class="panel-body"> <input type="text" name="episode_name" class="form-control border-input" placeholder="Enter a name for this episode" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Episode IMDB ID</div>
                                    <div class="panel-body"> <input type="text" name="episode_number" class="form-control border-input" placeholder="Enter a number for this episode" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Season Number</div>
                                    <div class="panel-body"> <input type="text" name="season_number" class="form-control border-input" placeholder="What season does this episode belong to?" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Episode Description</div>
                                    <div class="panel-body"> <textarea id="editor" name="episode_description" rows="5" class="form-control" placeholder="Enter a description/plot for this episode" required></textarea> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Episode Thumbnail</div>
                                    <div class="panel-body"> <input type="file" name="episode_thumbnail" class="form-control border-input" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Actors</div>
                                    <div class="panel-body"><select name="actors[]" multiple="multiple" class="form-control chosen">
                                            <?php
                                            while($actor = $actors->fetch_object()) {
                                                echo '<option value="'.$actor->id.'"> '.$actor->actor_name.' </option>';
                                            }
                                            ?>
                                        </select> </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Ratings</div>
                                    <div class="panel-body"> <input type="text" name="rating" class="form-control border-input" placeholder="Rating" required> </div>
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
                                            <select id="video_format" name="video_format" class="form-control" onchange="changeVideoFormat()">
                                                <option value="0"> Video file </option>
                                                <option value="1"> Embed code </option>
                                            </select>
                                            <br>
                                            <div id="video_file_div">
                                                <div class="form-group">
                                                    <label> Video URL (MP4)</label>
                                                    <input type="text" name="video_file_mp4" class="form-control">
                                                    <p class="help-block"> <b class="text-danger"> <i class="fa fa-youtube-play"></i> YouTube </b> links are supported </p>
                                                </div>
                                            </div>
                                            <div id="video_embed_div" style="display:none;">
                                                <div class="form-group">
                                                    <label> Embed code </label>
                                                    <textarea name="video_embed_code" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="add" class="btn btn-success btn-fill btn-wd">Add Episode</button>
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
<script src="assets/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="assets/js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script src="assets/js/theme.js"></script>
<script>
$('.chosen').chosen();
function changeVideoFormat() {
    var format = $('#video_format').val();
    if(format == 0) {
        $('#video_file_div').show();
        $('#video_embed_div').hide();
    } else {
        $('#video_file_div').hide();
        $('#video_embed_div').show();
    }
}
</script>
</html>
