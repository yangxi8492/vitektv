<?php
header("Content-Type:text/html; charset=utf-8");
require_once('../inc/init.php');
require_once('../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("main")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.site.man'])));
}

$action=strFilter($_GET['action']);
switch($action){
	case "savesettings":
		$settings = $_POST['settings'];
		$row=$db->row_select_one("attachments","id=".intval($settings['logo'])."");
		$settings['logopath']=$row['filepath'];
		try{
			saveSettings($settings);
			writeSettingsCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;	

	case "savebanner":
		$settings = $_POST['settings'];
		$rows=$db->row_select("attachments","id in (".intval($settings['banner1']).",".intval($settings['banner2']).",".intval($settings['banner3']).",".intval($settings['banner4']).",".intval($settings['banner5']).")",0,"id,filepath");
		$picpathmap=array();
		foreach($rows as $row){
			$picpathmap[$row['id']]=$row['filepath'];
		}
		unset($rows);
		for($n=1;$n<6;$n++){
			$settings['bannerpath'.$n]=$picpathmap[$settings['banner'.$n]];
		}
		unset($picpathmap);
		try{
			saveSettings($settings);
			writeSettingsCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;	

	case "saveprobanner":
	    $settings = $_POST['settings'];
	    $rows=$db->row_select("attachments","id in (".intval($settings['probanner1']).",".intval($settings['probanner2']).",".intval($settings['probanner3']).",".intval($settings['probanner4']).",".intval($settings['probanner5']).")",0,"id,filepath");
	    $picpathmap=array();
	    foreach($rows as $row){
	        $picpathmap[$row['id']]=$row['filepath'];
	    }
	    unset($rows);
	    for($n=1;$n<6;$n++){
	        $settings['probannerpath'.$n]=$picpathmap[$settings['probanner'.$n]];
	    }
	    unset($picpathmap);
	    try{
	        saveSettings($settings);
	        writeSettingsCache();
	        succeedFlag();
	    }catch(Exception $e){
	        echo($e);
	    }
	break;
	    
	case "saveconfig":
	case "savetime":
	case "saveseoinfo":
	case "savegzip":
	case "saveurlrewriteinfo":
	case "saveverifyinfo":
		
		$settings = $_POST['settings'];
		try{
			saveSettings($settings);
			writeSettingsCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
		
	break;	

	case "savemail":
	case "savefunopen":
	case "savecopyright":
	case "saveattachset":
		$settings = $_POST['settings'];
		try{
			saveSettings($settings,0);
			writeGlobalCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
		
	break;	


	case "savecontact":
		$settings = $_POST['settings'];
		try{
			$db->row_delete("contact","langid={$_SYS['alangid']}");
			$settings['langid']=$_SYS['alangid'];
			$db->row_insert("contact",$settings);
			writeContactCache();
			printRes("{$_AL['main.ct.setsucceed']}<script>setTimeout(function(){reloadSelf('admin.php?inc=main&action=contact');},2000);</script>");
		}catch(Exception $e){
			echo($e);
		}
		
	break;	

	case "testmail":
		require_once('../inc/email.php');
		$settings = $_POST['settings'];
		$mailsubject = "{$_AL['main.ct.testsubject']}".getDateStr(time(),false,false);//Mail Subject
		$mailbody = "{$_AL['main.ct.testcontent']}";//Mail Content
		$receiver = $settings['testreceiver'];
		if(sendMail($receiver, $mailsubject, $mailbody)){
			ob_end_clean();
			succeedFlag();
		}
	break;

	case "rebuildcache":
		$cachetype=$_POST['cachetype'];
		if(empty($cachetype)){
			exit($_AL['main.cache.choose']);
		}
		if(substr($cachetype,0,4)=='all,'){
			$cachetype='all';
		}
		//Clear products in cart 7 days ago
		$d=$_SYS['time']-7*24*3600;
		$db->row_delete("orderdetails","orderid=0 and addtime<{$d}");
		//Build Cache
		$types= explode(",",$cachetype);
		if($cachetype=='all'||in_array('langs',$types)){ writeLangsCache();}
		if($cachetype=='all'||in_array('settings',$types)){ writeGlobalCache(); writeSettingsCache();}
		if($cachetype=='all'||in_array('contact',$types)){ writeContactCache();}
		if($cachetype=='all'||in_array('channels',$types)){ writeChannelsCache();}
		if($cachetype=='all'||in_array('procates',$types)){ writeProductsCateCache();}
		if($cachetype=='all'||in_array('links',$types)){ writeLinksCache();}
		if($cachetype=='all'||in_array('votes',$types)){ writeVotesCache();}
		if($cachetype=='all'||in_array('folders',$types)){ writeFoldersCache();}
		if($cachetype=='all'||in_array('users',$types)){ writeUsersCache();}
		if($cachetype=='all'||in_array('templatevars',$types)){ writeTemplatevarsCache();}
		succeedFlag();
	break;


	default:
		echo($_AL['all.noaction']);
	break;
}
?>
