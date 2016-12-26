<?php
require_once('./inc/init.php');
require_once('./inc/pager.php');
require_once('./inc/parsefile.php');

$id=intval($_GET['id']);
$row=$db->row_select_one("products","id='{$id}' and langid={$_SYS['langid']}");
if(!empty($row)){
	$id=$row['id'];
	$db->row_query("update {$db->pre}products set hits=hits+1 where id={$id}");
	$row['posttime']=getDateStr($row['posttime'],0,0);
	$row['name']=htmlFilter($row['name']);
	$row['serialnum']=htmlFilter($row['serialnum']);
	$row['price1']=number_format($row['price1'],2);
	$row['content']=$parsefile->parse($row['content']);
	$row['smallimages']='';
	$pics=$webcore->getPics($row['picids'],$row['picpaths'],-1,true,true);
	foreach($pics as $pic){
		//$row['smallimages'].=intval($pic['picid'])>0?"<li id=\"liimg_{$pic['picpath']}\"><img src=\"{$pic['picpath']}\" /></li>":"";
		$row['images'].=intval($pic['picid'])>0?'<li><a href="javascript:;"><img src="'.$pic['picpath'].'" width="480" height="330" /></a></li>':"";
		$row['smallimages'].=intval($pic['picid'])>0?'<li><img src="'.$pic['picpath'].'" width="75" height="50" /></li>':"";
	}
	$row['picpath']=$pics[0]['picpath'];
	unset($pics);
}
empty($row)&&$webcore->checkViewLang('product',$id);


$cid=$row['cid'];
$procate=$cache_procates[$cid];

$_SYS['positionchannel']=" » <a href=".$webcore->genUrl("productlist.php?cid={$procate['id']}").">{$procate['title']}</a>";
$headtitle=(empty($row['seotitle'])?strip_tags($row['name']):strip_tags(str_replace(array("\r", "\n"), array('', ''), $row['seotitle'])));
$headtitle.=" - {$procate['title']}";
$headkeywords=(empty($row['metakeywords'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $row['metakeywords'])));
$headdesc=(empty($row['metadesc'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $row['metadesc'])));

if(intval($procate['pid'])>0){
	$tmpcate=$cache_procates[$procate['pid']];
	$headtitle.=" - ".strip_tags($tmpcate['title']);
	$_SYS['positionchannel']=" » <a href=".$webcore->genUrl("productlist.php?cid={$tmpcate['id']}").">{$tmpcate['title']}</a>".$_SYS['positionchannel'];
}


require_once('./header.php');

require_once getTemplatePath('product.htm');
footer();

?>