<?xml version="1.0" encoding="ISO-8859-1"?>
<!-- $Id: uni2cbex.xsl,v 1.2 2006-04-28 05:35:04 touraine37 Exp $ -->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	
	<xsl:output method="text" encoding="ISO-8859-1"/>
	
	<xsl:template match="/unimarc">
		<xsl:apply-templates select="notice/f[@c='995']/s[@c='f']"/>
	</xsl:template>
	
	<xsl:template match="notice/f[@c='995']/s[@c='f']">
		<xsl:value-of select="."/>
		<xsl:text>
</xsl:text>
	</xsl:template>
	
	<xsl:template match="*"/>

</xsl:stylesheet>