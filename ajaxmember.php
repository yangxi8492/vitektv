<?php
header("Content-Type:text/html; charset=utf-8");
include_once('inc/init.php');
if(!isLogin()){
	exit($_SLANG['all.notlogin']);
}

$action=strFilter($_GET["action"]);
switch($action){

	case "modifyPass":
		$opass=strFilter($_POST["oldpass"]);
		$npass=strFilter($_POST["memberpass"]);
		if($opass==""||$npass==""){
			echo($_SLANG['ajaxmember.password']);
		}
		else{
			$row=$db->row_select_one("members","id={$lg['memberid']}");
			if($row==null){
				exit($_SLANG['ajaxmember.usernotexist']);
			}
			$opass=encrypt($row['membername'],$opass);
			if($opass!=$row['memberpass']){
				exit($_SLANG['ajaxmember.oldpasserr']);
			}else{
				//可以修改密码
				$tmp['memberpass']=encrypt($row['membername'],$npass);
				$db->row_update("members",$tmp,"id={$row['id']}");
				succeedFlag();
			}	
		}
	break;
	
	case "modifyDetails":
		$member = $_POST['member'];
		if($member["email"]==""){
			exit($_SLANG['ajaxmember.details.required']);
		}
		$db->row_update("members",$member,"id={$lg['memberid']}");
		succeedFlag();
		
	break;	
	
	case "addFav":
		$proid = intval($_GET['proid']);
		if(empty($proid)){
			exit($_SLANG['ajaxmember.parmerr']);
		}
		$favobj["proid"] = $proid;
		$favobj["memberid"] = $lg["memberid"];
		$favobj["addtime"] = time();
		$favobj['langid']= $_SYS['langid'];
		$row = $db->row_select_one("favs","proid={$proid} and memberid={$lg['memberid']} and langid={$_SYS['langid']}");
		if(empty($row)){
			$db->row_insert("favs",$favobj);
		}else{
			$db->row_update("favs",$favobj,"proid={$proid} and memberid={$lg['memberid']} and langid={$_SYS['langid']}");
		}
		succeedFlag();
	break;	
	
	case "delFav":
		$favids=$_POST['favids'];
		if(empty($favids)){
			echo($_SLANG['ajaxmember.nofav']);
			return;
		}
		if(isIntArray($favids)) {
			$favidstr=implode(",",$favids);
			$db->row_delete("favs","id in ({$favidstr}) and memberid={$lg['memberid']} and langid={$_SYS['langid']}");
		}
		succeedFlag();
	break;	
	
	default:
		echo"No Such Action";
	break;
}
?>
