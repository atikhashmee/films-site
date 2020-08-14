<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);

if(isset($_POST['add'])) {
    $name = $_POST['user_name'];
    $first_name = explode(' ',$name);
    $first_name = $first_name[0];
    $email = $_POST['user_email'];
    $password = $admin->hashPassword($_POST['user_password']);
    $phone = $_GET['user_phone'];
    $rank = $_POST['user_rank'];
    if($rank === 'subscriber') {
        $rank = 0;
        $is_subscriber = 1;
        $subscription_expiration = strtotime('+31 days',time());
    } else {
        $is_subscriber = 0;
    }
    $random_avatar = rand(0,9);
    $db->query("INSERT INTO users (name,email,password,phone,phone_country_code,is_admin,is_subscriber,subscription_expiration) VALUES ('".$name."','".$email."','".$password."','".$phone."','".$phone."','".$rank."','".$is_subscriber."','".$subscription_expiration."')");
    $db->query("INSERT INTO profiles (user_id,profile_name,profile_avatar) VALUES ('".$db->insert_id."','".$first_name."','".$random_avatar."')");
    $db->query("UPDATE users SET last_profile='".$db->insert_id."'");
    header('Location: users.php?success=2');
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
                    <a class="navbar-brand" href="#">Add User</a>
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
                    <h4 class="title">New User</h4>
                </div>
                <div class="content">
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
                                    <input type="email" name="user_email" class="form-control border-input" placeholder="Enter a email for this user" value="<?=$user->email?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="user_password" class="form-control border-input" placeholder="Enter a password for this user" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Phone (optional)</label>
                                    <input type="text" name="user_phone" class="form-control border-input" placeholder="Enter a phone for this user">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Rank</label>
                                    <select name="user_rank" class="form-control">
                                        <option value="0"> User </option>
                                        <option value="subscriber"> Subscriber </option>
                                        <option value="1"> Admin </option>
                                        <option value="gold"> Gold </option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="pull-left">
                            <button type="submit" name="add" class="btn btn-success btn-fill btn-wd">Add User</button>
                        </div>
                        <div class="clearfix"></div>
                    </form>
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
