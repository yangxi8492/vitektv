<?php
$isfullversion=FULL_VERSION;
$effect_lang=_LANG($_AL['all.set.effect'],array($cache_langs[$_SYS['alangid']]['name']));
switch($action){
	/************************************** settings BEGIN ************************************************/
	case "settings":
	$row=getSettings();
	$gzip_str=function_exists('ob_gzhandler')? $_AL['main.gziptips1'] : $_AL['main.gziptips2'];
	$url=getUrlPath(-1);
	$row['url']=empty($row['url'])?$url:$row['url'];

echo <<<EOT
	<style>
		.td_1{width:400px;}
	</style>
		<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1">
		<form id="settingsform" onsubmit="return false;">
		<table class="table_1">
			<tr><td class="td_0">{$_AL['main.logo']}</td><td class=""></td></tr>
			<tr><td class="td_1"><div id="img_container" style="height:70px;"></div></td><td class="td_2">{$_AL['main.funad']}</td></tr>
			<tr><td class="td_0">{$_AL['main.webname']}:<span class='required'>(*)</span></td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['webname']}" name="settings[webname]" id="webname" class="text_css" style="width:380px;" /></td><td class="td_2">{$_AL['main.webname.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.url']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['url']}" name="settings[url]" id="url" class="text_css" style="width:380px;" /></td><td class="td_2">{$_AL['main.url.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.icp']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['icp']}" name="settings[icp]" id="icp" class="text_css" style="width:380px;" /></td><td class="td_2">{$_AL['main.icp.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.cur']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['cur']}" name="settings[cur]" id="cur" class="text_css" style="width:380px;" /></td><td class="td_2">{$_AL['main.cur.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.siteoff']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="settings[isoff]" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="settings[isoff]" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.siteoff.reason']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:70px;width:380px;" name="settings[offdetails]" id="offdetails">{$row['offdetails']}</textarea></td><td class="td_2">{$_AL['main.siteoff.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.3part']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:70px;width:380px;" name="settings[counter]" id="counter">{$row['counter']}</textarea></td><td class="td_2">{$_AL['main.3part.remark']}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_savesettings()" /></td><td class=""></td></tr>
		</table>
		</form>
	</div>
	<div id="t2">
		<form id="timeform" onsubmit="return false;">
		<table class="table_1">
			<tr><td class="td_0">{$_AL['main.timeformat']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="40" value="{$row['timeformat']}" name="settings[timeformat]" id="timeformat" class="text_css" /></td><td class="td_2">{$_AL['main.timeformat.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.timezone']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="40" value="{$row['timeoffset']}" name="settings[timeoffset]" id="timeoffset" class="text_css" /></td><td class="td_2">{$_AL['main.timezone.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.timehuman']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="settings[humantime]" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="settings[humantime]" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2">{$_AL['main.timehuman.remark']}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_savetime()" /></td><td class=""></td></tr>
		</table>
		</form>
		
	</div>
	<div id="t3">
		<form id="gzipform" onsubmit="return false;">
		<div class="tips_1">{$_AL['main.gzip.tips']}</div>
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['main.gzip.open']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="settings[isgzip]" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="settings[isgzip]" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2">{$gzip_str}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_savegzip()" /></td><td class=""></td></tr>
		</table>
		</form>
	</div>
	<div id="t4">
		<form id="seoform1" onsubmit="return false;">
		{$_AL['main.seo.ad']}
		<div class="tips_1">{$_AL['main.seo.tips']}</div>
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['main.urlrewrite.open']}:</td><td class=""></td></tr>
			<tr><td class="td_1" style="line-height:200%;">
				<input type="radio" value="0" name="settings[urlrewrite]" class="radio_css" /> {$_AL['main.urlrewrite.t1']}<br />
				<input type="radio" value="1" name="settings[urlrewrite]" class="radio_css" /> {$_AL['main.urlrewrite.t2']}<br />
				<input type="radio" value="2" name="settings[urlrewrite]" class="radio_css" /> {$_AL['main.urlrewrite.t3']}
				</td><td class="td_2">{$_AL['main.urlrewrite.remark']}</td>
			</tr>
			<tr><td class="td_0">{$_AL['all.seotitle']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['seotitle']}"name="settings[seotitle]" id="seotitle" class="text_css" style="width:380px;" /></td><td class="td_2">{$_AL['all.seotitle.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['all.metakey']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['metakeywords']}" name="settings[metakeywords]" id="metakeywords" class="text_css" style="width:380px;" /></td><td class="td_2">{$_AL['all.metakey.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['all.metadesc']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['metadescription']}" name="settings[metadescription]" id="metadescription" class="text_css" style="width:380px;" /></td><td class="td_2">{$_AL['all.metadesc.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.metaheader']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:70px;width:380px;" name="settings[headcontent]" id="headcontent">{$row['headcontent']}</textarea></td><td class="td_2">{$_AL['main.metaheader.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.sitemap']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" readonly="true" value="{$url}/sitemap/index.php" onmouseover="this.select()" class="text_css" style="width:380px;" /></td><td class="td_2">{$_AL['main.sitemap.remark']}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_saveseoinfo()" /></td><td class=""></td></tr>
		</table>
		</form>
	</div>
	<div id="t5">
	</div>
	<div id="t6">
		<form id="verifyform" onsubmit="return false;">
		<div class="tips_1">{$_AL['main.code.set']}</div>
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['main.code1']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="settings[signupsecuritycode]" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="settings[signupsecuritycode]" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2">{$_AL['main.code1.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.code2']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="settings[loginsecuritycode]" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="settings[loginsecuritycode]" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2">{$_AL['main.code2.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.code3']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="settings[msgsecuritycode]" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="settings[msgsecuritycode]" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2">{$_AL['main.code3.remark']}</td></tr>
	
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_saveverifyinfo()" /></td><td class=""></td></tr>
		</table>
		</form>
	</div>

	<div id="t7">
		<form id="configform" onsubmit="return false;">
		<table class="table_1">
			<tr><td class="td_0">{$_AL['main.page1']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="40" value="{$row['perpagepro']}" name="settings[perpagepro]" id="perpagepro" class="text_css" /></td><td class="td_2">{$_AL['main.page.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.page2']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="40" value="{$row['perpageart']}" name="settings[perpageart]" id="perpageart" class="text_css" /></td><td class="td_2">{$_AL['main.page.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.page3']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="40" value="{$row['perpagemsg']}" name="settings[perpagemsg]" id="perpagemsg" class="text_css" /></td><td class="td_2">{$_AL['main.page.remark']}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_saveconfig()" /></td><td class=""></td></tr>
		</table>
		</form>
	</div>

	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['main.t1']}","",true,"n");
	pt.createTab("t2","{$_AL['main.t2']}","",false,"n");
	pt.createTab("t3","{$_AL['main.t3']}","",false,"n");
	pt.createTab("t4","{$_AL['main.t4']}","",false,"n");
	//pt.createTab("t5","{$_AL['main.t5']}","",false,"n");
	pt.createTab("t6","{$_AL['main.t6']}","",false,"n");
	pt.createTab("t7","{$_AL['main.t7']}","",false,"n");

	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	
	var isoff="{$row['isoff']}";
	setRadioCheck("settings[isoff]",isoff);
	var funmember="{$row['funmember']}";
	setRadioCheck("settings[funmember]",funmember);
	var funshop="{$row['funshop']}";
	setRadioCheck("settings[funshop]",funshop);
	
	function ajax_savesettings(){
		if(getV("webname")==""){
			var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
			popwin.showDialog(2,"{$_AL['all.tips']}","{$_AL['all.required']}",btns,280,130);
			return;
		}
		popwin.loading();
		ajaxPost("settingsform","main_ajax.php?action=savesettings",ajax_savesettings_callback);
	}
	function ajax_savesettings_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['main.t1.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}",data,btns,280,130);
		}
	}

	
	var humantime="{$row['humantime']}";
	setRadioCheck("settings[humantime]",humantime);

	function ajax_savetime(){
		popwin.loading();
		ajaxPost("timeform","main_ajax.php?action=savetime",ajax_savetime_callback);
	}

	function ajax_savetime_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['main.t2.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}",data,btns,280,130);
		}
	}

	var isgzip="{$row['isgzip']}";
	setRadioCheck("settings[isgzip]",isgzip);

	function ajax_savegzip(){
		popwin.loading();
		ajaxPost("gzipform","main_ajax.php?action=savegzip",ajax_savegzip_callback);
	}
	function ajax_savegzip_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['main.t3.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}

	var seobaidu="{$row['seobaidu']}";
	var seogoogle="{$row['seogoogle']}";
	var urlrewrite="{$row['urlrewrite']}";
	setRadioCheck("settings[seobaidu]",seobaidu);
	setRadioCheck("settings[seogoogle]",seogoogle);
	setRadioCheck("settings[urlrewrite]",urlrewrite);
	
	function ajax_saveseoinfo(){
		popwin.loading();
		ajaxPost("seoform1","main_ajax.php?action=saveseoinfo",ajax_saveseoinfo_callback);
	}
	function ajax_saveseoinfo_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['main.t4.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}
	
	var curImgIndex=1;
	var imgArr=[0,{$row['logo']}];

	function openUploadAttach(handle1,handle2){
		window.handle1=handle1; window.handle2=handle2;
		popwin.showURL("{$_AL['main.upfile']}", '../inc/attachment/index.php',800,500);
	}

	function insertAttachment(fileid, filename, isimg){
		isimg=parseInt(isimg);
		if(isimg==1&&isImg(filename)){
			E("imginput_"+curImgIndex).value=fileid;
			E("imgdiv_"+curImgIndex).innerHTML="<img src='attachment.php?id="+fileid+"' border=0 />";
			popwin.close();
		}else{
			alert("{$_AL['all.choose.image']}");
		}
	}

	function createImg(){
		for(var i=1;i<2;i++){
			var inp=document.createElement("input");
			inp.id="imginput_"+i;
			inp.name="settings[logo]";
			inp.type='hidden';

			var outobj=document.createElement("div");
			outobj.className="uploaddiv logodiv";

			var obj=document.createElement("div");
			obj.id="imgdiv_"+i;
			obj.title="{$_AL['all.click2selimage']}";
			obj.className="uploadimgdiv";
			obj.onclick=(function(n){
				return function(){
					curImgIndex=n;
					openUploadAttach();
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


	var signupsecuritycode="{$row['signupsecuritycode']}";
	var loginsecuritycode="{$row['loginsecuritycode']}";
	var msgsecuritycode="{$row['msgsecuritycode']}";
	setRadioCheck("settings[signupsecuritycode]",signupsecuritycode);
	setRadioCheck("settings[loginsecuritycode]",loginsecuritycode);
	setRadioCheck("settings[msgsecuritycode]",msgsecuritycode);
	
	function ajax_saveverifyinfo(){
		popwin.loading();
		ajaxPost("verifyform","main_ajax.php?action=saveverifyinfo",ajax_saveverifyinfo_callback);
	}
	function ajax_saveverifyinfo_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['main.t6.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}

	function ajax_saveconfig(){
		popwin.loading();
		ajaxPost("configform","main_ajax.php?action=saveconfig",ajax_saveconfig_callback);
	}

	function ajax_saveconfig_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['main.t7.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}

	function loadJs() {
		var isfullversion="{$isfullversion}";
		var oHead = document.getElementsByTagName('HEAD')[0];
		var oScript= document.createElement("script");
		oScript.type = "text/javascript";
		oScript.src="{$_AL['main.jssrc']}";
		oHead.appendChild(oScript);
	}

	function InitPage(){
		createImg();
		loadJs();
	}
	
	window.onload = InitPage;

	</script>
EOT;
	break;
	/************************************** settings END ************************************************/


	/************************************** banner BEGIN ************************************************/
	case "banner":
	$row=getSettings();
	for($b=1;$b<6;$b++){
		$k="bannerlink".$b;
		$row[$k]=str_replace(array('"','\''),array('',''),$row[$k]);
	}
	$dwidth=array(0,80,400,150);

echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1">
		<form id="bannerform" onsubmit="return false;">
		<div class="tips_1">{$_AL['main.banner.tips']}</div>
		<table class="table_1" width="100%">
			<tr style="font-weight:bold;color:#333333;"><td class="td_6"><div class="rowdiv_0" style="width:{$dwidth[1]}px;">&nbsp;{$_AL['main.banner.pic']}</div><div class="rowdiv_0" style="width:{$dwidth[2]}px;">{$_AL['main.banner.link']}</div></td></tr>
		</table>
		<table class="table_1">
			<tr><td class="td_1">
				<div id="img_container"></div>
				</td><td class="td_2"></td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_savebanner()" /></td><td class=""></td></tr>
		</table>
		</form>
	</div>

	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['main.banner.title']}","",true,"n");

	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();

	var curImgIndex=1;
	var imgArr=[0,{$row['banner1']},{$row['banner2']},{$row['banner3']},{$row['banner4']},{$row['banner5']}];
	var imglinkArr=["{$_AL['main.banner.deflink']}","{$row['bannerlink1']}","{$row['bannerlink2']}","{$row['bannerlink3']}","{$row['bannerlink4']}","{$row['bannerlink5']}"];

	function openUploadAttach(handle1,handle2){
		window.handle1=handle1; window.handle2=handle2;
		popwin.showURL("{$_AL['main.banner.upfile']}",'../inc/attachment/index.php',800,500);
	}

	function insertAttachment(fileid, filename, isimg){
		isimg=parseInt(isimg);
		if(isimg==1&&isImg(filename)){
			E("imginput_"+curImgIndex).value=fileid;
			E("imgdiv_"+curImgIndex).innerHTML="<img src='attachment.php?id="+fileid+"' border=0 />";
			popwin.close();
		}else{
			alert("{$_AL['all.choose.image']}");
		}
	}

	function createImg(){
		for(var i=1;i<6;i++){
			var inp=document.createElement("input");
			inp.id="imginput_"+i;
			inp.name="settings[banner"+i+"]";
			inp.type='hidden';

			var inplink=document.createElement("input");
			inplink.id="imglink_"+i;
			inplink.name="settings[bannerlink"+i+"]";
			inplink.type='text';
			inplink.className='text_css inputlink';
			inplink.value="{$_AL['main.banner.deflink']}";

			var outobj=document.createElement("div");
			outobj.className="uploaddiv clearboth";

			var obj=document.createElement("div");
			obj.id="imgdiv_"+i;
			obj.title="{$_AL['all.click2selimage']}";
			obj.className="uploadimgdiv";
			obj.onclick=(function(n){
				return function(){
					curImgIndex=n;
					openUploadAttach();
				}
			})(i);
			var dellink=document.createElement("a");
			dellink.innerHTML='X';
			dellink.title="{$_AL['all.clear.img']}";
			dellink.href="javascript:cleanImg("+i+")";
			outobj.appendChild(obj);
			outobj.appendChild(inplink);
			outobj.appendChild(dellink);
			E("img_container").appendChild(inp);
			//E("img_container").appendChild(inplink);
			E("img_container").appendChild(outobj);
			if(imgArr[i]>0){
				E("imginput_"+i).value=imgArr[i];
				E("imglink_"+i).value=imglinkArr[i];
				E("imgdiv_"+i).innerHTML="<img src='attachment.php?id="+imgArr[i]+"' border=0 />";
			}
		}
	}

	function cleanImg(n){
		E("imginput_"+n).value="0";
		E("imgdiv_"+n).innerHTML="";
	}

	function ajax_savebanner(){
		popwin.loading();
		ajaxPost("bannerform","main_ajax.php?action=savebanner",ajax_savebanner_callback);
	}

	function ajax_savebanner_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['main.banner.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}

	function InitPage(){
		createImg();
	}
	
	window.onload = InitPage;

	</script>
EOT;
	break;
	/************************************** banner END ************************************************/



	/************************************** email BEGIN ************************************************/
	case "email":
	$row=getSettings(0);
	empty($row['smtpauth']) && $row['smtpauth']=1;
echo <<<EOT
	<style>
	.td_1{width:400px; line-height:200%;}
	</style>
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div id="t1">
		<form id="mailform" onsubmit="return false;">
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['main.mail.senttype']}:<span class='required'>(*)</span></td><td class=""></td></tr>
			<tr>
			<td class="td_1">
			<div><input type="radio" value="1" name="settings[mailsendtype]" onclick="changeSendType()" class="radio_css" /> {$_AL['main.mail.type1']}</div>
			<div id="mailsetting" class="divintd" style="display:none;">
				<p><span>{$_AL['main.mail.sender']}:</span><br /><input type="text" size="35" value="{$row['mailsender']}" name="settings[mailsender]" class="text_css" /></p>
			</div>
			<div><input type="radio" value="2" name="settings[mailsendtype]" onclick="changeSendType()" class="radio_css" /> {$_AL['main.mail.socket']}</div>
				<div id="smtpsetting" class="divintd" style="display:none;">
					<p><span>{$_AL['main.mail.smtpserver']}:</span><br /><input type="text" size="35" value="{$row['smtpserver']}" name="settings[smtpserver]" class="text_css" /></p>
					<p><span>{$_AL['main.mail.smtpport']}:</span><br /><input type="text" size="35" value="{$row['smtpport']}" name="settings[smtpport]" class="text_css" /></p>
					<p><span>{$_AL['main.mail.smtpauth']}:</span><br /><input type="radio" value="1" name="settings[smtpauth]" class="radio_css" /> {$_AL['all.y']}&nbsp; &nbsp; <input type="radio" value="0" name="settings[smtpauth]" class="radio_css" /> {$_AL['all.n']}</p>
					<p><span>{$_AL['main.mail.smtpsender']}:</span><br /><input type="text" size="35" value="{$row['smtpsender']}" name="settings[smtpsender]" class="text_css" /></p>
					<p><span>{$_AL['main.mail.smtpuser']}:</span><br /><input type="text" size="35" value="{$row['smtpusername']}" name="settings[smtpusername]" class="text_css" /></p>
					<p><span>{$_AL['main.mail.smtppass']}:</span><br /><input type="password" size="35" value="{$row['smtppassword']}" name="settings[smtppassword]" class="text_css" /></p>
				</div>
				<div class="clear" style="height:8px;"></div>
				<input class="button_css" type="button" value="  {$_AL['main.mail.smtpsave']}  " onclick="ajax_savemail()" />
			</td>
			<td class="td_2"></td>
			</tr>
		</table>

		<table class="table_1">
			<tr><td class="td_0">{$_AL['main.mail.testset']}:</td><td class=""></td></tr>
			<tr><td class="td_1">
				<div id="" class="divintd">
					<p><span>{$_AL['main.mail.testreceiver']}:</span><br /><input type="text" size="35" value="{$row['testreceiver']}" name="settings[testreceiver]" class="text_css" /></p>
					<p><input class="button_css" type="button" value="  {$_AL['main.mail.testbtn']}  " onclick="ajax_testmail()" /></p>
				</div>
			</td><td class="td_2"><div id="testback" style="margin:8px; height:70px; border:0px solid #dedede; overflow-y:auto;"></div></td></tr>
		</table>
		</form>
	</div>
	<div id="t2"></div>
	<div id="t3"></div>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['main.mail.title']}","",true,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	
	var mailsendtype="{$row['mailsendtype']}";
	setRadioCheck("settings[mailsendtype]",mailsendtype);
	
	var smtpauth="{$row['smtpauth']}";
	setRadioCheck("settings[smtpauth]",smtpauth);

	function ajax_testmail(){
		E("testback").innerHTML="";
		popwin.loading();
		ajaxPost("mailform","main_ajax.php?action=testmail",ajax_testmail_callback);
	}
	function ajax_testmail_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['main.mail.testmail']}","{$_AL['main.mail.testsent']}",btns,320,130);
		}else{
			E("testback").innerHTML=data;
		}
	}
	
	function ajax_savemail(){
		popwin.loading();
		ajaxPost("mailform","main_ajax.php?action=savemail",ajax_savemail_callback);
	}
	function ajax_savemail_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.edit.succeed']}","{$_AL['main.mail.saveset.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}
	function changeSendType(){
		var type=getRadioValue("settings[mailsendtype]");
		setDisplay('smtpsetting',type==2);
		setDisplay('mailsetting',type==1);
	}
	function InitPage(){
		changeSendType();
	}
	
	window.onload=InitPage;
	
	</script>
EOT;
	break;
	/************************************** email END ************************************************/

	/************************************** contact BEGIN ************************************************/
	case "contact":
	$row=$db->row_select_one("contact","langid={$_SYS['alangid']}","*","langid");

echo <<<EOT
	<style>
		.td_1{width:550px;}
	</style>
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1">
		<form id="settingsform" method="POST" onsubmit="return checkAllAction()" action="main_ajax.php?action=savecontact">
		<table class="table_1">
			<tr><td class="td_0">{$_AL['main.ct.company']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['company']}" name="settings[company]" id="company" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.ct.contact']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['contact']}" name="settings[contact]" id="contact" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.ct.depart']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['department']}" name="settings[department]" id="department" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.ct.email']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['email']}" name="settings[email]" id="email" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.ct.phone']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['phone']}" name="settings[phone]" id="phone" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.ct.fax']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['fax']}" name="settings[fax]" id="fax" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.ct.qq']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['qq']}" name="settings[qq]" id="qq" class="text_css" /></td><td class="td_2">{$_AL['main.ct.qq.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.ct.aliww']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['aliww']}" name="settings[aliww]" id="aliww" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.ct.msn']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['msn']}" name="settings[msn]" id="msn" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.ct.skype']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['skype']}" name="settings[skype]" id="skype" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.ct.yahoo']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['yahoo']}" name="settings[yahoo]" id="yahoo" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.ct.address']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['address']}" name="settings[address]" id="address" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.ct.zipcode']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['zipcode']}" name="settings[zipcode]" id="zipcode" class="text_css" /></td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.ct.other']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:200px;width:530px;" name="settings[extradesc]" id="extradesc">{$row['extradesc']}</textarea></td><td class="td_2">{$_AL['main.ct.contactus']}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="submit" value="  {$_AL['all.submit']}  " /></td><td class=""></td></tr>
		</table>
		</form>
	</div>
	
	<div class="div_clear" style="height:30px;"></div>
	<script type="text/javascript">
		KE.show({id : 'extradesc'});
	</script>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['main.ct.title']}","",true,"n");

	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();

	function openUploadAttach(handle1,handle2){
		window.handle1=handle1; window.handle2=handle2;
		popwin.showURL("{$_AL['all.upfile']}",'../inc/attachment/index.php',800,500);
	}

	function insertAttachment(fileid, filename, isimg){
		isimg=parseInt(isimg);
		if(isimg==1&&isImg(filename)){
			KE.insertHtml(window.handle2,'<img src="attachment.php?id='+fileid+'" />');
		}else{
			KE.insertHtml(window.handle2,'[file='+fileid+']'+filename+'[/file]');
		}
		popwin.close();
	}

	function checkAllAction(){
		return true;
	}
	</script>
