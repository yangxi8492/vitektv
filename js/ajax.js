///////////ajaxGet
///////////ajaxPost

function ajaxGet(url, callback) {
	var req = newXMLHttpRequest();
	url = url + ((url.indexOf("?")==-1)?"?":"&") +"timestamp="+(new Date()).getTime();
	if (req) {
		req.open("GET", url, true);
		req.onreadystatechange = getReadyStateHandler(req, callback);
		req.send(null);
	} else {
		jsDebug("XMLHttpRequest is NULL");
	}
}

function ajaxPost(formid ,url, callback) {
	var req = newXMLHttpRequest();
	url = url + ((url.indexOf("?")==-1)?"?":"&") +"timestamp="+(new Date()).getTime();
	if (req) {
		req.open("POST", url, true);
		req.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		req.onreadystatechange = getReadyStateHandler(req, callback);
		var oForm = document.getElementById(formid);
		req.send(getRequestBody(oForm));
	} else {
		jsDebug("XMLHttpRequest is NULL");
	}
}


function newXMLHttpRequest() {
	var ajax = false;
	try {
		ajax = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch (e) {
		try {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (E) {
			ajax = false;
		}
	}
	if (!ajax && typeof XMLHttpRequest != "undefined") {
		ajax = new XMLHttpRequest();
	}
	return ajax;
}
function getReadyStateHandler(req, responseXmlHandler) {
	return function () {
		if (req.readyState == 4) {
			if (req.status == 200) {
				responseXmlHandler(req.responseText);
			} else {
				jsDebug("HTTP error " + req.status + ": " + req.statusText);
			}
		}
	};
}
function jsDebug(info) {
	alert(info);
}

function getRequestBody(oForm) {
	var tmpForm = new Array();
	var aParams = new Array();
	for (var i = 0; i < oForm.elements.length; i++) {
		var sParam = encodeURIComponent(oForm.elements[i].name);
		var sValue = oForm.elements[i].value;
		if(oForm.elements[i].type=="radio"){
			if(tmpForm.contain(sParam)){
				continue;
			}
			tmpForm.push(sParam);
			sValue=getRadioValue(oForm.elements[i].name);
		}
		if(oForm.elements[i].type=="checkbox"){
			if(!oForm.elements[i].checked){
				continue;
			}
		}
		if(oForm.elements[i].nodeName=="SELECT"&&oForm.elements[i].multiple){
			sValue=getMultipleValue(oForm.elements[i]);
		}
		sParam += "=";
		sParam += encodeURIComponent(sValue);
		aParams.push(sParam);
	}
	return aParams.join("&");
}

function getMultipleValue(ob)
{
	var arSelected = new Array();
	while (ob.selectedIndex != -1)
	{
		arSelected.push(ob.options[ob.selectedIndex].value);
		ob.options[ob.selectedIndex].selected = false;
	}
	return arSelected.toString();
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
}

function array_has(val)
 {
  var i;
  for(i = 0; i < this.length; i++)
  {
   if(this[i] == val)
   {
    return true;
   }
  }
  return false;
 }
Array.prototype.contain = array_has;