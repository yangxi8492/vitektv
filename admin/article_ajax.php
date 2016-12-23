<?php
header("Content-Type:text/html; charset=utf-8");
require_once('../inc/init.php');
require_once('../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("article")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.article.man'])));
}
$action=strFilter($_GET['action']);
switch($action){
	
	case "savearticle":
		try{
			$doaction=strFilter($_POST['doaction']);
			$art['title']=strFilter($_POST['title']);
			$art['alias']=strFilter($_POST['alias']);
			$art['posttime']=strFilter($_POST['posttime']);
			$art['posttime']= empty($art['posttime']) ? time() : (strtotime($art['posttime'])-$cache_settings['timeoffset']*3600);
			$art['posttime'] = $art['posttime']<0 ? time(): $art['posttime'];
			$art['channelid']=intval($_POST['channelid']);
			$art['langid']=$_SYS['alangid'];
			$art['type']=intval($_POST['type']);
			$art['seotitle']=strFilter($_POST['seotitle']);
			$art['metakeywords']=strFilter($_POST['metakeywords']);
			$art['metadesc']=strFilter($_POST['metadesc']);
			$art['content']=strFilter($_POST['content']);
			$art["picid"]=intval($_POST['picid']);
			$row=$db->row_select_one("attachments","id=".$art['picid']);
			$art['picpath']=$row['filepath'];
			if($doaction=="edit"){
				$id=intval($_POST['id']);
				$db->row_update("articles",$art,"id={$id}");
			}else{
				$db->row_insert("articles",$art);
			}		

			if($doaction=="edit"){
				printRes($_AL['article.edit.succeed']."<script>setTimeout(function(){self.location.href='admin.php?inc=article&action=list&channelid={$art['channelid']}'},2000);</script>");
			}else {
				printRes($_AL['article.add.succeed']."<script>setTimeout(function(){self.location.href='admin.php?inc=article&action=list&channelid={$art['channelid']}'},2000);</script>");
			}
		}catch(Exception $e){
			echo($e);
		}
	break;

	case "doarticles":
		try{
			$postaction=$_POST['postaction'];
			$aids=$_POST['aids'];
			if(empty($aids)){
				exit($_AL['article.noselected']);
			}
			if(isIntArray($aids)) {
				$aidstr=implode(",",$aids);
				switch($postaction){
					case "":
					case "delArticle":
						$db->row_delete("articles","id in ({$aidstr})");
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
