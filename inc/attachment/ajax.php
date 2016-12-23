<?php
header("Content-Type:text/html; charset=utf-8");
include_once('./../init.php');
require_once('./../cache.php');
require_once('./../../'.ADMIN_DIR.'/inc/adminfun.php');
require_once('./../../'.ADMIN_DIR.'/language/language.php');

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}


$action=strFilter($_GET["action"]);
switch($action){
	case "createFolder":
		try{
			$folder['title']=strFilter($_POST['newfoldername']);
			if(empty($folder['title'])){
				exit($_AL['folder.empty.name']);
			}
			$folder['updatetime']=time();
			$db->row_insert("folders",$folder);
			writeFoldersCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;

	case "renameFolder":
		try{
			$folderid=intval($_POST['folderid']);
			$folder['title']=strFilter($_POST['newfoldername']);
			if(empty($folder['title'])){
				exit($_AL['folder.empty.name']);
			}
			$db->row_update("folders",$folder,"id={$folderid}");
			writeFoldersCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;

	case "delFolder":
		try{
			$folderids=$_POST['ids'];
			$deltype=intval($_POST['deltype']);

			if(isIntArray($folderids)) {
				//ignore default dir
				foreach($folderids as $key=>$folderid){
					if(intval($folderid)==1){
						unset($folderids[$key]);
					}
				}

				//del dir
				$delfolderids=implode(",",$folderids);
				$db->row_delete("folders","id in ($delfolderids)");
				
				if($deltype==1){
					//del file
					$attrows=$db->row_select("attachments","folderid in ({$delfolderids})");
					foreach($attrows as $row){
						$filepath=INC_P."/../uploadfile/attachment/".$row['filepath'];
						if(file_exists($filepath)){
							@unlink($filepath);
						}
						$filepath=INC_P."/../uploadfile/thumb/".$row['filepath'];
						if(file_exists($filepath)){
							@unlink($filepath);
						}
						$db->row_delete("attachments","id={$row['id']}");
					}
				}elseif($deltype==0){
					//to default dir
					$attach['folderid']=1;
					$db->row_update("attachments",$attach, "folderid in ({$delfolderids})");
				}
				writeFoldersCache();
				succeedFlag();
			}else{
				exit($_AL['folder.choose.folder']);
			}

		}catch(Exception $e){
			echo($e);
		}
	break;

	case "deleteAttachment":
		$id=intval($_GET['id']);
		$row = $db->row_select_one("attachments","id={$id}");
		if(!empty($row)){
			$attachpath = INC_P."/../uploadfile/attachment/{$row['filepath']}";
			if(file_exists($attachpath)){
				unlink($attachpath);
			}
			$attachpath = INC_P."/../uploadfile/thumb/{$row['filepath']}";
			if(file_exists($attachpath)){
				unlink($attachpath);
			}

			$db->row_delete("attachments","id={$id}");
			succeedFlag();
		}else{
			exit($_AL['all.con.failed']);
		}
	break;

	case "delFiles":
		try{
			$fileids=$_POST['ids'];
			if(isIntArray($fileids)) {
				$delfileids=implode(",",$fileids);
				
				//del files
				$attrows=$db->row_select("attachments","id in ({$delfileids})");
				foreach($attrows as $row){
					$filepath=INC_P."/../uploadfile/attachment/".$row['filepath'];
					if(file_exists($filepath)){
						@unlink($filepath);
					}
					$filepath=INC_P."/../uploadfile/thumb/".$row['filepath'];
					if(file_exists($filepath)){
						@unlink($filepath);
					}
					$db->row_delete("attachments","id={$row['id']}");
				}

				succeedFlag();
			}else{
				exit($_AL['folder.choose.file']);
			}

		}catch(Exception $e){
			echo($e);
		}
	break;

	case "moveFiles":
		try{
			$fileids=$_POST['ids'];
			$targetfolder=intval($_POST['targetfolder']);

			if(isIntArray($fileids)) {
				$movefileids=implode(",",$fileids);
				$attach['folderid']=$targetfolder;
				$db->row_update("attachments",$attach, "id in ({$movefileids})");
				succeedFlag();
			}else{
				exit($_AL['folder.choose.file']);
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