<?php
header("Content-Type:text/html; charset=utf-8");
require_once('../inc/init.php');
require_once('../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("template")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.template.set'])));
}

$action=strFilter($_GET['action']);
switch($action){
	case "settemplate":
		try{
			$template=$_POST['tdefault'];
			$tlang=$_POST['tlang'];
			
			$settings['template']=$template;
			$settings['templatelang']=$tlang[$template];
			saveSettings($settings);
			writeSettingsCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;

	case "savevars":
		try{
			$doaction=strFilter($_POST['doaction']);
			$vars['tkey']=trim(strFilter($_POST['tkey']));
			$vars['tvalue']=strFilter($_POST['tvalue']);
			$vars['tdesc']=strFilter($_POST['tdesc']);
			$vars['langid']=$_SYS['alangid'];
			if(!empty($vars['tkey'])){
				if($doaction=="editvar"){
					$id=intval($_POST['id']);
					$db->row_update("templatevars",$vars,"id={$id}");
				}else{
					$db->row_insert("templatevars",$vars);
				}
				writeTemplatevarsCache();
			}
			if($doaction=="editvar"){
				printRes("{$_AL['template.editsucceed']}<script>setTimeout(function(){self.location.href='admin.php?inc=template&action=varlist'},1000);</script>");
			}else {
				printRes("{$_AL['template.addsucceed']}<script>setTimeout(function(){self.location.href='admin.php?inc=template&action=varlist'},1000);</script>");
			}
		}catch(Exception $e){
			echo($e);
		}
	break;

	case "dotemplatevars":
		try{
			$vids=$_POST['vids'];
			if(empty($vids)){
				exit("{$_AL['template.noselect']}");
			}
			if(isIntArray($vids)) {
				$vidstr=implode(",",$vids);
				$db->row_delete("templatevars","id in ({$vidstr})");
				succeedFlag();
			}
			writeTemplatevarsCache();
		}catch(Exception $e){
			echo($e);
		}
	break;

	default:
		echo($_AL['all.noaction']);
	break;
}
?>