<?php
include_once("../lib/portallib.php");
if (!$_SESSION['loggedin']) {
  exit;
}

if ($_REQUEST['s'] == 'system') {
  if ($_REQUEST['n'] == 'online' ||
      $_REQUEST['n'] == 'portalurl' ||
      $_REQUEST['n'] == 'emailfrom') {
    query("update settings set value=:v where name=:n", array(':v'=>$_REQUEST['v'], ':n'=>$_REQUEST['n']));
  }
  if ($_REQUEST['n'] == 'urlbase' ||
      $_REQUEST['n'] == 'upoad_dir' ||
      $_REQUEST['n'] == 'pluneturl' ||
      $_REQUEST['n'] == 'plunetuser' ||
      $_REQUEST['n'] == 'plunetpass') {
    rewritefile('../lib/config.txt', $_REQUEST['n'], $_REQUEST['v']);
  }
}

function rewritefile($fn, $name, $value) {
  $fi = fopen($fn, 'r');
debuglog("fi=$fi");
  $fo = fopen($fn.'.tmp', 'w');
debuglog("fo=$fo");
  while (!feof($fi)) {
    $l = fgets($fi);
    if (preg_match("/^$name\s*=/", $l)) {
      fprintf($fo, "%s = \"%s\"\n", $name, $value);
    } else {
      fputs($fo, $l);
    }
  }
  fclose($fi);
  fclose($fo);
  unlink($fn);
  rename($fn.'.tmp', $fn);
}

?>
