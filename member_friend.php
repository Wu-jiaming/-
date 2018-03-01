<?php
session_start();
define('IN_TG',true);
define('SCRIPT','member_friend');
require dirname(__FILE__).'/include/common.inc.php';
if (!isset($_COOKIE['username'])){
	_alert_back('请先登录');
}
//验证好友
if ($_GET['action'] == 'check' && isset($_GET['id'])){
	if (!! $rows =_fetch_array("SELECT tg_uniqid 
										FROM tg_user
								WHERE tg_username='{$_COOKIE['username']}'
								
								")){
		_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
		_query("UPDATE tg_friend SET tg_state=1
					WHERE tg_id='{$_GET['id']}'");
		if (_affect_rows() == 1) {
			_close();
			_location('好友验证成功','member_friend.php');
		} else {
			_close();
			_alert_back('好友验证失败');
		}
		} else {
			_alert_back('非法登录！');
		}
	}

//批删除
if ($_GET['action']=='delete' && isset($_POST['ids'])){
	$clean=array();
	$clean['ids']=_mysql_string(implode(',', $_POST['ids']));
	//检验cookie
	if (!! $rows=_fetch_array("SELECT tg_uniqid
			FROM  tg_user
			WHERE tg_username='{$_COOKIE['username']}'
			LIMIT 1
				
			")){
			_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
			_query("DELETE FROM  tg_friend
					WHERE tg_id IN ({$clean['ids']})
					");
			if (_affect_rows()){
				_close();
				_location('信息批量删除成功', 'member_friend.php');
			}else{
				_close();
				_location('信息批量删除失败');
			}
	}else {
		_alert_back('检测没有找到tg_uniqid非法登录');
	}
}
global  $pagesize,$pagenum;
_page("SELECT tg_id FROM tg_friend WHERE tg_touser='{$_COOKIE['username']}' OR tg_fromuser='{$_COOKIE['username']}'", 15);
$result=_query("SELECT tg_id,tg_state,tg_fromuser,tg_touser,tg_time,tg_content
									FROM tg_friend
										WHERE tg_touser='{$_COOKIE['username']}'
											OR tg_fromuser='{$_COOKIE['username']}'
												ORDER BY tg_time DESC
													LIMIT $pagenum,$pagesize
													
		
		");
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
	require ROOT_PATH.'/include/member.inc.php';
?>
				<div id="member_main">
					<h2>小弟管理中心</h2>
					<form method="post" action="?action=delete">
					<table cellspacing="1">
						<tr><th>小弟</th><th>请求内容</th><th>时间</th><th>状态</th><th>操作</th></tr>
						
						<?php 
						$html=array();
								while (!!$rows=_fetch_array_list($result)){
									
									$html['tg_id']=$rows['tg_id'];
									$html['tg_touser']=$rows['tg_touser'];
									$html['tg_fromuser']=$rows['tg_fromuser'];
									$html['tg_content']=$rows['tg_content'];
									$html['tg_state']=$rows['tg_state'];
									$html['tg_time']=$rows['tg_time'];
									$html=_html($html);
									
							
									if($html['tg_touser'] == $_COOKIE['username']){
										$html['tg_friend'] = $html['tg_fromuser'];
										if(empty($html['tg_state'])){
											$html['tg_state_html']='<a href="?action=check&id='.$html['tg_id'].'" style="color:red">你未同意</a>';
										}else{
											$html['tg_state_html']='<span style="color:green">同意</span>';
										}
										
									}elseif($html['tg_fromuser']==$_COOKIE['username']){
										$html['tg_friend']=$html['tg_touser'];
										if (empty($html['tg_state'])){
											$html['tg_state_html']='<span style="color:blue;">对方未同意</span>';
										}else{
											$html['tg_state_html']='<span style="color:green;">同意</span>';
										}
									}
								
								
								?>
						<tr><td><?php echo $html['tg_friend']?></td><td><a href="member_friend_detail.php?id=<?php echo $html['tg_id']?>" title="<?php echo $html['tg_content']?>"> <?php echo $html['tg_content']?></a></td><td><?php echo $html['tg_time']?></td><td><?php  echo $html['tg_state_html'];?></td><td><input name="ids[]" value="<?php  echo $html['tg_id']?>" type="checkbox" /></td></tr>		
						
						<?php }	
							_free_result($result);
						?>
						
						<tr><td colspan="5"><label for="all">全选 <input type="checkbox" name="chkall" id="all" /></label> <input type="submit" value="批删除" /></td></tr>
					</table>
					</form>
						<?php _type(1);?>
				</div>

</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   	