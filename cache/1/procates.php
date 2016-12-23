<?php
$cache_procates = array(
	'1' => array(
		'id' => '1',
		'pid' => '0',
		'ordernum' => '1',
		'ishidden' => '0',
		'alias' => '',
		'title' => 'ThinkPad',
		'seotitle' => '',
		'metakeywords' => '',
		'metadesc' => '',
		'langid' => '1',
		'childcid' => array(
		),
	),
	'8' => array(
		'id' => '8',
		'pid' => '1',
		'ordernum' => '8',
		'ishidden' => '0',
		'alias' => '',
		'title' => '商务本',
		'seotitle' => '',
		'metakeywords' => '',
		'metadesc' => '',
		'langid' => '1',
		'childcid' => array(
		),
	),
	'9' => array(
		'id' => '9',
		'pid' => '1',
		'ordernum' => '9',
		'ishidden' => '0',
		'alias' => '',
		'title' => '游戏本',
		'seotitle' => '',
		'metakeywords' => '',
		'metadesc' => '',
		'langid' => '1',
		'childcid' => array(
		),
	),
	'10' => array(
		'id' => '10',
		'pid' => '1',
		'ordernum' => '10',
		'ishidden' => '0',
		'alias' => '',
		'title' => '学生本',
		'seotitle' => '',
		'metakeywords' => '',
		'metadesc' => '',
		'langid' => '1',
		'childcid' => array(
		),
	),
	'2' => array(
		'id' => '2',
		'pid' => '0',
		'ordernum' => '2',
		'ishidden' => '0',
		'alias' => '',
		'title' => '戴尔 (DELL)',
		'seotitle' => '',
		'metakeywords' => '',
		'metadesc' => '',
		'langid' => '1',
		'childcid' => array(
		),
	),
	'3' => array(
		'id' => '3',
		'pid' => '0',
		'ordernum' => '3',
		'ishidden' => '0',
		'alias' => '',
		'title' => '宏碁 (Acer)',
		'seotitle' => '',
		'metakeywords' => '',
		'metadesc' => '',
		'langid' => '1',
		'childcid' => array(
		),
	),
	'4' => array(
		'id' => '4',
		'pid' => '0',
		'ordernum' => '4',
		'ishidden' => '0',
		'alias' => '',
		'title' => '华硕 (ASUS)',
		'seotitle' => '',
		'metakeywords' => '',
		'metadesc' => '',
		'langid' => '1',
		'childcid' => array(
		),
	),
	'5' => array(
		'id' => '5',
		'pid' => '0',
		'ordernum' => '5',
		'ishidden' => '0',
		'alias' => '',
		'title' => '惠普 (HP)',
		'seotitle' => '',
		'metakeywords' => '',
		'metadesc' => '',
		'langid' => '1',
		'childcid' => array(
		),
	),
	'6' => array(
		'id' => '6',
		'pid' => '0',
		'ordernum' => '6',
		'ishidden' => '0',
		'alias' => '',
		'title' => '联想 (Lenovo)',
		'seotitle' => '',
		'metakeywords' => '',
		'metadesc' => '',
		'langid' => '1',
		'childcid' => array(
		),
	),
); 

array_push($cache_procates['1']['childcid'],'8');
array_push($cache_procates['1']['childcid'],'9');
array_push($cache_procates['1']['childcid'],'10');

$cache_procates_option = '<option value="1">&gt;&gt; ThinkPad</option><option value="8"> &nbsp;|- 商务本</option><option value="9"> &nbsp;|- 游戏本</option><option value="10"> &nbsp;|- 学生本</option><option value="2">&gt;&gt; 戴尔 (DELL)</option><option value="3">&gt;&gt; 宏碁 (Acer)</option><option value="4">&gt;&gt; 华硕 (ASUS)</option><option value="5">&gt;&gt; 惠普 (HP)</option><option value="6">&gt;&gt; 联想 (Lenovo)</option>'; 
$cache_procates_option2 = '<optgroup label="&gt;&gt;ThinkPad"><option value="8"> &nbsp;|- 商务本</option><option value="9"> &nbsp;|- 游戏本</option><option value="10"> &nbsp;|- 学生本</option></optgroup><optgroup label="&gt;&gt;戴尔 (DELL)"></optgroup><optgroup label="&gt;&gt;宏碁 (Acer)"></optgroup><optgroup label="&gt;&gt;华硕 (ASUS)"></optgroup><optgroup label="&gt;&gt;惠普 (HP)"></optgroup><optgroup label="&gt;&gt;联想 (Lenovo)"></optgroup>'; 
?>