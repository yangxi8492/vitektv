<?php
require_once('./../inc/pager.php');
$effect_lang=_LANG($_AL['all.set.effect'],array($cache_langs[$_SYS['alangid']]['name']));
switch($action){

	/************************************** add edit BEGIN ************************************************/
	case "reply":
	$id=intval($_GET['id']);
	$row=$db->row_select_one("msgs","id={$id}","*");
	echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<form id="articleform" method="POST" onsubmit="return checkAllAction()" action="msg_ajax.php?action=savemsg">
	<div id="t1">
		<table class="table_1">
			<tr><td class="td_0" style="width:680px;">{$_AL['msg.user']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="hidden" name="id" id="id" value="{$id}" /><input type="text"  value="{$row['name']}" name="name" id="name"  class="text_css" style="width:380px;" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['msg.email']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['email']}" name="email" id="email" class="text_css" style="width:380px;" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['msg.contact']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['contact1']}" name="contact1" id="contact1" class="text_css" style="width:380px;" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['msg.title']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['title']}" name="title" id="title" class="text_css" style="width:380px;" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['msg.content']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:150px;width:660px;" name="remark" id="remark">{$row['remark']}</textarea></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['msg.reply']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:150px;width:660px;" name="reply" id="reply">{$row['reply']}</textarea></td><td class="td_2">{$_AL['msg.reply.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['msg.verify']}:</td><td class=""></td></tr>
			<tr><td class="td_1">				
				<input type="radio" value="0" name="state" class="radio_css" /> {$_AL['msg.verify.1']} &nbsp; <input type="radio" value="1" name="state" class="radio_css" /> {$_AL['msg.verify.2']}
			</td><td class="td_2"></td></tr>
			<tr><td class="td_3"><input class="button_css" type="submit" value="  {$_AL['all.submit']}  " /></td><td class=""></td></tr>
		</table>
	</div>
	<div id="t2"></div>
	<div id="t3"></div>
	</form>
	<div class="div_clear" style="height:30px;"></div>
	<script>
		KE.show({id : 'reply'});
	</script>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	var doaction="{$action}";
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['msg.edit.title']}","",true,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	
	var state="{$row['state']}";
	setRadioCheck("state",state);

	function checkAllAction(){
		return true;
	}

	function InitPage(){
	}
	
	window.onload = InitPage;
	
	</script>
EOT;

	
	break;
	/************************************** add edit BEGIN ************************************************/
	
	/************************************** list BEGIN ************************************************/
	case "list":
echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div id="t1">
EOT;
	{
	 	$dwidth=array(0,30,300,180,300,80,100);
		$cond="langid={$_SYS['alangid']}";
		$keyword=trim($_GET['k']);
		$state=$_GET['state'];
		$orderby="id desc";
		$orderbystr=$orderby;
		$keyword=str_replace("*","%",$keyword);
		(!empty($keyword)) && $cond.=" and (name like '%{$keyword}%' or title like '%{$keyword}%' or remark like '%{$keyword}%' or replier like '%{$keyword}%' or reply like '%{$keyword}%')";
		$state=='y' && $cond.=" and state=1";
		$state=='n' && $cond.=" and state=0";

		$curPage = intval($_GET["page"]);
		$pager = new Pager();
		$pager->init(10,$curPage,"admin.php?inc=msg&action=list&k={$keyword}&state={$state}&page={page}");
		$rows = $pager->queryRows($db,"msgs", $cond , "*",$orderbystr);
		$recstr=_LANG($_AL['all.totalrecords'], array($pager->recordNum));
		
echo <<<EOT
	<div class="div_clear" style="height:10px;"></div>
	<div class="tips_1">
{$_AL['all.keyword']}: <input class="text_css" type="text" size="20" value="{$keyword}" id="keyword" /> <select id="state"><option value="all">{$_AL['msg.cond0']}</option><option value="y">{$_AL['msg.cond1']}</option><option value="n">{$_AL['msg.cond2']}</option></select> <input class="button_css" type="button" value="  {$_AL['all.search']}  " onclick="searchmsg()" />
&nbsp;&nbsp;&nbsp;{$recstr}</div>
EOT;
	echo("<form id=\"msgsform\" onsubmit=\"return false;\">");
	echo("<table class=\"table_1\" width=\"100%\">");
	echo("<tr style=\"font-weight:bold;color:#333333;\"><td class=\"row_0\">".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[1]}px;\">{$_AL['all.select']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[2]}px;padding:0px 10px;\">{$_AL['msg.title']}/{$_AL['msg.user']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[3]}px;\">{$_AL['msg.email']}/{$_AL['msg.contact']}/{$_AL['msg.ip']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[4]}px;padding:0px 10px;\">{$_AL['msg.lastreply']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[5]}px;\">&nbsp;{$_AL['msg.verify.state']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[6]}px;\">{$_AL['all.control']}</div>".
		"");

	for($i=0;$i<count($rows);$i++){
		$row=$rows[$i];
		$row['posttime']=$row['posttime']==0?"——":getDateStr($row['posttime']);
		$row['replytime']=$row['replytime']==0?"——":getDateStr($row['replytime']);
		$row['name']=htmlFilter($row['name']);
		$row['contact1']=htmlFilter($row['contact1']);
		$row['email']=htmlFilter($row['email']);
		$row['title']=htmlFilter($row['title']);
		$row['replier']=htmlFilter($row['replier']);
		$row['remark']=cutStr(strip_tags(str_replace(array("\r", "\n"), array('', ''), $row['remark'])),30);
		$row['reply']=cutStr(strip_tags(str_replace(array("\r", "\n"), array('', ''), $row['reply'])),30);
		$statestr=intval($row['state'])==0?"<a href='msg_ajax.php?action=verify&state=1&id={$row['id']}' class='def_no' title=\"{$_AL['msg.click2verify']}\">{$_AL['msg.notverify']}</a>":"<a href='msg_ajax.php?action=verify&state=0&id={$row['id']}' class='def_yes' title='{$_AL['msg.click2notverify']}'>{$_AL['msg.hadverify']}</a>";
		$checkboxstr="<input type=\"checkbox\" value=\"{$row['id']}\" name=\"ids[]\" class=\"checkbox_css\" />";

		echo("<tr><td class=\"row_0\" style=\"line-height:150%;\">".
			"<div class='rowdiv_0' style='width:{$dwidth[1]}px;'>{$checkboxstr}</div>".
			"<div class='rowdiv_0' style='width:{$dwidth[2]}px; padding:0px 10px;'>{$row['title']}<br /><b>{$row['name']}</b> <span class='time'>{$row['posttime']}</span></div>".
			"<div class='rowdiv_0' style='width:{$dwidth[3]}px;'>{$row['email']}<br />{$row['contact1']}<br /><span class='time'>{$row['ip']}</span></div>".
			"<div class='rowdiv_0' style='width:{$dwidth[4]}px; padding:0px 10px;'>{$row['reply']}<br /><b>{$row['replier']}</b> <span class='time'>{$row['replytime']}</span></div>".
			"<div class='rowdiv_0' style='width:{$dwidth[5]}px;'>{$statestr}</div>".				
			"<div class='rowdiv_0' style='width:{$dwidth[6]}px;'><a href=\"admin.php?inc=msg&action=reply&id={$row['id']}\">{$_AL['msg.reply']}</a></div>".
			"");				
	}
	echo("</table>");
