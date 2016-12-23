<?php
require_once('../inc/pager.php');
$channels=$_AL['channel.types'];
$effect_lang=_LANG($_AL['all.set.effect'],array($cache_langs[$_SYS['alangid']]['name']));
switch($action){
	/************************************** set BEGIN ************************************************/
	case "set":
if(!hasPopedom("channel")){
	echo("<style>.pdhide{display:none;}</style>");
}
echo <<<EOT
<style>
.td_5_1{width:320px;}	
.td_5_2{width:120px;}	
.td_5_3{width:140px;}	
.td_5_4{width:100px;}	
.td_5_5{width:180px;}	
</style>
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div id="t1">
		<form id="channelform" onsubmit="return false;">
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr class="tr_5"><td class="td_5_1" style="font-weight:bold;color:#333333;">[{$_AL['channel.order']}]{$_AL['channel.title']}</td><td class="td_5_2" style="font-weight:bold;color:#333333;">{$_AL['channel.type']}</td><td class="td_5_4 pdhide" style="font-weight:bold;color:#333333;">{$_AL['channel.show_hide']}</td><td class="td_5_3 pdhide" style="font-weight:bold;color:#333333;">{$_AL['channel.position']}</td><td class="td_5_5" style="font-weight:bold;color:#333333;">{$_AL['all.control']}</td><td>&nbsp;</td></tr>
EOT;

				$tabindex=1000;
				$rows1=$db->row_select("channels","pid=0 and langid={$_SYS['alangid']}",0,"*","ordernum,id");
				for($i=0;$i<count($rows1);$i++){
					$row1=$rows1[$i];
					$tabindex++;
					$hidestr=$row1['ishidden']=='1'?"<a href='channel_ajax.php?action=sethide&hide=0&channelid={$row1[id]}' class='def_no' title='{$_AL['all.click2show']}'>{$_AL['all.hide']}</a>":"<a href='channel_ajax.php?action=sethide&hide=1&channelid={$row1[id]}' class='def_yes' title='{$_AL['all.click2hide']}'>{$_AL['all.show']}</a>";
					$clink=getChnAdminLink($row1);
					$ctype=$row1['systemtype']>0?"<span style='color:#ff6600;font-weight:bold;'>{$_AL['channel.system']}</span>":$channels[$row1['channeltype']];
					$cposition="<input type='checkbox' name='position[{$row1['id']}][]' value='1' ".(stristr($row1['positions'],'|1|')?'checked':'')." /> {$_AL['channel.header']}&nbsp;";
					$cposition.="<input type='checkbox' name='position[{$row1['id']}][]' value='2' ".(stristr($row1['positions'],'|2|')?'checked':'')." /> {$_AL['channel.footer']}&nbsp;";

					echo("<tr class=\"tr_5\"><td class=\"td_5_1\"><span class=\"forum_click1\"></span><input type=\"text\" size=\"2\" value=\"{$row1[ordernum]}\" name=\"ordernum[{$row1['id']}]\" tabIndex=\"{$tabindex}\" class=\"text_css\" /> <input type=\"text\" size=\"15\" value=\"{$row1[title]}\" name=\"title[{$row1['id']}]\" tabIndex=\"1{$tabindex}\" style=\"font-weight:bold;\" class=\"text_css\" /><span class=pdhide> ".($row1['channeltype']==3?"":"<a href=\"admin.php?inc=channel&action=add&pid={$row1['id']}\"><img src=\"images/ico_add.gif\" border=\"0\" /> {$_AL['channel.add.sub']}</a>")."</span></td><td class=\"td_5_2\">{$ctype}</td><td class=\"td_5_4 pdhide\">{$hidestr}</td><td class=\"td_5_3 pdhide\">{$cposition}</td><td class=\"td_5_5\"><span class=pdhide><a href=\"admin.php?inc=channel&action=edit&id={$row1['id']}\">{$_AL['all.edit']}</a> &nbsp; ".($row1['systemtype']>0?"<span style='color:#c0c0c0' title='{$_AL['channel.system.nodel']}'>{$_AL['all.delete']}</span> &nbsp; ":"<a href=\"javascript:ajax_delchannel_yn({$row1['id']})\">{$_AL['all.delete']}</a> &nbsp; ")."</span><a href='{$clink}'>{$_AL['channel.content.man']}</a></td><td>&nbsp;</td></tr>");
					$rows2=$db->row_select("channels","pid={$row1['id']} and langid={$_SYS['alangid']}",0,"*","ordernum,id");
					for($j=0;$j<count($rows2);$j++){
						$row2=$rows2[$j];
						$tabindex++;
						$hidestr=$row2['ishidden']=='1'?"<a href='channel_ajax.php?action=sethide&hide=0&channelid={$row2[id]}' class='def_no' title='{$_AL['all.click2show']}'>{$_AL['all.hide']}</a>":"<a href='channel_ajax.php?action=sethide&hide=1&channelid={$row2[id]}' class='def_yes' title='{$_AL['all.click2hide']}'>{$_AL['all.show']}</a>";
						$clink=getChnAdminLink($row2);
						$ctype=$row2['systemtype']>0?"<span style='color:#ff6600;font-weight:bold;'>{$_AL['channel.system']}</span>":$channels[$row2['channeltype']];

						echo("<tr class=\"tr_5\"><td class=\"td_5_1\"><span class=\"forum_1\"></span><input type=\"text\" size=\"2\" value=\"{$row2[ordernum]}\" name=\"ordernum[{$row2['id']}]\" tabIndex=\"{$tabindex}\" class=\"text_css\" /> <input type=\"text\" size=\"15\" value=\"{$row2[title]}\" name=\"title[{$row2['id']}]\" tabIndex=\"1{$tabindex}\" class=\"text_css\" /></td><td class=\"td_5_2\">{$ctype}</td><td class=\"td_5_4 pdhide\">{$hidestr}</td><td class=\"td_5_3 pdhide\">&nbsp;</td><td class=\"td_5_5\"><span class=pdhide><a href=\"admin.php?inc=channel&action=edit&id={$row2['id']}\">{$_AL['all.edit']}</a> &nbsp; <a href=\"javascript:ajax_delchannel_yn({$row2['id']})\">{$_AL['all.delete']}</a> &nbsp; </span><a href='{$clink}'>{$_AL['channel.content.man']}</a></td><td>&nbsp;</td></tr>");
					}
				}
				echo("<tr class=\"tr_5 pdhide\"><td><a class=\"td_5_1a\" href=\"admin.php?inc=channel&action=add\"><img src=\"images/ico_add.gif\" border=\"0\" /> {$_AL['channel.add']}</a></td><td class=\"td_5_2\"> &nbsp;</td><td class=\"td_5_4\"> &nbsp;</td><td class=\"td_5_3 pdhide\"> &nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>");
echo <<<EOT
			<tr class=pdhide><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_saveset()" /></td></tr>
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
	pt.createTab("t1","{$_AL['channel.set']}","",true,"n");
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
		ajaxPost("channelform","channel_ajax.php?action=saveset",ajax_saveset_callback);
	}
	function ajax_saveset_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();reloadTop('admin.php?inc=channel&action=set');",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}","{$_AL['channel.set.succeed']}",btns,280,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.modify.failed']}",data,btns,280,130);
		}
	}
	
	function ajax_delchannel_yn(cid){
		var btns=[
			{value:" {$_AL['all.confirm']} ",onclick:"mainifm.ajax_delchannel("+cid+")",focus:true},
			{value:" {$_AL['all.cancel']} ",onclick:"popwin.close()"}
		];
		popwin.showDialog(3,"{$_AL['all.confirm']}","{$_AL['channel.del.tips']}",btns,320,130);
	}	
		
	function ajax_delchannel(cid){
		popwin.loading();
		ajaxGet("channel_ajax.php?action=delchannel&cid="+cid, ajax_delchannel_callback);
	}
	function ajax_delchannel_callback(data){
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();reloadTop('admin.php?inc=channel&action=set');",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.del.succeed']}","{$_AL['channel.del.succeed']}",btns,320,130);
		}else{
			popwin.showDialog(0,"{$_AL['all.del.failed']}",data,btns,320,130);
		}
	}
	
	</script>
