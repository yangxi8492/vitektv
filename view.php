<?php
require_once('./inc/init.php');
require_once('./inc/pager.php');
require_once('./inc/parsefile.php');

$id=intval($_GET['id']);
$_SYS['articleurl']=$webcore->genUrl("view.php?id={$id}");
$row=$db->row_select_one("articles","id={$id} and langid={$_SYS['langid']}");
if(!empty($row)){
	$db->row_query("update {$db->pre}articles set hits=hits+1 where id={$id}");
}
empty($row)&&$webcore->checkViewLang('view',$id);


$channelid=$row['channelid'];

if(!empty($channelid)){
	$channel=$cache_channels[$channelid];
	$headtitle=empty($row['seotitle'])?strip_tags($row['title']):strip_tags(str_replace(array("\r", "\n"), array('', ''), $row['seotitle']));
	$headtitle.=" - {$channel['title']}";
	$headkeywords=empty($row['metakeywords'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $row['metakeywords']));
	$headdesc=empty($row['metadesc'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $row['metadesc']));
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
		$class = 'big2';
		if($channelid == $childcid){
		    $class = 'big1';
		}
		$secmenu.="<li class='".$class."'><a href=\"".$webcore->genNavLink($tmpchannel)."\">{$tmpchannel['title']}</a></li>";
	}
}else{
	$par_channel=$channel;
}
$row['posttime']=getDateStr($row['posttime'], false, 0);
$row['content']=$parsefile->parse($row['content']);

$_SYS['positionchannel']=" » <a href=".$webcore->genUrl("articlelist.php?cid={$channel['id']}").">{$channel['title']}</a>";
require_once('./header.php');
require_once getTemplatePath('view.htm');
footer();

?>