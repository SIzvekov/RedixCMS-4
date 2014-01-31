// to use like this: <a href="..." target="blank" onclick="showdialog('dialog','{ajax-page-title}','{get-params-send-to-ajax-page}');return false;">
function showdialog(dialogclassname,ajaxpage,ajaxuri,ajaxsendform,padbgcolor,padopacity)
{
	if(document.getElementById('rx_bgpad')) {alert('Error, Element \'rx_bgpad\' already exists');return false;}
	if(document.getElementById('rx_dialog')) {alert('Error, Element \'rx_dialog\' already exists');return false;}
	if(document.getElementById('rx_dialog_closebut')) {alert('Error, Element \'rx_dialog_closebut\' already exists');return false;}
	if(!padbgcolor) padbgcolor = '#000';
	if(!padopacity) padopacity = '0.7';

	var bgpad = document.createElement("div");
	bgpad.style.position = 'fixed';
	bgpad.style.zIndex = '10000';
	bgpad.style.top = '0';
	bgpad.style.left = '0';
	bgpad.style.height = '100%';
	bgpad.style.width = '100%';
	bgpad.style.backgroundColor = padbgcolor;
	bgpad.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=25)';
	bgpad.style.MozOpacity = padopacity;
	bgpad.style.KhtmlOpacity = padopacity;
	bgpad.style.opacity = padopacity;

	bgpad.id = 'rx_bgpad';
	bgpad.onclick = function(){closedialog();}
	document.body.appendChild(bgpad);

	var dialog = document.createElement("div");
	dialog.className = dialogclassname;
	dialog.style.zIndex = '10001';
	dialog.style.position = 'absolute';
	dialog.id = 'rx_dialog';
	dialog.innerHTML = '<img src="/templates/_common_images/loader.gif" alt="" border="0" width="16" height="16"/>';
	document.body.appendChild(dialog);	
	dialog.style.top = parseInt(dialog.offsetTop+getBodyScrollTop())+'px';

	var closebut = document.createElement("div");
	closebut.id = 'rx_dialog_closebut';
	closebut.style.zIndex = '10002';
	closebut.style.position = 'absolute';
	closebut.style.top = (dialog.offsetTop-10)+'px';
	closebut.style.left = (dialog.offsetWidth+dialog.offsetLeft-20)+'px';
	closebut.style.background = 'url(\'/templates/_common_images/close.png\') no-repeat left top';
	closebut.style.width = '30px';
	closebut.style.height = '30px';
	closebut.style.margin = '0px';
	closebut.style.padding = '0px';
	closebut.style.cursor = 'pointer';
	closebut.innerHTML = '&nbsp;';
	closebut.onclick = function(){closedialog();}
	document.body.appendChild(closebut);


	loadXMLDoc('/ajax-index.php?page='+ajaxpage+'&'+ajaxuri,'rx_dialog',ajaxsendform);

}
function closedialog()
{
	document.body.removeChild(document.getElementById('rx_bgpad'));
	document.body.removeChild(document.getElementById('rx_dialog'));
	document.body.removeChild(document.getElementById('rx_dialog_closebut'));
}
function getClientHeight(){return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight;}
function getClientWidth(){return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth;}
function getBodyScrollTop(){return self.pageYOffset || (document.documentElement && document.documentElement.scrollTop) || (document.body && document.body.scrollTop);}
function getBodyScrollLeft(){return self.pageXOffset || (document.documentElement && document.documentElement.scrollLeft) || (document.body && document.body.scrollLeft);}