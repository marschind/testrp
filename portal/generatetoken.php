<?php
include_once("../lib/portallib.php");
logincheck();
pagetop('Settings', 'Web Token');

if (!$_SESSION['userrec']['apienable'] && !$_SESSION['userrec']['issiteadmin']) {
  printf("<h2>Permission Denied</h2>\n<p>You do not have permission to generate web tokens or use the API.</p>\n");
  pagebottom();
  exit;
}


if ($_REQUEST['revoke']) {
  $t = JWT::decode($_REQUEST['revoke']);
  if ($t) {
    update("insert into revocations (user, iat) values (:uid, :iat)", array(':uid'=>$t->sub, ':iat'=>$t->iat));
    update("update tokens set status='revoked' where value=:val", array(':val'=>$_REQUEST['revoke']));
    print("<h2>Success</h2><p>The specified web token has been revoked.</p>\n");
  } else {
    print("<h2>Error</h2><p>The web token you have asked to revoke is not valid.</p>\n");
  }
}

$res = query("select * from tokens where user=:uid and status='active'", array(':uid'=>$_SESSION['userrec']['id']));
if ($res[0]) {
  $token = $res[0]['value'];
} else {
  $token = JWT::encode($_SESSION['userrec']['id']);
  $ret = update("insert into tokens (user, value, status) values (:uid,:val,'active')", array(':uid'=>$_SESSION['userrec']['id'], ':val'=>$token));
  if ($ret) {
    print_r($ret);
  }
}
?>
<h2>Generate Web Token</h2>
<p>Here is a web token for this account.  Use copy and paste to load it into the application  you wish to authorize.</p>
<pre id="tokentext"><?= $token ?></pre>

<p>&nbsp;</p>
<h2>Revoke Web Token</h2>
<p>If you have accidentally revealed your web token so that it is no longer secret, you may revoke that token so that it cannot be used by any unauthorized persons.</p>
<form method="post">
Token to revoke: <input type="text" name="revoke" size="80"/><br/>
<input type="submit" value="Revoke"/>
</form>

<script type="text/javascript">
  $(document).ready(function(){
    SelectText('tokentext');
  });
</script>
<?php pagebottom(); ?>
