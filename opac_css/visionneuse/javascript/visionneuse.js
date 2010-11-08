// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: visionneuse.js,v 1.6 2010-07-08 15:28:34 arenou Exp $

function open_visionneuse(callbackFunction){
	callback = function(){
		return callbackFunction();
	}
	
	var visionneuse = document.createElement('div');
	visionneuse.setAttribute('id','visionneuse');
	document.getElementsByTagName('body')[0].appendChild(visionneuse);
	visionneuse.setAttribute('style','position:absolute;left:0;z-index:9000');
	visionneuse.style.top=window.scrollY+'px';	
	visionneuse.style.width=window.innerWidth+'px';
	visionneuse.style.height=window.innerHeight+'px';
	
	var background = document.createElement('div');
	background.setAttribute('id','visionneuseBackground');
	background.setAttribute('style','background-color:gray;-moz-opacity:0.5;opacity:0.5;filter:alpha(opacity=85);width:100%;height:100%;position:absolute;top:0;left:0;z-index:9001');
	visionneuse.appendChild(background);
	
	var iframe = document.createElement('iframe');
	iframe.setAttribute('style','overflow:hidden;background-color:white;position:absolute;z-index:9002;width:60%;height:80%;left:20%;top:8%');		
	iframe.setAttribute('name','visionneuse');
	iframe.setAttribute('id','visionneuseIframe');
	iframe.setAttribute('src','');
	visionneuse.appendChild(iframe);
	
	visionneuse.parentNode.style.overflow = "hidden";
		
	callback();
}

window.onresize = function(){
	var visionneuse = document.getElementById('visionneuse');
	visionneuse.style.width=window.innerWidth+'px';
	visionneuse.style.height=window.innerHeight+'px';
	visionneuse.style.top=window.scrollY+'px';	
}