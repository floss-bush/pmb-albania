<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	
	<xsl:output method="text" encoding="utf-8"/>
	
	<xsl:template match="/unimarc">
		<xsl:apply-templates select="notice/f[@c='996']/s[@c='f']"/>
	</xsl:template>
	
	<xsl:template match="notice/f[@c='996']/s[@c='f']"><!--Code barre-->
		<xsl:value-of select="."/>
		<xsl:text>;Bibliotheque de medecine;</xsl:text>
		<xsl:value-of select="../s[@c='v']"/><!--Localisation-->
		<xsl:text>;</xsl:text>
		<xsl:value-of select="../s[@c='x']"/><!--Section-->
		<xsl:text>;</xsl:text>
		<xsl:value-of select="../s[@c='e']"/><!--Type de document-->
		<xsl:text>;</xsl:text>
		<xsl:value-of select="../../f[@c='995']/s[@c='k']"/><!--Cote-->
		<xsl:text>;</xsl:text>
		<xsl:value-of select="../../f[@c='461']/s[@c='t']"/><!--Serie-->
		<xsl:text>;;;;</xsl:text>	
		<xsl:choose>
			<xsl:when test="../../f[@c='700']/s[@c='a']">
				<xsl:value-of select="../../f[@c='700']/s[@c='a']"/><xsl:text> </xsl:text><xsl:value-of select="../../f[@c='700']/s[@c='b']"/><!--Auteur-->
			</xsl:when>
			<xsl:when test="../../f[@c='701']/s[@c='a']">
				<xsl:value-of select="../../f[@c='701']/s[@c='a']"/><xsl:text> </xsl:text><xsl:value-of select="../../f[@c='701']/s[@c='b']"/><!--Auteur-->
			</xsl:when>
			<xsl:when test="../../f[@c='710']/s[@c='a']">
				<xsl:value-of select="../../f[@c='710']/s[@c='a']"/><xsl:text> </xsl:text><xsl:value-of select="../../f[@c='710']/s[@c='b']"/><!--Auteur-->
			</xsl:when>
			<xsl:when test="../../f[@c='701']/s[@c='a']">
				<xsl:value-of select="../../f[@c='711']/s[@c='a']"/><xsl:text> </xsl:text><xsl:value-of select="../../f[@c='711']/s[@c='b']"/><!--Auteur-->
			</xsl:when>
			<xsl:otherwise></xsl:otherwise>
		</xsl:choose>
		
		<xsl:text>;</xsl:text>

		<xsl:value-of select="../../f[@c='200']/s[@c='a']"/><!--Titre-->
		<xsl:text>
</xsl:text>
	</xsl:template>
	
	<xsl:template match="*"/>

</xsl:stylesheet>