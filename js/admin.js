function selectAll(formid,b){
	var oForm=E(formid);
	for (var i = 0; i < oForm.elements.length; i++) {
		if(oForm.elements[i].type=="checkbox"&&!oForm.elements[i].disabled){
			oForm.elements[i].checked = b;
		}
	}
}

function reloadTop(url){
	url=encodeURIComponent(url);
	top.location.href="index.php?frame="+url;
}

function reloadSelf(url){
	self.location.href=url;
}

//判断是否图片文件名
function isImg(filename){
	var imgext=['jpeg','jpg','gif','png','bmp'];
	filename=filename.toLowerCase();
	var ext=filename.substring(filename.length-3,filename.length);
	for(var i=0; i<imgext.length; i++){
		if(ext==imgext[i]){
			return true;
		}
	}
	return false;
}