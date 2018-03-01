<?php
session_start();
define('IN_TG',true);
define('SCRIPT','manage_member');
require dirname(__FILE__).'/include/common.inc.php';
_manage_login();
global $pagesize,$pagenum;
_page("SELECT tg_id FROM tg_user", 15);

$result = _query("SELECT tg_id,
											tg_ban,
											tg_username,
										    tg_email,
											tg_reg_time
								FROM tg_user
							ORDER BY tg_reg_time DESC
						LIMIT $pagenum,$pagesize
		");
//禁止会员发帖
if ($_GET['action'] == 'ban' && isset($_POST['ids']))
{
			$clean=array();
			$clean['ids']=_mysql_string(implode(',', $_POST['ids']));
			if (!!$rows = _fetch_array("SELECT tg_uniqid
				FROM tg_user
				WHERE tg_username='{$_COOKIE['username']}'
				LIMIT 1"
				))
			{
			_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
			_query("UPDATE tg_user SET tg_ban=1 WHERE tg_id IN ({$clean['ids']})");
				if (_affect_rows()){
					_close();
					_location('禁止会员发帖成功', 'manage_member.php');
				}else{
					_close();
					_location('禁止会员发帖失败');
				}
			}
}	
//解禁会员发帖
if ($_GET['action']=='open' && $_GET['id']){
			if (!!$rows = _fetch_array("SELECT tg_uniqid
				FROM tg_user
				WHERE tg_username='{$_COOKIE['username']}'
				LIMIT 1"
		)){
			_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
			_query("UPDATE tg_user SET tg_ban=0 WHERE  tg_id='{$_GET['id']}'");
				if (_affect_rows() ==1){
					_close();
					_location('解禁会员发帖成功', 'manage_member.php');
				}else{
					_close();
					_location('解禁会员发帖失败');
				}
			}
}
//删除会员
if ($_GET['action'] == 'del' &&  $_GET['id']){
		if (!!$rows = _fetch_array("SELECT tg_uniqid
				FROM tg_user
				WHERE tg_username='{$_COOKIE['username']}'
				LIMIT 1"
		)){
			_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
			//删除会员
			_query("DELETE FROM tg_user WHERE tg_id='{$_GET['id']}'");
			if(_affect_rows() ==1){
				_close();
				_location('会员删除成功', 'manage_member.php');
				
			}else{
				_close();
				_alert_back('会员删除失败');
			}
			
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
<script type="text/javascript" src="js/member_message.js"></script>

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
						<form method="post" action="?action=ban">
							<table cellspacing="1">
								<tr><th>ID号</th><th>会员名</th><th>邮件</th><th>注册时间</th><th>禁止发帖</th><th>操作</th></tr>
								<?php 
											$html=array();
											while(!! $rows = _fetch_array_list($result)){
												$html['id'] = $rows['tg_id'];
												$html['username'] = $rows['tg_username'];
												$html['email'] = $rows['tg_email'];
												$html['reg_time'] = $rows['tg_reg_time'];
												$html['ban'] = $rows['tg_ban'];
												$html = _html($html);
											
								?>
								<tr><td><?php echo $html['id'];?></td>
									   <td><?php echo $html['username'];?></td>
									   <td><?php echo $html['email'];?></td>
									   <td><?php echo $html['reg_time'];?></td>
									   <?php if (!!$html['ban']){?>
									  		<td>[<a href="?action=open&id=<?php echo $html['id']?>">解禁</a>]</td>
									 <?php }else{?>
									 		<td><input name="ids[]" value="<?php  echo $html['id']?>" type="checkbox" /></td>
									 <?php }?>		
									   <td>[<a href="?action=del&id=<?php echo  $html['id'];?>" >删</a>][<a href="manage_member_mod.php?id=<?php echo  $html['id'];?>" >改</a>]</td>
							   </tr>
							   <?php }	?>
							   	<tr>
							   	<td colspan="6"><label for="all">全选 <input type="checkbox" name="chkall" id="all" /></label> 
								<input type="submit" value="提交" />
								</td>
								</tr>
							</table>
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