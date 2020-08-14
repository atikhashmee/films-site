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
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());

if(isset($_POST['promo_code'])) {
	$promo_code = trim($_POST['promo_code']);
	$code = $db->query("SELECT * FROM codes WHERE code='".$promo_code."'");
	if($code->num_rows >= 1) {
		$code = $code->fetch_object();
		$action = $code->action;
		if($action == 'add_subscription') {
			$db->query("UPDATE users SET is_subscriber=1,subscription_expiration='".$code->amount."' WHERE id='".$muviko->user_id."'");
		} elseif ($action == 'add_subscription_time') {
			$db->query("UPDATE users SET subscription_expiration='".$code->amount."' WHERE id='".$muviko->user_id."'");
		}
		$db->query("DELETE FROM codes WHERE code='$promo_code' AND multi_users=0");
		// header('Location: settings');
		// exit;
	}
} else {
	die('Error');
}