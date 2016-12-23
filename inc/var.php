<?php
//常量
define('GROUP_GUESS', 101);
define('GROUP_NOVERIFY', 102);
define('GROUP_NOPOST', 103);
define('GROUP_NOVISIT', 104);
define('GROUP_VERIFYFAILED', 105);
define('GROUP_FORUMADMIN', 201);
define('GROUP_SUPERADMIN', 202);
define('GROUP_ADMIN', 203);
define('GROUP_VIP', 204);
define('SUCCEED_FLAG','_Y_');
define('ADMIN_DIR','admin');
define('FULL_VERSION',0);


$_SYS['cookiepre']		= "w6z_";					//cookies 前缀
$_SYS['cookiedomain']	= "";							//cookies 作用域
$_SYS['cookiepath']		= "/";							//cookies 作用路径
$_SYS['sessionpre']		= "z_";							//session 前缀
$_SYS['styleid']		= "6kzz";						//默认模板
$_SYS['IMGP']			= "images";						//系统图片路径
$_SYS['TP']				= "template/".$_SYS["styleid"];	//模板路径
$_SYS['VERSION']		= "20110519";						//css,js文件版本
$_SYS['time']			= time();
?>