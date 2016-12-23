<?php
global $_SYS;
global $cache_settings;
global $cache_global;
global $cache_contacts;
global $cache_langs;
global $cache_links_logo;
global $cache_links_text;
global $_SLANG;
global $_LANG;
global $webcore;
global $lg;
global $db;
$tid=empty($tid)?intval($_GET['tid']):$tid;
$fid=empty($fid)?intval($_GET['fid']):$fid;
$headtitle=$_SLANG['printmsg.title'];
$_SYS['positionchannel']=" » ".$_SLANG['printmsg.title'];

$msgstr="";
$msglink1="<img src=\"images/ico_msgp1.gif\" border=\"0\" align=\"absmiddle\" alt=\"\" /> <a href=\"javascript:history.go(-1)\"><u>{$_SLANG['printmsg.return']}</u></a>";
$msglink2="<img src=\"images/ico_msgp1.gif\" border=\"0\" align=\"absmiddle\" alt=\"\" /> <a href=\"{$_SYS['indexurl']}\"><u>{$_SLANG['printmsg.backhome']}</u></a>";

switch($msg_code){
	case "login_detailsrequired":
		$msgstr=$_SLANG['printmsg.nameandpass.required'];
		$msglink = $msglink1;		
	break;
	
	case "login_namepasserr":
		$msgstr=$_SLANG['printmsg.nameorpass.err'];
		$msglink = $msglink1;				
	break;
	
	case "login_validationcodeerr":
		$msgstr=$_SLANG['printmsg.secodeerr'];
		$msglink = $msglink1;		
	break;

	case "login_succeed":
		$ref = empty($_POST['ref'])?"index.php":$_POST['ref'];
		$msgstr=$_SLANG['printmsg.login.succeed']."<script>setTimeout(function(){window.location.href=\"{$ref}\";},2000);</script>";
		$msglink = "<a href='{$ref}'><u>{$_SLANG['printmsg.return.prelogin']}</u> <img src=\"images/ico_go.gif\" border=\"0\" align=\"absmiddle\" alt=\"\" /></a>";
	break;

	case "login_group".GROUP_NOVERIFY:
		if($cache_global['issignupverify']=='2'){
			$msgstr=$_SLANG['printmsg.first.active'];
			$msglink = "<a href='public.php?action=getactive'><u>{$_SLANG['printmsg.sendactive']}</u> <img src=\"images/ico_msgp1.gif\" border=\"0\" align=\"absmiddle\" alt=\"\" /></a>";	
		}else{
			$msgstr=$_SLANG['printmsg.notverify'];
			$msglink = $msglink2;
		}
	break;

	case "login_group".GROUP_NOVISIT:
		$msgstr=$_SLANG['printmsg.forbidden'];
	break;
	
	case "login_group".GROUP_VERIFYFAILED:
		$msgstr=$_SLANG['printmsg.verify.failed'];
	break;

	case "logout_succeed":
		$msgstr=$_SLANG['printmsg.exit.succeed'];
		$msglink = $msglink2;
	break;	

	case "signup_signupoff":
		$msgstr=$_SLANG['printmsg.signup.not'];
		$msglink = $msglink2;
	break;

	case "signup_signupitime":
		$msgstr=_LANG($_SLANG['printmsg.iplimittime'],array($cache_global['signupitime']));
		$msglink = $msglink2;
	break;
	
	case "signup_required":
		$msgstr=$_SLANG['printmsg.details.required'];
		$msglink = $msglink1;
	break;
	
	case "signup_succeed_0":
		global $membername;
		global $memberpass;
		$msgstr=$_SLANG['printmsg.signup.succeed1']."<form id='autologinform' action='login.php?action=login' method='POST' style='display:none;'><input name='membername' value='{$membername}' /><input name='memberpass' value='{$memberpass}' />";
		if($cache_settings['loginsecuritycode']=='1'){
			wSESSION('validationcode','sc6k');
			$msgstr .= "<input name='securitycode' value='sc6k' />";
		}
		$msgstr .= "</form><script>setTimeout(function(){document.getElementById(\"autologinform\").submit();},2000);</script>";
		$msglink = "<a href='javascript:void(0)' onclick='document.getElementById(\"autologinform\").submit()'><u>{$_SLANG['printmsg.login.now']}</u> <img src=\"images/ico_go.gif\" border=\"0\" align=\"absmiddle\" alt=\"\" /></a>";
	break;

	case "signup_succeed_1":
		$msgstr=$_SLANG['printmsg.signup.succeed2']."<script>setTimeout(function(){window.location.href=\"login.php\";},5000);</script>";
		$msglink = $msglink2;
	break;

	case "signup_succeed_2":
		$msgstr=$_SLANG['printmsg.signup.succeed3']."<script>setTimeout(function(){window.location.href=\"login.php\";},8000);</script>";
		$msglink = $msglink2;
	break;

	case "signup_succeed_3":
		$msgstr=$_SLANG['printmsg.signup.succeed4']."<script>setTimeout(function(){window.location.href=\"login.php\";},8000);</script>";
		$msglink = $msglink2;
	break;

	case "signup_memberexist":
		$msgstr=$_SLANG['printmsg.username.used'];
		$msglink = $msglink1;
	break;

	case "signup_membernotvalid":
		$msgstr=$_SLANG['printmsg.username.illegal'];
		$msglink = $msglink1;
	break;
	
	case "signup_memberlength":
		$msgstr=$_SLANG['printmsg.username.length'];
		$msglink = $msglink1;
	break;
	
	case "signup_reservedkeyword":
		$msgstr=$_SLANG['printmsg.username.key'];
		$msglink = $msglink1;
	break;

	case "signup_passlength":
		$msgstr=$_SLANG['printmsg.pass.length'];
		$msglink = $msglink1;
	break;
	
	case "signup_repasserr":
		$msgstr=$_SLANG['printmsg.pass.re'];
		$msglink = $msglink1;
	break;

	case "signup_emailnotvalid":
		$msgstr=$_SLANG['printmsg.email.err'];
		$msglink = $msglink1;
	break;

	case "signup_emailexist":
		$msgstr=$_SLANG['printmsg.email.used'];
		$msglink = $msglink1;
	break;
	
	case "signup_validationcodeerr":
		$msgstr=$_SLANG['printmsg.secodeerr'];
		$msglink = $msglink1;		
	break;

	case "funmember_off":
		$msgstr=$_SLANG['printmsg.nofun.member'];
		$msglink = $msglink2;
	break;

	case "funshop_off":
		$msgstr=$_SLANG['printmsg.nofun.order'];
		$msglink = $msglink2;
	break;

	case "public_active_err":
		$msgstr=$_SLANG['printmsg.activelink.outdate'];
		$msglink = "<a href='public.php?action=getactive'><u>{$_SLANG['printmsg.sendactive']}</u> <img src=\"images/ico_msgp1.gif\" border=\"0\" align=\"absmiddle\" alt=\"\" /></a>";	
	break;

	case "public_active_succeed":
		$msgstr=$_SLANG['printmsg.active.succeed'];
		$msglink = "<a href='login.php'><u>{$_SLANG['printmsg.login.now']}</u> <img src=\"images/ico_go.gif\" border=\"0\" align=\"absmiddle\" alt=\"\" /></a>";
	break;

	case "public_resetpass_err":
		$msgstr=$_SLANG['printmsg.reset.outdate'];
		$msglink = "<a href='public.php?action=forgetpass'><u>{$_SLANG['printmsg.reset.send']}</u> <img src=\"images/ico_msgp1.gif\" border=\"0\" align=\"absmiddle\" alt=\"\" /></a>";	
	break;

	case "public_resetpass_succeed":
		global $newpass;
		$msgstr=_LANG($_SLANG['printmsg.reset.succeed'],array("<span class='msg_newpass'>{$newpass}</span>"));
		$msglink = "<a href='login.php'><u>{$_SLANG['printmsg.login.now']}</u> <img src=\"images/ico_go.gif\" border=\"0\" align=\"absmiddle\" alt=\"\" /></a>";
	break;

}
if($msgstr==""){
	$msgstr=$msg_code;
}
require_once('header.php');
require_once getTemplatePath('printmsg.htm');
footer();
?>