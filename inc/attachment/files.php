<?php
require_once('../init.php');
require_once('../pager.php');
require_once getCacheFilePath('folders.php');
require_once('./../../'.ADMIN_DIR.'/language/language.php');
if(!isAdmin()){
	exit($_AL['all.notlogin']);
}

$curPage = intval($_GET["page"]);
$folderid = intval($_GET["folderid"]);
if($folderid>0){
	setCookies("lastfolderid",$folderid);
}else{
	$folderid = intval(getCookies("lastfolderid"));
}

//preview
setCookies("lastfoldertype",2);

$pagerlink="files.php?page={page}&folderid={$folderid}";
$condition="folderid={$folderid}";
$orderstr="id desc";
$pager = new Pager();
$pager->init(10,$curPage,$pagerlink);
$attachements = $pager->queryRows($db,"attachments",$condition, "*",$orderstr);
foreach($attachements as $key=>$att){
	$att['shortfilename']=htmlFilter(cutStr($att['filename'],12));
	$att['filename']=htmlFilter($att['filename']);
	$att['uploadtime']=getDateStr($att['uploadtime']);
	$attachements[$key]=$att;
}


$folderrow=$cache_folders[$folderid];
$folderrow['title']=htmlFilter($folderrow['title']);

print <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>IMAGE</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../getfiles.php?t=js&v={$_SYS['VERSION']}&f=util|ajax|admin"></script>
<body style="background:#fff;">
<table width="100%"><tr><td width="140px"><a href="folder.php" target="_self" title="{$_AL['folder.returnfolder']}"><img src="images/btn_uplevel.gif" border="0" /></a> <img src="images/btn_close.gif" id="btnClose" onclick="window.parent.popwin.close()" border="0" style="cursor:pointer;" /> </td><td><div class="list_pager">{$pager->getPageStr()}</div></td></tr></table>
<form id="filesform" onsubmit="return false">
<!--#######################-->
<div class="condiv"><a href="folder.php">{$_AL['folder.all']}</a> -&gt; {$folderrow['title']} [<a href="javascript:showRenameFolder()">{$_AL['folder.rename']}</a>] &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;<input type="checkbox" onclick="selectAll('filesform',this.checked)" />{$_AL['all.selectall']} &nbsp;<a href="javascript:showDelFiles()">{$_AL['folder.delselfiles']}</a> &nbsp;<a href="javascript:showMoveFiles()">{$_AL['folder.moveselfiles']}</a>
	<!--#######################-->
	<div id="renamefolderdiv" style="display:none;">
		<input type="hidden" name="folderid" value="{$folderid}" />
		{$_AL['folder.inputnewname']}: <input type="text" value="{$folderrow['title']}" size="30" maxlength="50" id="newfoldername" name="newfoldername" class="text_css" /> <input type="button" value="  {$_AL['folder.confirm.modify']}  " onclick="renameFolder()" class="button_css" />
	</div>
	<!--#######################-->
	<div id="movefilediv" style="display:none;">
		{$_AL['folder.targetdir']}: <select name="targetfolder">{$cache_foldersoption}</select> <input type="button" value="  {$_AL['folder.move']}  " onclick="moveFiles()" class="button_css" />
	</div>
	<!--#######################-->
	<div id="delfilediv" style="display:none;">
		{$_AL['folder.del.warning']} <input type="button" value="  {$_AL['folder.confirm.del']}  " onclick="delFiles()" class="button_css" />
	</div>

</div>
EOT;
foreach($attachements as $att){
if($att['type']==1 && isImg($att['filename'])){
	$thumb="../../uploadfile/thumb/".$att['filepath'];
		$imgwidth=$att['imgwidth']>90?"style='width:90px;'":"";
	if(file_exists($thumb)){
		$img="<img src=\"{$thumb}\"{$imgwidth} />";
	}else{
		$img="<img src=\"../../uploadfile/attachment/{$att['filepath']}\"{$imgwidth} />";
	}
}else{
	$img="<img src=\"../../images/attachment.gif\" />";
}
print <<<EOT
<div class="attitem" id="imgitem_{$att['id']}" title="{$att['filename']}">
	<input type="checkbox" value="{$att['id']}" name="ids[]" class="checkbox" {$disablestr} />
	<div class="attiem_imgdiv" title="{$_AL['folder.click2use']}: {$att['filename']}" onclick="useFile('{$att['id']}','{$att['filename']}','{$att['type']}')">{$img}</div>
	<div class="attiem_filename">{$att['shortfilename']}</div>
	<div class="attiem_edit">{$att['uploadtime']}&nbsp;<a href="javascript:delFile('imgitem_{$att['id']}', '{$att['id']}' ,'')" class="del" title="{$_AL['all.delete']}">X</a></div>
</div>
EOT;
}

print <<<EOT
</form>
<div style='clear:both; height:15px; border-top:1px dotted #999999;'></div>
<div class="list_pager">{$pager->getPageStr()}</div>

<script>
function useFile(fileid,filename, isimg){
	window.parent.insertAttachment(fileid,filename,isimg);
}

function delFile(divid, fileid, md5str){
	var filediv = document.getElementById(divid);
	 if (filediv != null){
		filediv.parentNode.removeChild(filediv);
	 }
	 ajaxGet("ajax.php?action=deleteAttachment&id="+fileid+"&md5="+md5str, delFile_callback);
}

function delFile_callback(data){
	
}

function showMoveFiles(){
	setDisplays(['renamefolderdiv','delfilediv'],[false,false]);
	E("movefilediv").style.display=E("movefilediv").style.display==''?'none':'';
}

function moveFiles(){
	ajaxPost("filesform", "ajax.php?action=moveFiles", moveFiles_callback);
}

function moveFiles_callback(data){
	if(isSucceed(data)){
		alert("{$_AL['folder.filemove.succeed']}");
		self.location.reload();
	}else{
		alert(data);
	}
}

function showDelFiles(){
	setDisplays(['renamefolderdiv','movefilediv'],[false,false]);
	E("delfilediv").style.display=E("delfilediv").style.display==''?'none':'';
}

function delFiles(){
	ajaxPost("filesform", "ajax.php?action=delFiles", delFiles_callback);
}

function delFiles_callback(data){
	if(isSucceed(data)){
		alert("{$_AL['folder.filedel.succeed']}");
		self.location.reload();
	}else{
		alert(data);
	}
}


function showRenameFolder(){
	setDisplays(['movefilediv','delfilediv'],[false,false]);
	E("renamefolderdiv").style.display=E("renamefolderdiv").style.display==''?'none':'';
}
function renameFolder(){
	var foldername=E("newfoldername").value;
	if(foldername==''){
		alert("{$_AL['folder.alert.inputname']}");
		return;
	}
	ajaxPost("filesform", "ajax.php?action=renameFolder", renameFolder_callback);
}

function renameFolder_callback(data){
	if(isSucceed(data)){
		alert("{$_AL['folder.rename.succeed']}");
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