<?php 
session_start();
define('IN_TG',true);
define('SCRIPT','member_modify');
require dirname(__FILE__).'/include/common.inc.php';
//修改资料
if($_GET['action']=='member_modify'){
	_check_yzm($_POST['yzm'], $_SESSION['code']);
	if (!!$_rows = _fetch_array("SELECT 
																tg_uniqid 
													FROM 
																tg_user 
												 WHERE 
																tg_username='{$_COOKIE['username']}' 
													 LIMIT 
																1"
		)) {
		//为了防止cookies伪造，还要比对一下唯一标识符uniqid()
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		include ROOT_PATH.'/include/check.func.php';
		$clean=array();
		
		$clean['password']=_check_modify_password($_POST['password'],6,40);
		$clean['sex']=_check_sex($_POST['sex']);
		$clean['face']=_check_face($_POST['face']);
		$clean['email']=_check_email($_POST['email'],40);
		$clean['qq']=_check_qq($_POST['qq']);
		$clean['url']=_check_url($_POST['url'],40);
		$clean['switch']=$_POST['switch'];
		$clean['autograph']=$_POST['autograph'];
		if(empty($clean['password'])){
		
			_query("UPDATE tg_user SET
					tg_sex='{$clean['sex']}',
					tg_face='{$clean['face']}',
					tg_email='{$clean['email']}',
					tg_qq='{$clean['qq']}',
					tg_url='{$clean['url']}',
					tg_switch='{$clean['switch']}',
					tg_autograph='{$clean['autograph']}'
					WHERE tg_username ='{$_COOKIE['username']}'
					");
		}else {
			_query("UPDATE tg_user SET
					tg_password='{$clean['password']}',
					tg_sex='{$clean['sex']}',
					tg_face='{$clean['face']}',
					tg_email='{$clean['email']}',
					tg_qq='{$clean['qq']}',
					tg_url='{$clean['url']}'
					tg_switch='{$clean['switch']}',
					tg_autograph='{$clean['autograph']}'
					WHERE tg_username ='{$_COOKIE['username']}'
					");
				
		}
	}
	
	
	if(_affect_rows() == 1){
			_close();
			//_session_destroy();
			_location('恭喜你，修改成功', 'manage_member.php');
	}else{
		
		_close();
		//_session_destroy();
 		_location('很遗憾，没有任何数据被修改', 'member_modify.php');
	}
}
//检查是否正常登陆
if($_GET['id']){
	$rows=_fetch_array("SELECT tg_username,
														tg_sex,tg_face,
														tg_email,tg_url,
														tg_qq,
														tg_switch,
														tg_autograph
										FROM tg_user WHERE 
											tg_id='{$_GET['id']}'");
	if($rows){
		$html=array();
		$html['username']=$rows['tg_username'];
		$html['sex']=$rows['tg_sex'];
		$html['face']=$rows['tg_face'];
		$html['email']=$rows['tg_email'];
		$html['url']=$rows['tg_url'];
		$html['qq']=$rows['tg_qq'];
		$html['switch']=$rows['tg_switch'];
		$html['autograph']=$rows['tg_autograph'];

		$html = _html($html);
		//性别选择
		if($html['sex']=='男'){
			$html['sex_html']='<input type="radio" name="sex" value="男" checked="checked" /> 男 <input type="radio" name="sex" value="女" /> 女';
			
		}else{
			$html['sex_html']='<input type="radio" name="sex" value="男" /> 男 <input type="radio" name="sex" value="女" checked="checked" />  女 ';
		}
		
		//签名开关
		if ($html['switch'] == 1){
			$html['switch_html']='<input type="radio" checked="checked" name="switch" value="1"/>启用<input type="radio" name="switch" value="0"/>禁用';
			
		}elseif($html['switch'] == 0){
			$html['switch_html']='<input type="radio" name="switch" value="1"/>启用<input type="radio" name="switch" value="0" checked="checked	"/>禁用';
		}
		//头像选择
// 		$html['face_html']='<select name="face">';
// 		foreach (range(1, 9 ) as  $num){
// 			$html['face_html'].='<option value="face/m0'.$num.'.gif">face/m0'.$num.'.gif</option>';
// 		}
// 		foreach (range(10, 64) as $num){
// 			$html['face_html'].='<option value ="face/m'.$num.'.gif">face/m0'.$num.'.gif</option>';
// 		}
// 		$html['face_html'] .='</select>';
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
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/member_modify.js"></script>
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
		<form method="post" name="member_modify" action ="?action=member_modify">
			<dl>
				<dd>用 户 名：<?php echo $html['username']?></dd>
				<dd>密　　码：<input type ="password" class="text" name="password"/>(留空则密码不修改) </dd>
				<dd>性　　别：<?php  echo $html['sex_html']?></dd>
				<dd class="face"><input type="text" name="face" value="<?php  echo $html['face']?>" /><img src="<?php  echo $html['face']?>" alt="头像选择" id="faceimg" /></dd>
				<dd>电子邮件：<input type="text" class="text" name="email" value="<?php echo $html['email']?>"/></dd>
				<dd>主　　页：<input type="text" class="text" name="url" value="<?php  echo $html['url']?>"/></dd>
				<dd>Ｑ　　Ｑ：<input type="text" class="text" name="qq" value="<?php  echo $html['qq']?>"/></dd>
				<dd>个性签名：<?php echo $html['switch_html']?>(可以用ubb样式)
												<p><textarea name="autograph"><?php echo $html['autograph']?></textarea></p>
				</dd>
				<dd>验 证 	码：<input type="text" name="yzm" class="yzm" /><img src="code.php" id="code"/></dd>
				<dd><input type ="submit" class="submit" value="完成修改" /></dd>
			</dl>
			</form>
	</div>
</div>

<?php 
		require ROOT_PATH.'/include/footer.inc.php';
?>
</body>  
</html> 	