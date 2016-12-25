<?php
error_reporting(E_ALL ^ E_NOTICE);
if ( isCached() )return;

define('INC_P',dirname(__FILE__));
if(function_exists('ob_gzhandler')){
	ob_start('ob_gzhandler');
}else{
	ob_start();
}

	$types = Array();
	$types["css"]["path"] = "css/";
	$types["css"]["ext"] = ".css";
	$types["css"]["contentType"] = "text/css; charset=UTF-8";
	$types["templatecss"]["path"] = "";
	$types["templatecss"]["ext"] = ".css";
	$types["templatecss"]["contentType"] = "text/css; charset=UTF-8";
	$types["js"]["path"] = "js/";
	$types["js"]["ext"] = ".js";
	$types["js"]["contentType"] = "text/javascript; charset=UTF-8";
	$files = $_GET['f'];
	$type =  $_GET['t'];


	if ( empty($type) || empty($files) )
	{
		header ("HTTP/1.0 404", true, 404);
		return;
	}

	if ( $type=='css' )
	{
		$config = $types["css"];
	}
	elseif ( $type=='js')
	{
		$config = $types["js"];
	}
	elseif ( $type=='templatecss')
	{
		$types["templatecss"]["path"] = "template/".str_replace(array("'","/","\/","\"","."),array('','','','',''),$_GET['s'])."/";
		$config = $types["templatecss"];
	}
	else
	{
		header ("HTTP/1.0 404", true, 404);
		return;
	}

	$tenyears = date("D, j M Y H:i:s T",time()  + 60 * 60 * 24 * 365 * 10 );
	header("Content-disposition: attachment; filename=".$files.$config["ext"]);
	header("Expires: $tenyears" ,true);
	header("Last-Modified: ". date("D, j M Y H:i:s T") ,true);
	header("Content-Type: " .  $config["contentType"] ,true);
	header("Cache-Control: max-age=315360000",true);
	header("Age: 87000",true);

	$fs = preg_split("/\|/",str_replace(array("'","/","\/","\"","."),array('','','','',''),$files));
	for ( $i = 0 ; $i<count($fs) ; $i++ )
	{
		@include($config["path"] . $fs[$i] . $config["ext"]);
		print "\n";
	}

	function isCached ()
	{return false;
		$tmp = getRequestHeader("HTTP_PRAGMA");
		if ( strcasecmp($tmp,"no-cache") == 0  )
		{
			return false;
		}

		$tmp = getRequestHeader("HTTP_CACHE_CONTROL");
		if ( strcasecmp($tmp,"no-cache") == 0  )
		{
			return false;
		}

		$tmp = getRequestHeader("HTTP_IF_MODIFIED_SINCE" );
		if (!is_null ($tmp))
		{
			header ("HTTP/1.0 304 Not Modified", true, 304);
			return true;
		}
		else
			return false;
	}

	function getRequestHeader($name)
	{
		if ( ! array_key_exists($name,$_SERVER))
			return null;
		return trim($_SERVER[$name]);
	}

ob_end_flush();
?>