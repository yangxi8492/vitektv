<?php
require_once("../inc/init.php");
require_once("./inc/adminfun.php");
require_once("./language/language.php");

if(!isAdmin()){
	_header_("location:login.php?ref=".urlencode($_SERVER['REQUEST_URI']) );
}

//Select
$select_lang="<select style='width:130px' onchange='changeLanguage(this.value)'>";
foreach($cache_langs as $lang_item){
	$select_lang.="<option value='{$lang_item['id']}' title='../language/{$lang_item['directory']}/flag.gif'".($lang_item['id']==$_SYS['alangid']?' selected':'').">{$lang_item['name']}</option>";
}
$select_lang.='</select>';

$frame=$_GET['frame'];
$frame && $frame=urldecode($frame);
empty($frame) && $frame="admin.php?inc=main&action=settings";

$ctabjs='';
foreach($cache_channels as $chnid=>$chn){
	if($chn['ishidden']=='1' || $chn['pid']!='0' || !stristr($chn['positions'],'|1|'))continue;
	$ctabjs.=("pt.createTab(\"webmenu_{$chnid}\", \"{$chn['title']}\", \"".getChnAdminLink($chn)."\", false, \"n\");\r\n");
}

print<<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base target="mainifm">
<title>{$_AL['index.title']}</title>
<link href="css/global.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="msdropdown/dd.css" />
<script>if(top.location !== self.location){top.location=self.location;}</script>
<script type="text/javascript" src="../getfiles.php?t=js&v={$_SYS['VERSION']}&f=util|tab|popwin|jquery|admin"></script>
<script src="msdropdown/js/jquery.dd.js" type="text/javascript"></script>
<style>
	html{overflow:hidden;}
	body{overflow-y:hidden;}
</style>
</head>
<body>
<table width="100%" height="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
<tr>
	<td class="top_1" style="witdh:168px;"><a href="" target="_blank" title="{$_AL['index.visit']}"><img src="images/img_adminlogo.png" border="0" /></a></td>
	<td class="top_1">
		<div class="div_clear" style="height:25px;"></div>
		<table cellspacing="0" cellpadding="0" style="border-collapse:collapse;" width="100%"><tr><td></td><td><div id="bigtab_container"></div></td><td class="right" width="190px"><a href="admin.php?inc=lang&action=list">{$_AL['index.languageset']}</a> | <a href="../" target="_blank">{$_AL['index.homepage']}</a> | <a href="login.php?action=exit" target="_top">{$_AL['index.exit']}</a>&nbsp; &nbsp;</td></tr></table>
	</td>
</tr>
<tr>
	<td class="top_2"><div style='padding:0px 0px 0px 10px;'>{$select_lang}</div></td>
	<td class="top_2"><img src="images/ico_home.gif" border="0" style="margin-right:5px;" /> <span id="position">{$_AL['index.menu']}</span></td>
