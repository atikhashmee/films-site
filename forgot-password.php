<?php
session_set_cookie_params(172800);
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require('core/config.php');
require('core/system.php');
require('core/php-mailer/Exception.php');
require('core/php-mailer/PHPMailer.php');
require('core/php-mailer/SMTP.php');
require('core/php-mailer/POP3.php');
$core = new Core($db,$domain);
require($core->getExtendPath());
$muviko = new Muviko($db,$domain);
define('THEME_PATH', $core->getThemePath());
define('UPLOADS_PATH', $core->getUploadsPath());

if(isset($_POST['forgot'])){
    $email = $_POST['email'];
    // $password = 'admin';
    // $pass = hash('sha512',$password);
    // $db->query("UPDATE users SET password='".$pass."' WHERE id=22");

	$user = $muviko->getUser(false,$email,'email');
    if(is_object($user)) {
        $password = _gRS(8);
        //$mailed = mail($email,'Password Change Request',$message,"From:  <>");
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
             //$mail->isSMTP();                                      // Set mailer to use SMTP
             $mail->Host = 'mail.dissdemo.biz';  // this one Specify main and backup SMTP servers
             $mail->SMTPAuth = false;                               // Enable SMTP authentication
            $mail->Username = 'mustafa@dissdemo.biz';                 // this one SMTP username
             $mail->Password = 'xtHe{)DLUT@U';              //this one SMTP password
             $mail->SMTPSecure = 'ssl';
             $mail->Port = 465;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('mustafa@dissdemo.biz', 'Mustafa Vohra');
            $mail->addAddress($email);     // Add a recipient
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Password Change Request';
            $mail->Body    = "Your New Password is : ".$password;
            $type = 'success';
            $mail->send();
            $responsemessage = 'Your new password has been sent on email';
            $updated = $db->query("UPDATE users SET password='".$muviko->hashPassword($password)."' WHERE id='".$user->id."'");
        } catch (Exception $e) {
            $type = 'error';
            $responsemessage = 'Password not changed. <br/>';
            $responsemessage .= 'Mailer Error: ' . $mail->ErrorInfo;
        }
        //debug(array($email,$mailed,$message,$updated,$user));
        mail($email,'Password Change Request',"Your New Password is : ".$password,"From: Mustafa Vohra <mustafa@dissdemo.biz>");
        header('Location: '.$muviko->getDomain()."/index.php?{$type}={$responsemessage}");
    }
    else{
        $responsemessage = 'Email Not Valid';
		header('Location: '.$muviko->getDomain().'/index.php?error='.$responsemessage);
		exit;
    }
}
?>