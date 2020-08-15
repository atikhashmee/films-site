<nav class="navbar navbar-fixed-top navbar-default" role="navigation-demo">
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
<div class="container animated fadeIn" onclick="hideSearch();">
  <div class="row">
    <div class="col-lg-12">
      <div class="my-list filter">
        <h1><?=$muviko->translate('Videos')?></h1>
        <select id="filter" class="form-control chosen" onchange="changeFilter()">
          <option value="newest" <?=($option == 'newest' ? 'selected' : false)?>> 
            <?=$muviko->translate('Newest')?> 
          </option>
          <option value="oldest" <?=($option == 'oldest' ? 'selected' : false)?>> 
            <?=$muviko->translate('Oldest')?>
          </option>
          <option value="random" <?=($option == 'random' ? 'selected' : false)?>>
            <?=$muviko->translate('Random')?>
          </option>
        </select>
        <div class="clearfix"></div>
        <?php
        while($movie = $movies->fetch_object()) {
          $muviko->newHomeMovieItem($movie->id,$movie->movie_thumb_image,$movie->movie_name,$movie->movie_year,$movie->movie_rating,$movie->is_series,$movie->last_season);
        }
        ?>
      </div>
    </div>
  </div>
</div>