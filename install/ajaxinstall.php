<?php
error_reporting(E_ALL ^ E_NOTICE);
@set_time_limit(0);
define('INC_INSTALL',dirname(__FILE__));

include_once('../inc/cache.php');
include_once('../admin/inc/adminfun.php');

$action=$_GET["action"];
if(file_exists('install.lock')){
	exit('6KZZ快站已经安装完毕。如果要重新安装，请删除本目录的install.lock文件。');
}
switch($action){
	
	case "setChmod":
		//TODO:777
		$chmoddirs=array('../inc/config.php', '../cache', '../install', '../uploadfile');
		foreach($chmoddirs as $chmoddir){
			if(!is_writable($chmoddir)){
				echo("没有写的权限:{$chmoddir}<br />");
			}
		}

		$dirs=array('../uploadfile/attachment');
		foreach($dirs as $dir){
			create($dir);
			if(!file_exists($dir)){
				echo("文件夹建立失败:{$dir}<br />");
			}
		}
		exit('_Y_');
	break;
	
	case "testConnect":
		$settings=$_POST['settings'];
		if(!is_array($settings)){
			exit("参数错误");
		}
		$conn = @mysql_connect($settings['dbserver'],$settings['dbuser'],$settings['dbpass']);
		if($conn)
		{
			exit('_Y_');
		}else{
			exit("X 数据库连接失败！");
		}

	break;

	case "genData":
		$settings=$_POST['settings'];
		if(!is_array($settings)){
			exit("参数错误");
		}
		$conn = @mysql_connect($settings['dbserver'],$settings['dbuser'],$settings['dbpass']);
		@mysql_unbuffered_query("SET NAMES 'utf8'");
		if($conn)
		{
			$dbname=$settings['dbname'];
			if (mysql_select_db($dbname,$conn))  
			{
				//echo("Selected ".$dbname);
			}  
			else  
			{
				mysql_query("CREATE DATABASE `".$dbname."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");
				//echo("DO:------CREATE ".$dbname."-------<br />");
				$dberror = mysql_error();
				if(!empty($dberror)){
					exit("数据库{$dbname}建立失败。\r\n".$dberror);
				}
			}

			populate_db($dbname, $settings['dbpre'], 'data.sql' );
			if(!empty($errors)){
				echo("数据表建立时发生".count($errors)."个异常。\r\n");
				foreach($errors as $err){
					echo($err[0]."\r\n");
				}
				exit();
			}

			if(intval($settings['testdata'])==1){
				//TODO:安装测试数据
				populate_db($dbname, $settings['dbpre'], 'testdata.sql' );
				if(!empty($errors)){
					echo("安装测试数据时发生".count($errors)."个异常。\r\n");
					foreach($errors as $err){
						echo($err[0]."\r\n");
					}
					exit();
				}
			}

			$content = file_get_contents("../inc/config.php");
			$content = preg_replace('/\/\*DB_SETTING_BEGIN\*\/(.+?)\/\*DB_SETTING_END\*\//is', getdbsetting($settings), $content);
			writeFile('../inc/config.php',$content);

			exit('_Y_');
		}else{
			exit("X 数据库连接失败！请检查参数是否填写正确。");
		}
	break;

	case "addAdmin":
		$settings=$_POST['settings'];
		if(!is_array($settings)){
			exit("参数错误");
		}
		//TODO:addAdmin
		if(!isValidName($settings['username'])){
			exit("您输入的管理员帐号不是有效的用户名。");
		}
		if(!isValidEmail($settings['email'])){
			exit("邮箱格式错误。");
		}
		include_once('../inc/var.php');
		include_once('../inc/config.php');
		include_once('../inc/db_mysql.php');

		$db = new db();
		$db->connect($_DB);

		$user['username'] = $settings['username'];
		$user['userpass'] = encrypt($settings['username'],$settings['userpass']);
		$user['email'] = $settings['email'];
		$user['addtime'] = time();
		$user['popedom']='|channel|page|article|procate|products|order|member|main|lang|template|link|msg|vote|user|database|';
		$row=$db->row_select_one("users","username='{$settings['username']}'");
		if(!empty($row)){
			exit("用户名已经被使用。");
		}
		$db->row_insert("users",$user);
		$salt=md5(time().$user['username'].$user['userpass']);
		$db->query_unbuffered("DELETE FROM `{$db->pre}settings` WHERE property='salt' and langid=0");
		$db->query_unbuffered("INSERT INTO `{$db->pre}settings` (property, setvalue, langid) VALUES ('salt','{$salt}',0)");

		global $_SYS;
		$_SYS['alangid']=1;
		writeGlobalCache();
		writeSettingsCache();
		writeLinksCache();
		writeChannelsCache();
		writeProductsCateCache();
		writeLangsCache();
		writeUsersCache();
		writeContactCache();
		writeVotesCache();
		writeFoldersCache();

		exit('_Y_');
	break;

	case "finishInstall":
		writeFile("install.lock","");
		exit('_Y_');
	break;

	default:
		echo"No Such Action";
	break;
}

