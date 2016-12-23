<?php
error_reporting(E_ALL ^ E_NOTICE);
header("Content-Type: text/html; charset=UTF-8");
session_start();

//初始化
define('INC_P',dirname(__FILE__));
require_once(INC_P.'/logger.php');
require_once(INC_P.'/config.php');
if(empty($_DB['type']))$_DB['type']='mysql';
require_once(INC_P.'/db_'.$_DB['type'].'.php');
require_once(INC_P.'/var.php');
require_once(INC_P.'/fun.php');

//addslashes_deep
if($_DB['type']=='mysql'){
	if (!get_magic_quotes_gpc()){if(!empty($_GET)){$_GET  = mysql_addslashes_deep($_GET);}	if (!empty($_POST)){$_POST = mysql_addslashes_deep($_POST);}	$_COOKIE   = mysql_addslashes_deep($_COOKIE);		$_REQUEST  = mysql_addslashes_deep($_REQUEST);}
}elseif($_DB['type']=='sqlite'){
	if (get_magic_quotes_gpc()){if(!empty($_GET)){$_GET  = stripslashes_deep($_GET);}	if (!empty($_POST)){$_POST = stripslashes_deep($_POST);}	$_COOKIE   = stripslashes_deep($_COOKIE);		$_REQUEST  = stripslashes_deep($_REQUEST);}
	{if(!empty($_GET)){$_GET  = sqlite_addslashes_deep($_GET);}	if (!empty($_POST)){$_POST = sqlite_addslashes_deep($_POST);}	$_COOKIE   = sqlite_addslashes_deep($_COOKIE);		$_REQUEST  = sqlite_addslashes_deep($_REQUEST);}
}
unregister_globals('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');

require_once(INC_P.'/webcore_class.php');
require_once getCacheFilePath('langs.php');
require_once getCacheFilePath('global.php');

//网站的langid
if($_GET['langid']){
	setCookies("langid",$_GET['langid'],3600*24*365);
	$_SYS['langid'] = $_GET['langid'];
}else{
	$_SYS['langid']=getCookies('langid');
}	
if(!array_key_exists($_SYS['langid'],$cache_langs)){
	$def_cache_lang=array_slice($cache_langs,0,1);
	$_SYS['langid']=$def_cache_lang[0]['id'];
	setCookies("langid",$_SYS['langid'],3600*24*365);
	unset($def_cache_lang);
}
$_SYS['langid']=intval($_SYS['langid']);

//后台管理的alangid
if($_GET['alangid']){
	setCookies("alangid",$_GET['alangid'],3600*24*365);
	$_SYS['alangid'] = $_GET['alangid'];
}else{
	$_SYS['alangid']=getCookies('alangid');
	if(empty($_SYS['alangid'])){
		$_SYS['alangid']=$_SYS['langid'];
	}
}
if(!array_key_exists($_SYS['alangid'],$cache_langs)){
	$def_cache_lang=array_slice($cache_langs,0,1);
	$_SYS['alangid']=$def_cache_lang[0]['id'];
	setCookies("alangid",$_SYS['alangid'],3600*24*365);
	unset($def_cache_lang);
}
$_SYS['alangid']=intval($_SYS['alangid']);

//根据语言加载不同的缓存文件
$_cachelangid=(stristr($_SERVER['REQUEST_URI'],'/'.ADMIN_DIR.'/')||isset($_GET['preview']))?$_SYS['alangid']:$_SYS['langid'];
require_once getCacheFilePath('settings.php',$_cachelangid);
require_once getCacheFilePath('channels.php',$_cachelangid);
require_once getCacheFilePath('procates.php',$_cachelangid);
require_once getCacheFilePath('contacts.php',$_cachelangid);
require_once getCacheFilePath('links.php',$_cachelangid);
require_once getCacheFilePath('votes.php',$_cachelangid);
require_once getCacheFilePath('templatevars.php',$_cachelangid);

//是否启用gzip
if($cache_settings['isgzip']=='1' && function_exists('ob_gzhandler')){
	ob_start('ob_gzhandler');
}
ob_start("_vars_");
ob_start("_clear_");


