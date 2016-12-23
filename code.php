<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
require_once('./inc/validationCode.php');
require_once('./inc/var.php');
$image = new ValidationCode('60','20','4');    //图片长度、宽度、字符个数
$image->outImg();
$_SESSION[$_SYS['sessionpre'].'validationcode'] = $image->checkcode;
?>