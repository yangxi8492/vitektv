<?php
require_once('./../inc/pager.php');
$effect_lang=_LANG($_AL['all.set.effect'],array($cache_langs[$_SYS['alangid']]['name']));
switch($action){
	/************************************** set BEGIN ************************************************/
	case "set":
echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div id="t1">
		<form id="procateform" onsubmit="return false;">
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr class="tr_5"><td class="td_5_1" style="font-weight:bold;color:#333333;">[{$_AL['procate.order']}]{$_AL['procate.name']}</td><td class="td_5_3" style="font-weight:bold;color:#333333;">{$_AL['all.show']}/{$_AL['all.hide']}</td><td class="td_5_3" style="font-weight:bold;color:#333333;">{$_AL['all.control']}</td><td>&nbsp;</td></tr>
EOT;

				$tabindex=1000;
				$rows1=$db->row_select("procates","pid=0 and langid={$_SYS['alangid']}",0,"*","ordernum,id");
				for($i=0;$i<count($rows1);$i++){
					$row1=$rows1[$i];
					$tabindex++;
					$hidestr=$row1['ishidden']=='1'?"<a href='procate_ajax.php?action=sethide&hide=0&procateid={$row1[id]}' class='def_no' title='{$_AL['procate.click2open']}'>{$_AL['procate.off']}</a>":"<a href='procate_ajax.php?action=sethide&hide=1&procateid={$row1[id]}' class='def_yes' title='{$_AL['procate.click2off']}'>{$_AL['procate.open']}</a>";

					echo("<tr class=\"tr_5\"><td class=\"td_5_1\"><span class=\"forum_click1\"></span><input type=\"text\" size=\"2\" value=\"{$row1[ordernum]}\" name=\"ordernum[{$row1['id']}]\" tabIndex=\"{$tabindex}\" class=\"text_css\" /> <input type=\"text\" size=\"15\" value=\"{$row1[title]}\" name=\"title[{$row1['id']}]\" tabIndex=\"1{$tabindex}\" style=\"font-weight:bold;\" class=\"text_css\" /> <a href=\"admin.php?inc=procate&action=add&pid={$row1['id']}\"><img src=\"images/ico_add.gif\" border=\"0\" /> {$_AL['procate.addsub']}</a></td><td class=\"td_5_3\">{$hidestr}</td><td class=\"td_5_3\" style='width:200px;'><a href=\"admin.php?inc=procate&action=edit&id={$row1['id']}\">{$_AL['all.edit']}</a> &nbsp; <a href=\"javascript:ajax_delprocate_yn({$row1['id']})\">{$_AL['all.delete']}</a> &nbsp;</td><td>&nbsp;</td></tr>");
					$rows2=$db->row_select("procates","pid={$row1['id']} and langid={$_SYS['alangid']}",0,"*","ordernum,id");
					for($j=0;$j<count($rows2);$j++){
						$row2=$rows2[$j];
						$tabindex++;
						$hidestr=$row2['ishidden']=='1'?"<a href='procate_ajax.php?action=sethide&hide=0&procateid={$row2[id]}' class='def_no' title='{$_AL['procate.click2open']}'>{$_AL['procate.off']}</a>":"<a href='procate_ajax.php?action=sethide&hide=1&procateid={$row2[id]}' class='def_yes' title='{$_AL['procate.click2off']}'>{$_AL['procate.open']}</a>";

						echo("<tr class=\"tr_5\"><td class=\"td_5_1\"><span class=\"forum_1\"></span><input type=\"text\" size=\"2\" value=\"{$row2[ordernum]}\" name=\"ordernum[{$row2['id']}]\" tabIndex=\"{$tabindex}\" class=\"text_css\" /> <input type=\"text\" size=\"15\" value=\"{$row2[title]}\" name=\"title[{$row2['id']}]\" tabIndex=\"1{$tabindex}\" class=\"text_css\" /></td><td class=\"td_5_3\">{$hidestr}</td><td class=\"td_5_3\" style='width:200px;'><a href=\"admin.php?inc=procate&action=edit&id={$row2['id']}\">{$_AL['all.edit']}</a> &nbsp; <a href=\"javascript:ajax_delprocate_yn({$row2['id']})\">{$_AL['all.delete']}</a> &nbsp;</td><td>&nbsp;</td></tr>");
					}
				}
				echo("<tr class=\"tr_5\"><td><a class=\"td_5_1a\" href=\"admin.php?inc=procate&action=add\"><img src=\"images/ico_add.gif\" border=\"0\" /> {$_AL['procate.add']}</a></td><td class=\"td_5_3\"> &nbsp;</td><td class=\"td_5_3\"> &nbsp;</td><td>&nbsp;</td></tr>");
echo <<<EOT
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_saveset()" /></td></tr>
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
	pt.createTab("t1","{$_AL['procate.set.title']}","",true,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	
	var isoff="{$row['isoff']}";
	setRadioCheck("isoff",isoff);
	
	function ajax_saveset(){
		popwin.loading();
		ajaxPost("procateform","procate_ajax.php?action=saveset",ajax_saveset_callback);
	}
	function ajax_saveset_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['procate.set.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,280,130);
		}
	}
	
	function ajax_delprocate_yn(cid){
		var btns=[
			{value:" {$_AL['all.confirm']} ",onclick:"mainifm.ajax_delprocate("+cid+")",focus:true},
			{value:" {$_AL['all.cancel']} ",onclick:"popwin.close()"}
		];
		popwin.showDialog(3,"{$_AL['all.confirm']}","{$_AL['procate.del.warning']}",btns,320,130);
	}	
		
	function ajax_delprocate(cid){
		popwin.loading();
		ajaxGet("procate_ajax.php?action=delprocate&cid="+cid, ajax_delprocate_callback);
	}
	function ajax_delprocate_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.del.succeed']}","{$_AL['procate.del.succeed']}",btns,320,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.con.failed']}", data,btns,320,130);
		}
	}
	
	</script>
