<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);
$page = (isset($_GET["page"]))?$_GET["page"]:1;
$search = (isset($_GET["s"]))?$_GET["s"]:'';
$movies = $admin->getFilms($search,$page);
$WHERE = '';
if($search != ''){
    $WHERE = " AND (movie_name LIKE '%$search%' OR movie_plot LIKE '%$search%' OR imdbid LIKE '%$search%' OR alternative_titles LIKE '%$search%')";
}
$totalMovies = $db->query("SELECT COUNT(*) FROM movies where from_type='film' $WHERE")->fetch_array()[0];
//$totalMovies = $db->query("SELECT COUNT(*) FROM movies");
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
                    <a class="navbar-brand" href="#">
                        Films
                        <a href="add_films.php" class="btn btn-success btn-fill btn-xs pull-left" style="margin-top:21px;">
                            <i class="ti-plus"></i> Add Films
                        </a>
                        <a href="bulk-film-add.php" class="btn btn-success btn-fill btn-xs pull-left" style="margin-top:21px;">
                            <i class="ti-video-clapper"></i> Bulk Add Films
                        </a>
                    </a>
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
             <?php if(isset($_GET['error'])) { ?> <div class="alert alert-danger"> <?=$_GET['error']?> </div> <? } ?>
             <?php if(isset($_GET['not_added']) && $_GET['not_added']!='') { ?> <div class="alert alert-danger"> IMDB ID's not added : <b><?=$_GET['not_added']?></b> </div> <? } ?>
               <?php if(isset($_GET['ae']) && $_GET['ae']!='') { ?> <div class="alert alert-danger"> IMDB ID's already added : <b><?=$_GET['ae']?></b> </div> <? } ?>
              <?php if(isset($_GET['success'])) { ?> <div class="alert alert-success">Successfully created.</div> <? } ?>
            <div class="content">
                <div class="col-md-4">
                    <button type="button" class="btn btn-success btn-fill" id="eDDB">Enable Delete</button>
                </div>
                <div class="col-md-4 pull-right">
                    <form method="get">
                        <div class="input-group">
                            <input type="text" name="s" class="form-control" value="<?=$search?>" placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit"><span class="ti-search"></span></button>
                            </span>
                        </div><!-- /input-group -->
                    </form>
                </div>
                <div class="clearfix"></div>
                <hr />
                <div class="container-fluid">
                 <?php
                 if($movies->num_rows >= 1) {
                    while($movie = $movies->fetch_object()) {
                        $postImg = $admin->getDomain().'/uploads/poster_images/'.$movie->movie_poster_image;
                        if($movie->movie_poster_image == ''){
                            $postImg =  $admin->getDomain().'/images/default-image.png';
                        }
                        $My = $movie->movie_year?'('.$movie->movie_year.')':'';
                        echo '<div class="video-card">';
                        echo '
                        <div class="poster"
                        style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$postImg.'\');
                        background-image: -moz-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$postImg.'\');
                        background-image: -o-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$postImg.'\');
                        background-image: -ms-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$postImg.'\');
                        background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$postImg.'\')">
                        </div>';


                        echo '<div class="edit"><a href="edit_video.php?id='.$movie->id.'&is_film=true"><i class="ti-pencil"></i></a></div>';
                        echo '<div class="delete"><a href="delete_video.php?id='.$movie->id.'&is_film=true"><i class="ti-trash"></i></a></div>';
                        if($movie->all_starcast == "no") {

                            echo '<div style="position: absolute;bottom: 7px;right: 75px;border-right: 1px solid #66615B;padding-right: 10px;"><a href="add_starcast.php?id='.$movie->imdbid.'" title="Add Full Starcast"><i class="fa fa-users" style="color:white;" ></i></a></div>';
                        }
?>
                        <a href="../titles.php?id=<?=$movie->id?>" target="_blank"><div class="title"><?=$movie->movie_name.' '.$My ?> </div></a>
        <?php                echo '</div>';
                    }
                    $ouput = '<div class="clearfix"></div>
                    <div class="text-right col-md-12">';
                    $ouput .= $admin->pagination($totalMovies,$page);
                    $ouput .= '</div>';
                    echo $ouput;
                }
                ?>
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
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script src="assets/js/theme.js"></script>
</html>
