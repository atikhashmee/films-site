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
<div class="container animated fadeIn" onclick="hideSearch();">
  <div class="row">
    <div class="col-lg-12">
      <div class="col-md-5 centered pricing-page">
        <div class="panel panel-danger">
         <div class="panel-heading"><h3 class="text-center"><?=$muviko->settings->subscription_name?></h3></div>
         <div class="panel-body text-center">
           <p class="lead" style="font-size:40px">
            <strong>
              $<?=$muviko->settings->subscription_price?>
              <span>/<?=strtolower($muviko->translate('Month'))?></span>
            </strong>
          </p>
        </div>
        <ul class="list-group list-group-flush text-center">
          <li class="list-group-item"><i class="icon-ok text-danger"></i> Unlimited Movies & Series</li>
          <li class="list-group-item"><i class="icon-ok text-danger"></i> Share your account with up to 4 people</li>
          <li class="list-group-item"><i class="icon-ok text-danger"></i> Create a playlist with your favorite movies</li>
        </ul>
        <div class="panel-footer">
          <a class="btn btn-success btn-block btn-fill" data-toggle="modal" data-target="#pay"><?=strtoupper($muviko->translate('Subscribe'))?></a>
       </div>
     </div>
   </div>
 </div>
</div>
</div>
<div class="modal fade" id="pay" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content payment-modal">
      <div class="modal-body">
        <div class="panel panel-danger"> 
          <div class="panel-heading"> 
            <div class="panel-title"> 
              <?=$muviko->translate('Sign_Up')?>
              <div class="pull-right">
                <span><?=$muviko->translate('Secure_Form')?></span>
                <img src="<?=THEME_PATH?>/assets/images/padlock.png">
              </div>
            </div>
          </div>
          <div class="panel-body">
            <form action="" method="post">
              <div class="form-group">
                <label>
                  <span><?=$muviko->translate('Full_Name')?>*</span>
                </label>
                <input type="text" name="full_name" required class="form-control"/>
              </div>
              <div class="form-group">
                <label>
                  <span><?=$muviko->translate('Email')?>*</span>
                </label>
                <input type="text" name="email" required class="form-control"/>
              </div>
              <div class="form-group">
                <label>
                  <span><?=$muviko->translate('Password')?>*</span>
                </label>
                <input type="password" name="password" required class="form-control"/>
              </div>
              <div class="form-group">
                <label>
                  <span><?=$muviko->translate('Phone')?></span>
                </label>
                <input type="text" name="phone" class="form-control"/>
              </div>
              <button type="submit" name="continue" class="btn btn-success btn-fill pull-right"><?=$muviko->translate('Continue')?></button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>