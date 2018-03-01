<?php
define('IN_TG',true);
define('SCRIPT','face');
require dirname(__FILE__).'/include/common.inc.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<?php 
	require ROOT_PATH.'/include/title.inc.php';
?>
<script type="text/javascript" src="js/opener.js"></script>
</head>  
<body>
<div id="face">
	<h3>头像选择</h3>
<dl>
			<?php  foreach(range(1,9)as $num)		{?>
				<dd><img src="face/m0<?php echo $num?>.gif" alt="face/m0<?php echo $num?>.gif" title="头像<?php echo $num?>"/></dd>
				<?php } ?>
			
		</dl>	
		<dl>
			<?php  foreach(range(10,64)as $num)		{?>
				<dd><img src="face/m<?php echo $num?>.gif" alt="face/m<?php echo $num?>.gif" title="头像<?php echo $num?>"/></dd>
				<?php } ?>
			
		</dl>	
</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html> 