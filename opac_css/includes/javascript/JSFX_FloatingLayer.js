/*******************************************************************
*
* File    : JSFX_FloatingLayer.js © JavaScript-FX.com
*
* Created : 2001/03/16
*
* Author  : Roy Whittle www.Roy.Whittle.com
*           
* Purpose : To make and positional div float to
*		one plave on the page and stay there.
*
* History
* Date         Version        Description
*
* 2001-03-17	2.0		Converted for javascript-fx
***********************************************************************/
// +-------------------------------------------------+
// $Id: JSFX_FloatingLayer.js,v 1.2 2008-11-10 13:26:06 touraine37 Exp $

JSFX.FloatingLayer = function(theDiv, x, y)
{
	//Call the superclass constructor
	this.superC = JSFX.Layer;
	this.superC(JSFX.findLayer(theDiv), x, y);

	this.baseX = x;
	this.baseY = y;
	this.x = x;
	this.y = y;
	this.moveTo(x,y);
	this.show();

}
JSFX.FloatingLayer.prototype = new JSFX.Layer;

JSFX.FloatingLayer.prototype.animate = function()
{
	var targetX;
	var targetY;
	//if(this.baseX > 0)
	//	targetX = JSFX.Browser.getMinX() + this.baseX;
	//else
	//	targetX = JSFX.Browser.getMaxX() + this.baseX;

	if(this.baseY > 0)
		targetY = JSFX.Browser.getMinY() + this.baseY;
	else
		targetY = JSFX.Browser.getMaxY() + this.baseY;

	//var dx = (targetX - this.x)/1;
	var dy = (targetY - this.y)/1;
	//this.x += dx;
	this.y += dy;

	this.moveTo(this.x, this.y);
}
JSFX.MakeFloatingLayer = function(theDiv, x, y)
{
	JSFX.MakeFloatingLayer.floaters[JSFX.MakeFloatingLayer.floaters.length] = new JSFX.FloatingLayer(theDiv, x, y);
}
JSFX.MakeFloatingLayer.floaters = new Array();
JSFX.MakeFloatingLayer.animate = function()
{
	var i;
	for(i=0 ; i<JSFX.MakeFloatingLayer.floaters.length ; i++)
		JSFX.MakeFloatingLayer.floaters[i].animate();
}
setInterval("JSFX.MakeFloatingLayer.animate()", 30);
