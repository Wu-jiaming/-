<?php
	if(!defined('IN_TG'))
	{
		exit('Acess Defined!');
	}
	header('Content-Type: text/html; charset=utf-8');
	define ('ROOT_PATH',substr(dirname(__FILE__),0,-7));
	//将转义语句命名为GBC
	define('GBC',get_magic_quotes_gpc());
	if(PHP_VERSION < '4.1.0')
	{
		exit('version is too low');
	}
	require ROOT_PATH.'include/global.func.php';
	require ROOT_PATH.'include/mysql.func.php';
	define('start_time',_runtime());

	//将数据库的各个属性又命名
	define('DB_HOST', 'localhost');
	define('DB_USER','root');
	define('DB_PWD','123456');
	define('DB_NAME','testguest');
	
	_connect();
	_select_db();
	_set_query();

//网站设置初始化
if (!! $rows = _fetch_array("SELECT tg_webname,
															 tg_article,
															 tg_blog,
															 tg_photo,
															 tg_skin,
															 tg_string,
															 tg_post,
															 tg_re,
															 tg_code,
															 tg_register
												FROM  tg_system
										WHERE tg_id = 1
									LIMIT 1")){
	$system=array();
	$system['webname'] = $rows['tg_webname'];
	$system['article'] = $rows['tg_article'];
	$system['photo'] = $rows['tg_photo'];
	$system['blog'] = $rows['tg_blog'];
	$system['string'] = $rows['tg_string'];
	$system['skin'] = $rows['tg_skin'];
	$system['post'] = $rows['tg_post'];
	$system['re'] = $rows['tg_re'];
	$system['code'] = $rows['tg_code'];
	$system['register'] = $rows['tg_register'];
	
	$system=_html($system);
	//如果skin的cookies那么代替系统数据库的皮肤
	if ($_COOKIE['skin']){
		$system['skin'] = $_COOKIE['skin'];
	}
}else{
	exit('系统表异常，请管理员检查');
}
//未读信息的状态显示
$message = _fetch_array("SELECT COUNT(tg_id)
								AS count
						FROM tg_message		           	
		WHERE tg_state=0 AND tg_touser='{$_COOKIE['username']}'
		");
if (empty($message['count'])){
	$GLOBALS['message']='<strong class="read"><a href="member_message.php">(0)</a></strong> ';
}else {
	$GLOBALS['message']='<strong class ="noread"><a href="member_message.php">('.$message['count'].')</a></strong> ';
}
?>