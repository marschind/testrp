<?php
include_once("../lib/portallib.php");
include_once("../lib/plunetapi.php");

$plunet = new plunetApi();
$ch = $plunet->getHandle('DataCustomer30');

$ret = $ch->getAllCustomerObjects($plunet->session, 1);

for ($i = 0; $i < sizeof($ret->data); $i++) {
  $grp = query("select * from usergroups where plunetid=:pid", array(':pid'=>$ret->data[$i]->customerID));
  if (sizeof($grp) == 0) {
    update("insert into usergroups (plunetid, type, company, phone, url, created, modified, lastactivity) values (:id, 'customer',:name, :phone, :url, sysdate(), sysdate(), sysdate())",
  	array(':id'=>$ret->data[$i]->customerID,
	      ':name'=>$ret->data[$i]->fullName,
	      ':phone'=>$ret->data[$i]->phone,
	      ':url'=>$ret->data[$i]->website));
    $additions++;
  } else {
    if ($grp[0]['company'] != $ret->data[$i]->fullName ||
        $grp[0]['phone'] != $ret->data[$i]->phone ||
        $grp[0]['url'] != $ret->data[$i]->website) {
	update("update usergroups set company=:name, phone=:phone, url=:url, modified=sysdate() where id=:id",
		array(':id'=>$grp[0]['id'], ':name'=>$ret->data[$i]->fullName,
		      ':phone'=>$ret->data[$i]->phone,
		      ':url'=>$ret->data[$i]->website));
	printf("Updating %d: %s (%s, %s, %s)<br/>\n", $grp[0]['id'], $ret->data[$i]->customerID, $ret->data[$i]->fullName, $ret->data[$i]->phone, $ret->data[$i]->website);
      $updates++;
    }
  }
}

printf("Added %d customer%s, updated %d customer%s.<br/>\n", $additions, $additions==1?'':'s', $updates, $updates==1?'':'s');

?>
