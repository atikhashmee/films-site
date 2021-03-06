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
      <?php } ?>
    </ul>
  </div>
</div>
</nav>
<div class="container animated fadeIn" onclick="hideSearch();">
  <div class="row">
    <div class="col-lg-12">
      <div class="settings-container">
        <h1><?=$muviko->translate('My_Account')?></h1>
        <hr>
        <div class="row">
          <div class="col-lg-3 pull-left">
            <div class="left-title"><?=$muviko->translate('Membership_And_Billing')?></div>
            <p>By upgrading to gold you will lose all your outstanding standard membership time left.</p>
            <form action="" method="post">
              <select name="plan" required>
              <option value="">SELECT PLAN</option>
              <?php
              $member = $db->query("SELECT * FROM membership_plan");
              if ($member->num_rows > 0) {
                while($row = $member->fetch_assoc()) {
              ?>
                <option value="<?php echo $row["id"];?>"><?php echo $row["membership_plan"];?></option>
                <?php
                  }
                }
                ?>
              </select>
              <?php //if($muviko->user->subscription_expiration > time()) { ?>
             <!--  <button type="button" class="btn btn-danger btn-fill btn-block" disabled><?php //$muviko->translate('Renew_Membership')?></button> -->
              <?php //} elseif($muviko->user->subscription_expiration < time() && $muviko->user->subscription_expiration > 0) { 
                ?>
              <button type="submit" name="renew_membership" class="btn btn-danger btn-fill btn-block"><?=$muviko->translate('Renew_Membership')?></button>
              <?php //} elseif($muviko->user->subscription_expiration == 0) { ?>
              <button type="submit" name="upgrade_membership" class="btn btn-danger btn-fill btn-block"><?=$muviko->translate('Upgrade_Membership')?></button>
              <?php// } ?>
            </form>
          </div>
          <div class="col-lg-5 pull-left" style="padding-left:50px;">
            <b><?=$muviko->user->email?></b> <br>
            <?=$muviko->translate('Password')?>: ******** <br>
            <?=$muviko->translate('Phone')?>: <?=$muviko->user->phone?>
            <hr>
            <h2><?=$muviko->translate('Redeem_Promo_Code')?></h2>
            <form method="post">
              <div class="input-group">
                <input type="text" name="promo_code" placeholder="<?=$muviko->translate('Enter_Code')?>" class="form-control input-dark">
                <span class="input-group-btn">
                  <button type="submit" name="redeem" class="btn btn-default btn-fill redeem-btn" type="button"><?=$muviko->translate('Redeem')?></button>
                </span>
              </div>
            </form>
          </div>
          <div class="col-lg-4 pull-left">
            <a href="<?=$muviko->getDomain()?>/email.php"><?=$muviko->translate('Change_Email')?></a> <br>
            <a href="<?=$muviko->getDomain()?>/password.php"><?=$muviko->translate('Change_Password')?></a> <br>
            <a href="<?=$muviko->getDomain()?>/phone.php"><?=$muviko->translate('Change_Phone')?></a> 
          </div>
        </div>
        <hr class="space">
        <div class="row">
          <div class="col-lg-4 pull-left">
            <div class="left-title"><?=$muviko->translate('Plan_Details')?></div>
          </div>
          <div class="col-lg-4 pull-left">
           <span class="plan-name">
             <?php if($muviko->user->is_subscriber == 1) { ?>
             <?=sprintf($muviko->translate('Paid_Membership'),$muviko->settings->subscription_name)?>
			<?php
			 $toTime = date('Y-m-d',$muviko->user->subscription_expiration);
			echo '<br />Membership Left - '.getTimeLeft(date('Y-m-d'),$toTime);
			 ?>
             <?php } 
              elseif($muviko->user->is_subscriber == 2){
                echo  "Gold Membership";
$toTime = date('Y-m-d',$muviko->user->subscription_expiration);
      echo '<br />Membership Left - '.getTimeLeft(date('Y-m-d'),$toTime);
              }
             else { ?>
             <?=$muviko->translate('Free_Membership')?>
             <?php } ?>
           </span>
         </div>
         <div class="col-lg-4 pull-left">
          <!-- <a href="#"> Change plan </a> -->
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-lg-4 pull-left">
          <div class="left-title"><?=$muviko->translate('Settings')?></div>
        </div>
        <div class="col-lg-4 pull-left">
         <a href="<?=$muviko->getDomain()?>/mass_logout.php"><?=$muviko->translate('Sign_Out_All_Devices')?></a> <br>
         <a href="<?=$muviko->getDomain()?>/account_activity.php"><?=$muviko->translate('View_Account_Activity')?></a> <br>
       </div>
     </div>
     <hr>
     <div class="row">
      <div class="col-lg-4 pull-left">
        <div class="left-title"><?=$muviko->translate('My_Profile')?></div>
      </div>
      <div class="col-lg-4 pull-left">
       <div class="avatar">
        <img src="<?=$muviko->getProfilePicture()?>" class="img-responsive">
        <span class="name"> <?=$profile->profile_name?> </span>
      </div>
      <div class="clearfix"></div>
      <a href="<?=$muviko->getDomain()?>/language.php"><?=$muviko->translate('Language')?></a>
    </div>
    <div class="col-lg-4 pull-left">
     <a href="<?=$muviko->getDomain()?>/manage_profiles.php"><?=ucfirst(strtolower($muviko->translate('Manage_Profiles')))?></a> <br>
   </div>
 </div>
</div>
</div>
</div>
</div>
<style>
  select{
    color: #000 !important;
    padding: 5px 8px;
    border: none;
    box-shadow: none;
    background-image: none;
    -webkit-appearance: none;
    margin-bottom: 20px;
    width: 100%;
  }
</style>