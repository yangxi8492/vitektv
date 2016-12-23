<?php
require_once('../init.php');
require_once('../pager.php');
require_once getCacheFilePath('folders.php');
require_once('./../../'.ADMIN_DIR.'/language/language.php');
if(!isAdmin()){
	exit($_AL['all.notlogin']);
}

$folders = $cache_folders;

foreach($folders as $key=>$folder){
	$folder['title']=htmlFilter($folder['title']);
	$folder['updatetime']=getDateStr($folder['updatetime']);
	$folders[$key]=$folder;
}
print <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>Folder</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../getfiles.php?t=js&v={$_SYS['VERSION']}&f=util|ajax|admin"></script>
<body style="background:#fff;">
<table width="100%"><tr><td><a href="index.php?enter=1" target="_self"  title="{$_AL['folder.returnlist']}"><img src="images/btn_list.gif" border="0" /></a> <img src="images/btn_close.gif" id="btnClose" onclick="window.parent.popwin.close()" border="0" style="cursor:pointer;" /> </td></tr></table>
<form id="folderform" onsubmit="return false">
<!--#######################-->
<div class="condiv"><a href="javascript:showCreateFolder()">{$_AL['folder.new']}</a> | <input type="checkbox" onclick="selectAll('folderform',this.checked)" />{$_AL['all.selectall']} &nbsp;<a href="javascript:showDelFolder()">{$_AL['folder.delsel']}</a>
	<!--#######################-->
	<div id="createfolderdiv" style="display:none;">
		{$_AL['folder.pleaseinputname']}: <input type="text" value="" size="30" maxlength="50" id="newfoldername" name="newfoldername" class="text_css" /> <input type="button" value="  {$_AL['folder.create']}  " onclick="createFolder()" class="button_css" />
	</div>
	<!--#######################-->
	<div id="delfolderdiv" style="display:none;">
		<input type="radio" value="0" name="deltype" checked="true" /> {$_AL['folder.deldironly']} &nbsp;  <input type="radio" value="1" name="deltype" />{$_AL['folder.delfile']} &nbsp;  <input type="button" value="  {$_AL['folder.confirm.del']}  " onclick="delFolder()" class="button_css" />
	</div>
</div>
EOT;
foreach($folders as $folder){
if($folder['coverid']>0){
	$img="<img src=\"../../attachment.php?id={$folder['coverid']}\" />";
}else{
	$img="<img src=\"../../images/img_folder.gif\" />";
}
$disablestr=$folder['id']==1?'disabled=true':'';
print <<<EOT
<div class="folderitem" id="folder_{$folder['id']}" title="{$folder['title']}">
	<input type="checkbox" value="{$folder['id']}" name="ids[]" class="checkbox" {$disablestr} />
	<div class="folderitem_imgdiv"><a href="files.php?folderid={$folder['id']}">{$img}</a></div>
	<div class="folderitem_filename"><a href="files.php?folderid={$folder['id']}">{$folder['title']}</a></div>
</div>
EOT;
}

print <<<EOT
</form>
<div style='clear:both; height:15px; border-top:1px dotted #999999;'></div>
<script>
function showCreateFolder(){
	E("delfolderdiv").style.display="none";
	E("createfolderdiv").style.display=E("createfolderdiv").style.display==''?'none':'';
}
function createFolder(){
	var foldername=E("newfoldername").value;
	if(foldername==''){
		alert("{$_AL['folder.alert.inputname']}");
		return;
	}
	ajaxPost("folderform", "ajax.php?action=createFolder", createFolder_callback);
}

function createFolder_callback(data){
	if(isSucceed(data)){
		alert("{$_AL['folder.create.succeed']}");
		self.location.reload();
	}else{
		alert(data);
	}
}

function showDelFolder(){
	E("createfolderdiv").style.display="none";
	E("delfolderdiv").style.display=E("delfolderdiv").style.display==''?'none':'';
}

function delFolder(){
	ajaxPost("folderform", "ajax.php?action=delFolder", delFolder_callback);
}

function delFolder_callback(data){
	if(isSucceed(data)){
		alert("{$_AL['folder.del.succeed']}");
		self.location.reload();
	}else{
		alert(data);
	}
}
</script>
</body>
</html>
EOT;
?>