<?php
//删除目录函数
function _remove_Dir($dirname){
	if (! is_dir($dirname)){
		return  false;
	}
	$handle = @opendir($dirname);
	while (($file = @readdir($handle)) !== false){
		if ($file != '.' && $file != '..'){
			$dir = $dirname . '/' .  $file; 
			is_dir($dir) ? _remove_dir($dir) : @unlink($dir);
		}
	}
	closedir($handle);
 	return rmdir($dirname) ;
}
// function _remove_Dir($dirName)
// {
// 	if(! is_dir($dirName))
// 	{
// 		return false;
// 	}
// 	$handle = @opendir($dirName);
// 	while(($file = @readdir($handle)) !== false)
// 	{
// 		if($file != '.' && $file != '..')
// 		{
// 			$dir = $dirName . '/' . $file;
// 			is_dir($dir) ? _remove_Dir($dir) : @unlink($dir);
// 		}
// 	}
// 	closedir($handle);
// 	return rmdir($dirName) ;
// }
//时间
function _runtime() {
	$_mtime = explode(' ',microtime());
	return $_mtime[1] + $_mtime[0];
}
//生成show img 的缩略图
function _thumb($filename,$percent){
	//生成png标头文件
	header('Content-type:image/png');
	$n = explode('.', $filename);
	//获取文件的信息，高和宽
	list($width,$height) = getimagesize($filename);
	//生成缩微的高和宽
	$new_width = $width * $percent;
	$new_height = $height * $percent;
	//传建一个以percen百分比的新长度的画布
	$new_image = imagecreatetruecolor($new_width, $new_height);
	//按照已有的图片传建一个画布
	switch ($n[1]){
		case 'jpg':$image = imagecreatefromjpeg($filename);
		 	 	break;
		case 'png':$image = imagecreatefrompng($filename);
		 	 	break;
		case 'gif':$image = imagecreatefromgif($filename);
		 	 	break;
	}
	//将原图采集后重新复制到新图上，就缩略l 
	imagecopyresampled($new_image, $image,0, 0, 0, 0, $new_width, $new_height, $width, $height);
	imagepng($new_image);
	imagedestroy($image);
	imagedestroy($new_image);
}

//验证是不是用户登录同时还必须是管理员
function _manage_login(){
	if ((!isset($_COOKIE['username']))  || (!isset($_SESSION['admin']))){
		_alert_back('不是管理员或者是没有登录');
	}
}
//验证是不是用户登录还有是不是管理员
function _login(){
	if ((!isset($_COOKIE['username']))  ){
		_alert_back('请先登录');
	}
}
//设置限时发帖功能函数
function _timed($now_time,$pre_time,$second){
	if ($now_time - $pre_time <  $second){
		_alert_back('请阁下休息一会再发帖');
	}
}
//设置ubb样式
function _ubb($str){
	$str=nl2br($str);
	$str=preg_replace('/\[size=(.*)\](.*)\[\/size\]/U', '<span style="font-size:\1px">\2</span>', $str);
									
	$str = preg_replace('/\[b\](.*)\[\/b\]/U','<strong>\1</strong>',$str);
	
	$str=preg_replace('/\[i\](.*)\[\/i\]/U', '<em>\1</em>', $str);
	$str=preg_replace('/\[u\](.*)\[\/u\]/U','<span style="text-decoration:underline">\1</span>',$str);
	$str=preg_replace('/\[s\](.*)\[\/s\]/U','<span style="text-decoration:line-through">\1</span>',$str);
	$str=preg_replace('/\[color=(.*)\](.*)\[\/color\]/U','<span style="color:\1">\2</span>',$str);
										
	$str=preg_replace('/\[url\](.*)\[\/url\]/U','<a href="\1" target="_blank">\1</a>',$str);
	$str= preg_replace('/\[email\](.*)\[\/email\]/U','<a href="mailto:\1">\1</a>',$str);
	$str=preg_replace('/\[img\](.*)\[\/img\]/U','<img src="\1" alt="图片" />',$str);
	$str=preg_replace('/\[flash\](.*)\[\/flash\]/U','<embed style="width:480px;height:400px;" src="\1" />',$str);
	return $str;
	
}

