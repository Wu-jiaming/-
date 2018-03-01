<?php
session_start();
define('IN_TG',true);
define('SCRIPT','friend');
require dirname(__FILE__).'/include/common.inc.php';
 //判断是否登录咯
 if(!isset($_COOKIE['username'])){
 	_alert_back('小弟必须先登陆');
 }
 //写短信
 if ($_GET['action'] == 'add'){

 	//验证码
 	_check_yzm($_POST['yzm'],$_SESSION['code']);
 	
 	if (!!$rows=_fetch_array("SELECT tg_uniqid
 									FROM tg_user 
 								WHERE 
 						tg_username='{$_COOKIE['username']}' 
 					LIMIT 1")){
 		_uniqid($_COOKIE['uniqid'], $rows['tg_uniqid']);
 	}
 	
 	include ROOT_PATH.'/include/check.func.php';
 	
 	$clean=array();
 	$clean['touser']=$_POST['touser'];
 	$clean['fromuser']=$_COOKIE['username'];
 	$clean['content']=_check_content($_POST['content']);
 	$clean=_mysql_string($clean);
 	
 	//不能添加自己为好友
 	if ($clean['username'] == $clean['touser'])
 	{
 		_alert_close('不能添加自己为好友');
 	}
 	
 	//检验是否已经添加为好友
 	if (!! $rows = _fetch_array("SELECT tg_id 
 											FROM tg_friend
 									WHERE tg_touser='{$clean['touser']}' AND tg_fromuser='{$clean['fromuser']}'
 								LIMIT 1
 									")){
 		_alert_back('你们已经是好友了！或者是未验证的好友，无需添加');
 	}else {
 		_query("INSERT INTO tg_friend
 					(tg_touser,tg_fromuser,tg_content,tg_time)
 				VALUES('{$clean['touser']}','{$clean['fromuser']}','{$clean['content']}',now())
 				");
 		if (_affect_rows()==1){
 			_close();
 			//_session_destroy();
 			_alert_close('好友添加成功，等待对方的验证');
 			
 		}else{
 			_close();
 			//_session_destroy();
 			_alert_back('好友添加失败');
 		}
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
	<h2>添加好友</h2>
	<form method="post"  action="?action=add">
	<input type="hidden" name="touser" value="<?php echo $html['touser']	?>"/>
		<dl>
			<dd><input type="text" readonly="readonly" value="TO:<?php echo $html['touser']	?>" class="text"/></dd>
			<dd><textarea name="content"  ></textarea></dd>
			<dd>验 证 	码：<input type="text" name="yzm" class="yzm" /><img src="code.php" id="code"/><input type="submit" class="submit" value="发送信息" /></dd>

		</dl>
	</form>

</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   	