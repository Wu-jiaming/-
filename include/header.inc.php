<?php
if(!defined('IN_TG'))
{
	exit('Access Defined!');
}
?>
<script type="text/javascript" src="Js/skin.js"></script>
<div id ="header">
		<h1><a href="main.php">吴家明俱乐部用户留言系统</a></h1>
			<ul>
				<li ><a href="main.php">首页</a></li>
				<?php 
					if(isset($_COOKIE['username'])){
						echo '<li><a href ="member.php">'.$_COOKIE['username'].'-个人中心</a>'.$GLOBALS['message'].'</li>';
						echo "\n";
					}else{
						echo '<li><a href="register.php">注册</a></li>';
						echo "\n";
						echo "\t\t";
						echo '<li><a href="login.php">登录</a></li>';
						echo "\n";
					}
				?>
				<li><a href ="blog.php">小弟</a></li>
				<li><a href ="photo.php">相册</a></li>
				<li class="skin" onmouseover='inskin()' onmouseout='outskin()'>
						<a href="javascript:;">风格</a>
						<dl id="skin">
							<dd><a href="skin.php?id=1">一号皮肤</a></dd>
							<dd><a href="skin.php?id=2">二号皮肤</a></dd>
							<dd><a href="skin.php?id=3">三号皮肤</a></dd>
							
						</dl>		
				</li>
				
				<?php 
				if (isset($_COOKIE['username']) && isset($_SESSION['admin'])){
					echo '<li><a href="manage.php" class="manage"> 管理	 </a></li>';
				}
				if(isset($_COOKIE['username'])){
					echo '<li><a href ="logout.php">退出</a></li>';
					echo "\n";
				}
				
				?>
				
			</ul>	
	</div>