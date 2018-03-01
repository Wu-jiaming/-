<?php
//防止恶意调用
if(!defined('IN_TG')){
	exit('Access defined');
}
//检查_alert_back()函数是否存在
if(!(function_exists('_alert_back')))
{
	exit('_alert_back()函数不存在');
}
//setcookies生成cookies
function _setcookies($_username,$_uniqid,$_time) {
	switch ($_time) {
		case '0':  //浏览器进程
			setcookie('username',$_username);
			setcookie('uniqid',$_uniqid);
			break;
		case '1':  //一天
			setcookie('username',$_username,time()+86400);
			setcookie('uniqid',$_uniqid,time()+86400);
			break;
		case '7':  //一周
			setcookie('username',$_username,time()+604800);
			setcookie('uniqid',$_uniqid,time()+604800);
			break;
		case '30':  //一月
			setcookie('username',$_username,time()+2592000);
			setcookie('uniqid',$_uniqid,time()+2592000);
			break;
	}
}
//检查_mysql_string
if(!function_exists('_mysql_string'))
{
	exit('_mysql_string函数不存在');
}
//过滤用户名是否符合标准
function _check_name($string,$min_num,$max_num){
	$string=trim($string);	
	if(!(mb_strlen($string,'utf-8')>=$min_num&&mb_strlen($string,'utf-8')<=$max_num)){
		_alert_back('长度不得小于'.$min_num.'或者大于'.$max_num.'位');
		
	}
	$char_pattern ='/[<>\'\"\ ]/';
	if(preg_match($char_pattern, $string))
	{
			_alert_back('用户名不得是特殊字符');
	}
	$mg[0]='操你妈';
	$mg[1]='你麻痹';
//	将敏感名词连在一起
	foreach ($mg as $value){
		$mg_string.='['.$value.']';
		
	}
	//检查输入的用户名是否等于敏感名词
	if(in_array($string,$mg)){
		_alert_back($mg_string.'以上敏感用户名不得注册');
	}
	return _mysql_string($string);
}
//用户输入的密码和确认密码是否符合标准
function _check_password($pass,$min_num,$max_num){
	if((strlen($pass)<$min_num)&&(strlen($pass)<=$max_num)){
		
		_alert_back('密码必须至少'.$min_num.'位且少于'.$max_num.'');
	}

	return _mysql_string(sha1($pass));
}
//转义保留时间时间
function  _check_time($time){
	$time_array=array('0','1','7','30');
	if(!in_array($time, $time_array))
	{
		_alert_back('保留出错');
	}
	return _mysql_string($time);
}

?>