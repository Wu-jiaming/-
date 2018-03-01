<?php
session_start();
define('IN_TG',true);
define('SCRIPT','photo_modify');
require dirname(__FILE__).'/include/common.inc.php';

_manage_login();
if ($_GET['action'] == 'modify'){
	//_check_yzm($_POST['yzm'], $_SESSION['code']);
	if (!!$rows=_fetch_array("SELECT tg_uniqid
																			FROM tg_user
																	WHERE tg_username ='{$_COOKIE['username']}'
															LIMIT 1")	){
					_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
					include dirname(__FILE__).'/include/check.func.php';
					$clean = array();
					$clean['id'] =$_POST['id'];
					$clean['name'] =_check_name($_POST['name'], 2, 20) ;
					$clean['type'] =$_POST['type'];
					$clean['password']= _check_password_one($_POST['password'],6,40);
					$clean['face'] =$_POST['face'];
					$clean['content'] =_check_post_content($_POST['content'],0,400);
					$clean= _mysql_string($clean);
					
					//修改目录
					if (empty($clean['type'])){
						_query("UPDATE tg_dir
													SET
															tg_name='{$clean['name']}',
															tg_type='{$clean['type']}',
															tg_password=null,
															tg_face='{$clean['face']}',
															tg_content='{$clean['content']}'
											WHERE 
														tg_id='{$clean['id']}'
														LIMIT 1");
					}else{
						_query("UPDATE tg_dir
								SET
								tg_name='{$clean['name']}',
								tg_type='{$clean['type']}',
								tg_password='{$clean['password']}',
								tg_face='{$clean['face']}',
								tg_content='{$clean['content']}'
								WHERE
								tg_id='{$clean['id']}'
								LIMIT 1");
					}
	if(_affect_rows() ==1){
			_close();
			_location('目录修改成功', 'photo.php');
			
		}else{
			_close();
			_alert_back('目录修改失败');
		}
	}else{
		_alert_back('非法登陆！');
	}
}
//读出数据
if (isset($_GET['id'])){
	if (!! $rows = _fetch_array("SELECT tg_id,
																  tg_name,
																  tg_type,
																  tg_face,
																  tg_content
			 		 	 	 	 	 	 	 FROM tg_dir
								WHERE tg_id = '{$_GET['id']}'
						LIMIT 1
																   ")){
		$html=array();
		$html['id'] = $rows['tg_id'];
		$html['name'] = $rows['tg_name'];
		$html['type'] = $rows['tg_type'];
		$html['face'] = $rows['tg_face'];
		$html['content'] = $rows['tg_content'];
		$html= _html($html);
		
	}else{
		_alert_back('不存在此相册');
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
<script type="text/javascript" src="js/photo_add.js"></script>
</head>  
<body>
<?php require ROOT_PATH.'include/header.inc.php'; ?>
<div id="photo">
	<h2>修改相册目录</h2>
		<form method="post" action="?action=modify">
			<dl>
					<dd>相册名称：<input type="text" name="name" value="<?php echo $html['name'];?>" class="text"/></dd>
					<dd>相册类型：<input type="radio" name="type" value="0" <?php if ($html['type'] ==0) echo 'checked="checked"';?>/>公开
												  <input type="radio" name="type" value="1" <?php if ($html['type'] ==1) echo 'checked="checked"';?>/>私密 </dd>
					<dd id="pass" <?php if ($html['type'] == 1) echo '<style="display:block"';?>>相册密码：<input type="password" name="password"  class="text"/> </dd>
					<dd>相册封面：<input type="text" name="face" value="<?php echo $html['face'];?>" class="text"/></dd> 
					<dd>相册介绍：<textarea name="content"> <?php echo $html['content'];?></textarea></dd>
					
					
					<dd><input type="submit" class="submit" value="修改目录"/> </dd>
			</dl>
			<input type="hidden" value="<?php echo $html['id'];?>" name="id"/>
		</form>


</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   