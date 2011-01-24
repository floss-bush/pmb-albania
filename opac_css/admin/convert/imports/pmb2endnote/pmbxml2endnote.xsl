<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version = '1.0'
     xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>

<xsl:output method="text" encoding="iso-8859-1"/>

<xsl:template match="/">
		<xsl:apply-templates select="descendant::notice"/>
</xsl:template>

<xsl:template match="notice">
	<xsl:call-template name="subtype"/>
	<xsl:call-template name="auteur"/>	
	<xsl:call-template name="titres"/>
	<xsl:call-template name="collection"/>
	<xsl:call-template name="publisher"/>
	<xsl:call-template name="keywords"/>
	<xsl:call-template name="date"/>
	<xsl:call-template name="bulletin_vol"/>
	<xsl:call-template name="pagination"/>
	<xsl:call-template name="bulletin_num"/>
	<xsl:call-template name="perio_issn"/>	
	<xsl:call-template name="perio_titre"/>	
	<xsl:call-template name="resume"/>
	<xsl:call-template name="url"/>
	<xsl:call-template name="isbn"/>
	<xsl:call-template name="issn"/>
	<xsl:call-template name="notes"/>
	<xsl:text>&#010;</xsl:text>
</xsl:template>


<xsl:template name="titres">
		<xsl:if test="f[@c='200']/s[@c='a'] or f[@c='200']/s[@c='c'] or f[@c='200']/s[@c='d'] or f[@c='200']/s[@c='e']">	
			<xsl:text>%T </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='200']/s[@c='a'])"/>
			<xsl:if test="f[@c='200']/s[@c='d']">
				<xsl:text> = </xsl:text>
				<xsl:value-of select="normalize-space(f[@c='200']/s[@c='d'])"/>
			</xsl:if>
			<xsl:if test="f[@c='200']/s[@c='e']">
				<xsl:text> : </xsl:text>
				<xsl:value-of select="normalize-space(f[@c='200']/s[@c='e'])"/>
			</xsl:if>
			<xsl:if test="f[@c='200']/s[@c='c']">
				<xsl:text> ; </xsl:text>
				<xsl:value-of select="normalize-space(f[@c='200']/s[@c='c'])"/>
			</xsl:if>				
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="auteur"> 
		<xsl:if test="f[@c='700']/s[@c='a']">
			<xsl:choose>
				<xsl:when test=" f[@c='700']/s[@c='b']">
					<xsl:text>%A </xsl:text>
					<xsl:value-of select="concat(normalize-space(f[@c='700']/s[@c='a']),', ',normalize-space(f[@c='700']/s[@c='b']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>%A </xsl:text>
					<xsl:value-of select="normalize-space(f[@c='700']/s[@c='a'])"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:otherwise>
			</xsl:choose>			
		</xsl:if> 
		<xsl:for-each select="f[@c='701']">
			<xsl:choose>
				<xsl:when test=" s[@c='b']">
					<xsl:text>%A </xsl:text>
					<xsl:value-of select="concat(normalize-space(s[@c='a']),', ',normalize-space(s[@c='b']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>%A </xsl:text>
					<xsl:value-of select="normalize-space(s[@c='a'])"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:otherwise>
			</xsl:choose>	
		</xsl:for-each>
		<xsl:for-each select="f[@c='702']">
			<xsl:choose>
				<xsl:when test=" s[@c='b']">
					<xsl:text>%E </xsl:text>
					<xsl:value-of select="concat(normalize-space(s[@c='a']),', ',normalize-space(s[@c='b']))"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>%E </xsl:text>
					<xsl:value-of select="normalize-space(s[@c='a'])"/>			
					<xsl:text>&#010;</xsl:text>
				</xsl:otherwise>
			</xsl:choose>	
		</xsl:for-each>
		<xsl:if test="f[@c='710']">
			<xsl:text>%+ </xsl:text>
			<xsl:value-of select="concat(normalize-space(f[@c='710']/s[@c='a']),', ',translate(normalize-space(f[@c='710']/s[@c='e']),';',','))"/>
			<xsl:text>&#010;</xsl:text>
		</xsl:if> 
		<xsl:for-each select="f[@c='711']">
			<xsl:text>%+ </xsl:text>
			<xsl:value-of select="concat(normalize-space(s[@c='a']),', ',translate(normalize-space(s[@c='e']),';',','))"/>
			<xsl:text>&#010;</xsl:text>	
		</xsl:for-each>
		<xsl:for-each select="f[@c='712']">
			<xsl:text>%+ </xsl:text>
			<xsl:value-of select="concat(normalize-space(s[@c='a']),', ',translate(normalize-space(s[@c='e']),';',','))"/>
			<xsl:text>&#010;</xsl:text>	
		</xsl:for-each>  
</xsl:template>
<xsl:template name="collection">
		<xsl:if test="f[@c='225']/s[@c='a']">
			<xsl:text>%S </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='225']/s[@c='a'])"/>
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
		<xsl:if test="f[@c='225']/s[@c='v']">
			<xsl:text>%7 </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='225']/s[@c='v'])"/>
			<xsl:text>&#010;</xsl:text>
		</xsl:if>				
