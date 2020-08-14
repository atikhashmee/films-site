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
<?php
$checkmovie = "select * from ratings where movie_id = '".$movie->id."'";
$checkmovie_r = $db->query($checkmovie);

if ($checkmovie_r->num_rows > 0) {
	while($row = $checkmovie_r->fetch_assoc()) {
		$rating = round(($row['rating']*2),1);
	}
}

?>
<div class="movie" onclick="hideSearch();">
  <div class="container" style="padding:0px;">
    <div class="col-lg-7">
      <div class="main-info">
        <h1 class="title"><?=$movie->movie_name?></h1> <p><b style="color:red;">IMDB Link : </b><a target="_blank" style="color:white;" href="http://www.imdb.com/title/<?php echo $movie->imdbid;?>">http://www.imdb.com/title/<?php echo $movie->imdbid;?></a></p>
        <div class="star-rating" style="display: inline-block;margin-right:10px;"></div> <b>( <em style="color:red">IMDB Rating</em> : <?=$rating?> ) </b>
        <p class="plot"> 
         <?=$movie->movie_plot?>
       </p>
     </div>
   </div>
   <div class="col-lg-3 pull-right">
    <div class="action-buttons pull-right">
      <?php
      if($muviko->verifyAdmin(false)) {
        echo '<a target="_blank" href="'.$muviko->getDomain().'/admin/edit_video.php?id='.$movie->id.'" class="btn btn-neutral btn-neutral">
        <i class="ti-pencil"></i> 
        <span> Edit </span>
			</a><br />';
      }
      ?>
      <?=$muviko->newAddListBtn1($movie->id,'red')?>
	    <?=$muviko->newAddListBtn($movie->id,'red')?>
      <p class="about"> 
        <?php 
        echo '<b style="color:red;">'.$muviko->translate('Starring').'</b>';
        echo ' : ';
        $arr = $actors; 
        foreach($actors as $actor) { 
          echo '<a style="color:white;" href="../actor_profile.php?name='.$actor->actor_nconst.'">'.$actor->actor_name.'</a>';

          if(next($arr)) {
            echo ',   ';
          }
        }
        ?>
      </p>
      <p class="about">
        <?php
        echo '<b style="color:red">'.$muviko->translate('Genre').'</b>';
        echo ' : ';
        $muviko->movieGenresToText($movie->movie_genres);
        ?>
      </p> 
    </div>
  </div>
  <div class="clearfix"></div>
  <?php
  
  ?>
  <ul class="tabs nav nav-pills nav-pills-danger">
    <?php if($movie->is_series == 1) { ?> 
    <li class="active">
      <a href="#" onclick="loadSeason(<?=$default_season->id?>,<?=$default_season->season_number?>); return false;">
        <?=$muviko->translate('Episodes')?>
      </a>
    </li>
    <? } ?>
    <?php if($muviko->settings->show_actors == 1) { ?>
    <li>
      <a href="#" onclick="loadCast(); return false;">
        <?=$muviko->translate('Starring')?>
      </a>
    </li>
    <? } ?>
    <li>
      <a href="#" onclick="loadSuggestions(<?=$movie_id?>); return false;">
        <?=$muviko->translate('More_Like_This')?>
      </a>
    </li>
  </ul>
  <div class="dark-section">
    <div class="details-container">
      <div class="row">
        <?php
        if($muviko->verifyAdmin(false) || $muviko->subscriberAction()){
          ?>
            <div class="episodes">
              <div class="season-picker">
                <span class="season-number"> 
                  <?=$muviko->translate('Season').' '.$default_season->season_number?> 
                </span>
                <div class="dropdown">
                  <button id="dLabel" type="button" class="season-dropdown btn btn-sm btn-simple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="caret"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-actions dropdown-menu-right dropdown-small" aria-labelledby="dLabel">
                    <?php while($season = $seasons->fetch_object()) { ?>
                    <li>
                      <a href="#" class="action-line" onclick="loadSeason(<?=$season->id?>,<?=$season->season_number?>); return false;">
                        <?=$muviko->translate('Season').' '.$season->season_number?> 
                      </a>
                    </li>
                    <? } ?>
                  </ul>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="episodes-ajax"></div>
            </div>
          <?php
        }
        ?>
        
        <?php if($muviko->settings->show_actors == 1) { ?>
        <div class="cast" style="display:none;">
          <div class="row">
            <?php foreach($actors as $actor) { ?>
              <div class="actor col-md-2">
                <?php
                $picture = UPLOADS_PATH.'/actors/'.$actor->actor_picture;
                $style = '';
                if($actor->actor_picture == ''){
                    $picture = $muviko->getDomain().'/images/default-user.png';
                }
                ?>
                <a style="color:white;" href="../actor_profile.php?name=<?=$actor->actor_nconst?>">
                  <span class="actor-pro-img"><img src="<?=$picture?>"></span>
                  <p class="title"><?=$actor->actor_name?></p>
                </a>
              </div>
            <? } ?>
            <?php /*foreach($actors as $actor) { ?>
                <div class="actor">
            <?php if($actor->actor_picture == "") { $imageshoe = 'user.png'; ?>
                  <a style="color:white;" href="../actor_profile.php?name=<?=$actor->actor_nconst?>"><img src="<?=UPLOADS_PATH?>/actors/<?=$imageshoe?>" class="img-responsive">
                  <p class="title"><?=$actor->actor_name?></p></a>
            <?php } ?>
                </div>
            <? }*/ ?>
          </div>
        </div>
        <? } ?>
        <div class="suggestions my-list" style="display:none;">
          <?php 
          while($suggestion = $suggestions->fetch_object()) { 
          $muviko->newHomeMovieItem($suggestion->id,$suggestion->movie_thumb_image,$suggestion->movie_name,$suggestion->movie_year,$suggestion->movie_rating,$suggestion->is_series,$suggestion->last_season);
          } 
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<input type="hidden" id="movie_id" value="<?=$movie->id?>">
<script type="text/javascript">
  jQuery(document).ready(function($){
    setTimeout(function() {
    $(".add-list-watch").trigger('click');
  }, 900000);
  });
</script>
