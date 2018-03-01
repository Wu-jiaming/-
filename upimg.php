<?php
define('IN_TG',true);
define('SCRIPT','face');
require dirname(__FILE__).'/include/common.inc.php';
if (!$_COOKIE['username']){
	_alert_back('非法登陆');
}
//执行上传图片功能
if ($_GET['action'] == 'up'){
			if (!!$rows=_fetch_array("SELECT tg_uniqid
																			FROM tg_user
																	WHERE tg_username ='{$_COOKIE['username']}'
															LIMIT 1")	){
					_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
					//设置上传图片类型
					$file = array('image/jpeg','image/pjpeg','image/png','image/x-png','image/gif');
				
					//判断类型是否是数组里的一种
					if (is_array($file)){
						if (!in_array($_FILES['userfile']['type'], $file)){
							_alert_back('上传图片必须是jpg/png/gif');
						}
					}
					//判断文件错误类型
					if ($_FILES['userfile']['error']> 0){
						switch($_FILES['userfile']['error']){
							case 1:_alert_back('上传文件超过约定值1');
										break;
							case 2:_alert_back('上传文件超过约定值2');
										break;
							case 3:_alert_back('部分文件被上传');
							 	 	 	break;
							case 4:_alert_back('没有任何文件被上传');
							 		  break;
						}
						exit;
					}
					
					//判断配置的大小
					if ($_FILES['userfile']['size'] >10000000){
						_alert_back('上传文件不得超过10M');
					}
					//获取文件的文件扩展名
	
						//获取文件的扩展名 1.jpg
						$n = explode('.',$_FILES['userfile']['name']);
						$name = $_POST['dir'].'/'.time().'.'.$n[1];
						
						
					//移动文件
					if (is_uploaded_file($_FILES['userfile']['tmp_name'])){
						if (!@move_uploaded_file($_FILES['userfile']['tmp_name'], $name)){
							_alert_back('移动失败??');
						}else {
							//_alert_back('移动成功');
							echo "<script>alert('上传成功！');window.opener.document.getElementById('url').value='$name';window.close();</script>";
							exit();
						}
					}else{
						_alert_back('上传的临时文件不存在');
					}
			}else{
				_alert_back('非法登陆');
			}
}
//接受dir
if (!isset($_GET['dir'])){
	_alert_back('非法操作get不到dir');
}
// if (!isset($_GET['dir'])) {
// 	_alert_back('非法操作！');
// }
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
<div id="upimg" style="padding:20px;">
	<h2 style="text-align:center;">选择图片</h2>
		<form enctype="multipart/form-data" action="upimg.php?action=up" method="post">
				<input type="hidden" name="MAX_FILE_SIZE" value="10000000"/>
				<input type="hidden" name="dir" value="<?php echo $_GET['dir'];?>"/>
				选择图片：<input type="file" name="userfile" style="border:solid;"/>
				<input type="submit" value="上传"/>
		</form>
</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html> 