EOT;
	break;
	/************************************** contact END ************************************************/

	/************************************** cache BEGIN ************************************************/
	case "cache":
echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1">
		<div class="tips_1">{$_AL['main.cache.tips']}
		</div>
		<form id="cacheform" onsubmit="return false;">
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['main.cache.type']}:<span class='required'>(*)</span></td><td class=""></td></tr>
			<tr><td class="td_1">
		<select id="cachetype" name="cachetype" multiple="true" style="width:200px;padding:2px;" size="15">
		<option value='all' selected="true">{$_AL['all.all']}</option>
		<option value='langs'>{$_AL['main.cache1']}</option>
		<option value='settings'>{$_AL['main.cache2']}</option>
		<option value='contact'>{$_AL['main.cache3']}</option>
		<option value='channels'>{$_AL['main.cache4']}</option>
		<option value='procates'>{$_AL['main.cache5']}</option>
		<option value='links'>{$_AL['main.cache6']}</option>
		<option value='votes'>{$_AL['main.cache7']}</option>
		<option value='folders'>{$_AL['main.cache8']}</option>
		<option value='users'>{$_AL['main.cache9']}</option>
		<option value='templatevars'>{$_AL['main.cache10']}</option>
		</select></td><td class="td_2">{$_AL['main.cache.remark']}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_rebuildcache()" /></td><td class=""></td></tr>
		</table>
		</form>
	</div>
	<div id="t2"></div>
	<div id="t3"></div>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['main.cache.title']}","",true,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	
	function ajax_rebuildcache(){
		popwin.loading();
		ajaxPost("cacheform","main_ajax.php?action=rebuildcache", ajax_rebuildcache_callback);
	}
	function ajax_rebuildcache_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.con.succeed']}","{$_AL['main.cache.rebuild.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}
	
	</script>
