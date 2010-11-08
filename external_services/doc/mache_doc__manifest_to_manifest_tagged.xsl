<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="xml" indent="yes"/>

<xsl:template match="/manifest/name">
	<xsl:copy>
    	<xsl:apply-templates select="node()|@*"/>
	</xsl:copy>
	<xsl:choose>
		<xsl:when test="count(../description)=0">
			<xsl:text>
</xsl:text>
			<xsl:element name="description">msg:<xsl:value-of select="."/>_description</xsl:element>
		</xsl:when>
	</xsl:choose>
</xsl:template>

<xsl:template match="/manifest/methods/method">
	<xsl:copy>
		<xsl:apply-templates select="@*"/>
		<xsl:attribute name="comment">msg:<xsl:value-of select="@name"/>_description</xsl:attribute>
    	<xsl:apply-templates select="node()"/>
	</xsl:copy>
</xsl:template>

<xsl:template match="/manifest/types/type">
	<xsl:copy>
		<xsl:apply-templates select="@*"/>
		<xsl:attribute name="description">msg:<xsl:value-of select="@name"/>_description</xsl:attribute>
    	<xsl:apply-templates select="node()"/>
	</xsl:copy>
</xsl:template>

<xsl:template match="/manifest/methods/method/inputs">
	<xsl:copy>
		<xsl:choose>
			<xsl:when test="count(description)=0">
				<xsl:apply-templates select="@*"/>
				<xsl:text>
				</xsl:text>
				<xsl:element name="description">msg:<xsl:value-of select="../@name"/>_input_description</xsl:element>
				<xsl:apply-templates select="node()"/>				
			</xsl:when>
			<xsl:otherwise>
				<xsl:apply-templates select="node()|@*"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:copy>
</xsl:template>

<xsl:template match="/manifest/methods/method/outputs">
	<xsl:copy>
		<xsl:choose>
			<xsl:when test="count(description)=0">
				<xsl:apply-templates select="@*"/>
				<xsl:text>
				</xsl:text>
				<xsl:element name="description">msg:<xsl:value-of select="../@name"/>_output_description</xsl:element>
				<xsl:apply-templates select="node()"/>				
			</xsl:when>
			<xsl:otherwise>
				<xsl:apply-templates select="node()|@*"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:copy>
</xsl:template>

<xsl:template match="*[local-name()='part' or local-name()='result' or local-name()='param']">
	<xsl:copy>
		<xsl:apply-templates select="@*"/>
		<xsl:choose>
			<xsl:when test="(../@type = 'array') and (count(preceding-sibling::* | following-sibling::*) = 0)">
			</xsl:when>
			<xsl:otherwise>
				<xsl:attribute name="description">
					<xsl:text>msg:</xsl:text><xsl:call-template name="echo_part_name"/>
				</xsl:attribute>
			</xsl:otherwise>
		</xsl:choose>
    	<xsl:apply-templates select="node()"/>
	</xsl:copy>
</xsl:template>

<xsl:template match="node()|@*">
  <xsl:copy>
    <xsl:apply-templates select="node()|@*"/>
  </xsl:copy>
</xsl:template>

<xsl:template name="echo_part_name">
    <xsl:for-each select="ancestor-or-self::*">
      <xsl:call-template name="print-step"/>
    </xsl:for-each>
  <xsl:apply-templates select="*"/>
</xsl:template>

<xsl:template name="print-step">
	<xsl:choose>
		<xsl:when test="local-name()='type'">
			<xsl:value-of select="@name"/>
		</xsl:when>
		<xsl:when test="local-name()='inputs' or local-name()='outputs'">
			<xsl:value-of select="../@name"/>
		</xsl:when>
		<xsl:when test="local-name()='part' or local-name()='result' or local-name()='param'">
			<xsl:text>_</xsl:text>
			<xsl:value-of select="@name"/>
		</xsl:when>
		<xsl:otherwise>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>


</xsl:stylesheet>
