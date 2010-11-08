<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xlink="http://www.w3.org/TR/xlink" 
xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:mods="http://www.loc.gov/mods/">
	<xsl:output method="xml" indent="yes" encoding="UTF-8"/>
	<!-- MODS version 2 to MODS version 3 Conversion Stylesheet
		Trail 9/2003
		Change log: 4/21/06 deleted schemaLocation as well as namespace references under modsCollection and mods sections. Tested successfully w/Xalan processor. Other processors seem ta add xmlns codes that can be removed after converting to MODS v3. Denenberg, Meehleib, Trail.
	-->
	<xsl:template match="/record">
	<record>
		<xsl:choose>
			<xsl:when test="mods:collection">
				<xsl:for-each select="mods:collection">
					<xsl:apply-templates/>
				</xsl:for-each>
			</xsl:when>
			<xsl:when test="mods:modsCollection">
				<xsl:for-each select="mods:modsCollection">
					<modsCollection>
                      <xsl:for-each select="mods:mods">
							<xsl:apply-templates/>
						</xsl:for-each>
					</modsCollection>
				</xsl:for-each>
			</xsl:when>
			<xsl:when test="mods:mods">
				<xsl:for-each select="mods:mods">
					<xsl:apply-templates/>
				</xsl:for-each>
			</xsl:when>
		</xsl:choose>
	</record>
	</xsl:template>

	<xsl:template match="mods:title"><!--moves parts outsiede title -->		
		<mods:title>
			<xsl:value-of select="."/>
		</mods:title>
		<xsl:apply-templates select="partName|partNumber"/>
	</xsl:template>

	<xsl:template match="mods:role">
		<mods:role>
			<mods:roleTerm>
				<xsl:attribute name="mods:type">
					<xsl:value-of select="local-name(*)"/>
				</xsl:attribute>
				<xsl:value-of select="*"/>
			</mods:roleTerm>
		</mods:role>
	</xsl:template>

	<xsl:template match="mods:place">
		<xsl:for-each select="*">
			<mods:place>	
				<mods:placeTerm>
					<xsl:choose>
						<xsl:when test="@mods:authority='marc'">
							<xsl:attribute name="mods:authority">marccountry</xsl:attribute>
						</xsl:when>
						<xsl:when test="not(@mods:authority)"/>
						<xsl:otherwise><xsl:copy-of select="@mods:authority"/></xsl:otherwise>
					</xsl:choose>								
					<xsl:attribute name="mods:type">
						<xsl:value-of select="local-name()"/>
					</xsl:attribute>
					<xsl:value-of select="."/>
				</mods:placeTerm>
			</mods:place>
		</xsl:for-each>
	</xsl:template>

	<xsl:template match="mods:form">		
		<mods:form>			
			<xsl:copy-of select="@*"/>
			<xsl:choose>
				<xsl:when test="mods:unControlled">
					<xsl:value-of select="mods:unControlled"/>
				</xsl:when>
				<xsl:otherwise><xsl:value-of select="."/></xsl:otherwise>
			</xsl:choose>
		</mods:form>
	</xsl:template>

	<xsl:template match="mods:identifier">
<!--1. Convert all <identifier type="uri"> to <location><url>
This would make an assumption that any URIs previously used are really
locations. That is probably a likely assumption.

2. Convert <identifier type="uri"> to both <location><url> and retain the
previously coded <identifier type="uri">. This might be safest but causes
redundancy. A human being generally would have to determine whether it is
really an identifier or location, although in many cases it isn't obvious.

3. Analyze <identifier type="uri"> and if it begins with doi* or hdl* or purl* put it in
both places. The rest go in location.

