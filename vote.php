<?php
require_once('./inc/init.php');

$id=intval($_GET['id']);
$action=strFilter($_GET["action"]);
$action=empty($action)? "view" : $action;
switch($action){
	case "vote":
	case "view":
		$votetips='';
		if($action=="vote"){
			$voteitemid=$_POST['voteitemid'];
			$voteid=intval($_POST['voteid']);
			$voterow=$db->row_select_one("votes","id={$voteid}");
			if($voterow['starttime']>$_SYS['time'] || $voterow['stoptime']<$_SYS['time'] ){
				$votetips=$_SLANG['vote.expired'];
			}
			if(!empty($voteitemid)){
				if(isIntArray($voteitemid) && count($voteitemid)<=$voterow['maxvotes']){
					//合法
				}else{
					$votetips=_LANG($_SLANG['vote.max2'],array($voterow['maxvotes']));
				}
				
				$rows=$db->row_select("voteitems","voteid={$voteid}");
				if($voterow['level']>0){
					foreach($rows as $row){
						if(stristr(",{$row['voteips']},", ",".getIP().",")){
							$votetips=$_SLANG['vote.voted'];
						}
					}
				}
				if($voterow['level']==2 && intval(getCookies("vote{$voteid}"))==1){
					$votetips=$_SLANG['vote.voted'];
				}

				//成功投票
				if(empty($votetips)){
					foreach($rows as $row){
						if(in_array($row['id'],$voteitemid)){
							$tip=getIP();
							$db->query_unbuffered("update `{$db->pre}voteitems` set votednum=votednum+1, voteips=".$db->concat("voteips","'{$tip}'")." where id={$row['id']}");
						}
					}
					
					//写cookies
					if($voterow['level']==2){
						setCookies("vote{$voteid}",'1');
					}
					$totalrow=$db->row_query_one("SELECT SUM(votednum) as total FROM `{$db->pre}voteitems` WHERE voteid={$voteid} LIMIT 1");
					$db->query_unbuffered("update `{$db->pre}votes` set votednum={$totalrow['total']} where id={$voteid}");
					$votetips=$_SLANG['vote.succeed'];
				}
				$votetips=empty($votetips)?"":"<div class='votesucceed'>{$votetips}</div>";
			}
		}
		
		$votecolors=array('','#DCEF17', '#FFBF2A', '#EA793F', '#ECA45C', '#4CBA4A', '#5D74B1', '#98C6D5', '#DD30AE', '#BDF752', '#EE335F');
		$votestr.="<div class='view_vote'>";
			$voterow=$db->row_select_one("votes", "id={$id}");
			$allvotednum=$voterow['votednum'];
			$itemrows=$db->row_select("voteitems", "voteid={$id}", 0, "*","id");
			$votestarttime=getDateStr($voterow['starttime'], 'dateonly', false);
			$votestoptime=getDateStr($voterow['stoptime'], 'dateonly', false);
			
			$voteindex=1;
			$votestr.="<div class='votetitle'>{$voterow['title']}</div>";
			if($voterow['maxvotes']>1){
				$votestr.="<div class='votelimit'>"._LANG($_SLANG['vote.title'],array($voterow['votednum'], "<span class='time'>{$votestarttime}</span>", "<span class='time'>{$votestoptime}</span>"))."</div>";
			}else{
				$votestr.="<div class='votelimit'>"._LANG($_SLANG['vote.title'],array($voterow['votednum'], "<span class='time'>{$votestarttime}</span>", "<span class='time'>{$votestoptime}</span>"))."</div>";
			}
			$votestr.="<table class='view_votetable'>";
			foreach($itemrows as $item){
				@$votev=number_format($item['votednum']/$allvotednum*100,2);
				$width=300*$votev/100;
				$votestr.="<tr><td class='votetd1'>{$item['title']}</td>";
				$votestr.="<td class='votetd2'><div class='vote100'><div style='width:{$width}px; background:{$votecolors[$voteindex]};'></div></div></td><td class='votetd3'> {$item['votednum']} ({$votev}%)</td></tr>";
				$voteindex++;
			}
		$votestr.="</table></div>";

		$headtitle=empty($voterow['title'])?"":strip_tags(str_replace(array("\r", "\n"), array('', ''), $voterow['title']));
		$headkeywords=$headtitle;
		$headdesc=$headtitle;
	break;

	case "list":
		echo $webcore->getVotes();
	break;
}
require_once('./header.php');
require_once getTemplatePath('vote.htm');
footer();

?>