//生成一个xml
function _set_xml($xmlfile,$clean){
	$fp=fopen('new.xml','w');
	if (!$fp){
		exit('创建xml失败');
	}
	flock($fp, LOCK_EX);
	$str="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
	fwrite($fp,$str, strlen($str));
	
	$str="<vip>\r\n";
	fwrite($fp, $str,strlen($str));
	
	$str="\t<id>{$clean['id']}</id>\r\n";
	fwrite($fp,$str, strlen($str));
	
	$str="\t<username>{$clean['username']}</username>\r\n";
	fwrite($fp, $str,strlen($str));
	
	$str="\t<sex>{$clean['sex']}</sex>\r\n";
	fwrite($fp,$str, strlen($str));
	
	$str="\t<face>{$clean['face']}</face>\r\n";
	fwrite($fp,$str, strlen($str));
	
	$str="\t<email>{$clean['email']}</email>\r\n";
	fwrite($fp, $str,strlen($str));
	
	$str="\t<url>{$clean['url']}</url>\r\n";
	fwrite($fp, $str,strlen($str));
	
	$str="</vip>\r\n";
	fwrite($fp, $str,strlen($str));
	
	flock($fp, LOCK_UN);
	fclose($fp);
	
}

//获取xml的数据
function _get_xml($xmlfile){
	$html= array();
	if (file_exists($xmlfile)){
		$xml=file_get_contents($xmlfile);
		preg_match_all('/<vip>(.*)<\/vip>/s', $xml,$dom);
		
		foreach ($dom[1] as $value){
			preg_match_all('/<id>(.*)<\/id>/s', $value,$id);
			
			preg_match_all('/<username>(.*)<\/username>/s', $value,$username);
			
			preg_match_all('/<sex>(.*)<\/sex>/s', $value,$sex);
			
			preg_match_all('/<face>(.*)<\/face>/s', $value,$face);
			
			preg_match_all('/<email>(.*)<\/email>/s', $value,$email);
			
			preg_match_all('/<url>(.*)<url>/s', $value,$url);
			
			$html['id']=$id[1][0];
			$html['username']=$username[1][0];
			$html['sex']=$sex[1][0];	
			$html['url']=$url[1][0];
			$html['email']=$email[1][0];
			$html['face']=$face[1][0];
		}
	}else{
		echo 'xml文件不存在';
	}
	return $html;
}

//信息内容切割函数  
function _title($string,$strlen){
	if (mb_strlen($string,'utf-8')>$strlen){
		$string=mb_substr($string,0,$strlen,'utf-8').'.....';
	}
	return $string;
}

//验证码的验证
function _check_yzm($f_yzm,$s_yzm){
	if(!($f_yzm==$s_yzm))
	{
		_alert_back('验证码不正确');
	}
}


//唯一标识符的识别
function _uniqid($mysql_uniqid,$_cookie_uniqid){
	if($mysql_uniqid!=$_cookie_uniqid){
		_alert_back('唯一标识符错误');
	}
}



//唯一标识符的函数包装
function _sha1_uniqid(){
	return _mysql_string(sha1(uniqid(rand(),true)));
}
//location注册成功之后
function _location($info,$url){
	if(!empty($info)){	
			echo "<script type='text/javascript'>alert('$info');location.href='$url';</script>";
			exit();
								}else{
										header('Location:'.$url);
								}
}
//对字符串进行html的过滤，如果是数组按数组方式过滤
//如果是单独的字符串，那么就按单独到字符串过滤
function _html($str){
	if(is_array($str)){
		foreach($str as $key => $value){
			$str[$key]=_html($value);
		}
	}else{
		$str=htmlspecialchars($str);
	}
	return $str;
}
// 判断是否需要转义字符
function _mysql_string($string){
	//GBC即哪个自动转义的功能是否开启  开启为1
	if(!GPC){
		if(is_array($string)){
									foreach ($string as $key => $value){
										$string[$key] = _mysql_string($value); 
										}
			}else{
			$string =mysql_real_escape_string($string);
		}
		return $string;
	}
	return $string;
}
//关闭窗口
function _alert_close($info){
	echo "<script type='text/javascript'> alert('$info');window.close();</script>	";
}
//小窗口
function _alert_back($info){
	echo "<script type='text/javascript'> alert('$info');history.back();</script>";
	exit();
}
//login_state的登录状态的判断
function _login_state(){
	if(isset($_COOKIE['username'])){
		_alert_back('登录状态无法进行本操作');
	}
}
//删除cookies
function _unsetcookies(){
	setcookie('username','',time()-1);
	setcookie('uniqid','',time()-1);
	_session_destroy();
	_location(null, "main.php");
}

