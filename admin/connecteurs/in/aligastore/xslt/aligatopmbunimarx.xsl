<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version = '1.0'
    xmlns:xsl='http://www.w3.org/1999/XSL/Transform'
	xmlns:aliga="http://www.aligastore.com/">

<xsl:output method="xml" indent='yes'/>

<xsl:template match="/">
	<xsl:if test="/aliga:solution/aliga:reponses/aliga:IDOFFSET">
		<xsl:apply-templates/>
	</xsl:if>
</xsl:template>
	
<xsl:template match="aliga:solution/aliga:question">
</xsl:template>
	
<xsl:template match="aliga:solution/aliga:reponses">
<unimarc>
	<notice>
		<xsl:element name="rs">*</xsl:element>
		<xsl:element name="ru">*</xsl:element>
		<xsl:element name="el">1</xsl:element>
		<xsl:element name="bl"><xsl:value-of select="./bl"/></xsl:element>
		<xsl:element name="hl"><xsl:value-of select="./hl"/></xsl:element><!-- niveau hierarchique:  -->
		<xsl:element name="dt"><xsl:value-of select="./dt"/></xsl:element>
		
		<xsl:call-template name="record_identifier"/>
		<xsl:call-template name="identifier"/>
		<xsl:call-template name="langue"/>
		<xsl:call-template name="titre"/>
		<xsl:call-template name="autorite"/>
		<xsl:call-template name="editeur"/>
		<xsl:call-template name="physical_description"/>
		<xsl:call-template name="series"/>
		<xsl:call-template name="collection"/>
		<xsl:call-template name="dewey"/>
		<xsl:call-template name="website"/>
		<xsl:call-template name="presentation"/>
		<xsl:call-template name="sommaire"/>
		<xsl:call-template name="imageinfo"/>
		<xsl:call-template name="dateparution"/>
		<xsl:comment>!!!__thumbnail_information__!!!</xsl:comment>
	</notice>
</unimarc>
</xsl:template>

<xsl:template name="record_identifier">
	<xsl:if test="aliga:IDOFFSET">
		<f c="001"><xsl:value-of select="aliga:IDOFFSET"/></f>
	</xsl:if>	
</xsl:template>
	
<xsl:template name="identifier">
	<xsl:element name="f">
		<xsl:attribute name="c">010</xsl:attribute>	
		<xsl:if test="aliga:GENCOD">
				<s c="a">
					<xsl:value-of select="aliga:GENCOD"/>
				</s>	
		</xsl:if>
		<xsl:if test="aliga:ISBN">
				<s c="a">
					<xsl:value-of select="aliga:ISBN"/>
				</s>	
		</xsl:if>
		<xsl:if test="aliga:PRIXEURO">
				<s c="d">
					<xsl:value-of select="aliga:PRIXEURO"/> euros</s>
		</xsl:if>	
	</xsl:element>
</xsl:template>
	
<xsl:template name="langue">
	<xsl:element name="f">
		<xsl:attribute name="c">101</xsl:attribute>	
		<xsl:if test="aliga:LANGUE">
				<s c="a">
					<xsl:value-of select="aliga:LANGUE"/>
				</s>	
		</xsl:if>
	</xsl:element>	
</xsl:template>
	
<xsl:template name="titre">
	<xsl:element name="f">
		<xsl:attribute name="c">200</xsl:attribute>	
		<xsl:if test="aliga:TITRE">
				<s c="a">
					<xsl:value-of select="aliga:TITRE"/>
				</s>	
		</xsl:if>
	</xsl:element>	
</xsl:template>

<xsl:template name="autorite_old">
	<xsl:element name="f">
		<xsl:attribute name="c">700</xsl:attribute>	
		<xsl:if test="aliga:AUTEURS">
				<s c="a">
					<xsl:value-of select="aliga:AUTEURS"/>
				</s>
		</xsl:if>
	</xsl:element>
</xsl:template>

<xsl:template name="autorite">
	<xsl:variable name="auteur">
		<xsl:choose>
			<xsl:when test="aliga:AUTEURS">
				<xsl:value-of select="aliga:AUTEURS"/>
			</xsl:when>
			<xsl:otherwise>Sans auteur</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<f c="700">
		<xsl:choose>
			<xsl:when test="substring-after($auteur,';')">
				<s c="a"><xsl:value-of select="normalize-space(substring-before($auteur,';'))"/></s>
			</xsl:when>
			<xsl:otherwise>
				<s c="a"><xsl:value-of select="$auteur"/></s>
			</xsl:otherwise>
		</xsl:choose>
	</f>
	<xsl:if test="substring-after($auteur,';')">
		<xsl:call-template name="explose">
			<xsl:with-param name="chaine" select="substring-after($auteur,';')"/>
			<xsl:with-param name="field">701</xsl:with-param>
			<xsl:with-param name="subfield">a</xsl:with-param>
		</xsl:call-template>
	</xsl:if>
