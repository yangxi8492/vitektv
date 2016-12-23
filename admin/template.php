<?php
require_once('./../inc/pager.php');
require_once('./../inc/xml2.php');
$cache_templatevars=array();

$effect_lang=_LANG($_AL['all.set.effect'],array($cache_langs[$_SYS['alangid']]['name']));
echo("<script>var links={}; ".
"links.t1='admin.php?inc=template&action=list';".
"links.t2='admin.php?inc=template&action=varlist';".
"</script>");
switch($action){
	/************************************** list BEGIN ************************************************/
	case "list":
	$row=getSettings();
	$dwidth=array(0,120,220,150,120,190);
	$lang_item=$cache_langs[$_SYS['alangid']];
echo <<<EOT
	<style>.rowdiv_0{line-height:200%;}</style>
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1">
		<form id="templatesform" onsubmit="return false;">
	{$_AL['template.ad']}
		<div class="tips_1">{$_AL['template.tips']}</div>
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1" width="100%">
			<tr style="font-weight:bold;color:#333333;"><td class="td_6"><div class="rowdiv_0" style="width:{$dwidth[1]}px;">{$_AL['template.preview']}</div><div class="rowdiv_0" style="width:{$dwidth[2]}px;">{$_AL['template.name.dir']}</div><div class="rowdiv_0" style="width:{$dwidth[4]}px;">{$_AL['template.use']}</div><div class="rowdiv_0" style="width:{$dwidth[5]}px;"><img src="../language/{$lang_item['directory']}/flag.gif" /> {$lang_item['name']}{$_AL['template.langpack']}</div><div class="rowdiv_0" style="width:{$dwidth[3]}px;">{$_AL['template.author']}</div></td></tr>
		
EOT;
		$narray = array();
		$dir = '../template';
		$templatedir = dir($dir);
		$xml = new xmlParser();   
  		$defaultstyle="";
		$tmporder=array();
		while($entry = $templatedir->read()) {
			$tpldir = realpath($dir.'/'.$entry);
			if(!in_array($entry, array('.', '..')) && is_dir($tpldir)) {
				$tmporder[$entry]='0';
			}
		}

		foreach($tmporder as $entry=>$order) {
			$tpldir = realpath($dir.'/'.$entry);
			if(!in_array($entry, array('.', '..')) && is_dir($tpldir)) {
				$config=array();
				$xmlstr=@file_get_contents("{$dir}/{$entry}/config.xml");
				if($xmlstr){
					$config= $xml->xml2array($xmlstr);
				}
				$tempname=$config['name'];
				$defico="<input type='radio' name='tdefault' value='{$entry}' ".($cache_settings['template']==$entry?'checked=true':'')." /> {$_AL['template.use']}";
				
				//lang
				$langselect='';
				$langdir = realpath($dir.'/'.$entry.'/language');
				if(is_dir($langdir)){
					$f_langdir = dir($langdir);
					$langselect='';
					while($lang_entry = $f_langdir->read()) {
						if(!in_array($lang_entry, array('.', '..'))){
							$langselect.="<option value='{$lang_entry}'>{$lang_entry}</option>";
						}
					}
					$langselect="<select name='tlang[{$entry}]' id='tlang[{$entry}]' style='width:140px;'>{$langselect}</select>";
				}

				echo("<tr><td class=\"row_0\" style=\"line-height:150%;\">".
				"<div class='rowdiv_0' style='width:{$dwidth[1]}px;'><a href='../index.php?styleid={$entry}&preview=1' target='_blank' title=\"{$_AL['template.preview.index']}\"><img src='{$dir}/{$entry}/{$config['preview']}' border='0' /></a>&nbsp;</div>".
				"<div class='rowdiv_0' style='width:{$dwidth[2]}px;'><b>{$tempname}</b><br /><img src='images/folder.gif' /> template/{$entry}</div>".
				"<div class='rowdiv_0' style='width:{$dwidth[4]}px;'>{$defico}</div>".
				"<div class='rowdiv_0' style='width:{$dwidth[5]}px;'>{$langselect}&nbsp;</div>".
				"<div class='rowdiv_0' style='width:{$dwidth[3]}px;'>{$config['author']}</div>".
				"</td></tr>");
			}
		}
			
echo <<<EOT
		</table>
EOT;
if(!empty($tmporder)){
echo <<<EOT
		<table class="table_1" width="100%">
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_templates()" /></td></tr>
		</table>
EOT;
}
echo <<<EOT
		</form>
	</div>
	<div id="t2"></div>
	<div id="t3"></div>
	<div class="div_clear" style="height:30px;"></div>
<script>
var curInput;
var hasErr=false;
var smallNowTab;
var pt = new Tabs();
pt.classpre="smalltab_";
pt.container = "smalltab_container";
pt.createTab("t1","{$_AL['template.tab.title']}","",true,"n");
pt.createTab("t2","{$_AL['template.var']}","",false,"n");
pt.init = function(){
	smallNowTab = pt.nowTab;
};
pt.onclick = function(){
	smallNowTab = pt.nowTab;
	smallNowTab = pt.nowTab;
	if(smallNowTab=='t1'){return;}
	eval("self.location.href=links."+smallNowTab+";");

};		
pt.initTab();
pt.clickNowTab();

setRadioValue("tdefault","{$row['template']}");
setSelect("tlang[{$row['template']}]","{$row['templatelang']}");

function ajax_templates(){
	popwin.loading();
	ajaxPost("templatesform","template_ajax.php?action=settemplate",ajax_templates_callback);
}
function ajax_templates_callback(data){
	var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,"{$_AL['all.edit.succeed']}","{$_AL['template.set.succeed']}",btns,280,130);
	}else{
		popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
	}
}

