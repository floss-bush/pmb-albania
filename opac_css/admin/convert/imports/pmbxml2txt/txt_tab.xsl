<?xml version="1.0" encoding="iso-8859-1"?>
<!-- $Id: txt_tab.xsl,v 1.2 2006-04-28 05:35:04 touraine37 Exp $ -->
<xsl:stylesheet version = '1.0'
     xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>

<xsl:output method="text"/>

<xsl:template match="pmbmarc">
		<xsl:apply-templates select="notice"/>
</xsl:template>

<xsl:template match="unimarc">
		<xsl:apply-templates select="notice"/>
</xsl:template>

<xsl:template match="notice">
		<xsl:apply-templates select="./rs"/>
		<xsl:apply-templates select="./dt"/>
		<xsl:apply-templates select="./bl"/>
		<xsl:apply-templates select="./hl"/>
		<xsl:apply-templates select="./el"/>
		<xsl:apply-templates select="./ru"/>
		<xsl:apply-templates select="./f"/>
		<xsl:text>
</xsl:text>
</xsl:template>

<xsl:template match="notice/rs">
		<xsl:text>rs     </xsl:text><xsl:value-of select="."/>
		<xsl:text>
</xsl:text>
</xsl:template>

<xsl:template match="notice/ru">
		<xsl:text>ru     </xsl:text><xsl:value-of select="."/>
		<xsl:text>
</xsl:text>
</xsl:template>

<xsl:template match="notice/dt">
		<xsl:text>dt     </xsl:text><xsl:value-of select="."/>
		<xsl:text>
</xsl:text>
</xsl:template>

<xsl:template match="notice/bl">
		<xsl:text>bl     </xsl:text><xsl:value-of select="."/>
		<xsl:text>
</xsl:text>
</xsl:template>

<xsl:template match="notice/hl">
		<xsl:text>hl     </xsl:text><xsl:value-of select="."/>
		<xsl:text>
</xsl:text>
</xsl:template>

<xsl:template match="notice/el">
		<xsl:text>el     </xsl:text><xsl:value-of select="."/>
		<xsl:text>
</xsl:text>
</xsl:template>

<xsl:template match="notice/f">
<xsl:value-of select="@c"/><xsl:text> (</xsl:text>
	<xsl:choose>
		<xsl:when test="./s">
			<xsl:value-of select="@ind"/>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="."/>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:text>) </xsl:text>
	<xsl:choose>
		<xsl:when test="./s">
			<xsl:apply-templates select="./s"/>
		</xsl:when>
		<xsl:otherwise/>
	</xsl:choose>
<xsl:text>
</xsl:text>
</xsl:template>

<xsl:template match="notice/f/s">
<xsl:text>$</xsl:text><xsl:value-of select="@c"/><xsl:text> </xsl:text><xsl:value-of select="."/><xsl:text>  </xsl:text>  
</xsl:template>

<xsl:template match="*"/>

</xsl:stylesheet>
