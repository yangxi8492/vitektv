<?php
class WebCore
{
/* Public Variables */
var $vars;
var $db;
var $baseurl;
var $langid;
var $haspro;

/* Constractor */
function WebCore()
{	
	global $db,$_SYS;
	$this->db= $db;
	$this->vars = null;	
	$this->langid = $_SYS['langid'];
	$http_host=($_SERVER["HTTPS"]=='on'?'https':'http').'://'.$_SERVER['HTTP_HOST'];
	$_p = preg_replace("/\/(.[^\/]*).php/is", "", $_SERVER['PHP_SELF']);
	$this->baseurl=	$http_host.$_p;
	$this->haspro=false;
}

/* getProCates(子类别)
 * 子类别:0:不显示; 1:显示
 */
function getProCates($haschild=0){
	global $cache_procates;
	$catestr='';
	//$rows1=$this->db->row_select("procates","pid=0 and langid={$this->langid}",0,"*","ordernum,id");
	if(empty($cache_procates))return '';
	$rows1=$cache_procates;
	foreach($rows1 as $row1){
		if($row1['pid']!='0' || $row1['ishidden']=='1')continue;
		$catestr.="<li class='big'><a href='".$this->genUrl("productlist.php?cid={$row1['id']}")."'>{$row1['title']}</a></li>";
		if($haschild){
			foreach($row1['childcid'] as $childid){
				$row2=$cache_procates[$childid];
				if($row2['ishidden']=='1')continue;
				$catestr.="<li class='small'><a href='".$this->genUrl("productlist.php?cid={$row2['id']}")."'>{$row2['title']}</a></li>";
			}
		}
	}
	return $catestr;
}


/* getArticles(栏目ID,文章属性, 排序, 数目,图文数目,图文字符数量,是否返回数组)
 * 文章属性:0:普通;1:头条;2:推荐;3:高亮;4:滚动;5:加粗;6:跳转
 * 排序:0:发布时间;1:人气
 */
function getArticles($channelid,$type,$order,$n,$b,$w=100,$returnarr=0){
	$channelid=intval($channelid);
	$type=intval($type);
	$order=intval($order);
	$n=intval($n);
	$b=intval($b);
	$w=intval($w);
	global $cache_channels;
	$channel=$cache_channels[$channelid];
	$articlestr="";
	$orderstr=($order==1)?"hits desc":"posttime desc";
	$arts=$this->db->row_select("articles","langid={$this->langid}".($channelid>0?" and channelid={$channelid}":"").($type>0?" and type={$type}":"")."",$n,"id,channelid,posttime,title,content,type,picid,picpath",$orderstr);
	if($returnarr==1){return $arts;}
	$bn=0;
	foreach($arts as $art){
		$arturl=$this->genUrl("view.php?id={$art['id']}");
		$art['posttime']=getDateStr($art['posttime'],true);
		$bn++;
		if($bn<$b+1){
			$art['picpath']=$this->getPicPath($art['picpath']);
			$articlestr.="<li class=\"pictitle\"><a href=\"{$arturl}\" target=\"_blank\">{$art['title']}</a><p><a href=\"{$arturl}\" target=\"_blank\"><img src=\"{$art['picpath']}\" /></a><a href=\"{$arturl}\" target=\"_blank\">".cutStr(strip_tags($art['content']),$w)."</a></p></li>";
		}else{
			$articlestr.="<li class=\"normaltitle\"><span class=time>{$art['posttime']}</span><a href=\"{$arturl}\" target=\"_blank\">{$art['title']}</a></li>";
		}
	}
	return $articlestr;
}


/* getProducts(栏目ID,产品属性, 排序, 数目,显示图片,显示名称, 显示价格)
 * 文章属性:0:普通;1:推荐;2:热门;
 * 排序:0:后台设定的排序; 1:人气
 */
function getProducts($cateid, $type, $order, $n, $showpic=0, $showname=1, $showprice=0){
	$cateid=intval($cateid);
	$type=intval($type);
	$order=intval($order);
	$n=intval($n);
	global $cache_procates;
	global $cache_settings;
	global $cache_global;
	$cate=$cache_procates[$cateid];
	$cache_global['funshop']=='0' && $showprice=0;
	$prostr="";
	$orderstr=($order==1)?"hits desc":"ordernum, posttime desc";

	$pros=$this->db->row_select("products","langid={$this->langid}".($cateid>0?" and cid={$cateid}":"").($type>0?" and type={$type}":"")."",$n,"id,cid,name,content,type,picids,picpaths,price1",$orderstr);
	foreach($pros as $pro){
		$prourl=$this->genUrl("product.php?id={$pro['id']}");
		$pro['price1']=number_format($pro['price1'],2);
		$pro['pic']=$this->getPics($pro['picids'],$pro['picpaths'],0,true,true);
		$prostr.="<li>".($showpic==1?"<div class='proimg'><a href=\"{$prourl}\" title=\"{$pro['name']}\" target=\"_blank\"><img src=\"{$pro['pic']['picpath']}\" title=\"{$pro['name']}\"></a></div>":"").($showname==1?"<span class='text'><a href=\"{$prourl}\" title=\"{$pro['name']}\" target=\"_blank\">{$pro['name']}</a>".($showprice==1?"<br /><span class='price'>{$cache_settings['cur']}{$pro['price1']}</span>":"")."</span>":"")."</li>";
	}
	return $prostr;
}

/* getChannel(位置,菜单)
 * 位置:0:全部; 1:顶部导航; 2:底部导航
 * 菜单:0:不显示; 1:显示;
 */
function getChannel($position, $menu=0){
	global $cache_channels,$cache_procates;
	$cstr='';
	foreach($cache_channels as $channel){
		if($position==1 && (intval($channel['ishidden'])>0 || intval($channel['pid'])>0 || !stristr($channel['positions'],'|1|')))continue;
		if($position==2 && (intval($channel['ishidden'])>0 || !stristr($channel['positions'],'|2|')))continue;
		if($menu){
			//生成栏目菜单
			$submenu="";
			foreach($channel['childcid'] as $childcid){
				$tmpchannel=$cache_channels[$childcid];
				if($tmpchannel['ishidden']=='1'){
					continue;
				}
				$submenu.="<li><h3><a href=\"".$this->genNavLink($tmpchannel)."\">{$tmpchannel['title']}</a></h3></li>";
			}
			//生成产品菜单
			if($channel['systemtype']=='1'){
				//产品栏目启用：
				$this->haspro=true;

				$submenu="";
				foreach($cache_procates as $tmpprocate){
					if($tmpprocate['ishidden']=='1' || $tmpprocate['pid']!='0'){
						continue;
					}
					$submenu.="<li><h3><a href='".$this->genUrl("productlist.php?cid={$tmpprocate['id']}")."'>{$tmpprocate['title']}</a></h3></li>";
				}
			}

			if(!empty($submenu)){
				$submenu="<div class='sub'><ul>{$submenu}</ul></div>";
			}

		}
		$cstr.="<li class='topmenu'><a class='toplink' href='".$this->genNavLink($channel)."'".($channel['channeltype']=='4'&&$channel['target']=='1'?" target='_blank'":"").">{$channel['title']}</a>{$submenu}</li>";
	}
	return $cstr;
}

function getContacts(){
	global $cache_contacts;
	$arrs=array('phone','qq','msn','aliww','yahoo','skype');
	$links=array(
		'',
		'<a href="http://wpa.qq.com/msgrd?V=1&Uin={value}&Site=在线咨询&Menu=no" target="_blank">',
		'<a href="http://settings.messenger.live.com/Conversation/IMMe.aspx?invitee={value}&mkt=zh-hk" target="_blank">',
		'<a href="http://amos1.taobao.com/msg.ww?v=2&uid={value}&s=1" target="_blank">',
		'<a href="http://webmessenger.yahoo.com/?im={value}" target="_blank">',
		'<a href="skype:{value}?call" target="_blank">'
	);
	$links_tail=array(
		'','</a>','</a>','</a>','</a>','</a>'
	);
	$str="";
	foreach($arrs as $n=>$k){
		$links[$n]=str_replace('{value}',$cache_contacts[0][$k],$links[$n]);
		$str.=empty($cache_contacts[0][$k])?"":"<li class=\"ct{$k}\">{$links[$n]}{$cache_contacts[0][$k]}{$links_tail[$n]}</li>";
	}
	return $str;
}

function getVotes(){
	global $cache_votes,$_SYS, $_SLANG;
	if(empty($cache_votes))return "";
	$voterows=$cache_votes;
	$votestr.="";			
	foreach($voterows as $voterow){
		$votestr.="<div class='voteitem'><form name='voteform{$voterow['id']}' id='voteform{$voterow['id']}' target='_blank' action='vote.php?action=vote&id={$voterow['id']}' method='POST'><input type='hidden' value='{$voterow['id']}' name='voteid' />";
		$itemrows=$voterow['voteitems'];			
		$voteindex=1;
		if(intval($voterow['starttime'])>$_SYS['time'] || intval($voterow['stoptime'])<$_SYS['time'] ){
			continue;
		}
		$votestr.="<b>{$voterow['title']}</b>".(intval($voterow['maxvotes'])>1 && intval($voterow['maxvotes'])<count($itemrows)?"<br />(".(str_replace('{0}',$voterow['maxvotes'],$_SLANG['all.survey.max'])).")":"")."<ul>";
		
		if($voterow['maxvotes']>1){
			$inputtype='checkbox';
		}else{
			$inputtype='radio';
		}

		foreach($itemrows as $item){
			$votestr.="<li><input type='{$inputtype}' class='{$inputtype}_css' value='{$item['id']}' name='voteitemid[]' /> {$item['title']}</li>";
			$voteindex++;
		}
		$votestr.="</ul><a href=\"javascript:void(0);\" onclick=\"javascript:document.getElementById('voteform{$voterow['id']}').submit();\">{$_SLANG['all.survey.submit']}</a> <a href=\"vote.php?id={$voterow['id']}\" target=\"_blank\">{$_SLANG['all.survey.view']}</a><div class='clear'></div></form></div>";

	}
	return $votestr;
}

function checkViewLang($type, $id){
	global $_SLANG;
	$reallangid;
	switch($type){
		case 'articlelist':
		case 'page':
			$row=$this->db->row_select_one("channels","id={$id}");
			empty($row)&&exitRes($_SLANG['webcore.channel.ne']);
			$reallangid=$row['langid'];
			break;
		case 'productlist':
			$row=$this->db->row_select_one("procates","id={$id}");
			empty($row)&&exitRes($_SLANG['webcore.cate.ne']);
			$reallangid=$row['langid'];
			break;
		case 'product':
			$row=$this->db->row_select_one("products","id={$id}");
			empty($row)&&exitRes($_SLANG['webcore.product.ne']);
			$reallangid=$row['langid'];
			break;
		case 'view':
			$row=$this->db->row_select_one("articles","id={$id}");
			empty($row)&&exitRes($_SLANG['webcore.art.ne']);
			$reallangid=$row['langid'];
			break;
	}
	setCookies("langid",$reallangid,3600*24*365);
	$tourl='';
	unset($_GET['langid']);
	foreach($_GET as $getkey=>$getvalue){
		(!empty($getvalue))&&$tourl.="&{$getkey}={$getvalue}";
	}
	$tourl="{$type}.php?langid={$reallangid}{$tourl}";
	//exit($tourl);
	_header_("location:{$tourl}");
}

function getPicPath($picpath, $addpre=true, $thumb=false) {
	if(empty($picpath))return 'images/img_no.gif';
	$picpath=preg_replace('/'.ADMIN_DIR.'/i','/',$picpath);
	if(!$thumb){
		$picp=($addpre?'uploadfile/attachment/':'').$picpath;
	}else{
		$picp=($addpre?'uploadfile/thumb/':'').$picpath;
		if(!file_exists(INC_P.'/../'.$picp)){
			$picp=($addpre?'uploadfile/attachment/':'').$picpath;
		}
	}

	return $picp;
}

function getPics($picidstr,$picpathstr,$index=-1,$addpre=true, $thumb=false) {
	$picids=explode("\t",$picidstr);
	$picpaths=explode("\t",$picpathstr);
	$pics=array();
	if(!is_array($picids))return $pics;
	
	for($n=0;$n<count($picids);$n++){
		if(empty($picids[$n])){continue;}
		if($index>-1 && $n!=$index){continue;}
		$pictp=preg_replace('/'.ADMIN_DIR.'/i','/',$picpaths[$n]);
		if(!$thumb){
			$picp=($addpre?'uploadfile/attachment/':'').$pictp;
		}else{
			$picp=($addpre?'uploadfile/thumb/':'').$pictp;
			if(!file_exists(INC_P.'/../'.$picp)){
				$picp=($addpre?'uploadfile/attachment/':'').$pictp;
			}
		}
		$pics[$n]=array('picid'=>$picids[$n],'picpath'=>$picp);
	}
	
	if($index>-1){$pics=$pics[$index];} 
	return $pics;
}

function genNavLink($channel){
	$url='';
	if($channel['systemtype']==1){
		return $this->genUrl("productlist.php");
	}elseif($channel['systemtype']==2){
		return $this->genUrl("contact.php");
	}elseif($channel['systemtype']==3){
		return $this->genUrl("msg.php");
	}
	switch($channel['channeltype']){
		case '1':
			$url=$this->genUrl("page.php?cid={$channel['id']}");
			break;
		case '2':
			$url=$this->genUrl("articlelist.php?cid={$channel['id']}");
			break;
		case '3':
			$url=$this->genUrl("productlist.php");
			break;
		case '4':
			$url=$channel['link'];
			break;
	}
	return $url;
}



function genUrl($url){
	if(empty($url))return "";
	global $cache_settings;
	$urlrewrite = intval($cache_settings['urlrewrite']);
	$http_host=($_SERVER["HTTPS"]=='on'?'https':'http').'://'.$_SERVER['HTTP_HOST'];
	switch($urlrewrite){
		case 0:
			$_p = preg_replace("/\/(.[^\/]*).php/is", "", $_SERVER['PHP_SELF']);
			return $http_host.$_p."/".$url;
		break;
		case 1:	//伪静态的方式1,程序实现
			$_p = preg_replace("/\/(.[^\/]*).php/is", "", $_SERVER['PHP_SELF']);
			$url = str_replace("index.php", "{$_p}/?index.html", $url);
			$url = str_replace("contact.php", "{$_p}/?contact.html", $url);
			$url = preg_replace("/articlelist.php\?cid=(\d{1,})(&page=(.*))*/is", "{$_p}/?articlelist-\\1-\\3.html", $url);
			$url = preg_replace("/view.php\?id=(\d{1,})(&page=(.*))*/is", "{$_p}/?view-\\1-\\3.html", $url);
			$url = preg_replace("/productlist.php(\?cid=(\d{1,}))*(&page=(.*))*/is", "{$_p}/?productlist-\\2-\\4.html", $url);
			$url = preg_replace("/msg.php(\?cid=(\d{1,}))*(&page=(.*))*/is", "{$_p}/?msg-\\2-\\4.html", $url);
			$url = preg_replace("/product.php\?id=(\d{1,})(&page=(.*))*/is", "{$_p}/?product-\\1-\\3.html", $url);
			$url = preg_replace("/page.php\?cid=(\d{1,})(&page=(.*))*/is", "{$_p}/?page-\\1-\\3.html", $url);
			$url = preg_replace("/(-)*.html/is", ".html", $url);
			return $http_host.$url;
		break;
		case 2:	//伪静态的方式2,urlrewrite rule
			$_p = preg_replace("/\/(.[^\/]*).php/is", "", $_SERVER['PHP_SELF']);
			$url = str_replace("index.php", "{$_p}/index.html", $url);
			$url = str_replace("contact.php", "{$_p}/contact.html", $url);
			$url = preg_replace("/articlelist.php\?cid=(\d{1,})(&page=(.*))*/is", "{$_p}/articlelist-\\1-\\3.html", $url);
			$url = preg_replace("/view.php\?id=(\d{1,})(&page=(.*))*/is", "{$_p}/view-\\1-\\3.html", $url);
			$url = preg_replace("/productlist.php(\?cid=(\d{1,}))*(&page=(.*))*/is", "{$_p}/productlist-\\2-\\4.html", $url);
			$url = preg_replace("/msg.php(\?cid=(\d{1,}))*(&page=(.*))*/is", "{$_p}/msg-\\2-\\4.html", $url);
			$url = preg_replace("/product.php\?id=(\d{1,})(&page=(.*))*/is", "{$_p}/product-\\1-\\3.html", $url);
			$url = preg_replace("/page.php\?cid=(\d{1,})(&page=(.*))*/is", "{$_p}/page-\\1-\\3.html", $url);
			$url = preg_replace("/(-)*.html/is", ".html", $url);
			return $http_host.$url;
		break;
	}
	return $url;
}


}
?>