<?php
include_once("../lib/portallib.php");
logincheck();
pagetop('Admin', 'System');

if (!$_SESSION['userrec']['issiteadmin']) {
  printf("<h2>Permission Denied</h2>\n<p>You do not have permission to access this page.</p>\n");
  pagebottom();
  exit;
}

if (!$_SESSION['token']) {
  $_SESSION['token'] = JWT::encode($_SESSION['userrec']['id']);
}
?>
<h2>API Demo</h2>

<?php
if (is_array($_REQUEST['op'])) {
  $_SESSION['token'] = $_REQUEST['token'];
  $_SETTINGS['token'] = $_SESSION['token'];
  if ($_REQUEST['op']['GetLanguages']) {
    $ret = apicall('GetLanguages', array());
  } else if ($_REQUEST['op']['GetLanguagePairs']) {
    $ret = apicall('GetLanguagePairs', array());
  } else if ($_REQUEST['op']['GetOrders']) {
    $ret = apicall('GetOrders', array());
  } else if ($_REQUEST['op']['GetOrder']) {
    $ret = apicall('GetOrder', array('orderid'=>$_REQUEST['GOorderid']));
  } else if ($_REQUEST['op']['CreateOrder']) {
    $ret = apicall('CreateOrder', array('name'=>$_REQUEST['COname'], 'comments'=>$_REQUEST['COcomments'], 'duedate'=>reversedate($_REQUEST['COdue'])));
  } else if ($_REQUEST['op']['EditOrder']) {
    $ret = apicall('EditOrder', array('orderid'=>$_REQUEST['EOid'], 'name'=>$_REQUEST['EOname'], 'comments'=>$_REQUEST['EOcomments'], 'duedate'=>reversedate($_REQUEST['EOdue'])));
  } else if ($_REQUEST['op']['DeleteOrder']) {
    $ret = apicall('DeleteOrder', array('orderid'=>$_REQUEST['DOid']));
  } else if ($_REQUEST['op']['SubmitOrder']) {
    $ret = apicall('SubmitOrder', array('orderid'=>$_REQUEST['SOid']));
  } else if ($_REQUEST['op']['RequestOrderCallback']) {
    $ret = apicall('RequestOrderCallback', array('orderid'=>$_REQUEST['ROid'], 'url'=>$_REQUEST['ROurl']));
  } else if ($_REQUEST['op']['CancelOrderCallback']) {
    $ret = apicall('CancelOrderCallback', array('orderid'=>$_REQUEST['COid'], 'url'=>$_REQUEST['COurl']));
  } else if ($_REQUEST['op']['SendFile']) {
    $ret = apicallfile('SendFile', array('orderid'=>$_REQUEST['SFid'], 'sourcelang'=>$_REQUEST['SFsrc'], 'targetlang'=>$_REQUEST['SFtgt']), $_FILES['SFfile']);
  } else if ($_REQUEST['op']['ReplaceFile']) {
    $ret = apicallfile('ReplaceFile', array('orderid'=>$_REQUEST['RFid'], 'sourcelang'=>$_REQUEST['RFsrc'], 'targetlang'=>$_REQUEST['RFtgt']), $_FILES['RFfile']);
  } else if ($_REQUEST['op']['GetFileInfo']) {
    $ret = apicall('GetFileInfo', array('orderid'=>$_REQUEST['GFIid']));
  } else if ($_REQUEST['op']['RequestFileCallback']) {
    $ret = apicall('RequestFileCallback', array('orderid'=>$_REQUEST['RFCid'], 'filename'=>$_REQUEST['RFCfilename'], 'url'=>$_REQUEST['RFCurl']));
  } else if ($_REQUEST['op']['CancelFileCallback']) {
    $ret = apicall('CancelFileCallback', array('orderid'=>$_REQUEST['CFCid'], 'filename'=>$_REQUEST['CFCfilename'], 'url'=>$_REQUEST['CFCurl']));
  }
  if ($ret) {
    $op = array_keys($_REQUEST['op'])[0];
    ?>    
      <div class="scroller">
      <b>Operation: <?= $op ?></b><br/>
      <pre>
      <?php print_r($ret); ?>
      </pre>
      </div>
    <?php
  }
}
?>

