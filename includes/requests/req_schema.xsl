<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version = '1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform' >

<xsl:output method="xml" version="1.0" encoding="iso-8859-1" indent="yes"/>


<xsl:template match="dezign">
	<schema>
	<tables>
		<xsl:for-each select="/dezign/VERSION/DATADICT/ENTITIES/ENT">
			<xsl:if test="/dezign/VERSION/DATADICT/ENTITIES/ENT/PKCON/ATTRIBUTEIDS">
				<xsl:call-template name="entites"/>
			</xsl:if>
		</xsl:for-each>
	</tables>
	<relations>
		<xsl:for-each select="/dezign/VERSION/DATADICT/RELATIONSHIPS/REL">
			<xsl:call-template name="relations"/>
		</xsl:for-each>
	</relations>
	</schema>
</xsl:template>


<xsl:template name="entites">
	<xsl:element name="table">
		<xsl:attribute name="name"><xsl:value-of select="NAME"/></xsl:attribute>
		<xsl:attribute name="id"><xsl:value-of select="ID"/></xsl:attribute>
		<xsl:attribute name="desc">
			<xsl:choose>
				<xsl:when test="contains(DESC,'&#xA;')">
					<xsl:value-of select="substring-before(DESC,'&#xA;')"/>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="DESC"/>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:attribute>
		<xsl:attribute name="pkid">
			<xsl:call-template name="concat_virgule">
				<xsl:with-param name="attr" select="./PKCON/ATTRIBUTEIDS/ATTRIBUTEID"/>
				<xsl:with-param name="chaine"/>
				<xsl:with-param name="cpt" select="1"/>
			</xsl:call-template>
		</xsl:attribute>
		<xsl:call-template name="attributs">
			<xsl:with-param name="noeud" select="./ATTRIBUTES"/>
			<xsl:with-param name="id_entite" select="ID"/>
		</xsl:call-template>
		</xsl:element>
</xsl:template>


<xsl:template name="attributs">
	<xsl:param name="noeud"/>
	<xsl:param name="id_entite"/>
	<fields>
		<xsl:for-each select="$noeud/ATTR">
			<xsl:call-template name="attribut">
				<xsl:with-param name="id_entite" select="$id_entite"/>
			</xsl:call-template>
		</xsl:for-each>
	</fields>
</xsl:template>


<xsl:template name="attribut">
	<xsl:param name="id_entite"/>
	<xsl:element name="field">
		<xsl:attribute name="name"><xsl:value-of select="NAME"/></xsl:attribute>
		<xsl:attribute name="id"><xsl:value-of select="concat($id_entite,'-',ID)"/></xsl:attribute>
		<xsl:attribute name="desc">
		<xsl:choose>
			<xsl:when test="contains(DESC,'&#xA;')">
				<xsl:value-of select="substring-before(DESC,'&#xA;')"/>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="DESC"/>
			</xsl:otherwise>
		</xsl:choose>		
		</xsl:attribute>
		<xsl:attribute name="type"><xsl:value-of select="DT/DTLISTNAME"/></xsl:attribute>
		<xsl:attribute name="length"><xsl:value-of select="DT/LE"/></xsl:attribute>
		<xsl:attribute name="precision"><xsl:value-of select="DT/PR"/></xsl:attribute>
		<xsl:attribute name="unsigned"><xsl:value-of select="DT/UNS"/></xsl:attribute>
		<xsl:attribute name="autoincrement"><xsl:value-of select="DT/AN"/></xsl:attribute>
		<xsl:attribute name="enum"><xsl:value-of select="DT/ITMS"/></xsl:attribute>
		<xsl:attribute name="defval"><xsl:value-of select="DEFCON/VALUE"/></xsl:attribute>
	</xsl:element>
</xsl:template>


<xsl:template name="concat_virgule">	
	<xsl:param name="attr"/>
	<xsl:param name="chaine"/>
	<xsl:param name="cpt"/>

		<xsl:if test="$attr[$cpt]!=''">
			<xsl:choose>
				<xsl:when test="$chaine">
					<xsl:text>,</xsl:text>
					<xsl:value-of select="$attr[$cpt]"/>
					<xsl:call-template name="concat_virgule">
						<xsl:with-param name="attr" select="$attr"/>
						<xsl:with-param name="chaine" select="concat($chaine,',',$attr[$cpt])"/>
						<xsl:with-param name="cpt" select="$cpt+1"/>
					</xsl:call-template>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="$attr[$cpt]"/>
						<xsl:call-template name="concat_virgule">
							<xsl:with-param name="attr" select="$attr"/>
							<xsl:with-param name="chaine" select="$attr[$cpt]"/>
							<xsl:with-param name="cpt" select="$cpt+1"/>
					</xsl:call-template>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:if>
</xsl:template>


<xsl:template name="relations">
	<xsl:element name="lien">
		<xsl:attribute name="name"><xsl:value-of select="NAME"/></xsl:attribute>
		<xsl:attribute name="id"><xsl:value-of select="ID"/></xsl:attribute>
		<xsl:attribute name="desc"><xsl:value-of select="CHILDPHRASE"/></xsl:attribute>
		<xsl:attribute name="card"><xsl:value-of select="CARDINALITY"/></xsl:attribute>
		<xsl:attribute name="reltype"><xsl:value-of select="RELTYPE"/></xsl:attribute>
		<xsl:attribute name="mandatoryparent"><xsl:value-of select="MANDATORYPARENT"/></xsl:attribute>
		<xsl:attribute name="child"><xsl:value-of select="concat(CHILDOBJECTID,'-',PAIRS/PAIR/FOREIGNKEYID)"/></xsl:attribute>
		<xsl:attribute name="parent"><xsl:value-of select="concat(PARENTOBJECTID,'-',PAIRS/PAIR/KEYID)"/></xsl:attribute>
	</xsl:element>
</xsl:template>


<xsl:template match="*"/>
	
</xsl:stylesheet>