$errors=array();
function populate_db( $DBname, $DBPrefix, $sqlfile ) {  
    global $errors;  
    @mysql_select_db($DBname);  
    $mqr = @get_magic_quotes_runtime();  
    @magic_quotes_runtime(0);  
    $query = fread(fopen($sqlfile, "r"), filesize($sqlfile));  
    @magic_quotes_runtime($mqr);  
    $pieces  = split_sql($query);  
  
    for ($i=0; $i<count($pieces); $i++) {  
        $pieces[$i] = trim($pieces[$i]);  
        if(!empty($pieces[$i]) && $pieces[$i] != "#") {  
            $pieces[$i] = str_replace( "#__", $DBPrefix, $pieces[$i]);  
            if (!$result = @mysql_query ($pieces[$i])) {  
                $errors[] = array ( mysql_error(), "LINE {$i}:".$pieces[$i] );  
            }  
        }  
    }  
}  
  
function split_sql($sql) {  
    $sql = trim($sql);  
    $sql = preg_replace("/\n#[^\n]*\n/", "\n", $sql);  
  
    $buffer = array();  
    $ret = array();  
    $in_string = false;  
  
    for($i=0; $i<strlen($sql)-1; $i++) {  
        if($sql[$i] == ";" && !$in_string) {  
            $ret[] = substr($sql, 0, $i);  
            $sql = substr($sql, $i + 1);  
            $i = 0;  
        }  
  
        if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {  
            $in_string = false;  
        }  
        elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {  
            $in_string = $sql[$i];  
        }  
        if(isset($buffer[1])) {  
            $buffer[0] = $buffer[1];  
        }  
        $buffer[1] = $sql[$i];  
    }  
  
    if(!empty($sql)) {  
        $ret[] = $sql;  
    }  
    return($ret);  
}  


function getdbsetting($settings){
	$str.="";
	$str.="/*DB_SETTING_BEGIN*/\r\n";
	$str.=<<<EOT
\$_DB['type']='mysql';
\$_DB['hostname']='{$settings[dbserver]}';
\$_DB['username']='{$settings[dbuser]}';
\$_DB['password']='{$settings[dbpass]}';
\$_DB['database']='{$settings[dbname]}';
\$_DB['charset']='utf8';
\$_DB['prefix']='{$settings[dbpre]}';
EOT;
	$str.="\r\n/*DB_SETTING_END*/";
	return $str;
}

//encrypt
function encrypt($username,$userpass){
	$str=$username.$userpass;
	return empty($str)?'':md5(strtolower($str));
}


function getCacheFilePath($filename,$lang=0){
	$filepath=INC_INSTALL."/../cache/";
	if($lang==0){
		$filepath.=$filename;
	}else{
		$filepath.=$lang."/".$filename;
	}
	return $filepath;
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

function create($dir)
{
	if (!is_dir($dir))
	{
		$temp = explode('/',$dir);
		$cur_dir = '';
		for($i=0;$i<count($temp);$i++)
		{
			$cur_dir .= $temp[$i].'/';
			if (!is_dir($cur_dir))
			{
				@mkdir($cur_dir,0777);
				@fopen("$cur_dir/index.htm","a");
			}
		}
	}
}

?>
