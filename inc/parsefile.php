<?php
class ParseFile{
	function ParseFile(){

	}
	function parse($message){
		$message = preg_replace("/\"attachment.php\?id=(\d+)\"/ies", "\$this->pfile('\\1','\\0',true)", $message);
		$msglower = strtolower($message);
		if(strpos($msglower, '[/file]') !== false) {
			$message = preg_replace("/\s*\[file\](\d+?)\[\/file\]\s*/ies", "\$this->pfile('\\1','\\0')", $message);
			if(strpos($msglower, '[file=') !== false) {
				$message = preg_replace("/\[file=(\d+)\]\s*(.+?)\s*\[\/file\]/ies", "\$this->pfile('\\1','\\0')", $message);
			}
		}
		return $message;
	}
	
	function pfile($fileid,$oldstr,$onlyimgurl=false) {
		global $db;
		$str=$oldstr;
		$fileid=intval($fileid);
		if(empty($fileid)){
			return $str;
		}
		$row=$db->row_select_one("attachments","id={$fileid}");
		if(empty($row)){
			return $str;
		}

		$fs=pathinfo_($row['filename']);
		$fs['extension'] = strtolower($fs['extension']);
		if($onlyimgurl){
			return "uploadfile/attachment/{$row['filepath']}";
		}

		if($fs['extension']=='swf'){
			$str=$this->parseflash("uploadfile/attachment/{$row['filepath']}");
		}elseif($row['type']==1 && $fs['extension']!='swf' ){
			//图片
			$str="<a href=\"uploadfile/attachment/{$row['filepath']}\" target=\"_blank\" title=\"\"><img src=\"uploadfile/attachment/{$row['filepath']}\" border=\"0\" ".($row['imgwidth']>640?"width=\"640px\"":"")." /></a>";
		}else{
			//普通附件
			$extarr = array(
				'pdf'=> 'pdf',	
				'ppt'=> 'ppt',
				'doc'=> 'doc',
				'mp3'=> 'media',
				'wmv'=> 'media',
				'mpeg'=> 'media',
				'mp4'=> 'media',
				'rm'=> 'media',
				'rmvb'=> 'media',
				'wav'=> 'media',
				'html'=> 'htm',	
				'htm'=> 'htm',	
				'swf'=> 'swf',
				'fla'=> 'swf',
				'flv'=> 'swf',
				'xls'=> 'xls',
				'txt'=> 'txt',
				'gif'=> 'img',
				'jpg'=> 'img',
				'jpeg'=> 'img',
				'png'=> 'img',
				'bmp'=> 'img',
				'zip'=> 'package',
				'rar'=> 'package',
				'7z'=> 'package',
				'gz'=> 'package',
				'tar'=> 'package',
			);
			$extimg = $extarr[$fs['extension']];
			$extimg = empty($extimg)?"txt":$extimg;
			$str="<span class=\"ext_small ext_small_{$extimg}\"><a href=\"attachment.php?id={$row['id']}\" target=\"_blank\" title=\"\">{$fs['basename']}</a></span>";
		}
		return $str;
	}

	function parseflash($src){
		return '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="550" height="400"><param name="allowScriptAccess" value="sameDomain"><param name="movie" value="'.$src.'"><param name="quality" value="high"><param name="bgcolor" value="#ffffff"><embed src="'.$src.'" quality="high" bgcolor="#ffffff" width="550" height="400" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';
	}


}

$parsefile=new ParseFile();
?>