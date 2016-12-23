<?php
header("Content-Type:text/html; charset=utf-8");
require_once('../inc/init.php');
require_once('../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("page")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.page.man'])));
}

$action=strFilter($_GET['action']);

switch($action){
	case "savepage":
		$channelid=intval($_POST['channelid']);
		$content=strFilter($_POST['content']);
		$channel['content']=$content;
		$db->row_update("channels",$channel,"id={$channelid}");
		writeChannelsCache();
		printRes("{$_AL['page.edit.succeed']}<script>setTimeout(function(){reloadSelf('admin.php?inc=channel&action=set');},2000);</script>");
		//succeedFlag();
	break;
	default:
		echo($_AL['all.noaction']);
	break;

}
?>
