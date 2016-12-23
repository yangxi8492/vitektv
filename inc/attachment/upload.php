<?php
if (isset($_POST["PHPSESSID"])) {
	session_id($_POST["PHPSESSID"]);
}
require_once('../init.php');
require_once('../images_class.php');
require_once('./../../'.ADMIN_DIR.'/language/language.php');

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}

$folderid = intval($_GET["folderid"]);
$folderid=$folderid==0?1:$folderid;

$updir=INC_P."/../uploadfile/attachment/";
$thumbdir=INC_P."/../uploadfile/thumb/";

$renamed="1";
$overwrite="1";
if (isset($_FILES["Filedata"]) && is_uploaded_file($_FILES["Filedata"]["tmp_name"]) && $_FILES["Filedata"]["error"] == 0) {

	$upload_file=$_FILES["Filedata"];
	$file_info=pathinfo_($upload_file["name"]);
	$file_size = intval($_FILES["Filedata"]["size"]);
	$file_size_max = intval(20480*1024);

	if( $file_size > $file_size_max ){
		exit(_LANG($_AL['attachment.filesize.toobig'], array($file_size, $file_size_max)));
	}

	$file_type = $_FILES["Filedata"]["type"];
	$file_ext=strtolower($file_info["extension"]);
	$file_ext=addslashes(str_replace(array("'","/","\/","\"","."),array('','','','',''),$file_ext));
	$file_ext_allow= '*';
	if($file_ext_allow!="*" && !stristr(",{$file_ext_allow}," , ",{$file_ext},")){
		exit(_LANG($_AL['attachment.extnoallow'], array($file_ext, $file_ext_allow)));
	}

	//upload path
	switch($cache_settings['attachmentsave']){
		case "all":
			break;
		case "month":
			$upload_path=date(Y).date(m)."/";
			break;
		case "day":
			$upload_path=date(Y).date(m).date(d)."/";
			break;
		case "ext":
			$upload_path=$file_ext."/";
			break;
		default:
			$upload_path=date(Y).date(m)."/";
			break;
	}
	$thumb_path=$thumbdir.$upload_path;
	$upload_path=$updir.$upload_path;

	//Create Dir
	create($upload_path);
	create($thumb_path);

	//Rename
	$file_ext=in_array($file_ext,array('jpg','gif','jpeg','png'))?$file_ext:"6kfile";
	if($renamed){
		$upload_file['name']=str_replace('.','_',getmicrotime()).'_'.mt_rand(1000,9999).'.'.$file_ext;
	}
	//Server filename
	$upload_file['filename']=$upload_path.$upload_file['name'];
	$upload_file['thumb_path']=$thumb_path.$upload_file['name'];

	//File exist
	if(file_exists($upload_file['filename'])){
		if($overwrite){
			@unlink($upload_file['filename']);
		}else{
			$j=0;
			do{
				$j++;
				$temp_file=str_replace('.'.$file_ext,'('.$j.').'.$file_ext,$upload_file['filename']);
			}while (file_exists($temp_file));
			$upload_file['filename']=$temp_file;
			unset($temp_file);
			unset($j);
		}
	}

	if(@move_uploaded_file($upload_file["tmp_name"],$upload_file["filename"])){
		$attach['type']=0;
		$imginfo = @getimagesize($upload_file["filename"]);
		if(!empty($imginfo)){
			$attach['imgwidth'] = $imginfo[0];
			$attach['type'] = 1;
			if(intval($cache_global['isthumb'])==1 && ($imginfo[0]>intval($cache_global['thumbwidth']) || $imginfo[1]>intval($cache_global['thumbheight']))){
				try{
					$img = new Image($upload_file['filename']);
					if($imginfo[0]>$imginfo[1]){
						$img->changeSize(intval($cache_global['thumbwidth']),0);
					}else{
						$img->changeSize(0,intval($cache_global['thumbheight']));
					}
					$img->create($upload_file['thumb_path']);
					$img->free();

					//$img = new Image($upload_file['filename']);
					//$img->addWatermark(INC_P.'/../images/watermark.gif', 50);//添加水印，第一个参数是水印的图片地址，第二个参数是透明值
					//$img->create($upload_file['filename']);
					//$img->free();

				}catch(Exception $err){

				}
			}

		}
		$attach['filename'] =$file_info['basename'];
		$attach['filename']=addslashes(str_replace(array("'","/","\/","\""),array('','','',''),$attach['filename']));
		$attach['filesize'] =$file_size;
		$attach['filetype'] =$file_type;
		$attach['filepath'] =str_replace($updir,'',$upload_file['filename']);
		$attach['folderid'] =$folderid;
		$attach['uploadtime'] =time();
		$db->row_insert("attachments",$attach);

		$res['fileid'] = $db->insert_id();
		$res['md5'] = md5($attach['filepath'].$attach['uploadtime']);
		$res['filename'] = $upload_file["filename"];
		$res['filepath'] = $attach['filepath'];
		$res['isimg'] = $attach['type'];
		echo("{\"fileid\":\"{$res['fileid']}\", \"md5\":\"{$res['md5']}\", \"filename\":\"{$res['filename']}\", \"filepath\":\"{$res['filepath']}\", \"isimg\":\"{$res['isimg']}\" }");
	}else{
		echo ' ';
	}
} else {
	echo ''; // I have to return something or SWFUpload won't fire uploadSuccess
}

function create($dir)
{
	if (!is_dir($dir))
	{
		$temp = explode('/',$dir);
		$cur_dir = '';
		for($i=0;$i<count($temp);$i++)
		{
			$cur_dir .= $temp[$i].'/';
			if (!is_dir($cur_dir))
			{
				@mkdir($cur_dir,0777);
				@fopen("$cur_dir/index.htm","a");
			}
		}
	}
}
	
?>