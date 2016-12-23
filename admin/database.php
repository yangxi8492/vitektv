<?php
if(!hasPopedom("database")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.data.man'])));
}

$database = new db_class($_DB['hostname'], $_DB['username'], $_DB['password'], $_DB['database']);
$backdir = "backup/" . md5($cache_settings['salt']);
mysql_query("set names utf8");
if(!file_exists($backdir)){
	create($backdir);
}
echo("<script>var links={}; ".
"links.t1='admin.php?inc=database&action=backupform';".
"links.t2='admin.php?inc=database&action=restoreform';".
"links.t3='admin.php?inc=database&action=sqllist';".
"</script>");
switch ($action) {
	/************************************** backupform BEGIN ************************************************/
	case "backupform" :
		$tablesopt = '';
		$database->query("show table status from `{$_DB['database']}`");
		while ($database->nextrecord()) {
			if(strpos($database->f('Name'), $_DB['prefix'])===0){}else{continue;}
			$tablesopt .= "<option value='" . $database->f('Name') . "'>" . $database->f('Name') . "</option>";
		}
		$_AL['database.b.tips']=_LANG($_AL['database.b.tips'],array($backdir));
		echo<<<EOT
	<style>
		.td_1{width:400px; line-height:200%;}
	</style>
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1">
		<form id="backform" action="admin.php?inc=database&action=backup" method="POST">
		<div class="tips_1">{$_AL['database.b.tips']}</div>
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['database.b.type']}:</td><td class=""></td></tr>
			<tr><td class="td_1">
				<input type="radio" name="bfzl" value="quanbubiao" class="radio_css" checked="true" /> {$_AL['database.b.alldata']}<br />
				<input type="radio" name="bfzl" value="danbiao" class="radio_css" /> {$_AL['database.b.onetable']} <select name="tablename" onchange="setRadioValue('bfzl','danbiao')"><option value="">{$_AL['all.pleasechoose']}</option>{$tablesopt}</select>
			</td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['database.b.sp']}:</td><td class=""></td></tr>
			<tr><td class="td_1"><input type="checkbox" name="fenjuan" value="yes" class="checkbox_css" /> {$_AL['database.b.spinput']} <input name="filesize" type="text" size="10"  class="text_css" /> KB</td><td class="td_2"></td></tr>
			<tr><td class="td_0">{$_AL['database.b.target']}:</td><td class=""></td></tr>
			<tr><td class="td_1">
				<input type="radio" name="weizhi" value="server" checked=true /> {$_AL['database.b.2server']}<br />
				<input type="radio" name="weizhi" value="localpc" /> {$_AL['database.b.2local']}
			</td><td class="td_2"></td></tr>
			<tr><td class="td_3"><input class="button_css" type="submit" value="  {$_AL['database.b.submit']}  " /></td><td class=""></td></tr>
		</table>
		</form>
	</div>
	<div id="t2"></div>
	<div id="t3"></div>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['database.tab.backup']}","",true,"n");
	pt.createTab("t2","{$_AL['database.tab.restore']}","",false,"n");
	pt.createTab("t3","{$_AL['database.tab.man']}","",false,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
		if(smallNowTab=='t1'){return;}
		eval("self.location.href=links."+smallNowTab+";");
	};		
	pt.initTab();
	pt.clickNowTab();
	
	</script>
EOT;
		break;
		/************************************** backupform END ************************************************/

	/************************************** restoreform BEGIN ************************************************/
	case "restoreform" :
		$sqlfilestr = '';
		$handle = opendir("./{$backdir}");
		while ($file = readdir($handle)) {
			if (preg_match("/^[0-9]{8,8}([0-9a-z_]+)(\.sql)$/i", $file))
				$sqlfilestr .= "<option value='$file'>$file</option>";
		}
		closedir($handle);

		echo<<<EOT
	<style>
		.td_1{width:400px; line-height:200%;}
	</style>
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1"></div>
	<div id="t2">
		<form id="restoreform" action="admin.php?inc=database&action=restore" enctype="multipart/form-data" method="POST">
		<div class="tips_1">{$_AL['database.r.tips']}
		</div>
		<div class="div_clear" style="height:10px;"></div>
		<table class="table_1">
			<tr><td class="td_0">{$_AL['database.r.title']}:</td><td class=""></td></tr>
			<tr><td class="td_1" style="line-height:200%;">
				<input type="radio" name="restorefrom" value="server" class="radio_css" checked="true" /> {$_AL['database.r.fserver']} <select name="serverfile"><option value="">{$_AL['all.pleasechoose']}</option>{$sqlfilestr}</select><br />
				<input type="radio" name="restorefrom" value="localpc" /> {$_AL['database.r.flocal']} <input type="file" name="myfile" onclick="setRadioValue('restorefrom','localpc')" />
				</td><td class="td_2"></td></tr>
			<tr><td class="td_3"><input class="button_css" type="submit" value="  {$_AL['database.r.submit']}  " /></td><td class=""></td></tr>
		</table>
		</form>	
	</div>
	<div id="t3"></div>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['database.tab.backup']}","",false,"n");
	pt.createTab("t2","{$_AL['database.tab.restore']}","",true,"n");
	pt.createTab("t3","{$_AL['database.tab.man']}","",false,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
		if(smallNowTab=='t2'){return;}
		eval("self.location.href=links."+smallNowTab+";");
	};		
	pt.initTab();
	pt.clickNowTab();
	
	</script>
