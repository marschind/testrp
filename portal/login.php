<?php
include_once("../lib/portallib.php");

if ($_REQUEST['username']) {
  try {
    $res = query("select * from users where email=:username and passwdstate='activated'", array(':username'=>$_REQUEST['username']));
    if (!$res || !$res[0]) {
      throw new Exception("Incorrect username and/or password");
    }
    if (!password_verify($_REQUEST['password'], $res[0]['password'])) {
      throw new Exception("Incorrect username and/or password");
    }
    $_SESSION['userrec'] = $res[0];
    $res = query('select * from usergroups where id=:gid', array(':gid'=>$_SESSION['userrec']['usergroup']));
    $_SESSION['grouprec'] = $res[0];
    $_SESSION['loggedin'] = 1;
    header("Location: home.php");
    exit;
  } catch (Exception $ex) {
    printf("Error: %s<br/>\n", $ex->getMessage());
  }
}

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
#lbemail {
  background-color: #ffffff;
  width: 200px;
  height: 20px;
  border-radius: 4px;
  border: none;
  margin-bottom: 8px;
  background-image: url(/images/Username.png);
  background-repeat: no-repeat;
  background-position: 12px 4px;
  padding-left: 32px;
}
#lbpasswd {
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
  <div id="loginbox">
    <div><img src="/images/Logo.png" alt="my acclaro"/></div>
    <form method="post">
      <input id="lbemail" type="text" name="username" placeholder="Email"/>
      <input id="lbpasswd"  type="password" name="password" placeholder="Password"/>
      <input id="lblogin" type="submit" value="LOGIN"/>
      <div id="lbbottom">
        <div id="lbright"><a href="">Sign Up</a></div>
        <input type="checkbox"/> Remember Me
      </div>
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
