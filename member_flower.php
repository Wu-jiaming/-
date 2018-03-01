<?php
define('IN_TG',true);
define('SCRIPT','member_flower');
require dirname(__FILE__).'/include/common.inc.php';
if (!isset($_COOKIE['username'])){
	_alert_back('请先登录');
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
			_query("DELETE FROM  tg_flower
						WHERE tg_id IN ({$clean['ids']})
					");
			if (_affect_rows()){
				_close();
				_location('信息批量删除成功', 'member_flower.php');
			}else{
				_close();
				_location('信息批量删除失败');
			}
	}else {
		_alert_back('检测没有找到tg_uniqid非法登录');
	}
}
global  $pagesize,$pagenum;
_page("SELECT tg_id FROM tg_flower WHERE tg_touser='{$_COOKIE['username']}'",15);
$result=_query("SELECT tg_id,tg_flower,tg_fromuser,tg_time,tg_content
									FROM tg_flower
										WHERE tg_touser='{$_COOKIE['username']}'
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
					<h2>花朵管理中心</h2>
					<form method="post" action="?action=delete">
					<table cellspacing="1">
						<tr><th>发信人</th><th>花朵数</th><th>送花目的</th><th>时间</th><th>操作</th></tr>
						
						<?php 
								$html=array();
								while (!!$rows=_fetch_array_list($result)){
									
									$html['tg_id']=$rows['tg_id'];
									
									$html['tg_fromuser']=$rows['tg_fromuser'];
									$html['tg_content']=$rows['tg_content'];
									$html['tg_touser']=$_COOKIE['username'];
									$html['tg_time']=$rows['tg_time'];
									$html['tg_flower']=$rows['tg_flower'];
									$html['tg_count_flower'] +=$html['tg_flower'];
									$html=_html($html);
							
								
								
								?>
						<tr><td><?php echo $html['tg_fromuser']?></td><td><?php  echo $html['tg_flower'];?></td><td><a href="member_flower_detail.php?id=<?php echo $html['tg_id']?>" title="<?php echo $html['tg_content']?>"> <?php echo $html['tg_content']?></a></td><td><?php echo $html['tg_time']?></td><td><input name="ids[]" value="<?php  echo $html['tg_id']?>" type="checkbox" /></td></tr>		
						
						<?php }	
							_free_result($result);
						?>
						<tr><td colspan="5">花朵总数：<?php echo $html['tg_count_flower']?></td></tr>
						<tr><td colspan="5"><label for="all">全选 <input type="checkbox" name="chkall" id="all" /></label> <input type="submit" value="批删除" /></td></tr>
					</table>
					</form>
						<?php _type(1);?>
				</div>

</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   	