EOT;
		break;
		/************************************** restoreform END ************************************************/


	/************************************** sqllist BEGIN ************************************************/
	case "sqllist" :
		$dwidth=array(0,35,300,200);
		echo<<<EOT
	<div id="smalltab_container"></div>
	<div class="smalltab_line"></div>
	<div class="div_clear" style="height:10px;"></div>
	<div id="t1"></div>
	<div id="t2"></div>
	<div id="t3">
EOT;
	echo("<form id=\"sqlform\" action=\"admin.php?inc=database&action=delete\" method=\"POST\">");
	echo("<table class=\"table_1\" width=\"100%\">");
	echo("<tr style=\"font-weight:bold;color:#333333;\"><td class=\"row_0\">".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[1]}px;\">{$_AL['database.m.del']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[2]}px;\">{$_AL['database.m.file']}</div>".
		"<div class=\"rowdiv_0\" style=\"width:{$dwidth[3]}px;\">{$_AL['all.control']}</div>");
	$handle = opendir("./{$backdir}");
	while ($file = readdir($handle)) {
		if (preg_match("/^[0-9]{8,8}([0-9a-z_]+)(\.sql)$/i", $file)){
			$checkboxstr="<input type=\"checkbox\" value=\"{$file}\" name=\"fileids[]\" class=\"checkbox_css\" />";
	
			echo("<tr><td class=\"row_0\" style=\"line-height:150%;\">".
				"<div class='rowdiv_0' style='width:{$dwidth[1]}px;'>{$checkboxstr}</div>".
				"<div class='rowdiv_0' style='width:{$dwidth[2]}px;'><a href=\"admin.php?inc=database&action=download&fileid={$file}\" target=\"_blank\">{$file}</a>&nbsp;</div>".
				"<div class='rowdiv_0' style='width:{$dwidth[3]}px;'><a href=\"admin.php?inc=database&action=download&fileid={$file}\">{$_AL['database.m.down']}</a></div>");			
		}	
	}
	closedir($handle);
	echo("</table>");
	echo("<table width=100%><tr><td><input type=\"checkbox\" onclick=\"selectAll('sqlform',this.checked)\" class=\"checkbox_css\" /> {$_AL['all.selectall']} <input type=\"submit\" value=\"{$_AL['all.delete']}\" class=\"button_css\" /></td><td></td></tr></table>");
echo <<<EOT
	</div>
	<div class="div_clear" style="height:30px;"></div>
	<script>
	var smallNowTab;
	var pt = new Tabs();
	pt.classpre="smalltab_";
	pt.container = "smalltab_container";
	pt.createTab("t1","{$_AL['database.tab.backup']}","",false,"n");
	pt.createTab("t2","{$_AL['database.tab.restore']}","",false,"n");
	pt.createTab("t3","{$_AL['database.tab.man']}","",true,"n");
	pt.init = function(){
		smallNowTab = pt.nowTab;
	};
	pt.onclick = function(){
		smallNowTab = pt.nowTab;
		if(smallNowTab=='t3'){return;}
		eval("self.location.href=links."+smallNowTab+";");
	};		
	pt.initTab();
	pt.clickNowTab();
	
	</script>
