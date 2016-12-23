var hasErr = false;
var hasmemberCallback=false; 
var hasEmailCallback=false;
var hasVerifyCallback=false;

function checkmembername(){
	var v = trim(E("membername").value);
	if(v==""||getLength(v)<4||getLength(v)>16){
		hasErr = true;
		E("membername_tips").innerHTML = "<span class='errStyle'>"+_SLANG.signup_length4_16+"</span>";
	}else{
		E("membername_tips").innerHTML = "<span class='checkingStyle'>"+_SLANG.signup_checking+"</span>";
		hasmemberCallback=false;
		ajaxGet("ajaxpublic.php?action=checkmemberValid&u="+urlEncode(v)+"&t="+getTimer(),checkmemberValid_callback);
	}
}

function checkmemberValid_callback(data){
	if(isSucceed(data)){
		E("membername_tips").innerHTML = "<span class='yesStyle'>"+_SLANG.signup_usernameok+"</span>";
	}else{
		hasErr = true;
		E("membername_tips").innerHTML = "<span class='errStyle'>"+data+"</span>";
	}
	hasmemberCallback=true;
}


function checkPassword(){
	var v = E("memberpass").value;
	if(v==""||getLength(v)<6||getLength(v)>16){
		hasErr = true;
		E("memberpass_tips").innerHTML = "<span class='errStyle'>"+_SLANG.signup_length6_16+"</span>";
	}else{
		E("memberpass_tips").innerHTML = "<span class='yesStyle'>"+_SLANG.signup_canreg+"</span>";
	}
}

function checkRePassword(){
	var v = E("memberpass").value;
	var rv = E("repass").value;
	if(v!=rv || rv==""){
		hasErr = true;
		E("repass_tips").innerHTML = "<span class='errStyle'>"+_SLANG.signup_repass+"</span>";
	}else{
		E("repass_tips").innerHTML = "<span class='yesStyle'>"+_SLANG.signup_canreg+"</span>";
	}
}

function checkEmail(){
	var v = trim(E("email").value);
	if(!isEmail(v)){
		hasErr = true;
		E("email_tips").innerHTML = "<span class='errStyle'>"+_SLANG.signup_emailformaterr+"</span>"; 
	}else{
		E("email_tips").innerHTML = "<span class='checkingStyle'>"+_SLANG.signup_checking+"</span>";
		hasEmailCallback=false;
		ajaxGet("ajaxpublic.php?action=checkEmailValid&e="+urlEncode(v)+"&t="+getTimer(), checkEmailValid_callback);
	}
}

function checkEmailValid_callback(data){
	if(isSucceed(data)){
		E("email_tips").innerHTML = "<span class='yesStyle'>"+_SLANG.signup_emailok+"</span>";
	}else{
		hasErr = true;
		E("email_tips").innerHTML = "<span class='errStyle'>"+data+"</span>";
	}
	hasEmailCallback=true;
}


function checkSecurityCode(){
	if(!E('securitycode')){
		hasVerifyCallback=true;
		return;
	}
	var v = trim(E("securitycode").value);
	E("verify_tips").innerHTML = "<span class='checkingStyle'>"+_SLANG.signup_checking+"</span>";
	hasVerifyCallback=false;
	ajaxGet("ajaxpublic.php?action=checkSecurityCode&v="+urlEncode(v)+"&t="+getTimer(), checkSecurityCode_callback);
}

function checkSecurityCode_callback(data){
	if(isSucceed(data)){
		E("verify_tips").innerHTML = "<span class='yesStyle'>"+_SLANG.signup_inputok+"</span>";
	}else{
		hasErr = true;
		E("verify_tips").innerHTML = "<span class='errStyle'>"+data+"</span>";
	}
	hasVerifyCallback=true;
}

function checkAgree(){
	if(!E("agreereg").checked){
		hasErr = true;
		E("agree_tips").innerHTML = "<span class='errStyle'>"+_SLANG.signup_useragreement_tips+"</span>"; 
	}else{
		E("agree_tips").innerHTML = ""; 		
	}
}

function cond(){
	return (hasmemberCallback&&hasEmailCallback&&hasVerifyCallback&&!hasErr);
}
function stopcond(){
	return hasErr;
}
function runfun(){
	popwin.loading();
	E("regform").submit();
}

function checkAllAction(){
	hasErr = false;
	checkmembername();
	checkPassword();
	checkRePassword();
	checkEmail();
	checkSecurityCode();
	//checkAgree();
	onRun(runfun, cond, stopcond);
}

function viewmemberAgreement(){
	popwin.showURL(_SLANG.signup_useragreement,'agreement.php', 620, 450);	
}

function clickAgree(){
	popwin.close();
	E("agreereg").checked = true;
}

function signup_PageInit(){
	E("membername").focus();
	E("membername").onblur = checkmembername;
	E("memberpass").onkeyup = function(){pwStrength(E("memberpass").value, 'strength', 6);};
	E("memberpass").onblur = checkPassword;
	E("repass").onblur = checkRePassword;
	E("email").onblur = checkEmail;
	if(E("securitycode")){
		E("securitycode").onblur = checkSecurityCode;
	}
}