EOT;
	break;
	/************************************** cache END ************************************************/

	/************************************** attachment BEGIN ************************************************/
	case "attachment":
	$row=getSettings(0);

echo <<<EOT
	<style>
		.td_1{width:400px;}
	</style>
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1">
		<form id="settingsform" onsubmit="return false;">
		<table class="table_1">
			<tr><td class="td_0">{$_AL['main.thumb.onoff']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="settings[isthumb]" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="settings[isthumb]" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['main.thumb.size']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="8" value="{$row['thumbwidth']}" name="settings[thumbwidth]" id="thumbwidth" class="text_css" /> X <input type="text" size="8" value="{$row['thumbheight']}" name="settings[thumbheight]" id="thumbheight" class="text_css" /></td><td class="td_2">{$_AL['main.thumb.remark']}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_saveattachset()" /></td><td class=""></td></tr>
		</table>
		</form>
	</div>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['main.attac.title']}","",true,"n");

	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	
	var isthumb="{$row['isthumb']}";
	setRadioCheck("settings[isthumb]",isthumb);
	
	function ajax_saveattachset(){
		popwin.loading();
		ajaxPost("settingsform","main_ajax.php?action=saveattachset",ajax_saveattachset_callback);
	}
	function ajax_saveattachset_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['main.attach.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}",data,btns,280,130);
		}
	}

	
	function InitPage(){
	}
	
	window.onload = InitPage;

	</script>
