<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version = '1.0'
     xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>

<xsl:output method="text" encoding="iso-8859-1"/>

<xsl:template match="/">
		<xsl:apply-templates select="descendant::notice"/>
</xsl:template>

<xsl:template match="notice">
	<xsl:call-template name="subtype"/>
	<xsl:call-template name="titre"/>
	<xsl:call-template name="perio_titre"/>	
	<xsl:call-template name="bulletin_vol"/>
	<xsl:call-template name="bulletin_num"/>
	<xsl:call-template name="pagination"/>
	<xsl:call-template name="date"/>
	<xsl:call-template name="perio_issn"/>
	<xsl:call-template name="auteur"/>
	<xsl:call-template name="resume"/>
	<xsl:call-template name="editeur"/>
	<xsl:call-template name="keywords"/>
	<xsl:call-template name="doi"/>	
	<xsl:call-template name="pubmedid"/>
	<xsl:call-template name="notes"/>
	<xsl:text>&#010;</xsl:text>
</xsl:template>


<xsl:template name="titre">
		<xsl:if test="f[@c='200']/s[@c='a']">	
			<xsl:text>TI  - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='200']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="auteur"> 
		<xsl:if test="f[@c='700']/s[@c='a']">
			<xsl:choose>
				<xsl:when test=" f[@c='700']/s[@c='b']">
					<xsl:text>A1  - </xsl:text>
					<xsl:value-of select="concat(normalize-space(f[@c='700']/s[@c='a']),', ',normalize-space(f[@c='700']/s[@c='b']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>A1  - </xsl:text>
					<xsl:value-of select="normalize-space(f[@c='700']/s[@c='a'])"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:otherwise>
			</xsl:choose>			
		</xsl:if> 
		<xsl:for-each select="f[@c='701']">
			<xsl:choose>
				<xsl:when test=" s[@c='b']">
					<xsl:text>AU  - </xsl:text>
					<xsl:value-of select="concat(normalize-space(s[@c='a']),', ',normalize-space(s[@c='b']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>AU  - </xsl:text>
					<xsl:value-of select="normalize-space(s[@c='a'])"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:otherwise>
			</xsl:choose>	
		</xsl:for-each>
		<xsl:for-each select="f[@c='702']">
			<xsl:choose>
				<xsl:when test=" s[@c='b']">
					<xsl:text>A2  - </xsl:text>
					<xsl:value-of select="concat(normalize-space(s[@c='a']),', ',normalize-space(s[@c='b']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>A2  - </xsl:text>
					<xsl:value-of select="normalize-space(s[@c='a'])"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:otherwise>
			</xsl:choose>	
		</xsl:for-each> 
		<xsl:if test="f[@c='710']/s[@c='a']">
			<xsl:choose>
				<xsl:when test=" ((f[@c='710']/s[@c='K']!='') and (f[@c='710']/s[@c='M']!='')) ">
					<xsl:text>AD  - </xsl:text>
					<xsl:value-of select="concat(normalize-space(f[@c='710']/s[@c='a']),', ',normalize-space(f[@c='710']/s[@c='K']),', ',normalize-space(f[@c='710']/s[@c='M']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="((s[@c='K']!='') and (s[@c='M']=''))">
					<xsl:text>AD  - </xsl:text>
					<xsl:value-of select="concat(normalize-space(f[@c='710']/s[@c='a']),', ',normalize-space(f[@c='710']/s[@c='K']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>AD  - </xsl:text>
					<xsl:value-of select="normalize-space(f[@c='710']/s[@c='a'])"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:otherwise>
			</xsl:choose>			
		</xsl:if> 
		<xsl:for-each select="f[@c='711']">
			<xsl:choose>
				<xsl:when test=" ((s[@c='K']!='') and (s[@c='M']!='')) ">
					<xsl:text>AD  - </xsl:text>
					<xsl:value-of select="concat(normalize-space(s[@c='a']),', ',normalize-space(s[@c='K']),', ',normalize-space(s[@c='M']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test=" ((s[@c='K']!='') and (s[@c='M']='')) ">
					<xsl:text>AD  - </xsl:text>
					<xsl:value-of select="concat(normalize-space(s[@c='a']),', ',normalize-space(s[@c='K']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>AD  - </xsl:text>
					<xsl:value-of select="normalize-space(s[@c='a'])"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:otherwise>
			</xsl:choose>	
		</xsl:for-each> 
