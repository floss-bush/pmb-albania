<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!--
xsltproc pase.xslt en_US.xml > us_XML.po
-->

<xsl:output method="text" encoding="UTF-8"/>
<xsl:template match="XMLlist">
<xsl:for-each select="entry">
<xsl:value-of select="@code"/>="<xsl:value-of  select="."/>"
</xsl:for-each>
</xsl:template>

</xsl:stylesheet>
