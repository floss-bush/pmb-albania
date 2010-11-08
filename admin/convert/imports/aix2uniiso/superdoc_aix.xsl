<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:inm="http://www.inmagic.com/webpublisher/query">

<xsl:output method="xml" version="1.0" encoding="iso-8859-1" indent="yes"/>

<xsl:template match="/inm:Results">
	<xsl:apply-templates select="inm:Recordset"/>
</xsl:template>

<xsl:template match="inm:Recordset">
<unimarc>
	<xsl:apply-templates select="inm:Record"/>
</unimarc>
</xsl:template>

<xsl:template match="inm:Record">
	<xsl:if test="normalize-space(./inm:Nom-periodique)!='' "> <!-- article -->
		<xsl:call-template name="notice">
			<xsl:with-param name="doctype" select="./inm:Type-de-Document" />
			<xsl:with-param name="nottype">article</xsl:with-param>
		</xsl:call-template>
	</xsl:if>
	<xsl:if test="./inm:Nom-periodique=''"> <!-- monographie -->
		<xsl:call-template name="notice">
			<xsl:with-param name="doctype" select="./inm:Type-de-Document" />
			<xsl:with-param name="nottype">mono</xsl:with-param>
		</xsl:call-template>
	</xsl:if>
</xsl:template>


<xsl:template name="notice">
	<xsl:param name='doctype'/>
	<xsl:param name='nottype'/>
	<notice>
		<xsl:element name="rs">*</xsl:element>
		<xsl:element name="ru">*</xsl:element>
		<xsl:element name="el">1</xsl:element>
		<xsl:element name="dt">
			<xsl:choose>
				<xsl:when test="$doctype='texte imprimé'">a</xsl:when>
				<xsl:when test="$doctype='texte manuscrit'">b</xsl:when>
				<xsl:when test="$doctype='partition musicale imprimée'">c</xsl:when>
				<xsl:when test="$doctype='partition musicale manuscrite'">d</xsl:when>
				<xsl:when test="$doctype='document cartographique imprimé'">e</xsl:when>
				<xsl:when test="$doctype='document cartographique manuscrit'">f</xsl:when>
				<xsl:when test="$doctype='document projeté ou vidéo'">g</xsl:when>
				<xsl:when test="$doctype='enregistrement sonore non musical'">i</xsl:when>
				<xsl:when test="$doctype='enregistrement sonore musical'">j</xsl:when>
				<xsl:when test="$doctype='document graphique à deux dimensions'">k</xsl:when>
				<xsl:when test="$doctype='document électronique'">l</xsl:when>
				<xsl:when test="$doctype='document multimédia'">m</xsl:when>
				<xsl:when test="$doctype='objet à 3 dimensions, artefacts, ...'">r</xsl:when>
				<xsl:otherwise>a</xsl:otherwise>
			</xsl:choose>
		</xsl:element>
		
		<xsl:element name="bl">
			<xsl:choose>
				<xsl:when test="$nottype='article'">a</xsl:when>
				<xsl:otherwise>m</xsl:otherwise>
			</xsl:choose>
		</xsl:element>
		
		<xsl:element name="hl">
			<xsl:choose>
				<xsl:when test="$nottype='article'">2</xsl:when>
				<xsl:otherwise>0</xsl:otherwise>
			</xsl:choose>
		</xsl:element>

		<xsl:choose>	
			<xsl:when test="$nottype='article'">	
				<xsl:call-template name="article"/>
			</xsl:when>
			<xsl:otherwise>
				<xsl:call-template name="mono"/>
			</xsl:otherwise>
		</xsl:choose>
	</notice>
</xsl:template>


<!-- mono -->
<xsl:template name='mono'>
	<xsl:call-template name="isbn"/>
	<xsl:call-template name="langues"/>
	<xsl:call-template name="titres"/>
	<xsl:call-template name="edition"/>
	<xsl:call-template name="collation"/>
	<xsl:call-template name="collection"/>
	<xsl:call-template name="notes"/>
	<xsl:call-template name="indexations"/>
	<xsl:call-template name="responsabilites"/>
	<xsl:call-template name="origine"/>
	<xsl:call-template name="url"/>
	<xsl:call-template name="persos"/>
	<xsl:call-template name="exemplaires">
		<xsl:with-param name="n_ex" select="1"/>	
	</xsl:call-template>
