<?php
function pagetop($nav, $subnav) {
  global $_SETTINGS;
  $role = 'User';
  if ($_SESSION['userrec']['issiteadmin']) {
    $role = 'Administrator';
  } else {
    $role = $_SESSION['grouprec']['company'];
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
  <link rel="stylesheet" href="/css/portal.css?20160909" type="text/css">
  <script type="text/javascript" src="/js/jquery.js"></script>
  <script type="text/javascript" src="/js/schindlertech.js"></script>
</head>
<body>
  <div id="header">
    <?php if ($_SESSION['loggedin']) { ?>
    <div id="headerright">
      <?= $role ?> | <span id="headerrightun"><?= $_SESSION['userrec']['firstname'].' '.$_SESSION['userrec']['lastname'] ?></span>
    </div>
    <?php } ?>
    </div>
    <a href="/portal/"><img src="/images/Logo.png" alt="my acclaro"/></a>
  </div>
  <div id="mainnav">
    <div id="mainnavright">
      <img src="/images/Email.png"/> &nbsp;
      <a href="/portal/usersettings.php"><img src="/images/Gear.png"/></a> &nbsp;
      <span id="usericon">
        <img src="/images/User.png"/>
	<div id="userdropdown" class="dropdown">
	  <a href="/portal/logout.php">Logout</a>
	</div>
      </span>
    </div>
    <a href="/portal/dashboard.php" class="<?= $nav=='Dashboard'?'active':''?>">Dashboard</a> &nbsp; &nbsp;
    <a href="/portal/projects.php" class="<?= $nav=='Projects'?'active':''?>">Projects</a> &nbsp; &nbsp;
    <a href="/portal/files.php" class="<?= $nav=='Files'?'active':''?>">Files</a> &nbsp; &nbsp;
    <a href="/portal/Reports.php" class="<?= $nav=='Reports'?'active':''?>">Reports</a> &nbsp; &nbsp;
    <a href="/portal/connectors.php" class="<?= $nav=='Connectors'?'active':''?>">Connectors</a> &nbsp; &nbsp;
    <a href="/portal/team.php" class="<?= $nav=='Team'?'active':''?>">Team</a> &nbsp;
<?php if ($_SESSION['userrec']['issiteadmin']) { ?>
    <div class="vbarframe"><div class="vbar"></div></div> &nbsp;
    <a href="/portal/adminsystem.php" class="<?= $nav=='Admin'?'active':''?>">Admin</a>
<?php } ?>
  </div>
  <div id="subnav">
    <div id="subnavright">
      View As: Admin <img src="/images/ArrowDnWhite.png"/>
    </div>
<?php if ($nav == 'Admin') { ?>
    <a href="/portal/adminaccounts.php" class="<?= $subnav=='Accounts'?'active':''?>">Accounts</a> &nbsp; &nbsp;
    <a href="/portal/adminusers.php" class="<?= $subnav=='Users'?'active':''?>">Users</a> &nbsp; &nbsp;
    <a href="/portal/adminreports.php" class="<?= $subnav=='Reports'?'active':''?>">Reports</a> &nbsp; &nbsp;
    <a href="/portal/adminsystem.php" class="<?= $subnav=='System'?'active':''?>">System</a> &nbsp; &nbsp;
<?php } else if ($nav == 'Settings') { ?>
    <a href="/portal/usersettings.php" class="<?= $subnav=='Settings'?'active':''?>">Settings</a> &nbsp; &nbsp;
<?php if ($_SESSION['userrec']['apienable']) { ?>
    <a href="/portal/generatetoken.php" class="<?= $subnav=='Web Token'?'active':''?>">Web Token</a> &nbsp; &nbsp;
<?php } ?>
    <a href="/portal/passwd.php" class="<?= $subnav=='Password'?'active':''?>">Password</a> &nbsp; &nbsp;
<?php }  else { ?>
    &nbsp;
<?php } ?>
  </div>
  <div id="main">
<? }

function pagebottom() {
  ?>
  </div>
  <div id="footer">
    <div id="footerright">
      Powered by <a href="http://www.schindlertech.com/" target="_blank"><img id="stechicon" src="/images/StechIcon.png"/> SchindlerTechnology Inc.</a>
    </div>
    <div id="footerleft">
      &copy; 2016 ACCLARO INC.  ALL RIGHTS RESERVED.
    </div>
    <div id="footercenter">
       <a href="/p.php/aboutus">About Us</a> &nbsp; &nbsp;
       <a href="/p.php/useragreement">User Agreement</a> &nbsp; &nbsp;
       <a href="/p.php/privacypolicy">Privacy Policy</a> &nbsp; &nbsp;
       <a href="/p.php/contactus">Contact Us</a>
    </div>
  </div>
</body>
</html>
<?php }
?>