EOT;

	break;
	/************************************** set END ************************************************/

	/************************************** add edit BEGIN ************************************************/
	case "add":
	case "edit":
	if(!hasPopedom("lang")){
		printRes(_LANG($_AL['admin.nopopedom'], array($_AL['index.channel.man'])));
	}

	$id=intval($_GET['id']);
	$pid=intval($_GET['pid']);
	if($action=="add"){
		$row=null;
		$p1str=" checked";
	}else{
		$row=$db->row_select_one("channels","id={$id}","*");
		$pid=$row['pid'];
		if($row['systemtype']>0){
			$hidestr=" style='display:none;'";
		}
		if(stristr($row['positions'],'|1|')){
			$p1str=" checked";
		}
		if(stristr($row['positions'],'|2|')){
			$p2str=" checked";
		}
	}
	
	$options="";
	$rows1=$db->row_select("channels","pid=0 and langid={$_SYS['alangid']}",0,"*","ordernum,id");
	for($i=0;$i<count($rows1);$i++){
		$row1=$rows1[$i];
		$options.="<option value=\"{$row1['id']}\">&nbsp;&gt;&gt;&nbsp;{$row1[title]}</option>";
		$rows2=$db->row_select("channels","pid={$row1['id']} and langid={$_SYS['alangid']}",0,"*","ordernum,id");
		for($j=0;$j<count($rows2);$j++){
			$row2=$rows2[$j];
			$options.="<option value=\"{$row2['id']}\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--&nbsp;&nbsp;{$row2[title]}</option>";
		}
	}

echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<form id="detailsform">
	<div id="t1">
		<table class="table_1">
			<tr><td class="td_0" style="width:400px;">{$_AL['channel.title']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="hidden" name="doaction" id="doaction" value="{$action}" /><input type="hidden" name="id" id="id" value="{$id}" /><input type="text"  value="{$row['title']}" name="title" id="title"  class="text_css" style="width:380px;" /></td><td class="td_2">{$_AL['channel.title.remark']}</td></tr>
			<tr style="display:none;"><td class="td_0">{$_AL['channel.url.alias']}:</td><td class=""></td></tr>
			<tr style="display:none;"><td class="td_1"><input type="text" value="{$row['alias']}" name="alias" id="alias" class="text_css" style="width:380px;" /></td><td class="td_2">{$_AL['channel.url.remark']}</td></tr>
			<tr{$hidestr}><td class="td_0">{$_AL['channel.type']}:</td><td class=""></td></tr>
			<tr{$hidestr}><td class="td_1"><select name="channeltype" id="channeltype"><option value="1">{$channels[1]}</option><option value="2">{$channels[2]}</option><option value="4">{$channels[4]}</option></select></td><td class="td_2">{$_AL['channel.type.remark']}</td></tr>
			<tr{$hidestr}><td class="td_0">{$_AL['channel.parent']}:</td><td class=""></td></tr>
			<tr{$hidestr}><td class="td_1"><select name="pid" id="pid"><option value="0">{$_AL['channel.astop']}</option>{$options}</select></td><td class="td_2">{$_AL['channel.parent.tips']}</td></tr>
			<tr><td class="td_0">{$_AL['channel.hideyn']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="ishidden" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="ishidden" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2">{$_AL['channel.hideyn.tips']}</td></tr>
			<tr><td class="td_0">{$_AL['channel.position']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="checkbox" value="1" name="positions[]" class="radio_css" {$p1str} /> {$_AL['channel.header']} &nbsp; &nbsp;<input type="checkbox" value="2" name="positions[]" class="radio_css" {$p2str} /> {$_AL['channel.footer']}</td><td class="td_2">{$_AL['channel.position.tips']}</td></tr>
			<tr><td class="td_0">{$_AL['all.seotitle']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" style="width:380px;" value="{$row['seotitle']}" name="seotitle" id="seotitle" class="text_css" /></td><td class="td_2">{$_AL['all.seotitle.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['all.metakey']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:60px;width:380px;" name="metakeywords" id="metakeywords">{$row['metakeywords']}</textarea></td><td class="td_2">{$_AL['all.metakey.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['all.metadesc']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><textarea style="height:60px;width:380px;" name="metadesc" id="metadesc">{$row['metadesc']}</textarea></td><td class="td_2">{$_AL['all.metadesc.remark']}</td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_savechannel()" /></td><td class=""></td></tr>
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
		pt.createTab("t1","{$_AL['channel.add']}","",true,"n");
	}else{
		pt.createTab("t1","{$_AL['channel.edit']}","",true,"n");
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
	var channeltype="{$row['channeltype']}";
	var pid="{$pid}";
	var id="{$id}";
	setRadioCheck("ishidden",ishidden);
	setSelect("pid",pid);
	setSelect("channeltype",channeltype);
	
	function checkAllAction(){
		return true;
	}

	function ajax_savechannel(){
		popwin.loading();
		ajaxPost("detailsform","channel_ajax.php?action=modifychannel",ajax_savechannel_callback);
	}
	function ajax_savechannel_callback(data){
		var tips=(doaction=="add"?"{$_AL['channel.add.succeed']}":"{$_AL['channel.edit.succeed']}");
		var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();reloadTop('admin.php?inc=channel&action=set');",focus:true}];
		popwin.loaded();
		if(isSucceed(data)){
			popwin.showDialog(1,"{$_AL['all.modify.succeed']}",tips,btns,280,130);
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
	/************************************** add edit BEGIN ************************************************/


	/************************************** link BEGIN ************************************************/
	case "link":
	if(!hasPopedom("channel")){
		printRes(_LANG($_AL['admin.nopopedom.link'], array($_AL['index.channel.man'])));
	}

	$channelid=intval($_GET['channelid']);
	$row=$db->row_select_one("channels","id={$channelid}");
	
echo <<<EOT
	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>

	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<form id="linkform" method="POST" onsubmit="return checkAllAction()" action="channel_ajax.php?action=savelink">
	<div id="t1">
		<table class="table_1">
			<tr><td class="td_0">{$_AL['channel.link']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="text" value="{$row['link']}" name="link" id="link" class="text_css" style="width:280px;" /><input type="hidden" name="channelid" id="channelid" value="{$channelid}" /></td><td class="td_2">{$_AL['channel.link.remark']}</td></tr>
			<tr><td class="td_0">{$_AL['channel.isblank']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="radio" value="1" name="target" class="radio_css" /> {$_AL['all.y']} &nbsp; &nbsp;<input type="radio" value="0" name="target" class="radio_css" /> {$_AL['all.n']}</td><td class="td_2"></td></tr>
			<tr><td class="td_3"><input class="button_css" type="submit" value="  {$_AL['all.submit']}  " /></td><td class=""></td></tr>
		</table>
	</div>

	<div id="t2"></div>
	<div id="t3"></div>
	</form>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['channel.link']}","",true,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
	};		
	pt.initTab();
	pt.clickNowTab();

	var target="{$row['target']}";
	setRadioCheck("target",target);
	
	function checkAllAction(){
		return true;
	}

	function InitPage(){
	}
	
	window.onload = InitPage;
	
	</script>
EOT;

	
	break;
	/************************************** link END ************************************************/

}
?>