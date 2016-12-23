<?php
header("Content-Type:text/html; charset=utf-8");
require_once('../inc/init.php');
require_once('../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("order")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.order.man'])));
}

$action=strFilter($_GET['action']);
switch($action){
	
	case "saveorders":
		try{
			$order['state']=intval($_POST['state']);
			$order['remark2']=strFilter($_POST['remark2']);
			$order['expresscharges']=strFilter($_POST['expresscharges']);
			$order['name']=strFilter($_POST['name']);
			$order['phonenum']=strFilter($_POST['phonenum']);
			$order['email']=strFilter($_POST['email']);
			$order['address']=strFilter($_POST['address']);
			$order['zipcode']=strFilter($_POST['zipcode']);
			$order['remark']=strFilter($_POST['remark']);

			$id=intval($_POST['id']);
			$oldstate=intval($_POST['oldstate']);
			$db->row_update("orders",$order,"id={$id}");

			printRes("{$_AL['order.edit.succeed']}<script>setTimeout(function(){reloadSelf('admin.php?inc=order&action=list&state={$oldstate}');},2000);</script>");
		}catch(Exception $e){
			echo($e);
		}
	break;



	case "doorders":
		try{
			$postaction=$_POST['postaction'];
			$aids=$_POST['aids'];
			if(empty($aids)){
				exit($_AL['order.noselect']);
			}
			if(isIntArray($aids)) {
				$aidstr=implode(",",$aids);
				switch($postaction){
					case "":
					case "delOrders":
						$db->row_delete("orders","id in ({$aidstr}) and langid={$_SYS['alangid']}");
						succeedFlag();
					break;
					default:
						echo($_AL['all.noaction']);
					break;
				}
			}
		}catch(Exception $e){
			echo($e);
		}
	break;
	default:
		echo($_AL['all.noaction']);
	break;
}
?>
