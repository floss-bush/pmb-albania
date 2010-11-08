<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version = '1.0' 
	xmlns:xsl='http://www.w3.org/1999/XSL/Transform'
>
	
	<xsl:output method="xml" indent='yes' encoding="iso-8859-15"/>
	
	<xsl:template match="/LOB">
		<unimarc>
			<xsl:apply-templates select="LBook"/>
		</unimarc>
	</xsl:template>

	<xsl:template match="LBook">
		<notice>
			<xsl:element name="rs">*</xsl:element>
			<xsl:element name="ru">*</xsl:element>
			<xsl:element name="el">1</xsl:element>
			<xsl:element name="bl">m</xsl:element>
			<xsl:element name="hl">0</xsl:element>
			<xsl:element name="dt">a</xsl:element>
			
			<xsl:call-template name="ID"/>
			<xsl:call-template name="ISBN"/>
			<xsl:call-template name="TITRE"/>
			<xsl:call-template name="EDITEUR"/>
			<xsl:call-template name="COLLATION"/>
			<xsl:call-template name="NOTES"/>
			<xsl:call-template name="AUTEUR"/>
			<xsl:call-template name="LIENS"/>
		</notice>
	</xsl:template>
	
	<xsl:template name="ID">
		<f c="001">
			<xsl:value-of select="BTitleID"/>
		</f>
	</xsl:template>
	
	<xsl:template name="ISBN">
		<xsl:if test="(BISBN and BISBN!='#') or (BISBN13 and BISBN13!='#') or (BPrice and BPrice!='#')">
			<f c="010">
				<xsl:choose>
					<xsl:when test="BISBN13">
						<s c="a"><xsl:value-of select="BISBN13"/></s>		
					</xsl:when>
					<xsl:when test="BISBN">
						<s c="a"><xsl:value-of select="BISBN"/></s>	
					</xsl:when>
				</xsl:choose>
				<xsl:if test="BPrice">
					<s c="d"><xsl:value-of select="concat(normalize-space(BPrice),' ',BWaehrung)"/></s>
				</xsl:if>
			</f>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="TITRE">
		<f c="200">
			<s c="a"><xsl:value-of select="BTitle"/></s>
			<xsl:if test="BSubtitle and BSubtitle!='#'">
				<s c="e"><xsl:value-of select="BSubtitle"/></s>
			</xsl:if>
		</f>
	</xsl:template>
	
	<xsl:template name="EDITEUR">
		<xsl:if test="(BPublisher and BPublisher!='#') or (BYear and BYear!='#')">
			<f c="210">
				<xsl:if test="BPublisher and BPublisher!='#'">
					<s c="c"><xsl:value-of select="BPublisher"/></s>
				</xsl:if>
				<xsl:if test="BYear and BYear!='#'">
					<s c="d"><xsl:value-of select="BYear"/></s>
				</xsl:if>
			</f>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="COLLATION">
		<xsl:if test="BBiblio and BBiblio!='#'">
			<f c="215">
				<s c="a"><xsl:value-of select="BBiblio"/></s>
			</f>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="NOTES">
		<xsl:if test="BAnnotation and normalize-space(BAnnotation)">
			<f c="330">
				<s c="a"><xsl:value-of select="BAnnotation"/></s>
			</f>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="explose">
		<xsl:param name="chaine"/>
		<xsl:param name="separator"/>
		<xsl:choose>
			<xsl:when test="substring-before($chaine,$separator)">
				<f c="701">
					<s c="a"><xsl:value-of select="normalize-space(substring-before($chaine,$separator))"/></s>
				</f>
				<xsl:call-template name="explose">
					<xsl:with-param name="chaine"><xsl:value-of select="substring-after($chaine,$separator)"/></xsl:with-param>
					<xsl:with-param name="separator"><xsl:value-of select="$separator"/></xsl:with-param>
				</xsl:call-template>
			</xsl:when>
			<xsl:otherwise>
				<f c="701">
					<s c="a"><xsl:value-of select="normalize-space($chaine)"/></s>
				</f>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template name="AUTEUR">
		<xsl:if test="BAuthor and BAuthor!='#'">
			<xsl:choose>
				<xsl:when test="substring-before(BAuthor,';')">
					<f c="700">
						<s c="a"><xsl:value-of select="normalize-space(substring-before(BAuthor,';'))"/></s>
					</f>
					<xsl:call-template name="explose">
						<xsl:with-param name="chaine"><xsl:value-of select="substring-after(BAuthor,';')"/></xsl:with-param>
						<xsl:with-param name="separator"><xsl:text>;</xsl:text></xsl:with-param>
					</xsl:call-template>
				</xsl:when>
				<xsl:when test="substring-before(BAuthor,'   ')">
					<f c="700">
						<s c="a"><xsl:value-of select="normalize-space(substring-before(BAuthor,'   '))"/></s>
					</f>
					<xsl:call-template name="explose">
						<xsl:with-param name="chaine"><xsl:value-of select="substring-after(BAuthor,'   ')"/></xsl:with-param>
						<xsl:with-param name="separator"><xsl:text><![CDATA[   ]]></xsl:text></xsl:with-param>
					</xsl:call-template>
				</xsl:when>
				<xsl:otherwise>
					<f c="700">
						<s c="a"><xsl:value-of select="normalize-space(BAuthor)"/></s>
					</f>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="LIENS">
		<xsl:if test="BPicture">
			<f c="896">
				<s c="a"><xsl:value-of select="BPicture"/></s>
			</f>
		</xsl:if>
	</xsl:template>
</xsl:stylesheet>