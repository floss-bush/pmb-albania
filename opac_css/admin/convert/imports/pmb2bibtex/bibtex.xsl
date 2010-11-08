<?xml version="1.0" encoding="iso-8859-1"?>
<!-- $Id: bibtex.xsl,v 1.6 2007-05-07 21:31:01 gautier Exp $ -->
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
	<xsl:text>@</xsl:text>
	<xsl:call-template name="niveau"/>
	<xsl:text>{</xsl:text>	
    <xsl:call-template name="identification"/>
	<xsl:text>,</xsl:text>	
		
	<xsl:if test="f[@c='200']/s[@c='a']">
	    <xsl:call-template name="titre"/>
	</xsl:if>
	<xsl:if test="f[@c='700']/s[@c='a']">
	    <xsl:call-template name="auteur"/>
	</xsl:if>
	<xsl:if test="f[@c='210']/s[@c='c']">
	    <xsl:call-template name="editeur"/>
	</xsl:if>
	<xsl:if test="f[@c='210']/s[@c='d']">
	    <xsl:call-template name="annee"/>
	</xsl:if>
	<xsl:if test="f[@c='210']/s[@c='a']">
	    <xsl:call-template name="adresse"/>
	</xsl:if>
<!--	<xsl:if test="f[@c='']/s[@c='']">
	    <xsl:call-template name="serie"/>
	</xsl:if> -->
	<xsl:if test="f[@c='461']/s[@c='v']">
	    <xsl:call-template name="volume"/>
	</xsl:if>
	<xsl:if test="f[@c='330']/s[@c='a']">
	    <xsl:call-template name="resume"/>
	</xsl:if>
	<xsl:if test="f[@c='327']/s[@c='a']">
	    <xsl:call-template name="contenu"/>
	</xsl:if>
	<xsl:if test="f[@c='010']/s[@c='a']">
	    <xsl:call-template name="isbn"/>
	</xsl:if>
	<xsl:if test="f[@c='856']/s[@c='u']">
	    <xsl:call-template name="url"/>
	</xsl:if>
	<xsl:if test="f[@c='300']/s[@c='a']">
	    <xsl:call-template name="note"/>
	</xsl:if>
	<xsl:text>
}
</xsl:text>
</xsl:template>

<xsl:template name="identification">
	<!-- composition de l'identifiant de la notice : 6 premiers caractères du titre, nom de famille de l'auteur -->
	<xsl:if test="f[@c='001']">
	    <xsl:value-of select="f[@c='001']"/>
	</xsl:if>
	<xsl:text>-</xsl:text>
	<xsl:if test="f[@c='700']/s[@c='a']">
	    <xsl:value-of select="translate(f[@c='700']/s[@c='a'],' ','_')"/>
		<xsl:text>-</xsl:text>
	</xsl:if>
	<xsl:choose>
		<xsl:when test="f[@c='210']/s[@c='d']">
			<xsl:value-of select="substring(translate(f[@c='210']/s[@c='d'],' ','_'),1,6)"/>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="substring(translate(f[@c='200']/s[@c='a'],' ','_'),1,6)"/>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="niveau">
<xsl:choose>
	<xsl:when test="bl='m'">
		<xsl:text>BOOK</xsl:text>
	</xsl:when>
	<xsl:when test="bl='a'">
		<xsl:text>ARTICLE</xsl:text>
	</xsl:when>
	<xsl:otherwise>
		<xsl:text>MISC</xsl:text>
	</xsl:otherwise>
</xsl:choose>
</xsl:template>

<xsl:template name="titre">
		<xsl:text>
    title = "</xsl:text>
	<xsl:value-of select="f[@c='200']/s[@c='a']"/>
	<xsl:text>"</xsl:text>
</xsl:template>

