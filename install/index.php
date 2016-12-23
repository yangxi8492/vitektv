<?php
error_reporting(E_ALL ^ E_NOTICE);
$step = intval($_GET['step']);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>6KZZ快站 安装向导</title>
<style>
*{font-size:12px; font-family: "Verdana", "Arial", "Helvetica",  "sans-serif"; }
.login_body{background:#008EC3;margin:0px;padding:0px;}
.login_div{background:#F5F5F5; width:780px; margin:15px auto 10px auto; padding:15px 15px 30px 15px;}

.login_footer{height:48px; font-size:11px; color:#dedede; text-align:center; line-height:180%; margin-top:8px; padding-top:8px; }
.login_footer a {color:#A3C819; text-decoration: underline; }
.login_st0{font-size:16px; font-weight:bold; margin:10px 0px;}
.login_st1{height:35px; line-height:35px;font-weight:bold;color:#666; margin-top:20px;}
.login_st2{font-size:14px; line-height:180%; color:green;}
.login_st2 a:link,
.login_st2 a:hover,
.login_st2 a:visited,
.login_st2 a:active{
	color:#333;
}
.login_agreement{margin:20px;height:315px; padding:10px; overflow:scroll; border:1px solid #c0c0c0; background:#FFF;}
.login_agreement pre{
word-break: keep-all;white-space:pre-wrap;
white-space:-moz-pre-wrap;
white-space:-pre-wrap;
white-space:-o-pre-wrap;
word-wrap:break-word;
display:block; line-height:150%;
}
.login_agreement pre b{font-size:14px;}

#step2_tips,#step3_tips{margin-top:10px;}
.table_1{border-collapse:collapse;width:98%;}
.td_0{color:#333333;height:32px;width:200px;text-align:right;color:#666;}
.td_1 input{padding:3px 2px;}
.td_1{}

.status_succeed{color:green;}
.status_failed{color:red;}
.status_ing{color:blue;}

.button_css{padding:3px 2px;}
.hadinstall{font-size:14px; color:#333333;background:#fff;width:600px; margin:200px auto; padding:20px;}

</style>
<script type="text/javascript" src="../getfiles.php?t=js&v=<?php echo time();?>&f=util|ajax"></script></head>
<body class="login_body">
<?php
if(file_exists('install.lock')){
	echo('<div class=hadinstall>6KZZ快站已经安装完毕。如果要重新安装，请删除本目录的install.lock文件。</div>');
}else{
?>
<div class="login_div">
<img src="title.gif" border="0" />
<?php	
switch($step){
	case 0:
	case 1:
?>
	<div class="div_clear" style="height:10px;"></div>
	<div class="login_st0">第1步：6KZZ快站程序安装须知</div>
	<div class="login_agreement"><pre>
<b>1、运行环境需求：PHP+MYSQL</b>

<b>2、安装步骤</b>

<b>● Linux 或 Freebsd 服务器下安装方法</b>

第一步：
使用ftp工具，用二进制模式将该软件包里的所有文件上传到您的空间。

第二步：
先确认以下目录或文件属性为 (777) 可写模式。
inc/config.php
install
cache
uploadfile
admin/backup

第三步：
运行 http://yourwebsite/install/ 安装程序，填入安装相关信息与资料，完成安装！

<b>● Windows 服务器下安装方法</b>

第一步：
使用ftp工具，将该软件包里的所有文件上传到您的空间。

第二步：
运行 http://yourwebsite/install/ 安装程序，填入安装相关信息与资料，完成安装！

</pre>
	</div>
	<center><input class="button_css" type="button" value="  开始安装6KZZ快站  " onclick="setChmod()" id="step1_btn" /><div id="step1_tips"></div></center>
<?php
	break;
	case 2:
?>
	<div class="div_clear" style="height:10px;"></div>
	<div class="login_st0">第2步：填写数据库相关信息</div>
	<form id="step2form" onsubmit="return false;">
	<table class="table_1">
		<tr><td class="td_0">数据库服务器:</td><td class="td_1"><input tabindex="101" type="text" size="30" value="localhost" name="settings[dbserver]"id="settings[dbserver]" class="text_css" /></td></tr>
		<tr><td class="td_0">数据库用户名:</td><td class="td_1"><input tabindex="102" type="text" size="30" value="root" name="settings[dbuser]" id="settings[dbuser]" class="text_css" /></td></tr>
		<tr><td class="td_0">数据库密码:</td><td class="td_1"><input tabindex="103" type="password" size="30" value="root" name="settings[dbpass]" id="settings[dbpass]" class="text_css" onblur="testConnect()" /> <input class="button_css" type="button" value="  测试连接  " onclick="testConnect()" id="step2_testbtn" /><span id="step2_testtips"></span></td></tr>
		<tr><td class="td_0">数据库名:</td><td class="td_1"><input tabindex="104" type="text" size="30" value="" name="settings[dbname]" id="settings[dbname]" class="text_css" /></td></tr>
		<tr><td class="td_0">安装数据表前缀:</td><td class="td_1"><input tabindex="105" type="text" size="30" value="zzdb_" name="settings[dbpre]" id="settings[dbpre]" class="text_css" /> <span class="status_succeed"><b>注意: 不要与6kbbs产品的前缀一样。</b></span></td></tr>
		<tr style="display:none;"><td class="td_0">安装测试数据:</td><td class="td_1"><input type="radio" value="1" name="settings[testdata]" class="radio_css" /> 是 &nbsp; &nbsp;<input type="radio" value="0" name="settings[testdata]" class="radio_css" checked="true" /> 否</td></tr>
		<tr><td class=""></td><td style="padding-top:10px;"><input tabindex="106" class="button_css" type="button" value="  生成数据库并继续安装  " onclick="genData()" id="step2_btn" /><div id="step2_tips"></div></td></tr>
	</table>
	</form>
<?php
	break;
	case 3:
?>
	<div class="div_clear" style="height:10px;"></div>
	<div class="login_st0">第3步：创建网站管理员帐号</div>
	<form id="step3form" onsubmit="return false;">
	<table class="table_1">
		<tr><td class="td_0">管理员帐号:</td><td class="td_1"><input type="text" size="30" value="admin" name="settings[username]" id="settings[username]" class="text_css" /></td></tr>
		<tr><td class="td_0">管理员密码:</td><td class="td_1"><input type="password" size="30" value="" name="settings[userpass]" id="settings[userpass]" class="text_css" /></td></tr>
		<tr><td class="td_0">重复密码:</td><td class="td_1"><input type="password" size="30" value="" name="repass" id="repass" class="text_css" /></td></tr>
		<tr><td class="td_0">管理员 Email:</td><td class="td_1"><input type="text" size="30" value="" name="settings[email]" id="settings[email]" class="text_css" /></td></tr>
		<tr><td></td><td style="padding-top:10px;"><input class="button_css" type="button" value="  创建管理员帐号并继续安装  " id="step3_btn" onclick="addAdmin()" /><div id="step3_tips"></div></td></tr>
	</table>
	</form>
<?php
	break;
	case 4:
?>
	<div class="div_clear" style="height:10px;"></div>
	<div class="login_st0">6KZZ快站安装成功</div>
	<div class="login_st2">恭喜您，6KZZ快站已经安装成功。<br /><a href="../index.php">进入6KZZ快站首页</a>&nbsp;&nbsp;&nbsp;<a href="../admin/">进入后台管理</a></div>
	<script>
		function finishInstall(){
			ajaxGet("ajaxinstall.php?action=finishInstall",finishInstall_callback);
		}
		function finishInstall_callback(data){
			//alert(data);
		}
		finishInstall();		
	</script>

<?php
	break;
}
?>
</div>
<div class="login_footer">Powered by <a href="http://www.6kzz.com" target="_blank">6KZZ V1.4</a> &copy; 2011 6kzz.com</div>
<?php
}
?>
<script>
function testConnect(){
	if(E("settings[dbserver]").value=="" || E("settings[dbuser]").value==""){
		alert("测试之前，请填写完整以下项目：\r\n·数据库服务器\r\n·数据库用户名\r\n");
		return;
	}
	E("step2_testtips").innerHTML = "<img src=\"loading.gif\" border=\"0\" align=\"absmiddle\" /> <span class=\"status_ing\">正在测试，请耐心等候...</span>";
	E("step2_testbtn").disabled = true;
	ajaxPost("step2form","ajaxinstall.php?action=testConnect",testConnect_callback);
}
function testConnect_callback(data){
	E("step2_testtips").innerHTML = "";
	E("step2_testbtn").disabled = false;
	if(data.substring(0,3)=="_Y_"){
		E("step2_testtips").innerHTML="<b class='status_succeed'>√ 数据库连接测试成功！</b>";
	}else{
		E("step2_testtips").innerHTML="<b class='status_failed'>"+data+"</b>";
	}
}

function setChmod(){
	E("step1_tips").innerHTML = "<img src=\"loading.gif\" border=\"0\" align=\"absmiddle\" /> <span class=\"status_ing\">请稍候...</span>";
	E("step1_btn").disabled = true;
	ajaxGet("ajaxinstall.php?action=setChmod",setChmod_callback);
}
function setChmod_callback(data){
	if(data.substring(0,3)=="_Y_"){
		E("step1_tips").innerHTML = "<img src=\"loading.gif\" border=\"0\" align=\"absmiddle\" /> <span class=\"status_succeed\">正在进入下一步。</span>";
		window.location.href="index.php?step=2";
	}else{
		E("step1_tips").innerHTML = "<span class=\"status_failed\">"+data+"</span>";
		E("step1_btn").disabled = false;
	}
}


function genData(){
	if(E("settings[dbserver]").value=="" || E("settings[dbuser]").value=="" || E("settings[dbname]").value==""|| E("settings[dbpre]").value==""){
		alert("请填写完整所有项目。");
		return;
	}
	E("step2_tips").innerHTML = "<img src=\"loading.gif\" border=\"0\" align=\"absmiddle\" /> <span class=\"status_ing\">正在生成数据库，请耐心等候...</span>";
	E("step2_btn").disabled = true;
	ajaxPost("step2form","ajaxinstall.php?action=genData",genData_callback);
}
function genData_callback(data){
	if(data.substring(0,3)=="_Y_"){
		E("step2_tips").innerHTML = "<img src=\"loading.gif\" border=\"0\" align=\"absmiddle\" /> <span class=\"status_succeed\">数据库生成成功，正在进入下一步。</span>";
		window.location.href="index.php?step=3";
	}else{
		E("step2_tips").innerHTML = "<span class=\"status_failed\">"+data+"</span>";
		E("step2_btn").disabled = false;
	}
}

function addAdmin(){
	if(E("settings[username]").value=="" || E("settings[userpass]").value=="" || E("repass").value==""|| E("settings[email]").value==""){
		alert("请填写完整所有项目。");
		return;
	}
	if(E("settings[userpass]").value != E("repass").value){
		alert("管理员密码两次填写不一致。");
		return;
	}
	E("step3_tips").innerHTML = "<img src=\"loading.gif\" border=\"0\" align=\"absmiddle\" /> <span class=\"status_ing\">管理员帐号创建中，请耐心等候...</span>";
	E("step3_btn").disabled = true;
	ajaxPost("step3form","ajaxinstall.php?action=addAdmin",addAdmin_callback);
}
function addAdmin_callback(data){
	if(data.substring(0,3)=="_Y_"){
		E("step3_tips").innerHTML = "<img src=\"loading.gif\" border=\"0\" align=\"absmiddle\" /> <span class=\"status_succeed\">管理员帐号创建成功，正在进入下一步。</span>";
		window.location.href="index.php?step=4";
	}else{
		E("step3_tips").innerHTML = "<span class=\"status_failed\">"+data+"</span>";
		E("step3_btn").disabled = false;
	}
}



window.onload=function(){
	if(E("step2_btn")){E("step2_btn").disabled=false;}
	if(E("step3_btn")){E("step3_btn").disabled=false;}
}


</script>
</body>
</html>