</xsl:template>
	
<xsl:template name="editeur">
	<xsl:element name="f">
		<xsl:attribute name="c">210</xsl:attribute>	
		<xsl:if test="aliga:EDITEUR">
				<s c="c">
					<xsl:value-of select="aliga:EDITEUR"/>
				</s>
		</xsl:if>
	</xsl:element>	
</xsl:template>
	
<xsl:template name="physical_description">
	<xsl:element name="f">
		<xsl:attribute name="c">215</xsl:attribute>
		<xsl:if test="aliga:NBRPAGES">
			<s c="a">
<xsl:value-of select="aliga:NBRPAGES"/> pages</s>
		</xsl:if>
		<s c="d">
			<xsl:if test="aliga:LONGUEUR">Longueur: <xsl:value-of select="aliga:LONGUEUR"/>
			</xsl:if>
			<xsl:if test="aliga:LARGEUR">; Largeur: <xsl:value-of select="aliga:LARGEUR"/>
			</xsl:if>
			<xsl:if test="aliga:POIDS">; Poids: <xsl:value-of select="aliga:POIDS"/>
			</xsl:if>
		</s>
	</xsl:element>	
</xsl:template>
	
<xsl:template name="series">
	<xsl:element name="f">
		<xsl:attribute name="c">225</xsl:attribute>	
		<xsl:if test="aliga:COLLECTION">
				<s c="a">
					<xsl:value-of select="aliga:COLLECTION"/>
				</s>
		</xsl:if>
	</xsl:element>	
</xsl:template>
	
<xsl:template name="collection">
	<xsl:element name="f">
		<xsl:attribute name="c">410</xsl:attribute>	
		<xsl:if test="aliga:COLLECTION">
				<s c="a">
					<xsl:value-of select="aliga:COLLECTION"/>
				</s>
		</xsl:if>
	</xsl:element>	
</xsl:template>

<xsl:template name="dewey">
	<xsl:element name="f">
		<xsl:attribute name="c">676</xsl:attribute>	
		<xsl:if test="aliga:DEWEYCLASSE">
			<s c="a">
			<xsl:choose>
				<xsl:when test="contains(aliga:DEWEYCLASSE,'(')">
					<xsl:value-of select="normalize-space(substring-before(aliga:DEWEYCLASSE,'('))"/>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="aliga:DEWEYCLASSE"/>
				</xsl:otherwise>
			</xsl:choose>
			</s>
		</xsl:if>
	</xsl:element>	
</xsl:template>

<xsl:template name="website">
	<xsl:element name="f">
		<xsl:attribute name="c">856</xsl:attribute>	
		<xsl:if test="aliga:WEBSITE">
				<s c="u">
					<xsl:value-of select="aliga:WEBSITE"/>
				</s>
		</xsl:if>
	</xsl:element>
</xsl:template>

<xsl:template name="presentation">
	<xsl:if test="aliga:PRESENTATION">
		<xsl:element name="f">
			<xsl:attribute name="c">330</xsl:attribute>	
				<s c="a">
					<xsl:for-each select="/aliga:solution/aliga:reponses/aliga:reponse[starts-with(@name,'PRESENTATION')]">
						<xsl:value-of select="."></xsl:value-of>
					</xsl:for-each>
				</s>
		</xsl:element>
	</xsl:if>
</xsl:template>

<xsl:template name="imageinfo">
	<xsl:choose>
		<xsl:when test="aliga:IMAGE=1">
			<xsl:comment>!!!__IMAGEINFO_YES__!!!</xsl:comment>			
		</xsl:when>
		<xsl:otherwise>
			<xsl:comment>!!!__IMAGEINFO_NO__!!!</xsl:comment>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>
	
<xsl:template name="sommaire">
	<xsl:if test="aliga:SOMMAIRE">
		<xsl:element name="f">
			<xsl:attribute name="c">327</xsl:attribute>	
				<s c="a">
					<xsl:for-each select="/aliga:solution/aliga:reponses/aliga:reponse[starts-with(@name,'SOMMAIRE')]">
						<xsl:value-of select="."></xsl:value-of>
					</xsl:for-each>
				</s>
		</xsl:element>
	</xsl:if>
</xsl:template>
	
<xsl:template name="dateparution">
	<xsl:if test="aliga:DATEPARUTION">
		<xsl:element name="f">
			<xsl:attribute name="c">910</xsl:attribute>	
				<s c="a">
					<xsl:value-of select="aliga:DATEPARUTION"/>
				</s>		
		</xsl:element>
	</xsl:if>
</xsl:template>	
		
</xsl:stylesheet> 
