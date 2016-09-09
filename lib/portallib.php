<?php
include_once("apicall.php");
include_once("password_hashing.inc.php");
include_once("template.php");

function logincheck() {
  if (!$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
  }
}

function apicall($name, $args) {
  global $_CONFIG, $_SETTINGS;
  $urlbase = $_CONFIG['api']['urlbase'];
  $api = curl_init();
  curl_setopt($api, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$_SETTINGS['token']));
  function buildurl($k, $v) {
    return($k.'='.urlencode($v));
  }
  $argv = array_map(buildurl, array_keys($args), $args);
  $argstring = implode('&', $argv);
  curl_setopt($api, CURLOPT_URL, $urlbase.$name."?".$argstring);
  curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
  $ret = curl_exec($api);
  curl_close($api);
  return(json_decode($ret));
}

function apicallfile($name, $args, $file) {
  global $_CONFIG, $_SETTINGS;
  $urlbase = $_CONFIG['api']['urlbase'];
  $api = curl_init();
  curl_setopt($api, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$_SETTINGS['token']));
  function buildurl($k, $v) {
    return($k.'='.urlencode($v));
  }
  $argv = array_map(buildurl, array_keys($args), $args);
  $argstring = implode('&', $argv);
  curl_setopt($api, CURLOPT_URL, $urlbase.$name."?".$argstring);
  curl_setopt($api, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($api, CURLOPT_POST, true);
  $cf = curl_file_create($file['tmp_name'], 'application/octet-stream', $file['name']);
  curl_setopt($api, CURLOPT_POSTFIELDS, array('file'=>$cf));
  $ret = curl_exec($api);
  curl_close($api);
  return(json_decode($ret));
}

function reversedate($s) {
  if (preg_match("/(\d+)\/(\d+)\/(\d+)/", $s, $a)) {
    return(sprintf("%d-%02d-%02d", $a[3], $a[1], $a[2]));
  } else {
    return('');
  }
}

session_start();
$res = query("select * from settings", array());
foreach ($res as $r) {
  $_SETTINGS[$r['name']] = $r['value'];
}
?>