</xsl:template>


<!-- article -->
<xsl:template name='article'>
	<xsl:call-template name="isbn"/>
	<xsl:call-template name="langues"/>
	<xsl:call-template name="titres"/>
	
	<xsl:if test="./inm:Titre='' and ./inm:Sous-titre='' and ./inm:Titre-generique=''">
		<f c='200'><s c='a'>_OBJECT_BULLETIN_</s></f>
	</xsl:if>
	
	<!-- lien perio -->
	<f c='461'>
		<s c='t'>
			<xsl:choose>
				<xsl:when test="./inm:Nom-periodique!=''"> <!-- nom perio -->
					<xsl:value-of select="./inm:Nom-periodique"/>
				</xsl:when>
				<xsl:otherwise>INDETERMINE</xsl:otherwise>
			</xsl:choose>
		</s>
		<s c="9">lnk:perio</s>
	</f>		
	<!-- lien bulletin -->					
	<f c='463'>
		<s c='v'>
			<xsl:choose> <!-- numero -->
				<xsl:when test="normalize-space(./inm:No-collection)=''">
					<xsl:text>INDETERMINE</xsl:text>
				</xsl:when> 
				<xsl:when test="normalize-space(substring-before(./inm:No-collection,','))!=''" >
					<xsl:value-of select="concat('N° ', normalize-space(substring-before(./inm:No-collection,',')))"/>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="concat('N° ', normalize-space(./inm:No-collection))"/>
				</xsl:otherwise>
			</xsl:choose>
		</s>
		<s c='e'>
			<xsl:choose>  <!-- date -->
				<xsl:when test="normalize-space(./inm:No-collection)='' and normalize-space(./inm:Date-Edition)=''" >
					<xsl:text>INDETERMINE</xsl:text>
				</xsl:when>
				<xsl:when test="normalize-space(substring-after(./inm:No-collection,','))='' and normalize-space(./inm:Date-Edition)=''" >
					<xsl:text>INDETERMINE</xsl:text>
				</xsl:when>
				<xsl:when test="normalize-space(substring-after(./inm:No-collection,','))!='' and normalize-space(./inm:Date-Edition)='' ">
					<xsl:value-of select="normalize-space(substring-after(./inm:No-collection,','))"/>
				</xsl:when>
				<xsl:when test="normalize-space(substring-after(./inm:No-collection,','))!='' and normalize-space(./inm:Date-Edition)!='' ">
					<xsl:choose>
						<xsl:when test="normalize-space(substring-before(./inm:No-collection,./inm:Date-Edition))!=''">
							<xsl:value-of select="normalize-space(substring-after(./inm:No-collection,','))"/>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="concat(normalize-space(substring-after(./inm:No-collection,',')),' ',normalize-space(./inm:Date-Edition))"/>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:when>
				<xsl:when test="normalize-space(substring-after(./inm:No-collection,','))='' and normalize-space(./inm:Date-Edition)!=''">
					<xsl:value-of select="normalize-space(./inm:Date-Edition)"/>
				</xsl:when>
			</xsl:choose>						
		</s>
		<s c="9">lnk:bull</s>
	</f>
	<xsl:call-template name="edition"/>
	<xsl:call-template name="collation"/>
	<xsl:call-template name="notes"/>
	<xsl:call-template name="indexations"/>
	<xsl:call-template name="responsabilites"/>
	<xsl:call-template name="origine"/>
	<xsl:call-template name="url"/>
	<xsl:call-template name="persos"/>
	<xsl:call-template name="exemplaires">
		<xsl:with-param name="n_ex" select="1"/>	
	</xsl:call-template>			
</xsl:template>


<!-- ISBN/ISSN/prix -->
<xsl:template name="isbn">
	<xsl:if test="./inm:ISBN!='' or ./inm:Prix-de-vente!=''" >
		<f c='010'>
			<xsl:if test="./inm:ISBN!=''">
				<s c='a'><xsl:value-of select="./inm:ISBN"/></s>
			</xsl:if>
			<xsl:if test="./inm:Prix-de-vente!=''">
				<s c='d'><xsl:value-of select="./inm:Prix-de-vente"/></s>
			</xsl:if>				
		</f>
	</xsl:if>
	<xsl:if test="./inm:ISSN!=''">
		<f c='011'>
			<s c='a'><xsl:value-of select="./inm:ISSN"/></s>
		</f>		
	</xsl:if>