</script>

EOT;
	break;
	/************************************** list END ************************************************/

	/************************************** varlist BEGIN ************************************************/
	case "varlist":
echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div id="t1"></div>
	<div id="t2">
EOT;
	{
	 	$dwidth=array(0,35,300,300,100,130,80,80,150);
		$cond="langid={$_SYS['alangid']}";
		$keyword=$_GET['k'];
		$keyword=str_replace("*","%",$keyword);
		if(!empty($keyword)){
			$cond.=" and (tkey like '%{$keyword}%' or  tvalue like '%{$keyword}%')";
		}
		$curPage = intval($_GET["page"]);
		$pager = new Pager();
		$pager->init(10,$curPage,"admin.php?inc=template&action=varlist&k={$keyword}&page={page}");
		$rows = $pager->queryRows($db,"templatevars", $cond , "*","tkey");
		
echo <<<EOT
	<div class="div_clear" style="height:10px;"></div>
	<div class="tips_1">
{$_AL['all.keyword']}: <input class="text_css" type="text" size="20" value="{$keyword}" id="keyword" />
<input class="button_css" type="button" value="  {$_AL['all.search']}  " onclick="searchvar()" />
</div>
	<table class="table_1" width="100%">
		<tr><td class="td_6"><a class="td_5_1a" href="admin.php?inc=template&action=addvar"><img src="images/ico_add.gif" border="0" /> {$_AL['template.addvar']}</a></td></tr>
	</table>

EOT;
	echo("<form id=\"varform\" onsubmit=\"return false;\">");
	echo("<table class=\"table_1\" width=\"100%\">");
	echo("<tr style=\"font-weight:bold;color:#333333;\"><td class=\"row_0\">".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[1]}px;\">{$_AL['all.select']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[2]}px;\">{$_AL['template.varname']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[3]}px;\">{$_AL['template.vardesc']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[4]}px;\">{$_AL['all.control']}</div>".
		"");

	for($i=0;$i<count($rows);$i++){
		$row=$rows[$i];
		$checkboxstr="<input type=\"checkbox\" value=\"{$row['id']}\" name=\"vids[]\" class=\"checkbox_css\" />";
		echo("<tr><td class=\"row_0\">".
			"<div class='rowdiv_0' style='width:{$dwidth[1]}px;'>{$checkboxstr}</div>".
			"<div class='rowdiv_0' style='width:{$dwidth[2]}px;'>{$row['tkey']}</a>&nbsp;</div>".
			"<div class='rowdiv_0' style='width:{$dwidth[3]}px;'>{$row['tdesc']}</a>&nbsp;</div>".
			"<div class='rowdiv_0' style='width:{$dwidth[4]}px;'><a href=\"admin.php?inc=template&action=editvar&id={$row['id']}\">{$_AL['all.edit']}</a>&nbsp;</div>".
			"");				
	}
	echo("</table>");
	echo("<table width=100%><tr><td><input type=\"checkbox\" onclick=\"selectAll('varform',this.checked)\" class=\"checkbox_css\" /> {$_AL['all.selectall']} &nbsp; <input type=\"button\" class=\"button_css\" value=\"  {$_AL['all.deletesel']}  \" onclick=\"ajax_dotemplatevars_yn()\" /></td> <td><div class='pagestrdiv'>{$pager->getPageStr()}</div></td></tr></table>");
echo <<<EOT
	<table width="100%">
		<tr><td>
	 </td></tr>
	</table>	
EOT;
	echo("</form>");

 	}
echo <<<EOT
	</div>
	<div id="t3"></div>
	<div class="div_clear" style="height:30px;"></div>
