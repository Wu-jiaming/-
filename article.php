<?php
	session_start();
	define('IN_TG', true);
	define('SCRIPT', 'article');
	require dirname(__FILE__).'/include/common.inc.php';

	//设置敏感帖
	if ($_GET['action'] == 'sen' && isset($_GET['id']) && isset($_GET['on'])){
		if (!!$rows=_fetch_array("SELECT tg_uniqid ,tg_article_time
				FROM tg_user
				WHERE
				tg_username='{$_COOKIE['username']}'
				LIMIT 1"))
		{
			_uniqid($_COOKIE['uniqid'], $rows['tg_uniqid']);
			//设置敏感帖，或者取消敏感帖
			_query("UPDATE tg_article SET tg_sensitive='{$_GET['on']}' WHERE tg_id='{$_GET['id']}'");
			if (_affect_rows() ==1){
				_close();
				//_session_destroy();
				_location('敏感帖设置成功', 'article.php?id='.$_GET['id']);
			}else{
				_close();
				//_session_destroy();
				_alert_back('敏感帖设置失败');
			}
		}
	}else{
	
	}
	//chulijihuatie 
	if ($_GET['action'] == 'nice' && isset($_GET['id']) && isset($_GET['on'])){
				if (!!$rows=_fetch_array("SELECT tg_uniqid ,tg_article_time
											FROM tg_user 
								WHERE 
						tg_username='{$_COOKIE['username']}' 
				LIMIT 1"))
				{
			 		_uniqid($_COOKIE['uniqid'], $rows['tg_uniqid']);
			 		//设置精华帖，或者取消精华帖
					_query("UPDATE tg_article SET tg_nice='{$_GET['on']}' WHERE tg_id='{$_GET['id']}'");
					if (_affect_rows() ==1){
						_close();
						//_session_destroy();
						_location('精华帖设置成功', 'article.php?id='.$_GET['id']);
					}else{
						_close();
						//_session_destroy();
						_alert_back('精华帖设置失败');
					}
				}
	}
	if($_GET['action'] == 'rearticle'){
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
				$clean['reid']=$_POST['reid'];
				$clean['type']=$_POST['type'];
				$clean['title']=$_POST['title'];
				$clean['content']=$_POST['content'];
				$clean['username']=$_COOKIE['username'];
				$clean=_mysql_string($clean);
				//把从页面接受的信息保存在数据库中
			_query("INSERT INTO tg_article (
																	tg_reid,
																	tg_username,
																	tg_title,
																	tg_type,
																	tg_content,
																	tg_time
																)
												 VALUES (
												 					'{$clean['reid']}',
												 					'{$clean['username']}',
												 					'{$clean['title']}',
												 					'{$clean['type']}',
												 					'{$clean['content']}',
												 					NOW()
												 				)");
		
														
	
				if (_affect_rows() ==1){
					$clean['time'] = time();
					_query("UPDATE tg_user 
													set tg_article_time = '{$clean['time']}'
															WHERE 
																tg_username ='{$_COOKIE['username']}'");
					//评论数量
					
					_query("UPDATE tg_article 
															SET tg_commentcount=tg_commentcount+1
																	WHERE  tg_reid= 0 
																			AND tg_id='{$clean['reid']}'");
					
					//_session_destroy();
					_query("update tg_user set tg_score = tg_score+1  where tg_username='{$_COOKIE['username']}'");
					_close();
					_location('回帖成功', 'article.php?id='.$clean['reid']);
				}else{
					_close();
					//_session_destroy();
					_alert_back('回帖失败');
				}
			}
	}

	//删除帖子
	if ($_GET['action']=='delete' && isset($_GET['del_id']))
	{
	
		if (!!$rows = _fetch_array("SELECT tg_uniqid
				FROM tg_user
				WHERE tg_username='{$_COOKIE['username']}'
				LIMIT 1"
		)){
			
			_uniqid($rows['tg_uniqid'], $_COOKIE['uniqid']);
			if (!!$rowss=_fetch_array("SELECT tg_reid
																FROM tg_article
																	WHERE tg_id='{$_GET['del_id']}'")
																	)
							//删除帖子
			{	
				
				 
			
				if ($rowss['tg_reid'] != 0)
 				{
					//_alert_back('删除回复帖');
 							_query("DELETE FROM tg_article WHERE tg_id='{$_GET['del_id']}'");
							if (_affect_rows()==1){
								_location('删除成功','article.php?id='.$rowss['tg_reid']);
							}else{
								_alert_back('删除失败');
							}
				}else{
					//_alert_back('删除主题帖');
					_query("DELETE FROM tg_article WHERE tg_reid='{$_GET['del_id']}'");
					_query("DELETE FROM tg_article WHERE tg_id='{$_GET['del_id']}'");
					if (_affect_rows()==1){
						_location('删除成功','main.php');
					}else{
						_alert_back('删除失败');
					}
				} 
			}
		}
	}
	

	if (isset($_GET['id'])){
		if (!!$rows=_fetch_array("SELECT 
										tg_id,tg_username,tg_nice,tg_sensitive,
										tg_title,tg_type,tg_content,
										tg_readcount,tg_commentcount,tg_time
									FROM tg_article
							WHERE tg_reid=0 AND
												tg_id='{$_GET['id']}'
						LIMIT 1
				"
				)){
			//阅读量
			_query("UPDATE tg_article 
						SET tg_readcount=tg_readcount+1
					WHERE tg_id='{$_GET['id']}'");
			
			$html=array();
			$html['reid']=$rows['tg_id'];
			$html['nice']=$rows['tg_nice'];
			$html['sensitive']=$rows['tg_sensitive'];
			$html['username_subject']=$rows['tg_username'];
			$html['title']=$rows['tg_title'];
			$html['type']=$rows['tg_type'];
			$html['content']=$rows['tg_content'];
			$html['readcount']=$rows['tg_readcount'];
			$html['commentcount']=$rows['tg_commentcount'];
			$html['last_modify_time']=$rows['tg_last_modify_time'];
			$html['time']=$rows['tg_time'];
		
		
			//拿出用户名，去查找用户信息
						if (!!$rows = _fetch_array("SELECT 
												tg_id,tg_sex,tg_face,tg_ban,
												tg_email,tg_url,
												tg_autograph,
												tg_switch
											FROM tg_user
										WHERE tg_username='{$html['username_subject']}'
									LIMIT 1
												
			")){
				$html['userid']=$rows['tg_id'];
				$html['sex']=$rows['tg_sex'];
				$html['face']=$rows['tg_face'];
				$html['email']=$rows['tg_email'];
				$html['switch']=$rows['tg_switch'];
				$html['autograph']=$rows['tg_autograph'];
				$html['url']=$rows['tg_url'];
				$html['ban']=$rows['tg_ban'];
				$html=_html($html);
				
				//创建一个全局变量  做一个带参的分页
				global $id;
				$id='id='.$html['reid'].'&';
				
				//主题帖子修改
				if ($html['username_subject'] == $_COOKIE['username']){
						$html['subject_modify'] = '[<a href="article_modify.php?id='.$html['reid'].'">修改</a>]';
						
				}
				//读取最后一次修改的时间
				if ($html['last_modify_time'] !='0000-00-00 00:00:00'){
					$html['last_modify_time_string'] ='本帖于['.$html['last_modify_time'].']被['.$html['username_subject'].']最后修改';
				}
				
				//给楼主回复
				if ($_COOKIE['username']){
					$html['re']='<span>[<a href="#ree" name="re" title="回复一楼的'.$html['username_subject'].'">回复</a>]</span>';
				}
				
				//个性签名
				if ($html['switch'] == 1){
					$html['aotugraph_html']='<p class="autograph">'._ubb($html['autograph']).'</p>';
					
					
				}
				//读取回帖
				global $pagesize,$pagenum,$page;
				_page("SELECT tg_id FROM tg_article
																WHERE  
														tg_reid = '{$html['reid']}'", 2);
				
				$result=_query("SELECT tg_username,tg_type,tg_id,
																				tg_title,tg_content,tg_time
																		FROM tg_article
																	WHERE tg_reid='{$html['reid']}'
																ORDER BY tg_time ASC
															LIMIT $pagenum,$pagesize");
			}else{
				//该用户被删掉
				_alert_back('该用户被删掉');
			}
		}else{
			_alert_back('不存在这个帖子');
		}
	}else{
		_alert_back('非法操作！');
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
<?php echo $rowss['tg_reid'];?>
	<?php 
		require ROOT_PATH.'/include/header.inc.php';
	?>
	
	<div id="article">
		<h2>帖子详情</h2>
		
		<?php if (!empty($html['sensitive'])){?>
		<img src="image/ban.gif" alt="敏感帖" class="ban" />
		<?php }if(!empty($html['nice'])){?>
		<img src="image/nice.gif" alt="精华帖" class="nice" />
		<?php }
		//浏览量达到400.并且评论量达到20即可为热帖
		if ($html['readcount'] >= 100 && $html['commentcount'] >=5){
		?>
		<img src="image/hot.gif" alt="热帖" class="hot" />
		<?php
		}
			if ($page==1){
				
			
		?>
		<div id="subject">
			<dl>
				<dd class="user"><?php echo $html['username_subject']?>(<?php echo $html['sex']?>)【楼主】</dd>
				<dt><img src="<?php echo $html['face']?>" alt="<?php echo $html['username_subject']?>" /></dt>
				<dd class="message"><a href="javascript:;" name="message" title="<?php echo $html['userid']?>">发消息</a></dd>
				<dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $html['userid']?>">加为好友</a></dd>
				<dd class="guest">写留言</dd>
				<dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $html['userid']?>">给他送花</a></dd>
				<dd class="email">邮件：<a href="<?php echo $html['email']?>"><?php echo $html['email']?></a></dd>
				<dd class="url">网址：<a href="<?php echo $html['url']?>" target="_blank"><?php echo $html['url']?></a></dd>
			</dl>
					<div class="content">
								<div class="user">
								<span>
										<?php
										
										if (isset($_SESSION['admin'])){
											//下面是设置敏感贴
											if (empty($html['sensitive'])     ){?>
											[<a href="article.php?action=sen&on=1&id=<?php echo $html['reid']?>">设敏感帖</a>]
											<?php }else{?>
											[<a href="article.php?action=sen&on=0&id=<?php echo $html['reid']?>">解敏感帖</a>	]
											<?php }
											//下面是设置精华
												if (empty($html['nice'])     ){?>
												[<a href="article.php?action=nice&on=1&id=<?php echo $html['reid']?>">设置精华</a>]
												<?php }else{?>
												[<a href="article.php?action=nice&on=0&id=<?php echo $html['reid']?>">取消精华</a>	]
												<?php }
										}?>
	
										<?php echo $html['subject_modify'];if (isset($_SESSION['admin'])){?>
										[<a href="article.php?action=delete&del_id=<?php  echo $html['reid']?>">删除</a>]
										<?php }?>
										
										1#
									</span><?php echo $html['username_subject'];?> |发表于：<?php echo $html['time'];?>
									
								</div>
								<h3>主题：<?php echo $html['title']?><img src="image/icon<?php echo $html['type']?>.gif"  alt="icon" /><?php echo $html['re']?></h3>
								<div class="detail">
									<?php echo _ubb($html['content'])?>
									<?php echo $html['aotugraph_html'];?>
									
								</div>
								<div class="read">
									阅读量（<?php echo $html['readcount']?>）  评论量（<?php echo $html['commentcount']?>）
								</div>
					</div>
		</div>
		<?php  }
		//开始回复帖子的部分		?>

		<p class="line"></p>
		<?php 
			$i=2;
			while (!!$rows=_fetch_array_list($result)){
				$html['username']=$rows['tg_username'];
				$html['type']=$rows['tg_type'];
				$html['retitle']=$rows['tg_title'];
				$html['content']=$rows['tg_content'];
				$html['time']=$rows['tg_time'];
				$html['nat_id']=$rows['tg_id'];
			
				
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
				
				if (!! $rows=_fetch_array("SELECT * FROM tg_user WHERE tg_username='{$html['username']}'"))
				{
						if($page==1 && $i==2){
									if ($html['username'] == $html['username_subject']) {
										$html['username_html'] = $html['username'].'(楼主)';
									} else {
										$html['username_html'] = $html['username'].'(沙发)';
									}
						}else{
							$html['username_html']=$html['username'];
						}
				}else{

				}
								
						
					
						
			}else{
				//这个用户已经被删除了
				if($page==1 && $i==2){
					if ($html['username'] == $html['username_subject']) {
						$html['username_html'] = $html['username'].'(楼主)'.'(该会员已经被删除)';
					} else {
						$html['username_html'] = $html['username'].'(沙发)'.'(该会员已经被删除)';
					}
				}else{
					$html['username_html']=$html['username'].'(该会员已经被删除)';
				}
			}
			
			//跟帖处理
			if ($_COOKIE['username']){
				$html['re']='<span>[<a href="#ree" name="re" title="回复第'.($i+(($page-1)*$pagenum)).'楼的'.$html['username'].'">回复</a>]</span>';
			}
		?>
		
		<div class="re">
			<dl>
				<dd class="user"><?php echo $html['username_html'];?>(<?php echo $html['sex'];?>)</dd>
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
									<span><?php echo $i+(($page-1)*$pagenum);if (isset($_SESSION['admin'])){?>
									[<a href="article.php?action=delete&del_id=<?php  echo $html['nat_id'];?>">删除</a>]
									<?php }?>
									#
									</span><?php echo $html['username']?> |发表于：<?php echo $html['time']?>
									
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
		<?php if (isset($_COOKIE['username']) &&  (!$html['ban']) && (!$html['sensitive'])) {?>
		<form method="post" action="?action=rearticle">
		<a name="ree"></a>
				<input type="hidden" name="reid" value="<?php echo $html['reid']?>"/>
				<input type="hidden" name="type" value="<?php  echo $html['type']?>"/>
				
				<dl>
					<dd>标    	题：<input type="text" name="title" class="text" value="RE: <?php echo $html['title']?>"/></dd>
					<dd id="q">贴   图：<a href="javascript:;">Q图系列【1】</a> 	<a href="javascript:;">Q图系列【2】</a> 	<a href="javascript:;">Q图系列【3】</a></dd>
					<dd>
						<?php include ROOT_PATH.'/include/ubb.inc.php';?>
						<textarea name="content"  rows="9"></textarea>
					</dd>
					
					<dd>
					<?php if (!empty($system['code'])){?>
					验 证 码：<input type="text" name="yzm" class="yzm" /><img src="code.php" id="code"/>
					<?php }?>
					<input type="submit" class="submit" value="发表帖子"/></dd>
				</dl>
		</form>
			<?php }?>
	</div>
	<?php 
		require ROOT_PATH.'/include/footer.inc.php';
	?>
</body>
</html>