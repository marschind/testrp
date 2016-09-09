<?php
include_once("../lib/portallib.php");
logincheck();
pagetop('Admin', 'Accounts');
?>
<div class="sechead">
  <div class="secright">
    <img src="/images/Reset.png"/>
  </div>
  <div class="secleft">
    <img src="/images/Search.png"/>
  </div>
  <div class="seccenter">
    View by Activity in Last 30 Days <img src="/images/ArrowDnWhite.png"/>
  </div>
</div>
<div class="section2">
  <img id="toggleallicon" src="/images/OpenAllRows.png" onclick=\"toggleAllCustomers()\"/> &nbsp; Account Name <img src="/images/ArrowDnGreySm.png"/>
</div>
<?php
  $res = query("select * from usergroups limit 10", array());
  foreach ($res as $acct) {
    printf("<div id=\"section%d\" class=\"section\">\n<img id=\"opensec%d\" class=\"opensec\" src=\"/images/CloseRow.png\" onclick=\"toggleCustomer(%d)\"> &nbsp; %s</div>\n", $acct['id'], $acct['id'], $acct['id'], $acct['company']);
    printf("<div id=\"ssection%d\" class=\"gsection \">\n", $acct['id']);
?>
<table>
<tr><td><b>Portal Users</b></td><td><b>Email Address</b></td><td><b>Portal</b></td><td><b>Password</b></td><td><b>API Token</b><td></tr>
<?php
$res2 = query("select * from users where usergroup=:gid", array(':gid'=>$acct['id']));
if (!is_array($res2)) { $res2 = array(); }
foreach ($res2 as $u) {
  printf("<tr><td><a href=\"%spagesUTF8/partner_kunde.jsp?OSGO1=%d#AP%d\" target=\"_blank\">%s %s</a></td><td><a href=\"mailto:%s\">%s</a></td><td>
<div id=\"aapdp%d\" class=\"aapd aapdportal\"><span id=\"aapdp%dl\">%s</span><div id=\"aapdp%sdd\" class=\"aapddd\">
<div class=\"ddtip\"></div>
<div class=\"ddtop\"></div>
<div class=\"dditem dditem%d\">Enabled</div>
<div class=\"ddsep\"></div>
<div class=\"dditem dditem%d\">Disabled</div>
<div class=\"ddbottom\"></div>
</div></div>
</td><td>
<div id=\"aapdw%d\" class=\"aapd aapdpasswd\"><span id=\"aapdw%dl\">%s</span><div id=\"aapdw%sdd\" class=\"aapddd\">
<div class=\"ddtip\"></div>
<div class=\"ddtop\"></div>
<div class=\"dditem dditem%d\">Activated</div>
<div class=\"ddsep\"></div>
<div class=\"dditem dditem%d\">Pending</div>
<div class=\"ddsep\"></div>
<div class=\"dditem dditem%d\">Reset</div>
<div class=\"ddsep\"></div>
<div class=\"dditem ddinvite dditem%s\">Invite</div>
<div class=\"ddbottom%s\"></div>
</div></div>
</td><td>
<div id=\"aapdt%d\" class=\"aapd aapdtoken\"><span id=\"aapdt%dl\">%s</span><div id=\"aapdt%sdd\" class=\"aapddd\">
<div class=\"ddtip\"></div>
<div class=\"ddtop\"></div>
<div class=\"dditem dditem%d\">Enabled</div>
<div class=\"ddsep\"></div>
<div class=\"dditem dditem%d\">Disabled</div>
<div class=\"ddsep\"></div>
<div class=\"dditem ddview dditem%s\">View</div>
<div class=\"ddbottom%s\"></div>
</div></div>
</td></tr>\n",
  	$_CONFIG['plunet']['pluneturl'], $acct['plunetid'], $u['plunetid'], $u['firstname'], $u['lastname'], $u['email'], $u['email'], $u['id'], $u['id'], $u['portalenable']?'Enabled':'Disabled', $u['id'], $u['portalenable']?1:0, $u['portalenable']?0:1,
$u['id'], $u['id'], ucfirst($u['passwdstate']).'&nbsp;', $u['id'], $u['passwdstate']=='activated'?1:0, $u['passwdstate']=='pending'?1:0, $u['passwdstate']=='reset'?1:0, $u['passwdstate']=='activated'?'g':'b', $u['passwdstate']=='activated'?'g':'b',
$u['id'], $u['id'], $u['apienable']?'Enabled':'Disabled', $u['id'], $u['apienable']?1:0, $u['apienable']?0:1, $u['apienable']?'b':'g', $u['apienable']?'b':'g');
}
?>
  <tr><td><div id="aacsname"></div><td><div id="aacsemail"></div></td><td><div id="aacsportal"></div></td><td><div id="aacspasswd"></div></td><td><div id="aacstoken"></div></td></tr>
</table>
<?php
    print("</div>\n");
  }
