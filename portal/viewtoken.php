<?php
include_once("../lib/portallib.php");
logincheck();

if (!$_SESSION['userrec']['issiteadmin']) {
  exit;
}

if (preg_match("/aapdt(\d+)/", $_REQUEST['u'], $a)) {
  $uid = $a[1];
} else {
  exit;
}

$res = query("select * from tokens where user=:uid and status='active'", array(':uid'=>$uid));
if ($res[0]) {
  $token = $res[0]['value'];
} else {
  $token = JWT::encode($_SESSION['userrec']['id']);
  $ret = update("insert into tokens (user, value, status) values (:uid,:val,'active')", array(':uid'=>$uid, ':val'=>$token));
}
print($token);
?>
