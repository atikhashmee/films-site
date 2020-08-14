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

$genre = $admin->getGenre($id);

if(isset($_POST['save'])) {
    $name = $_POST['category_name'];
     $name = str_replace(' ', '-', $name);
    if($admin->settings->kid_profiles == 0) {
        $is_kid_friendly = 0;
    } else {
        $is_kid_friendly = $_POST['is_kid_friendly'];
    }
    $db->query("UPDATE genres SET genre_name='".$name."',is_kid_friendly='".$is_kid_friendly."' WHERE id='".$genre->id."'");
    header('Location: categories.php?success=1');
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
                    <a class="navbar-brand" href="#">Edit Category</a>
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
                    <h4 class="title"><?=$genre->genre_name?></h4>
                </div>
                <div class="content">
                    <?php if(isset($error)) { ?> <div class="alert alert-danger"> <?=$error?> </div> <? } ?>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="category_name" class="form-control border-input" placeholder="Enter a new name for this category" value="<?=$genre->genre_name?>" required>
                                </div>
                            </div>
                        </div>
                        <?php if($admin->settings->kid_profiles == 1) { ?>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Can be viewed by kids?</label>
                                    <select name="is_kid_friendly" class="form-control">
                                        <option value="0" <?php echo ($genre->is_kid_friendly == 0 ? 'selected' : false); ?> ?> False </option>
                                        <option value="1" <?php echo ($genre->is_kid_friendly == 1 ? 'selected' : false); ?> ?> True </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="pull-left">
                            <button type="submit" name="save" class="btn btn-success btn-fill btn-wd">Save</button>
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
