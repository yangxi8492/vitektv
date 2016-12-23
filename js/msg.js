function checkAllAction(){
	if( getV("title")=="" || getV("name")=="" || getV("email")=="" || getV("remark")=="" ){
		var btns=[{value:_SLANG.msg_ok, onclick:"popwin.close()",focus:true}];
		popwin.showDialog(2,_SLANG.msg_warning, _SLANG.msg_fillall, btns,320,130);
		return;
	}
	popwin.loading();
	E("sbtn").disabled=true;
	ajaxPost("msgform","ajaxpublic.php?action=savemsg",checkAllAction_callback);
}

function checkAllAction_callback(data){
	var btns=[{value:_SLANG.msg_ok, onclick:"popwin.close();top.location.reload();",focus:true}];
	var btns2=[{value:_SLANG.msg_ok, onclick:"popwin.close();reloadVerify('securitycodeimg');",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1, _SLANG.msg_succeed, _SLANG.msg_leavesucceed, btns,380,150);
	}else{
		popwin.showDialog(0, _SLANG.msg_failed, _SLANG.msg_leavefailed+":<br />"+data,btns2,380,150);
	}
	E("sbtn").disabled=false;
}