echo <<<EOT
	<table width=100%><tr><td><input type="checkbox" onclick="selectAll('msgsform',this.checked)" class="checkbox_css" /> {$_AL['all.selectall']} &nbsp;&nbsp;<select id="postaction" name="postaction">
		<option value="NOTHING">{$_AL['all.chooseaction']}</option>
		<option value="verifyY">{$_AL['msg.action1']}</option>
		<option value="verifyN">{$_AL['msg.action2']}</option>
		<option value="delMsg">{$_AL['msg.action3']}</option>
	</select>
	 <input type="button" class="button_css" value="  {$_AL['all.submit']}  " onclick="ajax_domsgs_yn()" /></td><td><div class='pagestrdiv'>{$pager->getPageStr()}</div></td></tr></table>
	<div class="div_clear" style="height:10px;"></div>
EOT;
	echo("</form>");
 	}
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
pt.createTab("t1","{$_AL['msg.list.title']}","",true,"n");
pt.init = function(){
	smallNowTab = pt.nowTab;
};
pt.onclick = function(){
	smallNowTab = pt.nowTab;
};		
pt.initTab();
pt.clickNowTab();

var state="{$state}";
setSelect("state",state);

function ajax_domsgs_yn(){
	if(E("postaction").value=="NOTHING"){
		var btns=[{value:" {$_AL['all.confirm']} ",onclick:"popwin.close()",focus:true}];
		popwin.showDialog(2,"{$_AL['all.tips']}","{$_AL['msg.pleasechoose']}",btns,320,130);
		return;
	}
	var btns=[
		{value:" {$_AL['all.confirm']} ",onclick:"mainifm.ajax_domsgs()",focus:true},
		{value:" {$_AL['all.cancel']} ",onclick:"popwin.close()"}
	];
	popwin.showDialog(3,"{$_AL['all.confirm']}","{$_AL['msg.action.tips']}",btns,320,130);
}


function ajax_domsgs(){
	popwin.loading();
	ajaxPost("msgsform","msg_ajax.php?action=domsgs",ajax_domsgs_callback);
}
function ajax_domsgs_callback(data){
	var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,"{$_AL['all.con.succeed']}","{$_AL['msg.batch.succeed']}",btns,280,130);
	}else{
		popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
	}
}

function searchmsg(){
	var loc = "admin.php?inc=msg&action=list&k="+urlEncode(E("keyword").value)+"&state="+(E("state").value);
	reloadSelf(loc);
}

function PageInit(){
	if(E("keyword")){E("keyword").onkeyup = function(event){checkKeyPressEnter(event);};}
}
function checkKeyPressEnter(eventobject){
	var eve=eventobject||window.event;
	if(eve.keyCode==13) {
		searchmsg();
	}
}
window.onload=PageInit;
</script>
EOT;
	break;
	/**************************************  list END ************************************************/
}	
?>