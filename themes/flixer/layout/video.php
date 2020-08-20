<?php
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
      <li id="search-toggle"> <a href="#" onclick="showSearch();"> <i class="ti-search"></i> &nbsp; <span><?=$muviko->translate('Search')?></span> </a> </li>
      <?php if(!$muviko->verifySession(false)) { ?>
      <li>
        <a href="#" class="btn btn-danger btn-fill" data-toggle="modal" data-target="#login"><?=$muviko->translate('Sign_In')?></a>
      </li>
      <?php } else { ?>
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
          <?php } ?>
          <li><a href="<?= $muviko->getDomain() ?>/my_list.php"><?= $muviko->translate('My_List') ?></a></li>
          <li><a href="<?= $muviko->getDomain() ?>/select_profile.php"><?= $muviko->translate('Switch_Profile') ?></a></li>
          <li><a href="<?= $muviko->getDomain() ?>/settings.php"><?= $muviko->translate('Settings') ?></a></li>
          <li><a href="<?=$muviko->getDomain()?>/logout.php"><?=$muviko->translate('Logout')?></a></li>
        </ul>
        <?php } ?>
      </li>
      <?php if($muviko->isKid()) { ?>
      <a href="select_profile.php" class="btn btn-danger btn-fill btn-sm exit-kids"><?=$muviko->translate('Exit_Kids')?></a>
      <?php } ?>
      <?php } ?>
    </ul>
  </div>
</div>
</nav>
<div class="player-single-outter container" onclick="hideSearch()">
  <?php
   if($muviko->user->is_suspended !='' || $muviko->user_id==22 || $sub_plan_id==2 || $sub['is_admin']==1){
  if($muviko->canWatch($movie->free_to_watch) || strpos($sub_plan, 'Gold')!== false ) { ?>
 <!--  <div class="player-single-wrapper">
    <div id="player"></div>
  </div> -->
  <iframe id="frameId" width="100%" height="100%" src="<?=$movie->movie_source?>" frameborder="0" scrolling="no" allowfullscreen=""></iframe>
  <?php }
}
   else { ?>
  <div class="subscribe-alert text-center">
    <h2> <?=$muviko->translate('Available_Only_Subscribers')?> </h2>
    <a href="<?=$muviko->getDomain()?>/register.php" class="btn btn-default btn-fill btn-lg">
      <?=$muviko->translate('Subscribe_Now')?>
    </a>
  </div>
  <?php } ?>
</div>
</div>

<?php
$checkmovie = "select * from ratings where movie_id = '".$movie->id."'";
$checkmovie_r = $db->query($checkmovie);

if ($checkmovie_r->num_rows > 0) {
	while($row = $checkmovie_r->fetch_assoc()) {
    //echo $row['rating'];
		//$rating = round(($row['rating']*2),1);
    $rating = $row['rating']*10;
    $b = $rating/10;
      $b = number_format((float)$b, 1, '.', '');
	}
}
$alternative_titles = 'No Alternative Title';
if($movie->alternative_titles != ''){
  $alternative_titles = $movie->alternative_titles;
}
//$a = $rating/20;
//echo $width = $a*10;
?>
<style>
  .jq-ry-rated-group{
    width: <?=$rating?>% !important;
  }
