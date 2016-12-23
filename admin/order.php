<?php
require_once('./../inc/pager.php');
$effect_lang=_LANG($_AL['all.set.effect'],array($cache_langs[$_SYS['alangid']]['name']));
$orderstate=$_AL['order.states'];
$orderstatecolor=array('#f60','#36c','#690','#000');
$options='';
foreach($orderstate as $okey=>$os){
	$options.="<option value='{$okey}'>{$os}</option>";
}
(!FULL_VERSION) && $fullversiontips=$_AL['order.fun.ad'];
echo($fullversiontips);

switch($action){

	/************************************** edit BEGIN ************************************************/
	case "edit":
	$id=intval($_GET['id']);
	$row=$db->row_select_one("orders","id={$id}","*");
	$row['createtime']=getDateStr($row['createtime']);

	$odts=$db->row_select("orderdetails","orderid={$id} and langid={$_SYS['alangid']}");
	foreach($odts as $okey=>$odt){
		$odt['proname']=htmlFilter($odt['proname']);
		$odt['displayprice']=number_format($odt['price'],2);
		$odt['itemtotal']=number_format($odt['price']*$odt['pronum'],2);
		$ordertotal+=($odt['price']*$odt['pronum']);
		$odt['prourl']="../product.php?id={$odt['proid']}";
		$odts[$okey]=$odt;
	}

	$udrow['total']=$ordertotal;
	$db->row_update("orders",$udrow, "id={$id}");
	$row['totalexpress']=number_format($ordertotal+$row['expresscharges'], 2);
	$row['total']=number_format($ordertotal, 2);
	$row['expresscharges']=number_format($row['expresscharges'], 2);

	$member['membername']="——";
	if(!empty($row['memberid'])){
		$member=$db->row_select_one("members","id={$row['memberid']}");
		$member['membername']=empty($member)?"{$_AL['order.memberdel']}":"<a href='admin.php?inc=member&action=editmember&uid={$member['id']}'>".htmlFilter($member['membername'])."</a>";
	}


echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<form id="articleform" method="POST" onsubmit="return checkAllAction()" action="order_ajax.php?action=saveorders">
	<table class="table_1">
		<tr><td class="td_0" style="width:500px;">{$_AL['order.number']}/{$_AL['order.time']}:</td><td class=""></td></tr>
		<tr><td class="td_1"><input type="hidden" name="id" id="id" value="{$id}" /><input type="hidden" name="oldstate" id="oldstate" value="{$row['state']}" /><b>{$row['ordernum']}</b> &nbsp; <b class='time'>{$row['createtime']}</b></td><td class="td_2"></td></tr>
		<tr><td class="td_0">{$_AL['order.totalfee']}:</td><td class=""></td></tr>
		<tr><td class="td_1"><b class='time'>{$cache_settings['cur']}{$row['totalexpress']}</b> = <span class='time'>{$cache_settings['cur']}{$row['total']} + {$cache_settings['cur']}{$row['expresscharges']}</span>({$_AL['order.expfee']})</td><td class="td_2"></td></tr>
		<tr><td class="td_0">{$_AL['order.expfee']}:</td><td class=""></td></tr>
		<tr><td class="td_1"><input type="text"  value="{$row['expresscharges']}" name="expresscharges" id="expresscharges"  class="text_css" /> {$cache_settings['cur']}</td><td class="td_2"></td></tr>
		<tr><td class="td_0">{$_AL['order.belong']}:</td><td class=""></td></tr>
		<tr><td class="td_1"><b>{$member['membername']}</b></td><td class="td_2"></td></tr>
	</table>
	<div id="t1">
		<table class="table_1">
			<tr><td class="td_0" style="width:500px;">{$_AL['order.state']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><select name="state" id="state">{$options}</select></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['order.flowdetails']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:80px;width:480px;" name="remark2" id="remark2">{$row['remark2']}</textarea></td><td class="td_2">{$_AL['order.flowdetails.remark']}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="submit" value="  {$_AL['all.submit']}  " /></td><td class=""></td></tr>
		</table>
	</div>
	<div id="t2">
		<table class="table_1">
			<tr><td class="td_0" style="width:500px;">{$_AL['order.consignee']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text"  value="{$row['name']}" name="name" id="name"  class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['order.phone']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text"  value="{$row['phonenum']}" name="phonenum" id="phonenum"  class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['order.email']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text"  value="{$row['email']}" name="email" id="email"  class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['order.address']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text"  value="{$row['address']}" name="address" id="address"  class="text_css" style="width:380px;" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['order.zipcode']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text"  value="{$row['zipcode']}" name="zipcode" id="zipcode"  class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['order.remark']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:150px;width:380px;" name="remark" id="remark">{$row['remark']}</textarea></td><td class="td_2"></td></tr>
			<tr><td class="td_3"><input class="button_css" type="submit" value="  {$_AL['all.submit']}  " /></td><td class=""></td></tr>
		</table>
	</div>
	<div id="t3">
		<table class="table_1">
			<tr><td style="height:30px;"><b>{$_AL['order.prodetails']}:</b></td></tr>
			<tr><td>

	<table class="cartpro">
	<thead><tr><td class="op1">{$_AL['order.pro.pic']}</td><td class="op2">{$_AL['order.pro.name']}</td><td class="op3">{$_AL['order.pro.price']}</td><td class="op4">{$_AL['order.pro.num']}</td><td class="op5">{$_AL['order.subtotal']}</td></tr></thead>
<!--
EOT;
foreach($odts as $odt){
$odt['picpath']=$webcore->getPicPath($odt['picpath'],true,true);
$odtid=$odt['proid'];
print <<<EOT
-->
	<tr><td class="op1"><a href="{$odt['prourl']}" target="_blank"><img src="../{$odt['picpath']}" /></a></td><td class="op2"><a href="{$odt['prourl']}" target="_blank">{$odt['proname']}</a> </td><td class="op3"><span class="price">{$cache_settings['cur']}{$odt['displayprice']}</span></td><td class="op4">{$odt['pronum']}</td><td class="op5"><span class="price" id="itemtotal_{$odtid}">{$cache_settings['cur']}{$odt['itemtotal']}</span></td></tr>
<!--
EOT;
}
print <<<EOT
-->
	<tr><td colspan="5" style="background:#f5f5f5; text-align:right; padding:8px;">{$_AL['order.total.noexp']}: <span class="price" id="total">{$cache_settings['cur']}{$row['total']}</span></td></tr>
	</table>

			</td></tr>
			<tr><td class="td_3"><input class="button_css" type="submit" value="  {$_AL['all.submit']}  " /></td><td class=""></td></tr>
		</table>	
	</div>
	</form>
	<div class="div_clear" style="height:30px;"></div>
	<script src="../js/member.js"></script>
	<script type="text/javascript">
		KE.show({id : 'remark2',items : ['fontname', 'fontsize', '|', 'textcolor', 'bgcolor', 'bold', 'italic', 'underline','removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright','insertorderedlist','insertunorderedlist', '|',  'link']});
	</script>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	var doaction="{$action}";
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['order.state.change']}","",true,"n");
	pt.createTab("t2","{$_AL['order.csg.details']}","",false,"n");
	pt.createTab("t3","{$_AL['order.product.list']}","",false,"n");

	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	
	var state="{$row['state']}";
	setSelect("state",state);
	
	function checkAllAction(){
		return true;
	}

	function InitPage(){
	}
	
	window.onload = InitPage;
	
	</script>
EOT;

	
	break;
	/************************************** add edit END ************************************************/
	
	/************************************** list BEGIN ************************************************/
	case "list":
echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div id="t1">
EOT;
	{
	 	$dwidth=array(0,35,170,370,120,160,70,60);
		$SPRT=".|.".rand(100000,999999).".|.";		//Random split
		$cond="langid={$_SYS['alangid']}";
		$keyword=trim($_GET['k']);
		$orderby=$_GET['orderby'];
		$orderby=empty($orderby)?"id":$orderby;
		$orderbystr='';
		$keyword=str_replace("*","%",$keyword);
		if(!empty($keyword)){
			$cond.=" and (name like '%{$keyword}%' or ordernum like '%{$keyword}%' or phonenum like '%{$keyword}%' or address like '%{$keyword}%')";
		}
		$state=-1;
		if($_GET['state']==''){
			$state=-1;
		}else{
			$state=intval($_GET['state']);
		}
		if($state>-1){
			$cond.=" and state={$state}";
		}
		if( in_array( $orderby, array('id','createtime','total')) ){
			$orderbystr=$orderby.' desc';
		}
		$curPage = intval($_GET["page"]);
		$pager = new Pager();
		$countrow=$db->row_select_one("orders",$cond,"count(1) as total");
		$pager->init(10,$curPage,"admin.php?inc=order&action=list&k={$keyword}&state={$state}&orderby={$orderby}&page={page}");
		/*
		$rows = $pager->queryRowsBySQL($db,"
		SELECT * FROM `{$db->pre}orders` OT,
		(SELECT orderid,
		GROUP_CONCAT(proid ORDER BY id DESC SEPARATOR '{$SPRT}') as proids, 
		GROUP_CONCAT(price ORDER BY id DESC SEPARATOR '{$SPRT}') as prices,
		GROUP_CONCAT(proname ORDER BY id DESC SEPARATOR '{$SPRT}') as pronames,
		GROUP_CONCAT(pronum ORDER BY id DESC SEPARATOR '{$SPRT}') as pronums 
		FROM `{$db->pre}orderdetails` WHERE langid={$_SYS['alangid']} GROUP BY orderid) ODT
		WHERE {$cond} AND OT.id=ODT.orderid ORDER BY {$orderbystr}",$countrow['total']);
		$recstr=_LANG($_AL['all.totalrecords'], array($pager->recordNum));
		*/
		$rows=$pager->queryRows($db,"orders","{$cond} AND langid={$_SYS['alangid']}","*","{$orderbystr}");

echo <<<EOT
	<div class="div_clear" style="height:10px;"></div>
	<div class="tips_1">
{$_AL['all.keyword']}: <input class="text_css" type="text" size="20" value="{$keyword}" id="keyword" />
<select id="state"><option value="-1">{$_AL['all.state']}</option>{$options}</select> <select id="orderby"><option value="id">{$_AL['all.orderby']}</option><option value="createtime">{$_AL['order.createtime']}</option><option value="total">{$_AL['order.total.noexp']}</option></select>
<input class="button_css" type="button" value="  {$_AL['all.search']}  " onclick="searchorders()" />
&nbsp;&nbsp;&nbsp;{$recstr}</div>

EOT;
	echo("<form id=\"ordersform\" onsubmit=\"return false;\">");
	echo("<table class=\"table_1\" width=\"100%\">");
	echo("<tr style=\"font-weight:bold;color:#333333;\"><td class=\"row_0\">".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[1]}px;\">{$_AL['all.select']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[2]}px;\">{$_AL['order.number']}/{$_AL['order.createtime']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[3]}px; padding-right:8px;\">{$_AL['order.product.list']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[4]}px;\">{$_AL['order.total']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[5]}px;\">{$_AL['order.csg.details']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[6]}px;\">{$_AL['order.state']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[7]}px;\">{$_AL['all.control']}</div>".
		"");

	for($i=0;$i<count($rows);$i++){
		$row=$rows[$i];
		$row['createtime']=getDateStr($row['createtime']);
		$row['name']=htmlFilter($row['name']);
		$row['phonenum']=htmlFilter($row['phonenum']);
		$row['email']=htmlFilter($row['email']);
		$row['address']=htmlFilter(cutStr($row['address'],12));
		$row['zipcode']=htmlFilter($row['zipcode']);
		$row['remark']=htmlFilter($row['remark']);
		$row['total']=number_format($row['total'],2);
		$orows=$db->row_select("orderdetails","langid={$_SYS['alangid']} and orderid={$row['id']}",0,"proid,price,proname,pronum");

		//$proids=explode($SPRT,$row['proids']);
		//$prices=explode($SPRT,$row['prices']);
		//$pronames=explode($SPRT,htmlFilter($row['pronames']));
		//$pronums=explode($SPRT,$row['pronums']);
		$prostr='';
		foreach($orows as $key=>$orow){
			//$prices[$key]=number_format($prices[$key],2);
			$orow['price']=number_format($orow['price'],2);
			$orow['proname']=htmlFilter($orow['proname']);
			$prostr.="<p class='order_pro_p' title=\"{$orow['proname']}\"><a href='../product.php?id={$orow['proid']}' target='_blank' class='proname'>{$orow['proname']}</a><span class='time'>{$cache_settings['cur']}{$orow['price']}</span> <span style='font-size:10px;'>X</span> <span class='time'><b>{$orow['pronum']}</b></span></p>";
		}
		$checkboxstr="<input type=\"checkbox\" value=\"{$row['id']}\" name=\"aids[]\" class=\"checkbox_css\" />";

		echo("<tr><td class=\"row_0\" style=\"line-height:150%;\">".
			"<div class='rowdiv_0' style='width:{$dwidth[1]}px;'>{$checkboxstr}</div>".
			"<div class='rowdiv_0' style='width:{$dwidth[2]}px; '><a href=\"admin.php?inc=order&action=edit&id={$row['id']}\">{$row['ordernum']}</a><br /><span class=time>{$row['createtime']}</span></div>".
			"<div class='rowdiv_0' style='width:{$dwidth[3]}px; padding-right:8px; '>{$prostr}</div>".
			"<div class='rowdiv_0' style='width:{$dwidth[4]}px; '><span class='time' style='font-weight:bold;'>{$cache_settings['cur']}{$row['total']}</span></div>".
			"<div class='rowdiv_0' style='width:{$dwidth[5]}px;'>{$row['name']} {$row['phonenum']}<br />{$row['address']}</div>".
			"<div class='rowdiv_0' style='width:{$dwidth[6]}px;'><a href=\"admin.php?inc=order&action=edit&id={$row['id']}\"><span style='color:{$orderstatecolor[$row['state']]}'>{$orderstate[$row['state']]}</span></a></div>".
			"<div class='rowdiv_0' style='width:{$dwidth[7]}px;'><a href=\"admin.php?inc=order&action=edit&id={$row['id']}\">{$_AL['all.edit']}</a></div>".
			"");				
	}
	echo("</table>");
	echo("<table width=100%><tr><td><input type=\"checkbox\" onclick=\"selectAll('ordersform',this.checked)\" class=\"checkbox_css\" /> {$_AL['all.selectall']} &nbsp;&nbsp;<input type=\"button\" class=\"button_css\" value=\"  {$_AL['all.delete']}  \" onclick=\"ajax_doorders_yn()\" /></td><td><div class='pagestrdiv'>{$pager->getPageStr()}</div></td></tr></table>");
	echo("</form>");
 	}
echo <<<EOT
	</div>
	<div id="t2"></div>
	<div id="t3"></div>
	<div class="div_clear" style="height:30px;"></div>
<script>
var smallNowTab;
var pt = new Tabs();
pt.classpre="smalltab_";
pt.container = "smalltab_container";
pt.createTab("t1","{$_AL['order.list']}","",true,"n");
pt.init = function(){
	smallNowTab = pt.nowTab;
};
pt.onclick = function(){
	smallNowTab = pt.nowTab;
};		
pt.initTab();
pt.clickNowTab();

var orderby="{$orderby}";
var state="{$state}";
setSelect("orderby",orderby);
setSelect("state",state);

function ajax_doorders_yn(){
	var btns=[
		{value:" {$_AL['all.confirm']} ",onclick:"mainifm.ajax_doorders()",focus:true},
		{value:" {$_AL['all.cancel']} ",onclick:"popwin.close()"}
	];
	popwin.showDialog(3,"{$_AL['all.confirm']}","{$_AL['order.del.warning']}",btns,380,130);
}


function ajax_doorders(){
	popwin.loading();
	ajaxPost("ordersform","order_ajax.php?action=doorders",ajax_doorders_callback);
}
function ajax_doorders_callback(data){
	var btns;
	popwin.loaded();
	if(isSucceed(data)){
		btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
		popwin.showDialog(1,"{$_AL['all.con.succeed']}","{$_AL['order.del.succeed']}",btns,280,130);
	}else{
		btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
	}
}

function searchorders(){
	var loc = "admin.php?inc=order&action=list&k="+urlEncode(E("keyword").value)+"&orderby="+E("orderby").value+"&state="+E("state").value;
	reloadSelf(loc);
}

function PageInit(){
	if(E("keyword")){E("keyword").onkeyup = function(event){checkKeyPressEnter(event);};}
}
function checkKeyPressEnter(eventobject){
	var eve=eventobject||window.event;
	if(eve.keyCode==13) {
		searchorders();
	}
}
window.onload=PageInit;
</script>
EOT;
	break;
	/**************************************  list END ************************************************/

}	
?>