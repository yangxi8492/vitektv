
////////////////////////////
////Tabs
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Tabs()
	{
		this.nowTab = "";
		this.needSelect = true;
		this.tabidsAry = new Array();
		this.tabvalsAry = new Array();
		this.classpre = "tab_";
		this.container;
		this.defaultTab = "";
		this.createTab = function (id,title,value,type)
		{
			var _type = 0;
			
			if(type)
				_type = 1;
			
			if(value == null)
				value = "";
							
			this.tabidsAry.push(id);	
			this.tabvalsAry.push(value);
			
			var nid="tab_"+id;
			var rstr="<div id="+nid+" class=\""+this.classpre+_type+"_all\"><input type='hidden' id='Val_"+id+"' value='"+value+"' /><div id="+id+"_1 class=\""+this.classpre+_type+"_left\"></div><div id="+id+"_2 class=\""+this.classpre+_type+"_center\"><span id='Title_"+id+"' >"+title+"</span></div><div id="+id+"_3 class=\""+this.classpre+_type+"_right\"></div></div>";
			if(this.container==undefined){
				document.write(rstr);
			}else{
				document.getElementById(this.container).innerHTML += rstr;
			}

			
			
			if(type)
			{
				this.nowTab=id;
			}
		}
		this.initTab = function ()
		{
			for(var i=0;i<this.tabidsAry.length;i++)
			{
				(
					function(tab  ,id )
					{
						var nid="tab_"+id;
						document.getElementById(nid).onclick = function ()
						{
							tab.clickTab(id);
							tab.changeTab();
						}
					}
				)
				(this , this.tabidsAry[i]);
			}
			
			if(this.defaultTab != "")
			{
				var nid = this.getIdByValue(this.defaultTab);
				this.changeTabCss(nid);
				this.nowTab = nid;
				this.changeTab();
			}
			
			this.init();
		}
		
		this.getIdByValue = function (val)
		{
			for(var i=0;i<this.tabvalsAry.length;i++)
			{
				if(this.tabvalsAry[i] == val)
				{
					return this.tabidsAry[i];
				}
			}
			
			return "";
		}
		
		this.getValue = function ()
		{
			try
			{
				return document.getElementById("Val_"+this.nowTab).value;
			}
			catch(err)
			{
				return "";
			}
		}
		this.setValue = function (id,value)
		{
			try
			{
				document.getElementById("Val_"+this.nowTab).value = value;
			}
			catch(err)
			{}
		}
		this.setTitle = function (id,title)
		{
			try
			{
				document.getElementById("Title_"+id).innerHTML = title;
			}
			catch(err)
			{}
		}
		this.clickNowTab = function ()
		{
			this.click(this.nowTab);
		}
		this.click = function (id)
		{
			this.clickTab(id);
			this.changeTab();
		}
		this.changeTabCss = function (id)
		{
			if(this.nowTab != id)
			{
				try
				{
					document.getElementById(this.nowTab+"_1").className= this.classpre+"0_left"; //'tab_1_1_'+this.styleID;
					document.getElementById(this.nowTab+"_2").className= this.classpre+"0_center"; //'tab_1_2_'+this.styleID;
					document.getElementById(this.nowTab+"_3").className= this.classpre+"0_right"; //'tab_1_3_'+this.styleID;
				}catch(err)
				{}
				
				document.getElementById(id+"_1").className= this.classpre+"1_left";
				document.getElementById(id+"_2").className= this.classpre+"1_center";
				document.getElementById(id+"_3").className= this.classpre+"1_right";
			}
			else
			{
				/*
				if(!this.needSelect)
				{
					var selectVal = 1;
					if(document.getElementById(this.nowTab+"_1").className == 'tab_1_1_'+this.styleID) 
					{
						selectVal = 2; // change to selecting
					}
					
					try
					{
						document.getElementById(this.nowTab+"_1").className='tab_'+selectVal+'_1_'+this.styleID;
						document.getElementById(this.nowTab+"_2").className='tab_'+selectVal+'_2_'+this.styleID;
						document.getElementById(this.nowTab+"_3").className='tab_'+selectVal+'_3_'+this.styleID;
					}catch(err)
					{}
				
				}
				*/
			}
		}
		this.clickTab = function (id)
		{
			this.changeTabCss(id);
			this.nowTab = id ;
			this.onclick();
		}
		this.changeTab = function ()
		{
			for(var i=0;i<this.tabidsAry.length;i++)
			{
				try
				{
					document.getElementById(this.tabidsAry[i]).style.display = this.tabidsAry[i] != this.nowTab ? "none" : "";
				}
				catch(err)
				{}
			}
		}
		
		this.init = function (){} // please override
		this.onclick = function (){} // please override
		
	}

//------------------------------------------Tabs ------------------------------------------//
	