</style>
<div class="movie" onclick="hideSearch();">
  <div class="container" style="padding:0px;">
    <div class="col-lg-7">
      <div class="main-info">
        <h1 class="title"><?=$movie->movie_name?> <?php if($movie->movie_year!=''){ echo "(".$movie->movie_year.")";}?></h1> <p><b style="color:#49d244;">IMDB Link : </b><a target="_blank" style="color:white;" href="http://www.imdb.com/title/<?php echo $movie->imdbid;?>">http://www.imdb.com/title/<?php echo $movie->imdbid;?></a></p>
        <h1 class="title">Alternative Titles</h1>
        <?php
        echo $alternative_titles.'<br/>';
        // foreach ($alternative_titles as $key => $value) {
        //   echo $key.'  '. ':' .'  '.$value.'<br/>';
        // }
        ?>
        <br/>
        <div class="star-rating" style="display: inline-block;margin-right:10px;"></div> <b>( <em style="color:#49d244">IMDB Rating</em> : <?=$b?> ) </b>
        <p class="plot">
         <?=$movie->movie_plot?>
       </p>
     </div>
   </div>
   <div class="col-lg-3 pull-right">
    <div class="action-buttons pull-right">
    <?php
 if($muviko->user->is_suspended !='' || $muviko->user_id==22 || $sub['is_admin']==1){
  if($muviko->canWatch($movie->free_to_watch) || strpos($sub_plan, 'Gold') !== false || $sub['is_admin']==1) {
    $results = array();
  $result = $db->query("SELECT * FROM movies WHERE id=$id LIMIT 1");
  $results = $result->fetch_array();
  $videoid = 0;
    $u = $results['movie_source'];
    if($u != ''){
      $u = explode('/', $u);
      if(!empty($u)){
        if(count($u)<=7){
          $videoid =  $u[5];
        }
        else{
          $videoid =  $u[7];
        }
      }
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
  echo $muviko->settings->title1;
  echo "To download in a different resolution, please copy link below and go to one of the 2 options shown.";
  echo '<div class="start-video-downloader start-video-downloader-mid start-video-downloader-lightblue"><input type="text" autocomplete="off" value="'.$text.'" id="myInput"><button onclick="myFunction()">Copy Link</button></div>';
   echo '<div><a href="'.$muviko->settings->link1.'" target="_blank" class="btn btnborder">'.$muviko->settings->title1.'</a> or <a href="'.$muviko->settings->link2.'" target="_blank" class="btn btnborder">'.$muviko->settings->title2.'</a></div>';
}
}
}
else{
?>
  <div class="subscribe-alert text-center">
    <h5> <?=$muviko->translate('Available_Only_Subscribers')?> </h5>
    <a href="<?=$muviko->getDomain()?>/register.php" class="btn btn-default btn-fill btn-lg">
      <?=$muviko->translate('Subscribe_Now')?>
    </a>
  </div>
<?php
}

      if($muviko->verifyAdmin(false)) {
        echo '<a target="_blank" href="'.$muviko->getDomain().'/admin/edit_video.php?id='.$movie->id.'" class="btn btn-neutral btn-neutral">
        <i class="ti-pencil"></i>
        <span> Edit </span>
			</a><br />';
      }
      ?>


      <?=$muviko->newAddListBtn1($movie->id,'#49d244')?>
	    <?=$muviko->newAddListBtn($movie->id,'#49d244')?>
      <!-- <p class="about">
        <?php
        echo '<b style="color:#49d244;">'.$muviko->translate('Starring').'</b>';
        echo ' : ';
        $arr = $actors;
        foreach($actors as $actor) {
          if(!empty($actor)){
            if($actor->actor_nconst != 2294){
              echo '<a style="color:white;" href="'.$muviko->getDomain().'/actor_profile.php?name='.$actor->actor_nconst.'">'.$actor->actor_name.'</a>';
              if(next($arr)) {
                echo ',   ';
              }
            }
          }

        }
        ?>
      </p> -->
      <p class="about">
        <?php
        echo '<b style="color:#49d244">'.$muviko->translate('Genre').'</b>';
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
    <?php } ?>
    <?php if($muviko->settings->show_actors != 0) { ?>
    <li>
      <a href="#" id="button" onclick="loadCast(<?=$actor->id?>); return false;">
        <?=$muviko->translate('Starring')?>
      </a>
    </li>
    <?php } ?>
    <li>
      <a href="#" onclick="loadSuggestions(<?=$mid?>); return false;">
        <?=$muviko->translate('More_Like_This')?>
      </a>
    </li>
  </ul>
  <div class="dark-section">
    <div class="">
      <div class="row">
      </div>
      </div>
      </div>
      <div class="clearfix"></div>
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
                    <?php

                    while($season = $seasons->fetch_object()) {
                      ?>
                    <li>
                      <a href="#" class="action-line" onclick="loadSeason(<?=$season->id?>,<?=$season->season_number?>); return false;">
                        <?=$muviko->translate('Season').' '.$season->season_number?>
                      </a>
                    </li>
                    
                    <?php } ?>

                  </ul>
                </div>
              </div>
              <div class="season-picker">
              <?php
              if(!empty($fetchWacthedAllSeason)){
                ?>
                <a href="#" class="btn removeAllWatchSeason WARS" data-movie="<?=$mid?>" data-user="<?=$uid?>">
                  <i class="ti-check"></i>
                  Remove Watched season
                </a>
                <?php
              }
              else{
                ?>
                <a href="#" class="btn watchAllsSeason WARS" data-movie="<?=$mid?>" data-user="<?=$uid?>">
                  <i class="ti-plus"></i>
                  Watch all season
                </a>
                <?php
              }
              ?>
              </div>
              <div class="clearfix"></div>
              <div class="episodes-ajax"></div>
            </div>
          <?php
        }
        ?>
        <div class="suggestions my-list" style="display:none;">
          <?php
          while($suggestion = $suggestions->fetch_object()) {
          $muviko->newHomeMovieItem($suggestion->id,$suggestion->movie_thumb_image,$suggestion->movie_name,$suggestion->movie_year,$suggestion->movie_rating,$suggestion->is_series,$suggestion->last_season,'',$suggestion->movie_year,$suggestion->imdbid);
          }
          ?>
        </div>
      </div>
    </div>
  </div>
