<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);
if(isset($_GET['action']) && $_GET['action']=='suspend'){
    $id = $_GET['id'];
    $db->query("UPDATE users SET is_suspended='1' WHERE id=$id");
    header('Location: users.php');
}elseif(isset($_GET['action']) && $_GET['action']=='unsuspend'){
///    $subscription_expiration = strtotime('+31 days',time());
    $id = $_GET['id'];
  //  $db->query("UPDATE users SET subscription_expiration='$subscription_expiration',is_suspended='0'   WHERE id=$id");
  $db->query("UPDATE users SET is_suspended='0' WHERE id=$id");
  //echo "UPDATE users SET is_suspended='0' WHERE id=$id";
  header('Location: users.php');

}
//$users = $admin->getUsers();
$users = $db->query("SELECT * FROM users");

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
                    <a class="navbar-brand" href="#">
                        Users
                        <a href="add_user.php" class="btn btn-success btn-fill btn-xs pull-left" style="margin-top:21px;"> <i class="ti-plus"></i> Add User </a> </a>
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
                 <?php
                 if($users->num_rows >= 1) {

                    echo '<table class="table table-responsive card">';
                    echo '<thead>';
                    echo '<th class="text-center"> # </th>';
                    echo '<th class="text-center"> Email </th>';
                    echo '<th class="text-center"> Name </th>';
                    echo '<th class="text-center"> Rank </th>';
                    echo '<th class="text-center"> Subscription </th>';
				            echo '<th class="text-center"> Last Login </th>';
                    echo '<th class="text-center"> Actions </th>';
                    echo '</thead>';
                    echo '<tbody>';
                    while($user = $users->fetch_object()) {

                        $subsEnd = 'Unlimited';
					           $lastLoin = $db->query("SELECT time FROM sessions WHERE user_id=$user->id ORDER BY time DESC")->fetch_assoc();
                        if($user->subscription_expiration != 0){
                            $subsEnd = date("d, M Y",$user->subscription_expiration);
                        }
					           $lastLogin=date("d, M Y",$lastLoin['time']);
                        echo '<tr>';
                        echo '<td class="text-center">'.$user->id.'</td>';
                        echo '<td class="text-center">'.$user->email.'</td>';
                        echo '<td class="text-center">'.$user->name.'</td>';
                        echo '<td class="text-center">'.$admin->getUserRank($user->is_admin,$user->is_subscriber).'</td>';
                        echo '<td class="text-center">'.$subsEnd.'</td>';
					              echo '<td class="text-center">'.$lastLogin.'</td>';
                        $suspendText = '';
                        if($user->is_admin == 0 && $user->is_subscriber == 1 || $user->is_subscriber == 2){
                            if($user->is_suspended == 1 ){
                                $suspendText = '<a href="users.php?id='.$user->id.'&action=unsuspend" class="btn btn-danger">Un-Suspend</a>';
                            }
                            else{
                                $suspendText = '<a href="users.php?id='.$user->id.'&action=suspend" class="btn btn-danger">Suspend</a>';
                            }
                        }
                        echo '
                        <td class="text-center">
                        <a href="edit_user.php?id='.$user->id.'&action=activity" class="btn btn-success">Activity</a>
                        '.$suspendText.'
                        <a href="edit_user.php?id='.$user->id.'" class="btn btn-success">Edit</a>
                        <a href="delete_user.php?id='.$user->id.'" class="btn btn-danger">Delete</a>
                        </td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                }
                ?>
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