<form method="post" enctype="multipart/form-data">
Web Token: <input type="text" name="token" size="64" value="<?= $_SESSION['token'] ?>"/><br/>
<hr/>
<b>GetLanguages</b>: <input type="submit" value="GO" name="op[GetLanguages]"/>

<hr/>
<b>GetLanguagePairs</b>: <input type="submit" value="GO" name="op[GetLanguagePairs]"/>

<hr style="border-top: 3px solid black"/>
<b>GetOrders</b>: <input type="submit" value="GO" name="op[GetOrders]"/>

<hr/>
<b>GetOrder</b>: Order ID: <input type="text" name="GOorderid" size="12"/>
<input type="submit" value="GO" name="op[GetOrder]"/>

<hr/>
<b>CreateOrder</b>:
Name: <input type="text" name="COname" size="24"/>
Comment: <input type="text" name="COcomments" size="36"/>
Due Date: <input type="text" name="COdue" size="12"/>mm/dd/yyyy
<input type="submit" value="GO" name="op[CreateOrder]"/>

<hr/>
<b>EditOrder</b>:
ID: <input type="text" name="EOid" size="10"/>
Name: <input type="text" name="EOname" size="24"/>
Comment: <input type="text" name="EOcomments" size="36"/>
Due Date: <input type="text" name="EOdue" size="12"/>mm/dd/yyyy
<input type="submit" value="GO" name="op[EditOrder]"/>

<hr/>
<b>DeleteOrder</b>:
ID: <input type="text" name="DOid" size="10"/>
<input type="submit" value="GO" name="op[DeleteOrder]"/>

<hr/>
<b>SubmitOrder</b>:
ID: <input type="text" name="SOid" size="10"/>
<input type="submit" value="GO" name="op[SubmitOrder]"/>

<hr/>
<b>RequestOrderCallback</b>:
ID: <input type="text" name="ROid" size="10"/>
Url: <input type="text" name="ROurl" size="32"/>
<input type="submit" value="GO" name="op[RequestOrderCallback]"/>

<hr/>
<b>CancelOrderCallback</b>:
ID: <input type="text" name="COid" size="10"/>
Url: <input type="text" name="COurl" size="32"/>
<input type="submit" value="GO" name="op[CancelOrderCallback]"/>

<hr style="border-top: 3px solid black"/>
<b>SendFile</b>:
OrderID: <input type="text" name="SFid" size="10"/>
Sourcelang: <input type="text" name="SFsrc" size="10"/>
Targetlang: <input type="text" name="SFtgt" size="10"/>
File: <input type="file" name="SFfile"/>
<input type="submit" value="GO" name="op[SendFile]"/>

<hr/>
<b>ReplaceFile</b>:
OrderID: <input type="text" name="RFid" size="10"/>
Sourcelang: <input type="text" name="RFsrc" size="10"/>
Targetlang: <input type="text" name="RFtgt" size="10"/>
File: <input type="file" name="RFfile"/>
<input type="submit" value="GO" name="op[ReplaceFile]"/>

<hr/>
<b>GetFileInfo</b>:
OrderID: <input type="text" name="GFIid" size="10"/>
<input type="submit" value="GO" name="op[GetFileInfo]"/>

<hr/>
<b>RequestFileCallback</b>:
OrderID: <input type="text" name="RFCid" size="10"/>
Filename: <input type="text" name="RFCfilename" size="24"/>
Url: <input type="text" name="RFCurl" size="32"/>
<input type="submit" value="GO" name="op[RequestFileCallback]"/>

<hr/>
<b>CancelFileCallback</b>:
OrderID: <input type="text" name="CFCid" size="10"/>
Filename: <input type="text" name="CFCfilename" size="24"/>
Url: <input type="text" name="CFCurl" size="32"/>
<input type="submit" value="GO" name="op[CancelFileCallback]"/>

</form>

<?php pagebottom(); ?>
