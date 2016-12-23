<?php
require_once('./inc/init.php');
require_once('./inc/pager.php');

$k=trim($_GET['k']);
$curPage = intval($_GET["page"]);
$pagerlink=$webcore->genUrl("search.php?page={page}".(empty($k)?"":"&k={$k}"));

$tempsql.="
 (
	SELECT 1 as rtype,id,name as title,posttime,content FROM {$db->pre}products WHERE langid={$_SYS['langid']} and (name like '%{$k}%' or content like '%{$k}%')
	UNION ALL
	SELECT 2 as rtype,id,title,posttime,content FROM {$db->pre}articles WHERE langid={$_SYS['langid']} and (title like '%{$k}%' or content like '%{$k}%')
	UNION ALL
	SELECT 3 as rtype,id,title,null as posttime,content FROM {$db->pre}channels WHERE langid={$_SYS['langid']} and (content like '%{$k}%' or content like '%{$k}%')
) TEMPTB
";

$recordnum=$db->row_query_one("SELECT count(1) as total FROM {$tempsql}");
$recordnum=intval($recordnum['total']);
$recordnumstr=_LANG($_SLANG['search.total'],array($recordnum));

$pager = new Pager();
$pager->init(10,$curPage,$pagerlink);
$res = $pager->queryRowsBySQL($db,"SELECT * FROM {$tempsql} ORDER BY posttime DESC",$recordnum);
foreach($res as $key=>$rs){
	switch($rs['rtype']){
		case 1:
			$rs['link']=$webcore->genUrl("product.php?id={$rs['id']}");
			$rs['posttime']=getDateStr($rs['posttime']);
			break;
		case 2:
			$rs['link']=$webcore->genUrl("view.php?id={$rs['id']}");
			$rs['posttime']=getDateStr($rs['posttime']);
			break;
		case 3:
			$rs['link']=$webcore->genUrl("page.php?cid={$rs['id']}");
			$rs['posttime']="";
			break;
	}
	$rs['type']=$_SLANG['search.types'][intval($rs['rtype'])];
	$rs['title']=htmlFilter($rs['title']);
	$rs['content']=cutStr(strip_tags($rs['content']),300);
	$rs['title']=preg_replace('/'.$k.'/i',"<u>{$k}</u>",$rs['title']);
	$rs['content']=preg_replace('/'.$k.'/i',"<u>{$k}</u>",$rs['content']);
	$res[$key]=$rs;
}

$headtitle=empty($voterow['title'])?"":strip_tags(str_replace(array("\r", "\n"), array('', ''), $voterow['title']));
$headkeywords=$headtitle;
$headdesc=$headtitle;
$_SYS['positionchannel']=" » {$_SLANG['search.site']}";

require_once('./header.php');
require_once getTemplatePath('search.htm');
footer();
?>