//删除session
function _session_destroy(){
	if (session_start()){
				session_destroy();
	}
}
//验证码的输出
function _code($width=75,$height=25,$rnd_code=4,$flag=true){
	
	for($i=0;$i<$rnd_code;$i++){
		$nmsg.=dechex(mt_rand(0,15));
	}
	$_SESSION['code']=$nmsg;
	//创建一个图像
	$img=imagecreatetruecolor($width, $height);
	//制造白色
	$white=imagecolorallocate($img,255,255,255);
	//填充颜色
	imagefill($img,0,0,$white);
	$flag=true;
	if($flag){
		$black=imagecolorallocate($img,0,0,0);
		imagerectangle($img, 0, 0, $width-1, $height-1, $black);
	}
	//画出6跳线
	for($i=0;$i<6;$i++){
		$rnd_color=imagecolorallocate($img, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
		imageline($img,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width),mt_rand(0,$height),$rnd_color);
	}
	//画雪花
	for($i=0;$i<100;$i++){
		$rnd_color=imagecolorallocate($img, mt_rand(200,255), mt_rand(200,255), mt_rand(200,255));
		imagestring($img,1,mt_rand(1,$width),mt_rand(1,$height),'*',$rnd_color);
	}
	//验证码
	for($i=0;$i<strlen($_SESSION['code']);$i++)
	{
		$rnd_color=imagecolorallocate($img, mt_rand(0,100), mt_rand(0,150), mt_rand(0,200));
		imagestring($img,5,$i*$width/$rnd_code+mt_rand(1,10),mt_rand(1,$height/2),$_SESSION['code'][$i],$rnd_color);
	}
	
	//输出一张图像
	header('Content-Type:image/png');
	imagepng($img);
	//销毁一张图像
	imagedestroy($img);
}

//分页函数
//page=当前页号
//pagesize=一页有多少个信息
//pagenum=LIMIT的其实数字
//pageabsolute=最大的页数号码
//num=一共有多少个数据
function _page($query,$size){
	global $page,$pagesize,$pagenum,$pageabsolute,$num;
	if(isset($_GET['page'])){
		$page=$_GET['page'];
		if (empty($page) || $page<=0 || !is_numeric($page)){
			$page=1;
		}else{
			$page=intval($page);
		}
	}else{
			$page=1;
		}
	$pagesize=$size;
	$num=_num_rows(_query($query));
	if ($num == 0){
		$pageabsolute=1;
		
	}else{
		$pageabsolute=ceil($num/$pagesize);
	}
	if ($page>$pageabsolute){
		$page=$pageabsolute;
	}
	$pagenum=($page-1)*$pagesize;
	
}

//每页的页码类型
function _type($type){
	global $page,$pageabsolute,$num,$id;
	if($type==1){
		echo '<div id="pagenum">';
		echo '<ul>';
	 	for($i=1;$i<=$pageabsolute;$i++) {
				
			if($page==$i)
			{
				echo '<li><a href="'.SCRIPT.'.php?'.$id.'page='.$i.'" class="selected">'.($i).'</a></li>';
			}else{
				echo '<li><a href="'.SCRIPT.'.php?'.$id.'page='.$i.'">'.($i).'</a></li>';
			}
				
		}	
			echo '</ul>';
		echo '</div>';

	}else if($type==2){
			echo '<div id="pagetext">';
			echo '<ul>';
					echo '<li>  '.$page.'/ '.$pageabsolute.'页	|</li>';
					echo '<li>共有<strong> '.$num.'</strong>小弟	</li>';
					 if($page==1)
					{	echo '<li>首页		|</li>';
						echo '<li>上一页		|</li>';
					}else{
						echo '<li><a href="'.SCRIPT.'.php?'.$id.'page=1">首页		|</a></li>';
						//不是首页就可以上一页
						echo '<li><a href="'.SCRIPT.'.php?'.$id.'page='.($page-1).'">上一页		|</a></li>';
					
					}
					if($page==$pageabsolute){
						echo '<li>下一页		|</li>';
						echo  '<li>尾页		|</li>';
						
					}else{
						//不是尾页就可以下一页
						echo '<li><a href="'.SCRIPT.'.php?'.$id.'page='.($page+1).'">下一页		|</a></li>';
						echo '<li><a href="'.SCRIPT.'.php?'.$id.'page='.($pageabsolute).'">尾页		|</a></li>';
					}
					echo '</ul>';
			echo '</div>';
	}
}

?>