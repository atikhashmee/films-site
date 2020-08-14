<?php
require('../core/config.php');
require('../core/libs/src/PaypalIPN.php');

use overint\PaypalIPN;

$ipn = new PaypalIPN();
$ipn->useSandbox();
$verified = $ipn->verifyIPN();
if($verified){
	$custom = json_decode($_POST['custom'],1);
    if(isset($custom['action'])) {
        $action = $custom['action'];
        if($action == 'register') {
            $full_name = $custom['full_name'];
            $email = $custom['email'];
            $password = $custom['password'];
            $phone = $custom['phone'];
            $subscription_expiration = $custom['subscription_expiration'];
            $db->query("INSERT INTO users(email,password,name,is_subscriber,phone,subscription_expiration) VALUES ('".$email."','".hash('sha512',$password)."','".$full_name."','1','".$phone."','".$subscription_expiration."')");
            $random_avatar = rand(0,9);
            $full_name = explode(' ',$full_name);
            $user_id = $db->insert_id;
            $db->query("INSERT INTO profiles(user_id,profile_name,profile_avatar) VALUES ('".$user_id."','".$full_name[0]."','".$random_avatar."')");
            $db->query("UPDATE users SET last_profile='".$db->insert_id."'");
        } elseif($action == 'upgrade') {
            $id = $custom['id'];
            $subscription_expiration = $custom['subscription_expiration'];
            $db->query("UPDATE users SET is_subscriber='1', subscription_expiration='".$subscription_expiration."'");
        } elseif($action == 'renew') {
            $id = $custom['id'];
            $subscription_expiration = $custom['subscription_expiration'];
            $db->query("UPDATE users SET is_subscriber='1', subscription_expiration='".$subscription_expiration."'");
        }
    }
}