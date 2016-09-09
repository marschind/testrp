<?php
include_once("../lib/portallib.php");
include_once("../lib/plunetapi.php");

$plunet = new plunetApi();
$uh = $plunet->getHandle('DataCustomerContact30');

$custs = query("select * from usergroups where type='customer'", array());
foreach ($custs as $c) {
  $ret = $uh->getAllContactObjects($plunet->session, $c['plunetid']);
  foreach ($ret->data as $u) {
    update("insert into users (plunetid, firstname, lastname, email, phone, usergroup, created, modified) values (:pid, :first, :last, :email, :phone, :gid, sysdate(), sysdate())",
    	array(':pid'=>$u->customerContactID, ':first'=>$u->name2,
		':last'=>$u->name1, ':email'=>$u->email,
		':phone'=>$u->phone, ':gid'=>$c['id']));
    $count++;
  }
}

printf("Added %d users.<br/>\n", $count);

?>
