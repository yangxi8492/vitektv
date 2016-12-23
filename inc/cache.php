<?php
//写文件
function writeFile($filename,$data,$method='rb+',$iflock=1,$check=1,$chmod=1){
	touch($filename);
	$handle = fopen($filename,$method);
	$iflock && flock($handle,LOCK_EX);
	fwrite($handle,$data);
	$method=='rb+' && ftruncate($handle,strlen($data));
	fclose($handle);
	$chmod && @chmod($filename,0777);
}

//输出变量为字符串
function varToStr($vari,$t = null) {
	switch (gettype($vari)) {
		case 'string':
			return "'".str_replace(array("\\","'"),array("\\\\","\'"),$vari)."'";
		case 'array':
			$output = "array(\r\n";
			foreach ($vari as $key => $value) {
				$output .= $t."\t".varToStr($key,$t."\t").' => '.varToStr($value,$t."\t");
				$output .= ",\r\n";
			}
			$output .= $t.')';
			return $output;
		case 'boolean':
			return $vari ? 'true' : 'false';
		case 'NULL':
			return 'NULL';
		case 'integer':
		case 'double':
		case 'float':
			return "'".(string)$vari."'";
	 }
	 return 'NULL';
}


function writeGlobalSettingsCache(){
	global $db,$_SYS;
	$row=getSettings();
	$apikey=$row['apikey'];
	$tmparr=array();
	if(!empty($apikey)){
		$row['apikey']=array();
		$tmpapis=explode("\n",$apikey);
		foreach($tmpapis as $tmpapi){
			$tmpapikv=explode(":",$tmpapi);
			if(is_array($tmpapikv) && !empty($tmpapikv[0]) && !empty($tmpapikv[1])){
				$row['apikey'][$tmpapikv[0]]=trim($tmpapikv[1]);
			}
		}
	}
	
	$str="<?php \r\n \$cache_settings = ".varToStr($row)."; \r\n?>";
	writeFile(getCacheFilePath("settings.php",$_SYS['alangid']),$str);
}

function writeSettingsCache(){
	global $db,$_SYS;
	$row=getSettings();
	$apikey=$row['apikey'];
	$tmparr=array();
	if(!empty($apikey)){
		$row['apikey']=array();
		$tmpapis=explode("\n",$apikey);
		foreach($tmpapis as $tmpapi){
			$tmpapikv=explode(":",$tmpapi);
			if(is_array($tmpapikv) && !empty($tmpapikv[0]) && !empty($tmpapikv[1])){
				$row['apikey'][$tmpapikv[0]]=trim($tmpapikv[1]);
			}
		}
	}
	
	$str="<?php \r\n \$cache_settings = ".varToStr($row)."; \r\n?>";
	writeFile(getCacheFilePath("settings.php",$_SYS['alangid']),$str);
}

function writeGlobalCache(){
	global $db,$_SYS;
	$row=getSettings(0);
	$apikey=$row['apikey'];
	$tmparr=array();
	if(!empty($apikey)){
		$row['apikey']=array();
		$tmpapis=explode("\n",$apikey);
		foreach($tmpapis as $tmpapi){
			$tmpapikv=explode(":",$tmpapi);
			if(is_array($tmpapikv) && !empty($tmpapikv[0]) && !empty($tmpapikv[1])){
				$row['apikey'][$tmpapikv[0]]=trim($tmpapikv[1]);
			}
		}
	}
	
	$str="<?php \r\n \$cache_global = ".varToStr($row)."; \r\n?>";
	writeFile(getCacheFilePath("global.php"),$str);
}


function writeLinksCache(){
	global $db,$_SYS;
	$rows = $db->row_select("links","langid={$_SYS['alangid']}",0,"*","ordernum");
	$link_logo=array();
	$link_text=array();
	foreach($rows as $row){
		if(empty($row['logo'])){
			array_push($link_text,$row);
		}else{
			array_push($link_logo,$row);
		}
	}
	$str="<?php \r\n \$cache_links_text = ".varToStr($link_text)."; \r\n\r\n \$cache_links_logo = ".varToStr($link_logo)."; \r\n?>";
	writeFile(getCacheFilePath("links.php",$_SYS['alangid']),$str);
}

function writeChannelsCache(){
	global $db,$_SYS;
	$channel=array();
	$channel_tree="\r\n";
	//$channel_option="";
	//$channel_option2="";
	/****1*****/
	$rows1=$db->row_select("channels","pid=0 and langid={$_SYS['alangid']}",0,"*","ordernum,id");
	for($i=0;$i<count($rows1);$i++){
		$row1=$rows1[$i];
		unset($row1['content']);
		$channel[$row1['id']]=$row1;
		$channel[$row1['id']]['childcid']=array();
		//$channel_option.="<option value=\"{$row1['id']}\">&gt;&gt; {$row1[title]}</option>";
		//$channel_option2.="<optgroup label=\"&gt;&gt;{$row1[title]}\">";
		/*****2****/
		$rows2=$db->row_select("channels","pid={$row1['id']} and langid={$_SYS['alangid']}",0,"*","ordernum,id");
		for($j=0;$j<count($rows2);$j++){
			$row2=$rows2[$j];
			unset($row2['content']);
			$channel[$row2['id']]=$row2;
			$channel[$row2['id']]['childcid']=array();
			$channel_tree.="array_push(\$cache_channels['$row2[pid]']['childcid'],'$row2[id]');\r\n";
			//$channel_option.="<option value=\"{$row2['id']}\"> &nbsp;|- {$row2[title]}</option>";
			//$channel_option2.="<option value=\"{$row2['id']}\"> &nbsp;|- {$row2[title]}</option>";
		}
		//$channel_option2.="</optgroup>";
	}
	$str="<?php\r\n";
	$str.="\$cache_channels = ".varToStr($channel)."; \r\n";
	$str.="{$channel_tree}\r\n";
	//$str.="\$cache_channelsoption = ".varToStr($channel_option)."; \r\n";
	//$str.="\$cache_channelsoption2 = ".varToStr($channel_option2)."; \r\n";
	$str.="?>";
	writeFile(getCacheFilePath("channels.php",$_SYS['alangid']),$str);
}


