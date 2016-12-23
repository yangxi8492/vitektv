<?php
class Pager{
	var $recordNum=0;
	var $pageNum=0;
	var $ePageRecordNum =10;
	var $cPage=1;
	var $MIN = 1;
	var $MAX;
	var $FIRST;
	var $P = 4;
	var $E = 8;
	var $styleClass="Pagination";
	var $pageLink="";
	var $objName;
	var $LANG;
	
	function Pager(){
		global $_SLANG;
		if(!empty($_SLANG['pager.total'])){
			$this->LANG['total']=$_SLANG['pager.total'];
		}else{
			$this->LANG['total']="{0}条记录";
		}
	}

	function init($ePageNum=10,$cPage=1,$linkTmp="?page={page}",$objName="PAGER",$recordNum=100){
		$this->ePageRecordNum=$ePageNum;
		$this->cPage=intval($cPage);
		if($this->cPage<1){$this->cPage=1;}
		$this->pageLink=$linkTmp;
		$this->styleId=$styleid;
		$this->objName=$objName;
		if($styleid!=undefined && $styleid=="Mini"){
			$this->styleClass="PaginationMini";
		}
		$this->recordNum=$recordNum;
	}


	function getLinkN($n){
		return str_replace("{page}",$n,$this->pageLink);
	}
	
	function turnLength($i){
		$s = $i;
		if($this->styleClass=="Pagination"){
			if($i<10){
				$s="&nbsp;&nbsp;".$i."&nbsp;&nbsp;";
			}else if($i<100){
				$s="&nbsp;".$i."&nbsp;";			
			}			
		}else{
			if($i<10){
				$s="&nbsp;".$i."&nbsp;";
			}else if($i<100){
				$s="".$i."";			
			}				
		}

		return $s;
	}

	function queryRows($db,$tbname, $where="",  $fields="*", $orderby="id DESC", $totalnum=0){
		$this->recordNum= $totalnum==0 ? $db->row_count($tbname, $where) : $totalnum;
		$limit=($this->cPage-1)*$this->ePageRecordNum;
		$limit.=",".$this->ePageRecordNum;
		return $db->row_select($tbname, $where, $limit, $fields, $orderby);
	}

	function queryRowsBySQL($db, $sql="", $totalnum=0){
		$this->recordNum= $totalnum;
		$limit=($this->cPage-1)*$this->ePageRecordNum;
		$limit.=",".$this->ePageRecordNum;
		$sql.= " limit ".$limit;
		return $db->row_query($sql);
	}


	function divisionInt($numA,$numB){
								$modNum=$numA%$numB;
								$result=($numA-$modNum)/$numB;
								if($modNum>0)$result++;						
								return $result;
						}

	function MinOf($a, $b){
		return $a<$b ? $a : $b;
	}
	
	function isFirst(){
		return ($this->cPage <= 1);
	}
	
	function isLast(){
		return ($this->cPage== $this->pageNum);
	}
	function getPageStr(){
		if($this->recordNum==0)return "";
		$pageStr = "";
		if ($this->recordNum == 0) {
			return "";
		}
		$pageStr = "<table cellpadding=0 cellspacing=0 border=0><tr><td class=Pagination_td1><div class=\"".$this->styleClass."\">";
		$this->pageNum =$this->divisionInt($this->recordNum,$this->ePageRecordNum);
		$this->MAX = $this->pageNum;
		if ($this->cPage <= 0) {
			$this->cPage = $this->MIN;
		}
		if ($this->cPage >$this->MAX) {
			$this->cPage = $this->MAX;
		}
		
		if ($this->cPage <= $this->P) {
			$this->FIRST = $this->MIN;
		} else if ($this->cPage >= $this->MAX - $this->E + $this->P) {
			$this->FIRST = $this->MAX - $this->E + 1;
		} else {
			$this->FIRST = $this->cPage - $this->P + 1;
		}
		if($this->FIRST<$this->MIN){
			$this->FIRST = $this->MIN;
		}
		if($this->MIN<$this->FIRST){
			$pageStr .= "<a href=\"".$this->getLinkN($this->MIN)."\">".$this->turnLength($this->MIN)."..</a>";
		}
		if($this->MIN < $this->cPage){
			$pageStr .= "<a href=\"".$this->getLinkN($this->cPage*1-1)."\">&lt;&lt;</a>";			
		}
		

		for($i=$this->FIRST; $i<=$this->MinOf($this->FIRST+$this->E-1,$this->MAX);$i++){
			if($i==$this->cPage){
				$pageStr .= "<strong>".$this->turnLength($i)."</strong>";				
			}else{
				$pageStr .= "<a href=\"".$this->getLinkN($i)."\">".$this->turnLength($i)."</a>";
			}
		}
		
		if($this->MAX > $this->cPage){
			$pageStr .= "<a href=\"".$this->getLinkN($this->cPage*1+1)."\">&gt;&gt;</a>";			
		}
		
		if($this->MAX>$this->FIRST+$this->E-1){
			$pageStr .= "<a href=\"".$this->getLinkN($this->MAX)."\">...".$this->turnLength($this->MAX)."</a>";
		}
		

		$pageStr .= "</div></td><td class=\"".$this->styleClass."_td2 ".$this->styleClass."_inputtd\"><input class='".$this->styleClass."_input' type=text value=".$this->cPage." id=".$this->objName."pageInput size=5 onkeypress='".$this->objName."_pageKeyPress(event,this)' /> ".str_replace("{0}",$this->recordNum,$this->LANG['total'])."</td>".
				"</tr></table>";
		
		$pageStr .= "<script type=\"text/javascript\">function "
				. $this->objName
				. "_pageKeyPress(eventobject,obj){var eve=eventobject||window.event;var pagenum=obj.value;pagenum=pagenum.replace(/\\D/g,'');if(pagenum==null||pagenum==\"\")return;if(pagenum>"
				. $this->MAX
				. ")pagenum="
				. $this->MAX
				. ";if(eve.keyCode==13) {"
				. "var toloc=\""
				. $this->pageLink
				. "\";toloc=toloc.replace(\"{page}\",pagenum); window.location.href=toloc;";
		$pageStr.="}}</script>";
		return $pageStr;
	}
	
	function getSmallPager($per=10, $total=100, $splitn=5, $link){
		$str='...';
		$pageNum =$this->divisionInt($total, $per);
		for($i=2; $i<=$this->MinOf($pageNum,$splitn); $i++){
			$tmplink=$link;
			$tmplink=str_replace("{page}",$i, $tmplink);
			$str.="<a href=\"{$tmplink}\">{$i}</a>";
		}
		if($pageNum>$splitn){
			$tmplink=$link;
			$tmplink=str_replace("{page}",$pageNum, $tmplink);
			$str.="..<a href=\"{$tmplink}\">{$pageNum}</a>";
		}
		return $str;
	}						

} 
?>
