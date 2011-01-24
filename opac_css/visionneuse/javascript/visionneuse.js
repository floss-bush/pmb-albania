// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: visionneuse.js,v 1.11 2011-01-06 14:21:05 arenou Exp $

function open_visionneuse(callbackFunction,explnum_id){
	callback = function(){
		return callbackFunction(explnum_id);
	}
	var visionneuse = document.createElement('div');
	visionneuse.setAttribute('id','visionneuse');
	document.getElementsByTagName('body')[0].appendChild(visionneuse);
	visionneuse.setAttribute('style','position:absolute;left:0;z-index:9000');
	visionneuse.setAttribute('onclick','close_visionneuse();');
	visionneuse.style.top=getWindowScrollY();
	visionneuse.style.width="100%";
	visionneuse.style.height="100%";
	
	var background = document.createElement('div');
	background.setAttribute('id','visionneuseBackground');
	visionneuse.appendChild(background);
	
	var iframe = document.createElement('iframe');
	iframe.setAttribute('style','overflow:hidden;background-color:white;position:absolute;z-index:9002;left:20%;top:8%');		
	iframe.setAttribute("width","60%");
	iframe.setAttribute("height","80%");
	iframe.setAttribute('name','visionneuse');
	iframe.setAttribute('id','visionneuseIframe');
	iframe.setAttribute('src','');
	visionneuse.appendChild(iframe);
	
	visionneuse.parentNode.style.overflow = "hidden";
		
	callback();
}

window.onresize = function(){
	var visionneuse = document.getElementById('visionneuse');
	if (visionneuse){
		visionneuse.style.width=getWindowWidth()+'px';
		visionneuse.style.height=getWindowHeight()+'px';
		visionneuse.style.top=getWindowScrollY()+'px';
	}	
}

function close_visionneuse(){
	var visionneuse =window.parent.document.getElementById('visionneuse');
	visionneuse.parentNode.style.overflow = '';	
	visionneuse.parentNode.removeChild(visionneuse);
	document.form_values.target='';
	document.form_values.action=oldAction;
}

function getWindowHeight(){
	if(window.innerHeight) 
		return window.innerHeight+"px";
	else return document.body.clientHeight;
}

function getWindowWidth(){
	if(window.innerWidth) 
		return window.innerWidth+"px";
	else return document.body.clientWidth;
}

function getWindowScrollY(){
	if(window.scrollY)
		return window.scrollY+"px";
	else return document.documentElement.scrollTop;
}