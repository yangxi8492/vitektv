<?php
header("Content-Type:text/html; charset=utf-8");
require_once('../inc/init.php');
require_once('../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("procate")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.procate.list'])));
}


$action=strFilter($_GET['action']);
switch($action){
	case "saveset":
		try{
			$ordernums=$_POST['ordernum'];
			$title=$_POST['title'];
			if(is_array($ordernums)) {
				foreach($ordernums as $id => $value) {
					$procate['ordernum'] = intval($value);
					$procate['title'] = $title[$id];
					$db->row_update("procates",$procate,"id={$id}");
				}
			}
			writeProductsCateCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;
	
	case "modifyprocate":
		try{
			$doaction=strFilter($_POST['doaction']);
			$procate['alias']=strFilter($_POST['alias']);
			$procate['ishidden']=intval($_POST['ishidden']);
			$procate['pid']=intval($_POST['pid']);
			$procate['langid']=$_SYS['alangid'];
			$procate['title']=strFilter($_POST['title']);
			$procate['seotitle']=strFilter($_POST['seotitle']);
			$procate['metadesc']=strFilter($_POST['metadesc']);
			$procate['metakeywords']=strFilter($_POST['metakeywords']);
			$procate["picid"]=intval($_POST['picid']);
			
			$row=$db->row_select_one("attachments","id=".$procate['picid']);
			$procate['picpath']=$row['filepath'];
			
			if($doaction=="edit"){
				$id=intval($_POST['id']);
				$db->row_update("procates",$procate,"id={$id}");
				$procate['cateid']=$id;
			}else{
				$tmprow=$db->row_query_one("SELECT max(ordernum) AS morder FROM `{$db->pre}procates` WHERE langid={$_SYS['alangid']} Limit 1");
				$procate['ordernum']=++$tmprow['morder'];
				$db->row_insert("procates",$procate);
				$procate['cateid']=$db->insert_id();
			}
			writeProductsCateCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;

	case "sethide":
		$procateid=intval($_GET['procateid']);
		$hide=intval($_GET['hide']);
		$procate['ishidden']=$hide;
		$db->row_update("procates",$procate,"id={$procateid}");
		writeProductsCateCache();
		_header_("location:admin.php?inc=procate&action=set");
		//succeedFlag();
	break;

	
	case "delprocate":
		try{
			$cid=intval($_GET['cid']);
			if(empty($cid)){
				exit($_AL['all.parmerr']);
			}
			$rows=$db->row_select("procates","pid={$cid}");
			if(!empty($rows)){
				exit($_AL['procate.deldown.failed']);
			}

			//del products
			$db->row_delete("products","cid={$cid}");
			//del procates
			$db->row_delete("procates","id={$cid}");
			writeProductsCateCache();
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
