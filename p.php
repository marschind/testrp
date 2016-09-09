<?php
include_once("lib/portallib.php");
$page = substr($_SERVER['PATH_INFO'], strchr('/', $_SERVER['PATH_INFO'])+1);
$res = query("select * from pages where name=:page", array(':page'=>$page));
if (!$res[0]) {
  pagetop('','');
  printf("<h2>Page Not Found</h2>\n<p>An invalid URL has been entered.</p>\n");
  pagebottom();
  exit;
}
pagetop($res[0]['section'], $res[0]['subsection']);
print($res[0]['content']);
pagebottom();

?>
