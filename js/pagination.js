function PaginationClass(){
	this.pageNum=0;
	this.cPage=1;
	this.MIN = 1;
	this.MAX;
	this.FIRST;
	this.P = 4;
	this.E = 8;
	this.styleClass="Pagination";
	this.turnLength = function(i){
		var s = i;
		if(this.styleClass=="Pagination"){
			if(i<10){
				s="&nbsp;&nbsp;"+i+"&nbsp;&nbsp;";
			}else if(i<100){
				s="&nbsp;"+i+"&nbsp;";			
			}			
		}else{
			if(i<10){
				s="&nbsp;"+i+"&nbsp;";
			}else if(i<100){
				s=""+i+"";			
			}				
		}

		return s;
	}
	this.setPageConfig	=	function (ePageNum,allRecordNum,objname,choosemethod,styleid){
		this.ePageRecordNum=ePageNum;
		this.recordNum=allRecordNum;
		this.objName=objname;
		this.chooseMethod=choosemethod;
		if(styleid!=undefined && styleid=="Mini"){
			this.styleClass="PaginationMini";
		}
	}
	this.Init		=	function(){
								this.pageNum=0;
								this.cPage=1;
							}
	
	this.pageKeyPress	=	function (eventobject,obj){
								var eve=eventobject||window.event;
								var pagenum=obj.value;
								pagenum=pagenum.replace(/\D/g,'');
								if(pagenum==null||pagenum=="")return;
								if(pagenum>this.pageNum)pagenum=this.pageNum;
								if(eve.keyCode==13) {
									eval(this.chooseMethod+"("+pagenum+")");
								} 
							}	
	
	this.divisionInt	=	function (numA,numB){
								var modNum=numA%numB;
								var result=(numA-modNum)/numB;
								if(modNum>0)result++;
								
								return result;
							}
	this.MinOf =function(a, b){
		return a<b ? a : b;
	}
	
	this.isFirst = function(){
		return (this.cPage <= 1);
	}
	
	this.isLast = function(){
		return (this.cPage== this.pageNum);
	}
	
	this.getPageStr	=	function(){
									if(this.recordNum==0)return "";
									var pageStr = "";
									if (this.recordNum == 0) {
										return "";
									}
									pageStr = "<table cellpadding=0 cellspacing=0 border=0><tr><td><div class=\""+this.styleClass+"\">";
									this.pageNum =this.divisionInt(this.recordNum,this.ePageRecordNum);
									this.MAX = this.pageNum;
									if (this.cPage <= 0) {
										this.cPage = this.MIN;
									}
									if (this.cPage >this.MAX) {
										this.cPage = this.MAX;
									}
									
									if (this.cPage <= this.P) {
										this.FIRST = this.MIN;
									} else if (this.cPage >= this.MAX - this.E + this.P) {
										this.FIRST = this.MAX - this.E + 1;
									} else {
										this.FIRST = this.cPage - this.P + 1;
									}
									if(this.FIRST<this.MIN){
										this.FIRST = this.MIN;
									}
									if(this.MIN<this.FIRST){
										pageStr += "<a href=\"javascript:"+this.chooseMethod+"("+this.MIN+")\">"+this.turnLength(this.MIN)+"..</a>";
									}
									if(this.MIN < this.cPage){
										pageStr += "<a href=\"javascript:"+this.chooseMethod+"("+(this.cPage*1-1)+")\">&lt;&lt;</a>";			
									}
									
							
									for(var i=this.FIRST; i<=this.MinOf(this.FIRST+this.E-1,this.MAX);i++){
										if(i==this.cPage){
											pageStr += "<strong>"+this.turnLength(i)+"</strong>";				
										}else{
											pageStr += "<a href=\"javascript:"+this.chooseMethod+"("+i+")\">"+this.turnLength(i)+"</a>";
										}
									}
									
									if(this.MAX > this.cPage){
										pageStr += "<a href=\"javascript:"+this.chooseMethod+"("+(this.cPage*1+1)+")\">&gt;&gt;</a>";			
									}
									
									if(this.MAX>this.FIRST+this.E-1){
										pageStr += "<a href=\"javascript:"+this.chooseMethod+"("+this.MAX+")\">..."+this.turnLength(this.MAX)+"</a>";
									}
									
							
									pageStr += "</div></td><td class=\""+this.styleClass+"_td "+this.styleClass+"_inputtd\"><input class='"+this.styleClass+"_input' type=text value="+this.cPage+" id="+this.objName+"pageInput size=5 onkeypress='"+this.objName+".pageKeyPress(event,this)' /> Total:"+this.recordNum+"</td>" +
											"</tr></table>";
									return pageStr;
							}
						
} 

