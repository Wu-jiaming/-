<?php
define('IN_TG',true);
define('SCRIPT','q');
require dirname(__FILE__).'/include/common.inc.php';
if (isset($_GET['num'])&&isset($_GET['path'])){
		if (!is_dir(ROOT_PATH.$_GET['path'])){
			_alert_back('非法操作');
			
		}
}else{
		_alert_back('非法操作');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<title>多用户留言系统-q图选择</title>  
<?php 
	require ROOT_PATH.'/include/title.inc.php';
?>
<script type="text/javascript" src="js/qopener.js"></script>
</head>  
<body>
<div id="q">
	<h3>q图选择</h3>
<dl>
			<?php  foreach(range(1,$_GET['num'])as $num)		{?>
				<dd><img src="<?php echo $_GET['path'].$num?>.gif" alt="<?php echo $_GET['path'].$num?>.gif" title="头像<?php echo $num?>"/></dd>
				<?php } ?>
			
		</dl>	
		
</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html> 