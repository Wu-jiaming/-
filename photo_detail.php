<?php
session_start();
define('IN_TG',true);
define('SCRIPT','photo_detail');
require dirname(__FILE__).'/include/common.inc.php';
//评论
if($_GET['action'] == 'rephoto'){
	global $system;
	if (!empty($system['code'])){
		_check_yzm($_POST['yzm'], $_SESSION['code']);
			
	}//clean是从页面接受的回帖数据
	if (!!$rows=_fetch_array("SELECT tg_uniqid ,tg_article_time
			FROM tg_user
			WHERE
			tg_username='{$_COOKIE['username']}'
			LIMIT 1")){
			_uniqid($_COOKIE['uniqid'], $rows['tg_uniqid']);
			//限时发帖
			_timed(time(), $rows['tg_article_time'], $system['re']);

			$clean=array();
			$clean['sid']=$_POST['sid'];
			$clean['title']=$_POST['title'];
			$clean['content']=$_POST['content'];
			$clean['username']=$_COOKIE['username'];
			$clean=_mysql_string($clean);
			//把从页面接受的信息保存在数据库中
			_query("INSERT INTO tg_photo_comment (
					tg_sid,
					tg_username,
					tg_title,
					tg_content,
					tg_time
					)
					VALUES (
					'{$clean['sid']}',
					'{$clean['username']}',
					'{$clean['title']}',
					'{$clean['content']}',
					NOW()
					)");



			if (_affect_rows() ==1){
				//评论数量
				_query("UPDATE tg_photo
						SET tg_commentcount=tg_commentcount+1
						
						where tg_id='{$clean['sid']}'");
				_close();
				//_session_destroy();
				_location('回帖成功', 'photo_detail.php?id='.$clean['sid']);
			}else{
				_close();
				//_session_destroy();
				_alert_back('回帖失败');
			}
	}
}
//一开始getid是图片的id
if (isset($_GET['id'])){
	if (!!$rows=_fetch_array("SELECT tg_id,
															 tg_sid,
															  tg_name,
				 	 	 	   		 	    		 		  tg_url,
															  tg_username,
															  tg_readcount,
															  tg_commentcount,
															 tg_content,
															 tg_time
														FROM tg_photo
												WHERE tg_id='{$_GET['id']}'
										LIMIT 1")){
		//防止加密相册被穿插访问
		//可以先取得这个图片的sid，
		//然后判断这个目录是否加密
		//如果是加密的，在判断是否有对应的cookies存在。并且	对应相应的值
		//管理员不受这个限制
		if (!isset($_SESSION['admin'])){
			if (!! $dirs = _fetch_array("SELECT tg_type,
																	   tg_id,
																		tg_name
														from tg_dir
															where tg_id='{$rows['tg_sid']}'
					")){
				if (!empty($dirs['tg_type']) && $_COOKIE['photo'.$dirs['tg_id']]!= $dirs['tg_name']){
					_alert_back('非法操作');
				}
				
							}else{
									_alert_back('相册目录出错了');
								}
			
		}
		
										//累计阅读量
		_query("UPDATE tg_photo SET tg_readcount=tg_readcount+1 WHERE tg_id='{$_GET['id']}'");
		
		$html=array();
		$html['id'] =$rows['tg_id'];
		$html['sid'] =$rows['tg_sid'];
		$html['name'] =$rows['tg_name'];
		$html['url'] =$rows['tg_url'];
		$html['username'] =$rows['tg_username'];
		$html['readcount'] =$rows['tg_readcount'];
		$html['commentcount'] =$rows['tg_commentcount'];
		$html['content'] =$rows['tg_content'];
		$html['time'] = $rows['tg_time'];
		$html=_html($html);
		
		//创建一个全局变量  做一个带参的分页
		global $id;
		$id='id='.$html['id'].'&';
		
		//读取回帖
		global $pagesize,$pagenum,$page;
		_page("SELECT tg_id FROM tg_photo_comment
				WHERE
				tg_sid = '{$html['id']}'", 2);
		
		$result=_query("SELECT tg_username,
								tg_title,tg_content,tg_time
							FROM tg_photo_comment
						WHERE tg_sid='{$html['id']}'
					ORDER BY tg_time ASC
				LIMIT $pagenum,$pagesize");
		$html['a']=$html['id'];
		//上一页，取比自己大的id之中最小的那个（因为加一张照片 id+1 所以就前一张的图片id大）
		$html['preid'] = _fetch_array("SELECT min(tg_id) as id
																FROM tg_photo
																	WHERE 
																		tg_sid='{$html['sid']}' and
																			tg_id>'{$html['id']}'
																				limit 1
																		")	;
		if (!empty($html['preid']['id'])){
			$html['pre'] = '<a href="photo_detail.php?id='.$html['preid']['id'].'#pre">上一页</a>';
		}else{
			$html['pre'] = '<span>你已经阅览到最前了</span>';
		}
		//下一页，取比自己小的id之中最大的那个
		$html['nextid'] = _fetch_array("SELECT max(tg_id) as id	
				FROM tg_photo
				WHERE
					tg_sid='{$html['sid']}' and
				tg_id<'{$html['id']}'
				limit 1
				")	;
		if (!empty($html['nextid']['id'])){
			$html['next'] = '<a href="photo_detail.php?id='.$html['nextid']['id'].'#next">下一页</a>';
		}else{
			$html['next'] = '<span>你已经阅览到最后了</span>';
		}
		
	}else{
		_alert_back('不存在此图片');
	}
				
}else{
	_alert_back('非法操作·1');
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  

<?php 
	require ROOT_PATH.'/include/title.inc.php';
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/article.js"></script>
</head>  
<body>
<?php require ROOT_PATH.'include/header.inc.php'; ?>
<div id="photo">
	<h2>图片详细</h2>
	<h2><?php echo $html['a']?></h2>
	
	<a name="pre" ></a><a name="next"></a>
		<dl class="detail">
			<dd class="name"><?php echo $html['name']?></dd>
			<dt><?php echo $html['pre']?><img src="<?php echo $html['url']?>" /><?php echo $html['next']?></dt>
			<dd>浏览量：(<strong><?php echo $html['readcount']?></strong>)  评论量：(<strong><?php echo $html['commentcount']?></strong>)  上传者：<?php echo $html['username']?></dd>
			<dd>图片简介：<?php echo $html['content'];?></dd>
		</dl>
		<p class="line"></p>
				<?php 
			$i=1;
			while (!!$rows=_fetch_array_list($result)){
				$html['username']=$rows['tg_username'];
				$html['type']=$rows['tg_type'];
				$html['retitle']=$rows['tg_title'];
				$html['content']=$rows['tg_content'];
				$html['time']=$rows['tg_time'];
			
				
				if (!!$rows = _fetch_array("SELECT
						tg_id,tg_sex,tg_face,
						tg_email,tg_url,
						tg_switch,
						tg_autograph
								FROM tg_user
						WHERE tg_username='{$html['username']}'
							LIMIT 1
				
						")){
						$html['userid']=$rows['tg_id'];
						$html['sex']=$rows['tg_sex'];
						$html['face']=$rows['tg_face'];
						$html['email']=$rows['tg_email'];
						$html['url']=$rows['tg_url'];
						$html['switch']=$rows['tg_switch'];
						$html['autograph']=$rows['tg_autograph'];
						$html=_html($html);	
						
			}else{
				//这个用户已经被删除了
			}

			?>
					
					<div class="re">
						<dl>
							<dd class="user"><?php echo $html['username'];?>(<?php echo $html['sex'];?>)</dd>
							<dt><img src="<?php echo $html['face']?>" alt="<?php echo $html['username'];?>" /></dt>
							<dd class="message"><a href="javascript:;" name="message" title="<?php echo $html['userid'];?>">发消息</a></dd>
							<dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $html['userid'];?>">加为好友</a></dd>
							<dd class="guest">写留言</dd>
							<dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $html['userid']?>">给他送花</a></dd>
							<dd class="email">邮件：<a href="<?php echo $html['email']?>"><?php echo $html['email']?></a></dd>
							<dd class="url">网址：<a href="<?php echo $html['url']?>" target="_blank"><?php echo $html['url']?></a></dd>
						</dl>
								<div class="content">
											<div class="user">
												<span><?php echo $i+(($page-1)*$pagenum);?>#</span><?php echo $html['username']?> |发表于：<?php echo $html['time']?>
												
											</div>
											<h3>主题：<?php echo $html['retitle']?><img src="image/icon<?php echo $html['type']?>.gif" alt="icon" /><?php echo $html['re']?></h3>
											<div class="detail">
												<?php  echo _ubb($html['content']);?>
												<?php
												if ($html['switch']==1){
													echo '<p class="autograph">'._ubb($html['autograph']).'</p>';	
												}?>
											</div>
								</div>
					</div>								
					
					<p class="line"></p>
					<?php 
					$i++;
							}
							_free_result($result);
							_type(1);
					?>
		<?php if (isset($_COOKIE['username'])) {?>

		<form method="post" action="?action=rephoto">
		
				<input type="hidden" name="sid" value="<?php echo $html['id']?>"/>
				
				<dl class="rephoto">
					<dd>标    	题：<input type="text" name="title" class="text" value="RE:<?php echo $html['name']?>"/></dd>
					<dd id="q">贴   图：<a href="javascript:;">Q图系列【1】</a> 	<a href="javascript:;">Q图系列【2】</a> 	<a href="javascript:;">Q图系列【3】</a></dd>
					<dd>
						<?php include ROOT_PATH.'/include/ubb.inc.php';?>
						
					</dd>
					
					<dd>
					
					验 证 码：<input type="text" name="yzm" class="yzm" /><img src="code.php" id="code"/>
					
					<input type="submit" class="submit" value="发表帖子"/></dd>
				</dl>
		</form>
			<?php }?>
	</div>

<?php require ROOT_PATH.'include/footer.inc.php'; ?>
</body>  
</html>   