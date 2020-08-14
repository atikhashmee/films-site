<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);

$genres = $admin->getGenres();
//$genress->fetch_object();
//print_r($genress);
//$actors = $admin->getActors();

$actors = $db->query("SELECT * FROM actors");
if(isset($_POST['add'])) {
    $video_name = $_POST['video_name'];
    $video_description = $db->real_escape_string($_POST['video_description']);
    $video_categories = $_POST['video_categories'];
    $video_format = $_POST['video_format'];
    $video_embed_code = $_POST['video_embed_code'];
    $video_file_mp4 = $_POST['video_file_mp4'];
    $movie_rating = $_POST['movie_rating'];
    $imdb_id = $_POST['imdb_id'];
    $alternative_title = $_POST['alternative_title'];
    $movie_year = $_POST['movie_year'];
    if($admin->settings->kid_profiles == 0) {
        $is_kid_friendly = 0;
    } else {
        $is_kid_friendly = $_POST['is_kid_friendly'];
    }
    if($video_format == 1) {
        $source = $video_embed_code;
    } else {
        $source = $video_file_mp4;
    }
    $video_categories = implode(',',$video_categories);
    $is_featured = $_POST['is_featured'];
    $free_to_watch = $_POST['free_to_watch'];
    // Thumbnail Photo
    if(isset($_FILES['video_thumbnail']['name'])) {
        $extension = strtolower(end(explode('.', $_FILES['video_thumbnail']['name'])));
        if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
            if(!$_FILES['video_thumbnail']['error']) {
                $new_file_name = md5(mt_rand()).$_FILES['video_thumbnail']['name'];
                if($_FILES['video_thumbnail']['size'] > (10024000)) {
                    $valid_file = false;
                    $error = 'Oops! One of the photos you uploaded is too large';
                } else {
                    $valid_file = true;
                }
                if($valid_file) {
                    move_uploaded_file($_FILES['video_thumbnail']['tmp_name'], '../uploads/masonry_images/'.$new_file_name);
                    $uploaded = true;
                }
            }
            else {
                $error = 'Error occured:  '.$_FILES['video_thumbnail']['error'];
            }
        }
    }
    // Poster Photo
    if(isset($_FILES['video_poster']['name'])) {
        $extension = strtolower(end(explode('.', $_FILES['video_poster']['name'])));
        if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
            if(!$_FILES['video_poster']['error']) {
                $new_file_name_2 = md5(mt_rand()).$_FILES['video_poster']['name'];
                if($_FILES['video_poster']['size'] > (10024000)) {
                    $valid_file = false;
                    $error = 'Oops! One of the photos you uploaded is too large';
                } else {
                    $valid_file = true;
                }
                if($valid_file) {
                    move_uploaded_file($_FILES['video_poster']['tmp_name'], '../uploads/poster_images/'.$new_file_name_2);
                    $uploaded = true;
                }
            }
            else {
                $error = 'Error occured:  '.$_FILES['video_poster']['error'];
            }
        }
    }
    $db->query("INSERT INTO movies (movie_name,movie_plot,movie_year,movie_genres,movie_poster_image,movie_thumb_image,movie_plays,movie_source,movie_rating,is_embed,is_featured,is_series,last_season,is_kid_friendly,free_to_watch,from_type,imdbid,all_starcast,alternative_titles,watch)
     VALUES ('".$video_name."','".$video_description."','".$movie_year."','".$video_categories."','".$new_file_name_2."','".$new_file_name."','0','".$source."','".$movie_rating."','".$video_format."','".$is_featured."','".$is_series."','0','".$is_kid_friendly."','".$free_to_watch."','film','".$imdb_id."','yes','".$alternative_title."','0')");
    $actors = $_POST['video_actors'];
    $movie_id = $db->insert_id;
    foreach($actors as $actor => $actor_id) {
        $db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('".$movie_id."','".$actor_id."')");
    }
    header('Location: videos.php?success=1');
    exit;
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
                    <a class="navbar-brand" href="#">Add Video</a>
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
                    <h4 class="title">New Video</h4>
                </div>
                <div class="content">
                    <?php if(isset($error)) { ?> <div class="alert alert-danger"> <?=$error?> </div> <? } ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Name</div>
                                    <div class="panel-body"> <input type="text" name="video_name" class="form-control border-input" placeholder="Enter a name for this video" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Description</div>
                                    <div class="panel-body"> <textarea id="editor" name="video_description" rows="5" class="form-control" placeholder="Enter a description/plot for this video" required></textarea> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Categories</div>
                                    <div class="panel-body">
                                        <select name="video_categories[]" multiple="multiple" class="form-control chosen">
                                            <?php
                                            while($genre = $genres->fetch_object()) {
                                                echo '<option value="'.$genre->id.'"> '.$genre->genre_name.' </option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if($admin->settings->show_actors == 1) { ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Actors</div>
                                    <div class="panel-body">
                                        <select name="video_actors[]" multiple="multiple" class="form-control chosen">
                                            <?php
                                            while($actor = $actors->fetch_object()) {
                                                echo '<option value="'.$actor->id.'"> '.$actor->actor_name.' </option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <? } ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Thumbnail</div>
                                    <div class="panel-body"> <input type="file" name="video_thumbnail" class="form-control border-input" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Poster</div>
                                    <div class="panel-body"> <input type="file" name="video_poster" class="form-control border-input" required> </div>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Imdb ID</div>
                                    <div class="panel-body"> <input type="text" name="imdb_id" class="form-control border-input" required> </div>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Alternative Title</div>
                                    <div class="panel-body"> <input type="text" name="alternative_title" class="form-control border-input" required> </div>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Rating</div>
                                    <div class="panel-body"> <input type="text" name="movie_rating" class="form-control border-input" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Is Series</div>
                                    <div class="panel-body">
                                      <select name="is_series" class="form-control border-input">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                      </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Movie Year</div>
                                    <div class="panel-body"> <input type="text" name="movie_year" class="form-control border-input" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Source</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label> Video Format </label>
                                            <div class="clearfix"></div>
                                            <select id="video_format" name="video_format" class="form-control" onChange="changeVideoFormat()">
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
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Status</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label> Featured </label>
                                            <select name="is_featured" class="form-control">
                                                <option value="0"> False </option>
                                                <option value="1"> True </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label> Who can watch? </label>
                                            <select name="free_to_watch" class="form-control">
                                                <option value="0"> Only subscribers </option>
                                                <option value="1"> Everyone </option>
                                            </select>
                                        </div>
                                        <?php if($admin->settings->kid_profiles == 1) { ?>
                                        <div class="form-group">
                                            <label>Can be viewed by kids?</label>
                                            <select name="is_kid_friendly" class="form-control">
                                                <option value="0"> False </option>
                                                <option value="1"> True </option>
                                            </select>
                                        </div>
                                        <? } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="add" class="btn btn-success btn-fill btn-wd">Add Video</button>
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
            Muviko
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
$('.chosen').chosen({disable_search_threshold: 10});
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
