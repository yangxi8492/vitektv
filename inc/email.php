<?php
//发送邮件
function sendMail($receiver, $subject, $body) {
	global $cache_global;
	$charset='utf-8';
	$mailsubject = $subject;
	$mailsubject='=?'.$charset.'?B?'.base64_encode($mailsubject).'?=';
	$mailbody = $body;

	$sendmailtype=intval($cache_global['mailsendtype']);
	$sender=$sendmailtype==1?$cache_global['mailsender']:$cache_global['smtpsender'];
	if(empty($sender)){return false;}
	
	if($sendmailtype==1){
		if(mail($receiver,   $mailsubject,   $mailbody,   "MIME-Version: 1.0\nContent-type:text/html;charset=".$charset."\nFrom:{$sender}\nReply-To:{$sender}\nX-Mailer:PHP/".phpversion())){
			return true;
		}
	}else{
		require_once(INC_P.'/smtp_class.php');

		##########################################
		$smtpserver = $cache_global['smtpserver'];
		$smtpport = $cache_global['smtpport'];
		$smtpusername = $cache_global['smtpusername'];
		$smtppassword = $cache_global['smtppassword'];
		$smtpauth = !empty($cache_global['smtpauth']);
		$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
		##########################################
		$smtp = new smtp($smtpserver,$smtpport,$smtpauth,$smtpusername,$smtppassword,$charset);
		$smtp->debug = true;//是否显示发送的调试信息
		if($smtp->sendmail($receiver, $sender, $mailsubject, $mailbody, $mailtype)){
			return true;
		}			
	}
	return false;
}

?>