<?php
function LOGGER($content){
	$fo=fopen("logger.txt","a");
	$content="[".getNow()."]:".$content."\r\n";
	fwrite($fo,$content);
	fclose($fo);
}
//得到秒数
function getTimer(){
	return time()+8*60*60;
}

//获取当前时间
function getNow($fmt="Y-m-d H:i:s"){
	return gmdate($fmt, getTimer());
}
?>
