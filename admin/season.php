<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);
$fid = $_GET['id'];
$M = $db->query("SELECT movie_name,movie_year, movie_poster_image FROM movies WHERE id=$fid")->fetch_object();
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
                        Episodes
                        <a href="add_episode.php" class="btn btn-success btn-fill btn-xs pull-left" style="margin-top:21px;"> <i class="ti-plus"></i> Add Episode </a> </a>
                        <a href="bulk-imdb-add.php?sid=<?=$_GET['id']?>" class="btn btn-success btn-fill btn-xs pull-left" style="margin-top:21px;">
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
            <div class="content">
							<div class="col-md-4">
									<button type="button" class="btn btn-success btn-fill enabledButton" id="eDDBB">Enable Delete</button>
							</div>
                <div class="container-fluid">
                 <div class="row">
                       <?php $index_url=str_replace("/film","",base_url());?>
                  <h1 class="season-title" ><a href="<?=$index_url?>/titles.php?id=<?=$fid?>" target="_blank"><?=$M->movie_name?> (<?=$M->movie_year?>) </a></h1>
                  <div class="col-xs-12 col-sm-6 col-md-6">

                    <div class="video-card"></div>
                  </div>
                </div>
                <div class="row">
                 <?php

                 $SS = $db->query("SELECT * FROM seasons WHERE movie_id='".$fid."' order by season_number asc");
                 //print_r($season);
                
                 if($SS->num_rows >= 1) {
                    while($s = $SS->fetch_object()) {
                     $si = $s->id;
										 $sN = ($s->season_number < 10)?'0'.$s->season_number:$s->season_number;
                     $eS = $db->query("SELECT * FROM episodes WHERE season_id='".$si."' ORDER BY id ASC");
                    
                    ?>
                    <div class="clearfix"></div>
                    <div class="col-sm-12 p-h  season_ep"><h4><?php echo "Season ".$sN;?><span style="color:#e20a0a;font-size: 15px;"> (<?php echo $eS->num_rows." Episodes";?>)</span><i class="fa fa-chevron-down"></i></h4></div>

										<div class="p-b col-sm-12">
											 <div class="delete" style="display:none;"><a href="delete_episode.php?rid=<?=$fid?>&season_id=<?=$si?>&season=true" class="btn btn-success btn-fill">Delete Season</i></a></div>
											<div class="col-sm-12">
                      <?php

                      if($eS->num_rows >= 1) {
												$counter = 1;
                        while($e = $eS->fetch_object()) 
                        {
                            if($e->episode_name!='')
                            {
                              $c = $e->episode_name;
                            }
                            else
                            {
                              $cd = ($counter < 10)?'0'.$counter:$counter;
                              $c = 'Episode '. $cd;
                            }
                            $f= $e->season_sub_id;
                            $eI = $admin->getDomain().'/uploads/episodes/'.$e->episode_thumbnail;
                            $imgtype  = @exif_imagetype($eI);
                            if ($imgtype === false) {
                                $eI = $admin->getDomain().'/uploads/poster_images/'.$M->movie_poster_image;
                            }
												    if(strpos($eI,"maxresdefault-1.jpg") !== false){ //$eI == ''
		                           $eI = getposterImg($e->movie_id);
		                        }
		                        echo '<div class="col-xs-12 col-sm-6 col-md-6"><div class="video-card">';
		                        echo '
		                        <div class="poster"
		                        style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$eI.'\');
		                        background-image: -moz-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$eI.'\');
		                        background-image: -o-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$eI.'\');
		                        background-image: -ms-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$eI.'\');
		                        background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$eI.'\')">
		                        </div>';
		                        echo '<div class="edit"><a href="edit_episode.php?id='.$e->id.'"><i class="ti-pencil"></i></a></div>';
		                        echo '<div class="delete"><a href="delete_episode.php?id='.$e->id.'&season_id='.$e->season_id.'&rid='.$fid.'"><i class="ti-trash"></i></a></div>';
		                        echo '<a href="../episode.php?id='.$e->id.'" target="_blank">
															<div class="title">
																'.$M->movie_name.' ('.$M->movie_year.') <br />
                                Season '.$sN.' | Episode '.$f.' <br />
                                 '.$c.'
															</div>
														</a>';
		                        echo '</div></div>';
														$counter++;
		                      }
                    	}
											echo '</div>
											</div>';
                    }
                }
                ?>


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
<style>
  .jumbotron h2{
    margin-left: 20px;
  }
  .jumbotron{
    background-color: #98cb00;
  }
</style>
<script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap-checkbox-radio.js"></script>
<script src="assets/js/chartist.min.js"></script>
<script src="assets/js/bootstrap-notify.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script src="assets/js/theme.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('.p-h h4').on('click',function(e){
			e.preventDefault();
			var c = $(this), p = c.parents('.p-h');
			if(!p.hasClass('a-ph')){
				$('.p-b').removeClass('a-pb');
				$('.p-h').removeClass('a-ph');
				$('.p-b').slideUp(500);
				p.addClass('a-ph');
				p.next('.p-b').slideDown(500);
				p.next('.p-b').addClass('a-pb');
			}
			else{
				$('.p-b').slideUp(500);
				$('.p-b').removeClass('a-pb');
				$('.p-h').removeClass('a-ph');
				p.next('.p-b').slideUp(500);

			}
		})

		$("#eDDBB").on("click",function(e){
			e.preventDefault();
			e.stopPropagation();
			 var current = $(this);
			 if(current.hasClass('enabledButton')){
				 current.removeClass('enabledButton');
				 current.text('Enable Delete');
				 $(".delete").hide();
			 }else{
         current.addClass('enabledButton');
				 current.text('Disable Delete');
				 $(".delete").show();
			 }
		});
	});
</script>
</html>
