function email_checkAllAction(){
	var btns=[{value:_SLANG.all_ok,onclick:"popwin.close();",focus:true}];
	var eu = trim(E("membername").value);
	if(eu==""){
		popwin.showDialog(2, _SLANG.all_tips, _SLANG.public_filluser, btns, 280, 130);
		return;
	}
	var ev = trim(E("email").value);
	if(!isEmail(ev)){
		popwin.showDialog(2, _SLANG.all_tips, _SLANG.public_emailformaterr, btns, 280, 130);
		return;
	}
	var sv = trim(E("securitycode").value);
	if(sv==""){
		popwin.showDialog(2, _SLANG.all_tips, _SLANG.public_fillcode, btns, 280, 130);
		return;
	}
	popwin.loading();
	ajaxPost("mailform","ajaxpublic.php?action="+action,email_checkAllAction_callback);
}

function email_checkAllAction_callback(data){
	var btns=[{value:_SLANG.all_ok,onclick:"popwin.close();reloadVerify('securitycodeimg')",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1, _SLANG.all_op_succeed, _SLANG.public_hadsubmit, btns, 380, 150);
	}else{
		popwin.showDialog(0, _SLANG.all_op_failed, data,btns,340, 150);
	}
}
