
<?php
if(!$muviko->verifyAdmin(false) && !$muviko->subscriberAction()){
  header("Location: ".$muviko->getDomain());
}
$episdeImg = UPLOADS_PATH.'/episodes/'.$movie->episode_thumbnail;
if($episdeImg == ''){
  $episdeImg = getposterImg($movie->movie_id);
}
?>
<div class="movie-page-image" style="
background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=$episdeImg?>');
background-image: -moz-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=$episdeImg?>');
background-image: -o-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=$episdeImg?>');
background-image: -ms-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=$episdeImg?>');
background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('<?=$episdeImg?>');
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
  
  <div class="player-single-wrapper">
    <div id="player"></div>
  </div>
 
</div>
</div>
<?php
$rating = round(($movie->ratings*2),1);

$episodeData = $db->query("SELECT id FROM `episodes` WHERE movie_id=$movie->movie_id AND season_id=$movie->season_id ORDER BY id ASC");
$counter = 0;
$epnumberofpisode = 1;
$episodesCounter = [];
while($row = $episodeData->fetch_assoc()){
  $counter++;
  if($row['id'] == $movie->id){
    $episodeNumber  = $counter;
    $epnumberofpisode = $counter;
  }
  $episodesCounter[$counter] = $row['id'];
}
if($episodeNumber < 10){
  $episodeNumber = '0'.$episodeNumber;
}
$sNo = $db->query("SELECT season_number FROM seasons WHERE id=$movie->season_id");
$movieName = $db->query("SELECT movie_name FROM movies WHERE id=$movie->movie_id")->fetch_assoc()['movie_name'];
$sNo = $sNo->fetch_assoc();
$sNber = $sNo['season_number'];
if($sNo['season_number'] < 10){
  $sNber = '0'.$sNo['season_number'];
}
?>
<input type="hidden" id="movie_id" value="<?=$movie->id?>">
<input type="hidden" id="ratings_id" value="<?=$movie->ratings?>">
<div class="movie" onclick="hideSearch();">
  <div class="container" style="padding:0px;">
    <div class="col-lg-7">
      <div class="main-info">
        <h3><?=$movieName?></h3>
        <h1 class="title">Season <?=$sNber?> | Episode <?=$episodeNumber.'<br />'.$movie->episode_name?></h1>
        <div id="rateYo" style="display: inline-block;margin-right:10px;"></div> <b>( <em style="color:red">IMDB Rating</em> : <?=$rating?> ) </b>
        <p class="plot"> 
         <?=$movie->episode_description?>
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
      if($epnumberofpisode > 1){
        echo $muviko->prevEpisode($episodesCounter[$epnumberofpisode-1],'red');
      }
      if($epnumberofpisode < $counter){
        echo $muviko->nextEpisode($episodesCounter[$epnumberofpisode+1],'red');
      }
      ?>
      <?=$muviko->newAddListBtn111($movie->id,'red')?>
	  <?=$muviko->newAddListBtn11($movie->id,'red')?>
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
    </div>
  </div>
  <div class="clearfix"></div>
  <ul class="tabs nav nav-pills nav-pills-danger">
    <li class="active">
      <a href="#" onclick="loadCast(); return false;">
        <?=$muviko->translate('Starring')?>
      </a>
    </li>
  </ul>
  <div class="dark-section">
    <div class="">
      <div class="row">
		    <div class="cast">
          <?php foreach($actors as $actor) { ?>
            <?php 
            $picture = UPLOADS_PATH.'/actors/'.$actor->actor_picture;
            $style = '';
            if($actor->actor_picture == ''){
                $picture = $muviko->getDomain().'/images/default-user.png';
            }
            ?>
            <div class="actor col-md-2">
              <a style="color:white;" href="../actor_profile.php?name=<?=$actor->actor_nconst?>">
                <span class="actor-pro-img"><img src="<?=$picture?>"></span>
                <p class="title"><?=$actor->actor_name?></p>
              </a>
            </div>
          <? } ?>
        </div>
		  </div>
	  </div>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
  jQuery(document).ready(function($){
    setTimeout(function() {
    $(".add-list-watch").trigger('click');
  }, 900000);
  });
</script>