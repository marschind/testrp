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

<h2>System Settings</h2>

Site is open for public access:
<input id="online1" type="radio" name="online" value="1" <?= $_SETTINGS['online']?' checked="checked"':'' ?>/>Open &nbsp;
<input id="online0" type="radio" name="online" value="0" <?= $_SETTINGS['online']?'':' checked="checked"' ?>/>Closed &nbsp;
<br/>

Email notifications come from this address:
<input type="text" id="emailfrom" value="<?= $_SETTINGS['emailfrom'] ?>" size="48"/>
<br/>
Portal location:
<input type="text" id="portalurl" value="<?= $_SETTINGS['portalurl'] ?>" size="48"/>
<br/>

&nbsp;<br/>
<b>API Settings</b><br/>
Acclaro API location:
<input type="text" id="urlbase" value="<?= $_CONFIG['api']['urlbase'] ?>" size="48"/>
<br/>
File upload directory:
<input type="text" id="upload_dir" value="<?= $_CONFIG['general']['upload_dir'] ?>" size="48"/>
<br/>

Plunet location:
<input type="text" id="pluneturl" value="<?= $_CONFIG['plunet']['pluneturl'] ?>" size="48"/>
<br/>
Plunet API username:
<input type="text" id="plunetuser" value="<?= $_CONFIG['plunet']['plunetuser'] ?>" size="16"/>
<br/>
Plunet API password:
<input type="password" id="plunetpass" value="<?= $_CONFIG['plunet']['plunetpass'] ?>" size="16"/>
<br/>

<script type="text/javascript">
$(document).ready(function(){
  $('#main input').change(function() {
    if (this.type == 'radio') {
      updatevalue('system', this.name, this.value);
    } else {
      updatevalue('system', this.id, this.value);
    }
  });
});
</script>

<?php pagebottom(); ?>
