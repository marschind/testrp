<?php
include_once("../lib/portallib.php");
if (!$_SESSION['loggedin']) {
  exit;
}

debuglog('t='.$_REQUEST['t'].' v='+$_REQUEST['v']);

if (preg_match("/aapdp(\d+)dd/", $_REQUEST['t'], $a)) {
  $id = $a[1];
  $v = ($_REQUEST['v'] == 'Enabled')?1:0;
  update("update users set portalenable=:v where id=:id", array(':id'=>$id, ':v'=>$v));
}
if (preg_match("/aapdt(\d+)dd/", $_REQUEST['t'], $a)) {
  $id = $a[1];
  $v = ($_REQUEST['v'] == 'Enabled')?1:0;
  update("update users set apienable=:v where id=:id", array(':id'=>$id, ':v'=>$v));
}
if (preg_match("/aapdw(\d+)dd/", $_REQUEST['t'], $a)) {
  $id = $a[1];
  $v = strtolower($_REQUEST['v']);
  update("update users set passwdstate=:v where id=:id", array(':id'=>$id, ':v'=>$v));
}


?>
