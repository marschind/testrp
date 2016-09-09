<?php
include_once("../lib/portallib.php");
logincheck();
pagetop('Admin', 'System');

if (!$_SESSION['userrec']['issiteadmin']) {
  printf("<h2>Permission Denied</h2>\n<p>You do not have permission to access this page.</p>\n");
  pagebottom();
  exit;
}

if ($_REQUEST['op'] == 'edit') {
  $res = query("select * from pages where id=:id", array(':id'=>$_REQUEST['id']));
  ?>
<style>
textarea {
  width: 800px;
  height: 500px;
}
</style>
<h2>Editing Page "<?= $res[0]['name'] ?>"</h2>

<form method="post>
  <input type="hidden" name="op" value="edit2"/>
  <input type="hidden" name="id" value="<? $res[0]['id'] ?>"/>
  <textarea id="content" name="content"><?= $res[0]['content'] ?></textarea>
  <br/>
  <input type="submit" value="Save"/>
</form>
<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
  selector: '#content'
});
</script>
<?php
}

if (!$_REQUEST['op']) {
  ?>
<h2>Edit Static Page Content</h2>
<table>
  <tr><td>Page</td><td>Operations</td></tr>
  <?php
    $res = query("select id, name from pages order by name", array());
    foreach ($res as $page) {
      printf("<tr><td><a href=\"/p.php/%s\">%s</a></td><td><a href=\"adminpages.php?op=edit&id=%d\">Edit</a></td></tr>\n",
	$page['name'], $page['name'], $page['id']);
    }
  ?>
</table>

<?php
}

pagebottom();
?>
