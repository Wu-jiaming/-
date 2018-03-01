<?php 
session_start();
define('IN_TG',true);
define('SCRIPT','main');
require dirname(__FILE__).'/include/common.inc.php';

//读取xml文件
$html=_html(_get_xml('new.xml'));
//读取帖子列表
global $pagesize,$pagenum,$system;
_page("SELECT tg_id FROM tg_article WHERE tg_reid=0", $system['article']);
$result=_query("SELECT tg_id,tg_type,
								tg_title,tg_readcount,
								tg_commentcount
				FROM tg_article
			WHERE tg_reid=0
		ORDER BY tg_time
	LIMIT $pagenum,$pagesize
		");
//最新的照片，找到时间点最后上传的那张照片，并且是非公开的
$photo=_fetch_array("SELECT tg_id as id,
													tg_name as name,
													tg_url as url
											FROM tg_photo
												WHERE  tg_sid in(
														SELECT tg_id from 
															tg_dir
																where tg_type=0
													)
												order by tg_time desc
													LIMIT 1

		")
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
<?php 
require ROOT_PATH.'include/header.inc.php';
echo ROOT_PATH.'include/header.inc.php';
?>

	<div id="list">
		<h2>帖子列表</h2>
		<a href="post.php" class ="post">发表帖子</a>
		<ul class ="article">
			<?php 
				$htmllist=array();
				while (!!$rows=_fetch_array_list($result)){
					$htmllist['id']=$rows['tg_id'];
					$htmllist['type']=$rows['tg_type'];
					$htmllist['title']=$rows['tg_title'];
					$htmllist['readcount']=$rows['tg_readcount'];
					$htmllist['commentcount']=$rows['tg_commentcount'];
					$htmllist=_html($htmllist);
					echo '<li class="icon'.$htmllist['type'].'"><em>阅读数(<strong>'.$htmllist['readcount'].'</strong>)	评论数(<strong>'.$htmllist['commentcount'].'</strong>)</em><a href="article.php?id='.$htmllist['id'].'">'._title($htmllist['title'],20).'</a></li>';
				}
				_free_result($result);
			?>
			
		</ul>
		<form method="post" action="article.php?action=search" name="search">
			<dl>
				<dd>标题查找：<input type="text" name="search_title" class="text"/><input type="submit"  name="submit" value="search"/></dd>
				<dd>内容查找：<input type="text" name="search_content" class="text"/><input type="submit"  name="submit" value="search"/></dd>
			</dl>
		</form>
		<?php _type(2);?>
	</div>

	<div id="user">
		<h2>新进会员</h2>
			<dl>
	
				<dd class="username"><?php echo $html['username']?>(<?php echo $html['sex']?>)</dd>
				<dt><img src="<?php echo $html['face']?> "alt="<?php echo $html['username']?>"/></dt>
				<dd class="message"><a href ="javascript:;" name ="message" title="<?php echo $html['username']?>">发消息</a></dd>
				<dd class="friend"><a href ="javascript:;" name ="friend" title="<?php echo $html['username']?>">加为好友</a></dd>
				<dd class="guest">写留言</dd>
				<dd class="flower"><a href ="javascript:;" name ="flower" title="<?php echo $html['username']?>">送花</a></dd>
				<dd class="email"> 邮件： <?php echo $html['email']?></dd>
				<dd class="url">网址：<?php echo $html['url']?></dd>
		</dl>
	</div>
	
	<div id="pics">
 		<h2 > 最热图片 -- <?php echo $photo['name']?></h2>
 		<a href ="photo_detail.php?id=<?php echo $photo['id']?>">
 				<img src="thumb.php?filename=<?php 	echo $photo['url']?>&percent=0.5" alt="<?php echo $photo['name']?>" />
 		</a>
	</div>
	
	<?php 
		require ROOT_PATH.'/include/footer.inc.php';
	?>
</body>  
</html> 