<?php
switch($action){
	/************************************** popedom BEGIN ************************************************/
	case "popedom":
	$uid=intval($_GET['uid']);
	$row=$db->row_select_one("users","id={$uid}");
	$row['username']=htmlFilter($row['username']);
echo <<<EOT
	<style>
		.td_1{padding-left:30px; line-height:180%; width:600px;}
	</style>
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div id="t1">
		<form id="popedomform" onsubmit="return false;">
		<input type="hidden" name="userid" value="{$uid}" />
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['index.channel']}:</td><td class=""></td></tr>
			<tr><td class="td_1">	
				<input type="checkbox" name="popedom[]" value="channel" /> {$_AL['index.channel.man']}<br />
				<input type="checkbox" name="popedom[]" value="page" /> {$_AL['index.page.man']}<br />
				<input type="checkbox" name="popedom[]" value="article" /> {$_AL['index.article.man']}<br />
			</td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['index.product.man']}:</td><td class=""></td></tr>
			<tr><td class="td_1">	
				 <input type="checkbox" name="popedom[]" value="procate" /> {$_AL['index.procate.list']}<br />
				 <input type="checkbox" name="popedom[]" value="products" /> {$_AL['index.product.list']}<br />
			</td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['index.order.man']}:</td><td class=""></td></tr>
			<tr><td class="td_1">	
				 <input type="checkbox" name="popedom[]" value="order" /> {$_AL['index.order.man']}<br />
			</td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['index.member.man']}:</td><td class=""></td></tr>
			<tr><td class="td_1">	
				 <input type="checkbox" name="popedom[]" value="member" /> {$_AL['index.member.list']}<br />
			</td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['index.site.man']}/{$_AL['index.other.set']}:</td><td class=""></td></tr>
			<tr><td class="td_1">	
				 <input type="checkbox" name="popedom[]" value="main" /> {$_AL['index.site.set']},{$_AL['index.banner.set']},{$_AL['index.contact.set']},{$_AL['index.sitecache.set']},{$_AL['index.email.set']},{$_AL['index.attachment.set']},{$_AL['index.site.fun']}<br />
				 <input type="checkbox" name="popedom[]" value="lang" /> {$_AL['index.language.set']}<br />
				 <input type="checkbox" name="popedom[]" value="template" /> {$_AL['index.template.set']}<br />
				 <input type="checkbox" name="popedom[]" value="link" /> {$_AL['index.frilink.list']}<br />
				 <input type="checkbox" name="popedom[]" value="msg" /> {$_AL['index.msg.list']}<br />
				 <input type="checkbox" name="popedom[]" value="vote" /> {$_AL['index.vote.list']}<br />
				 <input type="checkbox" name="popedom[]" value="user" /> {$_AL['index.admin.set']}<br />
				 <input type="checkbox" name="popedom[]" value="database" /> {$_AL['index.data.man']}<br />
			</td><td class="td_2"></td></tr>

			<tr><td class="td_3"><input type="checkbox" onclick="selectAll('popedomform',this.checked)" /> {$_AL['all.selectall']} &nbsp; &nbsp; <input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_savepopedom()" /></td><td class=""></td></tr>
		</table>
		</form>
	</div>
	<div id="t2"></div>
	<div id="t3"></div>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	var popedom="{$row['popedom']}";
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['user.editppd']}({$row['username']})","",true,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	
	function ajax_savepopedom(){
		popwin.loading();
		ajaxPost("popedomform","user_ajax.php?action=savepopedom",ajax_savepopedom_callback);
	}
	function ajax_savepopedom_callback(data){
		var btns;
		popwin.loaded();
		if(isSucceed(data)){
			btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.href='admin.php?inc=user&action=manager';",focus:true}];
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['user.editppd.succeed']}",btns,280,130);
		}else{
			btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}

	function initCheckbox(){
		var boxs=document.getElementsByName("popedom[]");
		for(var n=0;n<boxs.length;n++){
			if(popedom.indexOf("|"+boxs[n].value+"|")>-1){
				boxs[n].checked=true;
			}
		}
	}

	function InitPage(){
		initCheckbox();
	}
	
	window.onload = InitPage;
		
	</script>
EOT;
	break;
	/************************************** popedom END ************************************************/


	/************************************** edituser BEGIN ************************************************/
	case "edituser":
	$uid=intval($_GET['uid']);
	$row=$db->row_select_one("users","id={$uid}","*");
	$row['addtime']=getDateStr($row['addtime'],false,false);
	$row['lasttime']=empty($row['lasttime'])?"":getDateStr($row['lasttime'],false,false);
	$row['displayname']=htmlFilter($row['username']);
