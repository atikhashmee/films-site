<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db,$domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);

$id = $_GET['id'];

$db->query("DELETE FROM pages WHERE id='".$id."'");
header('Location: pages.php');
exit;