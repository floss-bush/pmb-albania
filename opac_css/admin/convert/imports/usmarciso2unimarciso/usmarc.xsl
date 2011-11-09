<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version = '1.0'
     xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
		<xsl:output method="xml" indent='yes'/>

<xsl:template match="pmbmarc">
	<unimarc>
		<xsl:apply-templates select="notice"/>
	</unimarc>
</xsl:template>

<xsl:template match="pmbmarc/notice">
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
		<xsl:call-template name="mention"/>	
		<xsl:call-template name="editeur"/>
		<xsl:call-template name="physical_description"/>
		<xsl:call-template name="series"/>
		<xsl:call-template name="contens_note"/>
		<xsl:call-template name="summary"/>
		<xsl:call-template name="collection"/>
		<xsl:call-template name="sous_collection"/>
		<xsl:call-template name="num_serie"/>
		<xsl:call-template name="descripteurs"/>
		<xsl:call-template name="mots_cles"/>
		<xsl:call-template name="dewey"/>
		<xsl:call-template name="autorite"/>
		<xsl:call-template name="autorite_701"/>
		<xsl:call-template name="autorite_710"/>
		<xsl:call-template name="autorite_711"/>
		<xsl:call-template name="electronic_location"/>	
	</notice>
</xsl:template>


<xsl:template name="record_identifier">
	<xsl:if test="./f[@c='001']">
		<f c="001"><xsl:value-of select="./f"/></f>
			
	</xsl:if>	
</xsl:template>


<!-- fonction identifier: récupère ISBN et Prix 
En entrée: Marc21
    <f c="020" ind="  ">
      <s c="a">0585212163 (electronic bk.)</s>
	  <s c="c">20 Euro</s>
    </f>
    <f c="020" ind="  ">
      <s c="a">9780585212166 (electronic bk.)</s>
    </f>	

En sortie: Unimarc
	<f c="010">
		<s c="a">0585212163</s>
		<s c="d">20 Euro</s>
	</f>
	<f c="010">
		<s c="a">9780585212166</s>
	</f>
-->
<xsl:template name="identifier">
	<xsl:for-each select="./f[@c='020']">
		<f c="010">
			<xsl:if test="./s[@c='a']">
				<!-- ISBN 020 a -> 010 a -->
				<s c="a">
					<xsl:choose>
						<xsl:when test="contains(./s[@c='a'],'(')">
							<xsl:value-of select="normalize-space(substring-before(./s[@c='a'],'('))"/>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="normalize-space(./s[@c='a'])"/>
						</xsl:otherwise>
					</xsl:choose>
				</s>	
			</xsl:if>
			<xsl:if test="./s[@c='c']">							
				<!-- Prix: 020 c -> 010 d -->
				<s c="d">
					<xsl:value-of select="./s[@c='c']"/>
				</s>	
			</xsl:if>									
		</f>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction langue récupère langue et langue originale
En entrée: Marc21
	<f c="041" ind=" 1">
      <s c="a">Anglais</s>
	  <s c="h">Francais</s>	
    </f>
En sortie: Unimarc
		<f c="101" ind=" 1">
			<s c="a">Anglais</s>
			<s c="c">Francais</s>
		</f>	
-->
<xsl:template name="langue">
	<xsl:choose>
		<xsl:when test="./f[@c='041']">		
			<xsl:element name="f">	
				<xsl:attribute name="c">101</xsl:attribute>	
				<xsl:attribute name="ind"><xsl:value-of select="./f[@c='041']/@ind"/></xsl:attribute>				
				<xsl:if test="./f[@c='041']/s[@c='a']">		
					<!-- langue 041 a -> 101 a -->
					<s c="a">
						<xsl:value-of select="./f[@c='041']/s[@c='a']"/>
					</s>	
				</xsl:if>
				<xsl:if test="./f[@c='041']/s[@c='h']">							
					<!-- langue originale: 041 h -> 041 c -->
					<s c="c">
						<xsl:value-of select="./f[@c='041']/s[@c='h']"/>
					</s>	
				</xsl:if>									
			</xsl:element>
		</xsl:when>
		<xsl:otherwise>
			<xsl:if test="./f[@c='008']">
			<xsl:element name="f">	
				<xsl:attribute name="c">101</xsl:attribute>	
				<xsl:attribute name="ind">  </xsl:attribute>
					<s c="a">
						<xsl:value-of select="substring(./f[@c='008'],36,3)"/>
					</s>
				</xsl:element>
			</xsl:if>				
		</xsl:otherwise>
	</xsl:choose>			
