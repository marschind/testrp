<?php
include_once("../lib/portallib.php");
logincheck();
pagetop('Admin', 'System');

if (!$_SESSION['userrec']['issiteadmin']) {
  printf("<h2>Permission Denied</h2>\n<p>You do not have permission to access this page.</p>\n");
  pagebottom();
  exit;
}
?>

<ol>
  <li>
    <a href="systemsettings.php">Settings</a>
  </li>
  <li>
    <a href="adminpages.php">Static Page Content</a>
  </li>
  <li>
    <a href="apidemo.php">API Test</a>
  </li>
  <li>
    <a href="sync.php?op=cust">Sync customers from Plunet</a>
  </li>
  <li>
    <a href="sync.php?op=user">Sync users from Plunet</a>
  </li>
  <li>
    <a href="sync.php?op=order">Sync orders from Plunet</a>
  </li>
</ol>

<?php pagebottom(); ?>