</xsl:template>


<!-- langues -->
<xsl:template name="langues">
	<xsl:if test="./inm:Langue!=''">
		<f c='101'>
			<xsl:for-each select="./inm:Langue">
				<xsl:choose>
					<xsl:when test=".='Français'">
						<s c='a'>fre</s>				
					</xsl:when>
					<xsl:when test=".='Anglais'">
						<s c='a'>eng</s>				
					</xsl:when>
					<xsl:when test=".='Allemand'">
						<s c='a'>ger</s>				
					</xsl:when>
					<xsl:when test=".='Espagnol'">
						<s c='a'>spa</s>				
					</xsl:when>
					<xsl:when test=".='Italien'">
						<s c='a'>ita</s>				
					</xsl:when>
					<xsl:when test=".='Japonais'">
						<s c='a'>jpn</s>				
					</xsl:when>					
					<xsl:when test=".='Chinois'">
						<s c='a'>chi</s>				
					</xsl:when>					
					<xsl:when test=".='Arabe'">
						<s c='a'>ara</s>				
					</xsl:when>					
					<xsl:when test=".='Basque'">
						<s c='a'>baq</s>				
					</xsl:when>					
					<xsl:when test=".='Danois'">
						<s c='a'>dan</s>				
					</xsl:when>					
					<xsl:when test=".='Esperanto'">
						<s c='a'>esp</s>				
					</xsl:when>					
					<xsl:when test=".='Français ancien'">
						<s c='a'>fro</s>				
					</xsl:when>					
					<xsl:when test=".='Grec classique'">
						<s c='a'>grc</s>				
					</xsl:when>					
					<xsl:when test=".='Grec moderne'">
						<s c='a'>gre</s>				
					</xsl:when>					
					<xsl:when test=".='Hébreu'">
						<s c='a'>heb</s>				
					</xsl:when>					
					<xsl:when test=".='Hongrois'">
						<s c='a'>hun</s>				
					</xsl:when>					
					<xsl:when test=".='Irlandais'">
						<s c='a'>iri</s>				
					</xsl:when>					
					<xsl:when test=".='Latin'">
						<s c='a'>lat</s>				
					</xsl:when>					
					<xsl:when test=".='Multilingue'">
						<s c='a'>mul</s>				
					</xsl:when>					
					<xsl:when test=".='Néerlandais'">
						<s c='a'>dut</s>				
					</xsl:when>					
					<xsl:when test=".='Occitan'">
						<s c='a'>oci</s>				
					</xsl:when>					
					<xsl:when test=".='Polonais'">
						<s c='a'>pol</s>				
					</xsl:when>					
					<xsl:when test=".='Portugais'">
						<s c='a'>por</s>				
					</xsl:when>					
					<xsl:when test=".='Provençal'">
						<s c='a'>pro</s>				
					</xsl:when>					
					<xsl:when test=".='Roumain'">
						<s c='a'>rum</s>				
					</xsl:when>					
					<xsl:when test=".='Russe'">
						<s c='a'>rus</s>				
					</xsl:when>					
					<xsl:when test=".='Tchèque'">
						<s c='a'>cze</s>				
					</xsl:when>					
					<xsl:when test=".='Turc'">
						<s c='a'>tur</s>				
					</xsl:when>					
					<xsl:when test=".='Yiddish'">
						<s c='a'>yid</s>				
					</xsl:when>					
					<xsl:when test=".='Coréen'">
						<s c='a'>kor</s>				
					</xsl:when>					
					<xsl:when test=".='Libanais'">
						<s c='a'>ara</s>				
					</xsl:when>					
					<xsl:when test=".='Suédois'">
						<s c='a'>swe</s>				
					</xsl:when>					
					<xsl:otherwise>
						<s c='a'><xsl:value-of select="."/></s>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:for-each>
		</f>
	</xsl:if>
</xsl:template>


<!-- titres -->

