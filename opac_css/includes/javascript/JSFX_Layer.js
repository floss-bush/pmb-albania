/******************************************************************* 
* File    : JSFX_Layer.js  © JavaScript-FX.com
* Created : 2001/04/11 
* Author  : Roy Whittle  (Roy@Whittle.com) www.Roy.Whittle.com 
* Purpose : To create a cross browser dynamic layers.
* History 
* Date         Version        Description 
* 2001-03-17	3.0		Completely re-witten for use by javascript-fx
***********************************************************************/ 
// +-------------------------------------------------+
// $Id: JSFX_Layer.js,v 1.2 2008-11-10 13:26:06 touraine37 Exp $

if(!window.JSFX)
	JSFX=new Object();

JSFX.layerNo=0; 
/**********************************************************************************/
JSFX.createElem = function(htmlStr, x, y)
{
	var elem = null;

 	if(document.layers) 
	{
		elem=new Layer(2000); 
		elem.document.open(); 
		elem.document.write(htmlStr); 
		elem.document.close(); 
		elem.moveTo(x,y);
		elem.innerHTML = htmlStr;
	}
	else 
	if(document.all) 
	{
		var xName = "xLayer" + JSFX.layerNo++; 
		var txt = "<DIV ID='" + xName 
			+ "' STYLE=\"position:absolute;" 
			+ "left:"  + x + ";" 
			+ "top:"   + y + ";" 
			+ "visibility:hidden\">" 
			+ htmlStr 
			+ "</DIV>"; 

			document.body.insertAdjacentHTML("BeforeEnd",txt); 

		elem = document.all[xName]; 
	} 
	else 
	if (document.getElementById)
	{
		var xName="xLayer" + JSFX.layerNo++;
		var txt = ""
			+ "position:absolute;"
			+ "left:"  + x + "px;"
			+ "top:"   + y + "px;"
			+ "visibility:hidden";

		var newRange = document.createRange();

		elem = document.createElement("DIV");
		elem.setAttribute("style",txt);
		elem.setAttribute("id", xName);

		document.body.appendChild(elem);

		newRange.setStartBefore(elem);
		strFrag = newRange.createContextualFragment(htmlStr);	
		elem.appendChild(strFrag);
	}

	return elem;
}
/**********************************************************************************/
JSFX.Layer = function(newLayer, x, y) 
{
	if(!newLayer)
		return;

	if(x==null)x=0; 
	if(y==null)y=0; 

	if(typeof newLayer == "string")
		this.elem = JSFX.createElem(newLayer, x, y);
	else
		this.elem=newLayer;

	if(document.layers)
	{
		this.images=this.elem.document.images; 
		this.style = this.elem;
 	} 
	else 
	{
		this.images  = document.images; 
		this.style   = this.elem.style; 
	} 
} 
/**********************************************************************************/
JSFX.findLayer = function(theDiv, d)
{
	if(document.layers)
	{
		var i;
		if(d==null) d = document;
		var theLayer = d.layers[theDiv];
		if(theLayer != null)
			return(theLayer);
		else
			for(i=0 ; i<d.layers.length ; i++)
			{
				theLayer = JSFX.findLayer(theDiv, d.layers[i].document);
				if(theLayer != null)
					return(theLayer);
			}
		return("Undefined....");
	}
	else 
	if(document.all)
		return(document.all[theDiv]);
	else 
	if(document.getElementById)
		return(document.getElementById(theDiv));
	else
		return("Undefined.....");
}
var ns4 = (navigator.appName.indexOf("Netscape") != -1 && !document.getElementById);

/**********************************************************************************/
/*** moveTo (x,y) ***/
JSFX.Layer.prototype.moveTo = function(x,y)
{
	this.style.left = x+"px";
	this.style.top = y+"px";
}
if(ns4)
	JSFX.Layer.prototype.moveTo = function(x,y) { this.elem.moveTo(x,y); }
