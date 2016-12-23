			
	function initMouseEvent(targetDivName){
		try{
			var divObjs =document.getElementsByTagName('div');
			for(var i=0; i<divObjs.length; i++)
			{
				if(divObjs[i].getAttribute("name") == targetDivName)
				{
					(
						function (obj)
						{
							obj.onmouseover = function(){
								onMouseOver(obj);
							}
							obj.onmouseout = function(){
								onMouseOut(obj);
							}
							obj.onclick = function(){
								//onMouseClick(obj);
							}
						}
					)(divObjs[i]);
					
				}
			}
		}catch(err){
			//alert(err);
		}
	}

	function onMouseOver(obj){
		obj.className="proimg colorborder";
	}
	function onMouseOut(obj){
		obj.className="proimg";
	}

	var curSmallImg=null;
	function initSmallImgEvent(){
		try{
			var divObjs =document.getElementById('smalldiv').childNodes;
			var runedflag=false;
			for(var i=0; i<divObjs.length; i++)
			{
				if(divObjs[i].nodeName=='LI'){
					if(!runedflag){smallImgMouseOver(divObjs[i]);runedflag=true;}
					(function(obj){
						obj.onmouseover = function(){
							smallImgMouseOver(obj);
						 }
						obj.onmouseout = function(){
						  //obj.className="";
						 }
					})(divObjs[i]);
					
				}
			}
		}catch(err){
			//alert(err);
		}
	}
	
	function smallImgMouseOver (obj) {
		if(curSmallImg==obj)return;
		if(curSmallImg){curSmallImg.className='';}
		curSmallImg=obj;
		obj.className="p2";
		var imgpath=obj.id.replace("liimg_","");
		imgpathlink=imgpath.replace('/thumb/','/attachment/');
		document.getElementById("biginner").innerHTML="<img src=\""+imgpathlink+"\" jqimg=\""+imgpathlink+"\" onload=\"if(this.width>250)this.width=250;\" />";
	}