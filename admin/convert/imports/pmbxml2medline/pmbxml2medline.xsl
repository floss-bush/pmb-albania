<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version = '1.0'
     xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>

<xsl:output method="text" encoding="iso-8859-1"/>

<xsl:template match="/">
		<xsl:apply-templates select="descendant::notice"/>
</xsl:template>

<xsl:template match="notice">
	<xsl:call-template name="pubmedid"/>
	<xsl:call-template name="perio_issn"/>
	<xsl:call-template name="bulletin_vol"/>
	<xsl:call-template name="bulletin_num"/>
	<xsl:call-template name="date"/>
	<xsl:call-template name="titre"/>
	<xsl:call-template name="pagination"/>
	<xsl:call-template name="resume"/>
	<xsl:call-template name="auteur"/>
	<xsl:call-template name="editeur"/>	
	<xsl:call-template name="langue"/>
	<xsl:call-template name="subtype"/>
	<xsl:call-template name="perio_titre"/>
	<xsl:call-template name="doi"/>	
	<xsl:text>&#010;</xsl:text>
</xsl:template>


<xsl:template name="titre">
		<xsl:if test="f[@c='200']/s[@c='a']">	
			<xsl:text>TI - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='200']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="auteur"> 
		<xsl:if test="f[@c='700']/s[@c='a']">
			<xsl:choose>
				<xsl:when test=" f[@c='700']/s[@c='b']">
					<xsl:text>FAU - </xsl:text>
					<xsl:value-of select="concat(normalize-space(f[@c='700']/s[@c='a']),', ',normalize-space(f[@c='700']/s[@c='b']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>AU - </xsl:text>
					<xsl:value-of select="normalize-space(f[@c='700']/s[@c='a'])"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:otherwise>
			</xsl:choose>			
		</xsl:if> 
		<xsl:if test="f[@c='710']/s[@c='a']">
				<xsl:text>AD - </xsl:text>
				<xsl:value-of select="concat(normalize-space(f[@c='710']/s[@c='a']),', ',normalize-space(f[@c='710']/s[@c='e']))"/>			
				<xsl:text>&#010;</xsl:text>			
		</xsl:if> 
		<xsl:for-each select="f[@c='701']">
			<xsl:choose>
				<xsl:when test=" s[@c='b']">
					<xsl:text>FAU - </xsl:text>
					<xsl:value-of select="concat(normalize-space(s[@c='a']),', ',normalize-space(s[@c='b']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>AU - </xsl:text>
					<xsl:value-of select="normalize-space(s[@c='a'])"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:otherwise>
			</xsl:choose>	
		</xsl:for-each>
		<xsl:for-each select="f[@c='702']">
			<xsl:choose>
				<xsl:when test=" s[@c='b']">
					<xsl:text>FAU - </xsl:text>
					<xsl:value-of select="concat(normalize-space(s[@c='a']),', ',normalize-space(s[@c='b']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>AU - </xsl:text>
					<xsl:value-of select="normalize-space(s[@c='a'])"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:otherwise>
			</xsl:choose>	
		</xsl:for-each> 
</xsl:template>
<xsl:template name="editeur">
		<xsl:if test="f[@c='210']/s[@c='c']">	
			<xsl:text>PB - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='210']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="date">
		<xsl:if test="f[@c='210']/s[@c='d']">	
			<xsl:text>DP - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='210']/s[@c='d'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="resume">
		<xsl:if test="f[@c='330']/s[@c='a']">	
			<xsl:text>AB - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='330']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="pubmedid">
		<xsl:if test="f[@c='900']/s[@c='n'] = 'pmi_xref_dbase_id'">	
			<xsl:text>PMID - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='900'][s[@c='n'] = 'pmi_xref_dbase_id']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="doi">
		<xsl:if test="f[@c='900']/s[@c='n'] = 'pmi_doi_identifier'">	
			<xsl:text>AID - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='900'][s[@c='n'] = 'pmi_doi_identifier']/s[@c='a'])"/>	
			<xsl:text> [doi] </xsl:text>		
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="subtype">
		<xsl:if test="f[@c='900']/s[@c='n'] = 'subtype'">	
			<xsl:text>PT - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='900'][s[@c='n'] = 'subtype']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="pagination">
		<xsl:if test="f[@c='215']/s[@c='a']">	
			<xsl:text>PG - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='215']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="bulletin_vol">
		<xsl:choose>
			<xsl:when test="substring-before(normalize-space(f[@c='463']/s[@c='v']),', ')">	
				<xsl:text>VI - </xsl:text>
				<xsl:value-of select="substring-after(substring-before(normalize-space(f[@c='463']/s[@c='v']),', '),'vol. ')"/>			
				<xsl:text>&#010;</xsl:text>
			</xsl:when>
			<xsl:when test="substring-after(normalize-space(f[@c='463']/s[@c='v']),'vol. ')">	
				<xsl:text>VI - </xsl:text>
				<xsl:value-of select="substring-after(normalize-space(f[@c='463']/s[@c='v']),'vol. ')"/>			
				<xsl:text>&#010;</xsl:text>
			</xsl:when>
		</xsl:choose>
</xsl:template>
<xsl:template name="bulletin_num">
		<xsl:if test="substring-after(normalize-space(f[@c='463']/s[@c='v']),'no. ')">	
			<xsl:text>IP - </xsl:text>
			<xsl:value-of select="substring-after(normalize-space(f[@c='463']/s[@c='v']),'no. ')"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="perio_titre">
		<xsl:if test="f[@c='461']/s[@c='t']">	
			<xsl:text>JT - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='461']/s[@c='t'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="perio_issn">
		<xsl:if test="f[@c='461']/s[@c='x']">	
			<xsl:text>IS - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='461']/s[@c='x'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="langue">
		<xsl:if test="f[@c='101']/s[@c='a']">	
			<xsl:text>LA - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='101']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
</xsl:stylesheet>