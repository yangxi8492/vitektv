//弹出窗口类popwin
function E(elementid)
{
	var obj;
	try
	{
		obj = document.getElementById(elementid);
	}
	catch (err)
	{
		alert(elementid+" NOT Found!");
	}
	return obj;
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

function POPUP(){
	var _t=this;
	_t.ID="popwin";
	_t.X=0;
	_t.Y=0;
	_t.W = 300 ;	_t.H = 200 ;	//Default Size
	_t.fixX = 0;	_t.fixY = 20;	//Fix position
	_t.mX = 0;		_t.mY = 0;		//Mouse Position
	_t.title ;
	_t.url ;
	_t.html ;
	_t.zindex =50000;
	_t.isShow = false;
	_t.showNum = 0;
	_t.ifrBlank = "about:blank";
	_t.closeEvent = "";

	_t.setSize = function(w,h,v){
		if(v==false)
		{
			setDisplays([_t.ID,_t.ID+"_html",_t.ID+"_loading",_t.ID+"_iframe"],[false,false,true,false]);
		}
		_t.W =w;
		_t.H =h;
		var elemSize = [
			{ID:_t.ID,w:_t.W,h:_t.H},
			{ID:_t.ID+"_main",w:(_t.W),h:20},
			{ID:_t.ID+"_title",w:(_t.W-26),h:20},
			{ID:_t.ID+"_content",w:(_t.W-26),h:(_t.H-46)},
			{ID:_t.ID+"_html",w:(_t.W-26),h:(_t.H-20)},
			{ID:_t.ID+"_loading",w:(_t.W-26),h:(_t.H-20)},
			{ID:_t.ID+"_iframe",w:(_t.W-26),h:(_t.H-46)},
			{ID:_t.ID+"_ifr",w:(_t.W-26),h:(_t.H-46)}
		]
		var curE;
		for(var n=0;n<elemSize.length;n++){
			curE=elemSize[n];
			if(E(curE.ID)){
				if(curE.w !=undefined)E(curE.ID).style.width = curE.w +"px";
				if(curE.h !=undefined)E(curE.ID).style.height = curE.h +"px";
			}
		}
	}

	_t.Init = function(){

		if(!E(_t.ID+"_out")){
			document.write("<div id=\""+_t.ID+"_out\" style=\"width:0px;height:0px;overflow:hidden;\">"+_t.ID+"_out</div>");
		}
		else{
			E(_t.ID+"_out").innerHTML = "";
		}
		_t.createDiv();
		_t.setSize(_t.W,_t.H);
		window.onresize = function () { popwin.refreshCover(); };
	}

	_t.createDiv = function(){
		var s="";
			s+="<div id='"+_t.ID+"coverDiv' class='coverDiv' style='display:none; z-index:" +_t.zindex+ ";' >";
				s+="<iframe id='"+_t.ID+"coverFrame' class='coverFrame' border='0' frameborder='0' src='"+_t.ifrBlank+"'></iframe>";
			s+="</div>";
			s+=("<div id=\""+_t.ID+"_ajaxloading\" class=\"snapdiv_loading\" style=\"position:absolute;display:none;z-index:500;\"><\/div>");
			s+=("<div style=\"position:absolute;left:0px;top:0px;display:none;z-index:"+_t.zindex+5+";\" id=\""+_t.ID+"\">");
			s+=("	<div style=\"\" id=\""+_t.ID+"_main\" class=\"snapdiv_title_div\"><div style=\"float:left;\" id=\""+_t.ID+"_title\" class=\"snapdiv_title\">&nbsp;<\/div><div class=\"snapdiv_button_close\" onclick=\"popwin.close()\"></div><\/div>	");
				s+=("	<div id=\""+_t.ID+"_content\" class=\"snapdiv_content\">");
				s+=("		<div id=\""+_t.ID+"_loading\" class=\"snapdiv_loading\"><\/div>");
				s+=("		<div id=\""+_t.ID+"_html\" style=\"display:none;\"><\/div>");
				s+=("		<div id=\""+_t.ID+"_iframe\" style=\"display:none;\"><iframe src=\""+_t.ifrBlank+"\" onreadystatechange=\"popwin.IFrameStateChangeIE(this)\" onload=\"popwin.IFrameStateChangeFF(this)\" style=\"overflow-x:hidden;border:0px;width:"+(_t.W-20)+"px;height:"+(_t.H-46)+"px\" marginwidth=\"1\" marginheight=\"1\" name=\""+_t.ID+"_ifr\" id=\""+_t.ID+"_ifr\"  frameborder=\"0\" scrolling=\"yes\"><\/iframe></div>");
				s+=("	<\/div>");
			s+=("<\/div>");
			E(_t.ID+"_out").innerHTML =s;
	}

	//Interface:showURL
	_t.showURL	= function(title,url,w,h,cevt){
		_t.show(title,url,null,w,h,cevt);
	}

	//Interface:showHTML
	_t.showHTML	= function(title,html,w,h,cevt){
		_t.show(title,null,html,w,h,cevt);
	}

	//Interface:showDialog
	_t.showDialog = function(icon,title,html,btns, w,h,cevt){
		var btstr="";
		var iconclass=["dialog_ico_err","dialog_ico_yes","dialog_ico_info","dialog_ico_ask","dialog_ico_stop"][icon];

		for(var i=0;i<btns.length;i++){
			var focusstr="";
			if(btns[i].focus){
				focusstr ="id=\"focus_button\"";
			}
			btstr+="<input type=\"button\" value=\""+btns[i].value+"\" onclick=\""+btns[i].onclick+"\" "+focusstr+" \/>";
		}

		var s="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"dialog_table\"><tr><td class=\"dialog_table_td\"><div class=\""+iconclass+"\"></div></td><td class=\"dialog_content\">"+html+"</td></tr></table>";
		s+="<div class=\"dialog_button\">"+btstr+"</div>";

		_t.show(title,null,s,w,h,cevt);

		//按钮焦点
		if(E("focus_button")){
			E("focus_button").focus();
		}
	}


	_t.show = function(title,url,html,w,h,cevt){
		if(w&&h){
			_t.setSize(w,h,false);
		}

		_t.showNum ++;
		_t.closeEvent = cevt;
		_t.title = title;
		_t.url = url;
		_t.html = html;

		_t.showDiv();
	}

	_t.showDiv = function(){
		_t.isShow = true;
		_t.refreshCover();
		_t.X = document.documentElement.scrollLeft;
		_t.Y = document.documentElement.scrollTop;

		var winW = document.documentElement.clientWidth;
		var winH = document.documentElement.clientHeight;

		_t.X += (winW - _t.W)/2;
		_t.Y += (winH - _t.H)/2;


		E(_t.ID).style.width = _t.W+"px";
		E(_t.ID).style.height = _t.H+"px";
		E(_t.ID).style.left = _t.X+"px";
		E(_t.ID).style.top = _t.Y+"px";
		E(_t.ID).style.display = "";

		E(_t.ID+"_title").innerHTML = _t.title;
		if(_t.html != null){
			setDisplays([_t.ID+"coverDiv",_t.ID+"_loading",_t.ID+"_iframe",_t.ID+"_ifr",_t.ID+"_html"],[true,false,false,false,true]);
			//E(_t.ID+"_ifr").src =_t.ifrBlank;
			//E(_t.ID+"_iframe").innerHTML ="";
			E(_t.ID+"_html").innerHTML = _t.html;
		}
		else if(_t.url != null){
			setDisplays([_t.ID+"coverDiv",_t.ID+"_loading",_t.ID+"_iframe",_t.ID+"_ifr",_t.ID+"_html"],[true,true,false,true,false]);
			E(_t.ID+"_html").innerHTML = "";
			E(_t.ID+"_ifr").src =_t.url;
			//E(_t.ID+"_iframe").innerHTML = "<iframe src=\""+_t.url+"\" onreadystatechange=\"popwin.IFrameStateChangeIE(this)\" onload=\"popwin.IFrameStateChangeFF(this)\" style=\"border:0px;width:"+(_t.W-20)+"px;height:"+(_t.H-46)+"px\" marginwidth=\"1\" marginheight=\"1\" name=\""+_t.ID+"_ifr\" id=\""+_t.ID+"_ifr\"  frameborder=\"0\" scrolling=\"auto\"><\/iframe>";

		}
	}

	_t.IFrameStateChangeIE = function(obj){
		if (obj.readyState=="interactive")		//state: loading ,interactive,   complete
		{
			setDisplays([_t.ID+"_loading",_t.ID+"_iframe"],[false,true]);
		}
	}

	_t.IFrameStateChangeFF = function(obj){
		setDisplays([_t.ID+"_loading",_t.ID+"_iframe"],[false,true]);
	}

	//Interface: close
	_t.close = function(){
		if(_t.closeEvent){
			eval(_t.closeEvent);
		}
		setDisplays([_t.ID+"coverDiv",_t.ID,_t.ID+"_html",_t.ID+"_loading",_t.ID+"_iframe"],[false,false,false,true,false]);
		E(_t.ID+"_html").innerHTML = "";
		E(_t.ID+"_title").innerHTML = "";
		//E(_t.ID+"_iframe").innerHTML ="";
		E(_t.ID+"_ifr").src = _t.ifrBlank;
		_t.isShow = false;
	}
	
	//Interface: loading
	_t.loading = function(){
		_t.isShow = true;
		_t.refreshCover();
		_t.X = document.documentElement.scrollLeft;
		_t.Y = document.documentElement.scrollTop;

		var winW = document.documentElement.clientWidth;
		var winH = document.documentElement.clientHeight;

		_t.X += (winW - 32)/2;
		_t.Y += (winH - 32)/2;

		E(_t.ID+"_ajaxloading").style.left = _t.X+"px";
		E(_t.ID+"_ajaxloading").style.top = _t.Y+"px";

		setDisplays([_t.ID+"coverDiv",_t.ID+"_ajaxloading"],[true,true]);

	}

	//Interface: loaded
	_t.loaded = function(){
		setDisplays([_t.ID+"coverDiv",_t.ID+"_ajaxloading"],[false,false]);
	}

	_t.refreshCover = function(){
		if(!_t.isShow)return;
		var nowHeight=_t.getBodyObj().scrollHeight;
		E(_t.ID+"coverDiv").style.height=(nowHeight*1)+"px";
		E(_t.ID+"coverFrame").style.height=(nowHeight*1)+"px";

		var nowWidth=_t.getBodyObj().scrollWidth;

		E(_t.ID+"coverDiv").style.width=(nowWidth*1)+"px";
		E(_t.ID+"coverFrame").style.width=(nowWidth*1)+"px";
	}

	_t.getBodyObj = function()
	{
		return (document.documentElement) ? document.documentElement : document.body;
	}

}

var popwin=new POPUP();
popwin.Init();
