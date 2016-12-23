<?php
require_once('./../inc/init.php');
require_once('./language/language.php');

$action=strFilter($_GET['action']);
$username=strFilter($_POST['username']);
$userpass=strFilter($_POST['userpass']);
$securitycode=strFilter($_POST['securitycode']);
$ref=strFilter($_POST['ref']);
if($ref==""){
	$ref="index.php";
}

if($action=='exit'){
	uSESSION('isadmin');
	_header_("location:login.php");
}

if(isAdmin()){
	_header_("location:index.php");
}

if($action=='login'){
	if($lg['groupid']!=GROUP_ADMIN){
		//exit($_AL['login.webfirst']);
	}
	if($username==''||$userpass==''||$userpass==''){
		$errtips=('login_detailsrequired');
	}
	
	elseif(strtolower(rSESSION('validationcode'))!=strtolower($securitycode)){
		$errtips=('login_validationcodeerr');
	}
	
	else{
		$userpass = encrypt($username,$userpass);
		$row=$db->row_select_one("users","username='{$username}' and userpass='{$userpass}'");
		if($row==null){
			$errtips=('login_namepasserr');
		}else{
			$uobj['lastip']=getIP();
			$uobj['lasttime']=time();
			$db->row_update("users",$uobj,"id={$row['id']}");
			wSESSION('isadmin',1);
			wSESSION('userid',$row['id']);
			_header_("location:{$ref}");
			$errtips=('login_succeed');
		}
	}
}else{
	//$ref = $_GET["ref"];
	if($ref==""){
		$ref="index.php";
	}
}
$errtipsstr=array(
	'login_detailsrequired'=>$_AL['login.required'],
	'login_validationcodeerr'=>$_AL['login.codeerr'],
	'login_namepasserr'=>$_AL['login.usererr'],
);
print<<<EOT

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base target="mainifm">
<title>{$_AL['login.title']}</title>
<link href="css/global.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../getfiles.php?t=js&v={$_SYS['VERSION']}&f=util"></script>
</head>
<body class="login_body">
<div class="login_div">
<table class="login0">
	<tr>
		<td class="login0_left">
			<div class="login0_logo"></div>
			<div class="login0_text"></div>
		</td>
		<td class="login0_right">
EOT;
if(true){
print<<<EOT

		<form action="login.php?action=login" method="POST" target="_self">
			<div id="login_errtips">{$errtipsstr["{$errtips}"]}</div>
			<table class="login2">
				<tr><td class="left">{$_AL['login.username']}:</td><td class="right"><input type="text" value="" size="20" name="username" id="username" class="text_css" /><input type="hidden" value="{$ref}" size="20" name="ref" id="ref" /></td></tr>
				<tr><td class="left">{$_AL['login.userpass']}:</td><td class="right"><input type="password" value="" size="20" name="userpass" id="userpass" class="text_css" /></td></tr>
				<tr><td class="left">{$_AL['login.code']}:</td><td class="right"><input type="text" value="" size="5" name="securitycode" id="securitycode" class="text_css" /> <a href="javascript:reloadVerify('securitycodeimg')" title="{$_AL['login.notclear']}" target="_self"><img src="../code.php?t={$_SYS['time']}" align="absmiddle" border="0" id="securitycodeimg" /></a></td></tr>
				<tr><td class="left"></td><td class="right"><input type="submit" class="button_css" value="   {$_AL['login.submit']}   " /></td></tr>
			</table>
		</form>
EOT;
}else{
$urlecd=urlencode($_SERVER['REQUEST_URI']);
print<<<EOT

			<div class="login0_noallow"><a href="../login.php?ref={$urlecd}" target="_top">{$_AL['login.webfirst']}</a></div>
EOT;
}
print<<<EOT

		</td>
	</tr>
</table>
</div>
<script type="text/javascript">
function reloadVerify(imgid){
	E(imgid).src="../code.php?t="+getTimer();
}
window.onload=function(){
	if(E("username")){E("username").focus();}
}
</script>

</body>
</html>
EOT;
?>