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

if(isset($_POST['save'])) {
    $show_actors = $_POST['show_actors'];
    $show_profiles = $_POST['show_profiles'];
    $kid_profiles = $_POST['kid_profiles'];
    $db->query("UPDATE settings SET show_actors='".$show_actors."',show_profiles='".$show_profiles."',kid_profiles='".$kid_profiles."'");
    header('Location: themes.php');
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
<title>Admin panel</title>
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
        Themes
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
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading panel-title">Theme Settings</div>
                <div class="panel-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <label> Actors Module </label>
                            <?php if($admin->settings->supports_starring == 0) { ?>
                            <select name="show_actors" class="form-control chosen" disabled>
                                <option value="0"> Disabled </option>
                            </select>
                            <?php } else { ?>
                            <select name="show_actors" class="form-control chosen" required>
                                <option value="1" <?php echo ($admin->settings->show_actors == 1 ? 'selected' : false); ?>> Enabled </option>
                                <option value="0" <?php echo ($admin->settings->show_actors == 0 ? 'selected' : false); ?>> Disabled </option>
                            </select>
                            <?php } ?>
                            <?php if($admin->settings->supports_starring == 0) { ?>
                            <div class="help-block"> 
                                This feature is not supported by the <b><?=ucfirst($admin->settings->theme)?></b> theme
                            </div>
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            <label> Profiles Module </label>
                            <?php if($admin->settings->supports_profiles == 0) { ?>
                            <select name="show_profiles" class="form-control chosen" disabled>
                                <option value="0"> Disabled </option>
                            </select>
                            <?php } else { ?>
                            <select name="show_profiles" class="form-control chosen" required>
                                <option value="1" <?php echo ($admin->settings->show_profiles == 1 ? 'selected' : false); ?>> Enabled </option>
                                <option value="0" <?php echo ($admin->settings->show_profiles == 0 ? 'selected' : false); ?>> Disabled </option>
                            </select>
                            <?php } ?>
                            <?php if($admin->settings->supports_profiles == 0) { ?>
                            <div class="help-block"> 
                                This feature is not supported by the <b><?=ucfirst($admin->settings->theme)?></b> theme
                            </div>
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            <label> Kids Mode </label>
                            <?php if($admin->settings->supports_profiles == 0) { ?>
                            <select name="kid_profiles" class="form-control chosen" disabled>
                                <option value="0"> Disabled </option>
                            </select>
                            <?php } else { ?>
                            <select name="kid_profiles" class="form-control chosen" required>
                                <option value="1" <?php echo ($admin->settings->kid_profiles == 1 ? 'selected' : false); ?>> Enabled </option>
                                <option value="0" <?php echo ($admin->settings->kid_profiles == 0 ? 'selected' : false); ?>> Disabled </option>
                            </select>
                            <?php } ?>
                            <?php if($admin->settings->supports_profiles == 0) { ?>
                            <div class="help-block"> 
                                This feature is not supported by the <b><?=ucfirst($admin->settings->theme)?></b> theme
                            </div>
                            <?php } ?>
                        </div>
                        <button type="submit" name="save" class="btn btn-success btn-fill btn-wd">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php 
    if(count($themes) >= 1) {
        foreach($themes as $theme) {
            if(substr($theme,0,1) != '.') {
                echo '<div class="theme-card card">';
                echo '
                <div class="poster"
                style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$admin->getDomain().'/themes/'.$theme.'/theme.jpg\');
                background-image: -moz-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$admin->getDomain().'/themes/'.$theme.'/theme.jpg\');
                background-image: -o-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$admin->getDomain().'/themes/'.$theme.'/theme.jpg\');
                background-image: -ms-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$admin->getDomain().'/themes/'.$theme.'/theme.jpg\');
                background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url(\''.$admin->getDomain().'/themes/'.$theme.'/theme.jpg\');">
                </div>';
                if($admin->settings->theme == $theme) {
                    echo '<div class="action"><a href="#" class="btn btn-default btn-lg btn-fill disabled">Active</a></div>';
                } else {
                    echo '<div class="action"><a href="activate_theme.php?theme='.$theme.'" class="btn btn-success btn-lg btn-fill">Activate</a></div>';
                }
                echo '<div class="title">'.ucfirst($theme).'</div>';
                echo '</div>';
            }  
        }
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
<style>
    .theme-card{
        display: none;
    }
</style>
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
