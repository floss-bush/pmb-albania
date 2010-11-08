<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version = '1.0'
     xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
		<xsl:output method="xml" indent='yes'/>
	
<xsl:template match="@*">
	<xsl:copy/>
</xsl:template>

<xsl:template match="text()">
	<xsl:choose>
		<xsl:when test="substring(.,string-length(.))='/'">
			<xsl:value-of select="normalize-space(substring(.,1,string-length(.)-1))"></xsl:value-of>
		</xsl:when>
		<xsl:when test="substring(.,string-length(.))=':'">
			<xsl:value-of select="normalize-space(substring(.,1,string-length(.)-1))"></xsl:value-of>
		</xsl:when>
		<xsl:when test="substring(.,string-length(.))=','">
			<xsl:value-of select="normalize-space(substring(.,1,string-length(.)-1))"></xsl:value-of>
		</xsl:when>
		<xsl:when test="substring(.,string-length(.))=';'">
			<xsl:value-of select="normalize-space(substring(.,1,string-length(.)-1))"></xsl:value-of>
		</xsl:when>
		<xsl:when test="substring(.,string-length(.))='.'">
			<xsl:value-of select="normalize-space(substring(.,1,string-length(.)-1))"></xsl:value-of>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="normalize-space(.)"></xsl:value-of>
		</xsl:otherwise>
	</xsl:choose>	
		
</xsl:template>
	
<xsl:template match="*">
	<xsl:element name="{name()}">
		<xsl:apply-templates select="* | text() | @*">			
		</xsl:apply-templates>
	</xsl:element>
</xsl:template>


</xsl:stylesheet> 
