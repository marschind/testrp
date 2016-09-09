<?php
include_once("../lib/portallib.php");
$_SESSION['userrec'] = array();
$_SESSION['loggedin'] = 0;
$_SESSION['token'] = '';
header("Location: login.php");
?>
