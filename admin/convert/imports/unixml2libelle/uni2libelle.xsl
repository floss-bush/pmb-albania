<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="2.0">
	
	<xsl:output method="xml" indent="yes" encoding="iso-8859-1"/>
	
	<xsl:param name="corresp" select="document('imports/unixml2libelle/uni2libelle.xml')"/>
	
	<xsl:template match="/*">
		<notices>
			<xsl:apply-templates select="notice"/>
		</notices>
	</xsl:template>
	
	<xsl:template match="notice">
		<notice>
			<xsl:apply-templates select="f"/>
		</notice>
	</xsl:template>
	
	<xsl:template match="f">
		<!-- Recherche du code -->
		<xsl:variable name="code">
			<xsl:call-template name="element">
				<xsl:with-param name="code" select="@c"/>
			</xsl:call-template>
		</xsl:variable>
		<xsl:element name="{$code}">
			<xsl:for-each select="*">
				<xsl:choose>
					<xsl:when test="name(.)='s'">
						<xsl:variable name="sous_code">
							<xsl:call-template name="sous_element">
								<xsl:with-param name="code" select="../@c"/>
								<xsl:with-param name="sous_code" select="@c"/>
							</xsl:call-template>
						</xsl:variable>
						<xsl:element name="{$sous_code}">
							<xsl:value-of select="."/>
						</xsl:element>
					</xsl:when>
					<xsl:otherwise><xsl:copy-of select="."/></xsl:otherwise>
				</xsl:choose>
			</xsl:for-each>
			<xsl:if test="not(s)">
				<xsl:copy-of select="text()"/>
			</xsl:if>
		</xsl:element>
	</xsl:template>
	
	<xsl:template name="element">
		<xsl:param name="code"/>
		<xsl:choose>
			<xsl:when test="$corresp/convert/field[@code=$code]"><xsl:value-of select="$corresp/convert/field[@code=$code]/@tag"/></xsl:when>
			<xsl:otherwise><xsl:value-of select="concat('f_',$code)"/></xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template name="sous_element">
		<xsl:param name="code"/>
		<xsl:param name="sous_code"/>
		
		<xsl:choose>
			<xsl:when test="$corresp/convert/field[@code=$code]/subfield[@code=$sous_code]"><xsl:value-of select="$corresp/convert/field[@code=$code]/subfield[@code=$sous_code]/@tag"/></xsl:when>
			<xsl:otherwise><xsl:value-of select="concat('s_',$sous_code)"/></xsl:otherwise>
		</xsl:choose>
	</xsl:template>
</xsl:stylesheet>