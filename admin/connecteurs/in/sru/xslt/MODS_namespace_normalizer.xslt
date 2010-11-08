<?xml version="1.0" encoding="iso-8859-1"?>
<!--
	This stylesheet will change the URL of the namespaces so that all mods elements fit under a namespace
	labeled "mods", corresponding to the url "http://www.loc.gov/mods/v3"	
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:oldmods="http://www.loc.gov/mods/"
	xmlns:mods="http://www.loc.gov/mods/v3">
	
	<xsl:template match="node()|@*">
		<xsl:copy>
			<xsl:apply-templates select="node()|@*"/>
		</xsl:copy>
	</xsl:template>
	
	<xsl:template match="@oldmods:*">
	    <xsl:attribute name="mods:{local-name()}">
	    	<xsl:value-of select="."/>
	    </xsl:attribute>
	</xsl:template>
	
	<xsl:template match="oldmods:*">
		<xsl:element name="mods:{local-name()}">
			<xsl:apply-templates select="node()|@*"/>
		</xsl:element>
	</xsl:template>
	
</xsl:stylesheet>