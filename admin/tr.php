<?php
session_set_cookie_params(172800);
session_start();
require('../core/config.php');
require('../core/system.php');
$admin = new Data($db, $domain);
$admin->startUserSession($_SESSION);
$admin->verifySession(true);
$admin->verifyAdmin(true);
$sql = "SELECT actor_nconst FROM actors where actor_nconst like 'nm%' ";
$result = $db->query($sql);

while ($a = $result->fetch_assoc()) {
$d =   $a['actor_nconst'];
$sql = "UPDATE `actors` SET `imdbid`='".$d."'  WHERE `imdbid`=''";
$db->query($sql);

}

?>