function writeProductsCateCache(){
	global $db,$_SYS;
	$products_cate=array();
	$products_cate_tree="\r\n";
	$products_cate_option="";
	$products_cate_option2="";
	/****1*****/
	$rows1=$db->row_select("procates","pid=0 and langid={$_SYS['alangid']}",0,"*","ordernum,id");
	for($i=0;$i<count($rows1);$i++){
		$row1=$rows1[$i];
		unset($row1['content']);
		$products_cate[$row1['id']]=$row1;
		$products_cate[$row1['id']]['childcid']=array();
		$products_cate_option.="<option value=\"{$row1['id']}\">&gt;&gt; {$row1[title]}</option>";
		$products_cate_option2.="<optgroup label=\"&gt;&gt;{$row1[title]}\">";
		/*****2****/
		$rows2=$db->row_select("procates","pid={$row1['id']} and langid={$_SYS['alangid']}",0,"*","ordernum,id");
		for($j=0;$j<count($rows2);$j++){
			$row2=$rows2[$j];
			unset($row2['content']);
			$products_cate[$row2['id']]=$row2;
			$products_cate[$row2['id']]['childcid']=array();
			$products_cate_tree.="array_push(\$cache_procates['$row2[pid]']['childcid'],'$row2[id]');\r\n";
			$products_cate_option.="<option value=\"{$row2['id']}\"> &nbsp;|- {$row2[title]}</option>";
			$products_cate_option2.="<option value=\"{$row2['id']}\"> &nbsp;|- {$row2[title]}</option>";
		}
		$products_cate_option2.="</optgroup>";
	}
	$str="<?php\r\n";
	$str.="\$cache_procates = ".varToStr($products_cate)."; \r\n";
	$str.="{$products_cate_tree}\r\n";
	$str.="\$cache_procates_option = ".varToStr($products_cate_option)."; \r\n";
	$str.="\$cache_procates_option2 = ".varToStr($products_cate_option2)."; \r\n";
	$str.="?>";
	writeFile(getCacheFilePath("procates.php",$_SYS['alangid']),$str);
}

function writeLangsCache(){
	global $db;
	$rows = $db->row_select("langs","ishidden=0",0,"*","isdefault desc,ordernum");
	$langs=array();
	foreach($rows as $row){
		$langs[$row['id']]=$row;
	}
	$str="<?php \r\n \$cache_langs = ".varToStr($langs)."; \r\n?>";
	writeFile(getCacheFilePath("langs.php"),$str);
}

function writeUsersCache(){
	global $db;
	$rows = $db->row_select("users","id>0",0,"id,username,popedom");
	$users=array();
	foreach($rows as $row){
		$users[$row['id']]=$row;
	}
	$str="<?php \r\n \$cache_users = ".varToStr($users)."; \r\n?>";
	writeFile(getCacheFilePath("users.php"),$str);
}

function writeContactCache(){
	global $db,$_SYS;
	$rows = $db->row_select("contact","langid={$_SYS['alangid']}",0,"*","langid");
	$contacts=array();
	foreach($rows as $row){
		array_push($contacts,$row);
	}
	$str="<?php \r\n \$cache_contacts = ".varToStr($contacts)."; \r\n?>";
	writeFile(getCacheFilePath("contacts.php",$_SYS['alangid']),$str);
}


function writeVotesCache(){
	global $db,$_SYS;
	$votes=array();
	/****1*****/
	$rows1=$db->row_select("votes","langid={$_SYS['alangid']}",0,"id,maxvotes,starttime,stoptime,title","ordernum,id");
	for($i=0;$i<count($rows1);$i++){
		$row1=$rows1[$i];
		$votes[$row1['id']]=$row1;
		$votes[$row1['id']]['voteitems']=array();
		/*****2****/
		$rows2=$db->row_select("voteitems","voteid={$row1['id']}",0,"id,title","id");
		$votes[$row1['id']]['voteitems']=$rows2;
	}
	$str="<?php\r\n";
	$str.="\$cache_votes = ".varToStr($votes)."; \r\n";
	$str.="?>";
	writeFile(getCacheFilePath("votes.php",$_SYS['alangid']),$str);
}

function writeTemplatevarsCache(){
	require_once(INC_P.'/parsefile.php');
	global $db,$_SYS;
	$rows = $db->row_select("templatevars","langid={$_SYS['alangid']}",0,"*");
	$cache_vars=array();
	foreach($rows as $row){
		$row['tvalue']=$parsefile->parse($row['tvalue']);
		array_push($cache_vars,$row);
	}
	$str="<?php \r\n \$cache_templatevars = ".varToStr($cache_vars)."; \r\n\r\n ?>";
	writeFile(getCacheFilePath("templatevars.php",$_SYS['alangid']),$str);
}

function writeFoldersCache(){
	global $db;
	$rows = $db->row_select("folders","",0,"*","id");
	$folders=array();
	$folders_option="";

	foreach($rows as $row){
		$folders[$row['id']]=$row;
		$folders_option.="<option value=\"{$row['id']}\">{$row['title']}</option>";
	}
	$str="<?php \r\n \$cache_folders = ".varToStr($folders)."; \r\n";
	$str.="\$cache_foldersoption = ".varToStr($folders_option)."; \r\n";
	$str.="?>";

	writeFile(getCacheFilePath("folders.php"),$str);
}


?>