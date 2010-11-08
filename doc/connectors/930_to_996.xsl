<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:sdl="sudoc_location"
xmlns:exsl="http://exslt.org/common" extension-element-prefixes="exsl">
	
<xsl:output method="xml" indent='yes'/>

<xsl:template name="neuf_cent_trente">
	<xsl:for-each select="./f[@c='930']">
		<xsl:element name="f">
			<xsl:attribute name="c">996</xsl:attribute>
			<xsl:attribute name="ind">  </xsl:attribute>

			<xsl:for-each select="./s[@c='c']">
				<xsl:element name="s">
					<xsl:attribute name="c">x</xsl:attribute>
					<xsl:value-of select="."/>
				</xsl:element>	
			</xsl:for-each>

			<xsl:for-each select="./s[@c='5']">
				<xsl:element name="s">
					<xsl:attribute name="c">f</xsl:attribute>
					<xsl:value-of select="."/>
				</xsl:element>	
			</xsl:for-each>
			
			<xsl:for-each select="./s[@c='j']">
				<xsl:element name="s">
					<xsl:variable name="buffer"><xsl:value-of select="."/></xsl:variable>
					<xsl:attribute name="c">1</xsl:attribute>
					<xsl:choose>
						<xsl:when test="$buffer='u'">Disponible pour le PEB</xsl:when>
						<xsl:when test="$buffer='s'">PEB soumis à condition</xsl:when>
						<xsl:when test="$buffer='g'">Non disponible pour le PEB</xsl:when>
						<xsl:when test="$buffer='b'">Consultable sur place dans le site demandeur</xsl:when>
						<xsl:when test="$buffer='f'">Disponible sous forme de reproduction pour le PEB</xsl:when>
						<xsl:otherwise></xsl:otherwise>
					</xsl:choose>
				</xsl:element>	
			</xsl:for-each>
			
			<xsl:for-each select="./s[@c='a']">
				<xsl:element name="s">
					<xsl:attribute name="c">k</xsl:attribute>
					<xsl:value-of select="."/>
				</xsl:element>	
			</xsl:for-each>

			<xsl:for-each select="./s[@c='g']">
				<xsl:element name="s">
					<xsl:attribute name="c">u</xsl:attribute>
					<xsl:value-of select="."/>
				</xsl:element>	
			</xsl:for-each>
						
			<xsl:for-each select="./s[@c='b']">
				<xsl:element name="s">
					<xsl:attribute name="c">v</xsl:attribute>
					<xsl:value-of select="."/>
				</xsl:element>	
			</xsl:for-each>			
			
		</xsl:element>
	</xsl:for-each>
</xsl:template>
	
<xsl:template match="/notice">
	<notice>
	    <xsl:apply-templates />
		<xsl:call-template name="neuf_cent_trente"/>
	</notice>
</xsl:template>


<xsl:template match="*">
  <xsl:copy>
    <xsl:apply-templates select="@*" />
    <xsl:apply-templates />
  </xsl:copy>
</xsl:template>



<xsl:template match="@*">
  <xsl:copy-of select="." />
</xsl:template>


<!--
<xsl:template match="node()|@*">
    <xsl:copy>
      <xsl:apply-templates select="@*|node()"/>
    </xsl:copy>
</xsl:template>
-->

</xsl:stylesheet>
