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
$movies = $admin->getMovies();


$episode = $admin->getEpisode($id);
$actors = $db->query("SELECT * FROM actors");
$season = $admin->getSeason($episode->season_id);
if(isset($_POST['save'])) {
    $episode_name = $_POST['episode_name'];
    $episode_description = $db->real_escape_string($_POST['episode_description']);
    $episode_number = $_POST['episode_number'];
    $video_format = $_POST['video_format'];
    $video_embed_code = $_POST['video_embed_code'];
    $video_file_mp4 = $_POST['video_file_mp4'];
    $season_number = $_POST['season_number'];
    $actors = $_POST['actors'];
    $actors = implode(',', $actors);
    $rating = $_POST['rating'];
    if($video_format == 1) {
        $source = $video_embed_code;
    } else {
        $source = $video_file_mp4;
    }
    // Thumbnail Photo
    if(isset($_FILES['episode_thumbnail']['name']) && $_FILES['episode_thumbnail']['size'] > 0) {
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
    } else {
        $new_file_name = $episode->episode_thumbnail;
    }
    $db->query("UPDATE episodes SET episode_name='".$episode_name."',episode_description='".$episode_description."',episode_number='".$episode_number."',
        episode_thumbnail='".$new_file_name."',episode_source='".$source."',is_embed='".$video_format."',actor_id='".$actors."',ratings='".$rating."' WHERE id='".$episode->id."'");
    $db->query("UPDATE seasons SET season_number='".$season_number."' WHERE id='".$season->id."'");
    header('Location: episodes.php?success=2');
    exit;
}

$moviePosters = $db->query("SELECT * FROM episodes WHERE id='".$id."'");
$moviePoster = $moviePosters->fetch_object();
    $episdeImg = $admin->getDomain().'/uploads/episodes/'.$episode->episode_thumbnail;
  if(strpos($episdeImg,"maxresdefault-1.jpg") !== false){
    $episdeImg = getposterImg($moviePoster->movie_id);
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
                    <h4 class="title"><?=$episode->episode_name?></h4>
                </div>
                <div class="content">
                    <?php if(isset($error)) { ?> <div class="alert alert-danger"> <?=$error?> </div> <? } ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Video</div>
                                    <div class="panel-body">
                                        <select name="episode_id" class="form-control" required disabled>
                                            <?php
                                            while($movie = $movies->fetch_object()) {
                                                if($movie->id = $episode->movie_id) {
                                                    echo '<option value="'.$movie->id.'" selected> '.$movie->movie_name.' </option>';
                                                } else {
                                                    echo '<option value="'.$movie->id.'"> '.$movie->movie_name.' </option>';
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
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Episode Name</div>
                                    <div class="panel-body"> <input type="text" name="episode_name" class="form-control border-input" placeholder="Enter a name for this episode" value="<?=$episode->episode_name?>" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Episode IMDB ID Number</div>
                                    <div class="panel-body"> <input type="text" name="episode_number" class="form-control border-input" placeholder="Enter a number for this episode" value="<?=$episode->episode_number?>" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Season Number</div>
                                    <div class="panel-body"> <input type="text" name="season_number" class="form-control border-input" placeholder="What season does this episode belong to?" value="<?=$season->season_number?>" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Episode Description</div>
                                    <div class="panel-body"> <textarea id="editor" name="episode_description" rows="5" class="form-control" placeholder="Enter a description/plot for this episode" required><?=$episode->episode_description?></textarea> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Episode Thumbnail</div>

                                    <div class="panel-body">
                                      <div class="col-md-6">
                                        <input type="file" name="episode_thumbnail" class="form-control border-input">
                                      </div>
                                      <div class="col-md-6">
                                        <img src="<?=$episdeImg?>" width="100" height="auto" />
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
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
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Ratings</div>
                                    <div class="panel-body"> <input type="text" name="rating" class="form-control border-input" value="<?php echo $episode->ratings;?>" placeholder="Rating" required> </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">Episode Source</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label> Episode Format </label>
                                            <div class="clearfix"></div>
                                            <select id="video_format" name="video_format" class="form-control" onchange="changeVideoFormat()">
                                                <option value="0" <?php echo ($episode->is_embed == 0 ? 'selected' : false); ?>> Video file </option>
                                                <option value="1" <?php echo ($episode->is_embed == 1 ? 'selected' : false); ?>> Embed code </option>
                                            </select>
                                            <br>
                                            <div id="video_file_div" <?php echo ($episode->is_embed == 1 ? 'style="display:none;"' : false); ?>>
                                                <div class="form-group">
                                                    <label> Video URL (MP4)</label>
                                                    <input type="text" name="video_file_mp4" class="form-control" value="<?=$episode->episode_source?>">
                                                    <p class="help-block"> <b class="text-danger"> <i class="fa fa-youtube-play"></i> YouTube </b> links are supported </p>
                                                </div>
                                            </div>
                                            <div id="video_embed_div" <?php echo ($episode->is_embed == 0 ? 'style="display:none;"' : false); ?>>
                                                <div class="form-group">
                                                    <label> Embed code </label>
                                                    <textarea name="video_embed_code" class="form-control"><?=$episode->episode_source?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="save" class="btn btn-success btn-fill btn-wd">Save</button>
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
