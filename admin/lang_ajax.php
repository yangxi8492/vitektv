<?php
header("Content-Type:text/html; charset=utf-8");
require_once('./../inc/init.php');
require_once('./../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("lang")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.language.set'])));
}

$action=strFilter($_GET['action']);
switch($action){
	case "savelang":
		try{
			$lang_delid=$_POST['lang_delid'];

			//UPDATE
			$lang_ordernum=$_POST['lang_ordernum'];
			$lang_name=$_POST['lang_name'];
			$lang_directory=$_POST['lang_directory'];

			if(is_array($lang_name)) {
				foreach($lang_name as $key=>$tmp_name){
					//Create Cache Directory
					$cache_dir="../cache/{$key}";if (!is_dir($cache_dir)){@mkdir($cache_dir,0777);@fopen("$cache_dir/index.htm","a");}

					$langobj['ordernum']=intval($lang_ordernum[$key]);
					$langobj['name']=$lang_name[$key];
					$langobj['directory']=$lang_directory[$key];
					$db->row_update("langs",$langobj,"id={$key}");
				}
			}

			//INSERT
			$newlang_ordernum=$_POST['newlang_ordernum'];
			$newlang_name=$_POST['newlang_name'];
			$newlang_directory=$_POST['newlang_directory'];

			if(is_array($newlang_name)) {
				foreach($newlang_name as $key=>$tmp_name){
					$langobj['ordernum']=intval($newlang_ordernum[$key]);
					$langobj['name']=$newlang_name[$key];
					$langobj['directory']=$newlang_directory[$key];
					$db->row_insert("langs",$langobj);
					$_SYS['alangid']=$db->insert_id();

					//Create Cache Directory
					$cache_dir="../cache/{$_SYS['alangid']}";if (!is_dir($cache_dir)){@mkdir($cache_dir,0777);@fopen("$cache_dir/index.htm","a");}

					//System Channel
					$db->query_unbuffered("INSERT INTO `{$db->pre}channels` (channeltype, systemtype, title, content, langid) VALUES (1,1,'{$_AL['lang.sys.product']}','',{$_SYS['alangid']})");
					$db->query_unbuffered("INSERT INTO `{$db->pre}channels` (channeltype, systemtype, title, content, langid) VALUES (1,2,'{$_AL['lang.sys.contact']}','',{$_SYS['alangid']})");
					$db->query_unbuffered("INSERT INTO `{$db->pre}channels` (channeltype, systemtype, title, content, langid) VALUES (1,3,'{$_AL['lang.sys.msg']}','',{$_SYS['alangid']})");

					//Default Setting
					$setting['logo']='1';
					$setting['cur']=$_AL['lang.sys.cur'];
					$setting['banner1']='2';
					$setting['banner2']='3';
					$setting['bannerlink1']='';
					$setting['bannerlink2']='';
					$setting['securitycode']='1';
					$setting['webname']=$_AL['lang.sys.webname'];
					$setting['timeformat']='yyyy-mm-dd';
					$setting['timeoffset']='8';
					$setting['humantime']='0';
					$setting['isgzip']='0';
					$setting['isoff']='0';
					$setting['metakeywords']=$_AL['lang.sys.keyword'];
					$setting['metadescription']=$_AL['lang.sys.desc'];
					$setting['urlrewrite']='0';
					$setting['template']='6kzz';
					$setting['templatelang']='zh_cn.php';
					$setting['perpagepro']='12';
					$setting['perpageart']='15';
					$setting['perpagemsg']='10';
					$setting['signupsecuritycode']='0';
					$setting['loginsecuritycode']='0';
					$setting['msgsecuritycode']='0';

					saveSettings($setting);

					//Contact
					$contact['company']=$_AL['lang.ct.contact'];
					$contact['contact']=$_AL['lang.ct.contact'];
					$contact['email']=$_AL['lang.ct.email'];
					$contact['qq']=$_AL['lang.ct.qq'];
					$contact['phone']=$_AL['lang.ct.phone'];
					$db->row_delete("contact","langid={$_SYS['alangid']}");
					$contact['langid']=$_SYS['alangid'];
					$db->row_insert("contact",$contact);
				}
			}
			writeLangsCache();
			reBuildLang();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;

	case "setdefault":
		$setlangid=intval($_GET['setlangid']);
		$langobj['isdefault']=0;
		$db->row_update("langs",$langobj);
		$langobj['isdefault']=1;
		$langobj['ishidden']=0;
		$db->row_update("langs",$langobj,"id={$setlangid}");
		writeLangsCache();
		printRes("{$_AL['lang.ajax.set.succeed']}<script>reloadTop('admin.php?inc=lang&action=list');</script>");
		//succeedFlag();
	break;

	case "sethide":
		$setlangid=intval($_GET['setlangid']);
		$hide=intval($_GET['hide']);
		$uselangid=intval(getCookies('langid'));
		if($uselangid==$setlangid && $hide==1){
			printRes("{$_AL['lang.ajax.off.failed']}<script>setTimeout(function(){reloadSelf('admin.php?inc=lang&action=list');},2000);</script>");
		}

		$langobj['ishidden']=$hide;
		$db->row_update("langs",$langobj,"id={$setlangid}");
		writeLangsCache();
		printRes("{$_AL['lang.ajax.set.succeed']}<script>reloadTop('admin.php?inc=lang&action=list');</script>");
		//succeedFlag();
	break;

	case "dellang":
		$setlangid=intval($_GET['setlangid']);
		$row = $db->row_select_one("langs","isdefault=1");
		if($row['id']==$setlangid){
			exit($_AL['lang.ajax.deldef.failed']);
		}
		$uselangid=intval(getCookies('alangid'));
		if($uselangid==$setlangid){
			exit($_AL['lang.ajax.off.failed']);
		}

		$db->row_delete("langs","id={$setlangid}");
		$db->row_delete("favs","langid={$setlangid}");
		$db->row_delete("orderdetails","langid={$setlangid}");
		$db->row_delete("orders","langid={$setlangid}");
		$db->row_delete("articles","langid={$setlangid}");
		$db->row_delete("channels","langid={$setlangid}");
		$db->row_delete("contact","langid={$setlangid}");
		$db->row_delete("links","langid={$setlangid}");
		$db->row_delete("msgs","langid={$setlangid}");
		$db->row_delete("procates","langid={$setlangid}");
		$db->row_delete("products","langid={$setlangid}");
		$db->row_delete("settings","langid={$setlangid}");
		$db->row_query("delete from `{$db->pre}voteitems` where voteid in (select id from `{$db->pre}votes` where langid={$setlangid})");
		$db->row_delete("votes","langid={$setlangid}");

		delDir("./../cache/" . $setlangid);

		writeLangsCache();
		reBuildLang();
		succeedFlag();
	break;


	default:
		echo($_AL['all.noaction']);
	break;
}

function reBuildLang(){
	global $db,$_SYS;
	$rows = $db->row_select("langs","ishidden=0",0,"*","isdefault desc,ordernum");
	$langs=array();
	foreach($rows as $row){
		$_SYS['alangid']=$row['id'];
		writeSettingsCache();
		writeLinksCache();
		writeChannelsCache();
		writeProductsCateCache();
		writeContactCache();
		writeVotesCache();
		writeTemplatevarsCache();
	}
}

?>
