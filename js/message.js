/**
 * 
 */
 window.onload = function(){
 	code();
 	var fm = document.getElementsByTagName('form')[0];
 	fm.onsubmit = function(){
 		if(fm.code.value.length !=4){
 			alert('验证码必须四位');
 			fm.code.focus();
 			return false;
 		}
 		if(fm.content.value.length < 1 || fm.content.value.length >10000){
 			alert('信息内容必须至少一位或者是不超过10000');
 			fm.content.focus();
 			return false;
 		}
 	};
 };