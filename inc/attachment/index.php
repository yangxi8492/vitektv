<?php
require_once('../init.php');
require_once getCacheFilePath('folders.php');
require_once('./../../'.ADMIN_DIR.'/language/language.php');
if(!isAdmin()){
	exit($_AL['all.notlogin']);
}

$file_size_limit = 20480;
$file_num_limit = 99999;
$file_types='*';
$file_newnum=20;
$today_uploaded=1;
$enter = intval($_GET["enter"]);
$folderid = intval($_GET["folderid"]);

if(isset($_GET["folderid"])){
	setCookies("lastfolderid",$folderid);
}else{
	$folderid = intval(getCookies("lastfolderid"));
}
$folderid=$folderid==0?1:$folderid;

//list
$lastfoldertype = intval(getCookies("lastfoldertype"));
if($enter==0 && $lastfoldertype==2){
	header("location:files.php?folderid={$folderid}");
	exit();
}
setCookies("lastfoldertype",1);

$types=explode(",",$file_types);
$file_types="";
foreach($types as $ext){
	if(!empty($ext)){
		$file_types.="*.{$ext};";
	}
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>SWF</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../<?php echo ADMIN_DIR;?>/language/language.js"></script>
<script type="text/javascript" src="js/swfupload.js"></script>
<script type="text/javascript" src="js/swfupload.queue.js"></script>
<script type="text/javascript" src="js/fileprogress.js"></script>
<script type="text/javascript" src="js/handlers.js"></script>
<script type="text/javascript" src="../../getfiles.php?t=js&v=<?php echo $_SYS['VERSION'];?>&f=util|ajax"></script>
<script type="text/javascript">
		var swfu;
		var oldindex=-10000;
		
		var daily_allow_num= <?php echo $file_num_limit;?>;
		var today_uploaded= <?php echo $today_uploaded;?>;
		var today_allow_num=daily_allow_num-today_uploaded;
		today_allow_num=today_allow_num<1?0.5:today_allow_num;


		window.onload = function() {
			var settings = {
				flash_url : getPath()+"/js/swfupload.swf",
				upload_url: getPath()+"/upload.php?folderid=<?php echo $folderid;?>",	// Relative to the SWF file
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
				file_size_limit : "<?php echo $file_size_limit; ?> KB",
				file_types : "<?php echo $file_types ?>",
				file_types_description : "All Files",
				file_upload_limit : today_allow_num,
				file_queue_limit : 0,     //number
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "images/btn.gif",	// Relative to the Flash file
				button_width: "100",
				button_height: "28",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: "",
				button_text_style: "",
				button_text_left_padding: 12,
				button_text_top_padding: 3,
				
				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
			
			var file;
			
			<?php
				$cond=$folderid>0?"folderid={$folderid}":"";
				$rows=$db->row_select("attachments",$cond, $file_newnum,"*","id desc");
				foreach($rows as $row){
					$md5str=md5($row['filepath'].$row['uploadtime']);
echo <<<EOT
			file = {};
			file.id = oldindex++;
			file.name = "{$row['filename']}";
			file.size = {$row['filesize']};
			file.isimg = {$row['type']};
			swfu.uploadSuccess(file,"{fileid:'{$row[id]}',filepath:'{$row[filepath]}', md5:'{$md5str}', isimg:'{$row[type]}'}",'appendChild');			
EOT;
				}
				
			?>
			InitPage();

	     };

		function getPath(){
			var locHref = location.href;
			var locArray = locHref.split("/");
			delete locArray[locArray.length-1];
			var dirTxt = locArray.join("/");
			return dirTxt;
		}

		//del file
		function delFile(divid, fileid, md5str){
			var filediv = document.getElementById(divid);
			 if (filediv != null){
				filediv.parentNode.removeChild(filediv);
			 }
			 ajaxGet("ajax.php?action=deleteAttachment&id="+fileid+"&md5="+md5str, delFile_callback);
		}

		function delFile_callback(data){

		}

		//use file
		function useFile(fileid,filename,isimg){
			window.parent.insertAttachment(fileid,filename,isimg);
		}

		function InitPage(){
			if(E("viewfolder"))E("viewfolder").onchange = function(){
				self.location.href="index.php?folderid="+this.value;
			};
			var folderid="<?php echo $folderid;?>";
			setSelect("viewfolder",folderid);
		}
	</script>
</head>
<body>
	<div style="height:30px;">
	<table width="100%"><tr><td style="width:290px;"><span id="spanButtonPlaceHolder"></span>
		<input id="btnCancel" type="button" value="Cancel" onclick="swfu.cancelQueue();" disabled="disabled" style="display:none;" class="button_css" /> <a href="folder.php" target="_self"><img src="images/btn_browser.gif" border="0" /></a> <img src="images/btn_close.gif" id="btnClose" onclick="window.parent.popwin.close()" border="0" style="cursor:pointer;" /></td><td><?php echo($_AL['attachment.filesize.nomore']); ?> <b id="span_size_limit"><?php echo $file_size_limit;?></b> KB</td>
		<td style="width:180px; text-align:right;"><img src="images/folder.gif" /> <select name="viewfolder" id="viewfolder"><?php echo $cache_foldersoption;?></select></td>
		</tr></table>

		 
	</div>
	<div style="clear:both; height:5px; overflow:hidden;"></div>
	<div class="progressWrapper progressTitle">
		<div class="progressContainer">
			<div class="progressCancel"></div><div class="progressName"><?php echo _LANG($_AL['attachment.filename'],array($file_newnum));?></div>
			<div class="progressSize"><?php echo($_AL['attachment.filesize'])?></div><div class="progressBarStatus"><?php echo($_AL['attachment.progress'])?></div><div class="progressX"><?php echo($_AL['attachment.del'])?></div>
		</div>
	</div>
	<div id="fsUploadProgress" class="fieldset flash"></div>
	<input type="hidden" value="GO" onclick="javascript:swfu.setFileUploadLimit(1);" />
</html>