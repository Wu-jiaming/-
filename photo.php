<?php
session_start();
define('IN_TG',true);
define('SCRIPT','photo');
require dirname(__FILE__).'/include/common.inc.php';
_login();
//删除目录
if ($_GET['action'] == 'delete' && isset($_GET['id'])){
			if (!!$rows = _fetch_array("SELECT tg_uniqid 
											FROM tg_user
									WHERE tg_username='{$_COOKIE['username']}'
								LIMIT 1"
									)){
		_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
		//删除目录
		if (!! $rows=_fetch_array("SELECT tg_dir 
														FROM tg_dir
															WHERE tg_id='{$_GET['id']}'
																LIMIT 1")){
			$html = array();
			$html['url'] = $rows['tg_dir'];
			$html= _html($html);
			//删除磁盘的目录
			if (file_exists($html['url'])){
							if (_remove_Dir($html['url'])){
								//1 删除目录里的数据库图片
								_query("DELETE FROM tg_photo 
													WHERE tg_sid='{$_GET['id']}'");
								
								//2 删除这个目录的数据库
								_query("DELETE FROM tg_dir
													WHERE tg_id='{$_GET['id']}'")	;
							
								_close();
								_location('目录删除成功','photo.php');
							}else{
								_close();
								_alert_back('目录删除失败');
							}
				}else{
			_alert_back('不存在此目录物理');
		}
		}else{
			_alert_back('不存在此目录id');
		}
			}else {
				_alert_back('非法登录 1');
			}
}
global $pagenum,$pagesize,$system;
_page("SELECT tg_id FROM tg_user",$system['blog']);
$result=_query("SELECT tg_id,
										  tg_name,
										  tg_type,
										 tg_face
			
							FROM tg_dir  
						ORDER BY tg_time DESC 
				LIMIT $pagenum,$pagesize");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<?php 
	require ROOT_PATH.'/include/title.inc.php';
?>
<script type="text/javascript" src="js/blog.js"></script>
</head>  
<body>
<?php require ROOT_PATH.'include/header.inc.php'; ?>
<div id="photo">
	<h2>相册列表</h2>
		 <?php 
			 $html=array();
			 while(!! $rows = _fetch_array_list($result)){
					$html['id'] = $rows['tg_id'];
					$html['name']= $rows['tg_name'];
					$html['type']= $rows['tg_type'];
					$html['face']=$rows['tg_face'];
					$html=_html($html);
					if (empty($html['type'])){
						$html['type_html'] = '(公开)';
					}else{
						$html['type_html'] = '(私密)';
					}
			 		
					if (empty($html['face'])){
						$html['face_html']='';
					}else{
						$html['face_html']='<img src="'.$html['face'].'" alt="'.$html['tg_name'].'"/>';
					}
					//统计相册的照片数量
					$html['photo'] =  _fetch_array("SELECT count(*) 
																			as count			
																				FROM tg_photo
																					WHERE tg_sid='{$html['id']}'")	;
			 
		 ?>
		 <dl>
		 		<dt><a href="photo_show.php?id=<?php echo $html['id']?>"><?php echo $html['face_html'];?></a></dt>
		 		<dd><a href="photo_show.php?id=<?php echo $html['id']?>">
		 				<?php echo $html['name'];?>
		 				<?php echo '['.$html['photo']['count'].']'?>
		 				<?php echo $html['type_html'];?>
		 				</a>
		 				</dd>
		 				<?php if (isset($_SESSION['admin']) && isset($_COOKIE['username'])){?>
		 				<dd>[<a href="photo_modify.php?id=<?php echo $html['id'];?>">修改</a>]	 
		 						[<a href="photo.php?action=delete&id=<?php echo $html['id']?>">删除</a>]
		 				</dd>
		 				<?php }?>
		 </dl>
		 <?php }?>
		
		<p><a href="photo_add.php">添加目录</a></p>
</div>



<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   