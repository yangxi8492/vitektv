function login_checkAllAction(){
	if(getV("membername")==""||getV("memberpass")==""){
		E("input_err").innerHTML =  "<span class='errStyle'>"+_SLANG.login_filluser_andpass+"</span>"; 
		return false;
	}
	return true;
}

function login_PageInit(){
	E("membername").focus();	
}