echo <<<EOT
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div id="t1">
		<form id="userform" onsubmit="return false;">
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['user.username']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="hidden" value="{$row['username']}" size="30" name="user[username]" /><b>{$row['displayname']}</b></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['user.newpass']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="" size="30" name="user[userpass]" class="text_css" /></td><td class="td_2">{$_AL['user.newpass.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['user.realname']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['realname']}" size="30" name="user[realname]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['user.email']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['email']}" size="30" name="user[email]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['user.lastip']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['lastip']}" size="30" name="user[lastip]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['user.lastlogin']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['lasttime']}" size="30" name="user[lasttime]" class="text_css" /></td><td class="td_2">{$_AL['user.time.remark']}: 2011-01-01 00:00</td></tr>
			<tr><td class="td_0">{$_AL['user.addtime']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['addtime']}" size="30" name="user[addtime]" class="text_css" /></td><td class="td_2">{$_AL['user.time.remark']}: 2011-01-01 00:00</td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_edituser()" /></td><td class=""></td></tr>
		</table>
		</form>
	</div>
	<div id="t2"></div>
	<div id="t3"></div>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['user.edittab.title']}","",true,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
		
	function ajax_edituser(){
		popwin.loading();
		ajaxPost("userform","user_ajax.php?action=edituser&uid={$uid}",ajax_edituser_callback);
	}
	function ajax_edituser_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.href='admin.php?inc=user&action=manager';",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['user.edit.succeed']} ({$row['displayname']})",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}

	</script>
EOT;
	break;
	/************************************** edituser END ************************************************/


	/************************************** adduser BEGIN ************************************************/
	case "adduser":
