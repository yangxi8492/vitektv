<?php
header("Content-Type:text/html; charset=utf-8");
require_once('../inc/init.php');
require_once('../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("link")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.frilink.list'])));
}

$action=strFilter($_GET['action']);
switch($action){
	case "savelinks":
		try{
			$links_delid=$_POST['links_delid'];

			//DELETE
			if(isIntArray($links_delid)) {
				$delids=implode(",",$links_delid);
				$db->row_delete("links","id in ($delids)");
			}

			//UPDATE
			$links_ordernum=$_POST['links_ordernum'];
			$links_name=$_POST['links_name'];
			$links_url=$_POST['links_url'];
			$links_content=$_POST['links_content'];
			$links_logo=$_POST['links_logo'];
			$links_lang=$_POST['links_lang'];

			if(is_array($links_name)) {
				foreach($links_name as $key=>$link_name){
					$linkobj['ordernum']=intval($links_ordernum[$key]);
					$linkobj['name']=$links_name[$key];
					$linkobj['url']=$links_url[$key];
					$linkobj['content']=$links_content[$key];
					$linkobj['logo']=$links_logo[$key];
					$linkobj['langid']=$_SYS['alangid'];
					$db->row_update("links",$linkobj,"id={$key}");
				}
			}

			//INSERT
			$newlinks_ordernum=$_POST['newlinks_ordernum'];
			$newlinks_name=$_POST['newlinks_name'];
			$newlinks_url=$_POST['newlinks_url'];
			$newlinks_content=$_POST['newlinks_content'];
			$newlinks_logo=$_POST['newlinks_logo'];
			$newlinks_lang=$_POST['newlinks_lang'];

			if(is_array($newlinks_name)) {
				foreach($newlinks_name as $key=>$link_name){
					$linkobj['ordernum']=intval($newlinks_ordernum[$key]);
					$linkobj['name']=$newlinks_name[$key];
					$linkobj['url']=$newlinks_url[$key];
					$linkobj['content']=$newlinks_content[$key];
					$linkobj['logo']=$newlinks_logo[$key];
					$linkobj['langid']=$_SYS['alangid'];
					$db->row_insert("links",$linkobj);
				}
			}
			writeLinksCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;

	default:
		echo($_AL['all.noaction']);
	break;
}

?>
