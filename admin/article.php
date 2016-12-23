<?php
require_once('../inc/pager.php');
$effect_lang=_LANG($_AL['all.set.effect'],array($cache_langs[$_SYS['alangid']]['name']));
switch($action){

	/************************************** add edit BEGIN ************************************************/
	case "add":
	case "edit":
	$id=intval($_GET['id']);
	$channelid=intval($_GET['channelid']);
	if($action=="add"){
		$row=null;
	}else{
		$row=$db->row_select_one("articles","id={$id}","*");
		$channelid=intval($row['channelid']);
		$row['posttime']=getDateStr($row['posttime'],false,0);
	}

	$options="";
	$rows1=$db->row_select("channels","pid=0 and channeltype=2 and langid={$_SYS['alangid']}",0,"*","ordernum,id");
	for($i=0;$i<count($rows1);$i++){
		$row1=$rows1[$i];
		$options.="<option value=\"{$row1['id']}\">&nbsp;&gt;&gt;&nbsp;{$row1[title]}</option>";
		$rows2=$db->row_select("channels","pid={$row1['id']} and channeltype=2 and langid={$_SYS['alangid']}",0,"*","ordernum,id");
		for($j=0;$j<count($rows2);$j++){
			$row2=$rows2[$j];
			$options.="<option value=\"{$row2['id']}\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--&nbsp;&nbsp;{$row2[title]}</option>";
		}
	}

echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<form id="articleform" method="POST" onsubmit="return checkAllAction()" action="article_ajax.php?action=savearticle">
	<div id="t1">
		<table class="table_1">
			<tr><td class="td_0" style="width:400px;">{$_AL['article.title']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="hidden" name="doaction" id="doaction" value="{$action}" /><input type="hidden" name="id" id="id" value="{$id}" /><input type="hidden" name="langid" id="langid" value="{$langid}" /><input type="text"  value="{$row['title']}" name="title" id="title"  class="text_css" style="width:380px;" /></td><td class="td_2">{$_AL['article.title.remark']}</td></tr>
			<tr style="display:none;"><td class="td_0">{$_AL['article.url.alias']}:</td><td class=""></td></tr>
			<tr style="display:none;"><td class="td_1"><input type="text" value="{$row['alias']}" name="alias" id="alias" class="text_css" style="width:380px;" /></td><td class="td_2">{$_AL['article.url.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['article.posttime']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['posttime']}" size="30" name="posttime" id="posttime" class="text_css" /></td><td class="td_2">{$_AL['article.posttime.remark']}: 2011-01-01</td></tr>
			<tr><td class="td_0">{$_AL['article.picview']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><div id="img_container" style="height:70px;"><img src="images/loading.gif" /></div></td><td class="td_2">{$_AL['article.picview.desc']}</td></tr>
			<tr><td class="td_0">{$_AL['article.belong.channel']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><select name="channelid" id="channelid">{$options}</select></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['all.seotitle']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" style="width:380px;" value="{$row['seotitle']}" name="seotitle" id="seotitle" class="text_css" /></td><td class="td_2">{$_AL['all.seotitle.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['all.metakey']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:60px;width:380px;" name="metakeywords" id="metakeywords">{$row['metakeywords']}</textarea></td><td class="td_2">{$_AL['all.metakey.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['all.metadesc']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:60px;width:380px;" name="metadesc" id="metadesc">{$row['metadesc']}</textarea></td><td class="td_2">{$_AL['all.metadesc.remark']}</td></tr>
		</table>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['article.content']}:</td><td class=""></td></tr>
			<tr><td class="td_1" style='width:800px;'>
				<textarea name="content" id="content" style="width: 800px; height: 400px;">{$row['content']}</textarea>
			</td><td class="td_2"></td></tr>
			<tr><td class="td_3"><input class="button_css" type="submit" value="  {$_AL['all.submit']}  " /></td><td class=""></td></tr>
		</table>
	</div>
	<div id="t2">
	</div>
	<div id="t3"></div>
	</form>
	<div class="div_clear" style="height:30px;"></div>
	<script>
		KE.show({id : 'content'});
	</script>

	<script>
	var smallNowTab;
	var pt = new Tabs();
	var doaction="{$action}";
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	if(doaction=="add"){
		pt.createTab("t1","{$_AL['article.add']}","",true,"n");
	}else{
		pt.createTab("t1","{$_AL['article.edit']}","",true,"n");
	}
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	

	var id="{$id}";
	var type="{$row['type']}";
	var channelid="{$channelid}";
	setSelect("type",type);
	setSelect("channelid",channelid);
	
	function checkAllAction(){
		return true;
	}
	var curImgIndex=1;
	var imgArr=[0,{$row['picid']}];

	function openUploadAttach(handle1,handle2){
		window.handle1=handle1; window.handle2=handle2;
		popwin.showURL("{$_AL['article.upfile']}",'../inc/attachment/index.php',800,500);
	}

	function insertAttachment(fileid, filename, isimg){
		isimg=parseInt(isimg);
		if(window.handle1=='artimg'){
			if(isimg==1&&isImg(filename)){
				E("imginput_"+curImgIndex).value=fileid;
				E("imgdiv_"+curImgIndex).innerHTML="<img src='attachment.php?id="+fileid+"' border=0 />";
				popwin.close();
			}else{
				alert("{$_AL['all.choose.image']}");
			}
		}else if(window.handle1=='editor'){
			if(isimg==1&&isImg(filename)){
				KE.insertHtml('content','<img src="attachment.php?id='+fileid+'" />');
			}else{
				KE.insertHtml('content','[file='+fileid+']'+filename+'[/file]');
			}
			popwin.close();
		}
	}

	function createImg(){
		for(var i=1;i<2;i++){
			var inp=document.createElement("input");
			inp.id="imginput_"+i;
			inp.name="picid";
			inp.type='hidden';

			var outobj=document.createElement("div");
			outobj.className="uploaddiv";

			var obj=document.createElement("div");
			obj.id="imgdiv_"+i;
			obj.title="{$_AL['all.click2selimage']}";
			obj.className="uploadimgdiv";
			obj.onclick=(function(n){
				return function(){
					curImgIndex=n;
					openUploadAttach('artimg');
				}
			})(i);
			var dellink=document.createElement("a");
			dellink.innerHTML='X';
			dellink.title="{$_AL['all.clear.img']}";
			dellink.href="javascript:cleanImg("+i+")";
			outobj.appendChild(obj);
			outobj.appendChild(dellink);
			E("img_container").appendChild(inp);
			E("img_container").appendChild(outobj);
			if(imgArr[i]>0){
				E("imginput_"+i).value=imgArr[i];
				E("imgdiv_"+i).innerHTML="<img src='attachment.php?id="+imgArr[i]+"' border=0 />";
			}
		}
	}

	function cleanImg(n){
		E("imginput_"+n).value="0";
		E("imgdiv_"+n).innerHTML="";
	}

	function InitPage(){
		E("posttime").onfocus = function(){choosedate.dfd(E('posttime'))};
		E("img_container").innerHTML="";
		createImg();
	}
	//window.onload = InitPage;
	InitPage();
	
	</script>
EOT;

	
	break;
	/************************************** add edit BEGIN ************************************************/

	/************************************** list BEGIN ************************************************/
	case "list":
echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div id="t1">
EOT;
	{
	 	$dwidth=array(0,35,300,100,100,130,80,80,150);
		$channelid=intval($_GET['channelid']);	
		$cond="channelid={$channelid}";
		$keyword=trim($_GET['k']);
		$orderby=$_GET['orderby'];
		$orderby=empty($orderby)?"id":$orderby;
		$orderbystr='';
		$keyword=str_replace("*","%",$keyword);
		if(!empty($keyword)){
			$cond.=" and (title like '%{$keyword}%' or  content like '%{$keyword}%')";
		}
		$cid=intval($_GET['cid']);
		if(!empty($cid)){
			$cond.=" and cid={$cid}";
		}
		$type=-1;
		if($_GET['type']==''){
			$type=-1;
		}else{
			$type=intval($_GET['type']);
		}
		if($type>-1){
			$cond.=" and type={$type}";
		}
		if( in_array( $orderby, array('id','posttime','hits')) ){
			$orderbystr=$orderby.' desc';
		}
		$curPage = intval($_GET["page"]);
		$pager = new Pager();
		$pager->init(20,$curPage,"admin.php?inc=article&action=list&k={$keyword}&channelid={$channelid}&orderby={$orderby}&page={page}");
		$rows = $pager->queryRows($db,"articles", $cond , "*",$orderbystr);
		$recstr=_LANG($_AL['all.totalrecords'], array($pager->recordNum));
echo <<<EOT
	<div class="div_clear" style="height:10px;"></div>
	<div class="tips_1">
{$_AL['all.keyword']}: <input class="text_css" type="text" size="20" value="{$keyword}" id="keyword" />
<select id="orderby"><option value="id">{$_AL['all.orderby']}</option><option value="posttime">{$_AL['all.posttime']}</option><option value="hits">{$_AL['all.hits']}</option></select>
<input class="button_css" type="button" value="  {$_AL['all.search']}  " onclick="searcharticle()" />
&nbsp;&nbsp;&nbsp;{$recstr}</div>
	<table class="table_1" width="100%">
		<tr><td class="td_6"><a class="td_5_1a" href="admin.php?inc=article&action=add&channelid={$channelid}"><img src="images/ico_add.gif" border="0" /> {$_AL['article.add']}</a></td></tr>
	</table>

EOT;
	echo("<form id=\"articlesform\" onsubmit=\"return false;\">");
	echo("<table class=\"table_1\" width=\"100%\">");
	echo("<tr style=\"font-weight:bold;color:#333333;\"><td class=\"row_0\">".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[1]}px;\">{$_AL['all.select']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[2]}px;\">{$_AL['all.title']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[7]}px;\">{$_AL['all.hits']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[5]}px;\">{$_AL['all.posttime']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[8]}px;\">{$_AL['all.control']}</div>".
		"");

	for($i=0;$i<count($rows);$i++){
		$row=$rows[$i];
		$row['posttime']=getDateStr($row['posttime']);
		$row['title']=htmlFilter($row['title']);
		$row['username']=htmlFilter($row['username']);
		$checkboxstr="<input type=\"checkbox\" value=\"{$row['id']}\" name=\"aids[]\" class=\"checkbox_css\" />";

		echo("<tr><td class=\"row_0\" style=\"line-height:150%;\">".
			"<div class='rowdiv_0' style='width:{$dwidth[1]}px;'>{$checkboxstr}</div>".
			"<div class='rowdiv_0' style='width:{$dwidth[2]}px;'><a href=\"../view.php?id={$row['id']}\" target=\"_blank\">{$row['title']}</a>&nbsp;</div>".
			"<div class='rowdiv_0' style='width:{$dwidth[7]}px;'><span class='time'>{$row['hits']}</span></div>".
			"<div class='rowdiv_0' style='width:{$dwidth[5]}px;'><span class='time'>{$row['posttime']}</span></div>".				
			"<div class='rowdiv_0' style='width:{$dwidth[8]}px;'><a href=\"admin.php?inc=article&action=edit&id={$row['id']}\">{$_AL['all.edit']}</a></div>".
			"");				
	}
	echo("</table>");
	echo("<table width=100%><tr><td><input type=\"checkbox\" onclick=\"selectAll('articlesform',this.checked)\" class=\"checkbox_css\" /> {$_AL['all.selectall']} &nbsp;&nbsp;<input type=\"button\" class=\"button_css\" value=\"  {$_AL['all.delete']}  \" onclick=\"ajax_doarticles_yn()\" /></td><td><div class='pagestrdiv'>{$pager->getPageStr()}</div></td></tr></table>");
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
pt.createTab("t1","{$_AL['article.title']}","",true,"n");
pt.init = function(){
	smallNowTab = pt.nowTab;
};
pt.onclick = function(){
	smallNowTab = pt.nowTab;
};		
pt.initTab();
pt.clickNowTab();

function ajax_doarticles_yn(){
	var btns=[
		{value:" {$_AL['all.confirm']} ",onclick:"mainifm.ajax_doarticles()",focus:true},
		{value:" {$_AL['all.cancel']} ",onclick:"popwin.close()"}
	];
	popwin.showDialog(3,"{$_AL['all.tips']}","{$_AL['article.del.tips']}",btns,380,130);
}


function ajax_doarticles(){
	popwin.loading();
	ajaxPost("articlesform","article_ajax.php?action=doarticles",ajax_doarticles_callback);
}
function ajax_doarticles_callback(data){
	var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,"{$_AL['all.con.succeed']}","{$_AL['article.del.succeed']}",btns,280,130);
	}else{
		popwin.showDialog(0,"{$_AL['all.con.failed']}",data,btns,280,130);
	}
}

function searcharticle(){
	var loc = "admin.php?inc=article&action=list&channelid={$channelid}&k="+urlEncode(E("keyword").value)+"&orderby="+E("orderby").value;
	reloadSelf(loc);
}

function PageInit(){
	if(E("keyword")){E("keyword").onkeyup = function(event){checkKeyPressEnter(event);};}
	var cid="{$cid}";
	var orderby="{$orderby}";
	var type="{$type}";
	setSelect("cid",cid);
	setSelect("orderby",orderby);
	setSelect("type",type);
}
function checkKeyPressEnter(eventobject){
	var eve=eventobject||window.event;
	if(eve.keyCode==13) {
		searcharticle();
	}
}
window.onload=PageInit;
</script>
EOT;
	break;
	/**************************************  list END ************************************************/

	
}	
?>