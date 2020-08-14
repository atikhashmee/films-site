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
$s = isset($_GET["s"])?$_GET["s"]:'';
if(isset($_GET["s"])){
  ?>
<style>
#p {
    display: block;
}

#q {
    display: none !important;
}
</style>
<?php
}
else{
   ?>
<style>
#p {
    display: none;
}

#q {
    display: block;
}
</style>
<?php
}
$page = (isset($_GET["page"]))?$_GET["page"]:1;
$search = (isset($_GET["s"]))?$_GET["s"]:'';
//$episodes = $admin->getEpisodes($search,$page);
$limit = 50;
//$movies = $admin->getFilms($search,$page);
$start_from = ($page-1) * $limit;
$WHERE = '';
if($search != ''){
	$WHERE = " AND (movie_name LIKE '%$search%' OR movie_plot LIKE '%$search%' OR imdbid LIKE '%$search%' OR alternative_titles LIKE '%$search%')";
}
$episodes = $db->query("SELECT * FROM movies WHERE from_type='film' $WHERE AND (movie_genres=25 OR is_series=1) ORDER BY id DESC LIMIT $start_from, $limit");
//debug($episodes);
$totalEpisodes = $db->query("SELECT COUNT(*) FROM movies WHERE from_type='film' AND (movie_genres=25 OR is_series=1) $WHERE")->fetch_array()[0];
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
    <link href="assets/css/theme.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
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
                            Episodes
                        </a>
                        <a href="add_iepisode.php" class="btn btn-success btn-fill btn-xs pull-left"
                            style="margin-top:21px;">
                            <i class="ti-plus"></i> Add Episode
                        </a>
                        <a href="bulk-imdb-add.php" class="btn btn-success btn-fill btn-xs pull-left"
                            style="margin-top:21px;">
                            <i class="ti-plus"></i> Bulk Add Episodes
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
            <?php if(isset($_GET['error'])) { ?> <div class="alert alert-danger"> <?=$_GET['error']?> </div>
            <? } ?>
            <?php if(isset($_GET['ae']) && $_GET['ae']!='') { ?> <div class="alert alert-danger"> Already Exists
                <?=$_GET['ae']?>.</div>
            <? } ?>
            <?php if(isset($_GET['success'])) { ?> <div class="alert alert-success">Successfully created.</div>
            <? } ?>
            <div class="content">
                <div class="col-md-4 pull-right">
                    <form method="get">
                        <div class="input-group">
                            <input type="text" name="s" class="form-control" value="<?=$search?>"
                                placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit"><span class="ti-search"></span></button>
                            </span>
                        </div><!-- /input-group -->
                    </form>
                </div>
                <div class="clearfix"></div>
                <hr />
                <?php /*<div class="container-fluid" id="p">
				 <?php
				 $a = $db->query("SELECT * FROM movies WHERE from_type='film' and movie_name LIKE '$s%' AND is_series=1");
				 if($a->num_rows >= 1) {
					while($movie = $a->fetch_object()) {
						$eS = $db->query("SELECT * FROM episodes WHERE movie_id='".$movie->id."' ORDER BY id ASC");
				      	$SS = $db->query("SELECT * FROM seasons WHERE movie_id='".$movie->id."'");
						$postImg = $admin->getDomain().'/uploads/poster_images/'.$movie->movie_poster_image;

						if($movie->movie_poster_image == ''){
							$postImg =  $admin->getDomain().'/images/default-image.png';
						}
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

							echo '<div style="    position: absolute;bottom: 7px;right: 75px;border-right: 1px solid #66615B;padding-right: 10px;"><a href="add_starcast.php?id='.$movie->imdbid.'" title="Add Full Starcast"><i class="fa fa-users" style="color:white;" ></i></a></div>';
						}

						echo '<a href="season.php?id='.$movie->id.'"><div class="title">'.$movie->movie_name.'<div style="color:red;">'.$SS->num_rows.' Seasons</div><div style="color:red;">'.$eS->num_rows.' Episodes</div></div></a>';
						echo '</div>';
					}
					$output = '<div class="clearfix"></div>
					<div class="text-right col-md-12">';
					$output .= $admin->pagination($totalMovies,$page);
					$output .= '</div>';
					echo $output;
				}
				else{
				  echo "No data available.";
				}
				?>
            </div>*/?>
            <div class="container-fluid" style="display: block;">
                <?php
					$start_from = ($page-1) * $limit;
				 //$mov = $db->query("SELECT * FROM movies WHERE from_type='film' AND (movie_genres=25 OR is_series=1) LIMIT $start_from, $limit");
				 if($episodes->num_rows > 0) {
				  while($episode = $episodes->fetch_assoc()) {
				   $eS = $db->query("SELECT * FROM episodes WHERE movie_id='".$episode['id']."' ORDER BY id ASC");
				      $SS = $db->query("SELECT * FROM seasons WHERE movie_id='".$episode['id']."'");
						if($episode['movie_thumb_image'] != ''){
							$episdeImg = $admin->getDomain().'/uploads/masonry_images/'.$episode['movie_thumb_image'];
						}
						echo '<div class="video-card">';
						echo  '

						<div class="poster"
						style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$episdeImg.'\');
						background-image: -moz-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$episdeImg.'\');
						background-image: -o-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$episdeImg.'\');
						background-image: -ms-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$episdeImg.'\');
						background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$episdeImg.'\')">
						</div>';
						echo '<a href="season.php?id='.$episode['id'].'"><div class="title">'.$episode['movie_name']. ' ('.$episode['movie_year'].')<div style="color:red;">'.$SS->num_rows.' Seasons</div><div style="color:red;">'.$eS->num_rows.' Episodes</div></div></a>';
						echo '</div>';
					}
					$output = '<div class="clearfix"></div>
                    <div class="text-right col-md-12">';
                    $output .= $admin->pagination($totalEpisodes,$page,$limit);
                    $output .= '</div>';
                    echo $output;
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
<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script> -->
<script src="assets/js/theme.js"></script>

</html>