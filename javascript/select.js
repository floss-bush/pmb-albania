// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: select.js,v 1.7 2009-01-07 10:46:12 kantin Exp $

function insertatcursor(myField, myValue) {
	if (document.selection) {
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
	} else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		myField.value = myField.value.substring(0, startPos)+ myValue+ myField.value.substring(endPos, myField.value.length);
	} else {
		myField.value += myValue;
	}
}

function getWindowHeight() {
    var windowHeight=0;
    if (typeof(window.innerHeight)=='number') {
        windowHeight=window.innerHeight;
    }
    else {
     if (document.documentElement&&
       document.documentElement.clientHeight) {
         windowHeight = document.documentElement.clientHeight;
    }
    else {
     if (document.body&&document.body.clientHeight) {
         windowHeight=document.body.clientHeight;
      }
     }
    }
    return windowHeight;
}

function getWindowWidth() {
    var windowWidth=0;
    if (typeof(window.innerWidth)=='number') {
        windowWidth=window.innerWidth;
    }
    else {
     if (document.documentElement&&
       document.documentElement.clientWidth) {
         windowWidth = document.documentElement.clientWidth;
    }
    else {
     if (document.body&&document.body.clientWidth) {
         windowWidth=document.body.clientWidth
      }
     }
    }
    return windowWidth;
}

function show_frame(url) {
	var att=document.getElementById("att");
	var notice_view=document.createElement("iframe");
	notice_view.setAttribute('id','frame_notice_preview');
	notice_view.setAttribute('name','notice_preview');
	notice_view.src=url; 
	notice_view.style.visibility="hidden";
	notice_view.style.display="block";
	notice_view=att.appendChild(notice_view);
	w=notice_view.clientWidth;
	h=notice_view.clientHeight;
	posx=(getWindowWidth()/2-(w/2))<0?0:(getWindowWidth()/2-(w/2))
	posy=(getWindowHeight()/2-(h/2))<0?0:(getWindowHeight()/2-(h/2));
	notice_view.style.left=posx+"px";
	notice_view.style.top=posy+"px";
	notice_view.style.visibility="visible";
	document.onmousedown=clic;
}

function clic(e){
  	if (!e) var e=window.event;
	if (e.stopPropagation) {
		e.preventDefault();
		e.stopPropagation();
	} else { 
		e.cancelBubble=true;
		e.returnValue=false;
	}
  	kill_frame();
  	document.onmousedown='';
}

function kill_frame() {
	var notice_view=document.getElementById("frame_notice_preview");
	notice_view.parentNode.removeChild(notice_view);	
}