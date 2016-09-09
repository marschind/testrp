<?php
include_once("../lib/portallib.php");
include_once("../lib/plunetapi.php");

$plunet = new plunetApi();
$ch = $plunet->getHandle('DataCustomer30');

$ret = $ch->getAllCustomerObjects($plunet->session, 1);

print("getAllCustomerObjects<br/>\n");
print("<pre>\n");
print_r($ret);
print("</pre>\n");

for ($i = 0; $i < sizeof($ret->data); $i++) {
  update("insert into usergroups (plunetid, type, company, phone, url, created, modified) values (:id, 'customer',:name, :phone, :url, sysdate(), sysdate())",
  	array(':id'=>$ret->data[$i]->customerID,
	      ':name'=>$ret->data[$i]->fullName,
	      ':phone'=>$ret->data[$i]->phone,
	      ':url'=>$ret->data[$i]->website));
  $count++;
}

printf("Added %d customers.<br/>\n", $count);

?>
