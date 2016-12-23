var strengthStr_Arr;
try{
	strengthStr_Arr=[_SLANG.sc_pass_level1, _SLANG.sc_pass_level2, _SLANG.sc_pass_level3];
	pass_strength=_SLANG.sc_pass_strength;
}catch(err){
	pass_strength="密码强度";
	strengthStr_Arr=["弱", "中", "强"];
}

function CharMode(iN)
{
	if ( iN>=48 && iN<=57 )
	{
		return 1;
	}
    if ( iN>=65 && iN<=90 )
    {
		return 2;
	}
	if ( iN>=97 && iN<=122 )
	{
		return 4;
	}
    else
    {
		return 8;
	}
}

function bitTotal(num)
{
	modes = 0;
	for ( i=0; i<4; i++ )
    {
		if ( num & 1 )
		{
			modes++;
		}
		num >>>= 1;
	}
	return modes;
}

function checkStrong(sPW)
{
	if ( sPW.length <= 4 )
	{
		return 0;
	}
    Modes = 0;
	for ( i=0; i<sPW.length; i++ )
	{
		Modes |= CharMode(sPW.charCodeAt(i));
	}
	return bitTotal(Modes);
}

function pwStrength(pwd, div, minLen)
{
	O_color = "#eeeeee";
	L_color = "#FF0000";
	M_color = "#FF9900";
	H_color = "#33CC00";
	var strengthStr = "";
	if ( pwd==null || pwd=='' || pwd.length<minLen )
	{
		Lcolor = Mcolor = Hcolor = O_color;
	}
	else
	{
		S_level = checkStrong(pwd);
		if ( S_level<1 )
		{
			S_level = 1;
		}
		else if ( S_level>3 )
		{
			S_level = 3;
		}
		switch (S_level)
		{
			case 1:
				Lcolor = L_color;
				Mcolor = Hcolor = O_color;
				break;

            case 2:
				Lcolor = Mcolor = M_color;
				Hcolor = O_color;            
				break;

			case 3:
				Lcolor = Mcolor = Hcolor = H_color;                           
				break;
		}
		strengthStr = strengthStr_Arr[S_level-1];
	}
	if(strengthStr=="")strengthStr=strengthStr_Arr[0];
	document.getElementById(div+"_L").style.background = Lcolor;
	document.getElementById(div+"_M").style.background = Mcolor;
	document.getElementById(div+"_H").style.background = Hcolor;
	document.getElementById(div+"_Str").title = pass_strength+": "+strengthStr;
}



//是否为合法的Email格式
function isEmail(str)
{
	var myReg = /^[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]*)*@[a-zA-Z0-9\-]+([\.][a-zA-Z0-9\-]+)+$/;
	if ( myReg.test(str) )
		return true;
	return false;
}