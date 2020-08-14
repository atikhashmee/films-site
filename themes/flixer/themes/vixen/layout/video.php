<div class="movie-page-image" style="
background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=UPLOADS_PATH?>/poster_images/<?=$movie->movie_poster_image?>');
background-image: -moz-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=UPLOADS_PATH?>/poster_images/<?=$movie->movie_poster_image?>');
background-image: -o-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=UPLOADS_PATH?>/poster_images/<?=$movie->movie_poster_image?>');
background-image: -ms-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=UPLOADS_PATH?>/poster_images/<?=$movie->movie_poster_image?>');
background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=UPLOADS_PATH?>/poster_images/<?=$movie->movie_poster_image?>');
);">
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
          <a href="#" class="btn btn-default btn-fill dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?=$muviko->translate('Browse')?> <b class="caret"></b> </a>
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
        <a href="#" class="btn btn-success btn-fill" data-toggle="modal" data-target="#login"><?=$muviko->translate('Sign_In')?></a>
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
          <li><a href="<?= $muviko->getDomain() ?>/settings.php"><?= $muviko->translate('Settings') ?></a></li>
          <li><a href="<?=$muviko->getDomain()?>/logout.php"><?=$muviko->translate('Logout')?></a></li>
        </ul>
        <? } ?>
      </li>
      <? } ?>
    </ul>
  </div>
</div>
</nav>
<div class="player-single-outter container" onclick="hideSearch()">
  <?php if($muviko->canWatch($movie->free_to_watch)) { ?>
  <div class="player-single-wrapper">
    <div id="player"></div>
  </div>
  <? } else { ?>
  <div class="subscribe-alert text-center">
    <h2> <?=$muviko->translate('Available_Only_Subscribers')?> </h2>
    <a href="<?=$muviko->getDomain()?>/register.php" class="btn btn-default btn-fill btn-lg">
      <?=$muviko->translate('Subscribe_Now')?>
    </a>
  </div>
  <? } ?>
</div>
</div>
<div class="movie" onclick="hideSearch();">
  <div class="container" style="padding:0px;">
    <?php if($muviko->canWatch($movie->free_to_watch) && $movie->is_series == 1) { ?>
    <ul class="series-navigation">
      <li class="back" onclick="showSeasons(); return false;"> <a href="#"> <i class="ti-angle-left"></i> <?=$muviko->translate('Seasons')?>  </a> </li>
      <div class="seasons">
        <?php while($season = $seasons->fetch_object()) { ?>
        <li onclick="loadSeason(<?=$season->id?>,<?=$season->season_number?>); return false;"> 
          <a href="#"> <?=$muviko->translate('Season').' '.$default_season->season_number?> </a>
        </li>
        <? } ?>
      </div>
      <div class="episodes">
      </div>
    </ul>
    <? } ?>
    <div class="col-lg-5 pull-left" style="padding:0px;">
      <div class="main-info">
        <h1 class="title"> <?=$movie->movie_name?> </h1>
        <div class="star-rating"></div>
        <p class="plot"> 
         <?=$movie->movie_plot?>
       </p>
     </div>
   </div>
   <div class="col-lg-3 pull-right">
    <div class="action-buttons pull-right">
      <?=$muviko->newAddListBtn($movie->id,'green')?>
   </div>
 </div>
 <div class="clearfix"></div>
 <div id="disqus_thread"></div>
 <script>
 (function() { 
  var d = document, s = d.createElement('script');
  s.src = '//<?=$muviko->settings->disquis_short_name?>.disqus.com/embed.js';
  s.setAttribute('data-timestamp', +new Date());
  (d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
</div>
<input type="hidden" id="movie_id" value="<?=$movie->id?>">