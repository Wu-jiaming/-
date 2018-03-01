<?php

session_start();
define('IN_TG',true);
define('SCRIPT','register');
require dirname(__FILE__).'/include/common.inc.php';

//登陆状态无法进行本操作
_login_state();
global $system;
//判断是否提交注册
if($_GET['action']=='register')
{
	if (empty($system['register'])){
		exit('管理员设置不能注册');
	}
	if (!empty($system['code'])){
	//验证吗是否相等
		_check_yzm($_POST['yzm'],$_SESSION['code']);
	}
	include ROOT_PATH.'/include/check.func.php';
	$clean=array();
	//通过一个唯一的标识符可以防止恶意注册
	//第二个作用是登录cookie
	$clean['uniqid']=_check_uniqid($_POST['uniqid'], $_SESSION['uniqid']);
	//active也会死一种标识符  用来注册的用户进行激活处理
	$clean['active']=_sha1_uniqid();
	$clean['username']=_check_name($_POST['username'],2,20);
	$clean['password']=_check_password($_POST['password'],$_POST['notpassword'],6,40);
	$clean['passt']=_check_passt($_POST['passt'],2,40);
	$clean['passd']=_check_passd($_POST['passd'],$_POST['passt'],2,40);
	$clean['sex']=_check_sex($_POST['sex']);
	$clean['face']=_check_face($_POST['face']);
	$clean['email']=_check_email($_POST['email'],40);
	$clean['qq']=_check_qq($_POST['qq']);
	$clean['url']=_check_url($_POST['url'],40);
	//用户名的唯一性
	_is_repeat("SELECT tg_username FROM tg_user WHERE tg_username='{$clean['username']}' LIMIT 1", '对不起，该用户名已经被注册！');
	//新增用户  //在双引号里，直接放变量是可以的，比如$_username,但如果是数组，就必须加上{} ，比如 {$_clean['username']}
	_query(
			"INSERT INTO tg_user (
			tg_uniqid,
			tg_active,
			tg_username,
			tg_password,
			tg_passt,
			tg_passd,
			tg_sex,
			tg_face,
			tg_email,
			tg_qq,
			tg_url,
			tg_reg_time,
			tg_last_time,
			tg_last_ip
			)
			VALUES (
																'{$clean['uniqid']}',
																'{$clean['active']}',
																'{$clean['username']}',
																'{$clean['password']}',
																'{$clean['passt']}',
																'{$clean['passd']}',
																'{$clean['sex']}',
																'{$clean['face']}',
																'{$clean['email']}',
																'{$clean['qq']}',
																'{$clean['url']}',
																	NOW(),
																	NOW(),
																	'{$_SERVER["REMOTE_ADDR"]}'
			)"
	) ;
	if(_affect_rows() == 1){
		//获取刚刚新增的id
			$clean['id']=_insert_id();
			_close();
			_session_destroy();
			//生成XML
			_set_xml('new.xml', $clean);
			_location('恭喜你，注册成功', 'active.php?active='.$clean['active']);
	}else{
		_close();
		_session_destroy();
		_location('很遗憾，注册失败', 'register.php');
	}
}
else {
	$_SESSION['uniqid']=$_uniqid=_sha1_uniqid();
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
<script type="text/javascript" src="js/register.js"></script	>
</head>  
<body>
<?php require ROOT_PATH.'include/header.inc.php'; ?>
<div id="register">
	<h2>小弟注册</h2>
	<?php if (!empty($system['register'])){?>
	<form method="post" name="register" action="register.php?action=register" >
	<input type="hidden" name="uniqid" value="<?php echo $_uniqid?>"/>
		<dl>	
			<dt>请认真填写以下个人信息！打死不泄露（滑稽）</dt>
			<dd>用 户 名：<input type="text" name="username" class="text" />*必填 至少2位</dd>
			<dd>密　　码：<input type="password" name="password" class="text" />*必填 至少6位</dd>
			<dd>确认密码：<input type="password" name="notpassword" class="text" />*必填 至少6位</dd>
			<dd>密码提示：<input type="text" name="passt" class="text" /></dd>
			<dd>密码回答：<input type="text" name="passd" class="text" /></dd>
			<dd>性　　别：<input type="radio" name="sex" value="男" checked="checked" />男<input type="radio" name="sex" value="女" />女</dd>
			<dd>性　　别：<input type="radio" name="f " value="男" checked="checked" />男<input type="radio" name="sex" value="女" />女</dd>	
			<dd class="face"><input type="text" name="face" value="face/m01.gif" /><img src="face/m01.gif" alt="头像选择" id="faceimg" /></dd>
			<dd>电子邮件：<input type="text" name="email" class="text" />*必填</dd>
			<dd>　Q Q 　：<input type="text" name="qq" class="text" /></dd>
			<dd>主页地址：<input type="text" name="url" class="text"  value="http://"/></dd>
			<dd>验 证 	码：<input type="text" name="yzm" class="yzm" /><img src="code.php" id="code"/></dd>
			<dd><input type="submit" class="submit" value="注册" /></dd>
		</dl>
	</form>
	<?php }else{
		echo '<h4 style="text-align:center;padding:20px;">本站被管理员设置不能注册了</h4>';
	}?>	
</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html> 