EOT;
		break;
		/************************************** sqllist END ************************************************/

		/************************************** download BEGIN ************************************************/
	case "download" :
		ob_end_clean();
		$fileid=strFilter($_GET['fileid']);	
		$filepath="{$backdir}/".$fileid;
		if(!file_exists($filepath)){
			exit('file not exist');
		}
		
		$filename=stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')?urlencode($fileid):$fileid;
		_header_('Content-Encoding: none');
		_header_('Content-Type: application/octet-stream');
		_header_('Content-Disposition: attachment; filename="'.$filename.'"');
		_header_('Content-Length: '.filesize($filepath));
		getlocalfile($filepath);
		
	break;
		/************************************** download END ************************************************/

		/************************************** delete BEGIN ************************************************/
	case "delete" :
		$fileids=$_POST['fileids'];
		if(!empty($fileids) && is_array($fileids)){
			foreach($fileids as $fileid){
				if(file_exists("./{$backdir}/" . $fileid)){
					@unlink("./{$backdir}/" . $fileid);
					$msgs[]=$_AL['database.d.succeed'].$fileid;
				}
			}
		}else{
			$msgs[] = $_AL['database.d.choose'];
		}
		show_msg($msgs);
		
	break;
		/************************************** delete END ************************************************/


		/************************************** backup BEGIN************************************************/
	case "backup" :

		if ($_POST['weizhi'] == "localpc" && $_POST['fenjuan'] == 'yes') {
			$msgs[] = $_AL['database.b2.t1'];
			show_msg($msgs);
			pageend();
		}
		if ($_POST['fenjuan'] == "yes" && !$_POST['filesize']) {
			$msgs[] = $_AL['database.b2.t2'];
			show_msg($msgs);
			pageend();
		}
		if ($_POST['weizhi'] == "server" && !writeable("./{$backdir}")) {
			$msgs[] = _LANG($_AL['database.b2.t3'], array($backdir));
			show_msg($msgs);
			pageend();
		}

		/*----------备份全部表-------------*/
		if ($_POST['bfzl'] == "quanbubiao") {
			if (!$_POST['fenjuan']) {
				if (!$tables = $database->query("show table status from {$_DB['database']}")) {
					$msgs[] = $_AL['database.b2.t4'];
					show_msg($msgs);
					pageend();
				}
				$sql = "";
				while ($database->nextrecord($tables)) {
					if(strpos($database->f('Name'), $_DB['prefix'])===0){}else{continue;}
					$table = $database->f("Name");
					$sql .= make_header($table);
					$database->query("select * from $table");
					$num_fields = $database->nf();
					while ($database->nextrecord()) {
						$sql .= make_record($table, $num_fields);
					}
				}
				$filename = date("Ymd", time()) . "_all.sql";
				if ($_POST['weizhi'] == "localpc")
					down_file($sql, $filename);
				elseif ($_POST['weizhi'] == "server") {
					if (write_file($sql, $filename))
						$msgs[] = _LANG($_AL['database.b2.t5'], array("/{$backdir}/{$filename}"));
					else
						$msgs[] = $_AL['database.b2.t6'];
					show_msg($msgs);
					pageend();
				}
			} else {
				if (!$_POST['filesize']) {
					$msgs[] = $_AL['database.b2.t7'];
					show_msg($msgs);
					pageend();
				}
				if (!$tables = $database->query("show table status from {$_DB['database']}")) {
					$msgs[] = $_AL['database.b2.t8'];
					show_msg($msgs);
					pageend();
				}
				$sql = "";
				$p = 1;
				$filename = date("Ymd", time()) . "_all";
				while ($database->nextrecord($tables)) {
					if(strpos($database->f('Name'), $_DB['prefix'])===0){}else{continue;}
					$table = $database->f("Name");
					$sql .= make_header($table);
					$database->query("select * from $table");
					$num_fields = $database->nf();
					while ($database->nextrecord()) {
						$sql .= make_record($table, $num_fields);
						if (strlen($sql) >= $_POST['filesize'] * 1000) {
							$filename .= ("_v" . $p . ".sql");
							if (write_file($sql, $filename))
								$msgs[] = _LANG($_AL['database.b2.t9'], array("{$p}","/{$backdir}/{$filename}"));
							else
								$msgs[] = _LANG($_AL['database.b2.t10'], array($_POST['tablename']));
							$p++;
							$filename = date("Ymd", time()) . "_all";
							$sql = "";
						}
					}
				}
				if ($sql != "") {
					$filename .= ("_v" . $p . ".sql");
					if (write_file($sql, $filename))
						$msgs[] = _LANG($_AL['database.b2.t9'], array("{$p}","/{$backdir}/{$filename}"));
				}
				show_msg($msgs);
				/*---------------------分卷结束*/
			} /*--------------------------------------*/
			/*--------备份全部表结束*/
		} /*---------------------------------------------*/

		/*--------备份单表------*/
		elseif ($_POST['bfzl'] == "danbiao") { /*------------*/
			if (!$_POST['tablename']) {
				$msgs[] = $_AL['database.b2.t11'];
				show_msg($msgs);
				pageend();
			}
			/*--------不分卷*/
			if (!$_POST['fenjuan']) { /*-------------------------------*/
				$sql = make_header($_POST['tablename']);
				$database->query("select * from " . $_POST['tablename']);
				$num_fields = $database->nf();
				while ($database->nextrecord()) {
					$sql .= make_record($_POST['tablename'], $num_fields);
				}
				$filename = date("Ymd", time()) . "_" . $_POST['tablename'] . ".sql";
				if ($_POST['weizhi'] == "localpc")
					down_file($sql, $filename);
				elseif ($_POST['weizhi'] == "server") {
					if (write_file($sql, $filename))
						$msgs[] = _LANG($_AL['database.b2.t12'], array("{$_POST['tablename']}","/{$backdir}/{$filename}"));
					else
						$msgs[] = _LANG($_AL['database.b2.t10'], array($_POST['tablename']));
					show_msg($msgs);
					pageend();
				}
				/*----------------不要卷结束*/
			} /*------------------------------------*/
			/*----------------分卷*/
			else { /*--------------------------------------*/
				if (!$_POST['filesize']) {
					$msgs[] = $_AL['database.b2.t7'];
					show_msg($msgs);
					pageend();
				}
				$sql = make_header($_POST['tablename']);
				$p = 1;
				$filename = date("Ymd", time()) . "_" . $_POST['tablename'];
				$database->query("select * from " . $_POST['tablename']);
				$num_fields = $database->nf();
				while ($database->nextrecord()) {
					$sql .= make_record($_POST['tablename'], $num_fields);
					if (strlen($sql) >= $_POST['filesize'] * 1000) {
						$filename .= ("_v" . $p . ".sql");
						if (write_file($sql, $filename))
							$msgs[] = _LANG($_AL['database.b2.t12'], array("{$_POST['tablename']}","/{$backdir}/{$filename}"));
						else
							$msgs[] = _LANG($_AL['database.b2.t10'], array($_POST['tablename']));
						$p++;
						$filename = date("Ymd", time()) . "_" . $_POST['tablename'];
						$sql = "";
					}
				}
				if ($sql != "") {
					$filename .= ("_v" . $p . ".sql");
					if (write_file($sql, $filename))
						$msgs[] = _LANG($_AL['database.b2.t13'], array("{$_POST['tablename']}","{$p}","/{$backdir}/{$filename}"));
				}
				show_msg($msgs);
				/*----------分卷结束*/
			} /*--------------------------------------------------*/
			/*----------备份单表结束*/
		} /*----------------------------------------------*/

		break;
		/************************************** backup END ************************************************/

		/************************************** restore BEGIN ************************************************/
	case "restore" :
		if ($_POST['restorefrom'] == "server") { /**************/
			if (!$_POST['serverfile']) {
				$msgs[] = $_AL['database.r2.t1'];
				show_msg($msgs);
				pageend();
			}
			if (!preg_match("/_v[0-9]+/i", $_POST['serverfile'])) {
				$filename = "./{$backdir}/" . $_POST['serverfile'];
				if (import($filename))
					$msgs[] = _LANG($_AL['database.r2.t2'], array($_POST['serverfile']));
				else
					$msgs[] = _LANG($_AL['database.r2.t3'], array($_POST['serverfile']));
				show_msg($msgs);
				pageend();
			} else {
				$filename = "./{$backdir}/" . $_POST['serverfile'];
				if (import($filename))
					$msgs[] = _LANG($_AL['database.r2.t2'], array($_POST['serverfile']));
				else{
					$msgs[] = _LANG($_AL['database.r2.t3'], array($_POST['serverfile']));
					show_msg($msgs);
					pageend();
				}
				$voltmp = explode("_v", $_POST['serverfile']);
				$volname = $voltmp[0];
				$volnum = explode(".sq", $voltmp[1]);
				$volnum = intval($volnum[0]) + 1;
				$tmpfile = $volname . "_v" . $volnum . ".sql";
				if (file_exists("./{$backdir}/" . $tmpfile)) {
					$msgs[] = _LANG($_AL['database.r2.t4'], array($tmpfile));
					wSESSION('data_file',$tmpfile);
					show_msg($msgs);
					sleep(3);
					echo "<script language='javascript'>";
					echo "location='restore.php';";
					echo "</script>";
				} else {
					$msgs[] = $_AL['database.r2.t5'];
					show_msg($msgs);
				}
			}
			/**************服务器恢复结束*/
		} /********************************************/
		/*****************本地恢复*/
		if ($_POST['restorefrom'] == "localpc") {
			/**************/
			switch ($_FILES['myfile']['error']) {
				case 1 :
				case 2 :
					$msgs[] = $_AL['database.r2.t6'];
					break;
				case 3 :
					$msgs[] = $_AL['database.r2.t7'];
					break;
				case 4 :
					$msgs[] = $_AL['database.r2.t8'];
					break;
				case 0 :
					break;
			}
			if ($msgs) {
				show_msg($msgs);
				pageend();
			}
			$fname = date("Ymd", time()) . "_upload.sql";
			if (is_uploaded_file($_FILES['myfile']['tmp_name'])) {
				copy($_FILES['myfile']['tmp_name'], "./{$backdir}/" . $fname);
			}

			if (file_exists("./{$backdir}/" . $fname)) {
				$msgs[] = $_AL['database.r2.t9'];
				if (import("./{$backdir}/" . $fname)) {
					$msgs[] = $_AL['database.r2.t10'];
					unlink("./{$backdir}/" . $fname);
				} else
					$msgs[] = $_AL['database.r2.t11'];
			} else
				 $msgs[] = $_AL['database.r2.t12'];
			show_msg($msgs);
			/****本地恢复结束*****/
		} /****************************************************/
		/****************************主程序结束*/
} /**********************************/
/*************************剩余分卷备份恢复**********************************/
if (!$_POST['act'] && rSESSION('data_file')) {
	$filename = "./{$backdir}/" . rSESSION('data_file');
	if (import($filename))
		$msgs[] = _LANG($_AL['database.r2.t2'], array(rSESSION('data_file')));
	else {
		$msgs[] = _LANG($_AL['database.r2.t3'], array(rSESSION('data_file')));
		show_msg($msgs);
		pageend();
	}
	$voltmp = explode("_v", rSESSION('data_file'));
	$volname = $voltmp[0];
	$volnum = explode(".sq", $voltmp[1]);
	$volnum = intval($volnum[0]) + 1;
	$tmpfile = $volname . "_v" . $volnum . ".sql";
	if (file_exists("./{$backdir}/" . $tmpfile)) {
		$msgs[] = _LANG($_AL['database.r2.t4'], array($tmpfile));
		wSESSION('data_file',$tmpfile);
		show_msg($msgs);
		sleep(3);
		echo "<script language='javascript'>";
		echo "location='restore.php';";
		echo "</script>";
	} else {
		$msgs[] = "{$_AL['database.r2.t5']}";
		uSESSION('data_file');
		show_msg($msgs);
	}

	break;
	/************************************** restore END ************************************************/

}

