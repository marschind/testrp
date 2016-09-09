<?php

class plunetApi {
  public $baseurl = null;
  public $plunet = null;
  public $session = null;
  public $handles = array();

  function __construct() {
    global $_CONFIG;
debuglog("Connecting to ".$_CONFIG['plunet']['pluneturl']);
    $this->plunet = new SoapClient($_CONFIG['plunet']['pluneturl'].'PlunetAPI?wsdl', array('soap_version'=>SOAP_1_2));
debuglog(sprintf("Auth %s, %s", $_CONFIG['plunet']['plunetuser'], $_CONFIG['plunet']['plunetpass']));
    $this->session = $this->plunet->login($_CONFIG['plunet']['plunetuser'], $_CONFIG['plunet']['plunetpass']);
debuglog("Got session key ".$this->session);
    if ($this->session == 'refused') {
      throw new Exception("Cannot authenticate to Plunet", ERR_PLUNET_COM);
    }
    $this->baseurl = $_CONFIG['plunet']['pluneturl'];
  }

  public function getHandle($name) {
    if (!$this->handles[$name]) {
      $this->handles[$name] = new SoapClient($this->baseurl.$name.'?wsdl', array('soap_version'=>SOAP_1_2));
    }
    return($this->handles[$name]);
  }

  public function createOrder($name, $comment) {
    try {
      $orderapi = $this->getHandle('DataOrder30');
      $ret = $orderapi->insert($this->session);
      $oid = $ret->data;
debuglog("Plunet order id=$oid");
      $orderapi->setProjectName($this->session, $name, $oid);
      if ($comment) {
	$orderapi->setSubject($this->session, $comment, $oid);
      }
      $ret = query('select * from usergroups where id=:cust', array(':cust'=>$_SETTINGS['user']['usergroup']));
      $cust = $ret['plunetid'];
      $orderapi->setCustomerID($this->session, $cust, $oid);
    } catch (Exception $ex) {
      throw new Exception("Failed to complete Plunet operation ".$ex->getMessage(), ERR_PLUNET_COM);
    }
  }

  public static function parsedate($s) {
    if (preg_match("/(\d+)-(\d+)-(\d+)T(\d+):(\d+):(\d+)([+-])(\d+):(\d+)/", $s, $a)) {
      return(sprintf("%d-%d-%d %2d:%02d:%02d", $a[1], $a[2], $a[3], $a[4], $a[5], $a[6]));
    } else if (preg_match("/(\d+)-(\d+)-(\d+)T(\d+):(\d+):(\d+)/", $s, $a)) {
      return(sprintf("%d-%d-%d %2d:%02d:%02d", $a[1], $a[2], $a[3], $a[4], $a[5], $a[6]));
    } else if (preg_match("/(\d+)-(\d+)-(\d+) (\d+):(\d+):(\d+)/", $s, $a)) {
      return(sprintf("%d-%d-%d %2d:%02d:%02d", $a[1], $a[2], $a[3], $a[4], $a[5], $a[6]));
    } else if (preg_match("/(\d+)-(\d+)-(\d+)/", $s, $a)) {
      return(sprintf("%d-%d-%d", $a[1], $a[2], $a[3]));
    } else {
      return('');
    }
  }
}

?>
