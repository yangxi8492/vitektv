<?php
header("Content-Type:text/html; charset=utf-8");
require_once('../inc/init.php');
require_once('../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("products")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.product.man'])));
}

$action=strFilter($_GET['action']);
switch($action){
	
	case "saveproducts":
		try{
			$doaction=strFilter($_POST['doaction']);
			$pro['name']=strFilter($_POST['name']);
			$pro['alias']=strFilter($_POST['alias']);
			$pro['serialnum']=strFilter($_POST['serialnum']);
			$pro['price1']=strFilter($_POST['price1']);
			if(empty($pro['price1'])){unset($pro['price1']);}
			$pro['level']=intval($_POST['level']);
			$pro['store']=intval($_POST['store']);
			$pro['sold']=intval($_POST['sold']);
			$pro['cid']=intval($_POST['cid']);
			$pro['type']=intval($_POST['type']);
			$picids=array(
				intval($_POST['imginput_1']),
				intval($_POST['imginput_2']),
				intval($_POST['imginput_3']),
				intval($_POST['imginput_4']),
				intval($_POST['imginput_5'])				
			);

			$rows=$db->row_select("attachments","id in (".implode(',',$picids).")",0,"id,filepath");
			$picpathmap=array();
			foreach($rows as $row){
				$picpathmap[$row['id']]=$row['filepath'];
			}
			unset($rows);
			$picpaths=array();
			foreach($picids as $picid){
				array_push($picpaths, $picpathmap[$picid]);
			}
			unset($picpathmap);
			$pro['picids']=implode("\t",$picids);
			$pro['picpaths']=implode("\t",$picpaths);
			unset($picpaths);

			$pro['seotitle']=strFilter($_POST['seotitle']);
			$pro['metakeywords']=strFilter($_POST['metakeywords']);
			$pro['metadesc']=strFilter($_POST['metadesc']);
			$pro['content']=strFilter($_POST['content']);
			$pro['posttime']=time();
			$pro['langid']=$_SYS['alangid'];

			if($doaction=="edit"){
				$id=intval($_POST['id']);
				$db->row_update("products",$pro,"id={$id}");
			}else{
				$pro['ordernum']=100;
				$db->row_insert("products",$pro);
			}		
			if($doaction=="edit"){
				printRes("{$_AL['products.edit.succeed']}<script>setTimeout(function(){reloadSelf('admin.php?inc=products&action=list');},2000);</script>");
			}else{
				printRes("{$_AL['products.add.succeed']}<script>setTimeout(function(){reloadSelf('admin.php?inc=products&action=list');},2000);</script>");
			}

		}catch(Exception $e){
			echo($e);
		}
	break;


	case "doproductsorder":
		try{
			$ordernums=$_POST['ordernum'];
			if(is_array($ordernums)) {
				foreach($ordernums as $id => $value) {
					$product['ordernum'] = intval($value);
					$db->row_update("products",$product,"id={$id}");
				}
			}
			succeedFlag();

		}catch(Exception $e){
			echo($e);
		}
	break;

	case "doproducts":
		try{
			$postaction=$_POST['postaction'];
			$aids=$_POST['aids'];
			if(empty($aids)){
				exit($_AL['products.nosel']);
			}
			if(isIntArray($aids)) {
				$aidstr=implode(",",$aids);
				switch($postaction){
					case "":
					case "delProducts":
						$db->row_delete("products","id in ({$aidstr})");
						succeedFlag();
					break;
					default:
						echo($_AL['all.noaction']);
					break;
				}
			}
		}catch(Exception $e){
			echo($e);
		}
	break;
	default:
		echo($_AL['all.noaction']);
	break;
}
?>
