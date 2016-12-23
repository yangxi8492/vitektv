<?php
require_once('./inc/init.php');
//找到联系我们的记录
foreach($cache_channels as $contact){
	if($contact['systemtype']=='2'){
		break;
	}
}
$cid=intval($contact['id']);
$cache_contact=$cache_contacts[0];

require_once getTemplatePath('widget_contact.htm');
require_once('./page.php');
?>