</xsl:template>
<xsl:template name="date">
		<xsl:if test="f[@c='210']/s[@c='d']">	
			<xsl:text>%D </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='210']/s[@c='d'])"/>	
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="resume">
		<xsl:if test="f[@c='330']/s[@c='a']">	
			<xsl:text>%X </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='330']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="subtype">
		<xsl:choose>
		<xsl:when test="f[@c='900']/s[@c='n'] = 'subtype'">	
			<xsl:text>%0 </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='900'][s[@c='n'] = 'subtype']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:when>
		<xsl:when test="bl='a' and hl='2'">
			<xsl:text>%0 Journal Article</xsl:text>
			<xsl:text>&#010;</xsl:text>
		</xsl:when>
		<xsl:when test="bl='m' and hl='0'">
			<!-- monographie -->
			<xsl:choose>
				<xsl:when test="dt='a'">
					<!-- texte imprimé -->
					<xsl:text>%0 Book</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="dt='b'">
					<!-- texte manuscrit -->
					<xsl:text>%0 Personal Communication</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="dt='c'">
					<!-- partition musicale imprimée -->
					<xsl:text>%0 Artwork</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="dt='d'">
					<!-- partition musicale manuscrite -->
					<xsl:text>%0 Artwork</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="dt='e'">
					<!-- carte imprimée -->
					<xsl:text>%0 Map</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="dt='f'">
					<!-- carte manuscrite -->
					<xsl:text>%0 Map</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="dt='g'">
					<!-- vidéo -->
					<xsl:text>%0 Audiovisual Material</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="dt='i'">
					<!-- enregistrement sonore non musical -->
					<xsl:text>%0 Audiovisual Material</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="dt='j'">
					<!-- enregistrement sonore musical -->
					<xsl:text>%0 Audiovisual Material</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="dt='k'">
					<!-- document graphique -->
					<xsl:text>%0 Artwork</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="dt='l'">
					<!-- ressources électroniques -->
					<xsl:text>%0 Electronic Source</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="dt='m'">
					<!-- document multimédia -->
					<xsl:text>%0 Generic</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:when test="dt='r'">
					<!-- objet -->
					<xsl:text>%0 Generic</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>%0 Book</xsl:text>
					<xsl:text>&#010;</xsl:text>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:when>
		<xsl:when test="bl='s' and hl='1'">
			<xsl:text>%0 Newspaper</xsl:text>
			<xsl:text>&#010;</xsl:text>
		</xsl:when>
		<xsl:otherwise>
			<xsl:text>%0 Book</xsl:text>
			<xsl:text>&#010;</xsl:text>
		</xsl:otherwise>
		</xsl:choose>
</xsl:template>
<xsl:template name="pagination">
	<xsl:if test="f[@c='215']/s[@c='a']">
		<xsl:text>%P </xsl:text>
		<xsl:value-of select="normalize-space(f[@c='215']/s[@c='a'])"/>			
		<xsl:text>&#010;</xsl:text>
	</xsl:if>
</xsl:template>
<xsl:template name="bulletin_vol">
		<xsl:choose>
			<xsl:when test="substring-after(substring-before(normalize-space(f[@c='463']/s[@c='v']),', '),'vol. ')">	
				<xsl:text>%V </xsl:text>
				<xsl:value-of select="substring-after(substring-before(normalize-space(f[@c='463']/s[@c='v']),', '),'vol. ')"/>			
				<xsl:text>&#010;</xsl:text>
			</xsl:when>
			<xsl:when test="substring-after(normalize-space(f[@c='463']/s[@c='v']),'vol. ')">	
				<xsl:text>%V </xsl:text>
				<xsl:value-of select="substring-after(normalize-space(f[@c='463']/s[@c='v']),'vol. ')"/>			
				<xsl:text>&#010;</xsl:text>
			</xsl:when>
		</xsl:choose>
</xsl:template>
<xsl:template name="bulletin_num">
	<xsl:choose>
		<xsl:when test="substring-after(normalize-space(f[@c='463']/s[@c='v']),'no. ')">
			<xsl:text>%N </xsl:text>
			<xsl:value-of select="substring-after(normalize-space(f[@c='463']/s[@c='v']),'no. ')"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:when>
		<xsl:when test="normalize-space(f[@c='463']/s[@c='v'])">
			<xsl:text>%N </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='463']/s[@c='v'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:when>
	</xsl:choose>
</xsl:template>
<xsl:template name="perio_titre">
		<xsl:if test="f[@c='461']/s[@c='t']">	
			<xsl:text>%J </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='461']/s[@c='t'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="perio_issn">
		<xsl:if test="f[@c='461']/s[@c='x']">	
			<xsl:text>%@ </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='461']/s[@c='x'])"/>		
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
		<xsl:when test="substring-after($motcle,';')">
			<xsl:text>%K </xsl:text>
			<xsl:value-of select="substring-before($motcle,';')"/>	
			<xsl:text>&#010;</xsl:text>
			<xsl:call-template name="explose_kw">
					<xsl:with-param name="motcle" select="substring-after($motcle,';')"/>
			</xsl:call-template>
		</xsl:when>
		<xsl:otherwise>
			<xsl:text>%K </xsl:text>
			<xsl:value-of select="$motcle"/>
			<xsl:text>&#010;</xsl:text>
		</xsl:otherwise>
	</xsl:choose>				
</xsl:template>
<xsl:template name="publisher">
		<xsl:if test="f[@c='210']/s[@c='c']">	
			<xsl:text>%I </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='210']/s[@c='c'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
		<xsl:if test="f[@c='210']/s[@c='a']">	
			<xsl:text>%C </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='210']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="url">
		<xsl:if test="f[@c='856']/s[@c='u']">	
			<xsl:text>%U </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='856']/s[@c='u'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="isbn">
		<xsl:if test="f[@c='010']/s[@c='a']">	
			<xsl:text>%@ </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='010']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="issn">
		<xsl:if test="f[@c='011']/s[@c='a']">	
			<xsl:text>%@ </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='011']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
<xsl:template name="notes">
		<xsl:if test="f[@c='300']/s[@c='a']">	
			<xsl:text>%Z </xsl:text>
			<xsl:value-of select="normalize-space(f[@c='300']/s[@c='a'])"/>			
			<xsl:text>&#010;</xsl:text>
		</xsl:if>
</xsl:template>
</xsl:stylesheet>