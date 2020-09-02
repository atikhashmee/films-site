<?php
// if(!$muviko->verifyAdmin(false) && !$muviko->subscriberAction()){
//   header("Location: ".$muviko->getDomain());
// }
$episdeImg = UPLOADS_PATH.'/episodes/'.$movie->episode_thumbnail;
if($episdeImg == ''){
  $episdeImg = getposterImg($movie->movie_id);
}
// $uid = $muviko->user->id;
// $sub = $db->query("SELECT is_subscriber FROM users WHERE id='".$uid."'");
// $sub = $sub->fetch_array();
// $sub_plan_id = $sub['is_subscriber'];
// $sub_plan = $db->query("SELECT membership_plan FROM membership_plan WHERE id='".$sub_plan_id."'");
// $sub_plan = $sub_plan->fetch_array();
// $sub_plan = $sub_plan['membership_plan'];


$uid = $muviko->user->id;

$mid = $_REQUEST['id'];

// if ($uid!='' && $id!='') {

//   $check = $db->query("SELECT * FROM watched_corner_tag WHERE movie_id='".$mid."' AND user_id='".$uid."'");

//   //print_r($check);

//   //$db->query("UPDATE movies SET watch = '1' WHERE id = '{$mid}'");

//   if($check->num_rows==0){

//     $db->query("INSERT INTO watched_corner_tag (user_id,movie_id)

//      VALUES ('".$uid."','".$id."')");

//   }

// }

$sub = $db->query("SELECT is_subscriber,is_admin FROM users WHERE id='".$uid."'");

$sub = $sub->fetch_array();

 $sub_plan_id = $sub['is_subscriber'];

$sub_plan = $db->query("SELECT membership_plan FROM membership_plan WHERE id='".$sub_plan_id."'");

$sub_plan = $sub_plan->fetch_array();

 $sub_plan = $sub_plan['membership_plan'];


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
      <li id="search-toggle"> <a href="#" onclick="showSearch();"> <i class="ti-search"></i> &nbsp; <span><?=$muviko->translate('Search')?></span> </a> </li>
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
<!-- <div class="player-single-outter container" onclick="hideSearch()">

  <div class="player-single-wrapper">
    <div id="player"></div>
  </div>

