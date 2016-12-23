<?php
header("Content-Type:text/html; charset=utf-8");
require_once('../inc/init.php');
require_once('../inc/cache.php');
require_once('./inc/adminfun.php');
require_once("./language/language.php");

if(!isAdmin()){
	exit($_AL['all.notlogin']);
}
if(!hasPopedom("vote")){
	exit(_LANG($_AL['admin.nopopedom'], array($_AL['index.vote.list'])));
}

$action=strFilter($_GET['action']);
switch($action){
	
	case "savevotes":
		try{
				$id=intval($_POST['id']);
				$level=intval($_POST['level']);
				$doaction=$_POST['doaction'];
				$voteitem=$_POST['voteitem'];
				$votednum=$_POST['votednum'];
				$maxvotes=intval($_POST['maxvotes']);
				$starttime=$_POST['starttime'];
				$stoptime=$_POST['stoptime'];
				$title=$_POST['title'];
	
				$voteitem = !empty($voteitem) ? str_replace("\t", ' ', $voteitem) : $voteitem;
				if(!is_array($voteitem)){
					exit($_AL['vote.item.empty']);
				}
				$totalvote=0;
				foreach($voteitem as $key=>$votei){
					$voteistr=trim($votei);
					if(empty($voteistr)){
						unset($voteitem[$key]);
						unset($votednum[$key]);
					}else{
						$voteitem[$key] = $voteistr;
						$totalvote+=$votednum[$key];
					}
				}
				if(count($voteitem)==0){
					exit($_AL['vote.item.empty']);
				}
				if(count($voteitem)>10){
					exit($_AL['vote.item.less10']);
				}
				$maxvotes=($maxvotes<1?1:$maxvotes);
				$maxvotes=($maxvotes>count($voteitem)?count($voteitem):$maxvotes);
				
				$starttime= empty($starttime) ? 0 : (strtotime($starttime)-$cache_settings['timeoffset']*3600);
				$starttime = $starttime<0 ? 0: $starttime;
				$stoptime= empty($stoptime) ? 0 :(strtotime($stoptime)+24*3600-$cache_settings['timeoffset']*3600-1);
				$stoptime = $stoptime<0 ? 0: $stoptime;
				if($starttime && $stoptime && $stoptime>=$starttime){
				
				}else{
					exit($_AL['vote.votetime.err']);
				}
				
				$vote['level'] = $level;
				$vote['maxvotes'] = $maxvotes;
				$vote['starttime'] = $starttime;
				$vote['stoptime'] = $stoptime;
				$vote['title']= $title;
				$vote['votednum']= $totalvote;
				$vote['langid']= $_SYS['alangid'];
				if($doaction=='add'){
					$db->row_insert("votes",$vote);
					$voteid=$db->insert_id();
				}elseif($doaction=='edit'){
					$db->row_update("votes",$vote,"id={$id}");
					$voteid=$id;
					$db->row_delete("voteitems","voteid={$voteid}");
				}
				foreach($voteitem as $key=>$item){
					$v['voteid']=$voteid;
					$v['votednum']=intval($votednum[$key]);
					$v['title']=$item;	
					$v['voteips']='';
					$db->row_insert("voteitems",$v);
				}
				writeVotesCache();
				succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;

	case "delvote":
			$id=intval($_GET['id']);
			$db->row_delete("votes","id={$id}");
			$db->row_delete("voteitems","voteid={$id}");
			writeVotesCache();
			succeedFlag();
		break;

	case "dovotes":
		try{
			$postaction=$_POST['postaction'];
			$ordernums=$_POST['ordernum'];
			if(is_array($ordernums)) {
				foreach($ordernums as $id => $value) {
					$vote['ordernum'] = intval($value);
					$db->row_update("votes",$vote,"id={$id}");
				}
			}
			writeVotesCache();
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;
	default:
		echo($_AL['all.noaction']);
	break;
}
?>
