<?php
include_once("../lib/portallib.php");
logincheck();
pagetop('Admin','Settings');

if (!$_SESSION['userrec']['issiteadmin']) {
  printf("<h2>Permission Denied</h2>\n<p>You do not have permission to access this page.</p>\n");
  pagebottom();
  exit;
}

?>
<h2>Plunet Sync</h2>

<?php
if ($_REQUEST['op'] == 'cust') {
  print("Synching customers...<br/>\n");
  include("../etc/synccustomers.php");
}
if ($_REQUEST['op'] == 'user') {
  print("Synching users...<br/>\n");
  include("../etc/syncusers.php");
}
if ($_REQUEST['op'] == 'order') {
  print("Synching orders...<br/>\n");
  include("../etc/syncorders.php");
}

pagebottom();
?>