/**********************************************************************************/
/*** show()/hide() Visibility ***/
JSFX.Layer.prototype.show		= function() 	{ this.style.visibility = "visible"; } 
JSFX.Layer.prototype.hide		= function() 	{ this.style.visibility = "hidden"; } 
JSFX.Layer.prototype.isVisible	= function()	{ return this.style.visibility == "visible"; } 
if(ns4)
{
	JSFX.Layer.prototype.show		= function() 	{ this.style.visibility = "show"; }
	JSFX.Layer.prototype.hide 		= function() 	{ this.style.visibility = "hide"; }
	JSFX.Layer.prototype.isVisible 	= function() 	{ return this.style.visibility == "show"; }
}
/**********************************************************************************/
/*** zIndex ***/
JSFX.Layer.prototype.setzIndex	= function(z)	{ this.style.zIndex = z; } 
JSFX.Layer.prototype.getzIndex	= function()	{ return this.style.zIndex; }
/**********************************************************************************/
/*** BackGround Color ***/
JSFX.Layer.prototype.setBgColor	= function(color) { this.style.backgroundColor = color==null?'transparent':color; } 
if(ns4)
	JSFX.Layer.prototype.setBgColor 	= function(color) { this.elem.bgColor = color; }
/**********************************************************************************/
/*** BackGround Image ***/
JSFX.Layer.prototype.setBgImage	= function(image) { this.style.backgroundImage = "url("+image+")"; }
if(ns4)
	JSFX.Layer.prototype.setBgImage 	= function(image) { this.style.background.src = image; }
/**********************************************************************************/
/*** set Content***/
JSFX.Layer.prototype.setContent   = function(xHtml)	{ this.elem.innerHTML=xHtml; } 
if(ns4)
	JSFX.Layer.prototype.setContent   = function(xHtml)
	{
		this.elem.document.open();
		this.elem.document.write(xHtml);
		this.elem.document.close();
	}

/**********************************************************************************/
/*** Clipping ***/
JSFX.Layer.prototype.clip = function(x1,y1, x2,y2){ this.style.clip="rect("+y1+" "+x2+" "+y2+" "+x1+")"; }
if(ns4)
	JSFX.Layer.prototype.clip = function(x1,y1, x2,y2)
	{
		this.style.clip.top	=y1;
		this.style.clip.left	=x1;
		this.style.clip.bottom	=y2;
		this.style.clip.right	=x2;
	}
/**********************************************************************************/
/*** Resize ***/
JSFX.Layer.prototype.resizeTo = function(w,h)
{ 
	this.style.width	=w + "px";
	this.style.height	=h + "px";
}
if(ns4)
	JSFX.Layer.prototype.resizeTo = function(w,h)
	{
		this.style.clip.width	=w;
		this.style.clip.height	=h;
	}
/**********************************************************************************/
/*** getX/Y ***/
JSFX.Layer.prototype.getX	= function() 	{ return parseInt(this.style.left); }
JSFX.Layer.prototype.getY	= function() 	{ return parseInt(this.style.top); }
if(ns4)
{
	JSFX.Layer.prototype.getX	= function() 	{ return this.style.left; }
	JSFX.Layer.prototype.getY	= function() 	{ return this.style.top; }
}
/**********************************************************************************/
/*** getWidth/Height ***/
JSFX.Layer.prototype.getWidth		= function() 	{ return this.elem.offsetWidth; }
JSFX.Layer.prototype.getHeight	= function() 	{ return this.elem.offsetHeight; }
if(!document.getElementById)
	JSFX.Layer.prototype.getWidth		= function()
 	{ 
		//Extra processing here for clip
		return this.elem.scrollWidth;
	}

if(ns4)
{
	JSFX.Layer.prototype.getWidth		= function() 	{ return this.style.clip.right; }
	JSFX.Layer.prototype.getHeight	= function() 	{ return this.style.clip.bottom; }
}