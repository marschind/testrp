<?php
include_once("../lib/portallib.php");
include_once("../lib/plunetapi.php");

$plunet = new plunetApi();
$uh = $plunet->getHandle('DataCustomerContact30');
$ch = $plunet->getHandle('DataCustomer30');

$custs = query("select * from usergroups where type='customer'", array());
foreach ($custs as $c) {
//printf("Working on %s (%d, %s)<br/>\n", $c['company'], $c['id'], $c['plunetid']);
  $ret = $uh->getAllContactObjects($plunet->session, $c['plunetid']);
  $ra = $ret->data;
  if (!is_array($ra)) {
    $ra = array();
    $ra[0] = $ret->data;
  }
  foreach ($ra as $u) {
    $usr = query("select * from users where plunetid=:pid", array(':pid'=>$u->customerContactID));
    if (sizeof($usr) == 0) {
      update("insert into users (plunetid, firstname, lastname, email, phone, usergroup, created, modified) values (:pid, :first, :last, :email, :phone, :gid, sysdate(), sysdate())",
          array(':pid'=>$u->customerContactID, ':first'=>$u->name2,
		':last'=>$u->name1, ':email'=>$u->email,
		':phone'=>$u->phone, ':gid'=>$c['id']));
      $additions++;
      $changed++;
    } else {
      if ($usr[0]['firstname'] != $u->name2 ||
	  $usr[0]['lastname'] != $u->name1 ||
	  $usr[0]['email'] != $u->email ||
	  $usr[0]['phone'] != $u->phone) {
	  update("update users set firstname=:first, lastname=:last, email=:email, phone=:phone, modified=sysdate() where id=:id",
		array(':id'=>$usr[0]['id'], ':first'=>$u->name2,
		      ':last'=>$u->name1,
		      ':phone'=>$u->phone,
		      ':email'=>$u->email));
        $updates++;
        $changed++;
      }
    }
  }
  if ($changed) {
    update("update usergroups set lastactivity=sysdate() where id=:cid", array(':cid'=>$c['id']));
    $changed = 0;
  }
}


printf("Added %d user%s, updated %d user%s.<br/>\n", $additions, $additions==1?'':'s', $updates, $updates==1?'':'s');

?>

