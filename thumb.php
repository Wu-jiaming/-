<?php
define('IN_TG',true);
define('SCRIPT','thumb');
require dirname(__FILE__).'/include/common.inc.php';

if (isset($_GET['filename']) && isset($_GET['percent'])) {
	_thumb($_GET['filename'],$_GET['percent']);
}


?>
