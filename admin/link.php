<?php
$effect_lang=_LANG($_AL['all.set.effect'],array($cache_langs[$_SYS['alangid']]['name']));
switch($action){
	/************************************** friendlink BEGIN ************************************************/
	case "friendlink":
	$rows=$db->row_select("links","langid={$_SYS['alangid']}",0,"*","langid,ordernum");
	foreach($rows as $key=>$row){
		$row['name']=htmlFilter($row['name']);
		$row['url']=htmlFilter($row['url']);
		$row['content']=htmlFilter($row['content']);
		$row['logo']=htmlFilter($row['logo']);
		$rows[$key]=$row;
	}
	$dwidth=array(0,40,70,150,150,150,150);
echo <<<EOT
<script>
var maxIndex=0;
function addFriendLink(linkid, ordernum, linkname, url, content, logo){
	var namepre='links';
	if(linkid==''){
		namepre='newlinks';
	}
	var s="<table class=\"table_1\" width=\"100%\"><tr><td class=\"td_6\"><div class=\"rowdiv_0\" style=\"width:{$dwidth[1]}px;\"><input type=\"checkbox\" value=\""+linkid+"\" name=\""+namepre+"_delid[]\" class=\"checkbox_css\" /></div><div class=\"rowdiv_0\" style=\"width:{$dwidth[2]}px;\"><input type=\"text\" size=\"5\" value=\""+ordernum+"\" name=\""+namepre+"_ordernum["+linkid+"]\" class=\"text_css\" /></div><div class=\"rowdiv_0\" style=\"width:{$dwidth[3]}px;\"><input type='text' value='"+linkname+"' size='20' name='"+namepre+"_name["+linkid+"]' class=\"text_css\" /></div><div class=\"rowdiv_0\" style=\"width:{$dwidth[4]}px;\"><input type='text' value='"+url+"' size='20'  name='"+namepre+"_url["+linkid+"]' class=\"text_css\" /></div><div class=\"rowdiv_0\" style=\"width:{$dwidth[5]}px;\"><input type='text' value='"+content+"' size='20' name='"+namepre+"_content["+linkid+"]' class=\"text_css\" /></div><div class=\"rowdiv_0\" style=\"width:{$dwidth[6]}px;\"><input type='text' value='"+logo+"' size='20' name='"+namepre+"_logo["+linkid+"]' class=\"text_css\" /></div></td></tr></table>";
	var ele=document.createElement('div');
	ele.id="group_div_"+maxIndex;
	ele.innerHTML=s;
	E("linksdiv").appendChild(ele);
	maxIndex++;
}
</script>

	<table class="settop"><tr><td class="settop_left"><img src="../language/{$cache_langs[$_SYS['alangid']]['directory']}/flag.gif" title="{$effect_lang}" /></td><td><div id="smalltab_container"></div></td></tr></table>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1">
		<form id="linksform" onsubmit="return false;">
		<div class="tips_1">{$_AL['link.tips']}</div>
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1" width="100%">
			<tr style="font-weight:bold;color:#333333;"><td class="td_6"><div class="rowdiv_0" style="width:{$dwidth[1]}px;"><span class="warning">{$_AL['all.delete']}</span></div><div class="rowdiv_0" style="width:{$dwidth[2]}px;">{$_AL['link.orders']}</div><div class="rowdiv_0" style="width:{$dwidth[3]}px;">{$_AL['link.name']}</div><div class="rowdiv_0" style="width:{$dwidth[4]}px;">{$_AL['link.url']}</div><div class="rowdiv_0" style="width:{$dwidth[5]}px;">{$_AL['link.desc']}</div><div class="rowdiv_0" style="width:{$dwidth[6]}px;">{$_AL['link.logo']}</div></td></tr>
		</table>
		<div id="linksdiv">
EOT;
			for($i=0;$i<count($rows);$i++){
				$row=$rows[$i];
				echo("<script>addFriendLink(\"{$row['id']}\",\"{$row['ordernum']}\",\"{$row['name']}\",\"{$row['url']}\",\"{$row['content']}\",\"{$row['logo']}\");</script>");			
			}
			
echo <<<EOT
		</div>
			<table class="table_1" width="100%">
			<tr><td class="td_6"><a class="td_5_1a" href="javascript:addFriendLink('','','','','','')"><img src="images/ico_add.gif" border="0" /> {$_AL['link.add']}</a></td></tr>
			<tr><td class="td_3"><input class="button_css" type="button" value="  {$_AL['all.submit']}  " onclick="ajax_links()" /></td></tr>
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
pt.createTab("t1","{$_AL['link.title']}","",true,"n");
pt.init = function(){
	smallNowTab = pt.nowTab;
};
pt.onclick = function(){
	smallNowTab = pt.nowTab;
};		
pt.initTab();
pt.clickNowTab();

function ajax_links(){
	popwin.loading();
	ajaxPost("linksform","link_ajax.php?action=savelinks",ajax_links_callback);
}
function ajax_links_callback(data){
	var btns=[{value:" {$_AL['all.ok']} ",onclick:"popwin.close();mainifm.location.reload();",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,"{$_AL['all.edit.succeed']}","{$_AL['link.edit.succeed']}",btns,280,130);
	}else{
		popwin.showDialog(0,"{$_AL['all.con.failed']}",data,btns,280,130);
	}
}

</script>

EOT;
	break;
	/************************************** friendlink END ************************************************/
}	
?>
