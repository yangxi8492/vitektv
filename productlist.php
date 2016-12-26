<?php
require_once('./inc/init.php');
require_once('./inc/pager.php');
//product channel
foreach($cache_channels as $cache_prochannel){
	if($cache_prochannel['systemtype']=='1'){
		break;
	}
}
$k=trim($_GET['k']);
$curPage = intval($_GET["page"]);
$cid = intval($_GET["cid"]);
$pagerlink=$webcore->genUrl("productlist.php?cid={$cid}&page={page}".(empty($k)?"":"&k={$k}"));

$condition="langid={$_SYS['langid']}";
$condition.=empty($k)?"":" and name like '%{$k}%'";
$condition.=empty($cid)?"":" and cid in (select id from {$db->pre}procates where id={$cid} or pid={$cid})";

$orderstr="ordernum,id desc";

//Banner获取
$bannerad="";
for($b=1;$b<6;$b++){
    if(intval($cache_settings["probanner".$b])>0){
        //$probannerad.='<a href="'.$cache_settings['probannerlink'.$b].'" class="d1" style="background:url('.$webcore->getPicPath($cache_settings['probannerpath'.$b]).') center no-repeat;background-size: 100% 594px"></a>';
        $probannerad.='<li style="background: url('.$webcore->getPicPath($cache_settings['probannerpath'.$b]).') no-repeat scroll center 0px;background-size: 100% 594px position: absolute; left: 0px; top: 0px; display: none;"><a target="_blank" href="'.$cache_settings['probannerlink'.$b].'"></a></li>';
    }
}

$pager = new Pager();
$pager->init(intval($cache_settings['perpagepro']),$curPage,$pagerlink);
$products = $pager->queryRows($db,"products",$condition, "id,cid,type,hits,posttime,alias,name,price1,picids,picpaths",$orderstr);
foreach($products as $key=>$product){
	$product['link']=$webcore->genUrl("product.php?id={$product['id']}");
	$product['name']=htmlFilter($product['name']);
	$product['price1']=number_format($product['price1'],2);
	$protmppic=$webcore->getPics($product['picids'],$product['picpaths'],0,true,true);
	$product['picpath']=$protmppic['picpath'];
	$products[$key]=$product;
}
unset($protmppic);

if(!empty($cid)){
	$procate=$cache_procates[$cid];
	empty($procate)&&$webcore->checkViewLang('productlist',$cid);

	$headtitle=(empty($procate['seotitle'])?strip_tags($procate['title']):strip_tags(str_replace(array("\r", "\n"), array('', ''), $procate['seotitle'])));
	$headkeywords=(empty($procate['metakeywords'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $procate['metakeywords'])));
	$headdesc=(empty($procate['metadesc'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $procate['metadesc'])));
	$_SYS['positionchannel']=" » <a href=".$webcore->genUrl("productlist.php?cid={$procate['id']}").">{$procate['title']}</a>";
}else{
	$headtitle=(empty($cache_prochannel['seotitle'])?strip_tags($cache_prochannel['title']):strip_tags(str_replace(array("\r", "\n"), array('', ''), $cache_prochannel['seotitle'])));
	$headkeywords=(empty($cache_prochannel['metakeywords'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $cache_prochannel['metakeywords'])));
	$headdesc=(empty($cache_prochannel['metadesc'])?$headtitle:strip_tags(str_replace(array("\r", "\n"), array('', ''), $cache_prochannel['metadesc'])));
	$_SYS['positionchannel']=" » <a href=".$webcore->genUrl("productlist.php").">{$headtitle}</a>";
}


if(intval($procate['pid'])>0){
	$tmpcate=$cache_procates[$procate['pid']];
	$headtitle.=" - ".strip_tags($tmpcate['title']);
	$_SYS['positionchannel']=" » <a href=".$webcore->genUrl("productlist.php?cid={$tmpcate['id']}").">{$tmpcate['title']}</a>".$_SYS['positionchannel'];	
}


require_once('./header.php');
require_once getTemplatePath('productlist.htm');
footer();
?>