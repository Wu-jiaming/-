<?php 
session_start();
define('IN_TG',true);
define('SCRIPT','login');
require dirname(__FILE__).'/include/common.inc.php';
//登录状态无法进行本操作
_login_state();

//开始处理登录状态
if($_GET['action']=='login'){
	//验证吗是否相等
	global $system;
	if (!empty($system['code'])){
		_check_yzm($_POST['yzm'],$_SESSION['code']);
	}
	include ROOT_PATH.'/include/login.func.php';
	$clean = array();
	$clean['username'] = _check_name($_POST['username'],2,20);
	$clean['password'] = _check_password($_POST['password'],6,40);
	$clean['time'] = _check_time($_POST['time']);
// 	print_r($clean);
	//到数据库去验证
	if (!!  ($rows = _fetch_array("SELECT tg_username,tg_uniqid,tg_level 
														FROM tg_user 
																WHERE tg_username='{$clean['username']}' 
																	and tg_password='{$clean['password']}' 
																	and tg_active is null LIMIT 1"))) {
		//修改最后一次登录的ip和时间和次数
		
		_query("UPDATE tg_user SET 
															tg_last_time=NOW(),
															tg_last_ip='{$_SERVER["REMOTE_ADDR"]}',
															tg_login_count=tg_login_count+1
																	WHERE tg_username='{$rows['tg_username']}'
															
				");
		
		//print_r($clean);
	
		//_session_destroy();
		//setcookie('username',$rows[tg_username]);
		//setcookie('uniqid',$rows[uniqid]);
		_setcookies($rows['tg_username'], $rows['tg_uniqid'], $clean['time']);
		if ($rows['tg_level'] == 1){
			$_SESSION['admin']= $rows['tg_username'];
		}
		_close();
		_location(null,'member.php');
	} else {
		_close();
		//_session_destroy();
		_location('用户名密码不正确或者该账户未被激活！','login.php');
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
<script type="text/javascript" src="js/login.js"></script	>

</head>  
<body>
<?php 
require ROOT_PATH.'include/header.inc.php';
?>
	<div id="login">
		<h2>小弟登陆处</h2>
			<form method="post" name="login" action="login.php?action=login" >
					<dl>
						<dt> 	</dt>
						
						<dd>用 户 名：<input type="text" name="username" class="text" /></dd>
						<dd>密　　码：<input type="password" name="password" class="text" /></dd>
						<dd>密码保留：<input type="radio" name="time" value="0" checked="checked"/>不保留 <input type="radio" name="time" value="1"/>1 天 <input type="radio" name="time" value="7"/>一 周 <input type="radio" name="time" value="30"/>一个月 </dd>
						<?php if (!empty($system['code'])){?>
						<dd>验 证 	码：<input type="text" name="yzm" class="yzm" /><img src="code.php" id="code"/></dd>
						<?php }?>
						<dd><input type="submit" name="login" value="登录" class="submit"/> <input type="submit" name="register" value="注册" class="submit"/></dd>
					</dl>
			</form>
	</div>

<?php 
		require ROOT_PATH.'/include/footer.inc.php';
	?>
</body>  
</html> 