<?php
require_once('./inc/init.php');
require_once('./inc/parsefile.php');

empty($cid)&&($cid=intval($_GET['cid']));
if(!empty($cid)){
	$channel=$cache_channels[$cid];
	$headtitle=(empty($channel['seotitle'])?strip_tags($channel['title']):strip_tags(str_replace(array("\r", "\n"), array('', ''), $channel['seotitle'])));
	$headkeywords=(empty($channel['metakeywords'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $channel['metakeywords'])));
	$headdesc=(empty($channel['metadesc'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $channel['metadesc'])));
}
empty($channel)&&$webcore->checkViewLang('page',$cid);

$pagerow=$db->row_select_one("channels","id={$cid}");
$pagecontent=$pagerow['systemtype']==2?$widget_contact:$parsefile->parse($pagerow['content']);
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
		if($cid == $childcid){
		    $class = 'big1';
		}
		$secmenu.="<li class='".$class."'><a href=\"".$webcore->genNavLink($tmpchannel)."\">{$tmpchannel['title']}</a></li>";
	}
}else{
	$par_channel=$channel;
}
$_SYS['positionchannel']=" » <a href=".$webcore->genUrl("page.php?cid={$pagerow['id']}").">{$pagerow['title']}</a>";
require_once('./header.php');
require_once getTemplatePath('page.htm');
footer();
?>