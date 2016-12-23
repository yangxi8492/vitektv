<?php
//免费版本必须保留版权信息声明。如果您有需要修改版权声明，请购买企业授权。详见：http://www.6kzz.com/zz/service.php
$webmaintitle=(empty($cache_settings['seotitle'])?strip_tags($cache_settings['webname']):strip_tags($cache_settings['seotitle']));
$headtitle=empty($headtitle)? $webmaintitle : "{$headtitle} - ".strip_tags($cache_settings['webname']);
$headtitle.=$cache_global['copyrightheader'];

//语言
$langstr='';
foreach($cache_langs as $lang){
	$langstr.="<li><a href='index.php?langid={$lang['id']}' style='background-image:url(language/{$lang['directory']}/flag.gif)'>{$lang['name']}</a></li>";
}
//位置
$_SYS['indexurl'] = $webcore->genUrl('index.php'); 
$_SYS['positionindex'] = "<a href=\"{$_SYS['indexurl']}\">{$cache_settings['webname']}</a>";

//友情链接
foreach($cache_links_logo as $link){
	$links_logo.="<li><a href=\"{$link['url']}\" target=\"_blank\" title=\"{$link['content']}\"><img src=\"{$link['logo']}\" border=\"0\" /></a></li>";
}
foreach($cache_links_text as $link){
	$links_text.="<li><a href=\"{$link['url']}\" target=\"_blank\" title=\"{$link['content']}\">{$link['name']}</a></li>";
}


//Banner获取
$bannerad="";
for($b=1;$b<6;$b++){
	if(intval($cache_settings["banner".$b])>0){
		$bannerad.="<li><a href=\"".$cache_settings['bannerlink'.$b]."\" target=\"_blank\"><img src=\"".$webcore->getPicPath($cache_settings['bannerpath'.$b])."\"></a></li>";
	}
}

//Logo
$cache_settings['logopath']=$webcore->getPicPath($cache_settings['logopath']);

$headmeta=""
."<meta name=\"keywords\" content=\"{$headkeywords}\" />\n"
."<meta name=\"description\" content=\"{$headdesc}\" />\n"
."<meta name=\"generator\" content=\"6KZZ v1.4\" />\n"
."<meta name=\"author\" content=\"www.6kzz.com\" />\n"
."<meta name=\"copyright\" content=\"2011 6KZZ\" />\n"
."<meta name=\"MSSmartTagsPreventParsing\" content=\"True\" />\n"
."<meta http-equiv=\"MSThemeCompatible\" content=\"Yes\" />\n"
."<meta http-equiv=\"x-ua-compatible\" content=\"ie=7\" />\n"
."<script type=\"text/javascript\" src=\"language/{$cache_langs[$_cachelangid]['directory']}/language.js\"></script>";

$k=empty($k)?$_LANG['header.search']:htmlFilter($k);
require_once getTemplatePath('header.htm');
?>
