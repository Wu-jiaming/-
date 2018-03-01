<?php
session_start();
define('IN_TG',true);
define('SCRIPT','photo_add');
require dirname(__FILE__).'/include/common.inc.php';

_manage_login();
if ($_GET['action'] == 'adddir'){
		if (!!$rows=_fetch_array("SELECT tg_uniqid
																			FROM tg_user
																	WHERE tg_username ='{$_COOKIE['username']}'
															LIMIT 1")	){
					_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
					include dirname(__FILE__).'/include/check.func.php';
					$clean = array();
					$clean['name'] =_check_name($_POST['name'],2,20);
					$clean['password'] = _check_password_one($_POST['password'],6,40);
					$clean['dir'] =time();
					$clean['content'] = _check_post_content($_POST['content'],0,400);
					$clean['type'] = $_POST['type'];
					$clean= _mysql_string($clean);
			
					if (!is_dir('photo')){
						mkdir('photo',0777);
					}
					if (!is_dir('photo/'.$clean['dir'])){
						mkdir('photo/'.$clean['dir']);
					}
					if (empty($clean['type'])){
								_query("INSERT INTO tg_dir
																	(
																	 tg_name,
																	 tg_type,
																	 tg_content,
																	 tg_dir,
																	 tg_time
													)
											VALUES(
														'{$clean['name']}',
														'{$clean['type']}',
														'{$clean['content']}',
														'photo/{$clean['dir']}',
														now()
														
										)
										");
					}else{
							_query("INSERT INTO tg_dir
																	(
																	 tg_name,
																	 tg_type,
																	 tg_content,
																	 tg_dir,
																	 tg_password,
																	 tg_time
													)
											VALUES(
														'{$clean['name']}',
														'{$clean['type']}',
														'{$clean['content']}',
														'photo/{$clean['dir']}',
														'{$clean['password']}',
														now()
														
										)
										");
					}
		}//目录添加成功
		if(_affect_rows() ==1){
			_close();
			_location('目录添加成功', 'photo.php');
			
		}else{
			_close();
			_alert_back('目录添加失败');
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
<script type="text/javascript" src="js/photo_add.js"></script>
</head>  
<body>
<?php require ROOT_PATH.'include/header.inc.php'; ?>
<div id="photo">
	<h2>添加相册目录</h2>
		<form method="post" action="?action=adddir">
			<dl>
					<dd>相册名称：<input type="text" name="name" class="text"/></dd>
					<dd>相册类型：<input type="radio" name="type" value="0" checked="checked"/>公开
												  <input type="radio" name="type" value="1" />私密 </dd>
					<dd>相册介绍：<textarea name="content"></textarea></dd>
					<dd id="pass">相册密码：<input type="password" name="password" class="text"/> </dd>
					<dd><input type="submit" class="submit" value="添加目录"/> </dd>
			</dl>
		</form>


</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   