<div class="clearfix"></div>
  <div class="dark-section">
 <div class="cast">
        <div class="row" id="div1">

        </div>
  </div>
</div>
</div>

</div>
</div>
<style type="text/css">
.WARS{
  margin-bottom: 20px;
  border-color: #FFFFFF;
    color: #FFFFFF;
}
</style>
<script>
$(document).ready(function(){
    $("#button").click(function(){
        $.ajax({
           type: "get",
          url: "demo_test.php",
           data: {val: <?=$movie->id?>},
           cache: false,
          success: function(result){
            $("#div1").html(result);
        }});
    });
    $(".watchAllsSeason").on("click",function(e){
       e.preventDefault();
       var movie = $(this).data('movie');
       var user = $(this).data('user');
       $.ajax({
           type: "post",
           url: "watchallseason.php",
           data: {mV:movie,user:user},
           cache: false,
           success: function(result){
         
        }});
        $(".watchAllsSeason").html('<i class="ti-check"></i> Watched All Season');
        window.location.reload(true);
    });
    $(".removeAllWatchSeason").on("click",function(e){
       e.preventDefault();
       var movie = $(this).data('movie');
       var user = $(this).data('user');
       $.ajax({
           type: "post",
           url: "removeAllWatchSeason.php",
           data: {mV:movie,user:user},
           cache: false,
           success: function(result){
         
        }});
        $(".removeAllWatchSeason").html('<i class="ti-plus"></i> Removed Watched');
        window.location.reload(true);
    });
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
.ribbon-top-left{
	top: 70px !important;
  left:164px !important;
}
.ribbon{
	height: 110px !important;
}
.ribbon-top-left span{
	right: -20px !important;
	}
  .item img{
    height: 20px;
    /*width: 30px;*/
  }
  @media (min-width: 992px){
.col-md-2 {
    width: 234px;
}
.movie .cast .actor .actor-pro-img {
    width: 230px;
}
.actor-pro-img img{
  width: 242px;
}
}
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
    font-size: 1px;
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
    border: solid 1px #4c5e;
    border-radius: 10px 0 0 10px;
    font-size: 1px;
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
    font-size: 15px;
}
.apower-powerby{
  display: none;
}
</style>
<script src="https://d3j06uq1x1o3j.cloudfront.net/js/api-page.js?ab9d"></script>
<input type="hidden" id="movie_id" value="<?=$movie->id?>">
