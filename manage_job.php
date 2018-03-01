<?php
session_start();
define('IN_TG',true);
define('SCRIPT','manage_job');
require dirname(__FILE__).'/include/common.inc.php';
_manage_login();
//添加管理员
if ($_GET['action'] == 'add'){
	if (!! $rows = _fetch_array("SELECT tg_uniqid
											FROM tg_user
									WHERE tg_username='{$_COOKIE['username']}'
							LIMIT 1")){
		_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
		$clean = array();
		$clean['username'] = $_POST['manage'];
		$clean = _mysql_string($clean);
		//添加管理员
		_query("UPDATE tg_user
										SET tg_level=1
									WHERE tg_username='{$clean['username']}'");	
	
			if (_affect_rows() == 1){
				_close();
				_location("恭喜你，成功添加管理员", SCRIPT.'.php');
				
			}else{
				_close();
				_alert_back('添加管理员失败，因为不存在此用户');
			}
	}else{
		_alert_back('非法登录1');
	}
}
//辞职
if ($_GET['action'] == 'job'&& isset($_GET['id'])){
	if (!!$rows = _fetch_array("SELECT tg_uniqid
																	FROM tg_user
																WHERE tg_username = '{$_COOKIE['username']}'
														
															LIMIT 1")	){
		_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
		_query("UPDATE tg_user
										SET tg_level=0
									WHERE tg_username='{$_COOKIE['username']}'");
	
		if (_affect_rows() == 1){
			_close();
			_location("恭喜你，成功辞职", SCRIPT.'.php');
			
			}else{
				_close();
				_alert_back('辞职失败，因为不存在此用户');
			}
		
	}else{
		_alert_back('非法登录2');
	}
}
global $pagesize,$pagenum;
_page("SELECT tg_id FROM tg_user", 15);
$result =  _query("SELECT tg_username,
											 tg_id,
								 	  	 	 tg_email,
											 tg_reg_time
									FROM tg_user
							WHERE tg_level =1
						LIMIT $pagenum,$pagesize");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<?php
require ROOT_PATH.'/include/title.inc.php';
?>  
<script type="text/javascript" src="js/member_message_detail.js"></script>
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
				<h2>会员列表中心</h2>
						
							<table cellspacing="1">
								<tr><th>ID号</th><th>会员名</th><th>邮件</th><th>注册时间</th><th>操作</th></tr>
								<?php 
											$html=array();
											while(!! $rows = _fetch_array_list($result)){
												$html['id'] = $rows['tg_id'];
												$html['username'] = $rows['tg_username'];
												$html['email'] = $rows['tg_email'];
												$html['reg_time'] = $rows['tg_reg_time'];
												$html = _html($html);
												if ($_COOKIE['username'] == $html['username']){
													$html['job_html'] = '<a href="manage_job.php?action=job&id='.$html['id'].' ">辞职</a> ';
												}else{
													$html['job_html'] ='无操作权限';
												}
											
								?>
								<tr><td><?php echo $html['id'];?></td>
									   <td><?php echo $html['username'];?></td>
									   <td><?php echo $html['email'];?></td>
									   <td><?php echo $html['reg_time'];?></td>
									   <td><?php echo $html['job_html'];?></td>
							   </tr>
							   <?php }	?>
							   
							</table>
							<form method="post" action="?action=add">
								<input type="text" name = "manage" class="text"/>
								<input type="submit" value="添加管理员"/>
						</form>
							 <?php 
							 			_free_result($result);
							 			_type(1);	
							 ?>
				</div>	
</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   	