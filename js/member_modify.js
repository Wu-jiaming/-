/**
 * 
 */
 window.onload =function(){
 	code();
 	var faceimg =document.getElementById('faceimg');
 	
 	faceimg.onclick=function()
 	{
 		window.open('face.php','face','width=400,height=400px,top=0,left=0,scrollbars=1'); 
 	}
 	
 	
 	//修改信息的验证 	
 	var fm=document.getElementsByTagName('form')[0];
 	fm.onsubmit=function(){
 		//能用客户验证的  尽量用客户验证
 
 		//密码验证
 		if(fm.password.value != ''){
	 		if(fm.password.value.length<6||fm.password.value.length>40){
	 			alert('密码不得小鱼6位或者大雨40位');
	 			fm.password.value='';
	 			fm.password.focus();
	 			return false;
	 		}
 		}

 		//邮箱验证
 		if(!/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(fm.email.value)){
 			alert('邮箱格式不正确');
 			fm.email.value='';
 			fm.email.focus();
 			return false;
 		}
 		//qq验证
 		if(fm.qq.value!=''){
 			if(!/^[1-9]{1}[\d]{4,9}$/.test(fm.qq.value)){
 				alert('QQ格式不对');
 				fm.qq.value='';
 				fm.qq.focus();
 				return false;
 			}
 		}
 		//网址验证

		if(fm.url.value !="")
		{
			if(fm.url.value !="http:\/\/"){
				if(!/^(http(s)?:\/\/)?(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(fm.url.value)){
				alert('主页网址有错要么不填');
				fm.url.value='';
				return false;
				}
			}
		}
 		//彦曾某验证
 		if(fm.yzm.value.length != 4){
 			alert('验证码必须4位');
 			fm.yzm.value='';
 			fm.yzm.focus();
 			return false;
 		}
 		return true;
 	};
 };