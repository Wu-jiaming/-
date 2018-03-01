<?php
//防止恶意调用
if(!defined('IN_TG')){
	exit('Acess Defined');
}

//获取mysql刚新增的id
function _insert_id(){
	return mysql_insert_id();
}

//销毁函数
function _free_result($result){
	@	mysql_free_result($result);
}
//返回影响sql的记录行数
function _affect_rows(){
	return mysql_affected_rows();
}
function _num_rows($result){
	return mysql_num_rows($result);
}
//conect数据库服务器
function _connect(){
	//globle全局变量
	global $_conn;
	if(!$conn=mysql_connect(DB_HOST,DB_USER,DB_PWD)){
		exit('数据库连接失败');
	}
}
//select一个数据库
function _select_db(){
	if(!mysql_select_db(DB_NAME)){
		exit('选择数据库错误');
	}
}
//设置数据库的字符类型
function _set_query(){
	if(!mysql_query('SET NAMES UTF8')){
		exit('设置字符类型失败');
	}
}
//执行数据库的语句
function _query($sql){
	if(!$result=mysql_query($sql)){
		
		exit('sql语句执行失败!'.mysql_error());
	}
	
	return $result;
}
//只能获取指定数据集一条数据组
function _fetch_array($sql){
	return mysql_fetch_array(_query($sql),MYSQL_ASSOC);
}
//因为while循环  所以不能使用 _fetch_array函数 
//该函数有调用了query函数  在while循环中会不断重复调用query 出现死循环
//所以重做一个函数
function _fetch_array_list($result){
	return @mysql_fetch_array($result,MYSQL_ASSOC);
}

//如果重复了  就输出$info 以上3跳语句都是嵌套的
function _is_repeat($sql,$info){
	if((_fetch_array($sql)))
	{
		_alert_back($info);
	}
}
//检查数据库关闭
function _close()
{
	mysql_close();
}
?>