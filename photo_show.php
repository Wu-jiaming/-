<?php
session_start();
define('IN_TG',true);
define('SCRIPT','photo_show');
require dirname(__FILE__).'/include/common.inc.php';
//删除相片

if ($_GET['action'] == 'delete' && isset($_GET['id'])){
		if (!!$rows = _fetch_array("SELECT tg_uniqid 
											FROM tg_user
									WHERE tg_username='{$_COOKIE['username']}'
								LIMIT 1"
									)){
		_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
		//取得这张图片的发布者
		if (!! $rows = _fetch_array("SELECT tg_id,
																	  tg_username,
																	  tg_url,
																	  tg_sid
																FROM tg_photo
																	WHERE tg_id ='{$_GET['id']}'
																		LIMIT 1
																"
				)){
			$html = array();
			$html['id'] = $rows['tg_id'];
			$html['username'] = $rows['tg_username'];
			$html['url'] = $rows['tg_url'];
			$html['sid'] = $rows['tg_sid'];
			$html = _html($html);
			//判断删除图片的身份是否合法
			
					if ($html['uername'] == $_COOKIE['username'] || isset($_SESSION['admin'])){
						//首先删除图片的数据库信息
						_query("DELETE FROM tg_photo
											WHERE tg_id='{$html['id']}'");
						if (_affect_rows() == 1){
							//删除图片的物理地址
							if (file_exists($html['url'])){
									unlink($html['url']);
									}else{
										_alert_back('磁盘里没有此图');
									}
									_close();
									_location('图片删除成功', 'photo_show.php?id='.$html['sid']);
						}else {
							_close();
							_alert_back('删除失败');
						}
			}else{
				_alert_back('非法操作');
			}
			
	}else{
		_alert_back('不存在此图片');
	}
}else{
	_alert_back('非法登陆');
		}
}

//一开始getid是相册的id
if (isset($_GET['id'])){
	if (!!$rows=_fetch_array("SELECT tg_id,
																tg_type,
																tg_name
														FROM tg_dir
												WHERE tg_id='{$_GET['id']}'
										LIMIT 1")){
		$dirhtml=array();
		$dirhtml['type'] = $rows['tg_type'];
		$dirhtml['id'] =$rows['tg_id'];
		$dirhtml['name'] =$rows['tg_name'];
		$dirhtml=_html($dirhtml);
		
		//对加密相册的验证信息
		if ($_POST['password']){
			if (!! $rows=_fetch_array("SELECT tg_id from tg_dir
																where tg_password='"._mysql_string(sha1($_POST['password']))."'
																			LIMIT 1											
					")	){
				//生成cookies
				setcookie('photo'.$dirhtml['id'],$dirhtml['name']);
				//重定向
				_location(null, 'photo_show.php?id='.$dirhtml['id']);
			}else{
				_alert_back('相册密码不正确');
			}
		}
	}else{
		_alert_back('不存在此相册');
	}
				
}else {
	_alert_back('非法操作·1');
}

$percent = 0.5;
global $pagesize,$pagenum,$system,$pageid;
$id = 'id='.$dirhtml['id'].'&';
_page("SELECT tg_id
				FROM tg_photo 
						where tg_sid='{$dirhtml['id']}'", $system['photo']);
$result = _query("SELECT tg_id,
									   	   tg_username,
						   					tg_name,
						  					 tg_url,
											tg_readcount,
											
											tg_commentcount
					FROM tg_photo 
									WHERE tg_sid='{$dirhtml['id']}'
										order by	tg_time desc
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
<script type="text/javascript" src="js/photo_add.js"></script>
</head>  
<body>
<?php require ROOT_PATH.'include/header.inc.php'; ?>
<div id="photo">
	<h2><?php echo $dirhtml['name']?></h2>
		<?php 
		if (empty($dirhtml['type']) ||$_COOKIE['photo'.$dirhtml['id']] == $dirhtml['name'] ||$_SESSION['admin']){
				$html =array();
				while (!! $rows = _fetch_array_list($result)){
					$html['id'] = $rows['tg_id'];
					$html['username'] = $rows['tg_username'];
					$html['url'] = $rows['tg_url'];
					$html['name'] = $rows['tg_name'];
					$html['readcount'] = $rows['tg_readcount'];
					$html['commentcount'] = $rows['tg_commentcount'];
					$html=_html($html);
				
		?>
		<dl>
		
			<dt><a href="photo_detail.php?id=<?php echo $html['id']?>"><img src="thumb.php?filename=<?php echo $html['url']?>&percent=<?php echo $percent;?>"  /></a></dt>
			<dd><a href="photo_detail.php?id=<?php echo $html['id']?>"><?php echo $html['name'];?></a></dd>
			<dd>阅(<strong><?php echo $html['readcount']?></strong>) 评(<strong><?php echo $html['commentcount']?></strong>) 上传者：<?php echo $html['username'];?></dd>
			<?php if ($html['usename'] == $_COOKIE['username'] || isset($_SESSION['admin'])){?>
			<dd>[<a href="photo_show.php?action=delete&id=<?php echo $html['id']?>">删除</a>]</dd>
			<?php }?>
		</dl>
	
		<?php }
				_free_result($result);
				_type(1);
		?>
		<p><a href="photo_add_img.php?id=<?php echo $dirhtml['id']?>">上传图片</a></p>
	
	<?php }else {
		echo '<form method="post" action="photo_show.php?id='.$dirhtml['id'].'" >';
		echo '<p>请输入密码：<input type="password" name="password"/>
													<input type="submit" value="确认"/>';
		echo '</form>';
	}
	?>
</div>
<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   