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
if(isset($_POST['savepaypal'])) {
    $paypal_email = $_POST['paypal_email'];
    // $subscription_name = $_POST['subscription_name'];
    // $subscription_price = $_POST['subscription_price'];
    $subscription_currency = $_POST['subscription_currency'];
    $db->query("UPDATE settings SET paypal_email='".$paypal_email."',subscription_currency='".$subscription_currency."'");
    header('Location: membership_subscription.php');
    exit;
}
if(isset($_REQUEST['id'])){
  $db->query("DELETE FROM membership_plan WHERE id='".$_REQUEST['id']."'");
  header('Location: membership_subscription.php');
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
               Membership Subscription
<a href="add_membership.php" class="btn btn-success btn-fill btn-xs pull-left" style="margin-top:21px;"> <i class="ti-plus"></i> Add Membership </a> </a>

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
   <div class="container">
   <form method="POST">
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div class="panel-heading panel-title">Subcription</div>
<div class="panel-body">

<div class="form-group">
    <label> PayPal Email </label>
    <input type="email" name="paypal_email" class="form-control border-input" value="<?=$admin->settings->paypal_email?>" required>
</div>
<div class="form-group">
    <label> Subscription Currency </label>
    <select name="subscription_currency" class="form-control chosen" required>
        <option value="AUD" <?php echo ($admin->settings->subscription_currency == 'AUD' ? 'selected' : false); ?>> AUD </option>
        <option value="BRL" <?php echo ($admin->settings->subscription_currency == 'BRL' ? 'selected' : false); ?>> BRL </option>
        <option value="CAD" <?php echo ($admin->settings->subscription_currency == 'CAD' ? 'selected' : false); ?>> CAD </option>
        <option value="CZK" <?php echo ($admin->settings->subscription_currency == 'CZK' ? 'selected' : false); ?>> CZK </option>
        <option value="DKK" <?php echo ($admin->settings->subscription_currency == 'DKK' ? 'selected' : false); ?>> DKK </option>
        <option value="EUR" <?php echo ($admin->settings->subscription_currency == 'EUR' ? 'selected' : false); ?>> EUR </option>
        <option value="HKD" <?php echo ($admin->settings->subscription_currency == 'HKD' ? 'selected' : false); ?>> HKD </option>
        <option value="HUF" <?php echo ($admin->settings->subscription_currency == 'HUF' ? 'selected' : false); ?>> HUF </option>
        <option value="ILS" <?php echo ($admin->settings->subscription_currency == 'ILS' ? 'selected' : false); ?>> ILS </option>
        <option value="JPY" <?php echo ($admin->settings->subscription_currency == 'JPY' ? 'selected' : false); ?>> JPY </option>
        <option value="MYR" <?php echo ($admin->settings->subscription_currency == 'MYR' ? 'selected' : false); ?>> MYR </option>
        <option value="MXN" <?php echo ($admin->settings->subscription_currency == 'MXN' ? 'selected' : false); ?>> MXN </option>
        <option value="NOK" <?php echo ($admin->settings->subscription_currency == 'NOK' ? 'selected' : false); ?>> NOK </option>
        <option value="NZD" <?php echo ($admin->settings->subscription_currency == 'NZD' ? 'selected' : false); ?>> NZD </option>
        <option value="PHP" <?php echo ($admin->settings->subscription_currency == 'PHP' ? 'selected' : false); ?>> PHP </option>
        <option value="PLN"> PLN </option>
        <option value="GBP" <?php echo ($admin->settings->subscription_currency == 'GBP' ? 'selected' : false); ?>> GBP </option>
        <option value="RUB" <?php echo ($admin->settings->subscription_currency == 'RUB' ? 'selected' : false); ?>> RUB </option>
        <option value="SGD" <?php echo ($admin->settings->subscription_currency == 'SGD' ? 'selected' : false); ?>> SGD </option>
        <option value="SEK" <?php echo ($admin->settings->subscription_currency == 'SEK' ? 'selected' : false); ?>> SEK </option>
        <option value="CHF" <?php echo ($admin->settings->subscription_currency == 'CHF' ? 'selected' : false); ?>> CHF </option>
        <option value="TWD"> TWD </option>
        <option value="THB" <?php echo ($admin->settings->subscription_currency == 'TWD' ? 'selected' : false); ?>> THB </option>
        <option value="USD" <?php echo ($admin->settings->subscription_currency == 'USD' ? 'selected' : false); ?>> USD </option>
    </select>
</div>
</div>
</div>
</div>
</div>
<button type="submit" name="savepaypal" class="btn btn-success btn-fill btn-wd">Save</button>
               <!--  </div> -->
                <div class="clearfix"></div>
            </form>
              <div style="height: 50px;"></div>
  <div class="row">
    <?php
      $member = $db->query("SELECT * FROM membership_plan");
      if ($member->num_rows > 0) {
        while($row = $member->fetch_assoc()) {
          //echo "id: " . $row["id"];
          //print_r($row);
     ?>
          <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
        
          <!-- PRICE ITEM -->
          <div class="panel price panel-red">
            <div class="panel-heading  text-center">
            <h3><?php echo $row["membership_plan"];?></h3>
            </div>
            <div class="panel-body text-center">
              <p class="lead" style="font-size:40px"><strong>$ <?php echo $row["price"];?></strong></p>
            </div>
           <!--  <ul class="list-group list-group-flush text-center">
              <li class="list-group-item"><i class="icon-ok text-danger"></i> Personal use</li>
              <li class="list-group-item"><i class="icon-ok text-danger"></i> Unlimited projects</li>
              <li class="list-group-item"><i class="icon-ok text-danger"></i> 27/7 support</li>
            </ul> -->
            <div class="panel-footer">
              <a class="btn btn-lg btn-block btn-danger" href="<?=$admin->getDomain();?>/admin/edit_membership.php?id=<?=$row["id"];?>">Edit</a> 
              <a class="btn btn-lg btn-block btn-danger" href="<?=$admin->getDomain();?>/admin/membership_subscription.php?id=<?=$row["id"];?>">Delete</a>
            </div>
          </div>
          <!-- /PRICE ITEM -->
        </div> 
        <?php
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
  @import url("http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css");
@import url("http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&subset=latin,latin-ext");

  /* COMMON PRICING STYLES */
    .panel.price,
    .panel.price>.panel-heading{
      border-radius:0px;
       -moz-transition: all .3s ease;
      -o-transition:  all .3s ease;
      -webkit-transition:  all .3s ease;
    }
    .panel.price:hover{
      box-shadow: 0px 0px 30px rgba(0,0,0, .2);
    }
    .panel.price:hover>.panel-heading{
      box-shadow: 0px 0px 30px rgba(0,0,0, .2) inset;
    }
    
        
    .panel.price>.panel-heading{
      box-shadow: 0px 5px 0px rgba(50,50,50, .2) inset;
      text-shadow:0px 3px 0px rgba(50,50,50, .6);
    }
      
    .price .list-group-item{
      border-bottom-:1px solid rgba(250,250,250, .5);
    }
    
    .panel.price .list-group-item:last-child {
      border-bottom-right-radius: 0px;
      border-bottom-left-radius: 0px;
    }
    .panel.price .list-group-item:first-child {
      border-top-right-radius: 0px;
      border-top-left-radius: 0px;
    }
    
    .price .panel-footer {
      color: #fff;
      border-bottom:0px;
      background-color:  rgba(0,0,0, .1);
      box-shadow: 0px 3px 0px rgba(0,0,0, .3);
    }
    
    
    .panel.price .btn{
      box-shadow: 0 -1px 0px rgba(50,50,50, .2) inset;
      border:0px;
    }
    
  /* green panel */
  
    
    .price.panel-green>.panel-heading {
      color: #fff;
      background-color: #57AC57;
      border-color: #71DF71;
      border-bottom: 1px solid #71DF71;
    }
    
      
    .price.panel-green>.panel-body {
      color: #fff;
      background-color: #65C965;
    }
        
    
    .price.panel-green>.panel-body .lead{
        text-shadow: 0px 3px 0px rgba(50,50,50, .3);
    }
    
    .price.panel-green .list-group-item {
      color: #333;
      background-color: rgba(50,50,50, .01);
      font-weight:600;
      text-shadow: 0px 1px 0px rgba(250,250,250, .75);
    }
    
    /* blue panel */
  
    
    .price.panel-blue>.panel-heading {
      color: #fff;
      background-color: #608BB4;
      border-color: #78AEE1;
      border-bottom: 1px solid #78AEE1;
    }
    
      
    .price.panel-blue>.panel-body {
      color: #fff;
      background-color: #73A3D4;
    }
        
    
    .price.panel-blue>.panel-body .lead{
        text-shadow: 0px 3px 0px rgba(50,50,50, .3);
    }
    
    .price.panel-blue .list-group-item {
      color: #333;
      background-color: rgba(50,50,50, .01);
      font-weight:600;
      text-shadow: 0px 1px 0px rgba(250,250,250, .75);
    }
    
    /* red price */
    
  
    .price.panel-red>.panel-heading {
      color: #fff;
      background-color: #D04E50;
      border-color: #FF6062;
      border-bottom: 1px solid #FF6062;
    }
    
      
    .price.panel-red>.panel-body {
      color: #fff;
      background-color: #EF5A5C;
    }
    
    
    
    
    .price.panel-red>.panel-body .lead{
        text-shadow: 0px 3px 0px rgba(50,50,50, .3);
    }
    
    .price.panel-red .list-group-item {
      color: #333;
      background-color: rgba(50,50,50, .01);
      font-weight:600;
      text-shadow: 0px 1px 0px rgba(250,250,250, .75);
    }
    
    /* grey price */
    
  
    .price.panel-grey>.panel-heading {
      color: #fff;
      background-color: #6D6D6D;
      border-color: #B7B7B7;
      border-bottom: 1px solid #B7B7B7;
    }
    
      
    .price.panel-grey>.panel-body {
      color: #fff;
      background-color: #808080;
    }
    

    
    .price.panel-grey>.panel-body .lead{
        text-shadow: 0px 3px 0px rgba(50,50,50, .3);
    }
    
    .price.panel-grey .list-group-item {
      color: #333;
      background-color: rgba(50,50,50, .01);
      font-weight:600;
      text-shadow: 0px 1px 0px rgba(250,250,250, .75);
    }
    
    /* white price */
    
  
    .price.panel-white>.panel-heading {
      color: #333;
      background-color: #f9f9f9;
      border-color: #ccc;
      border-bottom: 1px solid #ccc;
      text-shadow: 0px 2px 0px rgba(250,250,250, .7);
    }
    
    .panel.panel-white.price:hover>.panel-heading{
      box-shadow: 0px 0px 30px rgba(0,0,0, .05) inset;
    }
      
    .price.panel-white>.panel-body {
      color: #fff;
      background-color: #dfdfdf;
    }
        
    .price.panel-white>.panel-body .lead{
        text-shadow: 0px 2px 0px rgba(250,250,250, .8);
        color:#666;
    }
    
    .price:hover.panel-white>.panel-body .lead{
        text-shadow: 0px 2px 0px rgba(250,250,250, .9);
        color:#333;
    }
    
    .price.panel-white .list-group-item {
      color: #333;
      background-color: rgba(50,50,50, .01);
      font-weight:600;
      text-shadow: 0px 1px 0px rgba(250,250,250, .75);
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
