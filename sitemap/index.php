<?php
require_once('./../inc/init.php');
_header_('Content-Type:text/xml;');
echo"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
$url=getUrlPath(0);
$lastmod=getDateStr(time(),'dateonly',false);
print <<<EOT
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
EOT;
foreach($cache_langs as $lang){
print <<<EOT
<sitemap>
	<loc>{$url}/channels.php?langid={$lang['id']}</loc>
	<lastmod>{$lastmod}</lastmod>
</sitemap>
<sitemap>
	<loc>{$url}/articlelist.php?langid={$lang['id']}</loc>
	<lastmod>{$lastmod}</lastmod>
</sitemap>
<sitemap>
	<loc>{$url}/productlist.php?langid={$lang['id']}</loc>
	<lastmod>{$lastmod}</lastmod>
</sitemap>
EOT;
}
print <<<EOT
</sitemapindex>
EOT;
?>