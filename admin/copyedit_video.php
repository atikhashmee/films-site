<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);

$id = $_GET['id'];

$genres = $admin->getGenres();

$actors = $admin->getActors('true');
$movie = $admin->getMovie($id);

if(isset($_POST['save'])) {
    $video_name = $_POST['video_name'];
    $video_description = $db->real_escape_string($_POST['video_description']);
    $video_categories = $_POST['video_categories'];
    $video_actors = $_POST['video_actors'];
    $video_format = $_POST['video_format'];
    $video_embed_code = $_POST['video_embed_code'];
    $video_file_mp4 = $_POST['video_file_mp4'];
    $movie_rating = $_POST['movie_rating'];
    $imdb_id = $_POST['imdb_id'];
    $alternative_title = $_POST['alternative_titles'];
    $movie_year = $_POST['movie_year'];
    $is_series = $_POST['is_series'];
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
    $is_featured = $_POST['is_featured'];
    $free_to_watch = $_POST['free_to_watch'];
    // Thumbnail Photo
    if(isset($_FILES['video_thumbnail']['name']) && $_FILES['video_thumbnail']['size'] > 0) {
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
    } else {
        $new_file_name = $movie->movie_thumb_image;
    }
    // Poster Photo
    if(isset($_FILES['video_poster']['name']) && $_FILES['video_poster']['size'] > 0) {
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
                $error = 'Error occured:  ' .$_FILES['video_poster']['error'];
            }
        }
    } else {
        $new_file_name_2 = $movie->movie_poster_image;
    }
    if(empty($video_categories)) {
        $video_categories = $movie->movie_genres;
    } else {
        $video_categories = implode(',',$video_categories);
    }
    foreach($video_actors as $actor_id) {
        $check = $db->query("SELECT id FROM actor_relations WHERE actor_id='".$actor_id."' AND movie_id='".$movie->id."'");
        if($check->num_rows == 0) {
         $db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('".$movie->id."','".$actor_id."')");
        }
    }
  //  $db->query("UPDATE movies SET movie_name='".$video_name."',movie_plot='".$video_description."',movie_genres='".$video_categories."',
  //      movie_poster_image='".$new_file_name_2."',movie_year='".$movie_year."',movie_thumb_image='".$new_file_name."',movie_source='".$source."',movie_rating='".$movie_rating."',imdbid='".$imdb_id."',alternative_titles='".$alternative_title."',is_series='".$is_series."',is_embed='".$video_format."',is_featured='".$is_featured."',is_kid_friendly='".$is_kid_friendly."', free_to_watch='".$free_to_watch."' WHERE id='".$movie->id."'");
  $db->query("UPDATE movies SET movie_name='{$video_name}',movie_plot='{$video_description}',movie_genres='{$video_categories}',
     movie_poster_image='{$new_file_name_2}',movie_year='{$movie_year}',movie_thumb_image='{$new_file_name}',movie_source='{$source}',movie_rating='{$movie_rating}',imdbid='{$imdb_id}',alternative_titles='{$alternative_title}',is_series='{$is_series}',is_embed='{$video_format}',is_featured='{$is_featured}',is_kid_friendly='{$is_kid_friendly}',
     free_to_watch='{$free_to_watch}' WHERE id='{$movie->id}'");

    if(isset($_GET['is_film']) && $_GET['is_film']=='true'){
        header('Location: films.php?success=2');
    }
    else{
        header('Location: videos.php?success=2');
    }

    exit;
}
  $postImg = $admin->getDomain().'/uploads/poster_images/'.$movie->movie_poster_image;
  $thumImg = $admin->getDomain().'/uploads/poster_images/'.$movie->movie_thumb_image;

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
   <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet"/>
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
                    <a class="navbar-brand" href="#">Edit Video</a>
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
                    <h4 class="title"><?=$movie->movie_name?></h4>
                </div>
                <div class="content">
                    <?php if(isset($error)) { ?> <div class="alert alert-danger"> <?=$error?> </div> <? } ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Name</div>
                                    <div class="panel-body"> <input type="text" name="video_name" class="form-control border-input" placeholder="Enter a name for this video" value="<?=$movie->movie_name?>" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Description</div>
                                    <div class="panel-body"> <textarea id="editor" name="video_description" rows="5" class="form-control" placeholder="Enter a description/plot for this video" required><?=$movie->movie_plot?></textarea> </div>
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
                                                $genre_id = (string)$genre->id;
                                                if(preg_match('~\b'.$genre_id.'\b~',$movie->movie_genres)) {
                                                echo '<option value="'.$genre->id.'" selected> '.$genre->genre_name.' </option>';
                                                } else {
                                                echo '<option value="'.$genre->id.'"> '.$genre->genre_name.' </option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php //if($admin->settings->show_actors == 1) { ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Actors</div>
                                    <div class="panel-body">

                                        <select name="video_actors[]" multiple="multiple" class="form-control chosen">
                                            <?php

                                            foreach ($actors as $actor) {

                                                $check = $db->query("SELECT * FROM actor_relations WHERE actor_id='".$actor->id."' AND movie_id='".$movie->id."'");
                                                if($check->num_rows >= 1) {
                                                echo '<option value="'.$actor->id.'" selected> '.$actor->actor_name.' </option>';
                                                } else {
                                                echo '<option value="'.$actor->id.'"> '.$actor->actor_name.' </option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <? //} ?>
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Thumbnail</div>
                                    <div class="panel-body">
                                      <div class="col-lg-6">
                                         <input type="file" name="video_thumbnail" class="form-control border-input">
                                      </div>
                                      <div class="col-lg-6">
                                         <img src="<?=$thumImg?>" width="100px" height="auto">
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Video Poster</div>
                                    <div class="panel-body">
                                      <div class="col-lg-6">
                                         <input type="file" name="video_poster" class="form-control border-input">
                                      </div>
                                      <div class="col-lg-6">
                                         <img src="<?=$postImg?>" width="100px" height="auto" >
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Imdb ID</div>
                                    <div class="panel-body"> <input type="text" name="imdb_id" value="<?php echo $movie->imdbid;?>" class="form-control border-input" required> </div>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Alternative Title</div>
                                    <div class="panel-body"> <input type="text" name="alternative_titles" value="<?php echo $movie->alternative_titles;?>" class="form-control border-input"> </div>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Rating</div>
                                    <div class="panel-body"> <input type="text" name="movie_rating" value="<?php echo $movie->movie_rating;?>" class="form-control border-input"> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Is Series</div>
                                    <div class="panel-body">
                                      <select name="is_series" class="form-control border-input">
                                        <option value="0" <?php echo ($movie->is_series == 0 ? 'selected' : false); ?>>No</option>
                                        <option value="1" <?php echo ($movie->is_series == 1 ? 'selected' : false); ?>>Yes</option>
                                      </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Movie Year</div>
                                    <div class="panel-body"> <input type="text" name="movie_year" value="<?php echo $movie->movie_year;?>" class="form-control border-input"> </div>
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
                                            <select id="video_format" name="video_format" class="form-control" onchange="changeVideoFormat()">
                                                <option value="0" <?php echo ($movie->is_embed == 0 ? 'selected' : false); ?>> Video file </option>
                                                <option value="1" <?php echo ($movie->is_embed == 1 ? 'selected' : false); ?>> Embed code </option>
                                            </select>
                                            <br>
                                            <div id="video_file_div" <?php echo ($movie->is_embed == 1 ? 'style="display:none;"' : false); ?>>
                                                <div class="form-group">
                                                    <label> Video URL (MP4)</label>
                                                    <input type="text" name="video_file_mp4" class="form-control" value="<?=$movie->movie_source?>">
                                                    <p class="help-block"> <b class="text-danger"> <i class="fa fa-youtube-play"></i> YouTube </b> links are supported </p>
                                                </div>
                                            </div>
                                            <div id="video_embed_div" <?php echo ($movie->is_embed == 0 ? 'style="display:none;"' : false); ?>>
                                                <div class="form-group">
                                                    <label> Embed code </label>
                                                    <textarea name="video_embed_code" class="form-control"><?=$movie->movie_source?></textarea>
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
                                            <label>Featured</label>
                                            <select name="is_featured" class="form-control">
                                                <option value="1" <?php echo ($movie->is_featured == 1 ? 'selected' : false); ?>> True </option>
                                                <option value="0" <?php echo ($movie->is_featured == 0 ? 'selected' : false); ?>> False </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Who can watch?</label>
                                            <select name="free_to_watch" class="form-control">
                                                <option value="0" <?php echo ($movie->free_to_watch == 0 ? 'selected' : false); ?>> Only subscribers </option>
                                                <option value="1" <?php echo ($movie->free_to_watch == 1 ? 'selected' : false); ?>> Everyone </option>
                                            </select>
                                        </div>
                                        <?php if($admin->settings->kid_profiles == 1) { ?>
                                        <div class="form-group">
                                            <label>Child-Friendly</label>
                                            <select name="is_kid_friendly" class="form-control">
                                                <option value="0" <?php echo ($movie->is_kid_friendly == 0 ? 'selected' : false); ?>> False </option>
                                                <option value="1" <?php echo ($movie->is_kid_friendly == 1 ? 'selected' : false); ?>> True </option>
                                            </select>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                        <button type="submit" name="save" class="btn btn-success btn-fill btn-wd">Save</button>
                      </div>
                    </div>
                      </form>
                    </div>
                    <div class="clearfix"></div>
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
