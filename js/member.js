//member_details
var hasDetailsErr = false;

function checkEmail(){
	var v = E("email").value;
	if(!isEmail(v)){
		hasDetailsErr = true ;
		E("email_tips").innerHTML = "<span class='errStyle'>"+_SLANG.member_fillcoremail+"</span>"; 
	}else{
		E("email_tips").innerHTML = "<span class='yesStyle'>"+_SLANG.member_coremail+"</span>"; 		
	}
}


function checkDetailsAction(){
	hasDetailsErr = false;
	checkEmail();
	if(!hasDetailsErr){
		popwin.loading();
		ajaxPost("detailsform","ajaxmember.php?action=modifyDetails", details_callback);
	}
}


function details_callback(data){
	popwin.loaded();
	var btns=[];
	if(isSucceed(data)){
		btns=[
			{value:_SLANG.all_ok,onclick:"javascript:popwin.close()",focus:true}
		];
		popwin.showDialog(1,_SLANG.all_modify_succeed, _SLANG.member_details_succeed,btns,320,140);
	}else{
		btns=[
			{value:_SLANG.all_ok,onclick:"popwin.close()",focus:true}
		];
		popwin.showDialog(0,_SLANG.all_modify_failed, _SLANG.member_details_failed+"<br />"+data,btns,320,140);
	}
}

function details_PageInit(){
	E("birthday").onfocus = function(){choosedate.dfd(E('birthday'))};
	E("email").onblur = checkEmail;
	setRadioCheck("member[sex]", member_sex);
}

//member_password
var hasPassErr = false;

function checkOldPassword(){
	var v = E("oldpass").value;
	if(v==""){
		hasPassErr = true;
		E("oldpass_tips").innerHTML = "<span class='errStyle'>"+_SLANG.member_must_fill+"</span>";
	}
	else{
		E("oldpass_tips").innerHTML = "";
	}
}

function checkPassword(){
	var v = E("memberpass").value;
	if(v==""||getLength(v)<6||getLength(v)>16){
		hasPassErr = true;
		E("memberpass_tips").innerHTML = "<span class='errStyle'>"+_SLANG.member_length6_16+"</span>";
	}else{
		E("memberpass_tips").innerHTML = "<span class='yesStyle'>&nbsp;</span>";
	}
}

function checkRePassword(){
	var v = E("memberpass").value;
	var rv = E("repass").value;
	if(rv==""||v!=rv){
		hasPassErr = true;
		E("repass_tips").innerHTML = "<span class='errStyle'>"+_SLANG.member_repeat_pass+"</span>";
	}else{
		E("repass_tips").innerHTML = "<span class='yesStyle'>&nbsp;</span>";
	}
}

function checkChangePass(){
	hasPassErr = false;
	checkOldPassword();
	checkPassword();
	checkRePassword();
	if(!hasPassErr){
		popwin.loading();
		ajaxPost("pass_form","ajaxmember.php?action=modifyPass",pass_callback);
	}
}

function pass_callback(data){
	popwin.loaded();
	var btns=[];
	if(isSucceed(data)){
		btns=[
			{value:_SLANG.all_ok,onclick:"javascript:popwin.close()",focus:true}
		];
		popwin.showDialog(1,_SLANG.all_modify_succeed, _SLANG.member_pass_succeed,btns,320,140);
		E("memberpass").value = "";
		E("oldpass").value = "";
		E("repass").value = "";
	}else{
		btns=[
			{value:_SLANG.all_ok,onclick:"popwin.close()",focus:true}
		];
		popwin.showDialog(0,_SLANG.all_modify_failed,_SLANG.member_pass_failed+"<br />"+data,btns,320,140);
	}
}

function password_PageInit(){
	E("memberpass").onkeyup = function(){pwStrength(E("memberpass").value, 'strength', 6);};
}

function msg_selectall(b){
	var oForm=getE("membermsg_form");
	for (var i = 0; i < oForm.elements.length; i++) {
		if(oForm.elements[i].type=="checkbox"&&!oForm.elements[i].disabled){
			oForm.elements[i].checked = b;
		}
	}
}

function deleteMsgs(){
	popwin.loading();
	ajaxPost("membermsg_form","ajaxmember.php?action=deleteMsgs",deleteMsgs_callback);
}

function deleteMsgs_callback(data){
	popwin.loaded();
	window.location.reload();
}


