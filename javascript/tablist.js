// gestion des listes "collapsibles" en Javascript
// $Id: tablist.js,v 1.11 2008-08-05 14:16:07 touraine37 Exp $

var imgOpened = new Image();
imgOpened.src = './images/minus.gif';
var imgClosed = new Image();
imgClosed.src = './images/plus.gif';
var expandedDb = '';

// on regarde si le client est DOM-compliant

var isDOM = (typeof(document.getElementsByTagName) != 'undefined') ? 1 : 0;

//Konqueror (support DOM partiel) : on rejette
if(isDOM && typeof(navigator.userAgent) != 'undefined') {
    var browserName = ' ' + navigator.userAgent.toLowerCase();
    if(browserName.indexOf('konqueror') > 0) {
        isDOM = 0;
    }
}

function changeCoverImage(elt) {
	imgs=elt.getElementsByTagName('img');
	for (i=0; i < imgs.length; i++) {
		img=imgs[i];		
		isbn=img.getAttribute('isbn');
		vigurl=img.getAttribute('vigurl');
		url_image=img.getAttribute('url_image');
		if (vigurl) {
			if (img.src.substring(img.src.length-8,img.src.length)=='vide.png') {
				img.src=vigurl;
			}
		} else if (isbn) {
			if (img.src.substring(img.src.length-8,img.src.length)=='vide.png') {
				img.src=url_image.replace(/!!noticecode!!/,isbn);
			}
		}
	}
}

function expandAll_ajax_callback(text,el) {
	var whichEl = document.getElementById(el + 'Child');
  	whichEl.innerHTML = text ;
 }
 
function expandAll_ajax_callback_error(status,text,el) {
 }
 
function expandAll_ajax_callback_block(text,el) {
	var res=text.split("|*|*|");
	
	for(var i = 0; i < res.length; i++){
		var res_notice=res[i].split("|*|");
		if(res_notice[0] &&  res_notice[1]) {
			var whichEl = document.getElementById('el' + res_notice[0] + 'Child');
	  		whichEl.innerHTML = res_notice[1] ;
  		}
	}
	

 }
 
function expandAll_ajax_callback_block_error(status,text,el) {
 }
 
function expandAll_ajax(start) {
	var tempColl_img    = document.getElementsByTagName('IMG');
	var tempColl    = document.getElementsByTagName('DIV');
	var liste_id='';
	var display_cmd='';
	var nb_to_send=0;
	var nb=0;
	if (!start)start=0;
	for (var i =start; i < tempColl.length; i++) {
 		if ((tempColl[i].className == 'notice-child') || (tempColl[i].className == 'child')) {
     		tempColl[i].style.display = 'block';
     		nb++;
     		if(nb >5){
     			setTimeout('expandAll_ajax('+i+')',0);
     			return;
     		}	
     	}
	    changeCoverImage(tempColl[i]);	    
  	} 	
	for (var i = 0; i < tempColl_img.length; i++) {
    	if(tempColl_img[i].name == 'imEx') {
			tempColl_img[i].src = imgOpened.src;
		}  		
		if(tempColl_img[i].name == 'imEx') {
			var obj_id=tempColl_img[i].getAttribute('id');
	 		var el=obj_id.replace(/Img/,'');
	 	
	 		if(!expand_state[el]) {
	    		var mono_display_cmd= tempColl_img[i].getAttribute('param');
	    		expand_state[el]=1;
	    		
	    		if(mono_display_cmd) {
	    			nb_to_send++;
	    			document.getElementById(el + 'Child').innerHTML = "<div style='width:100%; height:30px;text-align:center'><img style='padding 0 auto;' src='./images/patience.gif' id='collapseall' border='0'></div>";
	    			display_cmd+=mono_display_cmd;
	    			if (i<(tempColl_img.length -1))display_cmd+='|*|*|';
	    			if(nb_to_send>40) {
	    				setTimeout('expandAll_ajax_block_suite(\'display_cmd='+display_cmd+'\')',0);
	    				display_cmd='';
	    				nb_to_send=0;
	    			}	
				}
			}    
    	}
	} 
	if(nb_to_send)setTimeout('expandAll_ajax_block_suite(\'display_cmd='+display_cmd+'\')',0);
}

function expandAll_ajax_block_suite(post_data ) {
	// On initialise la classe:
	var req = new http_request();
	//	alert( post_data);
	// Exécution de la requette (url, post_flag ,post_param, async_flag, func_return, func_error) 
	req.request("./ajax.php?module=catalog&categ=expand_block",1,post_data,1,expandAll_ajax_callback_block,expandAll_ajax_callback_block_error);
} 

