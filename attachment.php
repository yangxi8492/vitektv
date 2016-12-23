<?php
error_reporting(E_ALL ^ E_NOTICE);
session_cache_limiter('public');
define('ATT_PATH',dirname(__FILE__));
require_once(ATT_PATH.'/inc/init.php');

$host_referer = parse_url($_SERVER['HTTP_REFERER']);
$host_server = $_SERVER['HTTP_HOST'];
if(($pos = strpos($host_server, ':')) !== FALSE) {
	$host_server = substr($host_server, 0, $pos);
}
if( $_SERVER['HTTP_REFERER'] && !($host_referer['host'] == $host_server)) {
	exitRes($_SLANG['attachment.referr']);
}

$id = empty($_GET['id']) ? 0 : intval($_GET['id']);

if(empty($id)){
	exitRes($_SLANG['attachment.fileid.ne']);
}

$row=$db->row_select_one("attachments","id={$id}");
if(empty($row)){
	exitRes($_SLANG['attachment.file.ne']);
}

$filepath="uploadfile/attachment/".$row['filepath'];

//if is image and redirect
//if(intval($_GET['r'])==1 && $row['type']==1){
if($row['type']==1){
	if(!file_exists(ATT_PATH."/".$filepath)){
		exitRes($_SLANG['attachment.filedel']);
	}
	$filepath=getUrlPath().'/'.$filepath;
	$filepath=preg_replace('/\/'.ADMIN_DIR.'\//i','/',$filepath);
	//ob_end_clean();
	header("HTTP/1.1 301 Moved Permanently");
	header("Last-Modified:".date('r'));
	header("Expires: ".date('r', time() + 86400));
	header("Location:{$filepath}");
	exit();
}

$filepath=ATT_PATH."/uploadfile/attachment/".$row['filepath'];
$filename=stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')?urlencode($row['filename']):$row['filename'];


ob_end_clean();
_header_('Content-Encoding: none');
_header_('Content-Type: application/octet-stream');
_header_('Content-Disposition: attachment; filename="'.$filename.'"');
_header_('Content-Length: '.filesize($filepath));


getlocalfile($filepath,1);


function getlocalfile($filename, $readmod = 2, $range = 0) {
	if($readmod == 1 || $readmod == 3 || $readmod == 4) {
		if($fp = @fopen($filename, 'rb')) {
			@fseek($fp, $range);
			if(function_exists('fpassthru') && ($readmod == 3 || $readmod == 4)) {
				@fpassthru($fp);
			} else {
				echo @fread($fp, filesize($filename));
			}
		}
		@fclose($fp);
	} else {
		@readfile($filename);
	}
	@flush(); @ob_flush(); @ob_end_flush();
}
?>