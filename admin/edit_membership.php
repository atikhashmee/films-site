<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);

$themes = $admin->getThemes('../themes');
$rid = $_REQUEST["id"];
if(isset($_POST['update_plan'])) {
    $membership_plan = $_POST['membership_plan'];
    $price = $_POST['price'];
    $time_period = $_POST['time_period'];
    $db->query("UPDATE membership_plan SET membership_plan='".$membership_plan."',price='".$price."',time_period='".$time_period."' WHERE id='".$rid."'");
    header('Location: membership_subscription.php');
    exit;
}
$member = $db->query("SELECT * FROM membership_plan WHERE id='".$rid."'");
$row = $member->fetch_assoc();
//print_r($row);
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
<link href="assets/css/chosen.min.css" rel="stylesheet"/>
<link href="assets/css/chosen-bootstrap.css" rel="stylesheet"/>
<link href="assets/css/style.css" rel="stylesheet"/>
<link href="assets/css/plugins.css" rel="stylesheet"/>
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
      Update Membership
    </a>
        
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
                    <h4 class="title">Update Membership Plan</h4>
                </div>
                <div class="content">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Membership Subscription Plan</label>
                                    <input type="text" name="membership_plan" class="form-control border-input" placeholder="Enter a name for this category" value="<?=$row['membership_plan'];?>" required>
                                </div>
                            </div>
                        </div>
                       <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Price $</label>
                                <input type="text" name="price" class="form-control border-input" placeholder="Price" value="<?=$row['price'];?>" required>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Time Period</label>
                                <input type="text" name="time_period" class="form-control border-input" placeholder="Price" value="<?=$row['time_period'];?>" required>
                            </div>
                          </div>
                        </div>
                        <div class="pull-left">
                            <button type="submit" name="update_plan" class="btn btn-success btn-fill btn-wd">Update Plan</button>
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
<script type="text/javascript" src="assets/js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script src="assets/js/theme.js"></script>
<script>
$('.chosen').chosen({disable_search_threshold: 10});
</script>
</html>