</xsl:template>



<!-- fonction titre
En entrée: Marc21
    <f c="245" ind="10">
      <s c="a">Introduction to survey sampling</s>
      <s c="h">[electronic resource] /</s>
      <s c="c">Graham Kalton.</s>
    </f>
En sortie: Unimarc
		<f c="200" ind="10">
			<s c="a">Introduction to survey sampling</s>
			<s c="c">Introduction to 2</s>
			<s c="b">[electronic resource] /</s>
			<s c="f">Graham Kalton.</s>
		</f>
-->
<xsl:template name="titre">
	<xsl:if test="./f[@c='245']">		
		<xsl:element name="f">	
			<xsl:attribute name="c">200</xsl:attribute>	
			<xsl:attribute name="ind"><xsl:value-of select="./f[@c='245']/@ind"/></xsl:attribute>
			<!-- Title Proper a -> a -->
			<s c="a"><xsl:value-of select="./f[@c='245']/s[@c='a'][1]"/></s>	
			<!-- Title Proper a -> c -->
			<xsl:if test="./f[@c='245']/s[@c='a'][2]">	
				<s c="c"><xsl:value-of select="./f[@c='245']/s[@c='a'][2]"/></s>
			</xsl:if>
			<!-- Medium h -> b -->
			<xsl:for-each select="./f[@c='245']/s[@c='h']">								
				<s c="b"><xsl:value-of select="."/></s>	
			</xsl:for-each>
			<!-- Other title information b -> e -->
			<xsl:if test="./f[@c='245']/s[@c='b']">	
				<s c="e"><xsl:value-of select="./f[@c='245']/s[@c='b']"/></s>
			</xsl:if>			
			<!-- 1st statement of resp. c -> f -->
			<xsl:if test="./f[@c='245']/s[@c='c'][1]">	
				<s c="f"><xsl:value-of select="./f[@c='245']/s[@c='c'][1]"/></s>
			</xsl:if>
			<!-- Subs. state. of resp. c -> g -->
			<xsl:if test="./f[@c='245']/s[@c='c'][2]">	
				<s c="g"><xsl:value-of select="./f[@c='245']/s[@c='c'][2]"/></s>
			</xsl:if>	
			<!-- Name of part n -> h -->
			<xsl:if test="./f[@c='245']/s[@c='n'][1]">	
				<s c="h"><xsl:value-of select="./f[@c='245']/s[@c='n'][1]"/></s>
			</xsl:if>		
			<!-- Name of part p -> i -->
			<xsl:if test="./f[@c='245']/s[@c='i'][1]">	
				<s c="i"><xsl:value-of select="./f[@c='245']/s[@c='i'][1]"/></s>
			</xsl:if>																																													
		</xsl:element>
	</xsl:if>	
</xsl:template>


<!-- fonction mention d'édition
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="mention">
	<xsl:for-each select="./f[@c='250']">
		<f c="010" ind='  '>
			<xsl:if test="./s[@c='a']">		
				<!-- Edition statement a -> a -->
				<s c="a">
					<xsl:value-of select="./s[@c='a']"/>
				</s>	
			</xsl:if>
			<xsl:if test="./s[@c='b']">							
				<!-- Statement of respons. b -> f -->
				<s c="f">
					<xsl:value-of select="./s[@c='b']"/>
				</s>	
			</xsl:if>									
		</f>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction editeur
En entrée: Marc21
    <f c="260" ind="  ">
      <s c="a">Beverly Hills, Calif. :</s>
      <s c="b">Sage Publications,</s>
      <s c="c">c1983.</s>
    </f>
