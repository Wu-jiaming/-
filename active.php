<?php
session_start();
define('IN_TG',true);
define('SCRIPT','active');
require dirname(__FILE__).'/include/common.inc.php';
if (!isset($_GET['active'])) {
	_alert_back('非法操作');
}
if (isset($_GET['action']) && isset($_GET['active']) && $_GET['action'] == 'ok') 
{
	$_active = _mysql_string($_GET['active']);
	
	if (_fetch_array("SELECT tg_active FROM tg_user WHERE tg_active='$_active' LIMIT 1")) 
	{
		//将tg_active设置为空
				_query("UPDATE tg_user SET tg_active=NULL WHERE tg_active='$_active' LIMIT 1");
				if (_affect_rows() == 1)
				{
					_close();
					_location('账户激活成功','login.php');
				} else
					{
						_close();
						_location('账户激活失败','register.php');
					}
	} else
		{
			_alert_back('找不到相等的active');
		}
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<?php 
	require ROOT_PATH.'/include/title.inc.php';
?>
<script type="text/javascript" src="js/register.js"></script	>
</head>  
<body>
<?php require ROOT_PATH.'include/header.inc.php'; ?>
<div id="active">
	<h2>激活账户</h2>
	<p>本页面是模拟邮件发送功能  用于激活你的账户</p>
	<p><a href="active.php?action=ok&amp;active=<?php echo $_GET['active']?>"><?php echo 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]?>active.php?action=ok&amp;active=<?php echo $_GET['active']?></a></p>
</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html> 