<?php
session_start();
define('IN_TG',true);
define('SCRIPT','blog');
require dirname(__FILE__).'/include/common.inc.php';

global $pagenum,$pagesize,$system;
_page("SELECT tg_id FROM tg_user",$system['blog']);
$result=_query("SELECT tg_id,tg_username ,
										   tg_sex ,tg_face 
							FROM tg_user  
						ORDER BY tg_reg_time DESC 
				LIMIT $pagenum,$pagesize");
//搜索会员
if ($_GET['action'] == 'search' ){
	if (!!$rows=_fetch_array("SELECT tg_uniqid 
			FROM tg_user
			WHERE
			tg_username='{$_COOKIE['username']}'
			LIMIT 1"))
	{
		_uniqid($_COOKIE['uniqid'], $rows['tg_uniqid']);
		//根据提交上来的数据进行精确搜索
		
		if(!!$rows=_fetch_array("SELECT tg_id FROM tg_user WHERE tg_username='{$_POST['search']}'"))
		{
			//找到该会员
			_location('找到该会员', 'find_member.php?id='.$rows['tg_id']);
		}else 
		{
			//找不到
			_alert_back('该会员不存在！请重新搜索！');
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
	<h2>小弟列表</h2>
	<?php while(!!$rows=_fetch_array_list($result)) {
				$html=array();
				$html['id']=_html($rows['tg_id']);
				$html['username']=_html($rows['tg_username']);
				$html['face']=_html($rows['tg_face']);
				$html['sex']=_html($rows['tg_sex']);
				?>
	<dl>
	
		<dd class="username"><?php  echo $html['username']?>(<?php echo $html['sex']?>)</dd>
		<dt><img src="<?php echo $html['face'] ?> "alt="吴家明帅哥"/></dt>
		<dd class="message"><a href ="javascript:;" name ="message" title="<?php echo $html['id']?>">发消息</a></dd>
		<dd class="friend"><a href ="javascript:;" name ="friend" title="<?php echo $html['id']?>">加为好友</a></dd>
		<dd class="guest">写留言</dd>
		<dd class="flower"><a href ="javascript:;" name ="flower" title="<?php echo $html['id']?>">送花</a></dd>
	</dl>
<?php }?>
</div>
<div id="search">
	<form method="post" action="blog.php?action=search" name="search">
	<dl>
		<dd>搜索会员：<input type="text" name="search" class="text"/><input type="submit"  name="submit" value="search"/></dd>
		
	</dl>
	</form>
</div>

<?php 
_free_result($result);
	_type(2);
?>


<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   