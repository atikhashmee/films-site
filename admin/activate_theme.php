<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);

$theme = $_GET['theme'];

$install_sql = trim(file_get_contents('../themes/'.$theme.'/install.sql'));
$db->query($install_sql);
$db->query("UPDATE settings SET theme='".$theme."'");
header('Location: themes.php?success=1');
exit;