</tr>
<tr>
	<td class="left_1" valign="top">
		<div class="div_clear" style="height:15px;"></div>
		<div class="headbg" id="channel">{$_AL['index.channel']}</div>
		<ul id="channel_ul" style="display:none;">
			<li><a href="admin.php?inc=channel&action=add">{$_AL['index.channel.add']}</a></li>
			<li><a href="admin.php?inc=channel&action=set">{$_AL['index.channel.man']}</a></li>
		</ul>
		<div class="headbg" id="product">{$_AL['index.product.man']}</div>
		<ul id="product_ul" style="display:none;">
			<li><a href="admin.php?inc=procate&action=set">{$_AL['index.procate.list']}</a></li>
			<li><a href="admin.php?inc=products&action=add">{$_AL['index.product.add']}</a></li>
			<li><a href="admin.php?inc=products&action=list">{$_AL['index.product.list']}</a></li>
		</ul>
		<div class="headbg" id="order">{$_AL['index.order.man']}</div>
		<ul id="order_ul" style="display:none;">
			<li><a href="admin.php?inc=order&action=list&state=0">{$_AL['index.order.state0']}</a></li>
			<li><a href="admin.php?inc=order&action=list&state=1">{$_AL['index.order.state1']}</a></li>
			<li><a href="admin.php?inc=order&action=list&state=2">{$_AL['index.order.state2']}</a></li>
			<li><a href="admin.php?inc=order&action=list&state=3">{$_AL['index.order.state3']}</a></li>
		</ul>
		<div class="headbg" id="member">{$_AL['index.member.man']}</div>
		<ul id="member_ul" style="display:none;">
			<li><a href="admin.php?inc=member&action=settings">{$_AL['index.signup.man']}</a></li>
			<li><a href="admin.php?inc=member&action=verify">{$_AL['index.signup.verify']}</a></li>
			<li><a href="admin.php?inc=member&action=addmember">{$_AL['index.member.add']}</a></li>
			<li><a href="admin.php?inc=member&action=manager">{$_AL['index.member.list']}</a></li>
		</ul>
		<div class="headbg" id="setting">{$_AL['index.site.man']}</div>
		<ul id="setting_ul">
			<li><a href="admin.php?inc=main&action=settings">{$_AL['index.site.set']}</a></li>
			<li><a href="admin.php?inc=main&action=banner">{$_AL['index.banner.set']}</a></li>
			<li><a href="admin.php?inc=main&action=probanner">{$_AL['index.probanner.set']}</a></li>
			
			<li><a href="admin.php?inc=main&action=contact">{$_AL['index.contact.set']}</a></li>
			<li><a href="admin.php?inc=template&action=list">{$_AL['index.template.set']}</a></li>
			<li><a href="admin.php?inc=lang&action=list">{$_AL['index.language.set']}</a></li>
			<li><a href="admin.php?inc=user&action=manager">{$_AL['index.admin.set']}</a></li>
		</ul>
		<div class="headbg" id="other">{$_AL['index.other.set']}</div>
		<ul id="other_ul" style="display:none;">
			<li><a href="admin.php?inc=main&action=cache">{$_AL['index.sitecache.set']}</a></li>
			<li><a href="admin.php?inc=link&action=friendlink">{$_AL['index.frilink.list']}</a></li>
			<li><a href="admin.php?inc=msg&action=list">{$_AL['index.msg.list']}</a></li>
			<li><a href="admin.php?inc=vote&action=list">{$_AL['index.vote.list']}</a></li>
			<li><a href="admin.php?inc=main&action=email">{$_AL['index.email.set']}</a></li>
			<li><a href="admin.php?inc=main&action=attachment">{$_AL['index.attachment.set']}</a></li>
			<li><a href="admin.php?inc=database&action=backupform">{$_AL['index.data.man']}</a></li>
			<li><a href="admin.php?inc=main&action=fun">{$_AL['index.site.fun']}</a></li>
		</ul>
	</td>
	<td valign="top" height="100%">
		<iframe scrolling="yes" width="100%" height="100%" frameborder="0" style="overflow-y:srcoll;overflow-x:hidden;" name="mainifm" id="mainifm" src="{$frame}"></iframe>
	</td>
</tr>
</table>



<script>
var leftHead=['channel','product','order','member','setting','other'];
var menuNowTab;
var pt = new Tabs();
pt.classpre="bigtab_";
pt.container = "bigtab_container";
{$ctabjs}

pt.init = function(){
	menuNowTab = pt.nowTab;
};
pt.onclick = function(){
	menuNowTab = pt.nowTab;
	var url=pt.getValue();
	if(url=="")return;
	mainifm.location.href = url;
};		
pt.initTab();
pt.clickNowTab();

var liObjs =document.getElementsByTagName('li');
var curObj;
for(var i=0; i<liObjs.length; i++)
{
	(function(obj)
		{
			try{obj.children[0].hideFocus = "true";}catch(err){}
			obj.onmouseover = function(){obj.className="left_2";}
			obj.onmouseout = function(){if(curObj!=obj){obj.className="";}}
			obj.onclick = function(){clickMenuObj(obj);}
		}
	)(liObjs[i]);
}

for(var i=0; i<leftHead.length; i++)
{
	(function(objid)
		{
			//try{obj.children[0].hideFocus = "true";}catch(err){}
			
			E(objid).onclick = function(){hideLeftMenu();$("#"+objid+"_ul").show(100);}
		}
	)(leftHead[i]);
}

function clickMenuObj(obj){
	if(curObj){
		curObj.className="";
	}
	obj.className="left_2";
	curObj=obj;
	if(curObj){
		E("position").innerHTML = "{$_AL['index.menu']} » "+curObj.children[0].innerHTML;
	}
}
function insertAttachment(fileid, filename, isimg){
	mainifm.insertAttachment(fileid, filename, isimg);
}

function hideLeftMenu(){
	for(var i=0;i<leftHead.length;i++){
		$("#"+leftHead[i]+"_ul").hide(100);
		//setDisplay(leftHead[i]+"_ul",false);
	}
}



</script>
<script language="javascript">
$(document).ready(function(e) {
	try {
		$("body select").msDropDown();
	}catch(e) {
		alert(e.message);
	}
});

function changeLanguage(langid){
	top.location.href = "../index.php?alangid="+langid;
}


function reinitIframe(){
	var bodyobj=(document.documentElement) ? document.documentElement : document.body;
	var height=bodyobj.clientHeight-100;
	var iframe1 = document.getElementById("mainifm");
	try{
		iframe1.style.height =  height+"px";
	}catch (ex){

	}
}
if(checkBrowser()==1){
	window.setInterval(reinitIframe, 500);
}

</script>

</body>
</html>
EOT;
?>