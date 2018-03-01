/**
 * 
 */
  window.onload=function()
 {
 	code();
 	//登录验证
 	var fm=document.getElementsByTagName('form')[0];
 	fm.onsubmit=function(){
 	//用户名验证
 		if(fm.username.value.length<2 || fm.username.value.length>20){
 			alert('用户名不得小鱼2位或者大雨20位');
 			fm.username.value='';//清空
 			fm.username.focus();//将焦点以至表单字段
 			return false;
 			
 		}
 		//密码验证
 		if(/[<>\'\"\ ]/.test(fm.username.value)){
 			alert('用户名不得包含非法字符');
 			fm.username.value='';
 			fm.username.focus();
 			return false;
 			
 		}
 		//密码验证
 		if(fm.password.value.length<6||fm.password.value.length>40){
 			alert('密码不得小鱼6位或者大雨40位');
 			fm.password.value='';
 			fm.password.focus();
 			return false;
 		}
 		//彦曾某验证
 		if(fm.yzm.value.length != 4){
 			alert('验证码必须4位');
 			fm.yzm.value='';
 			fm.yzm.focus();
 			return false;
 		}
 		return true;	
 	}
 };