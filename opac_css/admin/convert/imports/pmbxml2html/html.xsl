<?xml version="1.0" encoding="iso-8859-1"?>
<!-- $Id: html.xsl,v 1.2 2006-04-28 05:35:04 touraine37 Exp $ -->
<xsl:stylesheet version = '1.0'
     xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>

<xsl:output method="xml" version="1.0" encoding="iso-8859-1" indent="yes"/>

<xsl:template match="pmbmarc">
<html>
		<xsl:apply-templates select="notice"/>
</html>
</xsl:template>

<xsl:template match="unimarc">
<html>
		<xsl:apply-templates select="notice"/>
</html>
</xsl:template>

<xsl:template match="notice">
	<table>
		<xsl:apply-templates select="./rs"/>
		<xsl:apply-templates select="./dt"/>
		<xsl:apply-templates select="./bl"/>
		<xsl:apply-templates select="./hl"/>
		<xsl:apply-templates select="./el"/>
		<xsl:apply-templates select="./ru"/>
		<xsl:apply-templates select="./f"/>
	</table>
</xsl:template>

<xsl:template match="notice/rs">
	<tr>
		<td >rs</td>
		<td ></td>
		<td ><xsl:value-of select="."/></td>
		<!--<td ></td>-->
	</tr>
</xsl:template>

<xsl:template match="notice/ru">
	<tr>
		<td >ru</td>
		<td ></td>
		<td ><xsl:value-of select="."/></td>
		<!--<td ></td>-->
	</tr>
</xsl:template>

<xsl:template match="notice/dt">
	<tr>
		<td >dt</td>
		<td ></td>
		<td ><xsl:value-of select="."/></td>
		<!--<td ></td>-->
	</tr>
</xsl:template>

<xsl:template match="notice/bl">
	<tr>
		<td >bl</td>
		<td ></td>
		<td ><xsl:value-of select="."/></td>
		<!--<td ></td>-->
	</tr>
</xsl:template>

<xsl:template match="notice/hl">
	<tr>
		<td >hl</td>
		<td ></td>
		<td ><xsl:value-of select="."/></td>
		<!--<td ></td>-->
	</tr>
</xsl:template>

<xsl:template match="notice/el">
	<tr>
		<td >el</td>
		<td ></td>
		<td ><xsl:value-of select="."/></td>
		<!--<td ></td>-->
	</tr>
</xsl:template>

<xsl:template match="notice/f">
	<tr>
		<td ><xsl:value-of select="@c"/></td>
		<xsl:choose>
			<xsl:when test="./s">
				<td ><pre>(<xsl:value-of select="@ind"/>)</pre></td>
			</xsl:when>
			<xsl:otherwise>
				<td ><pre>(<xsl:value-of select="."/>)</pre></td>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:choose>
			<xsl:when test="./s">
				<td ><table class="noborder"><xsl:apply-templates select="./s"/><xsl:text> </xsl:text></table></td>
			</xsl:when>
			<xsl:otherwise>
				<td > </td>
			</xsl:otherwise>
		</xsl:choose>
		<!--<td ><xsl:value-of select="."/></td> -->
	</tr>
</xsl:template>

<xsl:template match="notice/f/s">
	<tr>
		<td class="noborder">
			$<xsl:value-of select="@c"/>
		</td>
		<td class="noborder">
			<xsl:value-of select="."/>
		</td>
	</tr>
</xsl:template>

<xsl:template match="*"/>

</xsl:stylesheet>