</xsl:template>
<xsl:template name="editeur">
		<xsl:if test="f[@c='210']/s[@c='c']">	
			<xsl:text>PB  - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='210']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="date">
		<xsl:if test="f[@c='210']/s[@c='d']">	
			<xsl:text>PY  - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='210']/s[@c='d'])"/>	
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="resume">
		<xsl:if test="f[@c='330']/s[@c='a']">	
			<xsl:text>AB  - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='330']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="pubmedid">
		<xsl:if test="f[@c='900']/s[@c='n'] = 'pmi_xref_dbase_id'">	
			<xsl:text>N1  - PubMed ID: </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='900'][s[@c='n'] = 'pmi_xref_dbase_id']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="doi">
		<xsl:if test="f[@c='900']/s[@c='n'] = 'pmi_doi_identifier'">	
			<xsl:text>N1  - doi: </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='900'][s[@c='n'] = 'pmi_doi_identifier']/s[@c='a'])"/>		
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="subtype">
		<xsl:if test="f[@c='900']/s[@c='n'] = 'subtype'">	
			<xsl:text>TY  - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='900'][s[@c='n'] = 'subtype']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="pagination">
	<xsl:if test="f[@c='215']/s[@c='a']">
	<xsl:choose>
		<xsl:when test="substring-after(normalize-space(f[@c='215']/s[@c='a']),'-')">	
			<xsl:text>SP  - </xsl:text>
			<xsl:value-of select="substring-before(normalize-space(f[@c='215']/s[@c='a']),'-')"/>			
			<xsl:text>&#010;</xsl:text>
			<xsl:text>EP  - </xsl:text>
			<xsl:value-of select="substring-after(normalize-space(f[@c='215']/s[@c='a']),'-')"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:when>
		<xsl:otherwise>
			<xsl:text>SP  - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='215']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:otherwise>
	</xsl:choose>
	</xsl:if>
</xsl:template>
<xsl:template name="bulletin_vol">
		<xsl:choose>
			<xsl:when test="substring-before(normalize-space(f[@c='463']/s[@c='v']),', ')">	
				<xsl:text>VL  - </xsl:text>
				<xsl:value-of select="substring-after(substring-before(normalize-space(f[@c='463']/s[@c='v']),', '),'vol. ')"/>			
				<xsl:text>&#010;</xsl:text>
			</xsl:when>
			<xsl:when test="substring-after(normalize-space(f[@c='463']/s[@c='v']),'vol. ')">	
				<xsl:text>VL  - </xsl:text>
				<xsl:value-of select="substring-after(normalize-space(f[@c='463']/s[@c='v']),'vol. ')"/>			
				<xsl:text>&#010;</xsl:text>
			</xsl:when>
		</xsl:choose>
</xsl:template>
<xsl:template name="bulletin_num">
		<xsl:if test="substring-after(normalize-space(f[@c='463']/s[@c='v']),'no. ')">	
			<xsl:text>IS  - </xsl:text>
			<xsl:value-of select="substring-after(normalize-space(f[@c='463']/s[@c='v']),'no. ')"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="perio_titre">
		<xsl:if test="f[@c='461']/s[@c='t']">	
			<xsl:text>JF  - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='461']/s[@c='t'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="perio_issn">
		<xsl:if test="f[@c='461']/s[@c='x']">	
			<xsl:text>SN  - </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='461']/s[@c='x'])"/>		
			<xsl:text> (ISSN)</xsl:text>
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="keywords">
		<xsl:for-each select="f[@c='610']/s[@c='a']">
			<xsl:call-template name="explose_kw">
				<xsl:with-param name="motcle" select="normalize-space(.)"/>
			</xsl:call-template>		
		</xsl:for-each>
</xsl:template>
<xsl:template name="explose_kw">
	<xsl:param name="motcle"/>
	<xsl:choose>
		<xsl:when test="substring-after($motcle,' ; ')">
			<xsl:text>KW  - </xsl:text>
			<xsl:value-of select="substring-before($motcle,' ; ')"/>	
			<xsl:text>&#010;</xsl:text>
			<xsl:call-template name="explose_kw">
					<xsl:with-param name="motcle" select="substring-after($motcle,' ; ')"/>
			</xsl:call-template>
		</xsl:when>
		<xsl:otherwise>
			<xsl:text>KW  - </xsl:text>
			<xsl:value-of select="$motcle"/>
			<xsl:text>&#010;</xsl:text>
		</xsl:otherwise>
	</xsl:choose>				
</xsl:template>
<xsl:template name="notes">
		<xsl:for-each select="f[@c='300']/s[@c='a']">
			<xsl:call-template name="explose_note">
				<xsl:with-param name="contenu" select="."/>
			</xsl:call-template>		
		</xsl:for-each>
</xsl:template>
<xsl:template name="explose_note">
	<xsl:param name="contenu"/>
	<xsl:choose>
		<xsl:when test="substring-after($contenu,'&#010;')">
			<xsl:text>N1  - </xsl:text>
			<xsl:value-of select="substring-before($contenu,'&#010;')"/>	
			<xsl:text>&#010;</xsl:text>
			<xsl:call-template name="explose_note">
					<xsl:with-param name="contenu" select="substring-after($contenu,'&#010;')"/>
			</xsl:call-template>
		</xsl:when>
		<xsl:otherwise>
			<xsl:text>N1  - </xsl:text>
			<xsl:value-of select="$contenu"/>
			<xsl:text>&#010;</xsl:text>
		</xsl:otherwise>
	</xsl:choose>				
</xsl:template>
</xsl:stylesheet>