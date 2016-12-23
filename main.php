<?php
require_once('./inc/init.php');
$headkeywords=strip_tags(str_replace(array("\r", "\n"), array('', ''), $cache_settings['metakeywords']));
$headdesc=strip_tags(str_replace(array("\r", "\n"), array('', ''), $cache_settings['metadescription']));

require_once('./header.php');
require_once getTemplatePath('main.htm');
footer();
?>