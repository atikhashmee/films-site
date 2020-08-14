<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);
$page = (isset($_GET["page"]))?$_GET["page"]:1;
$search = (isset($_GET["s"]))?$_GET["s"]:'';

$WHERE = '';
if($search != ''){
    $WHERE = " WHERE actor_name LIKE '%$search%'";
}
$actors = $db->query("SELECT * FROM actors $WHERE LIMIT 50");
$totalActors = $db->query("SELECT COUNT(*) FROM actors $WHERE")->fetch_array()[0];
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
                        Actors
                        <a href="add_actor.php" class="btn btn-success btn-fill btn-xs pull-left" style="margin-top:21px;"> <i class="ti-plus"></i> Add actor </a> </a>
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
                <div class="col-md-4">
                    <button type="button" class="btn btn-success btn-fill" id="eDDB">Enable Delete</button>
                </div>
                <div class="col-md-4 pull-right">
                    <form method="get">
                        <div class="input-group">
                            <input type="text" name="s" class="form-control" value="<?=$search?>" placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit"><span class="ti-search"></span></button>
                            </span>
                        </div><!-- /input-group -->
                    </form>
                </div>
                <div class="clearfix"></div>
                <hr />
                <div class="container-fluid">
                 <?php
                 if($actors->num_rows >= 1) {
                   $p = $actors->fetch_object();
                    while($actor= $actors->fetch_object()) {
                        $picture = $admin->getDomain().'/uploads/actors/'.$actor->actor_picture;
                        $style = '';
                        // if($actor->actor_picture == '' || !filesize($picture)){
                        //     $picture = $admin->getDomain().'/images/default-user.png';
                        //     $style = 'background-position: center;';
                        // }
                        echo '<div class="actor-card">';
                        echo '
                        <div class="poster"
                        style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$picture.'\');
                        background-image: -moz-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$picture.'\');
                        background-image: -o-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$picture.'\');
                        background-image: -ms-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$picture.'\');
                        background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$picture.'\');
                        '.$style.'">
                        </div>';
                        echo '<div class="edit"><a href="edit_actor.php?id='.$actor->id.'"><i class="ti-pencil"></i></a></div>';
                        echo '<div class="delete"><a href="delete_actor.php?id='.$actor->id.'"><i class="ti-trash"></i></a></div>';
                        echo '<div class="title"><a href="'.$admin->getDomain().'/actor_profile.php?name='.$actor->actor_nconst.'" target="_blank">'.$actor->actor_name.'</a></div>';//$admin->trimNames()
                        echo '</div>';
                    }
                    $ouput = '<div class="clearfix"></div>
                    <div class="text-right col-md-12">';
                    $ouput .= $admin->pagination($totalActors,$page);
                    $ouput .= '</div>';
                    echo $ouput;
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