?>
<div id="invitebox" class="infobox">
  <div class="infoheader">
    <div id="inviteclose" class="infoclose">
      <img src="/images/CloseWindow-White.png"/>
    </div>
    Invite
  </div>
  <div id="invitedisplay" class="tokendisplay"></div>
</div>
<div id="tokenbox" class="infobox">
  <div class="infoheader">
    <div id="tokenclose" class="infoclose">
      <img src="/images/CloseWindow-White.png"/>
    </div>
    API Token
  </div>
  <div id="tokendisplay" class="tokendisplay"></div>
</div>
<script type="text/javascript">
  function toggleCustomer(i) {
    if ($('#ssection'+i).is(':hidden')) {
      $('#ssection'+i).show();
      $('#opensec'+i).src = '/images/CloseRow.png';
    } else {
      $('#ssection'+i).hide();
      $('#opensec'+i).src = '/images/OpenRow.png';
    }
  }
  function toggleAllCustomers() {
  }
  $(document).ready(function(){
    $('.aapd').click(function(){
      var tag = $('#'+this.id+'l').html();
      $('#'+this.id+'dd .dditem').each(function(){
        if ($(this).html() == tag) {
	  $(this).addClass('dditem1').removeClass('dditem0');
	} else {
	  $(this).addClass('dditem0').removeClass('dditem1');
	}
      });
      if (tag == 'Enabled') {
        $('#'+this.id+'dd .ddbottomg').addClass('ddbottomb').removeClass('ddbottomg');
        $('#'+this.id+'dd .ddview').addClass('dditemb').removeClass('dditemg');
      } else if (tag == 'Disabled') {
        $('#'+this.id+'dd .ddbottomb').addClass('ddbottomg').removeClass('ddbottomb');
        $('#'+this.id+'dd .ddview').addClass('dditemg').removeClass('dditemb');
      } else if (tag == 'Activated') {
        $('#'+this.id+'dd .ddbottomb').addClass('ddbottomg').removeClass('ddbottomb');
        $('#'+this.id+'dd .ddinvite').addClass('dditemg').removeClass('dditemb');
      } else if (tag == 'Pending' || tag == 'Reset') {
        $('#'+this.id+'dd .ddbottomg').addClass('ddbottomb').removeClass('ddbottomg');
        $('#'+this.id+'dd .ddinvite').addClass('dditemb').removeClass('dditemg');
      }
      $('#'+this.id+'dd').toggle();
    });
    $('.aapd .dditem').click(function(){
	var tag = $(this).html();
	if (tag == 'View') {
	  $('#tokendisplay').load('/portal/viewtoken.php?u='+$(this).parent().parent().attr('id'), function(){
	    $('#tokenbox').show();
	    SelectText('tokendisplay');
	  });
	} else if (tag == 'Invite') {
	  $('#invitedisplay').load('/portal/viewinvite.php?u='+$(this).parent().parent().attr('id'), function(){
	    $('#invitebox').show();
	    SelectText('invitedisplay');
	  });
	} else {
	  $.get('/portal/menuclick.php?t='+$(this).parent().attr('id')+'&v='+tag);
	  $('#'+$(this).parent().parent().attr('id')+'l').html(tag);
	}
    });
    $('#inviteclose').click(function(){
      $('#invitebox').hide();
    });
    $('#tokenclose').click(function(){
      $('#tokenbox').hide();
    });
  });
</script>
<?php pagebottom(); ?>
