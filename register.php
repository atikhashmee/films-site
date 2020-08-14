<?php
session_set_cookie_params(172800);
session_start();
require('core/config.php');
require('core/system.php');
$core = new Core($db,$domain);
require($core->getExtendPath());
$muviko = new Muviko($db,$domain);
$muviko->startUserSession($_SESSION);
$muviko->verifySession(false);
$muviko->getLanguage();
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());
$page['name'] = $muviko->translate('Sign_Up');
$page['footer'] = true;
if(isset($_POST['continue'])) {
	$query = array();
	$full_name = $_POST['full_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$phone = $_POST['phone'];
	/* $query['notify_url'] = $muviko->getDomain().'/payment/paypal.php';
	$query['cmd'] = '_xclick';
	$query['business'] = $muviko->settings->paypal_email;
	$query['currency_code'] = $muviko->settings->subscription_currency;
	$query['custom'] = json_encode(array('full_name' => $full_name, 'email' => $email, 'password' => $password, 'phone' => $phone, 'subscription_expiration' => $subscription_expiration, 'action' => 'register'));
	$query['return'] = $muviko->getDomain().'/index.php';
	$query['item_name'] = $muviko->settings->subscription_name;
	$query['quantity'] = 1;
	$query['amount'] = $muviko->settings->subscription_price;
	$query_string = http_build_query($query); */
	$check = $db->query("SELECT * FROM users WHERE email='$email'");
	if($check->num_rows > 0){
		header('Location: '.$muviko->getDomain().'?error=Email Exists!');
		exit();
	}
	$db->query("INSERT INTO users(email,password,name,is_subscriber,phone,subscription_expiration) VALUES ('".$email."','".hash('sha512',$password)."','".$full_name."','0','".$phone."','0')");
	$random_avatar = rand(0,9);
	$full_name = explode(' ',$full_name);
	$user_id = $db->insert_id;
	$db->query("INSERT INTO profiles(user_id,profile_name,profile_avatar) VALUES ('".$user_id."','".$full_name[0]."','".$random_avatar."')");
	$db->query("UPDATE users SET last_profile='".$db->insert_id."'");
	//header('Location: https://www.paypal.com/cgi-bin/webscr?' . $query_string);
	header('Location: '.$muviko->getDomain());
}

include($muviko->getHeaderPath());
include($muviko->getPagePath('register'));
include($muviko->getFooterPath());