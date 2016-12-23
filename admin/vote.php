<?php
require_once('./../inc/pager.php');
$effect_lang=_LANG($_AL['all.set.effect'],array($cache_langs[$_SYS['alangid']]['name']));
switch($action){

	/************************************** add edit BEGIN ************************************************/
	case "add":
	case "edit":
	$id=intval($_GET['id']);
	if($action=="add"){
		$row=null;
		$scriptadd="addVoteItem();addVoteItem();addVoteItem();";
	}else{
		$row=$db->row_select_one("votes","id={$id}","*");
		$cid=intval($row['cid']);
		$row['starttime']=empty($row['starttime'])?"":getDateStr($row['starttime'],'dateonly',false);
		$row['stoptime']=empty($row['stoptime'])?"":getDateStr($row['stoptime'],'dateonly',false);
		$itemrows=$db->row_select("voteitems", "voteid={$id}", 0, "*","id");
		foreach($itemrows as $itemrow){
			$itemrow['title']=htmlFilter($itemrow['title']);
			$scriptadd.="addVoteItem('{$itemrow['title']}','{$itemrow['votednum']}');";
		}
	}
	


echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<form id="votesform">
	<div id="t1">
		<table class="table_1">
			<tr><td class="td_0" style="width:500px;">{$_AL['vote.title']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="hidden" name="doaction" id="doaction" value="{$action}" /><input type="hidden" name="id" id="id" value="{$id}" /><input type="text"  value="{$row['title']}" name="title" id="title"  class="text_css" style="width:380px;" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['vote.allowvotetime']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text"  value="{$row['starttime']}" name="starttime" id="starttime"  class="text_css" size="10" /> - <input type="text"  value="{$row['stoptime']}" name="stoptime" id="stoptime"  class="text_css" size="10" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['vote.items']}:</td><td class=""></td></tr>
			<tr><td class="td_1">
					<div id="votesdiv"></div>
					<div class="post_voteitem"><a href="javascript:addVoteItem('','')">{$_AL['vote.additem']}</a></div>
				</td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['vote.maxvote']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text"  value="{$row['maxvotes']}" name="maxvotes" id="maxvotes"  class="text_css" size="10" /></td><td class="td_2">{$_AL['vote.maxvote.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['vote.repeatvote']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><select name="level" id="level"><option value="0">{$_AL['vote.rep.level0']}</option><option value="1">{$_AL['vote.rep.level1']}</option><option value="2">{$_AL['vote.rep.level2']}</option></select></td><td class="td_2">{$_AL['vote.rep.remark']}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_postvote()" /></td><td class=""></td></tr>
		</table>
	</div>
	<div id="t2">
	</div>
	<div id="t3"></div>
	</form>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var hasErr=false;
	var smallNowTab;
	var pt = new Tabs();
	var doaction="{$action}";
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	if(doaction=="add"){
		pt.createTab("t1","{$_AL['vote.add']}","",true,"n");
	}else{
		pt.createTab("t1","{$_AL['vote.edit']}","",true,"n");
	}
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	

	var voteNum=0;
	function addVoteItem(title,votednum){
		title=title==undefined?'':title;
		votednum=votednum==undefined?'':votednum;
		var items=document.getElementsByName('voteitem[]');
		if(items.length>=10){
			return;
		}
		var s="<div class=post_voteitem><input type='text' style='width:400px;' name='voteitem[]' tabindex='100"+voteNum+"' class='text_css' value='"+title+"' /> <input type='text' style='width:40px;' name='votednum[]' tabindex='200"+voteNum+"' class='text_css' value='"+votednum+"' /> {$_AL['vote.p']} <a href=\"javascript:delVoteItem("+voteNum+")\" title=\"{$_AL['all.delete']}\">X</a></div>";
		var ele=document.createElement('div');
		ele.id="vote_"+voteNum;
		ele.innerHTML=s;
		E("votesdiv").appendChild(ele);
		voteNum++;
		
	}
	function delVoteItem(index){
		E("vote_"+index+"").innerHTML ="";
	}
	
	function checkAllAction(){
		hasErr=false;
		checkTitle();
		if(hasErr)return false;
		checkVotetime();
		if(hasErr)return false;
		return true;
	}

	function checkTitle(){
		var v = E("title").value;
		if(v==""||getLength(v)<2||getLength(v)>64){
			hasErr = true;
			var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.E('title').focus();",focus:true}];
			popwin.showDialog(2,"{$_AL['vote.title.err']}","{$_AL['vote.title.lengtherr']}",btns,320,130);
		}else{
			
		}
	}

	function checkVotetime(){
		var v1 = E("starttime").value;
		var v2 = E("stoptime").value;
		if(v1==""||v2==""){
			hasErr = true;
			var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.E('starttime').focus();",focus:true}];
			popwin.showDialog(2,"{$_AL['vote.time.err']}","{$_AL['vote.time.input']}",btns,320,130);
		}else{
			
		}
	}

	function ajax_postvote(){
		if(checkAllAction()){
			popwin.loading();
			ajaxPost("votesform","vote_ajax.php?action=savevotes",ajax_postvote_callback);
		}
	}
	function ajax_postvote_callback(data){
		var btns;
		popwin.loaded();
		if(isSucceed(data)){
			btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.href='admin.php?inc=vote&action=list';",focus:true}];
			popwin.showDialog(1,"{$_AL['vote.post.succeed.title']}","{$_AL['vote.post.succeed']}",btns,280,130);
		}else{
			btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close()",focus:true}];
			popwin.showDialog(0,"{$_AL['all.con.failed']}","{$_AL['all.con.failed']}:<br />"+data,btns,280,130);
		}
	}

	function InitPage(){
		E("starttime").onfocus = function(){choosedate.dfd(E('starttime'))};
		E("stoptime").onfocus = function(){choosedate.dfd(E('stoptime'))};
		{$scriptadd}

		var level="{$row['level']}";
		setSelect("level",level);
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
	 	$dwidth=array(0,35,200,300,100,150,60,80,150);
		$cond="langid={$_SYS['alangid']}";
		$keyword=trim($_GET['k']);
		$orderbystr='ordernum,id desc';
		$keyword=str_replace("*","%",$keyword);
		if(!empty($keyword)){
			$cond.=" and (title like '%{$keyword}%' or  content like '%{$keyword}%')";
		}
		$curPage = intval($_GET["page"]);
		$pager = new Pager();
		$pager->init(15,$curPage,"admin.php?inc=vote&action=list&k={$keyword}&page={page}");
		$rows = $pager->queryRows($db,"votes", $cond , "*",$orderbystr);
		$recstr=_LANG($_AL['all.totalrecords'], array($pager->recordNum));
		
echo <<<EOT
	<div class="div_clear" style="height:10px;"></div>
	<div class="tips_1">
{$_AL['all.keyword']}: <input class="text_css" type="text" size="20" value="{$keyword}" id="keyword" />
<input class="button_css" type="button" value="  {$_AL['all.search']}  " onclick="searchvotes()" />
&nbsp;&nbsp;&nbsp;{$recstr}</div>
	<table class="table_1" width="100%">
		<tr><td class="td_6"><a class="td_5_1a" href="admin.php?inc=vote&action=add"><img src="images/ico_add.gif" border="0" /> {$_AL['vote.add']}</a></td></tr>
	</table>

EOT;
	echo("<form id=\"votesform\" onsubmit=\"return false;\">");
	echo("<table class=\"table_1\" width=\"100%\">");
	echo("<tr style=\"font-weight:bold;color:#333333;\"><td class=\"row_0\">".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[6]}px;\">{$_AL['vote.order']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[2]}px;\">{$_AL['vote.subject']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[3]}px;\">{$_AL['vote.allowvotetime']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[4]}px;\">{$_AL['vote.totalnum']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[5]}px;\">{$_AL['all.control']}</div>".
		"");
	for($i=0;$i<count($rows);$i++){
		$tabindex=$i;
		$row=$rows[$i];
		$row['starttime']=getDateStr($row['starttime']);
		$row['stoptime']=getDateStr($row['stoptime']);
		$row['title']=htmlFilter($row['title']);
		$checkboxstr="<input type=\"checkbox\" value=\"{$row['id']}\" name=\"ids[]\" class=\"checkbox_css\" />";

		echo("<tr><td class=\"row_0\" style=\"line-height:150%;\">".
			"<div class='rowdiv_0' style='width:{$dwidth[6]}px;'><input type=\"text\" size=\"2\" value=\"{$row[ordernum]}\" name=\"ordernum[{$row['id']}]\" tabIndex=\"{$tabindex}\" class=\"text_css\" /></div>".
			"<div class='rowdiv_0' style='width:{$dwidth[2]}px;'><a href=\"../vote.php?id={$row['id']}\" target=\"_blank\">{$row['title']}</a>&nbsp;</div>".
			"<div class='rowdiv_0' style='width:{$dwidth[3]}px;'><span class='time'>{$row['starttime']}</span> ~ <span class='time'>{$row['stoptime']}</span> </div>".
			"<div class='rowdiv_0' style='width:{$dwidth[4]}px;'><span class='time'>{$row['votednum']}</span></div>".
			"<div class='rowdiv_0' style='width:{$dwidth[5]}px;'><a href=\"admin.php?inc=vote&action=edit&id={$row['id']}\">{$_AL['all.edit']}</a>&nbsp;&nbsp;&nbsp;<a href=\"javascript:ajax_delvote_yn({$row['id']})\">{$_AL['all.delete']}</a>&nbsp;</div>".
			"");				
	}
	echo("</table>");
echo <<<EOT
	<table class="table_1" width="100%">
		<tr><td class="td_6"><a class="td_5_1a" href="admin.php?inc=vote&action=add"><img src="images/ico_add.gif" border="0" /> {$_AL['vote.add']}</a></td></tr>
	</table>
	<table width=100%><tr><td><input type="button" class="button_css" value="  {$_AL['all.submit']}  " onclick="ajax_dovotes()" /></td><td><div class='pagestrdiv'>{$pager->getPageStr()}</div></td></tr></table>
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
pt.createTab("t1","{$_AL['vote.list']}","",true,"n");
pt.init = function(){
	smallNowTab = pt.nowTab;
};
pt.onclick = function(){
	smallNowTab = pt.nowTab;
};		
pt.initTab();
pt.clickNowTab();

function ajax_delvote_yn(id){
	var btns=[
		{value:" {$_AL['all.confirm']} ",onclick:"mainifm.ajax_delvote("+id+")",focus:true},
		{value:" {$_AL['all.cancel']} ",onclick:"popwin.close()"}
	];
	popwin.showDialog(3,"{$_AL['all.confirm']}","{$_AL['vote.del.warning']}",btns,320,130);
}	
	
function ajax_delvote(id){
	popwin.loading();
	ajaxGet("vote_ajax.php?action=delvote&id="+id, ajax_delvote_callback);
}
function ajax_delvote_callback(data){
	var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,"{$_AL['all.del.succeed']}","{$_AL['vote.del.succeed']}",btns,320,130);
	}else{
		popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,320,130);
	}
}

function ajax_dovotes(){
	popwin.loading();
	ajaxPost("votesform","vote_ajax.php?action=dovotes",ajax_dovotes_callback);
}
function ajax_dovotes_callback(data){
	var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,"{$_AL['all.con.succeed']}","{$_AL['vote.order.succeed']}",btns,280,130);
	}else{
		popwin.showDialog(0,"{$_AL['all.con.failed']}","{$_AL['all.con.failed']}:<br />"+data,btns,280,130);
	}
}

function searchvotes(){
	var loc = "admin.php?inc=vote&action=list&k="+urlEncode(E("keyword").value);
	reloadSelf(loc);
}

function PageInit(){
	if(E("keyword")){E("keyword").onkeyup = function(event){checkKeyPressEnter(event);};}
}
function checkKeyPressEnter(eventobject){
	var eve=eventobject||window.event;
	if(eve.keyCode==13) {
		searchvotes();
	}
}
window.onload=PageInit;
</script>
EOT;
	break;
	/**************************************  list END ************************************************/

}	
?>