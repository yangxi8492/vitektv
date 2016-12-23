function checkCart(){
	if(memberid==""||memberid=="0"){
		var btns=[
			{value:_SLANG.cart_nowlogin, onclick:"top.location.href='member.php';",focus:true},
			{value:_SLANG.cart_nownotlogin, onclick:"checkCartAction()",focus:true}
		];
		popwin.showDialog(2,_SLANG.all_warning, _SLANG.cart_logintips, btns,320,130);
	}else{
		checkCartAction();
	}
}

function checkCartAction(){
	if(  getV("name")=="" || getV("phonenum")=="" || getV("address")==""){
		var btns=[{value:_SLANG.all_ok, onclick:"popwin.close()",focus:true}];
		popwin.showDialog(2,_SLANG.all_warning, _SLANG.all_fillall, btns,320,130);
		return;
	}
	popwin.loading();
	ajaxPost("orderform","ajaxpublic.php?action=saveorder",checkCart_callback);
}

function checkCart_callback(data){
	var btns;
	if(memberid==""||memberid=="0"){
		btns=[{value:_SLANG.all_ok, onclick:"popwin.close();window.location.href='productlist.php';",focus:true}];
	}else{
		btns=[{value:_SLANG.all_ok, onclick:"popwin.close();window.location.href='member.php?action=myorders';",focus:true}];
	}
	var btns2=[{value:_SLANG.all_ok, onclick:"popwin.close();",focus:true}];
	popwin.loaded();
	if(isSucceed(data)){
		popwin.showDialog(1,_SLANG.all_op_succeed, _SLANG.cart_succeed_order+":<b>"+getCallBackData(data)+"</b>",btns,380,150);
	}else{
		popwin.showDialog(0,_SLANG.all_op_failed, _SLANG.all_op_failed+":<br />"+data,btns2,380,150);
	}
}
