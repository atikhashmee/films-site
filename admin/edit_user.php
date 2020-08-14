<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);
$id = $_GET['id'];
$user = $admin->getUser($id);
if(isset($_POST['save'])) {
    $name = $_POST['user_name'];
   //$plan = 2;
    $changePassword = '';
    if($_POST['user_password'] != ''){
        $password = $admin->hashPassword($_POST['user_password']);
        $changePassword = ",password='".$password."'";
    }
    $phone = $_GET['user_phone'];
    $rank = $_POST['user_rank'];
    $exprirationTime = '';
    if($rank === 'subscriber') {
        $rank = 0;
        $is_subscriber = 1;
        $subscription_expiration = strtotime('+31 days',time());
        $exprirationTime = ", subscription_expiration='$subscription_expiration'";
    } elseif ($rank === 'gold') {
      $rank = 0;
      $is_subscriber = 2;

    }else {
        $is_subscriber = 0;
    }

    if($_POST['extend_subs'] != ''){
	    $subs_time = $_POST['extend_subs_time'];
	    $extendSubs = $_POST['extend_subs']*$subs_time;
	    //debug($extendSubs);
        $subscription_expiration = strtotime('+'.$extendSubs.' days',time());
        $exprirationTime = " , subscription_expiration='$subscription_expiration'";
    }
    $db->query("UPDATE users SET name='".$name."' {$changePassword} ,phone='".$phone."',phone_country_code='".$phone."',is_admin='".$rank."',
        is_subscriber='".$is_subscriber."' $exprirationTime WHERE id='".$user->id."'");
    header('Location: users.php?success=1');
    exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Admin Panel</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/animate.min.css" rel="stylesheet"/>
    <link href="assets/css/theme.css" rel="stylesheet"/>
    <link href="assets/css/style.css" rel="stylesheet"/>
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <link href="assets/css/themify-icons.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
    <?php require_once "header.php";?>
    <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="#">Edit User</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?=$admin->getDomain()?>">
                                <i class="ti-arrow-left"></i>
                                <p>Back to User Area</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container-fluid">
              <div class="card">
                <div class="header">
                    <h4 class="title"><?=$user->name?></h4>
                </div>
                <div class="content">
                    <?php
                    if(isset($_GET['action']) && $_GET['action'] == 'activity'){
                       $sessions =  $db->query("SELECT * FROM sessions WHERE user_id='".$id."'");
                        ?>
      <table class="table table-responsive card">
          <thead>
          <th> Date &amp; Time </th>
          <th> Location </th>
          <th> Device </th>
          <th> Status </th>
          </thead>
          <tbody>
              <?php
              if($sessions->num_rows > 0){
                  while($ses = $sessions->fetch_object()){
                      echo '
                          <tr>
                              <td>'.date('F j, Y, g:i A',$ses->time).'</td>
                              <td>'.$admin->getCountryByIp($ses->user_ip).'<br>'.$ses->user_ip.'</td>
                              <td>Mac OS X Device</td>
                              <td>'.$admin->readableSessionStatus($ses->is_active).'</td>
                          </tr>
                      ';
                  }
              }
              else{
                  echo '
                  <tr>
                      <td colspan="4">No Results found</td>
                  </tr>
              ';
              }
              ?>
          </tbody>
      </table>

                        <?php
                    }
                    else{

                      ?>
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="user_name" class="form-control border-input" placeholder="Enter a name for this user" value="<?=$user->name?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="user_email" class="form-control border-input" readonly value="<?=$user->email?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>New Password (optional)</label>
                                        <input type="password" name="user_password" class="form-control border-input" placeholder="Enter a new password for this user">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Phone (optional)</label>
                                        <input type="text" name="user_phone" class="form-control border-input" placeholder="Enter a new phone for this user" value="<?=$user->phone_country_code?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Rank</label>
                                        <select name="user_rank" class="form-control">
                                            <option value="0" <?php echo ($user->is_subscriber == 0 ? 'selected' : false); ?>> User </option>
                                            <option value="subscriber" <?php echo ($user->is_subscriber == 1 ? 'selected' : false); ?>> Subscriber </option>
                                            <option value="1" <?php echo ($user->is_admin == 1 ? 'selected' : false); ?>> Admin </option>
                                            <option value="gold" <?php echo ($user->is_subscriber == 2 ? 'selected' : false); ?>> Gold </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
					    <div class="row">
					    	<div class="form-group">
							<div class="col-lg-3">
								<?php $toTime = date('Y-m-d',$user->subscription_expiration);?>
								<label>Subscription (Left :- <?=getTimeLeft(date('Y-m-d'),$toTime)?>)</label>
							</div>
						 </div>
					    </div>
					    <div class="row">
                                <div class="col-lg-8 form-inline">
                                    <div class="form-group">
								 <input type="number" name="extend_subs_time" class="form-control"/>
                                    </div>
							  <div class="form-group">
								  <select name="extend_subs" class="form-control">
                                            <option value="" >Extend Subscription</option>
                                            <option value="1">Day</option>
                                            <option value="7">Week</option>
                                            <option value="31">Month</option>
                                            <option value="365">Year</option>
                                        </select>
							  </div>
                                </div>
                            </div>
                            <div class="pull-left">
                                <button type="submit" name="save" class="btn btn-success btn-fill btn-wd">Save</button>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container-fluid">
            <div class="copyright pull-left">
            &nbsp;
            </div>
        </div>
    </footer>
</div>
</div>
</body>
<script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap-checkbox-radio.js"></script>
<script src="assets/js/chartist.min.js"></script>
<script src="assets/js/bootstrap-notify.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script src="assets/js/theme.js"></script>
</html>
