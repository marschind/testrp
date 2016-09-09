<?php
include_once("../lib/portallib.php");
logincheck();
pagetop('Settings','Password');

if ($_REQUEST['new']) {
  if (!password_verify($_REQUEST['old'], $_SESSION['userrec']['password'])) {
    print("<h2>Incorrect old password given</h2>\n");
  } else if ($_REQUEST['new'] != $_REQUEST['new2']) {
    print("<h2>The new password was not given the same twice</h2>\n");
  } else if (strlen($_REQUEST['new']) < 5) {
    print("<h2>Please choose a longer password</h2>\n");
  } else {
    update("update users set password=:pw where id=:id", array(':pw'=>password_hash($_REQUEST['new'], PASSWORD_BCRYPT), ':id'=>$_SESSION['userrec']['id']));
    print("<h2>Password Updated</h2>\n");
  }
}
?>
<h2>Change Password</h2>
<form method=post>
  <table>
    <tr><td>Current password:</td><td><input type="password" name="old"/></td></tr>
    <tr><td>New password:</td><td><input type="password" name="new"/></td></tr>
    <tr><td>New password again:</td><td><input type="password" name="new2"/></td></tr>
    <tr><td></td><td><input type="submit" value="Change"/></td></tr>
  </table>
</form>

<?php pagebottom(); ?>
