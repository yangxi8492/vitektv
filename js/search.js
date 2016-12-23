function checkSearchKeyPressEnter(eventobject,objid){var eve=eventobject||window.event;if(eve.keyCode==13) {searchproducts(objid);}}
function searchproducts(objid){objid=objid==undefined?"keyword":objid;window.location.href = "search.php?k="+urlEncode(E(objid).value);}
function search_InitPage(objid){objid=objid==undefined?"keyword":objid;if(E(objid)){E(objid).onkeyup = function(event){checkSearchKeyPressEnter(event,objid);};}}
$(document).ready(function(e){search_InitPage();});
