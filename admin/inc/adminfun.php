<?php
require_once getCacheFilePath('users.php');

function getSettings($langid=-1){
	global $db,$_SYS;
	$langid=$langid==-1?$_SYS['alangid']:intval($langid);
	$rows=$db->row_select("settings","langid={$langid}",0,"*","property");
	$row=array();
	foreach($rows as $tmprow){
		$row["{$tmprow['property']}"]=($tmprow['setvalue']);
	}
	return $row;
}

function saveSettings($setpp,$langid=-1){
	global $db,$_SYS;
	$langid=$langid==-1?$_SYS['alangid']:intval($langid);
	foreach($setpp as $key=>$value){
		$db->row_delete("settings","property='{$key}' and langid={$langid}");
		$db->query_unbuffered("INSERT INTO `{$db->pre}settings` (property, setvalue, langid) VALUES ('{$key}','{$value}',{$langid})");
	}
}


function sizeFormat($filesize, $type='') {
	if($type=='GB') {
		$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
	} elseif($type=='MB') {
		$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
	} elseif($type=='KB') {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} elseif($type=='Bytes') {
		$filesize = $filesize . ' Bytes';
	} else {
		if($filesize >= 1073741824) {
			$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
		} elseif($filesize >= 1048576) {
			$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
		} elseif($filesize >= 1024) {
			$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
		} else {
			$filesize = $filesize . ' Bytes';
		}
	}
	return $filesize;
}

function printRes($str="操作成功"){
print<<<EOT
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>
		.succeedtips{line-height:180%; background:#E2F4FC; padding:10px; color:#128CB2; font-size:12px;}
		.succeedtips a:link,.succeedtips a:active,.succeedtips a:visited,.succeedtips a:hover{color:#128CB2;}
	</style>
	<script src="../js/admin.js"></script>
	</head>
	<body>
	<div class="succeedtips"><b>{$str}</b></div>
	</body>
	</html>
EOT;
	exit();
}


function delDir($dir) {
	$dh=opendir($dir);
	while ($file=readdir($dh)) {
	  if($file!="." && $file!="..") {
		$fullpath=$dir."/".$file;
		if(!is_dir($fullpath)) {
			unlink($fullpath);
		} else {
			delDir($fullpath);
		}
	  }
	}

	closedir($dh);
   
	if(rmdir($dir)) {
	  return true;
	} else {
	  return false;
	}
} 

function phparr_to_jsarr($phparr,$jsarrname="")
{

	$str = "new Array(";
	if(empty($phparr))return $str.")";
	$str = $jsarrname=="" ? $str : "$jsarrname = ".$str;
	$len = count($phparr);
	$i = 0;
	while( list($a,$b)=each($phparr) )
	{
		$str .= $i++>0 ? "," : "";
		$str .= is_array($b) ? phparr_to_jsarr( $b ) : "\"".str_replace("\"","\\\"",str_replace("\\","\\\\",$b))."\"";
	}
	$str .=")";
	$str = $jsarrname=="" ? $str : $str.";";
	return $str;
}

function hasPopedom($p){
	global $cache_users;
	$uid=rSESSION('userid');
	if(stristr($cache_users[$uid]['popedom'],"|{$p}|")){
		return true;
	}
	return false;
}

function getChnAdminLink($row){
	$clink="";
	if($row['systemtype']==1){
		return "admin.php?inc=products&action=list";
	}elseif($row['systemtype']==2){
		return "admin.php?inc=main&action=contact";
	}elseif($row['systemtype']==3){
		return "admin.php?inc=msg&action=list";
	}
	switch($row['channeltype']){
		case 0:
		case 1:
			$clink="admin.php?inc=page&action=editpage&channelid={$row['id']}";
		break;
		case 2:
			$clink="admin.php?inc=article&action=list&channelid={$row['id']}";
		break;
		case 3:
			$clink="admin.php?inc=products&action=list";
		break;
		case 4:
			$clink="admin.php?inc=channel&action=link&channelid={$row['id']}";
		break;
	}
	return $clink;
}


?>