<xsl:template name="titres">
	<!-- titre / sous titre -->
	<f c='200'>

	<s c='a'>
	<xsl:choose>
		<xsl:when test="./inm:Titre!='' and ./inm:Sous-titre!='' ">
			<xsl:for-each select="./inm:Titre">
				<xsl:value-of select="concat(.,' ')" />
			</xsl:for-each>
			<xsl:text> : </xsl:text>
			<xsl:for-each select="./inm:Sous-titre">
				<xsl:value-of select="concat(.,' ')"/>
			</xsl:for-each>
		</xsl:when>
		<xsl:when test="./inm:Titre!='' and ./inm:Sous-titre='' ">
			<xsl:for-each select="./inm:Titre">
				<xsl:value-of select="concat(.,' ')" />
			</xsl:for-each>
		</xsl:when>
		<xsl:when test="./inm:Titre='' and ./inm:Titre-generique!='' ">
			<xsl:value-of select="./inm:Titre-generique"/>
		</xsl:when>
	</xsl:choose>
	
	</s>

<!-- 
	<xsl:choose>
		<xsl:when test="./inm:Titre!='' and ./inm:Sous-titre!='' ">
			<s c='a'><xsl:value-of select="concat(./inm:Titre,' : ',./inm:Sous-titre)"/></s>
		</xsl:when>
		<xsl:when test="./inm:Titre!='' and ./inm:Sous-titre='' ">
			<s c='a'><xsl:value-of select="./inm:Titre"/></s>
		</xsl:when>
		<xsl:when test="./inm:Titre='' and ./inm:Titre-generique!='' ">
			<s c='a'><xsl:value-of select="./inm:Titre-generique"/></s>
		</xsl:when>
	</xsl:choose>
-->


	</f>
</xsl:template>



<!-- Edition -->
<xsl:template name="edition">
	<!-- Editeur -->
	<xsl:if test="./inm:Editeur!='' or ./inm:Date-Edition!=''">
		<f c='210'>
			<xsl:if test="./inm:Editeur!=''">
				<s c='c'><xsl:value-of select="./inm:Editeur"/></s>
			</xsl:if>
			<xsl:if test="./inm:Date-Edition!=''">
				<s c='d'><xsl:value-of select="./inm:Date-Edition"/></s>
			</xsl:if>
		</f>
	</xsl:if>
</xsl:template>


<!-- collation -->
<xsl:template name="collation">
	<xsl:if test="./inm:Description-physique!='' or ./inm:Pagination!='' or ./inm:Duree!=''">
		<f c='215'>
			<s c='a'>
				<xsl:if test="./inm:Description-physique!=''">
					<xsl:value-of select="./inm:Description-physique"/>
				</xsl:if>					
				<xsl:if test="./inm:Pagination!='' and ./inm:Description-physique!=''">
					<xsl:text >&#x020;</xsl:text>
				</xsl:if>	
				<xsl:if test="./inm:Pagination!=''">
					<xsl:value-of select="./inm:Pagination"/>
				</xsl:if>					
				<xsl:if test="./inm:Duree!='' and (./inm:Description-physique!='' or ./inm:Pagination!='')">
					<xsl:text >&#x020;</xsl:text>
				</xsl:if>	
				<xsl:if test="./inm:Duree!=''">
					<xsl:value-of select="./inm:Duree"/>
				</xsl:if>					
			</s>
		</f>
	</xsl:if>
</xsl:template>


<!-- Collection -->
<xsl:template name="collection">
	<xsl:if test="./inm:Collection!='' or ./inm:No-collection!=''">
		<f c='225'>
			<xsl:if test="./inm:Collection!=''">
				<s c='a'><xsl:value-of select="./inm:Collection"/></s>
			</xsl:if>
			<xsl:if test="./inm:No-collection!=''">
				<s c='v'><xsl:value-of select="./inm:No-collection"/></s>
			</xsl:if>
		</f>
	</xsl:if>
</xsl:template>


<!-- Notes -->
<xsl:template name="notes">
	<!-- Notes générales -->
	<xsl:if test="./inm:Notes!=''">
		<f c='300'>
			<s c='a'><xsl:value-of select="./inm:Notes"/></s>
		</f>
	</xsl:if>
	<!-- Résumé -->
	<xsl:if test="./inm:Resume!=''">
		<f c='330'>
			<s c='a'><xsl:value-of select="./inm:Resume"/></s>
		</f>
	</xsl:if>
