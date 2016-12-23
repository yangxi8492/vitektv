<?php
!FULL_VERSION && $fullversiontips=$_AL['member.fun.ad'];
echo($fullversiontips);
switch($action){

	/************************************** editmember BEGIN ************************************************/
	case "editmember":
	$uid=intval($_GET['uid']);
	$row=$db->row_select_one("members","id={$uid}","*");
	$row['signuptime']=getDateStr($row['signuptime'],false,false);
	$row['logintime']=empty($row['logintime'])?"":getDateStr($row['logintime'],false,false);
	$row['lastlogintime']=empty($row['lastlogintime'])?"":getDateStr($row['lastlogintime'],false,false);
	$row['displayname']=htmlFilter($row['membername']);
echo <<<EOT
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div id="t1">
		<form id="memberform" onsubmit="return false;">
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['member.name']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="hidden" value="{$row['membername']}" size="30" name="member[membername]" /><b>{$row['displayname']}</b></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['member.newpass']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="" size="30" name="member[memberpass]" class="text_css" /></td><td class="td_2">{$_AL['member.newpass.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['member.sex']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="member[sex]" class="radio_css" /> {$_AL['member.male']} &nbsp; &nbsp;<input type="radio" value="0" name="member[sex]" class="radio_css" /> {$_AL['member.female']}</td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['member.email']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['email']}" size="30" name="member[email]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['member.signupip']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['signupip']}" size="30" name="member[signupip]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['member.signuptime']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['signuptime']}" size="30" name="member[signuptime]" class="text_css" /></td><td class="td_2">{$_AL['member.time.remark']}: 2011-01-01 01:01</td></tr>
			<tr><td class="td_0">{$_AL['member.lastlogin.time']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['logintime']}" size="30" name="member[logintime]" class="text_css" /></td><td class="td_2">{$_AL['member.time.remark']}: 2011-01-01 01:01</td></tr>
			<tr><td class="td_0">{$_AL['member.realname']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['realname']}" size="30" name="member[realname]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['member.bitrh']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['birthday']}" size="30" name="member[birthday]" class="text_css" /></td><td class="td_2">{$_AL['member.time.remark']}: 2011-01-01</td></tr>
			<tr><td class="td_0">{$_AL['member.qq']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['qq']}" size="30" name="member[qq]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['member.msn']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['msn']}" size="30" name="member[msn]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['member.phone']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['phone']}" size="30" name="member[phone]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_editmember()" /></td><td class=""></td></tr>
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
	pt.createTab("t1","{$_AL['member.edit.title']}","",true,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	
	setRadioCheck("member[sex]",{$row['sex']});
	
	function ajax_editmember(){
		popwin.loading();
		ajaxPost("memberform","member_ajax.php?action=editmember&uid={$uid}",ajax_editmember_callback);
	}
	function ajax_editmember_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['member.edit.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}
	
	</script>
EOT;
	break;
	/************************************** editmember END ************************************************/


	/************************************** addmember BEGIN ************************************************/
	case "addmember":
echo <<<EOT
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div id="t1">
		<form id="memberform" onsubmit="return false;">
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['member.name']}:<span class="required">(*)</span></td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="" size="30" name="member[membername]" id="member[membername]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['member.pass']}:<span class="required">(*)</span></td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="" size="30" name="member[memberpass]" id="member[memberpass]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['member.email']}:<span class="required">(*)</span></td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="" size="30" name="member[email]" id="member[email]" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_addmember()" /></td><td class=""></td></tr>
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
	pt.createTab("t1","{$_AL['member.add.title']}","",true,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	E("member[membername]").focus();
	function ajax_addmember(){
		if(getV("member[membername]")==""||getV("member[memberpass]")==""||getV("member[email]")==""){
			var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
			popwin.showDialog(2,"{$_AL['all.tips']}","{$_AL['all.required']}",btns,280,130);
			return;
		}
		popwin.loading();
		ajaxPost("memberform","member_ajax.php?action=addmember",ajax_addmember_callback);
	}
	function ajax_addmember_callback(data){
		popwin.loaded();
		if(isSucceed(data)){
			var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['member.add.succeed']}",btns,280,130);
		}else{
			var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}
	
	</script>
EOT;
	break;
	/************************************** addmember END ************************************************/


	/************************************** manager/search BEGIN ************************************************/
	case "manager":
	case "search":
	require_once('../inc/pager.php');

echo <<<EOT
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div id="t1">
EOT;

	 	$dwidth=array(0,35,190,130,160,160,130,100);
		$membername=$_GET['membername'];
		$cond.=empty($membername)?"":"membername like '%{$membername}%'";

		$orderby=$_GET['orderby'];
		$orderby=empty($orderby)?"id":$orderby;
		$orderbystr='';
		if( in_array( $orderby, array('id','signuptime','logintime')) ){
			$orderbystr=$orderby.' desc';
		}
		
		$curPage = intval($_GET["page"]);
		$pager = new Pager();
		$pager->init(10,$curPage,"admin.php?inc=member&action=search&membername={$membername}&orderby={$orderby}&page={page}");
		$rows = $pager->queryRows($db,"members", $cond , "*",$orderbystr);
		$recstr=_LANG($_AL['all.totalrecords'], array($pager->recordNum));
		
echo <<<EOT
	<div class="div_clear" style="height:10px;"></div>
	<div class="tips_1">
	{$_AL['all.keyword']}: <input class="text_css" type="text" size="20" value="{$membername}" id="membername" /> <select id="orderby"><option value="id">{$_AL['all.orderby']}</option><option value="signuptime">{$_AL['member.signuptime']}</option><option value="logintime">{$_AL['member.lastlogin.time']}</option></select> <input class="button_css" type="button" value="  {$_AL['all.search']}  " onclick="searchmember()" />&nbsp;&nbsp;&nbsp;{$recstr}</div>
EOT;
	echo("<form id=\"membersform\" onsubmit=\"return false;\">");
	echo("<table class=\"table_1\" width=\"100%\">");
	echo("<tr style=\"font-weight:bold;color:#333333;\"><td class=\"row_0\"><div class=\"rowdiv_0\" style=\"width:{$dwidth[1]}px;\"><span class=\"warning\">{$_AL['all.delete']}</span></div><div class=\"rowdiv_0\" style=\"width:{$dwidth[2]}px;\">{$_AL['member.name']}/{$_AL['member.email']}</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[3]}px;\">{$_AL['member.realname']}/{$_AL['member.phone']}</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[4]}px;\">{$_AL['member.qq']}/{$_AL['member.msn']}</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[5]}px;\">{$_AL['member.signuptime']}/{$_AL['member.lastlogin.time']}</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[6]}px;\">{$_AL['member.signupip']}</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[7]}px;\">{$_AL['all.control']}</div></td></tr>");
	for($i=0;$i<count($rows);$i++){
		$row=$rows[$i];
		$row['signuptime']=getDateStr($row['signuptime']);
		$row['logintime']=empty($row['logintime'])?'--':getDateStr($row['logintime']);
		$row['membername']=htmlFilter($row['membername']);
		$row['realname']=empty($row['realname'])?'--':htmlFilter($row['realname']);
		$row['phone']=empty($row['phone'])?'--':htmlFilter($row['phone']);
		$row['qq']=empty($row['qq'])?'--':htmlFilter($row['qq']);
		$row['msn']=empty($row['msn'])?'--':htmlFilter($row['msn']);
		$checkboxstr="<input type=\"checkbox\" value=\"{$row['id']}\" name=\"deluid[]\" class=\"checkbox_css\" />";
		echo("<tr><td class=\"row_0\" style=\"line-height:150%;\"><div class='rowdiv_0' style='width:{$dwidth[1]}px;'>{$checkboxstr}</div><div class='rowdiv_0' style='width:{$dwidth[2]}px;'><b>{$row['membername']}</b><br />{$row['email']}</div><div class='rowdiv_0' style='width:{$dwidth[3]}px;'>{$row['realname']}<br />{$row['phone']}</div><div class='rowdiv_0' style='width:{$dwidth[4]}px;'>{$row['qq']}<br />{$row['msn']}</div><div class='rowdiv_0' style='width:{$dwidth[5]}px; font-size:11px;'>{$row['signuptime']}<br />{$row['logintime']}</div><div class='rowdiv_0' style='width:{$dwidth[6]}px;font-size:11px;'>{$row['signupip']}&nbsp;</div><div class='rowdiv_0' style='width:{$dwidth[7]}px;'><a href=\"admin.php?inc=member&action=editmember&uid={$row['id']}\">{$_AL['all.edit']}</a></div></td></tr>");				
	}
	echo("</table>");
	echo("</form>");
	echo("<table width=100%><tr><td><input type=\"checkbox\" onclick=\"selectAll('membersform',this.checked)\" class=\"checkbox_css\" /> {$_AL['all.selectall']} &nbsp;  &nbsp; <input class=\"button_css\" type=\"button\" value=\"  {$_AL['all.deletesel']}  \" onclick=\"ajax_delmembers_yn()\" /></td><td><div class='pagestrdiv'>{$pager->getPageStr()}</div></td></tr></table>");

 	
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
pt.createTab("t1","{$_AL['member.man.title']}","",true,"n");
//pt.createTab("t2","{$_AL['member.man.delbatch']}","",false,"n");
pt.init = function(){
	smallNowTab = pt.nowTab;
};
pt.onclick = function(){
	smallNowTab = pt.nowTab;
};		
pt.initTab();
pt.clickNowTab();

var orderby="{$orderby}";
setSelect("orderby",orderby);

function searchmember(){
	var loc = "admin.php?inc=member&action=search&membername="+E("membername").value+"&orderby="+E("orderby").value;
	self.location.href = loc;
}

function ajax_delmembers_yn(){
	var btns=[
		{value:" {$_AL['all.confirm']} ",onclick:"mainifm.ajax_delmembers()",focus:true},
		{value:" {$_AL['all.cancel']} ",onclick:"popwin.close()"}
	];
	popwin.showDialog(3,"{$_AL['all.confirm']}","{$_AL['member.del.warning']}",btns,320,130);
}

function ajax_delmembers(){
	popwin.loading();
	ajaxPost("membersform","member_ajax.php?action=delmembers",ajax_delmembers_callback);
}
function ajax_delmembers_callback(data){
	var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,"{$_AL['all.del.succeed']}","{$_AL['member.del.succeed']}",btns,280,130);
	}else{
		popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
	}
}

