<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version = '1.0'
    xmlns:xsl='http://www.w3.org/1999/XSL/Transform'
	xmlns:aliga="http://www.aligastore.com/">

<xsl:output method="xml" indent='yes'/>

<xsl:template match="/">
	<xsl:if test="/aliga:solution/aliga:reponses/aliga:IDOFFSET">
		<xsl:apply-templates/>
	</xsl:if>
</xsl:template>
	
<xsl:template match="aliga:solution/aliga:question">
</xsl:template>
	
<xsl:template match="aliga:solution/aliga:reponses">
<enrichment>
		<xsl:call-template name="presentation"/>
		<xsl:call-template name="sommaire"/>
		<xsl:call-template name="biographie"/>
</enrichment>
</xsl:template>

<xsl:template name="presentation">
	<xsl:if test="aliga:PRESENTATION">
		<xsl:element name="resume">
			<xsl:for-each select="aliga:reponse[starts-with(@name,'PRESENTATION')]">
				<xsl:value-of select="."></xsl:value-of>
			</xsl:for-each>
		</xsl:element>
	</xsl:if>
</xsl:template>
	
<xsl:template name="sommaire">
	<xsl:if test="aliga:SOMMAIRE">
		<xsl:element name="sommaire">
			<xsl:for-each select="aliga:reponse[starts-with(@name,'SOMMAIRE')]">
				<xsl:value-of select="."></xsl:value-of>
			</xsl:for-each>
		</xsl:element>
	</xsl:if>
</xsl:template>

<xsl:template name="biographie">
	<xsl:if test="aliga:BIOGRAPHIE">
		<xsl:element name="biographie">
			<xsl:for-each select="aliga:reponse[starts-with(@name,'BIOGRAPHIE')]">
				<xsl:value-of select="."></xsl:value-of>
			</xsl:for-each>
		</xsl:element>
	</xsl:if>
</xsl:template>			
</xsl:stylesheet> 