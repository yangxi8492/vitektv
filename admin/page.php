<?php
require_once('../inc/pager.php');
$effect_lang=_LANG($_AL['all.set.effect'],array($cache_langs[$_SYS['alangid']]['name']));
switch($action){

	/************************************** editpage BEGIN ************************************************/
	case "editpage":
	$channelid=intval($_GET['channelid']);
	$row=$db->row_select_one("channels","id={$channelid}");
	
echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<form id="articleform" method="POST" onsubmit="return checkAllAction()" action="page_ajax.php?action=savepage">
	<div id="t1">
		<table class="table_1">
			<tr><td class="td_0">{$_AL['page.content']}:</td><td class=""><input type="hidden" name="channelid" id="channelid" value="{$channelid}" /></td></tr>
			<tr><td class="td_1" style='width:800px;'>
				<textarea name="content" id="content" style="width: 800px; height: 400px;">{$row['content']}</textarea>
			</td><td class="td_2"></td></tr>
			<tr><td class="td_3"><input class="button_css" type="submit" value="  {$_AL['all.submit']}  " /></td><td class=""></td></tr>
		</table>
	</div>

	<div id="t2"></div>
	<div id="t3"></div>
	</form>
	<div class="div_clear" style="height:30px;"></div>
	<script>
		KE.show({id : 'content'});
	</script>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['page.tab.title']}","",true,"n");
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
			KE.insertHtml('content','<img src="attachment.php?id='+fileid+'" />');
		}else{
			KE.insertHtml('content','[file='+fileid+']'+filename+'[/file]');
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
	/************************************** editpage END ************************************************/
}
?>