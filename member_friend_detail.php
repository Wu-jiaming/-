<?php
session_start();
define('IN_TG',true);
define('SCRIPT','member_message_detail');
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



//删除信息
if($_GET['action'] == 'delete' && isset($_GET['id'])){
	

	//验证是否删除的状态是否合法 
	//验证是否同一个id
			if(!!$rows = _fetch_array("SELECT 
															tg_id
												FROM 
															tg_friend
											 WHERE 
															tg_id='{$_GET['id']}'
												 LIMIT 
															1
					")){
					//验证是否有相同的cookie的uniqid
						if(!!$rows = _fetch_array("SELECT tg_uniqid 
																			FROM tg_user
																				WHERE tg_username='{$_COOKIE['username']}'
																						LIMIT 1
								")){
								
 								_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
								
									//删除信息
								_query("DELETE FROM tg_friend
														WHERE tg_id='{$_GET['id']}' 
															LIMIT 1
										
										");
												
													if (_affect_rows()==1){
														_close();
														//_session_destroy();
														_location('好友请求删除成功', 'member_friend.php');
													}else{
															_close();
															//_session_destroy();
															_location('好友请求删除失败', 'member_friend_detail.php');
														}
		}
			}else{
				_alert_back('tg_id!={$_GET[id]}');
			}
 }
if(isset($_GET['id'])){
	$rows=_fetch_array("SELECT 
															tg_touser,tg_state,tg_id,tg_fromuser,tg_content,tg_time
												FROM 
															tg_friend 
											 WHERE 
															tg_id='{$_GET['id']}' 
												 LIMIT 
															1
			");
	if($rows){
		
					$html=array();
					$html['tg_id']=$rows['tg_id'];
					$html['tg_touser']=$rows['tg_touser'];
					$html['tg_state']=$rows['tg_state'];
					$html['tg_fromuser']=$rows['tg_fromuser'];
					$html['tg_content']=$rows['tg_content'];
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
					
					
					
				}else{
					_alert_back('信息不存在');
				}
}else {
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
<script type="text/javascript" src="js/member_message_detail.js"></script>
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
				<h2>小弟请求详细页</h2>
				<dl>
					<dd>请求小弟：<?php echo $html['tg_fromuser'] ?></dd>
					<dd>请求内容：<?php echo $html['tg_content']?></dd>
					<dd>请求状态：<?php echo $html['tg_state_html']?></dd>
					<dd>请求时间：<?php echo $html['tg_time']?></dd>
					<dd class ="button"><input type="button" value="返回小弟请求列表"  id="return"/><input type="button" name="<?php  echo $html['tg_id']?>" value="删除小弟请求信息" id="delete"/></dd>
				</dl>
				</div>	
</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   	