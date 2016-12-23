/*
	A simple class for displaying file information and progress
	Note: This is a demonstration only and not part of SWFUpload.
	Note: Some have had problems adapting this class in IE7. It may not be suitable for your application.
*/

// Constructor
// file is a SWFUpload file object
// targetID is the HTML element id attribute that the FileProgress HTML structure will be added to.
// Instantiating a new FileProgress object with an existing file will reuse/update the existing DOM elements
function FileProgress(file, targetID, appendType) {
	this.fileProgressID = file.id;
	this.opacity = 100;
	this.height = 0;
	this.appendType=(appendType==null||appendType==undefined)?'insertBefore':'appendChild';

	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	if (!this.fileProgressWrapper) {
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.className = "progressWrapper";
		this.fileProgressWrapper.id = this.fileProgressID;

		this.fileProgressElement = document.createElement("div");
		this.fileProgressElement.className = "progressContainer";

		var progressCancel = document.createElement("div");
		progressCancel.className = "progressCancel";
		progressCancel.innerHTML = "";

		var progressText = document.createElement("div");
		progressText.className = "progressName";
		progressText.innerHTML = file.name;

		var progressSize = document.createElement("div");
		progressSize.className = "progressSize";
		progressSize.appendChild(document.createTextNode(parseInt(file.size/1024)+" KB"));

		var progressStatus = document.createElement("div");
		progressStatus.className = "progressBarStatus";
		progressStatus.innerHTML = "&nbsp;";

		var progressX = document.createElement("div");

		var progressBar = document.createElement("div");
		progressBar.className = "progressBarInProgress";

		this.fileProgressElement.appendChild(progressCancel);
		this.fileProgressElement.appendChild(progressText);
		this.fileProgressElement.appendChild(progressSize);
		this.fileProgressElement.appendChild(progressStatus);
		this.fileProgressElement.appendChild(progressX);
		this.fileProgressElement.appendChild(progressBar);

		this.fileProgressWrapper.appendChild(this.fileProgressElement);
		var tagele=document.getElementById(targetID);

		if(this.appendType=='appendChild'){
			tagele.appendChild(this.fileProgressWrapper);
		}else{
			tagele.insertBefore(this.fileProgressWrapper,tagele.firstChild);
		}
	} else {
		this.fileProgressElement = this.fileProgressWrapper.firstChild;
	}

	this.height = this.fileProgressWrapper.offsetHeight;

}
FileProgress.prototype.setProgress = function (percentage) {
	this.fileProgressElement.className = "progressContainer green";
	this.fileProgressElement.childNodes[5].className = "progressBarInProgress";
	this.fileProgressElement.childNodes[5].style.width = percentage + "%";
};
FileProgress.prototype.setComplete = function (file) {
	this.fileProgressElement.className = "progressContainer blue";
	this.fileProgressElement.childNodes[5].className = "progressBarComplete";
	this.fileProgressElement.childNodes[5].style.width = "";
	if(file.fileid){
		this.fileProgressElement.childNodes[1].innerHTML ="<a href='javascript:useFile(\""+file.fileid+"\",\""+file.name+"\",\""+file.isimg+"\")'>"+file.name+"</a>";
		this.fileProgressElement.childNodes[4].className = "progressX";
		this.fileProgressElement.childNodes[4].innerHTML = "<a title='Delete' href='javascript:delFile(\""+file.id+"\",\""+file.fileid+"\",\""+file.md5+"\")'></a>";
	}
};
FileProgress.prototype.setError = function () {
	this.fileProgressElement.className = "progressContainer red";
	this.fileProgressElement.childNodes[5].className = "progressBarError";
	this.fileProgressElement.childNodes[5].style.width = "";
};
FileProgress.prototype.setCancelled = function () {
	this.fileProgressElement.className = "progressContainer";
	this.fileProgressElement.childNodes[5].className = "progressBarError";
	this.fileProgressElement.childNodes[5].style.width = "";
};
FileProgress.prototype.setStatus = function (status) {
	this.fileProgressElement.childNodes[3].innerHTML = status;
};

// Show/Hide the cancel button
FileProgress.prototype.toggleCancel = function (show, swfUploadInstance) {
	/*
	this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (swfUploadInstance) {
		var fileID = this.fileProgressID;
		this.fileProgressElement.childNodes[0].onclick = function () {
			swfUploadInstance.cancelUpload(fileID);
			return false;
		};
	}
	*/
};