En sortie: Unimarc
		<f c="210" ind="  ">
			<s c="a">Beverly Hills, Calif. :</s>
			<s c="c">Sage Publications,</s>
			<s c="d">c1983.</s>
		</f>	
-->
<xsl:template name="editeur">
	<xsl:if test="./f[@c='260']">
		<f c="210" ind='  '>
			<!-- Place of public./distr a -> a -->
			<xsl:for-each select="./f[@c='260']/s[@c='a']">				
				<s c="a"><xsl:value-of select="."/></s>	
			</xsl:for-each>					
			<!-- Name of publisher/dist. b -> c -->
			<xsl:for-each select="./f[@c='260']/s[@c='b']">				
				<s c="c"><xsl:value-of select="."/></s>	
			</xsl:for-each>	
			<!-- Date of publication c -> d -->
			<xsl:for-each select="./f[@c='260']/s[@c='c']">				
				<s c="d"><xsl:value-of select="."/></s>	
			</xsl:for-each>	

			<!-- Place of manufacture e -> e -->
			<xsl:for-each select="./f[@c='260']/s[@c='e']">				
				<s c="e"><xsl:value-of select="."/></s>	
			</xsl:for-each>	
			<!-- Name of manufacturer f -> g -->
			<xsl:for-each select="./f[@c='260']/s[@c='f']">				
				<s c="g"><xsl:value-of select="."/></s>	
			</xsl:for-each>		
			<!-- Date of manufacture g -> h -->
			<xsl:for-each select="./f[@c='260']/s[@c='g']">				
				<s c="h"><xsl:value-of select="."/></s>	
			</xsl:for-each>																											
		</f>	
	</xsl:if>	
</xsl:template>

<!-- fonction physical_description
En entrée: Marc21
    <f c="300" ind="  ">
      <s c="a">96 p. ;</s>
      <s c="c">22 cm.</s>
    </f>
En sortie: Unimarc
		<f c="215" ind="  ">
			<s c="a">96 p. ;</s>
			<s c="d">22 cm.</s>
		</f>	
-->
<xsl:template name="physical_description">
	<xsl:for-each select="./f[@c='300']">
		<f c="215" ind='  '>
			<!-- SMD/extent of item a -> a -->	
			<xsl:for-each select="./s[@c='a']">	
				<s c="a"><xsl:value-of select="."/></s>	
			</xsl:for-each>
			<!-- Other physical details  b -> c -->
			<xsl:for-each select="./s[@c='b']">	
				<s c="c"><xsl:value-of select="."/></s>	
			</xsl:for-each>
			<!-- Dimensions details  c -> d -->
			<xsl:for-each select="./s[@c='c']">	
				<s c="d"><xsl:value-of select="."/></s>	
			</xsl:for-each>
			<!-- Accompanying material  e -> e -->
			<xsl:for-each select="./s[@c='e']">	
				<s c="e"><xsl:value-of select="."/></s>	
			</xsl:for-each>							
		</f>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction series
En entrée: Marc21
 
En sortie: Unimarc	
-->
	
<xsl:template name="series">
	
	<xsl:for-each select="./f[@c='440']">
		<xsl:element name="f">	
			<xsl:attribute name="c">225</xsl:attribute>
			<xsl:attribute name="ind"><xsl:text>2 </xsl:text></xsl:attribute>
			<xsl:for-each select="./s[@c='a']">	
				<s c="a"><xsl:value-of select="."/></s>	
			</xsl:for-each>
			<xsl:for-each select="./s[@c='v']">	
				<s c="v"><xsl:value-of select="."/></s>	
			</xsl:for-each>
			<xsl:for-each select="./s[@c='x']">	
				<s c="x"><xsl:value-of select="."/></s>	
			</xsl:for-each>					
		</xsl:element>	
	</xsl:for-each>
	
	<xsl:for-each select="./f[@c='490']">
		<xsl:element name="f">	
			<xsl:attribute name="c">225</xsl:attribute>
			<xsl:if test="./@ind=0">	
				<xsl:attribute name="ind"><xsl:text> 1</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./@ind=1">	
				<xsl:attribute name="ind"><xsl:text> 0</xsl:text></xsl:attribute>
			</xsl:if>			
			<!-- Series title a -> a -->	
			<xsl:for-each select="./s[@c='a']">	
				<s c="a"><xsl:value-of select="."/></s>	
			</xsl:for-each>
			<!-- Volume designation  v -> v -->e
			<xsl:for-each select="./s[@c='v']">	
				<s c="v"><xsl:value-of select="."/></s>	
			</xsl:for-each>
			<!-- ISSN  x -> x -->
			<xsl:for-each select="./s[@c='x']">	
				<s c="x"><xsl:value-of select="."/></s>	
			</xsl:for-each>					
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>


