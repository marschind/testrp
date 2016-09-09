<?php
include_once("../lib/portallib.php");
logincheck();

if (!$_SESSION['userrec']['issiteadmin']) {
  exit;
}

if (preg_match("/aapdw(\d+)/", $_REQUEST['u'], $a)) {
  $uid = $a[1];
} else {
  exit;
}

$res = query("select * from users where id=:uid", array(':uid'=>$uid));
if ($res[0]['passwdstate'] == 'activated') {
  exit;
}
if ($res[0]['passwdstate'] == 'reset') {
  $passwd = 'xyzzy'.$res[0]['email'];
  $enc = password_hash($passwd, PASSWORD_BCRYPT);
  update("update users set password=:pw, passwdstate='pending' where id=:uid", array(':uid'=>$uid, ':pw'=>$enc));
}
if ($res[0]['passwdstate'] == 'pending') {
  if (!$res[0]['password']) {
    $passwd = 'xyzzy'.$res[0]['email'];
    $enc = password_hash($passwd, PASSWORD_BCRYPT);
    update("update users set password=:pw, passwdstate='pending' where id=:uid", array(':uid'=>$uid, ':pw'=>$enc));
  } else {
    $enc = $res[0]['password'];
  }
}

$token = JWT::encodepayload(['uid'=>$uid, 'pwd'=>$enc]);
$url = $_SETTINGS['portalurl'].'register.php/'.$token;
update("update users set passwdstate='pending' where id=:uid", array(':uid'=>$uid));
debuglog($url);

print($url);
?>
