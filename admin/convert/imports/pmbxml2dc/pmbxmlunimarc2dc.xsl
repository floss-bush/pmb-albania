<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/" version="1.0">
<!-- Feuille de conversion pmb_xml_unimarc -> dublin core
****************************************************************************************
© 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
****************************************************************************************
$Id: pmbxmlunimarc2dc.xsl,v 1.2 2009-07-17 12:58:17 erwanmartin Exp $ -->

<xsl:output method="xml" indent="yes" encoding="utf-8"/>
<xsl:param name="notice_url_base"></xsl:param>

	<xsl:template match="/unimarc/notice">
		<oai_dc:dc xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd">
			<xsl:call-template name="identifier"/>
			<xsl:call-template name="language"/>
			<xsl:call-template name="title"/>
			<xsl:call-template name="publisher"/>
			<xsl:call-template name="collation"/>
			<xsl:call-template name="coverage"/>
			<xsl:call-template name="description"/>
			<xsl:call-template name="authors"/>
			<xsl:call-template name="category"/>
			<xsl:call-template name="relations"/>
		</oai_dc:dc>
	</xsl:template>
	
	<xsl:template name="identifier">
		<!-- URL -->
		<xsl:if test="$notice_url_base!=''">
			<xsl:for-each select="f[@c=001]">
				<dc:identifier>
					<xsl:value-of select="$notice_url_base"/>index.php?lvl=notice_display&amp;id=<xsl:value-of select="."/>
				</dc:identifier>
			</xsl:for-each>		    
		</xsl:if>
		<!-- Notice ID -->
		<xsl:for-each select="f[@c=001]">
			<dc:identifier>
				<xsl:value-of select="."/>
			</dc:identifier>
		</xsl:for-each>
		<!-- Identifier -->
		<xsl:for-each select="f[@c=100]/s[@c='a']">
			<dc:identifier>
				<xsl:value-of select="."/>
			</dc:identifier>
		</xsl:for-each>
		<!-- ISBN -->
		<xsl:for-each select="f[@c=010]/s[@c='a']">
			<dc:identifier scheme="ISBN">
				<xsl:value-of select="."/>
			</dc:identifier>
		</xsl:for-each>
		<!-- ISSN -->
		<xsl:for-each select="f[@c=011]/s[@c='a']">
			<dc:identifier scheme="ISSN">
				<xsl:value-of select="."/>
			</dc:identifier>
		</xsl:for-each>
	</xsl:template>
	
	<xsl:template name="language">
		<xsl:for-each select="f[@c=101]/s[@c='a']">
			<dc:language>
				<xsl:value-of select="."/>
			</dc:language>
		</xsl:for-each>
	</xsl:template>
	
	<xsl:template name="title">
		<!-- Titres propres -->
		<xsl:for-each select="f[@c=200]/s[@c='a']">
			<dc:title>
				<xsl:value-of select="."/>
			</dc:title>
		</xsl:for-each>
		<!-- Titres propres d'auteurs différents -->
		<xsl:for-each select="f[@c=200]/s[@c='c']">
			<dc:title>
				<xsl:value-of select="."/>
			</dc:title>
		</xsl:for-each>
		<!-- Titres parallèles -->
		<xsl:for-each select="f[@c=200]/s[@c='d']">
			<dc:title>
				<xsl:value-of select="."/>
			</dc:title>
		</xsl:for-each>
		<!-- Complément du titre -->
		<xsl:for-each select="f[@c=200]/s[@c='e']">
			<dc:title>
				<xsl:value-of select="."/>
			</dc:title>
		</xsl:for-each>
	</xsl:template>
	
	<xsl:template name="publisher">
		<xsl:for-each select="f[@c=210]/s[@c='c']">
			<dc:publisher>
				<xsl:value-of select="."/>
			</dc:publisher>
		</xsl:for-each>
	</xsl:template>
	
	<xsl:template name="collation">
		<!-- Importance matérielle (nombre de pages, d'éléments...) -->
		<xsl:for-each select="f[@c=215]/s[@c='a']">
			<dc:description>
				<xsl:value-of select="."/>
			</dc:description>
		</xsl:for-each>
		<!-- Autres caractéristiques matérielles (ill., ...) -->
		<xsl:for-each select="f[@c=215]/s[@c='c']">
			<dc:description>
				<xsl:value-of select="."/>
			</dc:description>
		</xsl:for-each>
		<!-- Matériel d'accompagnement -->
		<xsl:for-each select="f[@c=215]/s[@c='e']">
			<dc:description>
				<xsl:value-of select="."/>
			</dc:description>
		</xsl:for-each>
		<!-- Format -->
		<xsl:for-each select="f[@c=215]/s[@c='d']">
			<dc:format>
				<xsl:value-of select="."/>
			</dc:format>
		</xsl:for-each>
	</xsl:template>
	
	<xsl:template name="coverage">
		<xsl:for-each select="f[@c=300]/s[@c='a']">
			<dc:coverage>
				<xsl:value-of select="."/>
			</dc:coverage>
		</xsl:for-each>
	</xsl:template>
	
	<xsl:template name="description">
		<xsl:for-each select="f[@c=330]/s[@c='a']">
			<dc:description>
				<xsl:value-of select="."/>
			</dc:description>
		</xsl:for-each>
		<xsl:for-each select="f[@c=327]/s[@c='a']">
			<dc:description>
				<xsl:value-of select="."/>
			</dc:description>
		</xsl:for-each>
	</xsl:template>
	
	<xsl:template name="authors">
		<!-- Auteur principal -->
		<xsl:for-each select="f[@c=700]">
			<dc:creator>
				<xsl:value-of select="s[@c='a']"/><xsl:text> </xsl:text><xsl:value-of select="s[@c='b']"/>
			</dc:creator>
		</xsl:for-each>
		<!-- Auteur autre -->
		<xsl:for-each select="f[@c=701]">
			<dc:contributor>
				<xsl:value-of select="s[@c='a']"/><xsl:text> </xsl:text><xsl:value-of select="s[@c='b']"/>
			</dc:contributor>
		</xsl:for-each>
		<!-- Auteur secondaire -->
		<xsl:for-each select="f[@c=702]">
			<dc:contributor>
				<xsl:value-of select="s[@c='a']"/><xsl:text> </xsl:text><xsl:value-of select="s[@c='b']"/>
			</dc:contributor>
		</xsl:for-each>
	</xsl:template>
	
	<xsl:template name="category">
		<xsl:for-each select="f[@c=606]/s[@c='a']">
			<dc:subject>
				<xsl:value-of select="."/>
			</dc:subject>
		</xsl:for-each>
	</xsl:template>
	
	<xsl:template name="relations">
		<!-- Collections -->
		<xsl:for-each select="f[@c=225]/s[@c='a']">
			<dc:relation>
				<xsl:value-of select="."/>
			</dc:relation>
		</xsl:for-each>
		<!-- Sous collections -->
		<xsl:for-each select="f[@c=225]/s[@c='i']">
			<dc:relation>
				<xsl:value-of select="."/>
			</dc:relation>
		</xsl:for-each>
	</xsl:template>
	
</xsl:stylesheet>