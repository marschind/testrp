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
  if ($oid == 1101361 || $oid == 1110335) {
    printf("Skipping %s\n", $oid);
    continue;
  }
  printf("Processing %s\n", $oid);
  $ret = $oh->getOrderObject($plunet->session, $oid);
  if ($ret->data->requestID) {
    $ord = query("select * from orders where requestid=:rid", array(':rid'=>$ret->data->requestID));
  } else {
    $ord = query("select * from orders where orderid=:oid", array(':oid'=>$ret->data->orderID));
  }
  if ($ord[0]) {
    if ($ord[0]['name'] != $ret->data->projectName ||
        $ord[0]['orderid'] != $ret->data->orderID ||
        $ord[0]['quoteid'] != $ret->data->quoteID ||
	$ord[0]['comments'] != $ret->data->subject ||
	$ord[0]['status'] != 'unknown') {
      update("update orders set orderid=:oid, quoteid=:qid, name=:name, comments=:comments, status=:status, created=:created, modified=sysdate() where id=:id",
	array(':id'=>$ord[0]['id'],
	      ':oid'=>$ret->data->orderID,
	      ':qid'=>$ret->data->quoteID,
	      ':name'=>$ret->data->projectName,
	      ':comments'=>$ret->data->subject,
	      ':status'=>'unknown',
	      ':created'=>$plunet->parsedate($cd->data),
	      ));
      if ($plunet->parsedate($cd->data) > $lastactivity[$ret->data->customerID]) {
        update("update usergroups set lastactivity=:la where id=:id", array(':id'=>$customer[$ret->data->customerID], ':la'=>$plunet->parsedate($cd->data)));
      }
    $updates++;
    $changed++;
    }
  } else {
    $cd = $oh->getCreationDate($sess, $oid);
    update("insert into orders (user, usergroup, requestid, quoteid, orderid, name, comments, status, created, modified) values (:uid, :cid, :rid, :qid, :oid, :name, :comments, :status, :created, sysdate())",
	array(':cid'=>$customer[$ret->data->customerID],
	      ':uid'=>$users[$ret->data->customerContactID],
	      ':oid'=>$ret->data->orderID,
	      ':qid'=>$ret->data->quoteID,
	      ':rid'=>$ret->data->requestID,
	      ':name'=>$ret->data->projectName,
	      ':comments'=>$ret->data->subject,
	      ':status'=>'unknown',
	      ':created'=>$plunet->parsedate($cd->data),
	      ));
    $additions++;
    $changed++;
  }
  if ($changed && $plunet->parsedate($cd->data) > $lastactivity[$ret->data->customerID]) {
    update("update usergroups set lastactivity=:la where id=:id", array(':id'=>$customer[$ret->data->customerID], ':la'=>$plunet->parsedate($cd->data)));
  }
  $changed = 0;
  $count++;
}

printf("Added %d order%s, updated %d order%s.<br/>\n", $additions, $additions==1?'':'s', $updates, $updates==1?'':'s');

?>