function PageInit(){
	if(E("membername")){E("membername").onkeyup = function(event){checkKeyPressEnter(event);};E("membername").focus();}
}

function checkKeyPressEnter(eventobject){
	var eve=eventobject||window.event;
	if(eve.keyCode==13) {
		searchmember();
	}
}
window.onload=PageInit;

</script>
EOT;
	break;
	/**************************************  manager/search END ************************************************/
	
	/************************************** settings BEGIN ************************************************/
	case "settings":
	$row=getSettings(0);
echo <<<EOT
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1">
		<form id="signupform1" onsubmit="return false;">
		<table class="table_1">
			<tr><td class="td_0">{$_AL['member.s.open']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="settings[issignup]" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="settings[issignup]" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2">{$_AL['member.s.open.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['member.s.signupfilename']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['signupfilename']}" name="settings[signupfilename]" id="signupfilename" class="text_css" /></td><td class="td_2">{$_AL['member.s.signupfilename.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['member.s.code']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="settings[signupsecuritycode]" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="settings[signupsecuritycode]" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2">{$_AL['member.s.code.remark']}</td></tr>	
			<tr><td class="td_0">{$_AL['member.s.verify']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><select name="settings[issignupverify]" id="settings[issignupverify]"><option value="0">{$_AL['member.s.verify.1']}</option><option value="1">{$_AL['member.s.verify.2']}</option><option value="2">{$_AL['member.s.verify.3']}</option></select></td><td class="td_2">{$_AL['member.s.verify.remark']}</td></tr>
		
			<tr><td class="td_0">{$_AL['member.s.iptime']}</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['signupitime']}" name="settings[signupitime]" id="signupitime" class="text_css" /></td><td class="td_2">{$_AL['member.s.iptime.remark']}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_savesigninfo1()" /></td><td class=""></td></tr>
		</table>
		</form>
	</div>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['member.s.title']}","",true,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	
	var issignup="{$row['issignup']}";
	var iswelcome="{$row['iswelcome']}";
	var issignupverify="{$row['issignupverify']}";
	var signupsecuritycode="{$row['signupsecuritycode']}";
	setRadioCheck("settings[issignup]",issignup);
	setRadioCheck("settings[iswelcome]",iswelcome);
	setSelect("settings[issignupverify]",issignupverify);
	setRadioCheck("settings[signupsecuritycode]",signupsecuritycode);
	function ajax_savesigninfo1(){
		popwin.loading();
		ajaxPost("signupform1","member_ajax.php?action=savesignupinfo",ajax_savesigninfo1_callback);
	}
	function ajax_savesigninfo1_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['member.s.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}
	</script>
EOT;

	break;
	/************************************** settings ENG ************************************************/



	/************************************** verify BEGIN ************************************************/
	case "verify":
	$dwidth=array(0,140,210,190,230,230);
	$rows=$db->row_select("members","groupid=".GROUP_NOVERIFY,0,"*","id");
echo <<<EOT
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1">
		<form id="verifyform" onsubmit="return false;">
		<div class="tips_1">{$_AL['member.s.tips']}</div>
		<div class="div_clear" style="height:10px;"></div>
		
EOT;
		echo("<table class=\"table_1\" width=\"100%\">");
		echo("<tr style=\"font-weight:bold;color:#333333;\"><td class=\"row_0\"><div class=\"rowdiv_0\" style=\"width:{$dwidth[1]}px;\">{$_AL['all.control']}</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[2]}px;\">{$_AL['member.name']}/{$_AL['member.email']}</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[3]}px;\">{$_AL['member.signupip']}/{$_AL['member.signuptime']}</div></td></tr>");
		for($i=0;$i<count($rows);$i++){
			$row=$rows[$i];
			$row['membername']=htmlFilter($row['membername']);
			$row['signuptime']=getDateStr($row['signuptime'],false);
			echo("<tr><td class=\"row_0\" style=\"line-height:180%;\"><div class='rowdiv_0' style='width:{$dwidth[1]}px;'><input type='radio' value='0' name='doaction[{$row['id']}]' class='radio_css' /> {$_AL['member.state0']} &nbsp; <input type='radio' value='1' name='doaction[{$row['id']}]' checked='true' class='radio_css' /> {$_AL['member.state1']}<br /><input type='radio' value='2' name='doaction[{$row['id']}]' class='radio_css' /> {$_AL['member.state2']} &nbsp; <input type='radio' value='3' name='doaction[{$row['id']}]' class='radio_css' /> {$_AL['member.state3']}<br /></div><div class='rowdiv_0' style='width:{$dwidth[2]}px;'><span style='color:#FF6600;'>{$row['membername']}</span><br />{$row['email']}</div><div class='rowdiv_0' style='width:{$dwidth[3]}px;'>{$row['signupip']}<br />{$row['signuptime']}</div></td></tr>");				
		}
		echo("<tr><td class=\"td_3\"><input class=\"button_css\" type=\"button\" value=\"  {$_AL['all.submit']}  \" onclick=\"ajax_verify()\" /></td></tr>");
		echo("</table>");
		
echo <<<EOT
		
	
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
pt.createTab("t1","{$_AL['member.verify']}","",true,"n");
pt.init = function(){
	smallNowTab = pt.nowTab;
};
pt.onclick = function(){
	smallNowTab = pt.nowTab;
};		
pt.initTab();
pt.clickNowTab();
function ajax_verify(){
	popwin.loading();
	ajaxPost("verifyform","member_ajax.php?action=verify",ajax_verify_callback);
}
function ajax_verify_callback(data){
	var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,"{$_AL['member.verify.succeed']}","{$_AL['member.verify.succeed.remark']}",btns,280,130);
	}else{
		popwin.showDialog(0,"{$_AL['member.verify.failed']}", data,btns,280,130);
	}
}
</script>

EOT;
	break;
	/************************************** verify END ************************************************/



}	
?>