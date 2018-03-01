<?php
session_start();
define('IN_TG',true);
define('SCRIPT','message');
require dirname(__FILE__).'/include/common.inc.php';
 //判断是否登录咯
 if(!isset($_COOKIE['username'])){
 	_alert_back('小弟必须先登陆');
 }
 //写短信
 if ($_GET['action'] == 'write'){

 	//验证码
 	_check_yzm($_POST['yzm'],$_SESSION['code']);
 	if (!!$rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")){
 		_uniqid($_COOKIE['uniqid'], $rows['tg_uniqid']);
 	
 	include ROOT_PATH.'/include/check.func.php';
 	$clean=array();
 	$clean['touser']=$_POST['touser'];
 	$clean['fromuser']=$_COOKIE['username'];
 	$clean['content']=_check_content($_POST['content']);
 	$clean=_mysql_string($clean);
 	
 	//把数据写进数据库
 	_query("INSERT INTO tg_message (
 																tg_touser,
 																tg_fromuser,
 																tg_content,
 																tg_time)
 					values (
 						'{$clean['touser']}',
 								'{$clean['fromuser']}',
 										'{$clean['content']}',
 												now()
 				
 			)
 			");
		 	//如果添加数据成功
		 	if(_affect_rows() ==1){
		 		_close();
		 		//_session_destroy();
		 		_alert_close('信息发送成功');
		 	}else{
		 		_close();
		 		//_session_destroy();
		 		_alert_back('信息发送失败');
		 	}
	 }else{
	 	_alert_close('非法登录');
	 }
 }
 
 
 //获取数据
 if (isset($_GET['id'])){
 	if(!!$rows=_fetch_array("SELECT tg_username FROM tg_user WHERE tg_id='{$_GET['id']}' LIMIT 1")){
 		$html=array();
 		$html['touser']=$rows['tg_username'];
 		$html=_html($html);   
 	}else {
 		_alert_back('不存在此用户');
 	}
 }else {
 	_alert_back('非法操作！');
 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<?php 
	require ROOT_PATH.'/include/title.inc.php';
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/message.js"></script>
</head>  
<body>
<div id="message">
	<h2>发信息</h2>
	<form method="post"  action="?action=write">
	<input type="hidden" name="touser" value="<?php echo $html['touser']	?>"/>
		<dl>
			<dd><input type="text" readonly="readonly" value="TO:<?php echo $html['touser']	?>" class="text"/></dd>
			<dd><textarea name="content" ></textarea></dd>
			<dd>验 证 	码：<input type="text" name="yzm" class="yzm" /><img src="code.php" id="code"/><input type="submit" class="submit" value="发送信息" /></dd>

		</dl>
	</form>

</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   	