EOT;
	break;
	/************************************** attachment END ************************************************/


	/************************************** fun BEGIN ************************************************/
	case "fun":
	(!FULL_VERSION) && $fullversiontips=$_AL['main.fun.ad'];
	$row=getSettings(0);
	$url=getUrlPath(-1);
echo <<<EOT
	{$fullversiontips}
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1">
		<form id="settingsform" onsubmit="return false;">
		<table class="table_1">
			<tr><td class="td_0" style="width:300px;">{$_AL['main.fun.member']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="settings[funmember]" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="settings[funmember]" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2">{$_AL['main.fun.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.fun.cart']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="settings[funshop]" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="settings[funshop]" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2">{$_AL['main.fun.remark']}</td></tr>
		</table>
		</form>
	</div>
	<div id="t2">
		<form id="copyrightform" onsubmit="return false;">
		<table class="table_1">
			<tr><td class="td_0" style="width:550px;">{$_AL['main.copyright.top']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" size="30" value="{$row['copyrightheader']}" name="settings[copyrightheader]" id="copyrightheader" class="text_css" /></td><td class="td_2">{$_AL['main.copyright.top.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['main.copyright.bottom']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:100px;width:530px;" name="settings[copyrightfooter]" id="copyrightfooter">{$row['copyrightfooter']}</textarea></td><td class="td_2"></td></tr>
		</table>
		</form>
	</div>
	<div id="t3">
	</div>

	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['main.fun.1']}","",true,"n");
	pt.createTab("t2","{$_AL['main.fun.2']}","",false,"n");

	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	
	var funmember="{$row['funmember']}";
	setRadioCheck("settings[funmember]",funmember);
	var funshop="{$row['funshop']}";
	setRadioCheck("settings[funshop]",funshop);
	
	function ajax_savefunopen(){
		popwin.loading();
		ajaxPost("settingsform","main_ajax.php?action=savefunopen",ajax_savefunopen_callback);
	}
	function ajax_savefunopen_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['main.fun.1.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}

	function ajax_savecopyright(){
		popwin.loading();
		ajaxPost("copyrightform","main_ajax.php?action=savecopyright",ajax_savecopyright_callback);
	}
	function ajax_savecopyright_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['main.fun.2.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}

	function InitPage(){

	}
	
	window.onload = InitPage;

	</script>
EOT;
	break;
	/************************************** fun END ************************************************/


}	
?>