<?php 
session_start();
define('IN_TG',true);
define('SCRIPT','article_modify');
require dirname(__FILE__).'/include/common.inc.php';
if(!isset($_COOKIE['username'])){
	_location('发贴前必须先登录', 'login.php');
}
global $system;
if ($_GET['action'] == 'modify' && isset($_POST['id'])){
	if (!empty($system['code'])){
		_check_yzm($_POST['yzm'], $_SESSION['code']);
	}
	if (!!$rows = _fetch_array("SELECT tg_uniqid 
											FROM tg_user
									WHERE tg_username='{$_COOKIE['username']}'
								LIMIT 1"
									)){
		_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
		//_timed(time(), $rows['tg_post_time'], $system['post']);
		include ROOT_PATH.'/include/check.func.php';
		
		$clean=array();
		$clean['username']=$_COOKIE['username'];
		$clean['type']=$_POST['type'];
		$clean['title']=_check_post_title($_POST['title'], 1, 20);
		$clean['content']=_check_post_content($_POST['content'], 1, 400);
		$clean['id']=$_POST['id'];
		$clean=_mysql_string($clean);
		
		
		_query("UPDATE tg_article SET
														tg_username ='{$clean['username']}',
														tg_type ='{$clean['type']}',
														tg_title =	'{$clean['title']}',
														tg_content = 	'{$clean['content']}',
														tg_time =now()	
											WHERE tg_id='{$clean['id']}'
										
					");

		if (_affect_rows() ==1){
			$rows=_fetch_array("SELECT tg_title FROM tg_article
					WHERE tg_reid = '{$clean['id']}' ");
			//截取字符串 从RE：截取标题之后的字符串
			$sub_title=explode("E:",$rows['tg_title']);
			_query("UPDATE tg_article SET
										tg_type =	'{$clean['type']}',
										tg_title = '{$sub_title['0']}E:{$clean['title']}'
					WHERE tg_reid = '{$_POST['id']}' ");
			_close();
			//_session_destroy();
			_location('帖子修改成功', 'article.php?id='.$clean['id']);
		}else{
			_close();
			//_session_destroy();
			_alert_back('帖子修改失败');
		}
}
}
//读取数据
if (isset($_GET['id'])){
	if (!!$rows=_fetch_array("SELECT tg_title,tg_content,tg_type,tg_id,tg_username FROM tg_article WHERE tg_id='{$_GET['id']}'"))
	{
		$html=array();
		$html['title']=$rows['tg_title'];
		$html['content']=$rows['tg_content'];
		$html['type']=$rows['tg_type'];
		$html['id']=$rows['tg_id'];
		$html['username']=$rows['tg_username'];
		$html = _html($html);
		
		//判断权限
		if ((!$_SESSION['admin']) ||($_COOKIE['username'] != $html['username'] ))
		{
			_alert_back('没有权限修改');
		}
	}else{
		_alert_back('不存在此帖子');
	}
}else{
	_alert_back('获取该帖子的id失败');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<?php
require ROOT_PATH.'/include/title.inc.php';

?>  
<script type="text/javascript" src="js/code.js"></script	>
<script type="text/javascript" src="js/post.js"></script	>

</head>  
<body>
<?php 
	require ROOT_PATH.'/include/header.inc.php';
?>

<div id="post">
	<h2>修改帖子</h2>
	<form method="post" name="post" action="?action=modify">
		<input type="hidden" value="<?php echo $html['id'] ?>" name="id"/>
		<dl>
			<dt>修改帖子的内容</dt>
				<dd>
					类型：
					<?php 
						foreach (range(1,16) as $num){
							if ($num ==  $html['type'] ){
								echo '<label for="type'.$num.'"><input type="radio" id="type'.$num.'" name="type" value="'.$num.'" checked="checked" />';
							
							}else{
								echo '<label for="type'.$num.'"><input type="radio" id="type'.$num.'" name="type" value="'.$num.'"  />';
							}
							echo '<img src="image/icon'.$num.'.gif" alt="类型" /></label>';
							if($num ==8){
								echo '<br />　　　 ';
							}
						}
					?>
				</dd>
				<dd>标 	 	题：<input type="text" name="title" value="<?php echo $html['title']?>" class="text" /></dd>
				<dd id="q">贴	 	图：<a href="javascript:;">Q图系列[1]</a> 	<a href="javascript:;">Q图系列[2]</a> 	<a href="javascript:;">Q图系列[3]</a></dd>
				<dd>
				<?php 
					include ROOT_PATH.'include/ubb.inc.php';
				?>
				
				<textarea name="content"  rows="9"><?php echo $html['content']?></textarea>
				</dd>
				<dd>
				<?php if (!empty($system['code'])){?>
				验 证 码：<input type="text" name="yzm" class="yzm" /><img src="code.php" id="code"/>
				<?php }?>
				<input type="submit" class="submit" value="完成修改"/></dd>
				
		</dl>
	
	</form>
</div>
<?php 
	require ROOT_PATH.'/include/footer.inc.php';
?>
</body>
</html>