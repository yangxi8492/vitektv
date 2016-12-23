<?php
header("Content-Type:text/html; charset=utf-8");
require_once('../inc/init.php');
require_once('../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("channel")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.channel.man'])));
}

$action=strFilter($_GET['action']);

switch($action){
	case "saveset":
		try{
			$ordernums=$_POST['ordernum'];
			$positions=$_POST['position'];
			$title=$_POST['title'];
			if(is_array($ordernums)) {
				foreach($ordernums as $id => $value) {
					$channel['ordernum'] = intval($value);
					$channel['positions'] = isIntArray($positions[$id])?"|".implode($positions[$id],"|")."|":"";
					$channel['title'] = $title[$id];
					$db->row_update("channels",$channel,"id={$id} and langid={$_SYS['alangid']}");
				}
			}
			writeChannelsCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;
	
	case "delchannel":
		try{
			$cid=intval($_GET['cid']);
			if(empty($cid)){
				exit($_AL['all.parmerr']);
			}
			$row=$db->row_select_one("channels","id={$cid}");
			if($row['systemtype']>0){
				exit($_AL['channel.ajax.system.nodel']);
			}
			$rows=$db->row_select("channels","pid={$cid}");
			if(!empty($rows)){
				exit($_AL['channel.ajax.system.subnodel']);
			}

			//Delete Articles
			$db->row_delete("articles","channelid={$cid}");
			//Delete Channels
			$db->row_delete("channels","id={$cid}");
			writeChannelsCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;
	
	case "modifychannel":
		try{
			$doaction=strFilter($_POST['doaction']);
			$channel['channeltype']=intval($_POST['channeltype']);
			$channel['alias']=strFilter($_POST['alias']);
			$channel['ishidden']=intval($_POST['ishidden']);
			$channel['positions'] = isIntArray($_POST['positions'])?"|".implode($_POST['positions'],"|")."|":"";
			$channel['pid']=intval($_POST['pid']);
			$channel['langid']=$_SYS['alangid'];
			$channel['title']=strFilter($_POST['title']);
			$channel['seotitle']=strFilter($_POST['seotitle']);
			$channel['metadesc']=strFilter($_POST['metadesc']);
			$channel['metakeywords']=strFilter($_POST['metakeywords']);
			if($doaction=="edit"){
				$id=intval($_POST['id']);
				$db->row_update("channels",$channel,"id={$id}");
			}else{
				$tmprow=$db->row_query_one("SELECT max(ordernum) AS morder FROM `{$db->pre}channels` WHERE langid={$_SYS['alangid']} Limit 1");
				$channel['content']='';
				$channel['ordernum']=++$tmprow['morder'];
				$db->row_insert("channels",$channel);
			}
			writeChannelsCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;

	case "sethide":
		$channelid=intval($_GET['channelid']);
		$hide=intval($_GET['hide']);
		$channel['ishidden']=$hide;
		$db->row_update("channels",$channel,"id={$channelid}");
		writeChannelsCache();
		//_header_("location:admin.php?inc=channel&action=set");
		printRes($_AL['channel.ajax.set.succeed']."<script>setTimeout(function(){reloadTop('admin.php?inc=channel&action=set');},1000);</script>");
		//succeedFlag();
	break;

	case "savepage":
		$channelid=intval($_POST['channelid']);
		$content=strFilter($_POST['content']);
		$channel['content']=$content;
		$db->row_update("channels",$channel,"id={$channelid}");
		writeChannelsCache();
		printRes($_AL['channel.ajax.edit.succeed']."<script>setTimeout(function(){reloadSelf('admin.php?inc=channel&action=set');},2000);</script>");
		//succeedFlag();
	break;


	case "savelink":
		$channelid=intval($_POST['channelid']);
		$link=strFilter($_POST['link']);
		$target=intval($_POST['target']);
		$channel['link']=$link;
		$channel['target']=$target;
	
		$db->row_update("channels",$channel,"id={$channelid}");
		writeChannelsCache();
		printRes($_AL['channel.ajax.edit.succeed']."<script>setTimeout(function(){reloadSelf('admin.php?inc=channel&action=set');},2000);</script>");
		//succeedFlag();
	break;



	default:
		echo($_AL['all.noaction']);
	break;

}
?>
