<?php
header("Content-Type:text/html; charset=utf-8");
include_once('inc/init.php');

$action=strFilter($_GET["action"]);
switch($action){
	case "savemsg":
		try{
			$msg['title']=cutStr(strFilter($_POST['title']),30);
			$msg['name']=cutStr(strFilter($_POST['name']),30);
			$msg['email']=cutStr(strFilter($_POST['email']),30);
			$msg['contact1']=cutStr(strFilter($_POST['contact1']),30);
			$msg['remark']=cutStr(strFilter($_POST['remark']),5000);
			$msg['title'] = trim(!empty($msg['title']) ? str_replace(array("\r", "\n", "\t"), array(' ', ' ', ' '), $msg['title']) : $msg['title']);
			$securitycode=$_POST['securitycode'];
			if($cache_settings['msgsecuritycode']=='1' && strtolower(rSESSION('validationcode'))!=strtolower($securitycode)){
				exit($_SLANG['all.secodeerr']);
			}
			$msg['posttime']=time();
			$msg['langid']=$_SYS['langid'];
			$msg['ip']=getIP();
			$db->row_insert("msgs",$msg);
			succeedFlag();
		}catch(Exception $e){
			echo($e);
		}
	break;

	
	case "checkmemberValid":
		$u = strFilter($_GET["u"]);
		if(!isValidName($u)){
			exit($_SLANG['ajaxpublic.username.illegal']);
		}
		
		$row=$db->row_select_one("members","membername='{$u}'");
		if($row!=null){
			echo($_SLANG['ajaxpublic.username.used']);
		}else{
			succeedFlag();
		}
		
	break;

	case "checkEmailValid":
		$e = strFilter($_GET["e"]);
		if(!isValidEmail($e)){
			echo($_SLANG['ajaxpublic.email.err']);
		}else{
			$row=$db->row_select_one("members","email='{$e}'");
			if($row!=null){
				echo($_SLANG['ajaxpublic.email.used']);
			}else{
				succeedFlag();
			}
		}
	break;

	case "checkSecurityCode":
		$v = strFilter($_GET["v"]);
		if(strtolower(rSESSION('validationcode'))!=strtolower($v)){
			echo($_SLANG['ajaxpublic.codeerr']);
		}else{
			succeedFlag();
		}
	break;


	case "getmemberpass":
		$eu = strFilter($_POST["membername"]);
		$ev = strFilter($_POST["email"]);
		$sv = strFilter($_POST["securitycode"]);
		if(strtolower(rSESSION('validationcode'))!=strtolower($sv)){
			exit($_SLANG['ajaxpublic.codeerr']);
		}
		$row=$db->row_select_one("members","membername='{$eu}' and email='{$ev}'");
		if(empty($row)){
			exit($_SLANG['ajaxpublic.user.notexist']);
		}else{
			$d=$_SYS['time']-24*3600;
			$db->row_delete("memberfield","type=1 and createtime<{$d}");
			
			$fieldrows=$db->row_query("select count(0) as C from `{$db->pre}memberfield` where memberid={$row['id']} and type=1");
			if(!empty($fieldrows) && $fieldrows[0]['C']>=3){
				exit($_SLANG['ajaxpublic.reset.timelimit']);
			}
			$activecode=md5($row['membername'].$row['memberpass'].$_SYS['time'].mt_rand(1000,9999));
			$memberfield['memberid'] = $row['id'];
			$memberfield['code'] = $activecode;
			$memberfield['createtime'] = $_SYS['time'];
			$memberfield['type'] = 1;	//重置密码
			$db->row_insert("memberfield",$memberfield);
			$url=getUrlPath()."/public.php?action=resetpass&uid={$row['id']}&code={$activecode}";
			$subject=_LANG($_SLANG['ajaxpublic.resetemail.title'],array($cache_settings['webname']));
			$body=_LANG($_SLANG['ajaxpublic.resetemail.body'],array($row['membername'], 
				"<a href=\"{$cache_settings['url']}\" target=\"_blank\">{$cache_settings['webname']}</a>",
				"<br /><a href=\"{$url}\" target=\"_blank\">{$url}</a>"));

			require_once('inc/email.php');
			if(sendMail($row['email'],  $subject, $body)){
				succeedFlag();
			}else{
				echo($_SLANG['ajaxpublic.mailfailed']);
			}			
		}
		
	break;

	case "addToCart":
		$proid = intval($_GET["proid"]);
		$cartid=intval(getCookies("cartid"));
		$row=$db->row_select_one("products","id={$proid}");
		if(empty($row)){
			exit($_SLANG['ajaxpublic.pro.notexist']);
		}
		$odt['proid']=$row['id'];
		$odt['proname']=$row['name'];
		$protmppic=$webcore->getPics($row['picids'],$row['picpaths'],0,false);
		$odt['picid']=$protmppic['picid'];
		$odt['picpath']=$protmppic['picpath'];
		$odt['addtime']=time();
		$odt['price']=$row['price1'];
		$odt['langid']=$_SYS['langid'];
		if($cartid==0){
			$odt['pronum']=1;
			$db->row_insert("orderdetails",$odt);
			$cartid=$db->insert_id();
			$odt['cartid']=$cartid;
			$db->row_update("orderdetails",$odt,"id={$cartid}");
			setCookies("cartid",$cartid,3600*24*7);
		}else{
			$odtrow=$db->row_select_one("orderdetails","proid={$proid} and cartid={$cartid} and langid={$_SYS['langid']}");
			if(!empty($odtrow)){
				$odt['pronum']=$odtrow['pronum']+1;
				$db->row_update("orderdetails",$odt,"id={$odtrow['id']}");
			}else{
				$odt['pronum']=1;
				$odt['cartid']=$cartid;
				$db->row_insert("orderdetails",$odt);
			}
		}
		succeedFlag();
	break;


	case "delFromCart":
		$proid = intval($_GET["proid"]);
		$cartid=intval(getCookies("cartid"));
		if(!empty($cartid)){
			$db->row_delete("orderdetails","proid={$proid} and cartid={$cartid} and langid={$_SYS['langid']}");
		}
		succeedFlag($proid);
	break;

	case "changePronum":
		$proid = intval($_GET["proid"]);
		$pronum = intval($_GET["pronum"]);
		$cartid=intval(getCookies("cartid"));
		if(!empty($cartid)){
			$odt['pronum']=$pronum;
			$db->row_update("orderdetails",$odt,"proid={$proid} and cartid={$cartid} and langid={$_SYS['langid']}");
		}
		succeedFlag($proid);
	break;

	case "saveorder":
		try{
			$cartid=intval(getCookies("cartid"));
			if(!empty($cartid)){
				$odts=$db->row_select("orderdetails","cartid={$cartid} and langid={$_SYS['langid']}");
				if(empty($odts)){
					exit($_SLANG['ajaxpublic.nopro']);
				}
				$ordertotal=0;
				foreach($odts as $okey=>$odt){
					$ordertotal+=($odt['price']*$odt['pronum']);
				}

				$order['memberid']=$lg['memberid'];
				$order['ordernum']=GenOrderNum($lg,$cartid);
				$order['name']=cutStr(strFilter($_POST['name']),30);
				$order['phonenum']=cutStr(strFilter($_POST['phonenum']),30);
				$order['email']=cutStr(strFilter($_POST['email']),30);
				$order['address']=cutStr(strFilter($_POST['address']),100);
				$order['zipcode']=cutStr(strFilter($_POST['zipcode']),30);
				$order['remark']=cutStr(strFilter($_POST['remark']),5000);
				$order['createtime']=time();
				$order['total']=$ordertotal;
				$order['langid']=$_SYS['langid'];
				$db->row_insert("orders",$order);
				
				$updateodt['orderid']=$db->insert_id();
				$updateodt['cartid']=0;
				$db->row_update("orderdetails",$updateodt,"cartid={$cartid}");
			}
			succeedFlag($order['ordernum']);
		}catch(Exception $e){
			echo($e);
		}
	break;


	case "getActiveCode":
		$eu = strFilter($_POST["membername"]);
		$ev = strFilter($_POST["email"]);
		$sv = strFilter($_POST["securitycode"]);
		if(strtolower(rSESSION('validationcode'))!=strtolower($sv)){
			exit($_SLANG['ajaxpublic.codeerr']);
		}
		$row=$db->row_select_one("members","membername='{$eu}' and email='{$ev}'");
		if(empty($row)){
			exit($_SLANG['ajaxpublic.user.notexist']);
		}else{
			if($cache_global['issignupverify']!='2'){
				exit($_SLANG['ajaxpublic.email.notallow']);
			}
			if($row['groupid']!=GROUP_NOVERIFY){
				exit($_SLANG['ajaxpublic.user.actived']);
			}
			$d=$_SYS['time']-24*3600;
			$db->row_delete("memberfield","type=0 and createtime<{$d}");
			$fieldrows=$db->row_query("select count(0) as C from `{$db->pre}memberfield` where memberid={$row['id']} and type=0");
			if(!empty($fieldrows) && $fieldrows[0]['C']>=3){
				exit($_SLANG['ajaxpublic.active.timelimit']);
			}

			$activecode=md5($row['membername'].$row['memberpass'].$_SYS['time'].mt_rand(1000,9999));
			$memberfield['memberid'] = $row['id'];
			$memberfield['code'] = $activecode;
			$memberfield['createtime'] = $_SYS['time'];
			$memberfield['type'] = 0;	//用户激活
			$db->row_insert("memberfield",$memberfield);
			$url=getUrlPath()."/public.php?action=active&uid={$row['id']}&code={$activecode}";
			$subject=_LANG($_SLANG['signup.activeemail.title'],array($cache_settings['webname']));


			$body=_LANG($_SLANG['signup.activeemail.body'],array($row['membername'], 
				"<a href=\"{$cache_settings['url']}\" target=\"_blank\">{$cache_settings['webname']}</a>",
				"<br /><a href=\"{$url}\" target=\"_blank\">{$url}</a>"));
			
			require_once('inc/email.php');
			if(sendMail($row['email'], $subject, $body)){
				succeedFlag();
			}else{
				echo($_SLANG['ajaxpublic.mailfailed']);
			}
		}
		
	break;

	default:
		echo"No Such Action";
	break;
}


	//OrderForm 生成订单号OrderNum
	function GenOrderNum($lg,$cartid){
		$fid = microtime();
		$array = explode(" ",$fid);
		$fid = strval($array[1].intval($array[0]*1000));
		return strval($fid.rand(10000,99999));
	}

?>
