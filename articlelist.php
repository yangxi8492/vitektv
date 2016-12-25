<?php
require_once('./inc/init.php');
require_once('./inc/pager.php');

$channelid=intval($_GET['cid']);
if(!empty($channelid)){
	$channel=$cache_channels[$channelid];
	$headtitle=(empty($channel['seotitle'])?strip_tags($channel['title']):strip_tags(str_replace(array("\r", "\n"), array('', ''), $channel['seotitle'])));
	$headkeywords=(empty($channel['metakeywords'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $channel['metakeywords'])));
	$headdesc=(empty($channel['metadesc'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $channel['metadesc'])));
}
empty($channel)&&$webcore->checkViewLang('articlelist',$channelid);

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
		$class = 'big2';
		if($channelid == $childcid){
		    $class = 'big1';
		}
		$secmenu.="<li class='".$class."'><a href=\"".$webcore->genNavLink($tmpchannel)."\">{$tmpchannel['title']}</a></li>";
	}
}else{
	$par_channel=$channel;
}


$condition=empty($channelid)?"":"channelid={$channelid}";
$orderstr="id desc";
$curPage = intval($_GET["page"]);
$pagerlink="articlelist.php?cid={$channelid}";
$pagerlink.="&page={page}";
$pagerlink=$webcore->genUrl($pagerlink);
$pager = new Pager();
$pager->init(intval($cache_settings['perpageart']),$curPage,$pagerlink);
$articles = $pager->queryRows($db,"articles",$condition, "*",$orderstr);
foreach($articles as $key=>$article){
	$article['link']=$webcore->genUrl("view.php?id={$article['id']}");
	$article['title']=htmlFilter($article['title']);
	$article['picpath']=$webcore->getPicPath($article['picpath'],true,true);
	$article['posttime']=getDateStr($article['posttime'],false,0);
	$article['metadesc']=htmlFilter($article['metadesc']);
	$articles[$key]=$article;
}

$_SYS['positionchannel']=" > <a href=".$webcore->genUrl("articlelist.php?cid={$channel['id']}").">{$channel['title']}</a>";
require_once('./header.php');
require_once getTemplatePath('articlelist.htm');
footer();
?>