//收藏
function ajax_addFav(proid){
	popwin.loading();
	ajaxGet("ajaxmember.php?action=addFav&proid="+proid, ajax_addFav_callback);
}


function ajax_addFav_callback(data){
	popwin.loaded();
	var btns=[];
	btns=[
		{value:_SLANG.all_ok,onclick:"javascript:popwin.close()",focus:true}
	];
	if(isSucceed(data)){
		popwin.showDialog(1,_SLANG.all_add_succeed,_SLANG.member_addfav_succeed,btns,320,140);
	}else{
		popwin.showDialog(0,_SLANG.all_add_failed,data,btns,320,140);
	}
}

function ajax_delFav(){
	popwin.loading();
	ajaxPost("favsform","ajaxmember.php?action=delFav", ajax_delFav_callback);
}

function ajax_delFav_callback(data){
	popwin.loaded();
	var btns=[];
	if(isSucceed(data)){
		top.location.reload();
	}else{
		btns=[
			{value:_SLANG.all_ok,onclick:"popwin.close()",focus:true}
		];
		popwin.showDialog(0,_SLANG.all_del_failed,data,btns,320,140);
	}
}


//购物车
var CT;

function ajax_addToCart(proid){
	popwin.loading();
	ajaxGet("ajaxpublic.php?action=addToCart&proid="+proid, ajax_addToCart_callback);
}

function ajax_addToCart_callback(data){
	popwin.loaded();
	var btns=[];
	if(isSucceed(data)){
		btns=[
			{value:_SLANG.member_settle,onclick:"top.location.href='cart.php';",focus:true},
			{value:_SLANG.member_goshop,onclick:"javascript:popwin.close()",focus:true}
		];		
		popwin.showDialog(1,_SLANG.all_add_succeed,_SLANG.member_addcart_succeed,btns,320,140);
	}else{
		popwin.showDialog(0,_SLANG.all_add_failed,data,btns,320,140);
	}
}

function ajax_delFromCart(proid){
	popwin.loading();
	ajaxGet("ajaxpublic.php?action=delFromCart&proid="+proid, ajax_delFromCart_callback);
}

function ajax_delFromCart_callback(data){
	popwin.loaded();
	var btns=[];
	if(isSucceed(data)){
		$("#odtr_"+getCallBackData(data)).remove(); 
		countTotal();
	}else{
		btns=[
			{value:_SLANG.all_ok,onclick:"popwin.close()",focus:true}
		];
		popwin.showDialog(0,_SLANG.all_del_failed,data,btns,320,140);
	}
}

function ajax_changePronum(proid,pronum){
	popwin.loading();
	ajaxGet("ajaxpublic.php?action=changePronum&proid="+proid+"&pronum="+pronum, ajax_changePronum_callback);
}

function ajax_changePronum_callback(data){
	popwin.loaded();
}

function changeProNum(proid,n){
	var v=E("pronum_"+proid).value*1;
	if(n==-1 && v<=1){
		return;
	}
	E("pronum_"+proid).value=v+n;
	countTotal();
	if(CT){window.clearTimeout(CT);}
	CT=window.setTimeout(function(){
		ajax_changePronum(proid, E("pronum_"+proid).value);
	},1000);
}

function countTotal(){
	var pros=document.getElementsByName("proid");
	var proid;
	var itemtotal=0;
	var total=0;
	var pronum;
	for(var i=0; i<pros.length; i++){
		proid=pros[i].value;
		pronum=E("pronum_"+proid).value;
		if(isNaN(pronum*1) || pronum*1<1){
			pronum=1;
			E("pronum_"+proid).value=pronum;
		}
		itemtotal=E("price_"+proid).value * 1 * pronum;
		total+=itemtotal;
		E("itemtotal_"+proid).innerHTML=formatCurrency(itemtotal);
	}
	E("total").innerHTML=formatCurrency(total);
}

function initPronumChange(){
	var pros=document.getElementsByName("proid");
	var proid;
	for(var i=0; i<pros.length; i++){
		proid=pros[i].value;
		(
			function(obj,proid){
				obj.onchange=function(){
					countTotal();
					if(CT){window.clearTimeout(CT);}
					CT=window.setTimeout(function(){
						ajax_changePronum(proid, E("pronum_"+proid).value);
					},1000);
				}
			}
		)(E("pronum_"+proid),proid);
	}
}


function formatCurrency(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+','+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + num + '.' + cents);
}