echo <<<EOT
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div id="t1">
		<form id="userform" onsubmit="return false;">
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['user.username']}:<span class="required">(*)</span></td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="" size="30" name="user[username]" id="user[username]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['user.pass']}:<span class="required">(*)</span></td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="" size="30" name="user[userpass]" id="user[userpass]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['user.email']}:<span class="required">(*)</span></td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="" size="30" name="user[email]" id="user[email]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_adduser()" /></td><td class=""></td></tr>
		</table>
		</form>
	</div>
	<div id="t2"></div>
	<div id="t3"></div>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['user.add.tab']}","",true,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	
	function ajax_adduser(){
		if(getV("user[username]")==""||getV("user[userpass]")==""||getV("user[email]")==""){
			var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
			popwin.showDialog(2,"{$_AL['all.tips']}","{$_AL['all.required']}",btns,280,130);
			return;
		}
		popwin.loading();
		ajaxPost("userform","user_ajax.php?action=adduser",ajax_adduser_callback);
	}
	function ajax_adduser_callback(data){
		popwin.loaded();
		if(isSucceed(data)){
			var url="admin.php?inc=user&action=popedom&uid="+getCallBackData(data);
			var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.href='"+url+"'",focus:true}];
			popwin.showDialog(1,"{$_AL['all.con.succeed']}","{$_AL['user.add.succeed']}",btns,280,130);
		}else{
			var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}
	
	</script>
EOT;
	break;
	/************************************** adduser END ************************************************/


	/************************************** manager/search BEGIN ************************************************/
	case "manager":
	require_once('./../inc/pager.php');

echo <<<EOT
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div id="t1">
EOT;
	$dwidth=array(0,35,120,100,140,160,160,150);
	$curPage = intval($_GET["page"]);
	$pager = new Pager();
	$cond="ishidden=0";
	$pager->init(10,$curPage,"admin.php?inc=user&action=search&username={$username}&userid={$userid}&groupid={$groupid}&page={page}");
	$rows = $pager->queryRows($db,"users", $cond , "*","id desc");
		
echo <<<EOT
	<div class="div_clear" style="height:10px;"></div>
EOT;
	echo("<form id=\"usersform\" onsubmit=\"return false;\">");
	echo("<table class=\"table_1\" width=\"100%\">");
	echo("<tr style=\"font-weight:bold;color:#333333;\"><td class=\"row_0\"><div class=\"rowdiv_0\" style=\"width:{$dwidth[1]}px;\"><span class=\"warning\">{$_AL['all.delete']}</span></div><div class=\"rowdiv_0\" style=\"width:{$dwidth[2]}px;\">{$_AL['user.username']}</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[3]}px;\">{$_AL['user.realname']}</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[4]}px;\">{$_AL['user.lastip']}</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[5]}px;\">{$_AL['user.lastlogin']}</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[6]}px;\">{$_AL['user.addtime']}</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[7]}px;\">{$_AL['all.control']}</div></td></tr>");
	for($i=0;$i<count($rows);$i++){
		$row=$rows[$i];
		$row['lastip']=empty($row['lastip'])?'--':$row['lastip'];
		$row['lasttime']=empty($row['lasttime'])?'--':getDateStr($row['lasttime']);
		$row['addtime']=empty($row['addtime'])?'--':getDateStr($row['addtime']);
		$row['username']=htmlFilter($row['username']);
		$row['realname']=htmlFilter($row['realname']);
		$checkboxstr='';
		if($row['id']==$lg['userid']){
			$checkboxstr="<input type=\"checkbox\" disabled=\"true\" class=\"checkbox_css\" />";
		}else{
			$checkboxstr="<input type=\"checkbox\" value=\"{$row['id']}\" name=\"deluid[]\" class=\"checkbox_css\" />";
		}
		echo("<tr><td class=\"row_0\" style=\"line-height:150%;\"><div class='rowdiv_0' style='width:{$dwidth[1]}px;'>{$checkboxstr}</div><div class='rowdiv_0' style='width:{$dwidth[2]}px;'><a href=\"admin.php?inc=user&action=edituser&uid={$row['id']}\">{$row['username']}</a>&nbsp;</div><div class='rowdiv_0' style='width:{$dwidth[3]}px;'>{$row['realname']}&nbsp;</div><div class='rowdiv_0' style='width:{$dwidth[4]}px;'>{$row['lastip']}</div><div class='rowdiv_0' style='width:{$dwidth[5]}px;'>{$row['lasttime']}</div><div class='rowdiv_0' style='width:{$dwidth[6]}px;'>{$row['addtime']}</div><div class='rowdiv_0' style='width:{$dwidth[7]}px;'><a href=\"admin.php?inc=user&action=edituser&uid={$row['id']}\">{$_AL['all.edit']}</a>&nbsp;&nbsp; <a href=\"admin.php?inc=user&action=popedom&uid={$row['id']}\">{$_AL['user.popedom']}</a></div></td></tr>");				
	}
	echo("</table>");
echo <<<EOT
	<table class="table_1" width="100%">
		<tr><td class="td_6"><a class="td_5_1a" href="admin.php?inc=user&action=adduser"><img src="images/ico_add.gif" border="0" /> {$_AL['user.add.tab']}</a></td></tr>
	</table>
EOT;
	echo("</form>");
	echo("<table width=100%><tr><td><input type=\"checkbox\" onclick=\"selectAll('usersform',this.checked)\" class=\"checkbox_css\" /> {$_AL['all.selectall']} &nbsp;  &nbsp; <input class=\"button_css\" type=\"button\" value=\"  {$_AL['all.deletesel']}  \" onclick=\"ajax_delusers_yn()\" /></td><td><div class='pagestrdiv'>{$pager->getPageStr()}</div></td></tr></table>");
 	
echo <<<EOT
	
	</div>
	<div id="t2"></div>
	<div id="t3"></div>
	<div class="div_clear" style="height:30px;"></div>
<script>
var smallNowTab;
var pt = new Tabs();
pt.classpre="smalltab_";
pt.container = "smalltab_container";
pt.createTab("t1","{$_AL['user.tab.set']}","",true,"n");
pt.init = function(){
	smallNowTab = pt.nowTab;
};
pt.onclick = function(){
	smallNowTab = pt.nowTab;
};		
pt.initTab();
pt.clickNowTab();

function searchuser(){
	var loc = "admin.php?inc=user&action=search&username="+document.getElementById("username").value+"&userid="+document.getElementById("userid").value+"&groupid="+getMultipleValue(document.getElementById("groupid"));
	reloadSelf(loc);
}

function ajax_delusers_yn(){
	var btns=[
		{value:" {$_AL['all.confirm']} ",onclick:"mainifm.ajax_delusers()",focus:true},
		{value:" {$_AL['all.cancel']} ",onclick:"popwin.close()"}
	];
	popwin.showDialog(3,"{$_AL['all.confirm']}","{$_AL['user.del.warning']}",btns,320,130);
}

function ajax_delusers(){
	popwin.loading();
	ajaxPost("usersform","user_ajax.php?action=delusers",ajax_delusers_callback);
}
function ajax_delusers_callback(data){
	var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,"{$_AL['all.del.succeed']}","{$_AL['user.del.succeed']}",btns,280,130);
	}else{
		popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
	}
}

function PageInit(){
	if(E("username")){E("username").onkeyup = function(event){checkKeyPressEnter(event);};E("username").focus();}
	if(E("userid")){E("userid").onkeyup = function(event){checkKeyPressEnter(event);};}
	
}

function checkKeyPressEnter(eventobject){
	var eve=eventobject||window.event;
	if(eve.keyCode==13) {
		searchuser();
	}
}
window.onload=PageInit;

</script>
EOT;
	break;
	/**************************************  manager/search END ************************************************/
}	
?>