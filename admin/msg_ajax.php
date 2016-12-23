<?php
header("Content-Type:text/html; charset=utf-8");
include_once('../inc/init.php');
include_once('../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("msg")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.msg.list'])));
}

$action=strFilter($_GET['action']);
switch($action){
	
	case "savemsg":
		try{
			$doaction=strFilter($_POST['doaction']);
			$msg['name']=strFilter($_POST['name']);
			$msg['email']=strFilter($_POST['email']);
			$msg['contact1']=strFilter($_POST['contact1']);
			$msg['title']=strFilter($_POST['title']);
			$msg['remark']=strFilter($_POST['remark']);
			$msg['replier']=strFilter($cache_users[$lg['userid']]['username']);
			$msg['reply']=strFilter($_POST['reply']);
			$msg['state']=intval($_POST['state']);
			$msg['replytime']=time();
			$id=intval($_POST['id']);
			$db->row_update("msgs",$msg,"id={$id}");
			printRes("{$_AL['msg.reply.succeed']}<script>setTimeout(function(){reloadSelf('admin.php?inc=msg&action=list');},1500);</script>");
		}catch(Exception $e){
			echo($e);
		}
	break;

	case "domsgs":
		try{
			$postaction=$_POST['postaction'];
			$ids=$_POST['ids'];
			if(empty($ids)){
				exit($_AL['msg.nochoose']);
			}
			if(isIntArray($ids)) {
				$idstr=implode(",",$ids);
				switch($postaction){
					case "delMsg":
						$db->row_delete("msgs","id in ({$idstr})");
					break;
					case "verifyY":
						$msg['state']=1;
						$db->row_update("msgs",$msg,"id in ({$idstr})");
					break;
					case "verifyN":
						$msg['state']=0;
						$db->row_update("msgs",$msg,"id in ({$idstr})");
					break;
					default:
						echo($_AL['all.noaction']);
					break;
				}
				succeedFlag();
			}
		}catch(Exception $e){
			echo($e);
		}
	break;

	case "verify":
		$id=intval($_GET['id']);
		$state=intval($_GET['state']);
		$msg['state']=$state;
		$db->row_update("msgs",$msg,"id={$id}");
		_header_("location:{$_SERVER['HTTP_REFERER']}");
		//succeedFlag();
	break;

	default:
		echo($_AL['all.noaction']);
	break;
}
?>
