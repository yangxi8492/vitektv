<?php
function addslashes_deep($value){global $_DB; if($_DB['type']=='mysql'){return mysql_addslashes_deep($value);}elseif($_DB['type']=='sqlite'){return sqlite_addslashes_deep($value);}}
function mysql_addslashes_deep($value){if (empty($value)){return $value;}else{return is_array($value) ? array_map('mysql_addslashes_deep', $value) : addslashes($value);}}
function sqlite_addslashes_deep($value){if (empty($value)){return $value;}else{return is_array($value) ? array_map('sqlite_addslashes_deep', $value) : sqlite_escape_string($value);}}
function stripslashes_deep($value){if (empty($value)){return $value;}else{return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);}}
function unregister_globals(){if (!ini_get('register_globals')){return false;}foreach (func_get_args() as $name){foreach ($GLOBALS[$name] as $key=>$value){if (isset($GLOBALS[$key])){ unset($GLOBALS[$key]);}}}}

//取消HTML代码
function htmlFilter($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = htmlFilter($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
			str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

//过滤非法字符
function strFilter($str){
	return $str;
}


//是否为数字数组
function isIntArray($arr){
	if(!is_array($arr)){
		return false;
	}
	foreach($arr as $a){
		if(!is_numeric($a)){
			return false;
		}
	}
	return true;
}

//是否为合法的用户名
function isValidName($str){
	$reg="/^[\w\x80-\xff]+$/i";
	if(preg_match($reg,$str)){
		return true;
	}
	return false;
}

//是否为合法的邮箱
function isValidEmail($str){
	$reg="/[a-z0-9]([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
	if(preg_match($reg,$str)){
		return true;
	}
	return false;
}

//encrypt
function encrypt($username,$userpass){
	$str=$username.$userpass;
	return empty($str)?'':md5(strtolower($str));
}

//截取字符串
function cutStr($str,$len,$add=true){
    //Get the display width of the string.
    $i =0;
    $j =0;
    $str_width = 0;
    do{
            if(ord($str[$i]) > 224){
                    $str_width += 2;
                    $i += 3;
                }
            else if(ord($str[$i] > 192)){
                    $str_width += 2;
                    $i += 2;
                }
            else{
                    $str_width++;
                    $i++;
                }
        }while($i<strlen($str));
    //IF the display width is shorter than you want ,return the string.
    if($str_width < $len)
        {
            return $str;
        }
    else{
            $i = 0;
            $j = 0;
            $newword = '';
            do{
                    //If the character is a Chinese
                    if(ord($str[$i]) > 224){
                            $newword .= $str[$i].$str[$i+1].$str[$i+2];
                            $i = $i +3;
                            $j =$j + 2;
                        }
                    //If the character is a symble
                    else if(ord($str[$i] > 192)){
                            $newword .= $str[$i].$str[$i+1];
                            $i = $i + 2;
                            $j = $j + 2;
                        }
                    //If the character is a alpha
                    else{
                            $newword .= $str[$i];
                            $i++;
                            $j++;
                        }
                }while($j<$len);
            if($add){
                    return $newword.'...';
                }
            else
                return $newword;
        }

}

//int->date 显示时间(人性化时间)
/*
	getDateStr(时间戳, 只显示日期, 使用人性化时间)
*/
function getDateStr($dateint, $dateonly=false, $usehmt='-1'){
	global $cache_settings;
	$time_offset=$cache_settings['timeoffset'];
	$usehuman=($usehmt=='0'||empty($usehmt))?false:$cache_settings['humantime']=='1';
	$datedif=intval(time()-$dateint);
	if($usehuman){
		if($datedif<0){
			$usehuman=false;
		}elseif($datedif<60){
			$datedif=$datedif==0?1:$datedif;
			return "{$datedif}秒前";
		}elseif($datedif<3600){
			$datedif=intval($datedif/60);
			return "{$datedif}分钟前";
		}elseif($datedif<3600*24){
			$datedif=intval($datedif/3600);
			return "{$datedif}小时前";
		}else{
			$usehuman=false;
		}
	}
	if(!$usehuman){
		empty($cache_settings['timeformat']) && $cache_settings['timeformat']="yyyy-mm-dd";
		$df=$dateonly ? $cache_settings['timeformat']  : $cache_settings['timeformat']." H:i";
		$df = str_replace(array('yyyy','mm','dd'), array('Y','m','d'), $df);
		return gmdate($df,$dateint+$time_offset*3600);
	}
}


function getCacheFilePath($filename,$lang=0){
	$filepath=INC_P."/../cache/";
	if($lang==0){
		$filepath.=$filename;
	}else{
		$filepath.=$lang."/".$filename;
	}
	return $filepath;
}


//毫秒时间
function getmicrotime(){
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

//返回模板路径
function getTemplatePath($filename){
	global $_SYS;
	$tpl="{$_SYS['TP']}/{$filename}";
	if(!file_exists($tpl)){
		$tpl="template/6kzz/{$filename}";
	}
	return $tpl;
}

//是否登录
function isLogin(){
	global $lg;
	return $lg['memberid']>0;
}

//是否管理员
function isAdmin(){
	//return 1;
	global $lg;
	return (rSESSION('isadmin') ==1);
}


//_header_
function _header_($string, $isreplace = true, $code = 0) {
	$string = str_replace(array("\r", "\n"), array('', ''), $string);
	if(empty($code) || PHP_VERSION < '4.3' ) {
		@header($string, $isreplace);
	} else {
		@header($string, $isreplace, $code);
	}
	if(preg_match('/^\s*location:/is', $string)) {
		exit();
	}
}


//获取客户端IP
function getIP() {
	$cip = '';
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$cip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$cip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$cip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$cip = $_SERVER['REMOTE_ADDR'];
	}
	return is_ip($cip)?$cip:'127.0.0.1';
}

function is_ip($str){
	return preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/",$str); 
}

function setCookies($name, $value, $keep=0, $httponly=false) {
	global $_SYS;
	$path = $httponly && PHP_VERSION < '5.2.0' ? $_SYS['cookiepath']."; HttpOnly" : $_SYS['cookiepath'];
	$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
	$keep = $keep?(time()+$keep):0;
	if(PHP_VERSION < '5.2.0'){
		setcookie($_SYS['cookiepre'].$name, $value, $keep, $path, $_SYS['cookiedomain'], $secure);
	} else {
		setcookie($_SYS['cookiepre'].$name, $value, $keep, $path, $_SYS['cookiedomain'], $secure, $httponly);
	}
}

function getCookies($name){
	global $_SYS;
	return $_COOKIE[$_SYS['cookiepre'].$name];
}

function rSESSION($name){
	global $_SYS;
	return $_SESSION[$_SYS['sessionpre'].$name];
}

function wSESSION($name,$value){
	global $_SYS;
	$_SESSION[$_SYS['sessionpre'].$name]=$value;
}

function uSESSION($name){
	global $_SYS;
	unset($_SESSION[$_SYS['sessionpre'].$name]);
}

//获取访问目录路径	$predir = -1 ： 返回上级目录
function getUrlPath($predir=0){
	$pathdir=pathinfo($_SERVER['SCRIPT_NAME']);
	$pathdir=$pathdir['dirname'];
	$pathdir=$pathdir=='\\'?'':$pathdir;
	if($predir<0 && $pathdir!=''){
		$s=explode('/', $pathdir);
		$pathdir='';
		for($n=0;$n<count($s)+$predir; $n++){
			if(empty($s[$n]))continue;
			$pathdir.='/'.$s[$n];
		}
	}
	$url="http://{$_SERVER['HTTP_HOST']}{$pathdir}";
	return $url;
}

//底部
function footer($f=true){
	global $timer_begin;
	global $db;
	global $cache_global;
	global $cache_settings;
	global $webcore;
	$times=$db->query_count();
	$time_now=getDateStr(time(),false,false);
	$timer_end=getmicrotime();
	$timer_run = round($timer_end-$timer_begin,6);
	if($f){
		require_once getTemplatePath('footer.htm');
	}
	ob_end_flush();
	exit;
}


function _vars_($output){
	global $cache_templatevars;
	$rkey=array();
	$rvalue=array();
	foreach($cache_templatevars as $cache_templatevar){
		if(empty($cache_templatevar['tkey'])){continue;}
		array_push($rkey,'{'.$cache_templatevar['tkey'].'}');
		array_push($rvalue,$cache_templatevar['tvalue']);
	}
	$output = str_replace($rkey, $rvalue, $output);
	return $output;
}

function _clear_($output){
	$output = str_replace(array('<!--<!--<!---->','<!---->-->','<!--<!---->',"<!---->\r\n",'<!---->','<!-- -->',"\t\t\t"),'',$output);
	$output = preg_replace("/\n\s*\r/is", "", $output);
	return $output;
}


function succeedFlag($str=''){
	exit(SUCCEED_FLAG.(empty($str)?"":($str.SUCCEED_FLAG)));
}

//代替pathinfo函数，解决中文漏洞。
function pathinfo_($filename){
	$last_=strrpos($filename, '/');
	$arr['dirname']= $last_?substr($filename, 0, $last_):'.'; 
	$arr['basename'] = $last_?substr($filename, $last_ + 1):$filename; 
	$arr['extension'] = substr(strrchr($filename, '.'), 1); 
	$arr['filename'] = substr($arr['basename'], 0, strrpos($arr['basename'], '.'));
	return $arr;
}


function isImg($filename){
	if(empty($filename)){return false;}
	$imgext=array('jpeg','jpg','gif','png','bmp');
	$fileinfo=pathinfo($filename);
	return in_array(strtolower($fileinfo['extension']),$imgext);
}

//消息通知
function printMsg($code){
	ob_clean();
	$msg_code = $code;
	require_once('printmsg.php');
	exit();
}

//语言
function _LANG($str, $arr){
	if(empty($arr))return '';
	for($k=0;$k<count($arr);$k++){
		$str=str_replace('{'.$k.'}',$arr[$k],$str);
	}
	return $str;
}


function exitRes($str="..."){
print<<<EOT
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>
		.succeedtips{line-height:180%; padding:10px; color:#333; font-size:12px; text-align:center; border-bottom:1px dotted #c0c0c0; width:800px; margin:auto;}
		.succeedtips a:link,.succeedtips a:active,.succeedtips a:visited,.succeedtips a:hover{color:#333;}
	</style>
	</head>
	<body>
	<div class="succeedtips"><b>{$str}</b></div>
	</body>
	</html>
EOT;
	exit();
}

?>