<!-- fonction general_notes
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="general_notes">
	<xsl:for-each select="./f[@c='500']">
		<f c="300" ind='  '>
			<xsl:if test="./s[@c='a']">		
				<!-- Text of note a -> a -->
				<s c="a"><xsl:value-of select="./s[@c='a']"/></s>	
			</xsl:if>									
		</f>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction contens_note
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="contens_note">
	<xsl:if test="./f[@c='505']">
		<xsl:element name="f">	
			<xsl:attribute name="c">327</xsl:attribute>
			<xsl:if test="./f[@c='505']/@ind=0">	
				<xsl:attribute name="ind"><xsl:text> 1</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./f[@c='505']/@ind=1">	
				<xsl:attribute name="ind"><xsl:text> 0</xsl:text></xsl:attribute>
			</xsl:if>		
			<xsl:if test="./f[@c='505']/s[@c='a']">		
				<!-- Text of note a -> a -->
				<s c="a"><xsl:value-of select="./f[@c='505']/s[@c='a']"/></s>	
			</xsl:if>									
		</xsl:element>	
	</xsl:if>
</xsl:template>


<!-- fonction summary
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="summary">
	<xsl:for-each select="./f[@c='520']">
		<f c="330" ind='  '>
			<xsl:if test="./s[@c='a']">		
				<!-- Text of note a -> a -->
				<s c="a"><xsl:value-of select="./s[@c='a']"/></s>	
			</xsl:if>									
		</f>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction notice_code
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="notice_code">
	<xsl:for-each select="./f[@c='037']">
		<f c="345" ind='  '>
			<!-- Source for acquisition b -> a -->
			<xsl:if test="./s[@c='b']">				
				<s c="a"><xsl:value-of select="./s[@c='b']"/></s>	
			</xsl:if>
			<!-- Stock number a -> b -->	
			<xsl:if test="./s[@c='a']">				
				<s c="b"><xsl:value-of select="./s[@c='a']"/></s>	
			</xsl:if>
			<!-- Medium f -> c -->
			<xsl:for-each select="./s[@c='f']">	
				<s c="e"><xsl:value-of select="."/></s>	
			</xsl:for-each>		
			<!-- Terms of availability c -> d -->
			<xsl:for-each select="./s[@c='f']">	
				<s c="e"><xsl:value-of select="."/></s>	
			</xsl:for-each>														
		</f>	
	</xsl:for-each>	
</xsl:template>


