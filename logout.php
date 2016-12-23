<?php
require_once('inc/init.php');
$t= -86400 * 365 * 2;
uSESSION('memberid');
uSESSION('groupid');
//session_destroy();
setCookies('username', '', $t);
setCookies('userpass', '', $t);
setCookies('expire', '', $t);
setCookies('memberauth', '', $t);
printMsg('logout_succeed');
?>