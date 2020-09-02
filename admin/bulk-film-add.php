<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db, $domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);
$errors = '';
$errorImdbId = array();
$haveError = false;
if (isset($_FILES['excel-upload']) && $_FILES['excel-upload']['name'] != '') 
    {
        $alredyExists = array();
        $handle = fopen($_FILES['excel-upload']['tmp_name'], 'r');
        

    //   require 'class_IMDb.php';
    //   $imdb = new IMDb(true);
    // 	$imdb->summary=false;
    $isnotInsert = array();
    $is_kid_friendly = 0;
    $returnArr= [];
    while ($data = fgetcsv($handle, 1000, ",")) 
    {
        /* echo '<pre>';
        print_r($data);
        exit; */
        $imdbid = $data[0];
        $video = (isset($data[1]) && $data[1] != '')?$data[1]:'';

        $ex = new stdclass;
        $ex->video_url = $video;
        $returnArr[] = _storeFilm($imdbid, $ex);
    }
    fclose($handle);
    if($haveError){
      $errorIID = "&not_added=".implode(',',$errorImdbId);
    }
    echo '<pre>';
    print_r($returnArr);
  //  print_r($errorImdbId);
  //  print_r($alredyExists);
    header('Location: films.php?success=1&ae=' . implode(',', $alredyExists).$errorIID);
    exit();
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
    <?php require_once "header.php"; ?>
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
                    <a class="navbar-brand" href="#">Bulk Add
                        <a href="<?= $admin->getDomain() ?>/admin/filmids.csv" style="margin-top:21px;" download class="btn btn-success btn-fill btn-xs pull-left">
                            <i class="ti-download"></i>
                            Download Sample File
                        </a>
                    </a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?= $admin->getDomain() ?>">
                                <i class="ti-arrow-left"></i>
                                <p>Back to User Area</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="card">
                <div class="header">
                    <h4 class="title">Add Bulk Film</h4>
                </div>
                <div class="content">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading panel-title">Choose your file</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <input type="file" name="excel-upload" class="form-control" accept="application/csv"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-fill">Upload</button>
                        </div>
                    </form>
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