<!-- fonction collection	760 - > 411
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="collection">
	<xsl:for-each select="./f[@c='760']">
		<xsl:element name="f">	
			<xsl:attribute name="c">410</xsl:attribute>
			<xsl:if test="./@ind='1 '">	
				<xsl:attribute name="ind"><xsl:text> 0</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./@ind='0 '">	
				<xsl:attribute name="ind"><xsl:text> 1</xsl:text></xsl:attribute>
			</xsl:if>					
			<!-- Cont. no. of linked rec w -> 3 -->
			<xsl:if test="./s[@c='3']">				
				<s c="3"><xsl:value-of select="./s[@c='w']"/></s>	
			</xsl:if>
			<!-- Title t -> t -->	
			<xsl:if test="./s[@c='t']">				
				<s c="t"><xsl:value-of select="./s[@c='t']"/></s>	
			</xsl:if>
			<!-- Volume designation g -> v -->
			<xsl:for-each select="./s[@c='g']">	
				<s c="v"><xsl:value-of select="."/></s>	
			</xsl:for-each>		
			<!-- ISSN of linked record x -> x -->	
			<xsl:if test="./s[@c='x']">				
				<s c="x"><xsl:value-of select="./s[@c='t']"/></s>	
			</xsl:if>
			<!-- ISBN of linked record x -> x -->	
			<xsl:if test="./s[@c='z']">				
				<s c="y"><xsl:value-of select="./s[@c='z']"/></s>	
			</xsl:if>																		
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction sous_collection	762 - > 411
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="sous_collection">
	<xsl:for-each select="./f[@c='762']">
		<xsl:element name="f">	
			<xsl:attribute name="c">411</xsl:attribute>
			<xsl:if test="./@ind='1 '">	
				<xsl:attribute name="ind"><xsl:text> 0</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./@ind='0 '">	
				<xsl:attribute name="ind"><xsl:text> 1</xsl:text></xsl:attribute>
			</xsl:if>					
			<!-- Cont. no. of linked rec w -> 3 -->
			<xsl:if test="./s[@c='3']">				
				<s c="3"><xsl:value-of select="./s[@c='w']"/></s>	
			</xsl:if>
			<!-- Title t -> t -->	
			<xsl:if test="./s[@c='t']">				
				<s c="t"><xsl:value-of select="./s[@c='t']"/></s>	
			</xsl:if>
			<!-- Volume designation g -> v -->
			<xsl:for-each select="./s[@c='g']">	
				<s c="v"><xsl:value-of select="."/></s>	
			</xsl:for-each>		
			<!-- ISSN of linked record x -> x -->	
			<xsl:if test="./s[@c='x']">				
				<s c="x"><xsl:value-of select="./s[@c='t']"/></s>	
			</xsl:if>
			<!-- ISBN of linked record x -> x -->	
			<xsl:if test="./s[@c='z']">				
				<s c="y"><xsl:value-of select="./s[@c='z']"/></s>	
			</xsl:if>																		
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>


