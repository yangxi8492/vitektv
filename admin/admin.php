<?php
require_once("./../inc/init.php");
require_once("./inc/adminfun.php");
require_once("./language/language.php");
if(!isAdmin()){
	_header_("location:login.php?ref=".urlencode($_SERVER['REQUEST_URI']) );
}
$inc=strFilter($_GET['inc']);
$action=strFilter($_GET['action']);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="css/pager.css" rel="stylesheet" type="text/css" />
<link href="css/global.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../getfiles.php?t=js&v=<?php echo $_SYS['VERSION'];?>&f=tab|util|ajax|choosedate|color|jquery|admin"></script>
<script type="text/javascript" src="../inc/kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="../js/admin.js"></script>
<script type="text/javascript">
var popwin = window.parent.popwin;
</script>
<style>
	body{margin:10px;}
</style>
</head>
<body>
<?php
$inc_arr =	array(
'channel',
'procate',
'products',
'lang',
'main',
'template',
'link',
'user',
'database',
'article',
'page',
'msg',
'vote',
'member',
'order');

$incname_arr =array(
$_AL['index.channel.man'],
$_AL['index.procate.list'],
$_AL['index.product.list'],
$_AL['index.language.set'],
$_AL['index.site.set'].','.$_AL['index.banner.set'].','.$_AL['index.contact.set'].','.$_AL['index.sitecache.set'].','.$_AL['index.email.set'].','.$_AL['index.site.fun'],
$_AL['index.template.set'],
$_AL['index.frilink.list'],
$_AL['index.admin.set'],
$_AL['index.data.man'],
$_AL['index.article.man'],
$_AL['index.page.man'],
$_AL['index.msg.list'],
$_AL['index.vote.list'],
$_AL['index.member.man'],
$_AL['index.order.man']);

foreach($inc_arr as $key=>$incpage){
	if($inc==$incpage){
		//Channel. Read Only.
		if($incpage!='channel'){
			if(!hasPopedom($incpage)){
				printRes(_LANG($_AL['admin.nopopedom'],array($incname_arr[$key])));
			}
		}
		require_once($inc.".php");
		break;
	}
}
?>
</body>
</html>
