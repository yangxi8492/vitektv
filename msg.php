<?php
require_once('./inc/init.php');
require_once('./inc/pager.php');

//查找在线留言的缓存
foreach($cache_channels as $cache_msgchannel){
	if($cache_msgchannel['systemtype']=='3'){
		break;
	}
}
$channelid=intval($cache_msgchannel['id']);
if(!empty($channelid)){
	$channel=$cache_channels[$channelid];
	$headtitle=(empty($channel['seotitle'])?strip_tags($channel['title']):strip_tags(str_replace(array("\r", "\n"), array('', ''), $channel['seotitle'])));
	$headkeywords=(empty($channel['metakeywords'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $channel['metakeywords'])));
	$headdesc=(empty($channel['metadesc'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $channel['metadesc'])));
}

$secmenu='';
foreach($channel['childcid'] as $childcid){
	$tmpchannel=$cache_channels[$childcid];
	if($tmpchannel['ishidden']=='1'){
		continue;
	}
	$secmenu.="<li class='big'><a href=\"".$webcore->genNavLink($tmpchannel)."\">{$tmpchannel['title']}</a></li>";
}
if(intval($channel['pid'])>0){
	$par_channel=$cache_channels[$channel['pid']];
	foreach($cache_channels[$channel['pid']]['childcid'] as $childcid){
		$tmpchannel=$cache_channels[$childcid];
		if($tmpchannel['ishidden']=='1'){
			continue;
		}
		$secmenu.="<li class='big'><a href=\"".$webcore->genNavLink($tmpchannel)."\">{$tmpchannel['title']}</a></li>";
	}
}else{
	$par_channel=$channel;
}

$msgkey=$_GET['msgkey'];

$curPage = intval($_GET["page"]);

$condition="langid={$_SYS['langid']} and state=1";
$condition.=empty($msgkey)?"":" and (name like '%{$msgkey}%' or title like '%{$msgkey}%' or remark like '%{$msgkey}%' or reply like '%{$msgkey}%')";

$orderstr="id desc";

$pagerlink=$webcore->genUrl("msg.php?page={page}".(empty($msgkey)?"":"&msgkey={$msgkey}"));
$pager = new Pager();
$pager->init(intval($cache_settings['perpagemsg']),$curPage,$pagerlink);
$msgs = $pager->queryRows($db,"msgs",$condition, "*",$orderstr);
$index=0;
foreach($msgs as $key=>$msg){
	$msg['mod']=(++$index)%2;
	$msg['name']=htmlFilter($msg['name']);
	$msg['email']=htmlFilter($msg['email']);
	$msg['contact1']=htmlFilter($msg['contact1']);
	$msg['title']=htmlFilter($msg['title']);
	$msg['remark']=nl2br(htmlFilter($msg['remark']));
	$msg['posttime']=getDateStr($msg['posttime']);
	$msg['replytime']=getDateStr($msg['replytime']);
	$msgs[$key]=$msg;
}

$_SYS['positionchannel']=" » <a href=".$webcore->genUrl("msg.php").">{$channel['title']}</a>";

$msgkey=empty($msgkey)?$_LANG['header.search']:htmlFilter($msgkey);
require_once('./header.php');
require_once getTemplatePath('msg.htm');
footer();
?>