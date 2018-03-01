<?php 
session_start();
define('IN_TG',true);
define('SCRIPT','post');
require dirname(__FILE__).'/include/common.inc.php';
if(!isset($_COOKIE['username'])){
	_location('发贴前必须先登录', 'login.php');
}
global $system;
if ($_GET['action'] == 'post'){
	if (!empty($system['code'])){
		_check_yzm($_POST['yzm'], $_SESSION['code']);
	}
	if (!!$rows = _fetch_array("SELECT tg_uniqid 
											FROM tg_user
									WHERE tg_username='{$_COOKIE['username']}'
								LIMIT 1"
									)){
		_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
		_timed(time(), $rows['tg_post_time'], $system['post']);
		include ROOT_PATH.'/include/check.func.php';
		
		$clean=array();
		$clean['username']=$_COOKIE['username'];
		$clean['type']=$_POST['type'];
		$clean['title']=_check_post_title($_POST['title'], 1, 20);
		$clean['content']=_check_post_content($_POST['content'], 1, 400);
		$clean=_mysql_string($clean);
		
		
		_query("INSERT INTO tg_article(
														tg_username,
														tg_type,
														tg_title,
														tg_content,
														tg_time
				)
											VALUES(
										'{$clean['username']}',
										'{$clean['type']}',
										'{$clean['title']}',
										'{$clean['content']}',
										now()	
					)");
		if (_affect_rows() ==1){
			//刷新获取新增的id
			$clean['id'] =_insert_id();
			_query("update tg_user set tg_score = tg_score+3  where tg_username='{$_COOKIE['username']}'");
			
			_close();
			//_session_destroy();
			_location('帖子发送成功', 'article.php?id='.$clean['id']);
		}else{
			_close();
			//_session_destroy();
			_alert_back('帖子发送失败');
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
<script type="text/javascript" src="js/code.js"></script	>
<script type="text/javascript" src="js/post.js"></script	>

</head>  
<body>
<?php 
	require ROOT_PATH.'/include/header.inc.php';
?>

<div id="post">
	<h2>发表帖子</h2>
	<form method="post" name="post" action="?action=post">
		<dl>
			<dt>填写帖子的内容</dt>
				<dd>
					类型：
					<?php 
						foreach (range(1,16) as $num){
							if ($num ==1){
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
				<dd>标 	 	题：<input type="text" name="title" class="text" /></dd>
				<dd id="q">贴	 	图：<a href="javascript:;">Q图系列[1]</a> 	<a href="javascript:;">Q图系列[2]</a> 	<a href="javascript:;">Q图系列[3]</a></dd>
				<?php 
					include ROOT_PATH.'include/ubb.inc.php';
				?>
			
				<dd>
				<?php if (!empty($system['code'])){?>
				验 证 码：<input type="text" name="yzm" class="yzm" /><img src="code.php" id="code"/>
				<?php }?>
				<input type="submit" class="submit" value="发表帖子"/></dd>
		</dl>
	
	</form>
</div>
<?php 
	require ROOT_PATH.'/include/footer.inc.php';
?>
</body>
</html>