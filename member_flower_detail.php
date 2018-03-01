<?php
session_start();
define('IN_TG',true);
define('SCRIPT','member_message_detail');
require dirname(__FILE__).'/include/common.inc.php';
if (!isset($_COOKIE['username'])){
	_alert_back('请先登录');
}
//删除信息
if($_GET['action'] == 'delete' && isset($_GET['id'])){
	

	//验证是否删除的状态是否合法 
	//验证是否同一个id
			if(!!$rows = _fetch_array("SELECT 
															tg_id
												FROM 
															tg_flower 
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
								_query("DELETE FROM tg_flower
														WHERE tg_id='{$_GET['id']}' 
															LIMIT 1
										
										");
												
													if (_affect_rows()==1){
														_close();
														//_session_destroy();
														_location('花朵信息删除成功', 'member_message.php');
													}else{
															_close();
															//_session_destroy();
															_location('花朵信息删除失败', 'member_message_detail.php');
														}
		}
			}else{
				_alert_back('tg_id!={$_GET[id]}');
			}
 }
if(isset($_GET['id'])){
	$rows=_fetch_array("SELECT 
															tg_flower,tg_id,tg_fromuser,tg_content,tg_time
												FROM 
															tg_flower 
											 WHERE 
															tg_id='{$_GET['id']}' 
												 LIMIT 
															1
			");
	if($rows){

					$html=array();
					$html['tg_id']=$rows['tg_id'];
					$html['tg_fromuser']=$rows['tg_fromuser'];
					$html['tg_flower']=$rows['tg_flower'];
					$html['tg_content']=$rows['tg_content'];
					$html['tg_time']=$rows['tg_time'];
					$html=_html($html);
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
				<h2>花朵信息详细页</h2>
				<dl>
					<dd>送 花 人：<?php echo $html['tg_fromuser'] ?></dd>
					<dd>送花朵数：<?php echo $html['tg_flower'] ?></dd>
					<dd>送花目的：<?php echo $html['tg_content']?></dd>
					<dd>送花时间：<?php echo $html['tg_time']?></dd>
					<dd class ="button"><input type="button" value="返回花朵列表"  id="return"/><input type="button" name="<?php  echo $html['tg_id']?>" value="删除花朵信息" id="delete"/></dd>
				</dl>
				</div>	
</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   	