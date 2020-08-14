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
$actors = $admin->getActors();

if(isset($_POST['add'])) {
	
	
	
	require 'class_IMDb.php';
	$imdb = new IMDb(true);
	$imdb->summary=false;
	$movie = $imdb->find_by_id(trim($_POST['IMDBID']));
	print_r($movie);
	if($movie->response_msg != "Fail") {
	
	$checkmovie = "select * from movies where movie_name like '%".$db->real_escape_string($movie->title)."%'";
	$checkmovie_r = $db->query($checkmovie);

	if ($checkmovie_r->num_rows > 0) {
		header('Location: films.php?error=Movie Already Exists!');
		exit;
	}
	
	
	
/*	if($video_name != "") {
        $new_file_name = md5(mt_rand()).'_video.mp4';
		$url = $video_name;
		$img = '../uploads/masonry_images/'.$new_file_name;
		file_put_contents($img, file_get_contents($url));
    }
*/	
	

// for category	
	$genres = $movie->genres;
	foreach($genres as $category) {
		$sql = "select * from genres where genre_name like '%".$category."%'";
		$result = $db->query($sql);
	
		if ($result->num_rows <= 0) {
			$newinsert = $db->query("INSERT INTO genres(genre_name,is_kid_friendly) VALUES ('".$category."','".$is_kid_friendly."')");
			$gener_id = $db->insert_id;
		}
		else {
			while($row = $result->fetch_assoc()) {
				$gener_id = $row['id'];
			}
		}
		$make_geners[] = $gener_id;
	}
	$genres_video = implode(",",$make_geners);


	// for actors
	$cast_summary = $movie->cast_summary;
	foreach($cast_summary as $cast) {
		$cname_array = $cast->name;
		$name_c = $cname_array->name;
		$nconst = $cname_array->nconst;
		
		$im_url_c = $cname_array->image->url;
		if(isset($im_url_c) && $im_url_c != "") {
			$extension = strtolower(end(explode('.',$im_url_c)));
			$c_actor = md5(mt_rand()).'_actor.'.$extension;
			$ur11 = $im_url_c;
			$img11 = '../uploads/actors/'.$c_actor;
			file_put_contents($img11, file_get_contents($ur11));
		}
		else {
			$c_actor = "";
		}
		$sql1 = "select * from actors where actor_name like '%".$name_c."%'";
		$result1 = $db->query($sql1);
		if ($result1->num_rows <= 0) {
			$db->query("INSERT INTO actors (actor_name,actor_picture,actor_nconst) VALUES ('".$name_c."','".$c_actor."','".$nconst."')");
			$actor_id = $db->insert_id;
		}
		else {
			while($row1 = $result1->fetch_assoc()) {
				$actor_id = $row1['id'];
				if($row1['actor_nconst'] == "") {
					$db->query("update actors set actor_nconst = '".$nconst."' where id = '".$actor_id."'");
				}
			}
		}
		$make_actors[] = $actor_id;
	}
	//$makeactors = implode(",",$make_actors);


	$description = $movie->plot->outline;
	$video_name = $db->real_escape_string($movie->title);
    $video_description = $db->real_escape_string($description);
    $video_categories = $genres_video;

    $is_kid_friendly = 0;
	
	$videurl = $movie->trailer->encodings;
	foreach($videurl as $videos_u) {
		$video_file_mp4 = $videos_u->url;
		break;
	}
	$source = $video_file_mp4;
	if(isset($_POST['video_url']) && trim($_POST['video_url']) != "") {
		$video_format =  $_POST['video_format'];
		$source = $_POST['video_url'];
	}
	else {
		$video_format =  0;
		$source = $video_file_mp4;
	}
	
	
	$im_url = $movie->image->url;
	if($im_url != "") {
		$extension = strtolower(end(explode('.',$im_url)));
        $new_file_name_2 = md5(mt_rand()).'_poster.'.$extension;
        $new_file_name = md5(mt_rand()).'_poster2.'.$extension;
		$url = $im_url;
		
		$img = '../uploads/masonry_images/'.$new_file_name;
		$img2 = '../uploads/poster_images/'.$new_file_name_2;
		file_put_contents($img, file_get_contents($url));
		file_put_contents($img2, file_get_contents($url));
    }
	
	 
   // $video_categories = implode(',',$video_categories);
      // Poster Photo
    
    $db->query("INSERT INTO movies (movie_name,movie_plot,movie_genres,movie_poster_image,movie_thumb_image,movie_plays,movie_source,movie_year,from_type,imdbid,is_embed)
     VALUES ('".$video_name."','".$video_description."','".$video_categories."','".$new_file_name_2."','".$new_file_name."','0','".$source."','".$movie->year."','film','".$_POST['IMDBID']."','".$video_format."')");
    $actors = $make_actors;
    $movie_id = $db->insert_id;
    foreach($actors as $actor => $actor_id) {
        $db->query("INSERT INTO actor_relations(movie_id,actor_id) VALUES ('".$movie_id."','".$actor_id."')");
    }
	
	
	
	if(isset($movie->rating)) {
		$rating = round((($movie->rating)/2),1);
		$db->query("INSERT INTO ratings(movie_id,user_id,rating) VALUES ('".$movie_id."','22','".$rating."')");
	}
	
    header('Location: films.php?success=1');
    exit;
	}
	else {
		header('Location: films.php?error=Something Not Right!');
		exit;
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
 <title>Muviko Admin</title>
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
        <div class="sidebar" data-background-color="black" data-active-color="success">
         <div class="sidebar-wrapper">
            <div class="logo">
                <a href="#" class="simple-text">
                    <img src="assets/images/logo.png">
                </a>
            </div>

            <ul class="nav">
                <li>
                    <a href="dashboard.php">
                        <i class="ti-panel"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li>
                    <a href="users.php">
                        <i class="ti-user"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li>
                    <a href="categories.php">
                        <i class="ti-view-list"></i>
                        <p>Categories</p>
                    </a>
                </li>
                <li class="active">
                    <a href="films.php">
                        <i class="ti-video-clapper"></i>
                        <p>Films</p>
                    </a>
                </li>
				<li>
                    <a href="videos.php">
                        <i class="ti-video-clapper"></i>
                        <p>Videos</p>
                    </a>
                </li>
                <li>
                    <a href="episodes.php">
                        <i class="ti-video-clapper"></i>
                        <p>Episodes</p>
                    </a>
                </li>
                <li>
                    <a href="actors.php">
                        <i class="ti-star"></i>
                        <p>Actors</p>
                    </a>
                </li>
                <li>
                    <a href="codes.php">
                        <i class="ti-ticket"></i>
                        <p>Codes</p>
                    </a>
                </li>
                <li>
                    <a href="pages.php">
                        <i class="ti-file"></i>
                        <p>Pages</p>
                    </a>
                </li>
                <li>
                    <a href="themes.php">
                        <i class="ti-palette"></i>
                        <p>Themes</p>
                    </a>
                </li>
                <li>
                    <a href="settings.php">
                        <i class="ti-settings"></i>
                        <p>Settings</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
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
                    <h4 class="title">New Film</h4>
                </div>
				<div class="overlay">
					<div id="loading-img"></div>
				</div>
                <div class="content">
                   
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading panel-title">IMDB Film ID</div>
                                    <div class="panel-body"> <input type="text" name="IMDBID" id="imdbidnew" class="form-control border-input" placeholder="Enter IMDB Film ID for this film" required> </div>
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