<!-- fonction num_serie  461 -> 774
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="num_serie">
	<xsl:for-each select="./f[@c='774']">
		<xsl:element name="f">	
			<xsl:attribute name="c">461</xsl:attribute>
			<xsl:if test="./@ind='1 '">	
				<xsl:attribute name="ind"><xsl:text> 0</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./@ind='0 '">	
				<xsl:attribute name="ind"><xsl:text> 1</xsl:text></xsl:attribute>
			</xsl:if>					
			<!-- Cont. no. of linked rec w -> 3 -->
			<xsl:if test="./s[@c='3']">				
				<s c="3"><xsl:value-of select="./s[@c='w']"/></s>	
			</xsl:if>
			<!-- Title t -> t -->	
			<xsl:if test="./s[@c='t']">				
				<s c="t"><xsl:value-of select="./s[@c='t']"/></s>	
			</xsl:if>
			<!-- Volume designation g -> v -->
			<xsl:for-each select="./s[@c='g']">	
				<s c="v"><xsl:value-of select="."/></s>	
			</xsl:for-each>		
			<!-- ISSN of linked record x -> x -->	
			<xsl:if test="./s[@c='x']">				
				<s c="x"><xsl:value-of select="./s[@c='t']"/></s>	
			</xsl:if>
			<!-- ISBN of linked record x -> x -->	
			<xsl:if test="./s[@c='z']">				
				<s c="y"><xsl:value-of select="./s[@c='z']"/></s>	
			</xsl:if>																		
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction mots_cles  650 -> 606
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="descripteurs">
	<xsl:for-each select="./f[@c='650']">
		<xsl:element name="f">	
			<xsl:attribute name="c">606</xsl:attribute>
			<xsl:attribute name="ind"><xsl:text>  </xsl:text></xsl:attribute>

			<!-- Subject term a -> a -->
			<xsl:for-each select="./s[@c='a']">	
				<s c="a"><xsl:value-of select="."/></s>	
			</xsl:for-each>		
			
			<!-- Subject term x -> x -->
			<xsl:for-each select="./s[@c='x']">	
				<s c="x"><xsl:value-of select="."/></s>	
			</xsl:for-each>
						
			<!-- Subject term z -> y -->
			<xsl:for-each select="./s[@c='z']">	
				<s c="y"><xsl:value-of select="."/></s>	
			</xsl:for-each>
			
			<!-- Subject term y -> z -->
			<xsl:for-each select="./s[@c='y']">	
				<s c="z"><xsl:value-of select="."/></s>	
			</xsl:for-each>												
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction mots_cles  653 -> 610
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="mots_cles">
	<xsl:for-each select="./f[@c='653']">
		<xsl:element name="f">	
			<xsl:attribute name="c">610</xsl:attribute>
			<xsl:attribute name="ind">  </xsl:attribute>

			<!-- Subject term a -> a -->
			<xsl:for-each select="./s[@c='a']">	
				<s c="a"><xsl:value-of select="."/></s>	
			</xsl:for-each>		
															
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction dewey  082 -> 676
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="dewey">
	<xsl:for-each select="./f[@c='082']">
		<xsl:element name="f">	
			<xsl:attribute name="c">676</xsl:attribute>
			<xsl:attribute name="ind"><xsl:text>  </xsl:text></xsl:attribute>

			<!-- Number a -> a -->
			<xsl:if test="./s[@c='a']">				
				<s c="a"><xsl:value-of select="./s[@c='a']"/></s>	
			</xsl:if>		
			<!-- Edition 2 -> v -->
			<xsl:if test="./s[@c='2']">				
				<s c="v"><xsl:value-of select="./s[@c='2']"/></s>	
			</xsl:if>																
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction autorite  100 -> 700
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="autorite">
	<xsl:for-each select="./f[@c='100']">
		<xsl:element name="f">	
			<xsl:attribute name="c">700</xsl:attribute>
			<xsl:if test="./@ind='0 '">	
				<xsl:attribute name="ind"><xsl:text> 0</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./@ind='1 '">	
				<xsl:attribute name="ind"><xsl:text> 1</xsl:text></xsl:attribute>
			</xsl:if>
			<!-- Entry element a -> a -->
			<xsl:if test="./s[@c='a']">				
				<s c="a"><xsl:value-of select="./s[@c='a']"/></s>	
			</xsl:if>												
			<!-- Additions (not dates) c -> c -->
			<xsl:for-each select="./s[@c='c']">	
				<s c="c"><xsl:value-of select="."/></s>	
			</xsl:for-each>		
			<!-- Entry element q -> g -->
			<xsl:if test="./s[@c='q']">				
				<s c="g"><xsl:value-of select="./s[@c='q']"/></s>	
			</xsl:if>			
			<!-- Entry element u -> p -->
			<xsl:if test="./s[@c='u']">				
				<s c="p"><xsl:value-of select="./s[@c='u']"/></s>	
			</xsl:if>	
			<!-- Relator cod 4 -> 4 -->
			<xsl:for-each select="./s[@c='4']">	
				<s c="4"><xsl:value-of select="."/></s>	
			</xsl:for-each>																					
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction autorite_701  700 -> 701
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="autorite_701">
	<xsl:for-each select="./f[@c='700']">
		<xsl:element name="f">	
			<xsl:attribute name="c">701</xsl:attribute>
			<xsl:if test="./@ind='0 '">	
				<xsl:attribute name="ind"><xsl:text> 0</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./@ind='1 '">	
				<xsl:attribute name="ind"><xsl:text> 1</xsl:text></xsl:attribute>
			</xsl:if>
			<!-- Entry element a -> a -->
			<xsl:if test="./s[@c='a']">				
				<s c="a"><xsl:value-of select="./s[@c='a']"/></s>	
			</xsl:if>												
			<!-- Additions (not dates) c -> c -->
			<xsl:for-each select="./s[@c='c']">	
				<s c="c"><xsl:value-of select="."/></s>	
			</xsl:for-each>		
			<!-- Roman numerals b -> d -->
			<xsl:if test="./s[@c='b']">				
				<s c="d"><xsl:value-of select="./s[@c='b']"/></s>	
			</xsl:if>		
			<!-- Dates d -> f -->
			<xsl:if test="./s[@c='d']">				
				<s c="f"><xsl:value-of select="./s[@c='d']"/></s>	
			</xsl:if>							
			<!-- Expansion of initials q -> g -->
			<xsl:if test="./s[@c='q']">				
				<s c="g"><xsl:value-of select="./s[@c='q']"/></s>	
			</xsl:if>			
			<!-- Affiliation u -> p -->
			<xsl:if test="./s[@c='u']">				
				<s c="p"><xsl:value-of select="./s[@c='u']"/></s>	
			</xsl:if>	
			<!-- Relator cod 4 -> 4 -->
			<xsl:for-each select="./s[@c='4']">	
				<s c="4"><xsl:value-of select="."/></s>	
			</xsl:for-each>																					
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction autorite_710  110 -> 710
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="autorite_710">
	<xsl:for-each select="./f[@c='110']">
		<xsl:element name="f">	
			<xsl:attribute name="c">710</xsl:attribute>
			<xsl:if test="./@ind='0 '">	
				<xsl:attribute name="ind"><xsl:text> 0</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./@ind='1 '">	
				<xsl:attribute name="ind"><xsl:text> 1</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./@ind='2 '">	
				<xsl:attribute name="ind"><xsl:text> 2</xsl:text></xsl:attribute>
			</xsl:if>			
			<!-- Entry element a -> a -->
			<xsl:if test="./s[@c='a']">				
				<s c="a"><xsl:value-of select="./s[@c='a']"/></s>	
			</xsl:if>		
			<!-- Subdivision b -> b -->
			<xsl:for-each select="./s[@c='b']">	
				<s c="b"><xsl:value-of select="."/></s>	
			</xsl:for-each>	
			<!-- Subdivision e -> b -->
			<xsl:for-each select="./s[@c='e']">	
				<s c="b"><xsl:value-of select="."/></s>	
			</xsl:for-each>	
			<!-- Number of meeting n -> d -->
			<xsl:if test="./s[@c='n']">				
				<s c="d"><xsl:value-of select="./s[@c='n']"/></s>	
			</xsl:if>										
			<!-- Location of meeting c -> e -->
			<xsl:if test="./s[@c='c']">				
				<s c="e"><xsl:value-of select="./s[@c='c']"/></s>	
			</xsl:if>		
			<!-- Date of meeting d -> f -->
			<xsl:if test="./s[@c='d']">				
				<s c="f"><xsl:value-of select="./s[@c='d']"/></s>	
			</xsl:if>		
			<!-- Affiliation u -> p -->
			<xsl:if test="./s[@c='u']">				
				<s c="p"><xsl:value-of select="./s[@c='u']"/></s>	
			</xsl:if>	
			<!-- Relator cod 4 -> 4 -->
			<xsl:for-each select="./s[@c='4']">	
				<s c="4"><xsl:value-of select="."/></s>	
			</xsl:for-each>																					
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction autorite  710 -> 711
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="autorite_711">
	<xsl:for-each select="./f[@c='710']">
		<xsl:element name="f">	
			<xsl:attribute name="c">711</xsl:attribute>
			<xsl:if test="./@ind='0 '">	
				<xsl:attribute name="ind"><xsl:text> 0</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./@ind='1 '">	
				<xsl:attribute name="ind"><xsl:text> 1</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./@ind='2 '">	
				<xsl:attribute name="ind"><xsl:text> 2</xsl:text></xsl:attribute>
			</xsl:if>						
			<!-- Entry element a -> a -->
			<xsl:if test="./s[@c='a']">				
				<s c="a"><xsl:value-of select="./s[@c='a']"/></s>	
			</xsl:if>		
			<!-- Subdivision b -> b -->
			<xsl:for-each select="./s[@c='b']">	
				<s c="b"><xsl:value-of select="."/></s>	
			</xsl:for-each>	
			<!-- Subdivision e -> b -->
			<xsl:for-each select="./s[@c='e']">	
				<s c="b"><xsl:value-of select="."/></s>	
			</xsl:for-each>	
			<!-- Number of meeting n -> d -->
			<xsl:if test="./s[@c='n']">				
				<s c="d"><xsl:value-of select="./s[@c='n']"/></s>	
			</xsl:if>										
			<!-- Location of meeting c -> e -->
			<xsl:if test="./s[@c='c']">				
				<s c="e"><xsl:value-of select="./s[@c='c']"/></s>	
			</xsl:if>		
			<!-- Date of meeting d -> f -->
			<xsl:if test="./s[@c='d']">				
				<s c="f"><xsl:value-of select="./s[@c='d']"/></s>	
			</xsl:if>		
			<!-- Affiliation u -> p -->
			<xsl:if test="./s[@c='u']">				
				<s c="p"><xsl:value-of select="./s[@c='u']"/></s>	
			</xsl:if>	
			<!-- Relator cod 4 -> 4 -->
			<xsl:for-each select="./s[@c='4']">	
				<s c="4"><xsl:value-of select="."/></s>	
			</xsl:for-each>																					
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>

