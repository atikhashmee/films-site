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
    $actor_name = $_POST['actor_name'];
    $actor_nconst = $_POST['actor_nconst'];
    $birthday = $_POST['birthday'];
    $place_of_birth = $_POST['place_of_birth'];
    $biography = addslashes($_POST['biography']);
    $actor_img_url = $_POST['actor_img_url'];
    $imdbid = $_POST['imdbid'];
    // Thumbnail Photo
    if(isset($_FILES['actor_picture']['name'])) {
        $extension = strtolower(end(explode('.', $_FILES['actor_picture']['name'])));
        if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
            if(!$_FILES['actor_picture']['error']) {
                $new_file_name = md5(mt_rand()).$_FILES['actor_picture']['name'];
                if($_FILES['actor_picture']['size'] > (10024000)) {
                    $valid_file = false;
                    $error = 'Oops! One of the photos you uploaded is too large';
                } else {
                    $valid_file = true;
                }
                if($valid_file) {
                    move_uploaded_file($_FILES['actor_picture']['tmp_name'], '../uploads/actors/'.$new_file_name);
                    $uploaded = true;
                }
            }
            else {
                $error = 'Error occured:  '.$_FILES['actor_picture']['error'];
            }
        }   
    }
    $db->query("INSERT INTO actors (actor_name,actor_picture,actor_nconst,birthday,place_of_birth,biography,actor_img_url,imdbid) VALUES ('".$actor_name."','".$new_file_name."','".$actor_nconst."','".$birthday."','".$place_of_birth."','".$biography."','".$actor_img_url."','".$imdbid."')");
    header('Location: actors.php?success=1');
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
                    <a class="navbar-brand" href="#">Add Actor</a>
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
                    <h4 class="title">New Actor</h4>
                </div>
                <div class="content">
                    <?php if(isset($error)) { ?> <div class="alert alert-danger"> <?=$error?> </div> <? } ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Actor Name</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <input type="text" name="actor_name" class="form-control border-input" placeholder="Enter a name" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Actor Constant</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <input type="text" name="actor_nconst" class="form-control border-input" placeholder="" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Birthday</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <input type="text" name="birthday" class="form-control border-input" placeholder="Enter a birthday">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Place of birth</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <input type="text" name="place_of_birth" class="form-control border-input" placeholder="Enter a place of birth">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Biography</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <input type="text" name="biography" class="form-control border-input" placeholder="Enter a biography">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Actor image imdb url</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <input type="text" name="actor_img_url" class="form-control border-input" placeholder="Enter a url">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Imdb ID</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <input type="text" name="imdbid" class="form-control border-input" placeholder="Enter a imdbid" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Actor Picture</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <input type="file" name="actor_picture" class="form-control border-input" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pull-left">
                            <button type="submit" name="add" class="btn btn-success btn-fill btn-wd">Add Actor</button>
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
