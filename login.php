<?php
session_set_cookie_params(172800);
session_start();
require('core/config.php');
require('core/system.php');
$core = new Core($db,$domain);
require($core->getExtendPath());
$muviko = new Muviko($db,$domain);
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());

if(isset($_POST['login'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$user = $muviko->getUser(false,$email,'email');
	if(is_object($user)) {
		if(hash('sha512',$password) == $user->password) {
			if(!$user->is_admin == 1){
				if($user->is_suspended == 1){
					$error = 'Account Suspended';
					header('Location: '.$muviko->getDomain().'/index.php?error='.$error);
					exit;
				}
                else if($user->is_suspended == 2){
					$error = 'Please activate your account first';
					header('Location: '.$muviko->getDomain().'/index.php?error='.$error);
					exit;
				}
				$curTime = time();
				$endTime = $user->subscription_expiration;
				if($curTime > $endTime){
					$db->query("UPDATE users SET subscription_expiration='0',is_subscriber='0' WHERE id=$id");
					/* $error = 'Subscription Expired';
					header('Location: '.$muviko->settings->redirect_after_login);
					exit; */
				}
			}
			$session_id = $muviko->generateSessionID();
			$profile = $muviko->getProfile($user->last_profile);
			$user_ip = $_SERVER['REMOTE_ADDR'];
			$db->query("INSERT INTO sessions(session_id,user_ip,user_id,profile_id,language,is_active,time) VALUES ('".$session_id."','".$user_ip."','".$user->id."','0','english','1','".time()."')") or die(mysqli_error($db));
			$array = array('fl_session_id' => $session_id, 'fl_user_id' => $user->id, 'is_admin' => $user->is_admin, 'fl_language' => 'english');
			$muviko->startUserSession($array);
			$muviko->setProfile($profile->id,0);
			header('Location: '.$muviko->settings->redirect_after_login.'.php');
			exit;
		} else {
			$error = 'Password is incorrect';
			header('Location: '.$muviko->getDomain().'/index.php?error='.$error);
			exit;
		}
	} else {
		$error = 'Email Not Valid';
		header('Location: '.$muviko->getDomain().'/index.php?error='.$error);
		exit;
	}
}