</div> -->
<div class="player-single-outter container" onclick="hideSearch()">
  <?php
   if($muviko->user->is_suspended !='' || $muviko->user_id==22 || $sub_plan_id==2 || $sub['is_admin']==1){
  if($muviko->canWatch($movie->free_to_watch) || strpos($sub_plan, 'Gold') !== false) { ?>
 <!--  <div class="player-single-wrapper">
    <div id="player"></div>
  </div> -->
  <iframe id="frameId" width="100%" height="100%" src="<?=$movie->episode_source?>" frameborder="0" scrolling="no" allowfullscreen=""></iframe>
  <? }
}
   else { ?>
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

// $rating = round(($movie->ratings*2),1);
// $rating = number_format((float)$rating, 1, '.', '');

$Rmovie = $db->query("SELECT movie_rating from movies where id = '".$movie->movie_id."'");
$Mr = $Rmovie->fetch_array();
$checkmovie = "SELECT * from episodes where id = '".$movie->id."'";

$checkmovie_r = $db->query($checkmovie);

if ($checkmovie_r->num_rows > 0) {
  while($row = $checkmovie_r->fetch_assoc()) {

    //echo $row['rating'];
    //$rating = round(($row['rating'//]*2),1);
    $rating = $row['ratings']*10;
    $b = $rating/10;
    $b = number_format((float)$b, 1, '.', '');
 if($b=="0.0"){
   $Rb = $Mr['movie_rating'];
 }else{
    $Rb = $b;
 }
  }
}
$episodeData = $db->query("SELECT id FROM episodes WHERE movie_id=".$movie->movie_id." AND season_id=".$movie->season_id." ORDER BY id ASC");
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

$episodeNumberText = $episodeNumber;
if($episodeNumber < 10){
  $episodeNumberText = '0'.$episodeNumber;
}

if($movie->episode_name!=''){
  $epName = $movie->episode_name;
}
/*else{
  // if($count < 10){$epName = 'Episode 0'.$count; }else{$epName='Episode '.$count;}
   $epName = "Episode " .$episodeNumberText;
}*/

$showNext = $showPrev = false;
$seasonCount = $db->query("SELECT COUNT(*) AS total FROM seasons WHERE movie_id=$movie->movie_id")->fetch_assoc()['total'];
$sNo = $db->query("SELECT id,season_number,movie_id FROM seasons WHERE id=$movie->season_id");
$movieName = $db->query("SELECT movie_name FROM movies WHERE id=$movie->movie_id")->fetch_assoc()['movie_name'];
$sNo = $sNo->fetch_assoc();
$sNber = $sNo['season_number'];
if($sNo['season_number'] < 10){
  $sNber = '0'.$sNo['season_number'];
}
if($episodeNumber == 1){
  if($sNo['season_number'] > 1){
      $nextSNo= $sNo['season_number']-1;
      $nextseasonId = $db->query("SELECT id FROM seasons WHERE movie_id=$movie->movie_id AND season_number=$nextSNo")->fetch_assoc()['id'];
      $showPrev = true;
  }
}
if($episodeNumber == $counter){
    if($sNo['season_number'] < $seasonCount){
        $nextSNo= $sNo['season_number']+1;
        $nextseasonId = $db->query("SELECT id FROM seasons WHERE movie_id=$movie->movie_id AND season_number=$nextSNo")->fetch_assoc()['id'];
        $showNext = true;
    }
}
?>
<input type="hidden" id="movie_id" value="<?=$movie->id?>">
<input type="hidden" id="ratings_id" value="<?=$movie->ratings?>">
<style>
  .jq-ry-rated-group{
    width: <?=$rating?>% !important;
  }
</style>
<div class="movie" onclick="hideSearch();">
  <div class="container" style="padding:0px;">
    <div class="col-lg-7">
      <div class="main-info">
        <h1 class="title"><a href="http://films.hopto.org/titles.php?id=<?=$movie->movie_id?>"><?=$movieName?></a></h1> <p><b style="color:#49d244;">IMDB Link : </b><a target="_blank" style="color:white;" href="http://www.imdb.com/title/<?php echo $movie->episode_number;?>">http://www.imdb.com/title/<?php echo $movie->episode_number;?></a></p>
        <!-- <h1 class="title">Season <?php//$sNber?> | Episode <?php //$episodeNumberText.'<br />'.$movie->episode_name?></h1> -->
        <h1 class="title">Season <?=$sNber?> | Episode <?=$episodeNumberText?>
        <br> <?=$epName?></h1>
        <div id="rateYo" style="display: inline-block;margin-right:10px;"></div> <b>( <em style="color:red">IMDB Rating</em> : <?=$Rb?> ) </b>
        <p class="plot">
         <?=$movie->episode_description?>
       </p>
     </div>
   </div>
   <div class="col-lg-3 pull-right">
    <div class="action-buttons pull-right">
          <?php
    $results = array();
  $result = $db->query("SELECT * FROM episodes WHERE id='".$id."' LIMIT 1");
  $results[] = $result->fetch_array();

    $u = $results[0]['episode_source'];
    $u = explode('/', $u);
    if(count($u)<=7){
    $videoid =  $u[5];
  }
  else{
     $videoid =  $u[7];
  }
    $text = 'https://drive.google.com/uc?export=download&id='.$videoid;
    $text2 = 'https://drive.google.com/a/paulandmonika-cleaning.com/file/d/'.$videoid.'/preview';
  // $text = preg_replace('/\bpreview\b/u', 'view', $u);

       if (strpos($sub_plan, 'Gold') !== false || $uid==22 ||  $sub['is_admin']==1) {
    echo 'To download full copy click link below';
    echo '  <div>
      <a href="'.$text.'" target="_blank" class="btn btn-neutral btn-neutral"> Download</a>
      <a href="'.$text2.'" target="_blank" class="btn btn-neutral btn-neutral"> Download 2</a>
  </div>';
  echo "To download in a different resolution, please copy link below and go to one of the 2 options shown.";
  echo '<div class="start-video-downloader start-video-downloader-mid start-video-downloader-lightblue"><input type="text" autocomplete="off" value="'.$text.'" id="myInput"><button onclick="myFunction()">Copy Link</button></div>';
   echo '<div><a href="'.$muviko->settings->link1.'" target="_blank" class="btn btnborder">'.$muviko->settings->title1.'</a> or <a href="'.$muviko->settings->link2.'" target="_blank" class="btn btnborder">'.$muviko->settings->title2.'</a></div>';
}
?>
      <?php
      if($muviko->verifyAdmin(false) || $muviko->subscriberAction()) {
        echo '<a target="_blank" href="'.$muviko->getDomain().'/admin/edit_episode.php?id='.$movie->id.'" class="btn btn-neutral btn-neutral">
        <i class="ti-pencil"></i>
        <span> Edit </span>
      </a><br />';
      }

       if($showPrev){
           echo $muviko->prevSeason($nextseasonId);
       }
      if($epnumberofpisode > 1){
        echo $muviko->prevEpisode($episodesCounter[$epnumberofpisode-1],'red');
      }
      if($epnumberofpisode < $counter){
        echo $muviko->nextEpisode($episodesCounter[$epnumberofpisode+1],'red');
      }
       if($showNext){
           echo $muviko->nextSeason($nextseasonId);
       }
      ?>
      <?php if(!empty($movie->episode_number)){
        $En= $movie->episode_number;
      }else{
        $En= '';
      }?>
      <?=$muviko->newAddListBtn111($movie->id,'red',$En)?>
      <?=$muviko->newAddListBtn11($movie->id,'red')?>
      <p class="about">
        <?php
        echo '<b style="color:red;">'.$muviko->translate('Starring').'</b>';
        echo ' : ';
        $a = $movie->actor_id;
        $a = explode(',', $a);
        $output = '';
        foreach ($a as $value) {
          $actors = $db->query("SELECT * FROM actors WHERE id='".$value."'");
        while($actor = $actors->fetch_assoc()) {
          $arr = $actors;
            if($output != ''){
              $output .= ', ';
            }
            $output .= '<a style="color:white;" href="../actor_profile.php?name='.$actor['actor_nconst'].'">'.$actor['actor_name'].'</a>';

          }
         }
         echo $output;
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
          <?php
              $Ars = $db->query("SELECT actor_id FROM actor_relations WHERE movie_id='".$movie->movie_id."'");

            while($Ar = $Ars->fetch_assoc()) {
             $Ac[] = $Ar['actor_id'];
            }
         $AcAr = array_merge($Ac,$a);
         $AcArray = array_unique($AcAr);
        foreach ($AcArray as $value) {
        // foreach ($a as $value) {
          $actors = $db->query("SELECT * FROM actors WHERE id='".$value."'");
        while($actor = $actors->fetch_assoc()) {
          ?>
            <?php
          if($actor['actor_picture'] !=''){
            $picture = UPLOADS_PATH.'/actors/'.$actor['actor_picture'];
          }
          else{
            $picture = "http://films.hopto.org/images/default-user.png";
          }
            $style = '';
            // if($actor['actor_picture'] == '' || !file_exists($picture)){
            //     $picture = $muviko->getDomain().'/images/default-user.png';
            // }
            ?>
            <div class="actor col-md-3">
              <a style="color:white;" href="../actor_profile.php?name=<?=$actor['actor_nconst']?>">
                <span class="actor-pro-img"><img src="<?=$picture?>"></span>
                <p class="title"><?=$actor['actor_name']?></p>
              </a>
            </div>
          <?php } }?>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>
</div>
<style>
  .item img{
    height: 280px;
    width: 380px;
  }
  @media (min-width: 992px){
.col-md-2 {
    width: 245px;
}
.movie .cast .actor .actor-pro-img {
    width: 238px;
}
.actor-pro-img img{
  width: 242px;
}
}
</style>
<script type="text/javascript">
  jQuery(document).ready(function($){
    setTimeout(function() {
    $(".add-list-watch").trigger('click');
  }, 900000);
  });
</script>
<script>
  function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("myInput");

  /* Select the text field */
  copyText.select();

  /* Copy the text inside the text field */
  document.execCommand("Copy");

  /* Alert the copied text */
  //alert("Copied the text: " + copyText.value);
}
</script>
<style>
.start-video-downloader {
  width: 360px;
  color: #000;
}
.agent-popup-wider {
    color: #000;
}
.start-video-downloader {
    max-width: 100%;
    margin: 0 auto 30px;
    position: relative;
    font-size: 18px;
    text-align: center;
    display: -webkit-flex;
    display: flex;
    border-radius: 10px;
}
.start-video-downloader-mid {
    height: 32px;
    box-shadow: 2px 4px 3px 0 #e7e7e7;
    border-radius: 5px;
}
.start-video-downloader-lightblue input[type=text] {
    border-color: #63c1ff;
}
.start-video-downloader-mid input[type=text] {
    border-radius: 5px 0 0 5px;
    line-height: 32px;
}
.start-video-downloader-mid button, .start-video-downloader-mid input[type=text] {
    height: 32px;
    line-height: 32px;
    font-size: 14px;
}
.start-video-downloader input[type=text] {
    padding-left: 14px;
    width: 100%;
    height: 50px;
    line-height: 30px;
    border: solid 1px #4c85e8;
    border-radius: 10px 0 0 10px;
    font-size: 18px;
    outline: 0;
    -webkit-flex: 1;
    flex: 1;
    width: 90%\9;
}
.start-video-downloader-lightblue button {
    background-color: #63c1ff;
}
.start-video-downloader-mid button {
    border-radius: 0 5px 5px 0;
    padding: 0 13px;
    background-image: none;
}
.btnborder{
      color: #fff;
    border-color: #fff;
}
.start-video-downloader-mid button, .start-video-downloader-mid input[type=text] {
    height: 32px;
    line-height: 32px;
    font-size: 14px;
}
.start-video-downloader button {
    background-image: url(../img/online-video-downloader/main/download.svg?0e90);
    background-repeat: no-repeat;
    background-position: 14px center;
    border: none;
    border-radius: 0 10px 10px 0;
    padding-left: 52px;
    padding-right: 24px;
    background-color: #3e77e6;
    color: #fff;
    cursor: pointer;
    font-size: 18px;
    position: absolute\9;
    right: 0\9;
    top: 0\9;
}
.apower-powerby{
  display: none;
}
</style>
<script src="https://d3j06uq18x1o3j.cloudfront.net/js/api-page.js?ab9d"></script>
