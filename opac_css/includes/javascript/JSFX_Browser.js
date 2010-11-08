/******************************************************************* 
* 
* File    : JSFX_Browser.js © JavaScript-FX.com
* 
* Created : 2000/07/15 
* 
* Author  : Roy Whittle www.Roy.Whittle.com 
* 
* Purpose : To create a cross browser "Browser" object.
*		JSFX.Browser library will allow scripts to query parameters
*		about the current browser window.
* 
* History 
* Date         Version        Description 
* 2001-03-17	2.0		Converted for javascript-fx
***********************************************************************/  
// +-------------------------------------------------+
// $Id: JSFX_Browser.js,v 1.2 2008-11-10 13:26:06 touraine37 Exp $

if(!window.JSFX)
	JSFX=new Object();

if(!JSFX.Browser)
	JSFX.Browser = new Object();

if(navigator.appName.indexOf("Netscape") != -1)
{
	JSFX.Browser.getCanvasWidth	= function() {return innerWidth;}
	JSFX.Browser.getCanvasHeight	= function() {return innerHeight;}
	JSFX.Browser.getWindowWidth 	= function() {return outerWidth;}
	JSFX.Browser.getWindowHeight	= function() {return outerHeight;}
	JSFX.Browser.getScreenWidth 	= function() {return screen.width;}
	JSFX.Browser.getScreenHeight	= function() {return screen.height;}
	JSFX.Browser.getMinX		= function() {return(pageXOffset);}
	JSFX.Browser.getMinY		= function() {return(pageYOffset);}
	JSFX.Browser.getMaxX		= function() {return(pageXOffset+innerWidth);}
	JSFX.Browser.getMaxY		= function() {return(pageYOffset+innerHeight);}

}
else if ((document.documentElement)&&(document.documentElement.clientWidth || document.documentElement.clientHeight)) {
    JSFX.Browser.getCanvasWidth	= function() {return document.documentElement.clientWidth;}
	JSFX.Browser.getCanvasHeight	= function() {return document.documentElement.clientHeight;}
	JSFX.Browser.getWindowWidth 	= function() {return document.documentElement.clientWidth;}
	JSFX.Browser.getWindowHeight	= function() {return document.documentElement.clientHeight;}
	JSFX.Browser.getScreenWidth	= function() {return screen.width;}
	JSFX.Browser.getScreenHeight	= function() {return screen.height;}
	JSFX.Browser.getMinX		= function() {return(document.documentElement.scrollLeft);}
	JSFX.Browser.getMinY		= function() {return(document.documentElement.scrollTop);}
	JSFX.Browser.getMaxX		= function() {
		return(document.documentElement.scrollLeft
			+document.documentElement.clientWidth);
	}
	JSFX.Browser.getMaxY		= function() {
			return(document.documentElement.scrollTop
				+document.documentElement.clientHeight);
	}
} else if((document.body)&&(document.body.clientWidth || document.body.clientHeight)) 	{
	JSFX.Browser.getCanvasWidth	= function() {return document.body.clientWidth;}
	JSFX.Browser.getCanvasHeight	= function() {return document.body.clientHeight;}
	JSFX.Browser.getWindowWidth 	= function() {return document.body.clientWidth;}
	JSFX.Browser.getWindowHeight	= function() {return document.body.clientHeight;}
	JSFX.Browser.getScreenWidth	= function() {return screen.width;}
	JSFX.Browser.getScreenHeight	= function() {return screen.height;}
	JSFX.Browser.getMinX		= function() {return(document.body.scrollLeft);}
	JSFX.Browser.getMinY		= function() {return(document.body.scrollTop);}
	JSFX.Browser.getMaxX		= function() {
		return(document.body.scrollLeft
			+document.body.clientWidth);
	}
	JSFX.Browser.getMaxY		= function() {
			return(document.body.scrollTop
				+document.body.clientHeight);
	}
}
/*** End  ***/ 