<xsl:template name="auteur">
	<xsl:text>,
    author = "</xsl:text>
	<xsl:if test="f[@c='700']">
		<xsl:value-of select="f[@c='700']/s[@c='b']"/>	
		<xsl:text> </xsl:text>
		<xsl:value-of select="f[@c='700']/s[@c='a']"/>
	</xsl:if>
	<xsl:for-each select="./f[@c='701']">
		<xsl:if test="position()>1 or ../f[@c='700']">
			<xsl:text> and </xsl:text>
		</xsl:if>
		<xsl:value-of select="s[@c='b']"/>	
		<xsl:text> </xsl:text>
		<xsl:value-of select="s[@c='a']"/>
	</xsl:for-each>
	<xsl:for-each select="./f[@c='711']">
		<xsl:if test="position()>1 or ../f[@c='710'] or ../f[@c='701']">
			<xsl:text> and </xsl:text>
		</xsl:if>
		<xsl:value-of select="s[@c='b']"/>	
		<xsl:text> </xsl:text>
		<xsl:value-of select="s[@c='a']"/>
	</xsl:for-each>
			<xsl:text>"</xsl:text>	
</xsl:template>

<xsl:template name="editeur">
		<xsl:text>,
    publisher = "</xsl:text>
	<xsl:value-of select="f[@c='210']/s[@c='c']"/>
	<xsl:text>"</xsl:text>
</xsl:template>

<xsl:template name="annee">
		<xsl:text>,
    year = "</xsl:text>
	<xsl:value-of select="f[@c='210']/s[@c='d']"/>
	<xsl:text>"</xsl:text>
</xsl:template>

<xsl:template name="adresse">
		<xsl:text>,
    address = "</xsl:text>
	<xsl:value-of select="f[@c='210']/s[@c='a']"/>
	<xsl:text>"</xsl:text>
</xsl:template>

<xsl:template name="nopartie">
		<xsl:text>,
    volume = "</xsl:text>
	<xsl:value-of select="f[@c='461']/s[@c='v']"/>
	<xsl:text>"</xsl:text>
</xsl:template>

<xsl:template name="resume">
		<xsl:text>,
    abstract = "</xsl:text>
	<xsl:value-of select="f[@c='330']/s[@c='a']"/>
	<xsl:text>"</xsl:text>
</xsl:template>

<xsl:template name="contenu">
		<xsl:text>,
    contents = "</xsl:text>
	<xsl:value-of select="f[@c='327']/s[@c='a']"/>
	<xsl:text>"</xsl:text>
</xsl:template>

<xsl:template name="isbn">
		<xsl:text>,
    ISBN = "</xsl:text>
	<xsl:value-of select="f[@c='010']/s[@c='a']"/>
	<xsl:text>"</xsl:text>
</xsl:template>

<xsl:template name="prix">
		<xsl:text>,
    price = "</xsl:text>
	<xsl:value-of select="f[@c='010']/s[@c='d']"/>
	<xsl:text>"</xsl:text>
</xsl:template>

<xsl:template name="url">
		<xsl:text>,
    URL = "</xsl:text>
	<xsl:value-of select="f[@c='856']/s[@c='u']"/>
	<xsl:text>"</xsl:text>
</xsl:template>

<xsl:template name="volume">
		<xsl:text>,
    volume = "</xsl:text>
	<xsl:value-of select="f[@c='461']/s[@c='v']"/>
	<xsl:text>"</xsl:text>
</xsl:template>

<xsl:template name="note">
		<xsl:text>,
    note = "</xsl:text>
	<xsl:value-of select="f[@c='300']/s[@c='a']"/>
	<xsl:text>"</xsl:text>
</xsl:template>

<!-- pas d'export du titre de série pour l'instant
<xsl:template name="serie">
		<xsl:text>,
    series = "</xsl:text>
	<xsl:value-of select="f[@c='210']/s[@c='a']"/>
	<xsl:text>"</xsl:text>
</xsl:template>
-->

<xsl:template match="*"/>

</xsl:stylesheet>