</xsl:template>

	
<!-- responsabilites -->
<xsl:template name="responsabilites">
	<xsl:for-each select="./inm:Auteur">
		<xsl:variable name="type">
			<xsl:choose>
				<xsl:when test="position()=1">700</xsl:when> 
				<xsl:otherwise>701</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<xsl:choose>
			<xsl:when test="normalize-space(substring-before(.,','))">
				<f c='{$type}' ind='  '>
					<s c='a'><xsl:value-of select="normalize-space(substring-before(.,','))"/></s>
					<s c='b'><xsl:value-of select="normalize-space(substring-after(.,','))"/></s>
				</f>
			</xsl:when>
			<xsl:when test="normalize-space(.)">
				<f c='{$type}' ind='  '>
					<s c='a'><xsl:value-of select="normalize-space(.)"/></s>
				</f>
			</xsl:when>
		</xsl:choose>
	</xsl:for-each>
	<xsl:for-each select="./inm:Auteur-collectif">
		<xsl:choose>
			<xsl:when test="position()=1 and ../inm:Auteur = '' and normalize-space(.)">
				<f c='710' ind='0 '>
					<s c='a'><xsl:value-of select="normalize-space(.)"/></s>
				</f>
			</xsl:when> 
			<xsl:when test="normalize-space(.)">
				<f c='711' ind='0 '>
					<s c='a'><xsl:value-of select="normalize-space(.)"/></s>
				</f>
			</xsl:when>
		</xsl:choose>
	</xsl:for-each>
	<xsl:for-each select="./inm:Auteur-secondaire">
			<xsl:choose>
				<xsl:when test="normalize-space(substring-before(.,','))">
					<f c='702' ind='  '>
						<s c='a'><xsl:value-of select="normalize-space(substring-before(.,','))"/></s>
						<s c='b'><xsl:value-of select="normalize-space(substring-after(.,','))"/></s>
					</f>
				</xsl:when>
				<xsl:when test="normalize-space(.)">
					<f c='702' ind='  '>
						<s c='a'><xsl:value-of select="normalize-space(.)"/></s>
					</f>
				</xsl:when>
			</xsl:choose>
	</xsl:for-each>
</xsl:template>


<!-- indexations -->
<xsl:template name="indexations">
	<xsl:if test="./inm:Descripteurs!=''">
		<xsl:for-each select="./inm:Descripteurs">
			<f c='606'>
				<s c='a'><xsl:value-of select="."/></s>
			</f>
		</xsl:for-each>
	</xsl:if>
	<xsl:if test="./inm:Descripteurs-geo!=''">
		<xsl:for-each select="./inm:Descripteurs-geo">
			<f c='606'>
				<s c='a'><xsl:value-of select="."/></s>
			</f>
		</xsl:for-each>
	</xsl:if>
	<xsl:if test="./inm:Desc-noms-propres!=''">
		<xsl:for-each select="./inm:Desc-noms-propres">
			<f c='606'>
				<s c='a'><xsl:value-of select="."/></s>
			</f>
		</xsl:for-each>
	</xsl:if>
	<xsl:if test="./inm:Mots-cles!=''">
		<xsl:for-each select="./inm:Mots-cles">
			<f c='610'>
				<s c='a'><xsl:value-of select="."/></s>
			</f>
		</xsl:for-each>
	</xsl:if>	
	<xsl:if test="./inm:Indexation-locale!=''">
		<xsl:for-each select="./inm:Indexation-locale">
			<f c='610'>
				<s c='a'><xsl:value-of select="."/></s>
			</f>
		</xsl:for-each>
	</xsl:if>
</xsl:template>


<!--  origine notice -->
<xsl:template name="origine">
	<xsl:if test="./inm:Origine!=''">
		<f c='801'>
			<s c='b'><xsl:value-of select="./inm:Origine"/></s>
		</f>
	</xsl:if>	
</xsl:template>


<!-- url -->
<xsl:template name="url">
	<xsl:if test="./inm:URL!=''">
		<f c='856'>
			<s c='u'><xsl:value-of select="./inm:URL"/></s>
		</f>
	</xsl:if>	
</xsl:template>


