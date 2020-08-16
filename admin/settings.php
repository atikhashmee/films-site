<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);

if(isset($_POST['save'])) {
    $website_name = $_POST['website_name'];
    $website_title = $_POST['website_title'];
    $website_description = $_POST['website_description'];
    $website_keywords = $_POST['website_keywords'];
    $paypal_email = $_POST['paypal_email'];
    $subscription_name = $_POST['subscription_name'];
    $subscription_price = $_POST['subscription_price'];
    $subscription_currency = $_POST['subscription_currency'];
    $disquis_short_name = strtolower($_POST['disquis_short_name']);
    $jwplayer_key = trim($_POST['jwplayer_key']);
    $facebook_url = $_POST['facebook_url'];
    $twitter_url = $_POST['twitter_url'];

    $title1 = $_POST['title1'];
    $link1 = $_POST['link1'];
    $title2 = $_POST['title2'];
    $link2 = $_POST['link2'];

    $db->query("UPDATE settings SET website_name='".$website_name."',website_title='".$website_title."',website_description='".$website_description."',website_keywords='".$website_keywords."',
        paypal_email='".$paypal_email."',subscription_name='".$subscription_name."',subscription_price='".$subscription_price."',
        subscription_currency='".$subscription_currency."',disquis_short_name='".$disquis_short_name."',facebook_url='".$facebook_url."',twitter_url='".$twitter_url."',jwplayer_key='".$jwplayer_key."',title1='".$title1."',link1='".$link1."',title2='".$title2."',link2='".$link2."'");
    header('Location: settings.php?success=1');
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
                <?php if(isset($error)) { ?> <div class="alert alert-danger"> <?=$error?> </div> <?php } ?>
                <form action="" method="post">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-title">General</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label> Website Name </label>
                                        <input type="text" name="website_name" class="form-control border-input" placeholder="e.g Muviko" value="<?=$admin->settings->website_name?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label> Website Title </label>
                                        <input type="text" name="website_title" class="form-control border-input" placeholder="e.g Muviko - Movie & Video CMS" value="<?=$admin->settings->website_title?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label> Website Description </label>
                                        <input type="text" name="website_description" class="form-control border-input" placeholder="Enter a description for your website" value="<?=$admin->settings->website_description?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label> Website Keywords </label>
                                        <input type="text" name="website_keywords" class="form-control border-input" placeholder="Keywords separated by a comma" value="<?=$admin->settings->website_keywords?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label> Facebook URL </label>
                                        <input type="text" name="facebook_url" class="form-control border-input" value="<?=$admin->settings->facebook_url?>">
                                    </div>
                                    <div class="form-group">
                                        <label> Twitter URL </label>
                                        <input type="text" name="twitter_url" class="form-control border-input" value="<?=$admin->settings->twitter_url?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-title">Comments</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label> Disquis Short Name </label>
                                        <input type="text" name="disquis_short_name" class="form-control border-input" value="<?=$admin->settings->disquis_short_name?>">
                                    </div>
                                    <div class="help-block"> Register your website with <b><a href="https://disqus.com/" target="_blank">Disquis</a></b> for free. <br> Comments are not available on all themes</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-title">Player</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label> JWPlayer Key </label>
                                        <input type="text" name="jwplayer_key" class="form-control border-input" value="<?=$admin->settings->jwplayer_key?>" required>
                                    </div>
                                    <div class="help-block"> Register your website with <b><a href="https://jwplayer.com/" target="_blank">JWPlayer</a></b> for free.</b></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                        <label> Subscription Name </label>
                                        <input type="text" name="subscription_name" class="form-control border-input" placeholder="e.g Muviko" value="<?=$admin->settings->subscription_name?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label> Subscription Price </label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><?=$admin->settings->subscription_currency?></span>
                                            <input type="text" name="subscription_price" class="form-control border-input" placeholder="Enter a description for your website" value="<?=$admin->settings->subscription_price?>" required>
                                        </div>
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

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading panel-title">Download Link</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label> Title 1 </label>
                                        <input type="text" name="title1" class="form-control border-input" value="<?=$admin->settings->title1?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label> Link  1 </label>
                                        <input type="text" name="link1" class="form-control border-input" value="<?=$admin->settings->link1?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label> Title 2 </label>
                                        <input type="text" name="title2" class="form-control border-input" value="<?=$admin->settings->title2?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label> Link  2 </label>
                                        <input type="text" name="link2" class="form-control border-input" value="<?=$admin->settings->link2?>" required>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="save" class="btn btn-success btn-fill btn-wd">Save</button>
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
