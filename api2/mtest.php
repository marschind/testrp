<?php

$lr = new SoapClient('http://memoqdev.acclaro.com:8080/memoqservices/resource?wsdl');

$ret = $lr->ListResources();


print("<pre>\n");
print_r($ret);
print("</pre>\n");


?>
