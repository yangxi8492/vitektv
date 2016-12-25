<?php

$webmaintitle=(empty($cache_settings['seotitle'])?strip_tags($cache_settings['webname']):strip_tags($cache_settings['seotitle']));
$headtitle=empty($headtitle)? $webmaintitle : "{$headtitle} - ".strip_tags($cache_settings['webname']);
$headtitle.=$cache_global['copyrightheader'];

//语言
$langstr='<li>';
foreach($cache_langs  as $key=>$lang){
	if($key==2) $langstr.="|";
	$langstr.="<a href='index.php?langid={$lang['id']}'>{$lang['name']}</a>";
}
$langstr.='</li>';
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
		//$bannerad.="<li><a href=\"".$cache_settings['bannerlink'.$b]."\" target=\"_blank\"><img src=\"".$webcore->getPicPath($cache_settings['bannerpath'.$b])."\"></a></li>";
		$bannerad.='<a href="'.$cache_settings['bannerlink'.$b].'" class="d1" style="background:url('.$webcore->getPicPath($cache_settings['bannerpath'.$b]).') center no-repeat;background-size: 100% 594px"></a>';
	}
}

//Logo
$cache_settings['logopath']=$webcore->getPicPath($cache_settings['logopath']);

$headmeta=""
."<meta name=\"keywords\" content=\"{$headkeywords}\" />\n"
."<meta name=\"description\" content=\"{$headdesc}\" />\n"
."<meta name=\"MSSmartTagsPreventParsing\" content=\"True\" />\n"
."<meta http-equiv=\"MSThemeCompatible\" content=\"Yes\" />\n"
."<meta http-equiv=\"x-ua-compatible\" content=\"ie=7\" />\n"
."<script type=\"text/javascript\" src=\"language/{$cache_langs[$_cachelangid]['directory']}/language.js\"></script>";

$k=empty($k)?$_LANG['header.search']:htmlFilter($k);
require_once getTemplatePath('header.htm');
?>
