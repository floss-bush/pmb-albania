<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="xml" indent="yes"/>

<!-- La valeur contenue par défaut dans les champs. Ne pas en mettre peut résulter en des tag <entry/> peut pratique -->
<xsl:param name="default_value">a</xsl:param>
<!-- Si on souhaite générer le fichier entier ou juste les éléments non documentés -->
<xsl:param name="only_missing_elements"></xsl:param>

	<xsl:template name="add_if_not_exist_ou_pas">
		<xsl:param name="test_node"/>
		<xsl:param name="entry_code"/>
		<xsl:choose>
			<xsl:when test="$only_missing_elements = 'true'">
				<xsl:if test="count($test_node) = 0">
					<entry code="{$entry_code}"><xsl:value-of select="$default_value"/></entry>				
				</xsl:if>
			</xsl:when>
			<xsl:otherwise>
				<entry code="{$entry_code}"><xsl:value-of select="$default_value"/></entry>			
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

<xsl:template match="/">
	<XMLlist>
	<xsl:variable name="group_name" select="manifest/name"/>
		<xsl:comment><xsl:text>
</xsl:text>
			<xsl:value-of select="$group_name"/><xsl:text>
</xsl:text>
		</xsl:comment>
	<xsl:call-template name="add_if_not_exist_ou_pas">
		<xsl:with-param name="entry_code"><xsl:value-of select="concat($group_name, '_description')"/></xsl:with-param>
		<xsl:with-param name="test_node" select="manifest/description"/>
	</xsl:call-template>
	<xsl:apply-templates select="manifest/descendant::*"/>
	</XMLlist>
</xsl:template>

<xsl:template match="/manifest/methods/method">
	<xsl:variable name="method_name" select="@name"/>
	<xsl:comment><xsl:text>
</xsl:text>
	<xsl:value-of select="$method_name"/><xsl:text>
</xsl:text>
	</xsl:comment>
	<xsl:call-template name="add_if_not_exist_ou_pas">
		<xsl:with-param name="entry_code"><xsl:value-of select="concat($method_name, '_description')"/></xsl:with-param>
		<xsl:with-param name="test_node" select="@comment"/>
	</xsl:call-template>
	<xsl:call-template name="add_if_not_exist_ou_pas">
		<xsl:with-param name="entry_code"><xsl:value-of select="concat($method_name, '_input_description')"/></xsl:with-param>
		<xsl:with-param name="test_node" select="inputs/description"/>
	</xsl:call-template>
	<xsl:call-template name="add_if_not_exist_ou_pas">
		<xsl:with-param name="entry_code"><xsl:value-of select="concat($method_name, '_output_description')"/></xsl:with-param>
		<xsl:with-param name="test_node" select="outputs/description"/>
	</xsl:call-template>

	<xsl:call-template name="params_or_results">
	  <xsl:with-param name="node_name">param</xsl:with-param>
	  <xsl:with-param name="parent_node" select="inputs"/>
	  <xsl:with-param name="current_prefix" select="concat($method_name, '_')"/>
	</xsl:call-template>
	<xsl:call-template name="params_or_results">
		<xsl:with-param name="node_name">result</xsl:with-param>
		<xsl:with-param name="parent_node" select="outputs"/>
		<xsl:with-param name="current_prefix" select="concat($method_name, '_')"/>
	</xsl:call-template>
</xsl:template>

<xsl:template match="/manifest/types/type[string-length(normalize-space(@imported)) = 0]">
	<xsl:variable name="type_name" select="@name"/>
<xsl:comment><xsl:text>
</xsl:text>
	<xsl:value-of select="$type_name"/><xsl:text>
</xsl:text>
</xsl:comment>
	<xsl:call-template name="add_if_not_exist_ou_pas">
		<xsl:with-param name="entry_code"><xsl:value-of select="concat($type_name, '_description')"/></xsl:with-param>
		<xsl:with-param name="test_node" select="@description"/>
	</xsl:call-template>

	  <xsl:call-template name="params_or_results">
	    <xsl:with-param name="node_name">part</xsl:with-param>
	    <xsl:with-param name="parent_node" select="."/>
	    <xsl:with-param name="current_prefix" select="concat($type_name, '_')"/>
	  </xsl:call-template>
	  <xsl:text>
</xsl:text>
</xsl:template>

<xsl:template name="params_or_results">
	<xsl:param name="node_name">param</xsl:param>
	<xsl:param name="parent_node"></xsl:param>
	<xsl:param name="current_prefix"></xsl:param>
	<xsl:variable name="temp" select="$parent_node/*[local-name() = $node_name]"/>
	<xsl:for-each select="$temp">
		<xsl:variable name="param_name" select="@name"/>
		<xsl:if test="(local-name(..) != $node_name) or ((../@type != 'array')) or (count(preceding-sibling::* | following-sibling::*) != 0)">
			<xsl:call-template name="add_if_not_exist_ou_pas">
				<xsl:with-param name="entry_code"><xsl:value-of select="concat($current_prefix, $param_name)"/></xsl:with-param>
				<xsl:with-param name="test_node" select="@description"/>
			</xsl:call-template>
		</xsl:if>
	
		<xsl:call-template name="params_or_results">
		  <xsl:with-param name="node_name" select="$node_name"/>
		  <xsl:with-param name="parent_node" select="."/>
		  <xsl:with-param name="current_prefix" select="concat($current_prefix, $param_name, '_')"/>
		</xsl:call-template>
	</xsl:for-each>
</xsl:template>

<xsl:template match="*"></xsl:template>

</xsl:stylesheet>