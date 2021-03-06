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
      <? } ?>
    </ul>
  </div>
</div>
</nav>
<div class="content animated fadeIn" onclick="hideSearch();">
  <div class="edit-profile col-lg-6 col-centered">
    <form action="" method="post">
      <h1 class="pull-left"><?=$muviko->translate('Edit_Profile')?></h1>
      <div class="clearfix"></div>
      <div class="entry">
        <form action="" method="post">
          <div class="col-lg-2 avatar-container pull-left">
            <img src="<?=THEME_PATH?>/assets/images/avatars/<?=$profile->profile_avatar?>.png" class="avatar pull-left">
            <input type="hidden" name="profile_avatar" value="<?=$profile_avatar?>">
          </div>
          <div class="col-lg-7 pull-left">
            <div class="input-group">
              <input type="text" name="profile_name" value="<?=$profile->profile_name?>" placeholder="Name" class="form-control input-dark input-lg pull-left">
              <span class="input-group-addon checkbox-kids">
                <label class="checkbox pull-right" for="checkbox5">
                  <input type="checkbox" name="is_kid" value="1" id="checkbox5" data-toggle="checkbox" <?php if($profile->is_kid == 1) { echo 'checked'; } ?>>
                  <span class="description">Kid</span>
                </label>
              </span>
            </div>
            <div class="col-lg-4 pull-left text-left">
              <label><?=$muviko->translate('Language')?></label>
              <div class="clearfix"></div>
              <div class="chosen-settings pull-left">
                <select name="profile_language" class="form-control input-dark chosen" style="border-radius:0px;">
                  <option <?php if($profile->profile_language=='english') { echo 'selected'; } ?>> English <b class="caret"></b> </option>
                  <option <?php if($profile->profile_language=='bulgarian') { echo 'selected'; } ?>> Bulgarian </option>
                  <option <?php if($profile->profile_language=='turkish') { echo 'selected'; } ?>> Turkish </option>
                  <option <?php if($profile->profile_language=='italian') { echo 'selected'; } ?>> Italian </option>
                  <option <?php if($profile->profile_language=='spanish') { echo 'selected'; } ?>> Spanish </option>
                  <option <?php if($profile->profile_language=='chinese') { echo 'selected'; } ?>> Chinese </option>
                  <option <?php if($profile->profile_language=='polish') { echo 'selected'; } ?>> Polish </option>
                  <option <?php if($profile->profile_language=='danish') { echo 'selected'; } ?>> Danish </option>
                  <option <?php if($profile->profile_language=='german') { echo 'selected'; } ?>> German </option>
                  <option <?php if($profile->profile_language=='french') { echo 'selected'; } ?>> French </option>
                  <option <?php if($profile->profile_language=='russian') { echo 'selected'; } ?>> Russian </option>
                  <option <?php if($profile->profile_language=='japanese') { echo 'selected'; } ?>> Japanese </option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <button type="submit" name="save" class="btn btn-neutral btn-fill btn-lg pull-left"><?=$muviko->translate('Save')?></button>
        <a href="manage_profiles.php" class="btn btn-default btn-lg pull-left"><?=$muviko->translate('Cancel')?></a>
        <?php
        if($_REQUEST['fid']!=1){
        ?>
        <form method="POST">
    <input type="submit" name="psubmit" class="btn btn-default btn-lg pull-left" value="Delete Profile" style="background-color: red;color: #fff;">
        </form>
        <?php } ?>
    </form>
  </div>
</div>
</div>
<?php
if (isset($_POST["psubmit"])) {
  $rid = $_REQUEST['id'];
  $sql = $db->query("DELETE FROM profiles WHERE id='".$rid."' AND profile_avatar!=1");
  if($sql){
     echo "<script type='text/javascript'>window.location.href = '".base_url()."/manage_profiles'</script>";
  }
}
?>
