<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);

$genres = $admin->getGenres();

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
                        Categories
                        <a href="add_category.php" class="btn btn-success btn-fill btn-xs pull-left" style="margin-top:21px;"> <i class="ti-plus"></i> Add Category </a> </a>
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
                   if($genres->num_rows >= 1) {
                    echo '<table class="table table-responsive card">';
                    echo '<thead>';
                    echo '<th class="text-center"> # </th>';
                    echo '<th class="text-center"> Category </th>';
                    echo '<th class="text-center"> Kid Friendly </th>';
                    echo '<th class="text-center"> Actions </th>';
                    echo '</thead>';
                    echo '<tbody>';
                    while($genre = $genres->fetch_object()) {
                        echo '<tr>';
                        echo '<td class="text-center">'.$genre->id.'</td>';
                        echo '<td class="text-center">'.$genre->genre_name.'</td>';
                        echo '<td class="text-center">'.$admin->getKidFriendly($genre->is_kid_friendly).'</td>';
                        echo '
                        <td class="text-center">
                        <a href="edit_category.php?id='.$genre->id.'" class="btn btn-success">Edit</a>
                        <a href="delete_category.php?id='.$genre->id.'" class="btn btn-danger">Delete</a>
                        </td>
                        ';
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
