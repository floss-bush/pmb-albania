// gestion des forms "collapsibles" en Javascript
// $Id: tabform.js,v 1.6 2009-03-30 13:28:52 kantin Exp $

// tabCreate() : crée un objet form et affecte les méthodes et propriétés

var imgOpened = new Image();
imgOpened.src = './images/minus.gif';
var imgClosed = new Image();
imgClosed.src = './images/plus.gif';
var expandedDb = 'el0Child';

// on regarde si le client est DOM-compliant

var isDOM = (typeof(document.getElementsByTagName) != 'undefined') ? 1 : 0;

//Konqueror (support DOM partiel) : on rejette
if(isDOM && typeof(navigator.userAgent) != 'undefined') {
	var browserName = ' ' + navigator.userAgent.toLowerCase();
	if(browserName.indexOf('konqueror') > 0) {
		isDOM = 0;
	}
}

function expandAll() {
  var tempColl    = document.getElementsByTagName('DIV');
  var tempCollCnt = tempColl.length;
  for (var i = 0; i < tempCollCnt; i++) {
     if((tempColl[i].className == 'child')&&(tempColl[i].getAttribute("hide")!="yes"))
     tempColl[i].style.display = 'block';
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
     if((tempColl[i].className == 'child')&&(tempColl[i].getAttribute("hide")!="yes"))
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


function initIt()
{
  if (!isDOM) {
//	alert("ce navigateur n'est pas compatible avec le DOM.");
    return;
  }
  var tempColl    = document.getElementsByTagName('DIV');
  var tempCollCnt = tempColl.length;
  for (var i = 0; i < tempCollCnt; i++) {
    if (((tempColl[i].id == expandedDb)&&(document.getElementById("elbulChild")==null))||(tempColl[i].id=="elbulChild"))
    	tempColl[i].style.display = 'block';
    else if (tempColl[i].className == 'child'){
    	 tempColl[i].style.display = 'none';
    	 
    	 //On recharge l'onglet on met plus dans l'image
    	 var chaine= new String(tempColl[i].id);
    	 chaine=chaine.replace('Child', 'Parent');
    	 var tempCollparent = document.getElementById(chaine);
    	 
    	 //On parcourt tous les fils de l'élément parent
    	 if(tempCollparent!=null){
	    	 for(var j=0;j<tempCollparent.childNodes.length;j++){
	    		 if(tempCollparent.childNodes[j].nodeType == 1){
	    			 if(tempCollparent.childNodes[j].nodeName == 'H3'){
	    				 //on récupère tous les fils de H3
	    				 var tab = tempCollparent.childNodes[j].childNodes;
	    			 }
	    		 }
	    	 } 
	     }
    	 
    	 if(tab!=null){
    		 for (var k=0;k<tab.length;k++){
    			 if(tab[k].nodeName == 'IMG' && tab[k].name == 'imEx'){
    				//si un fils de H3 est une image qui a pour nom imEx on le met à plus
    				tab[k].src = imgClosed.src;
    			 }
    		 }
    	 }
     }
  }
} // end of the 'initIt()' function

function expandBase(el, unexpand)
{
  if (!isDOM)
    return;

  var whichEl = document.getElementById(el + 'Child');
  var whichIm = document.getElementById(el + 'Img');
  if (whichEl.style.display == 'none' && whichIm) {
    whichEl.style.display  = 'block';
    whichIm.src            = imgOpened.src;
  }
  else if (unexpand) {
    whichEl.style.display  = 'none';
    whichIm.src            = imgClosed.src;
  }
} // end of the 'expandBase()' function

onload = initIt;

/*	CSS functions
		empruntées de la DHTML Kitchen :
		http://dhtmlkitchen.com/js/utilities/setStyle/index.jsp	*/
function getRef(obj)
{
	if (typeof obj == "string")
	{
		obj = document.getElementById(obj);
	}
	return obj;
}

function setStyle(obj, style, value)
{
	getRef(obj).style[style] = value;
}

function getStyle(obj, style)
{
	if (!document.getElementById)
		return;

	var obj = getRef(obj);
	var value = obj.style[style];

	if (!value)
	{
		if (document.defaultView)
		{
			value = document.defaultView.getComputedStyle(obj, "").getPropertyValue(style);
		}
		else if (obj.currentStyle)
		{
			value = obj.currentStyle[style]
		}
	}
	return style;
}

function setClassName(obj, className)
{
	getRef(obj).className = className;
}