//是否关闭网站
if($cache_settings['isoff']=='1' && !stristr($_SERVER['PHP_SELF'],'/'.ADMIN_DIR.'/')){
	exit('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>'.$cache_settings['webname'].'</title></head><body>'.$cache_settings['offdetails'].'</body></html>');
}

//初始化
$timer_begin=getmicrotime();
$db = new db();
$db->connect($_DB);
$webcore=new WebCore();



//管理员登录信息
$lg['userid'] = intval(rSESSION('userid'));
$lg['isadmin'] = intval(rSESSION('isadmin'));

//会员登录信息
$lg['memberid'] = intval(rSESSION('memberid'));
$lg['groupid'] = intval(rSESSION('groupid'));
$lg['isadmin'] = intval(rSESSION('isadmin'));
$lg['membername'] = strFilter(getCookies('membername'));
$lg['displayname'] = htmlFilter(getCookies('membername'));
$lg['memberpass'] = strFilter(getCookies('memberpass'));
$lg['memberauth'] = strFilter(getCookies('memberauth'));
$lg['expire'] = intval(getCookies('expire'));
if(empty($lg['membername']) || empty($lg['memberpass'])){
	$lg['memberid']=0;$lg['groupid']=0;
}elseif(md5($lg['membername'].$lg['memberpass'].$cache_settings['salt'])!=$lg['memberauth']){
	$lg['memberid']=0;$lg['groupid']=0;$lg['membername']='';$lg['memberpass']='';
}
if($lg['memberid']==0 || $lg['groupid']==0){

	$lg['groupid']=GROUP_GUESS;
	//自动登录
	if(!empty($lg['membername']) && !empty($lg['memberpass'])){
		$lgrow=$db->row_select_one("members","membername='{$lg[membername]}' and memberpass='{$lg[memberpass]}'","id,groupid");
		if(empty($lgrow)){
			$lg['groupid']=GROUP_GUESS;
		}else{
			if($lgrow['groupid']==GROUP_NOVERIFY || $lgrow['groupid']==GROUP_NOVISIT || $lgrow['groupid']==GROUP_VERIFYFAILED){	//待验证 //禁止访问 //验证不通过
				$cleart= -86400 * 365 * 2; setCookies('membername', '', $cleart); setCookies('memberpass', '', $cleart); setCookies('expire', '', $cleart); _header_("location:index.php");
			}else{
				setCookies('memberauth', md5($lg['membername'].$lg['memberpass'].$cache_settings['salt']), $lg['expire']);
				wSESSION('memberid',$lgrow['id']);
				wSESSION('groupid',$lgrow['groupid']);
				$lg['memberid'] = intval(rSESSION('memberid'));
				$lg['groupid'] = intval(rSESSION('groupid'));
			}
		}
	}
}

//模板相关
$_SYS['styleid']=$cache_settings['template'];
if(isset($_GET['preview'])){
	$_SYS['styleid']=$_GET['styleid'];
	$_SYS['styleid']=str_replace(array("'","/","\\","\"","."),array('','','','',''),$_SYS['styleid']);
}

//模板路径
$_SYS['TP'] = 'template/'.$_SYS['styleid'];
$_SYS['indexurl'] = $webcore->genUrl('index.php'); 
$_SYS['positionindex'] = "<a href=\"{$_SYS['indexurl']}\">{$cache_settings['webname']}</a>";

//加载系统语言包
if(!empty($cache_langs[$_cachelangid])){
	$systemlang=INC_P.'/../language/'.$cache_langs[$_cachelangid]['directory'].'/language.php';
	if(file_exists($systemlang)){
		require_once($systemlang);
	}
	unset($systemlang);
}

//加载模版语言包
if(!empty($cache_settings['templatelang'])){
	$templatelang=INC_P.'/../template/'.$cache_settings['template'].'/language/'.$cache_settings['templatelang'];
	if(file_exists($templatelang)){
		require_once($templatelang);
	}
	unset($templatelang);
}

ob_clean();
?>