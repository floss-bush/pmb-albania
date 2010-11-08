<?xml version="1.0" encoding="iso-8859-1"?>
<!-- $Id: endnote.xsl,v 1.2 2007/05/08 18:43:54 gautier Exp $ -->
<xsl:stylesheet version = '1.0'
     xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>

<!-- 
Références pour l'export EndNote : http://llacan.vjf.cnrs.fr/fichiers/manuels/Endnote/EndNote3.htm 
Export texte compatible avec les anciennes versions EndNote
-->


<xsl:output method="text"/>

<xsl:template match="pmbmarc">
		<xsl:apply-templates select="notice"/>
</xsl:template>

<xsl:template match="unimarc">
		<xsl:apply-templates select="notice"/>
</xsl:template>

<xsl:template match="notice">
	<xsl:call-template name="niveau"/>
		
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
	    <xsl:call-template name="ville"/>
	</xsl:if>
<!-- titre de série actuellement pas exporté
	<xsl:if test="f[@c='']/s[@c='']">
	    <xsl:call-template name="serie"/>
	</xsl:if> -->
	<xsl:if test="f[@c='461']/s[@c='v']">
	    <xsl:call-template name="volume"/>
	</xsl:if>
	<xsl:if test="f[@c='330']/s[@c='a']">	
	    <xsl:call-template name="resume"/>
	</xsl:if>
<!-- note de contenu ne correspond à aucune zone EndNote
	<xsl:if test="f[@c='327']/s[@c='a']">
	    <xsl:call-template name="contenu"/>
	</xsl:if> -->
	<xsl:if test="f[@c='010']/s[@c='a']">
	    <xsl:call-template name="isbn"/>
	</xsl:if>
<!-- url ne correspond à aucune zone EndNote
	<xsl:if test="f[@c='856']/s[@c='u']">
	    <xsl:call-template name="url"/>
	</xsl:if>
-->
	<xsl:if test="f[@c='300']/s[@c='a']">
	    <xsl:call-template name="note"/>
	</xsl:if>
	<xsl:text>
		
</xsl:text>
</xsl:template>

<xsl:template name="niveau">
	<!-- types de références connus -->
	<xsl:text>%0 </xsl:text>	
<xsl:choose>
		<xsl:when test="bl='m'">
		<!-- monographie -->
		<xsl:choose>
			<xsl:when test="dt='a'">
				<!-- texte imprimé -->
				<xsl:text>Book</xsl:text>
			</xsl:when>
			<xsl:when test="dt='b'">
				<!-- texte manuscrit -->
				<xsl:text>Personal Communication</xsl:text>
			</xsl:when>
			<xsl:when test="dt='c'">
				<!-- partition musicale imprimée -->
				<xsl:text>Artwork</xsl:text>
			</xsl:when>
			<xsl:when test="dt='d'">
				<!-- partition musicale manuscrite -->
				<xsl:text>Artwork</xsl:text>
			</xsl:when>
			<xsl:when test="dt='e'">
				<!-- carte imprimée -->
				<xsl:text>Map</xsl:text>
			</xsl:when>
			<xsl:when test="dt='f'">
				<!-- carte manuscrite -->
				<xsl:text>Map</xsl:text>
			</xsl:when>
			<xsl:when test="dt='g'">
				<!-- vidéo -->
				<xsl:text>Audiovisual Material</xsl:text>
			</xsl:when>
			<xsl:when test="dt='i'">
				<!-- enregistrement sonore non musical -->
				<xsl:text>Audiovisual Material</xsl:text>
			</xsl:when>
			<xsl:when test="dt='j'">
				<!-- enregistrement sonore musical -->
				<xsl:text>Audiovisual Material</xsl:text>
			</xsl:when>
			<xsl:when test="dt='k'">
				<!-- document graphique -->
				<xsl:text>Artwork</xsl:text>
			</xsl:when>
			<xsl:when test="dt='l'">
				<!-- ressources électroniques -->
				<xsl:text>Electronic Source</xsl:text>
			</xsl:when>
			<xsl:when test="dt='m'">
				<!-- document multimédia -->
				<xsl:text>Generic</xsl:text>
			</xsl:when>
			<xsl:when test="dt='r'">
				<!-- objet -->
				<xsl:text>Generic</xsl:text>
			</xsl:when>
		</xsl:choose>	
		</xsl:when>
		<xsl:when test="bl='a'">
		<!-- article -->
			<xsl:text>Journal Article</xsl:text>
		</xsl:when>
		<xsl:otherwise>
		<!-- périodique -->
			<xsl:text>Generic</xsl:text>
		</xsl:otherwise>		
</xsl:choose>
</xsl:template>

<xsl:template name="titre">
		<xsl:text>
%T </xsl:text>
	<xsl:value-of select="f[@c='200']/s[@c='a']"/>
</xsl:template>

<xsl:template name="auteur">
	<xsl:if test="f[@c='700']">
	<xsl:text>
%A </xsl:text>
		<xsl:value-of select="f[@c='700']/s[@c='b']"/>	
		<xsl:text> </xsl:text>
		<xsl:value-of select="f[@c='700']/s[@c='a']"/>
	</xsl:if>
	<xsl:for-each select="./f[@c='701']">
	<xsl:text>
%A </xsl:text>
		<xsl:value-of select="s[@c='b']"/>	
		<xsl:text> </xsl:text>
		<xsl:value-of select="s[@c='a']"/>
	</xsl:for-each>
	<xsl:for-each select="./f[@c='711']">
	<xsl:text>
%A </xsl:text>
		<xsl:value-of select="s[@c='b']"/>	
		<xsl:text> </xsl:text>
		<xsl:value-of select="s[@c='a']"/>
	</xsl:for-each>
</xsl:template>

<xsl:template name="editeur">
		<xsl:text>
%I </xsl:text>
	<xsl:value-of select="f[@c='210']/s[@c='c']"/>
</xsl:template>

<xsl:template name="annee">
		<xsl:text>
%D </xsl:text>
	<xsl:value-of select="f[@c='210']/s[@c='d']"/>
</xsl:template>

<xsl:template name="ville">
		<xsl:text>
%C </xsl:text>
	<xsl:value-of select="f[@c='210']/s[@c='a']"/>
</xsl:template>

<xsl:template name="resume">
		<xsl:text>
%X </xsl:text>
	<xsl:value-of select="f[@c='330']/s[@c='a']"/>
</xsl:template>

<!-- 
note de contenu : non intégrable dans l'export endnote 
<xsl:template name="contenu">
		<xsl:text>
%O </xsl:text>
	<xsl:value-of select="f[@c='327']/s[@c='a']"/>
	<xsl:text>"</xsl:text>
</xsl:template>
-->

<xsl:template name="isbn">
		<xsl:text>
%@ </xsl:text>
	<xsl:value-of select="f[@c='010']/s[@c='a']"/>
</xsl:template>

<xsl:template name="volume">
		<xsl:text>
%V </xsl:text>
	<xsl:value-of select="f[@c='461']/s[@c='v']"/>
</xsl:template>

<xsl:template name="note">
		<xsl:text>
%O </xsl:text>
	<xsl:value-of select="f[@c='300']/s[@c='a']"/>
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
