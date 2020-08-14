<?php
$featured_movie = $db->query("SELECT * FROM actors WHERE actor_nconst='".$name."'");
$data = $featured_movie->fetch_object();

			$relations =  $db->query("SELECT * FROM actor_relations WHERE actor_id='".$data->id."' ORDER BY id ASC");
while($relation = $relations->fetch_object()) {
	$movies = $db->query("SELECT * FROM movies WHERE id='".$relation->movie_id."'");
	$movies = $movies->fetch_object();
	$movie_lot[] = $movies;
}



require 'class_IMDb.php';
$imdb = new IMDb(true);
$imdb->summary=false;
$movie = $imdb->person_by_id($name);

?>

<div class="movie-page-image">
<nav class="navbar navbar-fixed-top navbar-ct-transparent" role="navigation-demo">
 <div class="container">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="<?=$muviko->getDomain()?>/index.php"><img src="<?=THEME_PATH?>/assets/images/logo.png"></a>
  </div>
  <div class="collapse navbar-collapse" id="navigation-example-2">
    <ul class="nav navbar-nav navbar-left">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?=$muviko->translate('Browse')?> <b class="caret"></b> </a>
        <ul class="dropdown-menu dropdown-menu-right">
          <li><a href="<?=$muviko->getDomain()?>/videos.php"><?=$muviko->translate('Videos')?></a></li>
          <li><a href="<?= $muviko->getDomain()?>/series.php"><?= $muviko->translate('Series')?></a></li>
        </ul>
      </li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <form action="<?=$muviko->getDomain()?>/search.php" method="get" class="navbar-form navbar-left" style="display:none;" role="search">
        <div class="form-group">
         <div class="input-group search-input">
          <span class="input-group-addon" id="basic-addon1"><i class="ti-search"></i></span>
          <input type="text" name="q" class="form-control border-input" placeholder="<?=$muviko->translate('Title')?>">
        </div>
      </div>
    </form>
    <li id="search-toggle"> <a href="#" onclick="showSearch();"> <i class="ti-search"></i> &nbsp <span><?=$muviko->translate('Search')?></span> </a> </li>
    <?php if(!$muviko->verifySession(false)) { ?>
    <li>
      <a href="#" class="btn btn-danger btn-fill" data-toggle="modal" data-target="#login"><?=$muviko->translate('Sign_In')?></a>
    </li>
    <? } else { ?>
    <li class="dropdown">
      <a href="#" class="profile-photo dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <div class="profile-photo-small">
          <img src="<?=$muviko->getProfilePicture()?>" class="img-circle img-responsive img-no-padding">
        </div>
      </a>
      <?php if(!$muviko->isKid()) { ?>
      <ul class="dropdown-menu dropdown-menu-right">
        <?php if($muviko->verifyAdmin(false)) { ?>
        <li><a href="<?=$muviko->getDomain()?>/admin/index.php"><?=$muviko->translate('Admin')?></a></li>
        <li class="divider"></li>
        <? } ?>
        <li><a href="<?= $muviko->getDomain() ?>/my_list.php"><?= $muviko->translate('My_List') ?></a></li>
        <li><a href="<?= $muviko->getDomain() ?>/select_profile.php"><?= $muviko->translate('Switch_Profile') ?></a></li>
        <li><a href="<?= $muviko->getDomain() ?>/settings.php"><?= $muviko->translate('Settings') ?></a></li>
        <li><a href="<?=$muviko->getDomain()?>/logout.php"><?=$muviko->translate('Logout')?></a></li>
      </ul>
      <? } ?>
    </li>
    <?php if($muviko->isKid()) { ?>
    <a href="select_profile.php" class="btn btn-danger btn-fill btn-sm exit-kids"><?=$muviko->translate('Exit_Kids')?></a>
    <? } ?>
    <? } ?>
  </ul>
</div>
</div>
</nav>
<div class="player-single-outter container" style="height:50px">
</div>
<div class="player-single-outter container">
	<div class="col-lg-3 pull-left">
  <?php
    $picture = $muviko->getDomain().'/uploads/actors/'.$data->actor_picture;
    $style = '';
    if($data->actor_picture == ''){
        $picture = $muviko->getDomain().'/images/default-user.png';
    }
  ?>
		<div id="name-overview-widget"><img style="width:100%;" src="<?=$picture?>" />
		</div>
	</div>
	<div class="col-lg-8 pull-right">
		<div class="main-info">
			<h1 class="title"><?=$movie->name?></h1>
			<p class="plot"> 
			 <?=$movie->bio?>
		   </p>
		 </div>
	</div>
</div>
<div class="clearfix"></div>
<div class="movie" onclick="hideSearch();">
  <div class="container" style="padding:0px;">
    <div class="col-lg-8 pull-right">
     	<div class="action-buttons pull-right">
      
      
      <p class="about">
        <?php
        echo '<b>'.'Birthday'.'</b>';
        echo ': ';
       echo  $movie->birth->date->normal;
        ?>
      </p> 
	  <p class="about"> 
        <?php 
        echo '<b>'.'Birth Place'.'</b>';
        echo ': ';
       echo  $movie->birth->place;
		
        ?>
      </p>
    </div> 
   	</div>
   
  <div class="clearfix"></div>
  <ul class="tabs nav nav-pills nav-pills-danger">
    <li class="active">
      <a href="#" onclick="loadCast(); return false;">
        <?php echo 'Films'; ?>
      </a>
    </li>
  </ul>
  <div class="dark-section">
    <div class="details-container">
      <div class="row">
        <div class="cast" >
			<?php

			 foreach($movie_lot as $choose_m) { if($choose_m->movie_name != "") { ?>
         	<div class="episode col-md-4">
            <a style="color:white;" href="video/<?=$choose_m->id?>">
              <span><img src="<?=UPLOADS_PATH?>/poster_images/<?=$choose_m->movie_poster_image?>"></span>
				      <p class="title"><?=$choose_m->movie_name?></p></a>
			      </div>
			  <?php } } ?>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
