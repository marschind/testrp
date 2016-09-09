<?php
include_once("../lib/portallib.php");

if ($_SESSION['uid'] > 0 && $_REQUEST['password']) {
  try {
    if ($_REQUEST['password'] != $_REQUEST['password2']) {
      throw new Exception('You did not enter the same password twice');
    }
    if (strlen($_REQUEST['password']) < 6) {
      throw new Exception('Please use a longer password');
    }
    update("update users set password=:pw, passwdstate='activated', modified=sysdate() where id=:uid", array(':uid'=>$_SESSION['uid'], ':pw'=>password_hash($_REQUEST['password'], PASSWORD_BCRYPT)));
    header("Location: /portal/login.php");
    exit;
  } catch (Exception $ex) {
    $errormessage = sprintf("Error: %s", $ex->getMessage());
  }
}

$auth = JWT::decode(substr($_SERVER['PATH_INFO'], 1));
$res = query("select * from users where id=:uid and passwdstate='pending'", array(':uid'=>$auth->uid));
if (!$auth || !$auth->uid || !$auth->pwd || $auth->pwd != $res[0]['password']) {
  pagetop('','');
  print("<h2>You have entered an invalid URL.  Please use cut & paste to accurately copy the information to your browser.</h2>\n");
  pagebottom();
  exit;
}
$_SESSION['uid'] = $auth->uid;

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <meta name="title" content="My Acclaro Portal">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <link rel="icon" type="image/ico" href="/images/favicon.ico">
  <link rel="shortcut icon" type="image/ico" href="/images/favicon.ico">
  <title>My Acclaro Portal</title>
  <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,600italic,400italic|Open+Sans+Condensed:300,700,300italic|Lato:700italic' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="/css/portal.css" type="text/css">
  <script type="text/javascript" src="/js/jquery.js"></script>
  <script type="text/javascript" src="/js/schindlertech.js"></script>
<style>
#loginframe {
  background-image: url(/images/Login-Photo-1.png);
  image-repeat: no-repeat
  background-position: center center;
}
#errorbox {
  position: relative;
  background-color: #eeeeee;
  border-radius: 8px;
  width: 236px;
  top: 150px;
  left: 50%;
  margin-left: -125px;
  opacity: 0.9;
  padding: 30px;
  color: #660000;
  font-size: 14px;
  font-weight: bold;
  text-align: center;
}
#loginbox {
  position: relative;
  background-color: #eeeeee;
  border-radius: 8px;
  width: 236px;
  top: 250px;
  left: 50%;
  margin-left: -125px;
  opacity: 0.9;
  padding: 30px;
}
.instr {
  text-align: center;
  font-weight: bold;
  font-size: 14px;
}
#lbpasswd, #lbpasswd2 {
  background-color: #ffffff;
  width: 200px;
  height: 20px;
  border-radius: 4px;
  border: none;
  margin-bottom: 8px;
  background-image: url(/images/Password.png);
  background-repeat: no-repeat;
  background-position: 14px 4px;
  padding-left: 32px;
}
#lblogin {
  background-color: #3399cc;
  width: 232px;
  height: 20px;
  border-radius: 4px;
  border: none;
  text-align: center;
  color: #ffffff;
  font-size: 14px;
  cursor: pointer;
}
#lbbottom {
  margin-top: 16px;
  color: #3399cc;
  font-size: 10px;
}
#lbright {
  float: right;
}
#loginbox a:link, #loginbox a:visited {
  color: #3399cc;
  text-decoration: none;
}
#loginfooter {
  position: absolute;
  left: 0px;
  bottom: 0px;
  width: 100%;
  background-color: #000000;
  opacity: 0.75;
  padding: 20px;
  color: #ffffff;
}
#lfleft {
  position: absolute;
  top: 20px;
  left: 40px;
}
.lfcenter {
  text-align: center;
}
#loginfooter a:link, #loginfooter a:visited {
  color: #ffffff;
  text-decoration: none;
}
</style>
</head>
<body id="loginframe">
<?php if ($errormessage) { ?>
  <div id="errorbox">
    <?= $errormessage ?>
  </div>
<?php } ?>
  <div id="loginbox">
    <div><img src="/images/Logo.png" alt="my acclaro"/></div>
    <div class="instr">Please set your password</div>
    <form method="post">
      <input id="lbpasswd"  type="password" name="password" placeholder="Enter Password"/>
      <input id="lbpasswd2"  type="password" name="password2" placeholder="Confirm Password"/>
      <input id="lblogin" type="submit" value="GO TO LOGIN"/>
    </form>
  </div>
  <div id="loginfooter">
    <div id="lfleft">
      &copy; 2016 ACCLARO INC.  ALL RIGHTS RESERVED.
    </div>
    <div class="lfcenter">
       <a href="/p.php/aboutus">About Us</a> &nbsp; &nbsp;
       <a href="/p.php/useragreement">User Agreement</a> &nbsp; &nbsp;
       <a href="/p.php/privacypolicy">Privacy Policy</a> &nbsp; &nbsp;
       <a href="/p.php/contactus">Contact Us</a>
    </div>
  </div>
</body>
</html>