function expandAll_ajax_suite(post_data,el ) {
	// On initialise la classe:
	var req = new http_request();
	// Exécution de la requette (url, post_flag ,post_param, async_flag, func_return, func_error) 
	req.request("./ajax.php?module=catalog&categ=expand",1,post_data,1,expandAll_ajax_callback,expandAll_ajax_callback_error,el);
}

function expandAll() {
  var tempColl    = document.getElementsByTagName('DIV');
  var tempCollCnt = tempColl.length;
  for (var i = 0; i < tempCollCnt; i++) {
     if ((tempColl[i].className == 'notice-child') || (tempColl[i].className == 'child'))
     tempColl[i].style.display = 'block';
     changeCoverImage(tempColl[i]);
  }
  tempColl    = document.getElementsByTagName('IMG');
  tempCollCnt = tempColl.length;
  for (var i = 0; i < tempCollCnt; i++) {
     if(tempColl[i].name == 'imEx') {
       tempColl[i].src = imgOpened.src;
     }
  }
}

function collapseAll() {
  var tempColl    = document.getElementsByTagName('DIV');
  var tempCollCnt = tempColl.length;
  for (var i = 0; i < tempCollCnt; i++) {
     if ((tempColl[i].className == 'notice-child') || (tempColl[i].className == 'child'))
     tempColl[i].style.display = 'none';
  }
  tempColl    = document.getElementsByTagName('IMG');
  tempCollCnt = tempColl.length;
  for (var i = 0; i < tempCollCnt; i++) {
     if(tempColl[i].name == 'imEx') {
       tempColl[i].src = imgClosed.src;
     }
  }
}

function initIt() {
  if (!isDOM) {
//    alert("ce navigateur n'est pas compatible avec le DOM.");
    return;
  }
  var tempColl    = document.getElementsByTagName('DIV');
  var tempCollCnt = tempColl.length;
  for (var i = 0; i < tempCollCnt; i++) {
     if ((tempColl[i].className == 'notice-child') || (tempColl[i].className == 'child')) {
     	if (tempColl[i].getAttribute('startOpen') == 'Yes' ) {
     		expandBase (tempColl[i].id.substring(0,tempColl[i].id.indexOf('Child')), true);
     	   } else tempColl[i].style.display = 'none';
       }
  }
} // end of the 'initIt()' function

var expand_state=new Array();

function expandBase_ajax(el, unexpand,	mono_display_cmd) {
  if (!isDOM)
    return;

  var whichEl = document.getElementById(el + 'Child');
  var whichIm = document.getElementById(el + 'Img');
  if (whichEl.style.display == 'none' && whichIm) {
   	whichEl.style.display  = 'block';
    whichIm.src            = imgOpened.src;
 
    changeCoverImage(whichEl);     
    if(!expand_state[el]) {
    	whichEl.innerHTML =  "<div style='width:100%; height:30px;text-align:center'><img style='padding 0 auto;' src='./images/patience.gif' id='collapseall' border='0'></div>" ;
	    var url= "./ajax.php?module=catalog&categ=expand";	 
		// On initialise la classe:
			    var url= "./ajax.php?module=catalog&categ=expand";	 
				// On initialise la classe:
				var req = new http_request();
				// Exécution de la requette (url, post_flag ,post_param, async_flag, func_return, func_error) 
				req.request(url,1,'mono_display_cmd='+mono_display_cmd,1,expandAll_ajax_callback,expandAll_ajax_callback_error,el);
			expand_state[el]=1;
		
	}
 
  }
  else if (unexpand) {
    whichEl.style.display  = 'none';
    whichIm.src            = imgClosed.src;
    
  }
} // end of the 'expandBase()' function

function expandBase(el, unexpand) {
  if (!isDOM)
    return;

  var whichEl = document.getElementById(el + 'Child');
  var whichIm = document.getElementById(el + 'Img');
  if (whichEl.style.display == 'none' && whichIm) {
    whichEl.style.display  = 'block';
    whichIm.src            = imgOpened.src;
    changeCoverImage(whichEl);
  }
  else if (unexpand) {
    whichEl.style.display  = 'none';
    whichIm.src            = imgClosed.src;
  }
} // end of the 'expandBase()' function

onload = initIt;