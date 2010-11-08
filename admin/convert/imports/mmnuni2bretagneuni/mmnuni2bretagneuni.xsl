<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	
	<xsl:output method="xml" encoding="ISO-8859-1" indent="yes"/>
	
	<xsl:template match="/unimarc">
		<unimarc>
			<xsl:apply-templates select="notice"/>
		</unimarc>
	</xsl:template>
	
	<xsl:template match="notice">
		<notice>
			<xsl:apply-templates select="*"/>
		</notice>
	</xsl:template>
	
	<xsl:template match="f[@c='463']">
		<xsl:choose>
			<xsl:when test="../bl='a'">
				<f c="464" ind="  ">
					<s c="t"><xsl:value-of select="./s[4]"/></s>
					<xsl:apply-templates select="s[@c='v']"/>
					<xsl:element name="s">
						<xsl:attribute name="c">p</xsl:attribute>
						<xsl:value-of select="../f[@c='215']/s[@c='a']"/>
					</xsl:element>
				</f>
				<f c="010" ind="  ">
					<s c="a">
						<xsl:value-of select="./s[2]"/>
					</s>
				</f>
			</xsl:when>
			<xsl:when test="../bl!='a'">
				<xsl:copy-of select="."/>
			</xsl:when>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template match="/unimarc/notice/f[@c='463']/s[@c='v']">
		<xsl:element name="s">
			<xsl:attribute name="c">v</xsl:attribute>
			<xsl:value-of select="substring-after(substring-before(.,','),'No ')"/>
		</xsl:element>
		<xsl:element name="s">
			<xsl:attribute name="c">d</xsl:attribute>
			<xsl:value-of select="substring-after(.,', ')"/>
		</xsl:element>
	</xsl:template>
	
	<xsl:template match="f[@c='215']">
		<xsl:if test="../bl!='a'">
			<xsl:copy-of select="."/>
		</xsl:if>
	</xsl:template>
	
	<xsl:template match="*">
		<xsl:copy-of select="."/>
	</xsl:template>
</xsl:stylesheet>