4. Leave it as is in <identifier> and let the user decide whether to
convert it.
************ option 3 selected ************
-->
		<xsl:choose>
			<xsl:when test="@mods:type='uri'">			
				<xsl:choose>
					<xsl:when test="starts-with(.,'hdl') or starts-with(.,'doi') or starts-with(.,'purl')or starts-with(.,'http://hdl')">
						<mods:location>
							<mods:url><xsl:value-of select="."/></mods:url>
						</mods:location>
						<xsl:copy-of select="."/>
					</xsl:when>
					<xsl:otherwise>
						<mods:location>
							<mods:url><xsl:value-of select="."/></mods:url>
						</mods:location>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:when>
			<xsl:otherwise>
				<xsl:call-template name="copy"/>
			</xsl:otherwise>
		</xsl:choose>		
	</xsl:template>

	<xsl:template match="mods:location">		
		<mods:location>
			<mods:physicalLocation>
				<xsl:copy-of select="@*"/>				
				<xsl:value-of select="."/>		
			</mods:physicalLocation>
		</mods:location>		
	</xsl:template>
	
	<xsl:template match="mods:language">

		<mods:language>
			<mods:languageTerm>				
				<xsl:if test="@mods:authority">
					<xsl:copy-of select="@mods:authority"/>
						<xsl:attribute name="mods:type">code</xsl:attribute>
				</xsl:if>
				<xsl:if test="not(@mods:authority)">
					<xsl:attribute name="mods:type">text</xsl:attribute>
				</xsl:if>
				<xsl:value-of select="."/>			
			</mods:languageTerm>
		</mods:language>
	</xsl:template>

	<xsl:template match="mods:relatedItem">
		<mods:relatedItem>
			<xsl:if test="not(@mods:type='related')">
				<xsl:attribute name="mods:type">
					<xsl:value-of select="@mods:type"/>
				</xsl:attribute>
			</xsl:if>
			<xsl:call-template name="copy"/>
		</mods:relatedItem>
	</xsl:template>

	<xsl:template match="mods:languageOfCataloging">
		<mods:languageOfCataloging>
			<mods:languageTerm>				
				<xsl:if test="@mods:authority">
					<xsl:copy-of select="@mods:authority"/>
					<xsl:attribute name="mods:type">
						<xsl:text>code</xsl:text>
					</xsl:attribute>
				</xsl:if>
				<xsl:value-of select="."/>			
			</mods:languageTerm>

		</mods:languageOfCataloging>
	</xsl:template>

	<xsl:template match="@mods:*" name="attribs">
	    <xsl:attribute name="mods:{local-name()}">
	    	<xsl:value-of select="."/>
	    </xsl:attribute>
	</xsl:template>		
	
	<xsl:template match="mods:*" name="copy">
		<xsl:element name="mods:{local-name()}">
<!--			<xsl:copy-of select="@*"/>-->
			<xsl:call-template name="attribs"/>
			<xsl:apply-templates/>
		</xsl:element>
	</xsl:template>
	
</xsl:stylesheet><!-- Stylus Studio meta-information - (c)1998-2003 Copyright Sonic Software Corporation. All rights reserved.
<metaInformation>
<scenarios ><scenario default="no" name="mods2to3" userelativepaths="yes" externalpreview="no" url="modsv2&#x2D;1.xml" htmlbaseurl="" outputurl="..\test_files\modsv3fromv2.xml" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="Books" userelativepaths="yes" externalpreview="no" url="file://c:\Documents and Settings\jrad\Desktop\MODS\v2\mods99042030.xml" htmlbaseurl="" outputurl="file://c:\Documents and Settings\jrad\Desktop\MODS\v3\mods99042030.xml" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="Serials" userelativepaths="yes" externalpreview="no" url="file://c:\Documents and Settings\jrad\Desktop\MODS\v2\mods86646620.xml" htmlbaseurl="" outputurl="file://c:\Documents and Settings\jrad\Desktop\MODS\v3\mods86646620.xml" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="Computer File" userelativepaths="yes" externalpreview="no" url="file://c:\Documents and Settings\jrad\Desktop\MODS\v2\mods98801326.xml" htmlbaseurl="" outputurl="file://c:\Documents and Settings\jrad\Desktop\MODS\v3\mods98801326.xml" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="Conference" userelativepaths="yes" externalpreview="no" url="file://c:\Documents and Settings\jrad\Desktop\MODS\v2\mods97129132.xml" htmlbaseurl="" outputurl="file://c:\Documents and Settings\jrad\Desktop\MODS\v3\mods97129132.xml" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="Map" userelativepaths="yes" externalpreview="no" url="file://c:\Documents and Settings\jrad\Desktop\MODS\v2\mods83691515.xml" htmlbaseurl="" outputurl="file://c:\Documents and Settings\jrad\Desktop\MODS\v3\mods83691515.xml" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="Motion Picture" userelativepaths="yes" externalpreview="no" url="file://c:\Documents and Settings\jrad\Desktop\MODS\v2\mods80700998.xml" htmlbaseurl="" outputurl="file://c:\Documents and Settings\jrad\Desktop\MODS\v3\mods80700998.xml" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="Music" userelativepaths="yes" externalpreview="no" url="file://c:\Documents and Settings\jrad\Desktop\MODS\v2\mods85753651.xml" htmlbaseurl="" outputurl="file://c:\Documents and Settings\jrad\Desktop\MODS\v3\mods85753651.xml" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="Sound Recording" userelativepaths="yes" externalpreview="no" url="file://c:\Documents and Settings\jrad\Desktop\MODS\v2\mods94759273.xml" htmlbaseurl="" outputurl="file://c:\Documents and Settings\jrad\Desktop\MODS\v3\mods94759273.xml" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="yes" name="Mixed Material" userelativepaths="yes" externalpreview="no" url="file://c:\Documents and Settings\jrad\Desktop\MODS\v2\mods83001404.xml" htmlbaseurl="" outputurl="file://C:\Documents and Settings\jrad\Desktop\MODS\v3\mods83001404.xml" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/></scenarios><MapperInfo srcSchemaPath="" srcSchemaRoot="" srcSchemaPathIsRelative="yes" srcSchemaInterpretAsXML="no" destSchemaPath="" destSchemaRoot="" destSchemaPathIsRelative="yes" destSchemaInterpretAsXML="no"/>
</metaInformation>
-->