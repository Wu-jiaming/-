<?php 
session_start();
define('IN_TG',true);
define('SCRIPT','manage_set');
require dirname(__FILE__).'/include/common.inc.php';
//检查是否正常登陆
_manage_login();

//修改系统表
if ($_GET['action'] == 'set'){
	if(!!$rows = _fetch_array("SELECT tg_uniqid
										FROM tg_user
								WHERE tg_username='{$_COOKIE['username']}'
											LIMIT 1
	")){
		_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
		$clean= array();
		$clean['webname'] = $_POST['webname'];
		$clean['article'] = $_POST['article'];
		$clean['blog'] = $_POST['blog'];
		$clean['photo'] = $_POST['photo'];
		$clean['skin'] = $_POST['skin'];
		$clean['post'] = $_POST['post'];
		$clean['re'] = $_POST['re'];
		$clean['code'] = $_POST['code'];
		$clean['register'] = $_POST['register'];
		$clean['string'] = $_POST['string'];
		$clean = _mysql_string($clean);
		
		//写入数据库
		_query("UPDATE tg_system 
								SET tg_webname='{$clean['webname']}',
										tg_article='{$clean['article']}',
										tg_blog='{$clean['blog']}',
										tg_skin='{$clean['skin']}',
										tg_photo='{$clean['photo']}',
										tg_post='{$clean['post']}',
										tg_re='{$clean['re']}',
										tg_code='{$clean['code']}',
										tg_register='{$clean['register']}',
										tg_string='{$clean['string']}'
								WHERE tg_id =1
										LIMIT 1");
		
		if (_affect_rows() ==1){
			_close();
			_location('恭喜你，修改成功', 'manage_set.php');
		}else{
			_close();
			_location('很遗憾，没有数据被修改', 'manage_set.php');
		}
	}else{
		_alert_back('异常，登录的用户在数据库中找不到');
	}
}

//取出系统表
if (!!$rows = _fetch_array("select tg_webname,
															tg_article,
															tg_blog,
															tg_photo,
															tg_skin,
															tg_string,
															tg_post,
															tg_re,
															tg_code,
															tg_register
								FROM  tg_system
					WHERE tg_id=1
							LIMIT 1")){
	$html=array();
	$html['webname'] = $rows['tg_webname'];
	$html['article'] = $rows['tg_article'];
	$html['blog'] = $rows['tg_blog'];
	$html['photo'] = $rows['tg_photo'];
	$html['skin'] = $rows['tg_skin'];
	$html['string'] = $rows['tg_string'];
	$html['post'] = $rows['tg_post'];
	$html['re'] = $rows['tg_re'];
	$html['code'] = $rows['tg_code'];
	$html['register'] = $rows['tg_register'];
	$html=_html($html);
	
	//文章
	if ($html['article'] == 10){
		$html['article_html'] = '<select name="article"><option value="10" selected="selected">每页10篇</option><option value="15">每页15篇</option></select>';
	}elseif($html['article'] == 15){
		$html['article_html']='<select name="article"><option value="10">每篇10篇</option><option value="15" selected="selected">每页15篇</option></select>';
	}

   //博文
   if ($html['blog'] == 15){
   		$html['blog_html'] = '<select name="blog"><option value="15" selected="selected">每页15人</option><option value="20">每页20人</option></select>';
   }elseif ($html['blog'] == 20){
   		$html['blog_html'] ='<select name="blog"><option value="20" selected="selected">每页20人</option><option value="15">每页15人</option></select>';
   }
	

    
   //相册
   if ($html['photo'] == 8){
   		$html['photo_html'] = '<select name="photo"><option value="8" selected="selected">每页8人</option><option value="12">每页12人</option></select>';
   }elseif ($html['photo'] == 12){
   		$html['photo_html'] ='<select name="photo"><option value="12" selected="selected">每页12人</option><option value="8">每页8人</option></select>';
   }
   

   //皮肤
   if ($html['skin'] == 1) {
   	$html['skin_html'] = '<select name="skin"><option value="1" selected="selected">一号皮肤</option><option value="2">二号皮肤</option><option value="3">三号皮肤</option></select>';
   } elseif ($html['skin'] == 2) {
   	$html['skin_html'] = '<select name="skin"><option value="1">一号皮肤</option><option value="2" selected="selected">二号皮肤</option><option value="3">三号皮肤</option></select>';
   } elseif ($html['skin'] == 3) {
   	$html['skin_html'] = '<select name="skin"><option value="1">一号皮肤</option><option value="2">二号皮肤</option><option value="3" selected="selected">三号皮肤</option></select>';
   }
   
   //发帖
   if ($html['post'] == 30){
   	$html['post_html'] = '<input type="radio" name="post" value="30" checked="checked"/>30人<input type="radio" name="post" value="60" />60人<input type="radio" name="post" value="180" />180秒'; 
   }elseif ($html['post'] == 60){
   	$html['post_html'] =  '<input type="radio" name="post" value="30" />30人<input type="radio" name="post" value="60" checked="checked"/>60人<input type="radio" name="post" value="180" />180秒'; 
   }elseif ($html['post'] == 180){
   	$html['post_html'] =  '<input type="radio" name="post" value="30" />30人<input type="radio" name="post" value="60" />60人<input type="radio" name="post" value="180" checked="checked"/>180秒'; 
   } 
   
   //回帖
   if ($html['re'] == 15){
   	$html['re_html'] ='<input type="radio" name="re" value="15" checked="checked"/>15秒<input type="radio" name="re" value="30" />30秒<input type="radio" name="re" value="45" />45秒'; 
   }elseif ($html['re'] == 30){
   	$html['re_html'] = '<input type="radio" name="re" value="15" />15秒<input type="radio" name="re" value="30" checked="checked"/>30秒<input type="radio" name="re" value="45" />45秒'; 
   }elseif ($html['re'] == 45){
   	$html['re_html'] = '<input type="radio" name="re" value="15" />15秒<input type="radio" name="re" value="30" />30秒<input type="radio" name="re" value="45" checked="checked"/>45秒'; 
   }
   

   //验证码
   if ($html['code'] == 1){
   	$html['code_html'] = '<input type="radio" name="code" value="1" checked="checked" />启用<input type="radio" name="code" value="0"/>禁用';
   }elseif ($html['code'] == 0){
   	$html['code_html'] ='<input type="radio" name="code" value="1" />启用<input type="radio" name="code"value="0"  checked="checked"/>禁用';
   }
   
   //放开注册
   if ($html['register'] == 1){
   	$html['register_html'] = '<input type="radio" name="register" value="1" checked="checked" />启用<input type="radio" name="register" value="0" />禁用';
   }elseif ($html['register'] == 0){
   	$html['register_html'] = '<input type="radio" name="register" value="1"/>启用<input type="radio" name="register" value="0"  checked="checked" />禁用';
   }
  
   
   
}else{
	_alert_back('系统表读取错误！请联系管理员检查');
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
	require ROOT_PATH.'/include/manage.inc.php';
?>
	<div id="member_main">
		<h2>后台管理中心</h2>
		<form method="post" action="?action=set">
			<dl>
									<dd>·网 站 名 称：<input type="text" name="webname" value="<?php echo $html['webname'];?>"></input></dd>
					<dd>·文章每页列表数：<?php echo $html['article_html'];?></dd>
					<dd>·博客每页列表数：<?php echo $html['blog_html'];?></dd>
					<dd>·相册每页列表数：<?php echo $html['photo_html'];?></dd>
					<dd>·站点  默认  皮肤：<?php echo  $html['skin_html'];?></dd>
					<dd>·非法  字符  过滤：<input type="text" name="string" class="text" value="<?php echo $html['string']?>"/>(*用|线隔开)</dd>
									<dd>·每次  发帖  限制：<?php echo $html['post_html'];?></dd>
									<dd>·每次  回帖  限制：<?php echo $html['re_html'];?></dd>
									<dd>·是否  启用  验证：<?php echo $html['code_html'];?></dd>
									<dd>·是否  开放  注册：<?php echo $html['register_html'];?></dd>
					
					<dd><input type="submit" value="修改系统设置" class="submit" /></dd>
					
			</dl>
			</form>
	</div>
</div>

<?php 
		require ROOT_PATH.'/include/footer.inc.php';
?>
</body>  
</html> 	