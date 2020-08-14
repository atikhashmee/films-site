<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);
$codes = $db->query("SELECT * FROM codes ORDER BY id DESC");
if(isset($_POST['generate'])) {
    $code = substr(strtoupper(md5(rand())),0,3).'-'.substr(strtoupper(md5(rand())),0,3).'-'.substr(strtoupper(md5(rand())),0,3);
    $action = $_POST['code_action'];
    $amount = $_POST['code_amount'];
    $gold_member = $_POST['gold_member'];
    if($gold_member==''){
       $gold_member = 'premium'; 
    }
    $multi_users = $_POST['multi_use'];
    switch ($amount) {
        case '1_day':
        $time = strtotime('+1 day',time());
        $time_plain = '1 day';
        break;
        case '1_week':
        $time = strtotime('+1 week',time());
        $time_plain = '1 week';
        break;
        case '1_month':
        $time = strtotime('+31 days',time());
        $time_plain = '1 month';
        break;
        default:
        $time = 0;
        break;
    }
    $db->query("INSERT INTO codes(code,amount,amount_plain,action,member,multi_users) VALUES ('".$code."','".$time."','".$time_plain."','".$action."','".$gold_member."','".$multi_users."')");
    header('Location: codes.php?success=1');
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
   <link href="assets/css/chosen.min.css" rel="stylesheet"/>
   <link href="assets/css/chosen-bootstrap.css" rel="stylesheet"/>
   <link href="assets/css/plugins.css" rel="stylesheet"/>
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
                    <a class="navbar-brand" href="#">Settings</a>
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
                <?php if(isset($error)) { ?> <div class="alert alert-danger"> <?=$error?> </div> <? } ?>
                <form action="" method="post">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-title">New Code</div>
                                <div class="panel-body"> 
                                    <div class="form-group">
                                        <label> Action </label>
                                        <select name="code_action" class="form-control chosen">
                                            <option value="add_subscription"> Add subscription </option>
                                            <option value="add_subscription_time"> Extend subscription time </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label> Amount </label>
                                        <select name="code_amount" class="form-control chosen">
                                            <option value="1_day"> 1 Day </option>
                                            <option value="1_week"> 1 Week </option>
                                            <option value="1_month"> 1 Month </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label> Check for gold member </label>
                                        <input type="checkbox" name="gold_member" value="gold">
                                    </div>
                                     <div class="form-group">
                                        <label> Check for multiple users use </label>
                                        <input type="checkbox" name="multi_use" value="1">
                                    </div>
                                    <button type="submit" name="generate" class="btn btn-success btn-fill">Generate</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    if($codes->num_rows >= 1) {
                        echo '<table class="table table-responsive card">';
                        echo '<thead>';
                        echo '<th class="text-center"> Code </th>';
                        echo '<th class="text-center"> Action </th>';
                        echo '<th class="text-center"> Amount </th>';
                        echo '</thead>';
                        echo '<tbody>';
                        while($code = $codes->fetch_object()) {
                            echo '<tr>';
                            echo '<td class="text-center">'.$code->code.'</td>';
                            echo '<td class="text-center">'.ucfirst(str_replace('_', ' ', $code->action)).'</td>';
                            echo '<td class="text-center">'.$code->amount_plain.'</td>';
                            echo '
                            <td class="text-center">
                            <a href="delete_code.php?id='.$code->id.'" class="btn btn-danger">Delete</a>
                            </td>
                            ';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    }
                    ?>
                </div>
                <div class="clearfix"></div>
            </form>
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
<style>
  .icons{
    display: none;
  }
</style>
<script>
$('.chosen').chosen({disable_search_threshold: 10});
function changeVideoFormat() {
    var format = $('#video_format').val();
    if(format == 0) {
        $('#video_file_div').show();
        $('#video_embed_div').hide();
    } else {
        $('#video_file_div').hide();
        $('#video_embed_div').show();
    }
}
</script>
</html>
