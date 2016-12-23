<?php
header("Content-Type:text/html; charset=utf-8");
include_once('../inc/init.php');
include_once('../inc/cache.php');
include_once('inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("member")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.member.man'])));
}

$action=strFilter($_GET['action']);
switch($action){	
	case "delmembers":
		try{
			$deluid=$_POST['deluid'];
			if(isIntArray($deluid)) {
				foreach($deluid as $uid) {
					//1.delete favs
					$db->row_delete("favs","memberid={$uid}");
					//2.delete memberfield
					$db->row_delete("memberfield","memberid={$uid}");					
					//3.delete members
					$db->row_delete("members","id={$uid}");					
				}
			}
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;

	case "editmember":
		$uid=intval($_GET['uid']);
		$member=$_POST['member'];
		$delmemberpic=$_POST['delmemberpic'];
		
		if($uid==0||!is_array($member)){
			exit($_AL['all.parmerr']);
		}
		if(empty($member['memberpass'])){
			unset($member['memberpass']);
		}else{
			$member['memberpass']=encrypt($member['membername'],$member['memberpass']);
		}
		$member['signuptime']=strtotime($member['signuptime'])-$cache_settings['timeoffset']*3600;
		$member['signuptime'] = $member['signuptime']<0 ? 0: $member['signuptime'];
		$member['logintime']=strtotime($member['logintime'])-$cache_settings['timeoffset']*3600;
		$member['logintime'] = $member['logintime']<0 ? 0: $member['logintime'];
		$db->row_update("members",$member,"id={$uid}");
		succeedFlag();
	break;
	
	case "addmember":
		$member=$_POST['member'];
		if(empty($member['membername'])||empty($member['memberpass'])||empty($member['email'])){
			exit($_AL['member.details.required']);
		}
		$row=$db->row_select_one("members","membername='{$member['membername']}'");
		if(!empty($row)){
			exit($_AL['member.name.exist']);
		}
		$member['memberpass']=encrypt($member['membername'],$member['memberpass']);
		$member['sex']=1;
		$member['signuptime']=time();
		$member['signupip'] = getIP();
		$member['lastlogintime']=time();
		$db->row_insert("members",$member);
		succeedFlag();
	break;

	case "savesignupinfo":
		$settings = $_POST['settings'];
		try{
			saveSettings($settings,0);
			writeGlobalCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;	

	
	case "verify":
		try{
			$doaction=$_POST['doaction'];
			if(is_array($doaction)) {
				foreach($doaction as $id => $value) {
					$value=intval($value);
					if($value==1){
						//yes
						$members['groupid'] = 1;
						$db->row_update("members",$members,"id={$id}");
					}elseif($value==2){
						//no
						$members['groupid'] = GROUP_VERIFYFAILED;
						$db->row_update("members",$members,"id={$id}");
					}elseif($value==3){
						//del
						$db->row_delete("members","id={$id}");
					}else{
						//ignore
					}
					
				}
			}
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;
		

	default:
		echo($_AL['all.noaction']);
	break;
}

?>
