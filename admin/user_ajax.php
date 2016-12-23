<?php
header("Content-Type:text/html; charset=utf-8");
require_once('../inc/init.php');
require_once('../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("user")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.admin.set'])));
}


$action=strFilter($_GET['action']);
switch($action){	
	case "delusers":
		try{
			$deluid=$_POST['deluid'];
			if(isIntArray($deluid)) {
				foreach($deluid as $uid) {
					//delete users
					$db->row_delete("users","id={$uid}");
				}
			}
			writeUsersCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;
		

	case "savepopedom":
		$userid=intval($_POST['userid']);

		//check at least one user had popedom of user setting
		$row=$db->row_select_one("users","id<>{$userid} and popedom like '%|user|%' and ishidden=0");
		$popedom=$_POST['popedom'];
		if(is_array($popedom)){
			$str="|".implode("|",$popedom)."|";
		}
		else{
			$str="";
		}
		if(empty($row) && !stristr($str,"|user|")){
			exit($_AL['user.1user.setpopedom']);
		}
		$u['popedom']=$str;
		$db->row_update("users",$u,"id={$userid}");
		writeUsersCache();
		succeedFlag();
	break;

	

	case "edituser":
		$uid=intval($_GET['uid']);
		$user=$_POST['user'];
		
		if($uid==0||!is_array($user)){
			exit($_AL['all.parmerr']);
		}
		if(empty($user['userpass'])){
			unset($user['userpass']);
		}else{
			$user['userpass']=encrypt($user['username'],$user['userpass']);
		}
		$user['addtime']=strtotime($user['addtime'])-$cache_settings['timeoffset']*3600;
		$user['addtime'] = $user['addtime']<0 ? 0: $user['addtime'];
		$user['lasttime']=strtotime($user['lasttime'])-$cache_settings['timeoffset']*3600;
		$user['lasttime'] = $user['lasttime']<0 ? 0: $user['lasttime'];
		$db->row_update("users",$user,"id={$uid}");
		succeedFlag();
	break;

	
	case "adduser":
		$user=$_POST['user'];
		if(empty($user['username'])||empty($user['userpass'])||empty($user['email'])){
			exit($_AL['user.dts.required']);
		}
		$row=$db->row_select_one("users","username='{$user['username']}'");
		if(!empty($row)){
			exit($_AL['user.nameexist']);
		}
		$user['userpass']=encrypt($user['username'],$user['userpass']);
		$user['addtime']=time();
		$db->row_insert("users",$user);
		succeedFlag($db->insert_id());
	break;
	
	default:
		echo($_AL['all.noaction']);
	break;
}
?>