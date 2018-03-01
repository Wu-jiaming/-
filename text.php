<?php
session_start();
define('IN_TG',true);
define('SCRIPT','login');
require dirname(__FILE__).'/include/common.inc.php';
if($_GET['action']=='login'){
	//include ROOT_PATH.'/include/login.func.php';
	$a = $_POST['password'] ;
	if (!!  ($rows = _fetch_array("SELECT tg_username,tg_uniqid,tg_level
			FROM tg_user
			WHERE tg_username='{$_POST['username']}'
			and tg_password='$a'
			and tg_active is null LIMIT 1"))) 
			
		{
			setcookie('username',$rows['tg_username'],time()+2592000);
			setcookie('uniqid',$rows['tg_uniqid'],time()+2592000);
			_close();
			_location(null,'member.php');
		}
		else{
			_close();
			_alert_back($a);
			_location('用户名密码不正确或者该账户未被激活！','text.php');
			
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

</head>  
<body>
<?php 
require ROOT_PATH.'include/header.inc.php';
?>
	<div id="login">
		<h2>小弟登陆处</h2>
			<form method="post" name="login" action="text.php?action=login" >
					<dl>
						<dt> 	</dt>
						
						<dd>用 户 名：<input type="text" name="username" class="text" /></dd>
						<dd>密　　码：<input type="password" name="password" class="text" /></dd>

						<dd><input type="submit" name="login" value="登录" class="submit"/> <input type="submit" name="register" value="注册" class="submit"/></dd>
					</dl>
			</form>
	</div>

<?php 
		require ROOT_PATH.'/include/footer.inc.php';
	?>
</body>  
</html>