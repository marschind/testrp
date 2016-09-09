<?php
include_once("../lib/portallib.php");
logincheck();
pagetop('Settings', 'Settings');
?>
<h2>User Account Settings</h2>
<ol>
<?php if ($_SESSION['userrec']['apienable']) { ?>
  <li>
    <a href="generatetoken.php">Generate Web Token</a>
  </li>
<?php } ?>
  <li>
    <a href="passwd.php">Change Password</a>
  </li>
  <li>
    <a href="logout.php">Logout</a>
  </li>
</ol>

<?php pagebottom(); ?>
