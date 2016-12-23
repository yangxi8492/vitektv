<?php
if(file_exists('install') && !file_exists('install/install.lock')){
	header("location:install");
	exit();
}

require_once('./inc/init.php');
if($_GET['langid']){
	$refer=$_SERVER['HTTP_REFERER'];
	if(!empty($_GET['preview']) || !stristr($refer, $_SERVER['HTTP_HOST']) || !stristr($refer, 'admin/')){
		$refer="index.php";
	}
	$refer=empty($refer)?"index.php":$refer;
	_header_("location:{$refer}");
	exit();
}

if($_GET['alangid']){
	_header_("location:admin/index.php");
	exit();
}

//程序实现伪静态
if($cache_settings['urlrewrite']=='1'){
	$uri_=$_SERVER["REQUEST_URI"];
	$uri_parm=array();
	$p = '/\?(.*).html/isU';
	preg_match($p, $uri_, $r); 
	$uri_ = $r[1];
	$parms_vars = explode("-",$uri_);
	switch($parms_vars[0]){
		
		case 'articlelist':
			if(!empty($parms_vars[1])){$_GET['cid']=intval($parms_vars[1]);}
			if(!empty($parms_vars[2])){$_GET['page']=intval($parms_vars[2]);}
			require_once('./articlelist.php');
			exit();
		break;

		case 'view':
			if(!empty($parms_vars[1])){$_GET['id']=intval($parms_vars[1]);}
			if(!empty($parms_vars[2])){$_GET['page']=intval($parms_vars[2]);}
			require_once('./view.php');
			exit();
		break;

		case 'product':
			if(!empty($parms_vars[1])){$_GET['id']=intval($parms_vars[1]);}
			if(!empty($parms_vars[2])){$_GET['page']=intval($parms_vars[2]);}
			require_once('./product.php');
			exit();
		break;

		case 'productlist':
			if(!empty($parms_vars[1])){$_GET['cid']=intval($parms_vars[1]);}
			if(!empty($parms_vars[2])){$_GET['page']=intval($parms_vars[2]);}
			require_once('./productlist.php');
			exit();
		break;

		case 'msg':
			if(!empty($parms_vars[1])){$_GET['cid']=intval($parms_vars[1]);}
			if(!empty($parms_vars[2])){$_GET['page']=intval($parms_vars[2]);}
			require_once('./msg.php');
			exit();
		break;

		case 'contact':
			require_once('./contact.php');
			exit();
		break;

		case 'page':
			if(!empty($parms_vars[1])){$_GET['cid']=intval($parms_vars[1]);}
			if(!empty($parms_vars[2])){$_GET['page']=intval($parms_vars[2]);}
			require_once('./page.php');
			exit();
		break;
	}
}
require_once('./main.php');
?>