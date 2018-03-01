<?php 
session_start();
define('IN_TG',true);
define('SCRIPT','member');
require dirname(__FILE__).'/include/common.inc.php';
//检查是否正常登陆
if(isset($_COOKIE['username'])){
	$rows=_fetch_array("SELECT tg_login_count,tg_last_ip,tg_last_time,tg_username,tg_score,tg_sex,tg_face,tg_email,tg_url,tg_qq,tg_level,tg_reg_time FROM tg_user WHERE tg_username='{$_COOKIE['username']}'");
	if($rows){
		$html=array();
		$html['username']=$rows['tg_username'];
		$html['sex']=$rows['tg_sex'];
		$html['face']=$rows['tg_face'];
		$html['email']=$rows['tg_email'];
		$html['url']=$rows['tg_url'];
		$html['qq']=$rows['tg_qq'];
		$html['score']=$rows['tg_score'];
		$html['reg_time']=$rows['tg_reg_time'];
		$html['last_time']=$rows['tg_last_time'];
		$html['last_ip']=$rows['tg_last_ip'];
		$html['login_count']=$rows['tg_login_count'];
		switch($rows['tg_level']){
			case 0:
				$html['level']='普通会员';
				break;
			case 1:
				$html['level']="管理员";
				break;
			default:
				$html['level']='level不存在';
					
		}
		$html = _html($html);
	}else{
		_alert_back('用户不存在');
	}
	
}
else{
	_alert_back('非法登陆');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<?php
require ROOT_PATH.'/include/title.inc.php';
?>  
</head>  
<body>
<?php 
require ROOT_PATH.'include/header.inc.php';
?>
<div id="member">
<?php 
	require ROOT_PATH.'/include/member.inc.php';
?>
	<div id="member_main">
		<h2>会员管理中心</h2>
			<dl>
				<dd>用 户 名：<?php echo $html['username']?></dd>
				<dd>性　　别：<?php  echo $html['sex']?></dd>
				<dd>头　　像：<?php  echo $html['face']?></dd>
				<dd>电子邮件：<?php  echo $html['email']?></dd>
				<dd>主　　页：<?php  echo $html['url']?></dd>
				<dd>Ｑ　　Ｑ：<?php  echo $html['qq']?></dd>
				<dd>积　　分：<?php  echo $html['score']?></dd>
				<dd>注册时间：<?php  echo $html['reg_time']?></dd>
				<dd>登录次数：<?php  echo $html['login_count']?></dd>
				<dd>最后一次登录的IP：<?php  echo $html['last_ip']?></dd>
				<dd>最后一次登录时间：<?php  echo $html['last_time']?></dd>
				
				<dd>身　　份：<?php  echo $html['level']?></dd>
			</dl>
	</div>
</div>

<?php 
		require ROOT_PATH.'/include/footer.inc.php';
?>
</body>  
</html> 	