<!-- fonction autorite  710 -> 711
En entrée: Marc21

En sortie: Unimarc
	
-->
<xsl:template name="ressource_electronique">
	<xsl:for-each select="./f[@c='710']">
		<xsl:element name="f">	
			<xsl:attribute name="c">711</xsl:attribute>
			<xsl:if test="./@ind='0 '">	
				<xsl:attribute name="ind"><xsl:text> 0</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./@ind='1 '">	
				<xsl:attribute name="ind"><xsl:text> 1</xsl:text></xsl:attribute>
			</xsl:if>
			<xsl:if test="./@ind='2 '">	
				<xsl:attribute name="ind"><xsl:text> 2</xsl:text></xsl:attribute>
			</xsl:if>						
			<!-- Entry element a -> a -->
			<xsl:if test="./s[@c='a']">				
				<s c="a"><xsl:value-of select="./s[@c='a']"/></s>	
			</xsl:if>		
			<!-- Subdivision b -> b -->
			<xsl:for-each select="./s[@c='b']">	
				<s c="b"><xsl:value-of select="."/></s>	
			</xsl:for-each>	
			<!-- Subdivision e -> b -->
			<xsl:for-each select="./s[@c='e']">	
				<s c="b"><xsl:value-of select="."/></s>	
			</xsl:for-each>	
			<!-- Number of meeting n -> d -->
			<xsl:if test="./s[@c='n']">				
				<s c="d"><xsl:value-of select="./s[@c='n']"/></s>	
			</xsl:if>										
			<!-- Location of meeting c -> e -->
			<xsl:if test="./s[@c='c']">				
				<s c="e"><xsl:value-of select="./s[@c='c']"/></s>	
			</xsl:if>		
			<!-- Date of meeting d -> f -->
			<xsl:if test="./s[@c='d']">				
				<s c="f"><xsl:value-of select="./s[@c='d']"/></s>	
			</xsl:if>		
			<!-- Affiliation u -> p -->
			<xsl:if test="./s[@c='u']">				
				<s c="p"><xsl:value-of select="./s[@c='u']"/></s>	
			</xsl:if>	
			<!-- Relator cod 4 -> 4 -->
			<xsl:for-each select="./s[@c='4']">	
				<s c="4"><xsl:value-of select="."/></s>	
			</xsl:for-each>																					
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>


<!-- fonction electronic_location  856 -> 856
En entrée: Marc21
En sortie: Unimarc
-->
<xsl:template name="electronic_location">
	<xsl:for-each select="./f[@c='856']">
		<xsl:element name="f">	
			<xsl:attribute name="c">856</xsl:attribute>
			<xsl:attribute name="ind"><xsl:value-of select="./@ind"/></xsl:attribute>
			<xsl:for-each select="./s">	
				<xsl:element name="s">	
					<xsl:attribute name="c"><xsl:value-of select="./@c"/></xsl:attribute>
					<xsl:value-of select="."/>
				</xsl:element>	
			</xsl:for-each>																						
		</xsl:element>	
	</xsl:for-each>	
</xsl:template>


</xsl:stylesheet> 