<!-- champs persos -->
<xsl:template name="persos">
	<!-- theme -->
	<xsl:if test="./inm:Theme!=''">
		<f c='900'>
		<xsl:for-each select="./inm:Theme">
			<s c='a'><xsl:value-of select="."/></s>
		</xsl:for-each>
		</f>
	</xsl:if>
	<!-- Genre -->
	<xsl:if test="./inm:Genre-forme!=''">
		<f c='901'>
		<xsl:for-each select="./inm:Genre-forme">
			<s c='a'><xsl:value-of select="."/></s>
		</xsl:for-each>
		</f>
	</xsl:if>	
	<!-- Discipline -->
	<xsl:if test="./inm:Discipline!=''">
		<f c='902'>
		<xsl:for-each select="./inm:Discipline">
			<s c='a'><xsl:value-of select="."/></s>
		</xsl:for-each>
		</f>
	</xsl:if>
	<!-- Année de péremption -->
	<xsl:if test="./inm:Date-de-peremption!=''">
		<f c='903'>
			<s c='a'><xsl:value-of select="./inm:Date-de-peremption"/></s>
		</f>
	</xsl:if>
	<!-- Date catalogage -->
	<xsl:if test="./inm:Date-Catalogage!=''">
		<f c='904'>
			<s c='a'><xsl:value-of select="./inm:Date-Catalogage"/></s>
		</f>
	</xsl:if>
	<!-- Type de nature -->
	<xsl:if test="./inm:Nature-du-document!=''">
		<f c='905'>
		<xsl:for-each select="./inm:Nature-du-document">
			<s c='a'><xsl:value-of select="."/></s>
		</xsl:for-each>
		</f>
	</xsl:if>
	<!-- Niveau -->
	<xsl:if test="./inm:Niveau---public!=''">
		<f c='906'>
		<xsl:for-each select="./inm:Niveau---public">
			<s c='a'><xsl:value-of select="."/></s>
		</xsl:for-each>
		</f>
	</xsl:if>
</xsl:template>


<!-- Exemplaires -->
<xsl:template name="exemplaires">
	<xsl:param name="n_ex"/>
	<xsl:if test="./inm:Code-Barre[$n_ex]!=''">	
		<f c='995'>
			<!-- code barre -->
			<s c='f'><xsl:value-of select="./inm:Code-Barre[$n_ex]"/></s>
			<!-- cote -->
			<s c='k'>
				<xsl:choose>
					<xsl:when test="./inm:Expl-Cote[$n_ex]!=''">
						<xsl:value-of select="./inm:Expl-Cote[$n_ex]"/>
					</xsl:when>				
					<xsl:otherwise>
						<xsl:choose>
							<xsl:when test="./inm:Expl-Cote[1]!=''">
								<xsl:value-of select="./inm:Expl-Cote[1]"/>
							</xsl:when>
							<xsl:otherwise>
								<xsl:text>INDETERMINE</xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:otherwise>				
				</xsl:choose>
			</s>
			<!-- section -->
			<s c='q'>
				<xsl:choose>
					<xsl:when test="./inm:Expl-Localisation[$n_ex]!=''">
						<xsl:value-of select="./inm:Expl-Localisation[$n_ex]"/>
					</xsl:when>				
					<xsl:otherwise>
						<xsl:choose>
							<xsl:when test="./inm:Expl-Localisation[1]!=''">
								<xsl:value-of select="./inm:Expl-Localisation[1]"/>
							</xsl:when>
							<xsl:otherwise>
								<xsl:text>INDETERMINE</xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:otherwise>				
				</xsl:choose>				
			</s>
			<!-- type document -->
			<s c='r'>
			<xsl:choose>
				<xsl:when test="./inm:Support!=''">
					<xsl:value-of select="./inm:Support"/>
				</xsl:when>
				<xsl:otherwise>INDETERMINE</xsl:otherwise>
			</xsl:choose>
			</s>
		</f>
		<f c='996'>
			<!-- Localisation -->
			<s c='v'>
				<xsl:choose>
					<xsl:when test="./inm:Centre!=''">
						<xsl:value-of select="./inm:Centre"/>
					</xsl:when>
					<xsl:otherwise>
						<xsl:text>CDI</xsl:text>
					</xsl:otherwise>
				</xsl:choose>
			</s>
		</f>
	</xsl:if>
	<xsl:if test="./inm:Code-Barre[$n_ex+1]">
		<xsl:call-template name="exemplaires">
			<xsl:with-param name="n_ex" select="$n_ex+1"/>	
		</xsl:call-template>
	</xsl:if>
</xsl:template>

<xsl:template match="*" />

</xsl:stylesheet>