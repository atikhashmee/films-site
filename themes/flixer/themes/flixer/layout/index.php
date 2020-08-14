<div class="home-page-image" 
style="
background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=UPLOADS_PATH?>/poster_images/<?=$featured_movie->movie_poster_image?>');
background-image: -moz-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=UPLOADS_PATH?>/poster_images/<?=$featured_movie->movie_poster_image?>');
background-image: -o-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=UPLOADS_PATH?>/poster_images/<?=$featured_movie->movie_poster_image?>');
background-image: -ms-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=UPLOADS_PATH?>/poster_images/<?=$featured_movie->movie_poster_image?>');
background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=UPLOADS_PATH?>/poster_images/<?=$featured_movie->movie_poster_image?>');
);
">
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
  <div class="container animated fadeIn" onclick="hideSearch();">
    <div class="home-movie-info">
      <div class="title"> <?=$featured_movie->movie_name?> </div>
      <div class="star-rating"></div>
      <p class="plot"> 
       <?=$featured_movie->movie_plot?>
     </p>
     <a href="<?=$muviko->getDomain()?>/titles.php?id=<?=$featured_movie->id?>" class="btn btn-danger btn-fill" style="margin-right:10px;">
       <i class="ti-control-play"></i> 
       <span> <?=$muviko->translate('Watch_Now')?> </span>
     </a>
     <?=$muviko->newAddListBtn($featured_movie->id,'white')?>
     <input type="hidden" id="movie_id" value="<?=$featured_movie->id?>">
   </div>
 </div>
</div>
<div class="container animated fadeIn" onclick="hideSearch();">
  <div class="row">
    <div class="col-lg-12">
      <?php
      if($my_list->num_rows >= 1) {
        echo '<div class="home-section">';
        $muviko->homeSectionHeading($muviko->translate('My_List'),'my_list');
        echo '<div class="movie-slider-1 owl-theme">';
        while($list_item = $my_list->fetch_object()) {
          $list_item = $db->query("SELECT * FROM movies WHERE id='".$list_item->movie_id."' LIMIT 1");
          $list_item = $list_item->fetch_object();
          $muviko->newHomeMovieItem($list_item->id,$list_item->movie_thumb_image,$list_item->movie_name,$list_item->movie_year,$item->movie_rating,$list_item->is_series,$list_item->last_season);
        }
        echo '</div>';
        echo '</div>';
      }
      debug($genres);
      while($genre = $genres->fetch_object()) {
        $category = $db->query("SELECT * FROM movies WHERE FIND_IN_SET('".$genre->id."', movie_genres) ORDER BY id DESC");
        if($category->num_rows >= 1) {
          echo '<div class="home-section">';
          $muviko->homeSectionHeading($genre->genre_name,'category/'.$genre->id.'/'.strtolower($genre->genre_name));
          echo '<div class="movie-slider-1 owl-theme">';
          while($item = $category->fetch_object()) {
            
            $muviko->newHomeMovieItem($item->id,$item->movie_thumb_image,$item->movie_name,$item->movie_year,$item->movie_rating,$item->is_series,$item->last_season);
          }
          echo '</div>';
          echo '</div>';
        }
      }
      ?>
    </div>
  </div>
</div>