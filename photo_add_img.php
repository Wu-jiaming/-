<?php
session_start();
define('IN_TG',true);
define('SCRIPT','photo_add_img');
require dirname(__FILE__).'/include/common.inc.php';

//无论会员或者是管理员都能上传
if (!$_COOKIE['username']){
	_alert_back('非法登陆');
}

//把数据存进数据库
if ($_GET['action'] == 'addimg'){
	if (!!$rows=_fetch_array("SELECT tg_uniqid
																			FROM tg_user
																	WHERE tg_username ='{$_COOKIE['username']}'
															LIMIT 1")	){
			_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
			include dirname(__FILE__).'/include/check.func.php';
			//接受数据
			$clean = array();
			$clean['name'] = _check_name($_POST['name'], 1, 20);
			$clean['url'] = _check_photo_url($_POST['url']);
			$clean['sid'] = $_POST['sid'];
			$clean['content']=$_POST['content'];
			$clean = _mysql_string($clean);
			//写入
			_query("INSERT INTO tg_photo
													(tg_name,
													 tg_url,
						   	  		 	  	 	 	 tg_sid,
						 	 	 	  		 	 	 tg_content,
													tg_username,
						      	       				tg_time
													
					 		 	 	 	 	 	 	 )
										VALUES('{$clean['name']}',
														'{$clean['url']}',
										 		  	 	'{$clean['sid']}',
										 		  	 	'{$clean['content']}',
										 		  	 	'{$_COOKIE['username']}',
										 		  	 	now()
														)");
			if(_affect_rows() ==1){
				_query("update tg_user set tg_score = tg_score+3  where tg_username='{$_COOKIE['username']}'");
				_close();
				
				_location('图片添加成功', 'photo_show.php?id='.$clean['sid']);
				
			}else{
				_close();
				_alert_back('图片添加失败');
			}
	}
}
	//取值
	if (isset($_GET['id'])){
		if (!!$rows = _fetch_array("SELECT tg_id,
										tg_dir
								FROM tg_dir
						WHERE tg_id='{$_GET['id']}'
					LIMIT 1")){
				$html=array();
				$html['id'] = $rows['tg_id'];
				$html['dir']=$rows['tg_dir'];
				$html=_html($html);
		}else{
			_alert_back('不存在此相册addimg');
		}
	}else{
		_alert_back('非法操作');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<?php 
	require ROOT_PATH.'/include/title.inc.php';
?>
<script type="text/javascript" src="js/photo_add_img.js"></script>
</head>  
<body>
<?php require ROOT_PATH.'include/header.inc.php'; ?>
<div id="photo">
		<h2>上传图片</h2>
		<form method="post" name="up" action="?action=addimg">
		<input type="hidden" name="sid" value="<?php echo $html['id']?>"/>
		<dl>
				<dd>图片名称：<input type="text" name="name" class="name" /></dd>
				<dd>图片地址：<input type="text" name="url" id="url" readonly="readonly" class="text"/><a href="####" title="<?php echo $html['dir'];?>" id="up">上传</a></dd>
				<dd>图片介绍：<textarea name="content"></textarea></dd>
				<dd><input type="submit" class="submit" value="添加图片" /></dd>
		</dl>
		</form>
</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>
</html>
