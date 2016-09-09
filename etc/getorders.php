<?php
include_once("../lib/portallib.php");
include_once("../lib/plunetapi.php");

$plunet = new plunetApi();
$rh = $plunet->getHandle('ReportOrder25');
$oh = $plunet->getHandle('DataOrder30');

$ret = query("select id, plunetid, lastactivity from usergroups", array());
foreach ($ret as $c) {
  $customer[$c['plunetid']] = $c['id'];
  $lastactivity[$c['plunetid']] = $c['lastactivity'];
}
$ret = query("select id, plunetid from users", array());
foreach ($ret as $c) {
  $users[$c['plunetid']] = $c['id'];
}

$orders = $rh->search($plunet->session);
foreach ($orders->data as $oid) {
  $ret = $oh->getOrderObject($plunet->session, $oid);
  $cd = $oh->getCreationDate($sess, $oid);
  update("insert into orders (user, usergroup, plunetid, name, comments, status, created, modified) values (:uid, :cid, :pid, :name, :comments, :status, :created, sysdate())",
	array(':cid'=>$customer[$ret->data->customerID],
	      ':uid'=>$users[$ret->data->customerContactID],
	      ':pid'=>$ret->data->orderID,
	      ':name'=>$ret->data->projectName,
	      ':comments'=>$ret->data->subject,
	      ':status'=>'unknown',
	      ':created'=>$plunet->parsedate($cd->data),
	      ));
  if ($plunet->parsedate($cd->data) > $lastactivity[$ret->data->customerID]) {
    update("update usergroups set lastactivity=:la where id=:id", array(':id'=>$customer[$ret->data->customerID], ':la'=>$plunet->parsedate($cd->data)));
  }
  $count++;
}

printf("Added %d orders.<br/>\n", $count);

?>
