<?php
switch($action){
	/************************************** lang BEGIN ************************************************/
	case "list":
	$rows=$db->row_select("langs","",0,"*","isdefault desc,ordernum");
	$dwidth=array(0,50,80,170,150,80,80,80);
	$langselect='';
	$dir = '../language';
	$langdir = dir($dir);
	$tmporder=array();
	while($entry = $langdir->read()) {
		$ladir = realpath($dir.'/'.$entry);
		if(!in_array($entry, array('.', '..')) && is_dir($ladir)) {
			$langselect.="<option value='{$entry}' title='../language/{$entry}/flag.gif'>{$entry}</option>";
		}
	}


echo <<<EOT
<script>
var maxIndex=0;
function addLang(langid, ordernum, langname, directory, isdefault, ishidden){
	var namepre='lang';
	if(langid==''){
		namepre='newlang';
	}
	var defico=isdefault==''?'':(isdefault=="1"?"<a href='#' class='def_yes'>{$_AL['lang.def']}</a>":"<a href='lang_ajax.php?action=setdefault&setlangid="+langid+"' class='def_no' title='{$_AL['lang.click2def']}'>{$_AL['all.n']}</a>");
	var hideico=ishidden==''?'':(ishidden=="1"?"<a href='lang_ajax.php?action=sethide&hide=0&setlangid="+langid+"' class='def_no' title='{$_AL['lang.click2open']}'>{$_AL['lang.off']}</a>":"<a href='lang_ajax.php?action=sethide&hide=1&setlangid="+langid+"' class='def_yes' title='{$_AL['lang.click2off']}'>{$_AL['lang.open']}</a>");
	var dlang=langid==''?'':"<a href=\"javascript:ajax_dellang_yn("+langid+")\">{$_AL['all.delete']}</a>";
	var langsel="<select name='"+namepre+"_directory["+langid+"]' id='"+namepre+"_directory_"+langid+"' style='width:120px;'>{$langselect}</select>";
	var s="<table class=\"table_1\" width=\"100%\"><tr><td class=\"td_6\"><div class=\"rowdiv_0\" style=\"width:{$dwidth[2]}px;\"><input type=\"text\" size=\"6\" value=\""+ordernum+"\" name=\""+namepre+"_ordernum["+langid+"]\" class=\"text_css\" /></div><div class=\"rowdiv_0\" style=\"width:{$dwidth[3]}px;\"><input type='text' value='"+langname+"' size='20' name='"+namepre+"_name["+langid+"]' class=\"text_css\" /></div><div class=\"rowdiv_0\" style=\"width:{$dwidth[4]}px;\">"+langsel+"</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[5]}px;\">"+defico+"</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[6]}px;\">"+hideico+"</div><div class=\"rowdiv_0\" style=\"width:{$dwidth[7]}px;\">"+dlang+"</div></td></tr></table>";
	var ele=document.createElement('div');
	ele.id="group_div_"+maxIndex;
	ele.innerHTML=s;
	E("langdiv").appendChild(ele);
	maxIndex++;
}
</script>
<script src="msdropdown/js/jquery.dd.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="msdropdown/dd.css" />

	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1">
		<form id="langform" onsubmit="return false;">
		<div class="tips_1">{$_AL['lang.del.tips']}</div>
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1" width="100%">
			<tr style="font-weight:bold;color:#333333;"><td class="td_6"><div class="rowdiv_0" style="width:{$dwidth[2]}px;">{$_AL['lang.orders']}</div><div class="rowdiv_0" style="width:{$dwidth[3]}px;">{$_AL['lang.name']}</div><div class="rowdiv_0" style="width:{$dwidth[4]}px;">{$_AL['lang.dir']}</div><div class="rowdiv_0" style="width:{$dwidth[5]}px;">{$_AL['lang.def']}</div><div class="rowdiv_0" style="width:{$dwidth[6]}px;">{$_AL['lang.frontuse']}</div><div class="rowdiv_0" style="width:{$dwidth[7]}px;">{$_AL['all.control']}</div></td></tr>
		</table>
		<div id="langdiv">
EOT;
			for($i=0;$i<count($rows);$i++){
				$row=$rows[$i];
				echo("<script>addLang(\"{$row['id']}\",\"{$row['ordernum']}\",\"{$row['name']}\",\"{$row['directory']}\",\"{$row['isdefault']}\",\"{$row['ishidden']}\");setSelect('lang_directory_{$row['id']}','{$row['directory']}');</script>");			
			}
			
echo <<<EOT
		</div>
		<table class="table_1" width="100%">
			<tr><td class="td_6"><a class="td_5_1a" href="javascript:addLang('','','','','','')"><img src="images/ico_add.gif" border="0" /> {$_AL['lang.add']}</a></td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['lang.submit']}  " onclick="ajax_lang()" /></td></tr>
		</table>
		</form>
	</div>
	<div id="t2"></div>
	<div id="t3"></div>
	<div class="div_clear" style="height:30px;"></div>
<script>
var hasErr=false;
var smallNowTab;
var pt = new Tabs();
pt.classpre="smalltab_";
pt.container = "smalltab_container";
pt.createTab("t1","{$_AL['lang.set']}","",true,"n");
pt.init = function(){
	smallNowTab = pt.nowTab;
};
pt.onclick = function(){
	smallNowTab = pt.nowTab;
};		
pt.initTab();
pt.clickNowTab();

function ajax_lang(){
	popwin.loading();
	ajaxPost("langform","lang_ajax.php?action=savelang",ajax_lang_callback);
}
function ajax_lang_callback(data){
	var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();reloadTop('admin.php?inc=lang&action=list');",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['lang.setsucceed']}",btns,280,130);
	}else{
		popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
	}
}

function ajax_dellang_yn(langid){
	var btns=[
		{value:" {$_AL['all.confirm']} ",onclick:"mainifm.ajax_dellang("+langid+")",focus:true},
		{value:" {$_AL['all.cancel']} ",onclick:"popwin.close()"}
	];
	popwin.showDialog(3,"{$_AL['all.ok']}","{$_AL['lang.del.warning']}",btns,320,160);
}	
	
function ajax_dellang(langid){
	popwin.loading();
	ajaxGet("lang_ajax.php?action=dellang&setlangid="+langid, ajax_dellang_callback);
}
function ajax_dellang_callback(data){
	var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();reloadTop('admin.php?inc=lang&action=list');",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,"{$_AL['all.del.succeed']}","{$_AL['lang.delsucceed']}",btns,320,130);
	}else{
		popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,320,130);
	}
}

$(document).ready(function(e) {
	try {
		$("body select").msDropDown();
	}catch(e) {
		//alert(e.message);
	}
});
</script>

EOT;
	break;
	/************************************** lang END ************************************************/

}	
?>