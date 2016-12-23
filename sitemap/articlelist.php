<?php
require_once('./../inc/init.php');
$langid=intval($_GET['langid']);
$urlrewrite = intval($cache_settings['urlrewrite']);
$_viewurl='view.php?id={id}&amp;langid={langid}';
_header_('Content-Type:text/xml;');
print"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
$rows=$db->row_select("articles","langid={$langid}","500","id","posttime desc");
$url=getUrlPath(-1);
$lastmod=getDateStr(time(),"dateonly",false);
print <<<EOT
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\r\n
EOT;
$i=0;
foreach($rows as $row){
$priority=1-($i++/10000);
$priority=$priority<0.6?0.6:$priority;
$priority=number_format($priority,2);
$viewurl=$_viewurl;
$viewurl=str_replace('{id}',$row['id'],$viewurl);
$viewurl=str_replace('{langid}',$langid,$viewurl);
print <<<EOT
<url>
<loc>{$url}/{$viewurl}</loc>
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
