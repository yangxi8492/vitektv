function E(elementid)
{  
	var obj;
	try
	{
		obj = document.getElementById(elementid);
	}
	catch (err)
	{
		alert(elementid+" NOT Found!","System");
	}
	return obj;
}
function getE(elementid){
	return E(elementid);
}
function setDisplays(es,s){
	for(var n=0;n<es.length;n++){
		if(E(es[n])){
			E(es[n]).style.display = ((s[n])?"":"none");
		}
	}
}
function setDisplay(e,s){
	if(E(e)){
		E(e).style.display = (s?"":"none");
	}
}

function getV(oid){
	try{
		var v = E(oid).value;
		if(v==null){
			return "";
		}
		return v;
	}catch(err){
		return "";
	}
}

function trim(str){
	return str.replace(/(^\s*)|(\s*$)/g, "");
}

function getTimer(){
	return (new Date()).getTime();
}

function getRadioValue(rName)
{
	var rObj = document.getElementsByName(rName);
	for ( var i=0; i<rObj.length; i++ )
	{
		if ( rObj[i].checked )
		{
			return rObj[i].value;
		}
	}
	return null;
}

function setRadioValue(rName , val)
{
	var rObj = document.getElementsByName(rName);
	for ( var i=0; i<rObj.length; i++ )
	{
			rObj[i].checked = rObj[i].value == val;
	}
	return null;
}

function setRadioCheck(rName , val)
{
	setRadioValue(rName , val);
}


function setSelect(eid,evalue)
{
	var sObj = E(eid);
	if(!sObj){
		return;
	}
	for ( var i=0; i<sObj.length; i++ )
	{
		if ( sObj[i].value==evalue )
		{
			sObj[i].selected = true;
		}
	}
}

function setSelectList(objid, arrayvar, arrvalue)
{
	var objlen = E(objid).options.length;
	if ( arrvalue==null )
	{
		arrvalue = new Array();
		for ( var j=0; j<arrayvar.length; j++ )
		{
			arrvalue[j] = j+1;
		}
	}	
	
	for ( var i=0+objlen,x=0; i<arrayvar.length+objlen; i++,x++ )
	{
		//option begin at 0
		
		if(arrayvar[x]=="")
		{
			i--;
			objlen--;
		}
		else
		{
			E(objid).options[i] = new Option(arrayvar[x],arrvalue[x]); //value begin at 1
		}
	}
}


function removeAllOptions(objid)
{
	var obj = E(objid);
	var browsernum = checkBrowser();
	if ( browsernum==2 || browsernum == 3) //firefox and google
	{
		obj.length = 0;
	}
	else
	{
		try
		{
			while(obj.options[0] != null)
			{
				obj.options.removeChild(obj.options[0]);  
			}
		}
		catch(err)
		{
		}
	}
}

function checkBrowser()
{		
	if ( navigator.userAgent.indexOf("MSIE")>0 )
		return 1;
	if ( isFirefox=navigator.userAgent.indexOf("Firefox")>0 )
		return 2;
	if ( isSafari=navigator.userAgent.indexOf("Safari")>0 ) //google
		return 3;
	if ( isCamino=navigator.userAgent.indexOf("Camino")>0 )
		return 4;
	if ( isMozilla=navigator.userAgent.indexOf("Gecko/")>0)
		return 5;
	return 0;
}

RegExp.escape = function(text) {
  if (!arguments.callee.sRE) {
	var specials = [
	  '/', '.', '*', '+', '?', '|',
	  '(', ')', '[', ']', '{', '}', '\\'
	];
	arguments.callee.sRE = new RegExp(
	  '(\\' + specials.join('|\\') + ')', 'g'
	);
  }
  return text.replace(arguments.callee.sRE, '\\$1');
}

function urlEncode(str){
	return encodeURIComponent(str);
}

function debugObj(obj) { 
   var props = ""; 
	for(var p in obj){  
	   if(typeof(obj[p])=="function"){  
		   obj[p](); 
	   }else{  
		   props+= p + "=" + obj[p] + "\t"; 
	   }  
   }  
  alert(props); 
} 

function onRun(fun, run_stop_cond, stop_cond){
	var timeout=30000;
	var timeper = 250;
	var timecounter=0;
	var t=window.setInterval(
		function(){
			//E("debugstr").innerHTML+=".";
			timecounter+=timeper;
			if(stop_cond()||timecounter>timeout){
				window.clearInterval(t);
				return;
			}
			if(run_stop_cond()){
				window.clearInterval(t);
				fun();
				return;
			}
		}
		,timeper
	);
}

function reloadVerify(imgid){
	E(imgid).src="code.php?t="+getTimer();
}


//得到字符长度，英文1，中文2
function getLength(input)
{
	var i,cnt = 0;
	var temp = input;
	for ( i=0; i<temp.length; i++ )
	{
		if ( escape(temp.charAt(i)).length>=4 )
		{
			cnt+=2;
		}
		else
		{
			cnt++;
		}
	}
	return cnt;
}

function isSucceed(calldata){
	return (calldata.substring(0,3)=='_Y_');
}

function getCallBackData(calldata){
	if(calldata=="")return"";
	var reg = /_Y_(.[^_Y_]*)_Y_/;
	var result =  reg.exec(calldata);
	if(result){
		return (result[1]);
	}else{
		return ("");
	}
}

function selectAll(formid,b){
	var oForm=E(formid);
	for (var i = 0; i < oForm.elements.length; i++) {
		if(oForm.elements[i].type=="checkbox"&&!oForm.elements[i].disabled){
			oForm.elements[i].checked = b;
		}
	}
}