EOT;

	break;
	/************************************** set END ************************************************/

	/************************************** add edit BEGIN ************************************************/
	case "add":
	case "edit":
	$id=intval($_GET['id']);
	$pid=intval($_GET['pid']);
	if($action=="add"){
		$row=null;
	}else{
		$row=$db->row_select_one("procates","id={$id}","*");
		$pid=$row['pid'];
	}
	
	$options="";
	$rows1=$db->row_select("procates","pid=0 and langid={$_SYS['alangid']}",0,"*","ordernum,id");
	for($i=0;$i<count($rows1);$i++){
		$row1=$rows1[$i];
		$options.="<option value=\"{$row1['id']}\">&nbsp;&gt;&gt;&nbsp;{$row1[title]}</option>";
	}

echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<form id="cateform">
	<div id="t1">
		<table class="table_1">
			<tr><td class="td_0">{$_AL['procate.name']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="hidden" name="doaction" id="doaction" value="{$action}" /><input type="hidden" name="langid" id="langid" value="{$langid}" /><input type="hidden" name="id" id="id" value="{$id}" /><input type="text"  value="{$row['title']}" name="title" id="title"  class="text_css" style="width:280px;" /></td><td class="td_2">{$_AL['procate.name.remark']}</td></tr>
			<tr style="display:none;"><td class="td_0">{$_AL['procate.url']}:</td><td class=""></td></tr>
			<tr style="display:none;"><td class="td_1"><input type="text" value="{$row['alias']}" name="alias" id="alias" class="text_css" style="width:280px;" /></td><td class="td_2">{$_AL['procate.url.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['procate.uplevel']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><select name="pid" id="pid"><option value="0">{$_AL['procate.aslevel1']}</option>{$options}</select></td><td class="td_2">{$_AL['procate.uplevel.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['procate.ishidden']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="ishidden" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="ishidden" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2">{$_AL['procate.ishidden.remark']}</td></tr>
			
			<tr><td class="td_0">森{$_AL['all.seotitle']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" style="width:280px;" value="{$row['seotitle']}" name="seotitle" id="seotitle" class="text_css" /></td><td class="td_2">{$_AL['all.seotitle.remark']}</td></tr>
			
			<tr><td class="td_0">{$_AL['all.seotitle']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" style="width:280px;" value="{$row['seotitle']}" name="seotitle" id="seotitle" class="text_css" /></td><td class="td_2">{$_AL['all.seotitle.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['all.metakey']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:60px;width:280px;" name="metakeywords" id="metakeywords">{$row['metakeywords']}</textarea></td><td class="td_2">{$_AL['all.metakey.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['all.metadesc']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:60px;width:280px;" name="metadesc" id="metadesc">{$row['metadesc']}</textarea></td><td class="td_2">{$_AL['all.metadesc.remark']}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_modifyprocate()" /></td><td class=""></td></tr>
		</table>
	</div>
	<div id="t2"></div>
	<div id="t3"></div>
	</form>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	var doaction="{$action}";
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	if(doaction=="add"){
		pt.createTab("t1","{$_AL['procate.add']}","",true,"n");
	}else{
		pt.createTab("t1","{$_AL['procate.edit']}","",true,"n");
	}
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();
	

	var ishidden="{$row['ishidden']}";
	var pid="{$pid}";
	var id="{$id}";
	setRadioCheck("ishidden",ishidden);
	setSelect("pid",pid);
	
	function checkAllAction(){
		return true;
	}

	function ajax_modifyprocate(){
		popwin.loading();
		ajaxPost("cateform","procate_ajax.php?action=modifyprocate",ajax_modifyprocate_callback);
	}
	function ajax_modifyprocate_callback(data){
		var tips=(doaction=="add"?"{$_AL['procate.add.succeed']}":"{$_AL['procate.edit.succeed']}");
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.href='admin.php?inc=procate&action=set';",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}",tips,btns,280,130);
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
	/************************************** add edit BEGIN ************************************************/


}

?>