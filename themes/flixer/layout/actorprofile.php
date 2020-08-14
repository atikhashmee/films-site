<?php
$name = $_REQUEST['name'];
$featured_movie = $db->query("SELECT * FROM actors WHERE actor_nconst='".$name."'");
$data = $featured_movie->fetch_object();
			$relations =  $db->query("SELECT * FROM actor_relations WHERE actor_id='".$data->id."' ORDER BY id ASC");
      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";
while($relation = $relations->fetch_object()) {
	$movies = $db->query("SELECT * FROM movies WHERE id='".$relation->movie_id."'");
	$movies = $movies->fetch_object();
	$movie_lot[] = $movies;
}
// require 'class_IMDb.php';
// $imdb = new IMDb(true);
// $imdb->summary=false;
// $movie = $imdb->person_by_id($name);
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
    if($data->actor_img_url===0){
	    if($data->actor_picture == '' || !file_exists($picture)){
	        $picture = $muviko->getDomain().'/images/default-user.png';
	    }
	  }
    elseif($data->actor_picture==''){
        $picture = $muviko->getDomain().'/images/default-user.png';
    }
    elseif ($data->actor_picture!='' || file_exists($picture)) {
      $picture = $muviko->getDomain().'/uploads/actors/'.$data->actor_picture;
    }
    else{
     $picture = $data->actor_img_url;
      // if($picture==0){
      //   $picture = $muviko->getDomain().'/images/default-user.png';
      // }
    }
  ?>
		<div id="name-overview-widget">
    <img style="width:100%;" src="<?=$picture?>" />
		</div>

	</div>
	<div class="col-lg-8 pull-right">
		<div class="main-info">
			<?php /*<h1 class="title"><?=$movie->name?></h1>
			<p class="plot">
			 <?=$movie->bio?>
		   </p>*/

 //  $curl = curl_init();
 //  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
 //  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
 //  curl_setopt_array($curl, array(
 //   CURLOPT_URL => "https://api.themoviedb.org/3/person/{$name}?api_key=bc457c6e89c45bbb2f34a7bdd23688cf&language=en-US",
 //   CURLOPT_RETURNTRANSFER => true,
 //   CURLOPT_ENCODING => "",
 //   CURLOPT_MAXREDIRS => 10,
 //   CURLOPT_TIMEOUT => 30,
 //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
 //   ));
 //  $response = curl_exec($curl);
 //  //print_r($response);
 //  $err = curl_error($curl);
 //  curl_close($curl);
 //  if ($err) {
 //   echo "cURL Error #:" . $err;
 //  } else {
 //  //echo $response;
 // $json = json_decode($response);
 // $array = get_object_vars($json);
 // //print_r($array);
 // $url = "https://image.tmdb.org/t/p/original".$array['profile_path'];
  // $sql = $db->query("SELECT * FROM actors WHERE actor_nconst='".$name."'");
  // print_r($sql);
  // $sql = fetch_array($sql);
  // print_r($sql);
$imdbConst ='';
if(strpos($data->actor_nconst,'nm') !== false){$imdbConst =$data->actor_nconst;}
else{$imdbConst =$data->imdbid;};
 ?>
<!--  <img src="<?php //echo $url;?>" width="255" height="255"> -->
 <div class="table-responsive">
 <table class="table">
  <tr>
    <td>Name:</td>
    <td><?php echo $data->actor_name;?></td>
  </tr>
  <tr>
    <td>Birthday:</td>
    <td><?php echo $data->birthday;?></td>
  </tr>
  <tr>
  <td>Place of birth:</td>
    <td><?php echo $data->place_of_birth;?></td>
  </tr>
  <tr>
  <td>Biography:</td>
    <td><?php echo $data->biography;?></td>
  </tr>
</table>
</div>
<p><b style="color:#49d244;">IMDB Link : </b><a target="_blank" style="color:white;" href="http://www.imdb.com/name/<?php echo $imdbConst;?>">http://www.imdb.com/name/<?php echo $data->imdbid;?></a></p>
 <?php
    //foreach($array as $value){
      // echo 'Name : '.$array['name'].'<br/>';
      // echo 'Birthday : '.$array['birthday'].'<br/>';
      // echo 'Place of birth : '.$array['place_of_birth'].'<br/>';
      // echo 'Biography : '.$array['biography'].'<br/>';
    //}
  //}
?>
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
        echo '<b>'.$data->actor_name.'</b>';
       //  echo '<b>'.'Birthday'.'</b>';
       //  echo ': ';
       // echo  $movie->birth->date->normal;
        ?>
      </p>
	  <!-- <p class="about">  -->
        <?php
       //  echo '<b>'.'Birth Place'.'</b>';
       //  echo ': ';
       // echo  $movie->birth->place;

        ?>
     <!--  </p> -->
            <p>
        <?php
          if($muviko->verifyAdmin(false)) {
            echo '<a target="_blank" href="'.$muviko->getDomain().'/admin/edit_actor.php?id='.$data->id.'" class="btn btn-neutral btn-neutral">
            <i class="ti-pencil"></i>
            <span> Edit </span>
                </a><br />';
          }
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
			 foreach($movie_lot as $choose_m) {
      $movie_poster_image = $choose_m->movie_poster_image;
        if($choose_m->movie_name != "") {
          ?>
         	<div class="item episode col-md-4">
            <a style="color:white;" href="titles.php?id=<?=$choose_m->id?>">
            <?php
           $p = UPLOADS_PATH.'/masonry_images/'.$choose_m->movie_thumb_image;
           $pl = UPLOADS_PATH.'/poster_images/'.$choose_m->movie_poster_image;
            if($movie_thumb_image !='' || file_exists($pl)){
               $picture = $muviko->getDomain().'/images/default-image.png';
            ?>
              
              <span><img src="<?=UPLOADS_PATH?>/masonry_images/<?=$choose_m->movie_thumb_image?>"></span>
              <?php
                }
                elseif($movie_poster_image !='' || !file_exists($p)){
                   ?>
              <span><img src="<?=$pl?>"></span>
              <?php
                }
                else{
                  
                  ?>
                   <span><img src="<?=$p?>"></span>
                  <?php
                }
              ?>
				      <p class="title"><?=$choose_m->movie_name?></p></a>
			      </div>
			  <?php }

         } ?>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<style>
  .movie .details-container .episode span {
    height: 240px;
  }
  .item img{
    height: 280px;
    width: 380px;
  }
</style>
