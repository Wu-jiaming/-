<?php

define('IN_TG', true);
define('SCRIPT', 'find_member');
require dirname(__FILE__).'/include/common.inc.php';

if ($_GET['id']){
	if (!!$rows=_fetch_array("SELECT tg_uniqid
			FROM tg_user
			WHERE
			tg_username='{$_COOKIE['username']}'
			LIMIT 1"))
	{
		_uniqid($_COOKIE['uniqid'], $rows['tg_uniqid']);
		$html=array();
		if (!!$rows=_fetch_array("SELECT * FROM tg_user WHERE tg_id='{$_GET['id']}'"))
		{
			//找该会员的数据
			$html['username'] = $rows['tg_username'];
			$html['sex'] = $rows['tg_sex'];
			$html['face'] = $rows['tg_face'];
			$html['id'] = $rows['tg_id'];
				
			
			
		}else
		{
			//找不到该会员
			_alert_back('找不到该会员');
		}
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
<script type="text/javascript" src="js/blog.js"></script>
</head>  
<body>
<?php require ROOT_PATH.'include/header.inc.php'; ?>
<div id="blog">
	<h2><?php  echo $html['username']?></h2>
	
	<dl>
	
		<dd class="username"><?php  echo $html['username']?>(<?php echo $html['sex']?>)</dd>
		<dt><img src="<?php echo $html['face'] ?> "alt="头像"/></dt>
		<dd class="message"><a href ="javascript:;" name ="message" title="<?php echo $html['id']?>">发消息</a></dd>
		<dd class="friend"><a href ="javascript:;" name ="friend" title="<?php echo $html['id']?>">加为好友</a></dd>
		<dd class="guest">写留言</dd>
		<dd class="flower"><a href ="javascript:;" name ="flower" title="<?php echo $html['id']?>">送花</a></dd>
	</dl>

</div>



<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   