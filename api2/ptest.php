<?php

$pn = new SoapClient('http://plunetsandbox.acclaro.com:8991/PlunetAPI?wsdl',
	array('soap_version' => SOAP_1_2));

$ver = $pn->getVersion();
printf("Version: %s<br/>\n", $ver);
$sess = $pn->login('8991apiuser','5n8pr9ve');
printf("Got session ID %s<br/>\n", $sess);

if (0) {
  $dr = new SoapClient('http://plunetsandbox.acclaro.com:8991/DataRequest30?wsdl',
	  array('soap_version' => SOAP_1_2));

  $ret = $dr->getAll_Requests($sess);
}

if (0) {
  //$ret = $dr->insert($sess);
  //$rid = $ret->data;
  //printf("Created request %s<br/>\n", $rid);

  //$rid = 1118819;
  //$ret = $dr->getRequestObject($sess, $rid);

  //$rid = 1118819;
  //$ret = $dr->getOrderObject($sess, $rid);
}

if (1) {
  $rh = new SoapClient('http://plunetsandbox.acclaro.com:8991/ReportOrder25?wsdl',
	  array('soap_version' => SOAP_1_2));

//  $ret = $rh->search($sess);
//  $oid = $ret->data[sizeof($ret->data)-1];
  $oid = 1118827;
  $do = new SoapClient('http://plunetsandbox.acclaro.com:8991/DataOrder30?wsdl',
	  array('soap_version' => SOAP_1_2));

  $ret = $do->getOrderObject($sess, $oid);
}

if (0) {
  $oid = 1100793;
//  $oid = 1101361;
  $do = new SoapClient('http://plunetsandbox.acclaro.com:8991/DataRequest30?wsdl',
	  array('soap_version' => SOAP_1_2));

  $ret = $do->getRequestObject($sess, $oid);
  $ro = new SoapClient('http://plunetsandbox.acclaro.com:8991/DataOrder30?wsdl',
	  array('soap_version' => SOAP_1_2));

  $ret2 = $ro->getOrderObject($sess, $oid);
}

if (0) {
  $cid = 1596;
  $do = new SoapClient('http://plunetsandbox.acclaro.com:8991/DataCustomer30?wsdl',
	  array('soap_version' => SOAP_1_2));

  $ret = $do->getCustomerObject($sess, $cid);
}


print("<pre>\n");
print_r($ret);
//print_r($ret2);
print("</pre>\n");


?>