<script>
var smallNowTab;
var pt = new Tabs();
pt.classpre="smalltab_";
pt.container = "smalltab_container";
pt.createTab("t1","{$_AL['template.tab.title']}","",false,"n");
pt.createTab("t2","{$_AL['template.var']}","",true,"n");
pt.init = function(){
	smallNowTab = pt.nowTab;
};
pt.onclick = function(){
	smallNowTab = pt.nowTab;
	smallNowTab = pt.nowTab;
	if(smallNowTab=='t2'){return;}
	eval("self.location.href=links."+smallNowTab+";");

};		
pt.initTab();
pt.clickNowTab();

function ajax_dotemplatevars_yn(){
	var btns=[
		{value:" {$_AL['all.confirm']} ",onclick:"mainifm.ajax_dotemplatevars()",focus:true},
		{value:" {$_AL['all.cancel']} ",onclick:"popwin.close()"}
	];
	popwin.showDialog(3,"{$_AL['all.confirm']}","{$_AL['template.delconfirm']}",btns,320,130);
}


function ajax_dotemplatevars(){
	popwin.loading();
	ajaxPost("varform","template_ajax.php?action=dotemplatevars",ajax_dotemplatevars_callback);
}
function ajax_dotemplatevars_callback(data){
	var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,"{$_AL['all.con.succeed']}","{$_AL['template.delsucceed']}",btns,280,130);
	}else{
		popwin.showDialog(0,"{$_AL['all.con.failed']}","{$_AL['all.con.failed']}:<br />"+data,btns,280,130);
	}
}

function searchvar(){
	var loc = "admin.php?inc=template&action=varlist&k="+urlEncode(E("keyword").value);
	self.location.href = loc;
}

function PageInit(){
	if(E("keyword")){E("keyword").onkeyup = function(event){checkKeyPressEnter(event);};}
	var cid="{$cid}";
	var orderby="{$orderby}";
	var type="{$type}";
	setSelect("cid",cid);
	setSelect("orderby",orderby);
	setSelect("type",type);
}
function checkKeyPressEnter(eventobject){
	var eve=eventobject||window.event;
	if(eve.keyCode==13) {
		searchvar();
	}
}
window.onload=PageInit;
</script>
EOT;
	break;
	/**************************************  varlist END ************************************************/

	/************************************** addvar editvar BEGIN ************************************************/
	case "addvar":
	case "editvar":
	$id=intval($_GET['id']);
	if($action=="addvar"){
		$row=null;
	}else{
		$row=$db->row_select_one("templatevars","id={$id}","*");
	}
	
echo <<<EOT
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<form id="varform" method="POST" onsubmit="return checkAllAction()" action="template_ajax.php?action=savevars">
	<div id="t1">
		<table class="table_1">
			<tr><td class="td_0" style="width:400px;">{$_AL['template.varname']}:<span class="required">(*)</span></td><td class=""></td></tr>
			<tr><td class="td_1"><input type="hidden" name="doaction" id="doaction" value="{$action}" /><input type="hidden" name="id" id="id" value="{$id}" /><input type="text"  value="{$row['tkey']}" name="tkey" id="tkey"  class="text_css" style="width:380px;" /></td><td class="td_2"> {$_AL['template.varname.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['template.vardesc']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['tdesc']}" name="tdesc" id="tdesc" class="text_css" style="width:380px;" /></td><td class="td_2"></td></tr>
		</table>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['template.varvalue']}:</td><td class=""></td></tr>
			<tr><td class="td_1" style='width:800px;'>
				<textarea name="tvalue" id="tvalue" style="width: 780px; height: 400px;">{$row['tvalue']}</textarea>
			</td><td class="td_2"></td></tr>
			<tr><td class="td_3"><input class="button_css" type="submit" value="  {$_AL['all.submit']}  " /></td><td class=""></td></tr>
		</table>
	</div>
	<div id="t2">
	</div>
	<div id="t3"></div>
	</form>
	<div class="div_clear" style="height:30px;"></div>
	<script>
		KE.show({id : 'tvalue'});
	</script>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	var doaction="{$action}";
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	if(doaction=="add"){
		pt.createTab("t1","{$_AL['template.var.tab1']}","",true,"n");
	}else{
		pt.createTab("t1","{$_AL['template.var.tab2']}","",true,"n");
	}
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();

	function openUploadAttach(handle1,handle2){
		window.handle1=handle1; window.handle2=handle2;
		popwin.showURL("{$_AL['page.upfile']}",'../inc/attachment/index.php',800,500);
	}

	function insertAttachment(fileid, filename, isimg){
		isimg=parseInt(isimg);
		if(isimg==1&&isImg(filename)){
			KE.insertHtml('tvalue','<img src="attachment.php?id='+fileid+'" />');
		}else{
			KE.insertHtml('tvalue','[file='+fileid+']'+filename+'[/file]');
		}
		popwin.close();
	}
		
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

}	
?>