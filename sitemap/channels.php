<?php
require_once('../inc/init.php');
$langid=intval($_GET['langid']);
$urlrewrite = intval($cache_settings['urlrewrite']);
_header_('Content-Type:text/xml;');
print"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
$lastmod=getDateStr(time(),"dateonly",false);
print <<<EOT
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\r\n
EOT;
$rows=$db->row_select("channels","langid={$langid}","5000","id,ishidden,channeltype,pid,systemtype");

$i=0;
foreach($rows as $channel){
if($channel['channeltype']=='4')continue;
$priority=1-($i++/10000);
$priority=$priority<0.6?0.6:$priority;
$priority=number_format($priority,2);
$locurl=$webcore->genNavLink($channel);
$locurl=str_replace('/sitemap/','/',$locurl);
print <<<EOT
<url>
<loc>{$locurl}</loc>
<priority>{$priority}</priority>
<changefreq>daily</changefreq>
<lastmod>{$lastmod}</lastmod>
</url>\r\n
EOT;
}
print <<<EOT
</urlset>
EOT;
?>
