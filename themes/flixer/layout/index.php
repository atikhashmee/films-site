<?php
  $picture = UPLOADS_PATH ."/poster_images/".$featured_movie->movie_poster_image;
  $logo_image = THEME_PATH.'/assets/images/logo.png';
?>
<div class="home-page-image"
style="
  background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?= $picture ?>');
  background-image: -moz-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?= $picture?>');
  background-image: -o-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?= $picture?>');
  background-image: -ms-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?= $picture?>');
  background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?= $picture?>');
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
      <a class="navbar-brand" href="<?=$muviko->getDomain()?>/index.php"><img src="<?=$logo_image?>"></a>
    </div>
    <div class="collapse navbar-collapse" id="navigation-example-2">
      <ul class="nav navbar-nav navbar-left">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?= $muviko->translate('Browse') ?> <b class="caret"></b> </a>
          <ul class="dropdown-menu dropdown-menu-right">
            <li><a href="<?= $muviko->getDomain() ?>/videos.php"><?= $muviko->translate('Videos') ?></a></li>
            <li><a href="<?= $muviko->getDomain() ?>/series.php"><?= $muviko->translate('Series') ?></a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <form action="<?= $muviko->getDomain() ?>/search.php" method="get" class="navbar-form navbar-left" style="display:none;" role="search">
          <div class="form-group">
           <div class="input-group search-input">
            <span class="input-group-addon" id="basic-addon1"><i class="ti-search"></i></span>
            <input type="text" name="q" class="form-control border-input" placeholder="<?= $muviko->translate('Title') ?>">
          </div>
        </div>
      </form>
      <li id="search-toggle"> <a href="#" onclick="showSearch();"> <i class="ti-search"></i> &nbsp; <span><?= $muviko->translate('Search') ?></span> </a> </li>
      <?php if (!$muviko->verifySession(false)) { ?>
        <li>
          <a href="#" class="btn btn-danger btn-fill" data-toggle="modal" data-target="#login"><?= $muviko->translate('Sign_In') ?></a>
        </li>
      <? } else { ?>
      <li class="dropdown">
        <a href="#" class="profile-photo dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          <div class="profile-photo-small">
            <img src="<?= $muviko->getProfilePicture() ?>" class="img-circle img-responsive img-no-padding">
          </div>
        </a>
        <?php if (!$muviko->isKid()) { ?>
          <ul class="dropdown-menu dropdown-menu-right">
            <?php if ($muviko->verifyAdmin(false)) { ?>
            <li><a href="<?= $muviko->getDomain() ?>/admin/index.php"><?= $muviko->translate('Admin') ?></a></li>
            <li class="divider"></li>
            <? } ?>
            <li><a href="<?= $muviko->getDomain() ?>/my_list.php"><?= $muviko->translate('My_List') ?></a></li>
            <li><a href="<?= $muviko->getDomain() ?>/select_profile.php"><?= $muviko->translate('Switch_Profile') ?></a></li>
            <li><a href="<?= $muviko->getDomain() ?>/settings.php"><?= $muviko->translate('Settings') ?></a></li>
            <li><a href="<?= $muviko->getDomain() ?>/logout.php"><?= $muviko->translate('Logout') ?></a></li>
          </ul>
        <?php  } } ?>
      </li>
      <?php if ($muviko->isKid()) { ?>
        <a href="select_profile" class="btn btn-danger btn-fill btn-sm exit-kids"><?= $muviko->translate('Exit_Kids') ?></a>
      <?php 
        } 
      } 
      ?>
    </ul>
  </div>
</div>
</nav>
  <!-- <div class="container animated fadeIn" onclick="hideSearch();">
    <div class="home-movie-info">
      <div class="title"> <?= $featured_movie->movie_name ?> </div>
      <div class="star-rating"></div>
      <p class="plot">
       <?= $featured_movie->movie_plot ?>
     </p>
     <a href="<?= $muviko->getDomain() ?>/titles.php?id=<?= $featured_movie->id ?>" class="btn btn-danger btn-fill" style="margin-right:10px;">
       <i class="ti-control-play"></i>
       <span> <?= $muviko->translate('Watch_Now') ?> </span>
     </a>
     <?= $muviko->newAddListBtn($featured_movie->id, 'white') ?>
     <input type="hidden" id="movie_id" value="<?= $featured_movie->id ?>">
   </div>
 </div> -->
</div>
<!-- <div class="container animated fadeIn" onclick="hideSearch();">
  <div class="row">
      <?php echo '<div class="load-more-wrapper"><a href="#" class="load-prev"><i class="fa fa-angle-up"></i></a></div>'; ?>
    <div class="col-lg-12 load-content">
      <div class="loaded-content-slider-wrapper">
        <?php
       $l = $db->query("SELECT movie_genres FROM movies ORDER BY created_date DESC LIMIT 3");
          $data = array();
          foreach ($l as $value) {
            if(strpos($value['movie_genres'],',')===false){
                $data[] = $value['movie_genres'];
            }
            else{
                $D = explode(",",$value['movie_genres']);
                foreach ($D as $da) {
                $data[] =   $da;
               }
           }
          }
            $data = array_unique($data);
              foreach ($data as $arr) {
          $c_name = $db->query("SELECT genre_name FROM genres WHERE id='" . $arr . "'");

          $cname = $c_name->fetch_object();
          if ($arr) {
            $loadedItem = 'loaded-' . _gRS(6) . time();
            $category = $db->query("SELECT * FROM movies WHERE FIND_IN_SET('" . $arr . "', movie_genres) ORDER BY created_date DESC LIMIT 0, 8");
          
            if ($category->num_rows >= 1) {
              echo '<div class="home-section" mi="' . $arr . '" page="1">';
              $muviko->homeSectionHeading($cname->genre_name, 'category.php?id=' . $arr );
              echo '<div class="movie-slider-1 owl-theme owl-carousel ' . $loadedItem . '" id="' . $loadedItem . '">';
              while ($item = $category->fetch_object()) {
                $movie_rating = isset($item->movie_rating) ? $item->movie_rating : '';
                $muviko->newHomeMovieItem($item->id, $item->movie_thumb_image, $item->movie_name, $item->movie_year, $movie_rating, $item->is_series, $item->last_season, $item->watch);
              }
              echo '</div>';
              echo '<div class="o-n"><div class="o-prev"><i class="ti-angle-left icon-white"></i></div><div class="o-next"><i class="ti-angle-right icon-white"></i></div></div>';
              echo '</div>';
            }
          }
        }
        ?>
      </div>
    </div>
    <?php
      echo '<div class="load-more-wrapper"><a href="#" class="load-more"><i class="fa fa-angle-down"></i></a></div>';
    ?>
  </div>
</div> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<style type="text/css">
  .load-more-wrapper{
      width: 100%;
      text-align: center;
      clear: both;
  }
  .load-content{
    postition:relative;
    width:100%;
    display:block;
  }
  .load-content .loaded-content-slider-wrapper{
    position:absolute;
    width:100%;
    height:100%;
  }
  .load-more-wrapper .load-more, .load-more-wrapper .load-prev{
      font-size: 40px;
      display: inline-block;
      color: #FFF;
      width: 40px;
      height: 40px;
      line-height: 40px;
      text-align: center;
  }
  .load-more-wrapper .load-more i, .load-more-wrapper .load-prev i{
      display: block;
      width: 100%;
  }
  .load-content{
      /* height: 852px; */
      overflow: hidden;
  }
  .owl-carousel .owl-dots.disabled, .owl-carousel .owl-nav.disabled {
    display: block !important;
  }
</style>