function import($fname) {
	global $database;
	$sqls = file($fname);
	foreach ($sqls as $sql) {
		str_replace("\r", "", $sql);
		str_replace("\n", "", $sql);
		if (!$database->query(trim($sql)))
			return false;
	}
	return true;
}

function write_file($sql, $filename) {
	$re = true;
	global $backdir;
	if (!@ $fp = fopen("./{$backdir}/" . $filename, "w+")) {
		$re = false;
		echo "failed to open target file";
	}
	if (!@ fwrite($fp, $sql)) {
		$re = false;
		echo "failed to write file";
	}
	if (!@ fclose($fp)) {
		$re = false;
		echo "failed to close target file";
	}
	return $re;
}

function down_file($sql, $filename) {
	ob_end_clean();
	header("Content-Encoding: none");
	header("Content-Type: " . (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));

	header("Content-Disposition: " . (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ') . "filename=" . $filename);

	header("Content-Length: " . strlen($sql));
	header("Pragma: no-cache");

	header("Expires: 0");
	echo $sql;
	$e = ob_get_contents();
	ob_end_clean();
}

function writeable($dir) {

	if (!is_dir($dir)) {
		create($dir);
	}

	if (is_dir($dir)) {

		if ($fp = @ fopen("$dir/test.test", 'w')) {
			@ fclose($fp);
			@ unlink("$dir/test.test");
			$writeable = 1;
		} else {
			$writeable = 0;
		}

	}

	return $writeable;

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

function getlocalfile($filename, $readmod = 2, $range = 0) {
	if($readmod == 1 || $readmod == 3 || $readmod == 4) {
		if($fp = @fopen($filename, 'rb')) {
			@fseek($fp, $range);
			if(function_exists('fpassthru') && ($readmod == 3 || $readmod == 4)) {
				@fpassthru($fp);
			} else {
				echo @fread($fp, filesize($filename));
			}
		}
		@fclose($fp);
	} else {
		@readfile($filename);
	}
	@flush(); @ob_flush();
}


function make_header($table) {
	global $database;
	$sql = "DROP TABLE IF EXISTS " . $table . "\n";
	$database->query("show create table " . $table);
	$database->nextrecord();
	$tmp = preg_replace("/\n/", "", $database->f("Create Table"));
	$sql .= $tmp . "\n";
	return $sql;
}

function make_record($table, $num_fields) {
	global $database;
	$comma = "";
	$sql .= "INSERT INTO " . $table . " VALUES(";
	for ($i = 0; $i < $num_fields; $i++) {
		$sql .= ($comma . "'" . mysql_real_escape_string($database->record[$i]) . "'");
		$comma = ",";
	}
	$sql .= ")\n";
	return $sql;
}

function show_msg($msgs) {
	global $_AL;
	$tips = "<ul>";
	while (list ($k, $v) = each($msgs)) {
		$tips .= "<li>" . $v . "</li>";
	}
	$tips .= "</ul>";
	print<<<EOT
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>
		.succeedtips{line-height:180%; background:#E2F4FC; padding:10px; color:#128CB2; font-size:12px;}
		.succeedtips a:link,.succeedtips a:active,.succeedtips a:visited,.succeedtips a:hover{color:#128CB2;}
	</style>
	</head>
	<body>
	<div class="succeedtips"><b>{$tips}</b><a href="javascript:history.go(-1);">&gt;&gt;{$_AL['database.r2.t13']}</a></div>
	</body>
	</html>
EOT;
	exit ();

}

function pageend() {
	exit ();
}

class db_class {

	var $linkid;
	var $sqlid;
	var $record;

	function db_class($host = "", $username = "", $password = "", $database = "") {
		if (!$this->linkid)
			@ $this->linkid = mysql_connect($host, $username, $password) or die("{$_AL['database.r2.t14']}.");
		@ mysql_select_db($database, $this->linkid) or die("{$_AL['database.r2.t15']}");
		return $this->linkid;
	}

	function query($sql) {
		if ($this->sqlid = mysql_query($sql, $this->linkid))
			return $this->sqlid;
		else {
			$this->err_report($sql, mysql_error);
			return false;
		}
	}

	function nr($sql_id = "") {
		if (!$sql_id)
			$sql_id = $this->sqlid;
		return mysql_num_rows($sql_id);
	}

	function nf($sql_id = "") {
		if (!$sql_id)
			$sql_id = $this->sqlid;
		return mysql_num_fields($sql_id);
	}

	function nextrecord($sql_id = "") {
		if (!$sql_id)
			$sql_id = $this->sqlid;
		if ($this->record = mysql_fetch_array($sql_id))
			return $this->record;
		else
			return false;
	}

	function f($name) {
		if ($this->record[$name])
			return $this->record[$name];
		else
			return false;
	}

	function close() {
		mysql_close($this->linkid);
	}

	function lock($tblname, $op = "WRITE") {
		if (mysql_query("lock tables " . $tblname . " " . $op))
			return true;
		else
			return false;
	}

	function unlock() {
		if (mysql_query("unlock tables"))
			return true;
		else
			return false;
	}

	function ar() {
		return @ mysql_affected_rows($this->linkid);
	}

	function i_id() {
		return mysql_insert_id();
	}

	function err_report($sql, $err) {
		echo "{$_AL['database.r2.t16']}<br>";
		echo "{$_AL['database.r2.t17']}" . $sql . "<br>";
		echo "{$_AL['database.r2.t18']}" . $err;
	}
}
?>