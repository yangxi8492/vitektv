document.writeln("<div id='DateGird' style='display:none;position:absolute;left:0px; top:0px; z-index:600001;border:1px solid #e0e0e0;background-color:  #F08B09;'></div>");
var Glob_YY=parseInt(new Date().getFullYear());
var Glob_MM=parseInt(new Date().getMonth()+1);
var Glob_DD=parseInt(new Date().getDate());

var dateCoverDiv = "";
	dateCoverDiv+="<div id='dateCoverDiv' class='coverDivClear' style='display:none; z-index:600000;' >";
		dateCoverDiv+="<iframe id='dateCoverFrame' class='coverFrame' border='0' frameborder='0' src='about:blank'></iframe>";
	dateCoverDiv+="</div>";	
document.writeln(dateCoverDiv);
function shotable(sObj)
{         
        var DateArray=["7","1","2","3","4","5","6"];
        var output="";
        output=output+"<div style='padding:5px;border-top:1px solid #f4f4f4;border-left:1px solid #f4f4f4;'><table style='width:165px;font-size:12px;cursor:default;border:0px solid #999999;' border='0' cellpadding='0' cellspacing='0'>";
        output=output+"<tr><td colspan='7' class='TrTitle'><span ID='yearUU'>"+Glob_YY+"</span><span ID='monthUU'>"+Glob_MM+"</span></td></tr><table>";
        output=output+"<table style='width:165px;font-size:12px;font-family: \"verdana\", Helvetica, sans-serif;cursor:default;border:0px solid #999999;border-top:1px solid #404040;border-left:1px solid #404040;border-right:1px solid #efefef;border-bottom:1px solid #efefef;' border='1' cellpadding='0' cellspacing='0'>";
        output=output+"<tr align='center'>";
        for(var i=0;i<7;i++)        output=output+"<td class='TrOver'>"+DateArray[i]+"</td>";
        output=output+"</tr>";
        for(var i=0;i<6;i++){
        output=output+"<tr align='center'>";
                for(var j=0;j<7;j++)        
						output=output+"<td id='TD' name='TD' class='TdOver' onmouseover='choosedate.OverBK(this,\""+sObj.id+"\")' msg=''> </td>";
                        output=output+"</tr>";
                } 
        output=output+"</tabe></div>";

  var selectMMInnerHTML = "<select ID=\"sMonth\" onchange=\"setPan(document.getElementById('sYear').value,this.value)\" style='width:50px;'>";
  for (var i = 1; i <  13; i++)
  {
    if (i == Glob_MM)
       {selectMMInnerHTML += "<option Author=wayx value='" + i + "' selected>" + i + "" + "</option>\r\n";}
    else {selectMMInnerHTML += "<option Author=wayx value='" + i + "'>" + i + "" + "</option>\r\n";}
  }
  selectMMInnerHTML += "</select> ";
  var selectYYInnerHTML = "<select ID=\"sYear\" style=\"width:80px;\"  onchange=\"setPan(this.value,document.getElementById('sMonth').value)\" style='width:65px;'>";
  for (var i = 1900; i <=  Glob_YY+1; i++)
  {
    if (i == Glob_YY)
       {selectYYInnerHTML += "<option Author=wayx value='" + i + "' selected>" + i + "" + "</option>\r\n";}
    else {selectYYInnerHTML += "<option Author=wayx value='" + i + "'>" + i + "" + "</option>\r\n";}
  }
  selectYYInnerHTML += "</select> ";
        document.getElementById("DateGird").innerHTML= "<div style='height:20px;width:180px;text-align:right;padding-top:5px;clear:both;'><span style='cursor:pointer;color:#ffffff;font-weight:bold;' onclick='hiddenT()'>[X]&nbsp;</span></div>"+output;
        document.getElementById("monthUU").innerHTML= selectMMInnerHTML;
        document.getElementById("yearUU").innerHTML= selectYYInnerHTML;
        //document.writeln(output);
}
function classGetDate(sObj)
{
this.obj=sObj || "uncDate";
this.YY=Glob_YY;
this.MM=Glob_MM;
this.DD=Glob_DD;
document.getElementById("DateGird").style.display="";
setPan(this.YY,this.MM);
}        

function GetDay(y,m){
        this.TDate=function(){
                this.DayArray=[];
                for(var i=0;i<42;i++)this.DayArray[i]="&nbsp;";
                for(var i=0;i<new Date(y,m,0).getDate();i++)this.DayArray[i+new Date(y,m-1,1).getDay()]=i+1;
                return this.DayArray;
                }
        return this;
        }

function setPan(YY,MM)
{
var DArray=GetDay(YY,MM).TDate();
var TDArr=document.getElementsByName("TD");
if (MM<10){var showMM="0"+MM;}else{var showMM=MM;}
for(var i=0;i<TDArr.length;i++){
        if (Glob_DD==DArray[i]&&YY==new Date().getFullYear()&&MM==new Date().getMonth()+1){TDArr[i].className="TdOut";}else{TDArr[i].className="TdOver"}
        TDArr[i].innerHTML=DArray[i]; 
        if (DArray[i]<10){var showDD="0"+DArray[i];}else{var showDD=DArray[i];}
        TDArr[i].msg=YY+"-"+showMM+"-"+showDD;
        }
}

choosedate={
		obj:null,
        dfd:function (sObj,sPosition)
        {
        	obj = sObj;
        var dateGirdObj=document.getElementById("DateGird");
        //var i= sObj.style.top
		{
			if(sPosition==undefined){
	       		dateGirdObj.style.top=cmGetY(sObj)+20+"px";
	       		dateGirdObj.style.left=cmGetX(sObj)+"px";
	 		}else if(sPosition=="lefttop"){
	         	dateGirdObj.style.top=cmGetY(sObj)-192+"px";
	        	dateGirdObj.style.left=cmGetX(sObj)-100+"px";		
	        }else{
	         	dateGirdObj.style.top=cmGetY(sObj)-192+"px";
	        	dateGirdObj.style.left=cmGetX(sObj)+"px";		
	        }
        }
        
        shotable(sObj);
        showDateCoverDiv();
        classGetDate(sObj);
        },
        OverBK:function(t,m){
                
                if(t.className!="TdOut"){
                        
                        t.onmouseout=function(){t.className="TdOver";}
                }
                if(t.innerHTML!="&nbsp;")t.className="TdOut";
                
                (
                	function (t,obj)
                	{
		                t.onclick=function(){
		                        if (t.innerHTML!="&nbsp;"){
		                              	obj.value=t.msg;
		                                t.className="TdOver";
		                                //document.getElementById("DateGird").style.display="none";
		                                hiddenT();
		                        }
		                }
                	}
                )(t , obj);
        }
}

function showDateCoverDiv()
{
	try
	{
		var nowHeight=getBodyObj().scrollHeight;
		E("dateCoverDiv").style.height=(nowHeight*1)+"px";
		E("dateCoverFrame").style.height=(nowHeight*1)+"px";	
		var nowWidth=getBodyObj().scrollWidth;
		E("dateCoverDiv").style.width=(nowWidth*1)+"px";
		E("dateCoverFrame").style.width=(nowWidth*1)+"px";
		E("dateCoverDiv").style.display="";
	}
	catch(err)
	{
	}
}

function hiddenT(){
  	document.getElementById("DateGird").style.display="none";
  	E("dateCoverDiv").style.display="none";	
  }
function cmGetX (obj){var x = 0;do{x += obj.offsetLeft;obj = obj.offsetParent;}while(obj);return x;} 
function cmGetY (obj){var y = 0;do{y += obj.offsetTop;obj = obj.offsetParent;}while(obj);return y;}