<?php
session_start();
define('IN_TG',true);
define('SCRIPT','photo');
require dirname(__FILE__).'/include/common.inc.php';
//必须从上一页点击过来  才能设置 二期额必须具备id
$skin_url=$_SERVER['HTTP_REFERER'];
if (empty($skin_url) || !isset($_GET['id'])){
	_alert_back('非法操作');
}else {
	setcookie('skin',$_GET['id']);
	_location(null, $skin_url);
}
?>