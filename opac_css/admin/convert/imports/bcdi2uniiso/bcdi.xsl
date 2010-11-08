<?xml version="1.0" encoding="iso-8859-1"?>
<!-- $Id: bcdi.xsl,v 1.2 2006-04-28 05:35:04 touraine37 Exp $ -->
<!DOCTYPE stylesheet [
	<!ENTITY MAJUSCULE "ABCDEFGHIJKLMNOPQRSTUVWXYZ">
	<!ENTITY MINUSCULE "abcdefghijklmnopqrstuvwxyz">
	<!ENTITY MAJUS_EN_MINUS " '&MAJUSCULE;' , '&MINUSCULE;' ">
	<!ENTITY MINUS_EN_MAJUS " '&MINUSCULE;' , '&MAJUSCULE;' ">
]>
<xsl:stylesheet version = '1.0'
     xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>

<xsl:output method="xml" version="1.0" encoding="iso-8859-1" indent="yes"/>

<xsl:template match="/MEMO_NOTICES">
<unimarc>
		<xsl:apply-templates select="NOTICES"/>
</unimarc>
</xsl:template>

<xsl:template match="NOTICES">
	<notice>
		<xsl:element name="rs">*</xsl:element>
		<xsl:element name="ru">*</xsl:element>
		<xsl:element name="el">1</xsl:element>
	
		<xsl:if test="./TYPE_NOTICE_N">
			<xsl:call-template name="type_notice">
				<xsl:with-param name="noeud" select="./TYPE_NOTICE_N"/>
			</xsl:call-template>
		</xsl:if>
		
		<xsl:if test="./DOCUMENTS/TYPE_DOC_D">
			<xsl:call-template name="type_doc">
				<xsl:with-param name="noeud" select="./DOCUMENTS/TYPE_DOC_D"/>
			</xsl:call-template>
		</xsl:if>
		
		<!-- Dans l'ordre -->
		<!-- Numéro de référence -->
		<xsl:call-template name="ref"/>
		<!-- ISBN/PRIX-->
		<xsl:call-template name="isbn"/>
		<xsl:call-template name="issn"/>
		<!-- Langue -->
		<xsl:call-template name="langue"/>
		<!-- Titres -->
		<xsl:call-template name="titre"/>
		<!-- Mention d'édition -->
		<xsl:call-template name="mention_edition"/>
		<!-- Editeur -->
		<xsl:call-template name="editeurs"/>
		<!-- Collation -->
		<xsl:call-template name="collation"/>
		<!-- Collection -->
		<xsl:call-template name="collection"/>
		<!-- Notes -->
		<xsl:call-template name="notes"/>
		<!-- EAN -->
		<xsl:call-template name="ean"/>
		<!-- Série -->
		<!-- Périodiques -->
		<xsl:if test="./DOCUMENTS/SUPPORT_D='Périodique'">
				<xsl:call-template name="periodiques"/>
		</xsl:if>
		<!-- Descripteurs -->
		<xsl:call-template name="descripteurs"/>
		<!-- Mots clés -->
		<xsl:call-template name="mots_clefs"/>
		<!-- Dewey -->
		<!--<xsl:call-template name="dewey"/>-->
		<!-- Auteurs -->
		<xsl:if test="./AUTEURS">
			<xsl:call-template name="construct_auteurs">
				<xsl:with-param name="compteur" select="1"/>
				<xsl:with-param name="fonctions" select="./FONCTIONS_N"/>
				<xsl:with-param name="notc" select="."/>
			</xsl:call-template>
		</xsl:if>
		<!-- URL -->
		<xsl:call-template name="url"/>
		<!-- Champs persos -->
		<xsl:call-template name="persos"/>
		<!-- Exemplaires -->
		<xsl:call-template name="exemplaires">
			<xsl:with-param name="n_ex" select="1"/>	
		</xsl:call-template>
	</notice>
</xsl:template>

<!-- Construction de la liste des auteurs -->
<xsl:template name="construct_auteurs">
	<xsl:param name="compteur"/>
	<xsl:param name="fonctions"/>
	<xsl:param name="notc"/>
	
	<xsl:variable name="auteur_no" select="substring-before($fonctions,'/')"/>
	<xsl:element name="f">
		<xsl:attribute name="c">
			<xsl:choose>
				<xsl:when test="$compteur=1">700</xsl:when>
				<xsl:otherwise>701</xsl:otherwise>
			</xsl:choose>
		</xsl:attribute>
		<xsl:attribute name="ind"> 0</xsl:attribute>
		<xsl:apply-templates select="$notc/AUTEURS[$compteur]/*"/>
		<xsl:if test="$auteur_no">
			<xsl:element name="s">
				<xsl:attribute name="c">4</xsl:attribute>
				<xsl:choose>
					<xsl:when test="normalize-space($auteur_no)='Auteur'">
						<xsl:text>070</xsl:text>
					</xsl:when>
					<xsl:when test='normalize-space($auteur_no)="Chef d&apos;orchestre"'>
						<xsl:text>250</xsl:text>
					</xsl:when>
					<xsl:when test="normalize-space($auteur_no)='Compositeur'">
						<xsl:text>230</xsl:text>
					</xsl:when>
					<xsl:when test="normalize-space($auteur_no)='Directeur de la publication'">
						<xsl:text>651</xsl:text>
					</xsl:when>
					<xsl:when test="normalize-space($auteur_no)='Graphiste'">
						<xsl:text>410</xsl:text>
					</xsl:when>
					<xsl:when test="normalize-space($auteur_no)='Illustrateur'">
						<xsl:text>440</xsl:text>
					</xsl:when>
					<xsl:when test="normalize-space($auteur_no)='Interprète'">
						<xsl:text>590</xsl:text>
					</xsl:when>
					<xsl:when test="normalize-space($auteur_no)='Interviewé'">
						<xsl:text>460</xsl:text>
					</xsl:when>
					<xsl:when test="normalize-space($auteur_no)='Intervieweur'">
						<xsl:text>470</xsl:text>
					</xsl:when>
					<xsl:when test="normalize-space($auteur_no)='Parolier'">
						<xsl:text>520</xsl:text>
					</xsl:when>
					<xsl:when test="normalize-space($auteur_no)='Photographe'">
						<xsl:text>600</xsl:text>
					</xsl:when>
					<xsl:when test="normalize-space($auteur_no)='Réalisateur'">
						<xsl:text>370</xsl:text>
					</xsl:when>
					<xsl:when test="normalize-space($auteur_no)='Traducteur'">
						<xsl:text>730</xsl:text>
					</xsl:when>
					<xsl:otherwise>
						<xsl:text>070</xsl:text>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:element>
		</xsl:if>
	</xsl:element>
	<xsl:if test="$notc/AUTEURS[$compteur+1]">
		<xsl:call-template name="construct_auteurs">
			<xsl:with-param name="compteur" select="$compteur+1"/>
			<xsl:with-param name="fonctions" select="substring-after($fonctions,'/')"/>
			<xsl:with-param name="notc" select="$notc"/>
		</xsl:call-template>
	</xsl:if>
</xsl:template>

<xsl:template match="AUTEUR_A">
	<xsl:choose>	
		<xsl:when test="contains(.,',')">
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="substring-before(.,',')"/>
			</xsl:element>
			<xsl:element name="s">
				<xsl:attribute name="c">b</xsl:attribute>
				<xsl:value-of select="normalize-space(substring-after(.,','))"/>
			</xsl:element>
		</xsl:when>
		<xsl:otherwise>
			<xsl:element name="s">
					<xsl:attribute name="c">a</xsl:attribute>
					<xsl:value-of select="."/>
			</xsl:element>
		</xsl:otherwise>
	</xsl:choose>
	<!-- Autres éléments -->
	<xsl:if test="../DATE_DE_NAISSANCE_A|../DATE_DE_DECES_A">
		<xsl:element name="s">
				<xsl:attribute name="c">f</xsl:attribute>
				<xsl:value-of select="concat(../DATE_DE_NAISSANCE_A,'-',../DATE_DE_DECES_A)"/>
		</xsl:element>
	</xsl:if>
</xsl:template>

<!-- Identifiant unique -->
<xsl:template name="ref">
	<xsl:if test="IDENTITE_N_N">
		<xsl:element name="f">
			<xsl:attribute name="c">001</xsl:attribute>
			<xsl:value-of select="IDENTITE_N_N"/>
		</xsl:element>
	</xsl:if>
</xsl:template>

<!-- Type de notice et niveau hiérarchique -->
<xsl:template name="type_notice">
	<xsl:param name="noeud"/>
	<xsl:element name="bl">
		<xsl:choose>
			<xsl:when test="$noeud='Notice générale'">
				<xsl:text>m</xsl:text>
			</xsl:when>
			<xsl:otherwise>
				<xsl:text>a</xsl:text>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:element>
	<xsl:element name="hl">
		<xsl:choose>
			<xsl:when test="$noeud='Article'">
				<xsl:text>2</xsl:text>
			</xsl:when>
			<xsl:otherwise>
				<xsl:text>0</xsl:text>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:element>
</xsl:template>

<!--LANGUE -->
<xsl:template name="langue">
	<xsl:if test="LANGUE_N">
		<xsl:element name="f">
			<xsl:attribute name="c">101</xsl:attribute>
			<xsl:attribute name="ind">0 </xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="translate(LANGUE_N,&MAJUS_EN_MINUS;)"/>
			</xsl:element>			
		</xsl:element>
	</xsl:if>
</xsl:template>

<!--TITRE -->
<xsl:template name="titre" match="TITRE_N">
	<xsl:if test="TITRE_N">
		<xsl:element name="f">
			<xsl:attribute name="c">200</xsl:attribute>
			<xsl:attribute name="ind">
				<xsl:for-each select="SIGNIFICATIF_N">
					<xsl:if test='.="Oui"'>1 </xsl:if>
					<xsl:if test='.="Non"'>2 </xsl:if>
				</xsl:for-each>
			</xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="TITRE_N"/>
			</xsl:element>			
		</xsl:element>
	</xsl:if>
</xsl:template>

<!-- COLLATION -->
<xsl:template name="collation">
	<xsl:if test="COLLATION_N|DOCUMENTS/STANDARD_D">
		<xsl:if test="DOCUMENTS/SUPPORT_D!='Périodique'">
			<xsl:element name="f">
				<xsl:attribute name="c">215</xsl:attribute>
				<xsl:if test="COLLATION_N">
					<xsl:element name="s">
						<xsl:attribute name="c">a</xsl:attribute>
						<xsl:value-of select="COLLATION_N"/>
					</xsl:element>
				</xsl:if>	
				<xsl:if test="DOCUMENTS/STANDARD_D">
					<xsl:element name="s">
						<xsl:attribute name="c">d</xsl:attribute>
						<xsl:value-of select="DOCUMENTS/STANDARD_D"/>
					</xsl:element>
				</xsl:if>			
			</xsl:element>
		</xsl:if>
	</xsl:if>
</xsl:template>

<!-- DOCUMENTS -->

<!-- Type de document -->
<xsl:template name="type_doc">
	<xsl:param name="noeud"/>
	<xsl:element name="dt">
		<xsl:choose>
			<xsl:when test="$noeud='Document projeté, vidéo'">
				<xsl:text>g</xsl:text>
			</xsl:when>
			<xsl:when test="$noeud='Texte imprimé'">
				<xsl:text>a</xsl:text>
			</xsl:when>
			<xsl:when test="$noeud='Enregistrement sonore'">
				<xsl:text>i</xsl:text>
			</xsl:when>
			<xsl:when test="$noeud='Texte manuscrit'">
				<xsl:text>b</xsl:text>
			</xsl:when>
			<xsl:when test="$noeud='Document cartographique'">
				<xsl:text>e</xsl:text>
			</xsl:when>
			<xsl:when test="$noeud='Ressource électronique'">
				<xsl:text>l</xsl:text>
			</xsl:when>
			<xsl:when test="$noeud='Ressource en ligne'">
				<xsl:text>l</xsl:text>
			</xsl:when>
			<xsl:when test="$noeud='Document graphique'">
				<xsl:text>k</xsl:text>
			</xsl:when>
			<xsl:when test="$noeud='Document multisupport'">
				<xsl:text>m</xsl:text>
			</xsl:when>
			<xsl:when test="$noeud='Objet 3 dimensions'">
				<xsl:text>r</xsl:text>
			</xsl:when>
			<xsl:when test="$noeud='Autre'">
				<xsl:text>m</xsl:text>
			</xsl:when>
		</xsl:choose>
	</xsl:element>
</xsl:template>

<!-- EAN -->
<xsl:template name="ean">
	<xsl:if test="DOCUMENTS/CODE_BARRE_D">
		<xsl:element name="f">
			<xsl:attribute name="c">345</xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">b</xsl:attribute>
				<xsl:value-of select="normalize-space(translate(DOCUMENTS/CODE_BARRE_D,'/',''))"/>
			</xsl:element>			
		</xsl:element>
	</xsl:if>
</xsl:template>

<!-- ISBN -->
<xsl:template name="isbn">
	<xsl:if test="DOCUMENTS/ISBN_D">
		<xsl:element name="f">
			<xsl:attribute name="c">010</xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="normalize-space(translate(DOCUMENTS/ISBN_D,'/',''))"/>
			</xsl:element>
			<xsl:if test="DOCUMENTS/COUT_D_D">
				<xsl:element name="s">
					<xsl:attribute name="c">d</xsl:attribute>
					<xsl:value-of select="DOCUMENTS/COUT_D_D"/>
				</xsl:element>
			</xsl:if>			
		</xsl:element>
	</xsl:if>
</xsl:template>

<!-- ISSN -->
<xsl:template name="issn">
	<xsl:if test="DOCUMENTS/ISSN_D">
		<xsl:element name="f">
			<xsl:attribute name="c">011</xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="normalize-space(translate(DOCUMENTS/ISSN_D,'/',''))"/>
			</xsl:element>			
		</xsl:element>
	</xsl:if>
</xsl:template>

<!-- Mention d'édition -->
<xsl:template name="mention_edition">
	<xsl:if test="DOCUMENTS/EDITION_D">
		<xsl:element name="f">
			<xsl:attribute name="c">205</xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="DOCUMENTS/EDITION_D"/>
			</xsl:element>			
		</xsl:element>
	</xsl:if>
</xsl:template>

<!--EDITEUR -->
<xsl:template name="editeurs">
	<xsl:if test="DOCUMENTS/EDITEURS[1]|DATE_EDITION_N_N">
		<xsl:element name="f">
			<xsl:attribute name="c">210</xsl:attribute>
			<xsl:if test="DOCUMENTS/EDITEURS[1]/VILLE_E">
				<xsl:element name="s">
					<xsl:attribute name="c">a</xsl:attribute>
					<xsl:value-of select="DOCUMENTS/EDITEURS[1]/VILLE_E"/>
				</xsl:element>
			</xsl:if>
			<xsl:if test="DOCUMENTS/EDITEURS[1]/EDITEUR_E">
				<xsl:element name="s">
					<xsl:attribute name="c">c</xsl:attribute>
					<xsl:value-of select="DOCUMENTS/EDITEURS[1]/EDITEUR_E"/>
				</xsl:element>
			</xsl:if>
			<xsl:if test="DATE_EDITION_N_N">
				<xsl:element name="s">
				<xsl:attribute name="c">d</xsl:attribute>
				<xsl:value-of select="DATE_EDITION_N_N"/>
			</xsl:element>
			</xsl:if>
		</xsl:element>
	</xsl:if>
</xsl:template>

<!-- COLLECTIONS -->
<xsl:template name="collection">
	<xsl:if test="DOCUMENTS/COLLECTIONS">
		<xsl:if test="DOCUMENTS/SUPPORT_D!='Périodique'">
			<xsl:element name="f">
				<xsl:attribute name="c">225</xsl:attribute>
				<xsl:attribute name="ind">1 </xsl:attribute>
				<!-- NOM COLLECTION -->
				<xsl:element name="s">
					<xsl:attribute name="c">a</xsl:attribute>
					<xsl:value-of select="DOCUMENTS/COLLECTIONS/COLLECTION_C"/>
				</xsl:element>
				<!-- NUMERO DANS LA COLLECTION -->
				<xsl:if test="DOCUMENTS/NO_COLLECTION_D">
					<xsl:element name="s">
						<xsl:attribute name="c">v</xsl:attribute>
						<xsl:value-of select="DOCUMENTS/NO_COLLECTION_D"/>
					</xsl:element>
				</xsl:if>
				<!-- ISSN COLLECTION -->
				<xsl:apply-templates select="DOCUMENTS/COLLECTIONS/ISSN_C_C"/>
				<!-- RESPONSABLE COLLECTION -->
				<xsl:apply-templates select="DOCUMENTS/COLLECTIONS/RESPONSABLE_C"/>
			</xsl:element>
		</xsl:if>
	</xsl:if>
</xsl:template>

<!-- ISSN COLLECTION -->
<xsl:template match="ISSN_C_C">
	<xsl:element name="s">
			<xsl:attribute name="c">x</xsl:attribute>
			<xsl:value-of select="."/>
	</xsl:element>
</xsl:template>

<!-- RESPONSABLE COLLECTION -->
<xsl:template match="RESPONSABLE_C">
	<xsl:element name="s">
			<xsl:attribute name="c">f</xsl:attribute>
			<xsl:value-of select="."/>
	</xsl:element>
</xsl:template>

<!-- NOTES -->
<xsl:template name="notes">
	<!-- Notes générales -->
	<xsl:if test="NOTES_N">
		<xsl:element name="f">
			<xsl:attribute name="c">300</xsl:attribute>
			<xsl:attribute name="ind">  </xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="NOTES_N"/>
			</xsl:element>
		</xsl:element>
	</xsl:if>
	<!-- Note de contenu -->
	<xsl:if test="DIVERS_N">
		<xsl:element name="f">
			<xsl:attribute name="c">327</xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="DIVERS_N"/>
			</xsl:element>			
		</xsl:element>
	</xsl:if>
	<!-- Résumé -->
	<xsl:if test="RESUME_N">
		<xsl:element name="f">
			<xsl:attribute name="c">330</xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="RESUME_N"/>
			</xsl:element>			
		</xsl:element>
	</xsl:if>
</xsl:template>

<!-- MOTS CLES -->
<xsl:template name="mots_clefs" match="MOTS_CLES_N">
	<xsl:if test="MOTS_CLES_N">
		<xsl:element name="f">
			<xsl:attribute name="c">610</xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="MOTS_CLES_N"/>
			</xsl:element>			
		</xsl:element>
	</xsl:if>
</xsl:template>

<!-- DESCRIPTEURS -->
<xsl:template name="descripteurs">
	<xsl:if test="DESCRIPTEURS_N">
		<xsl:call-template name="construct_repeat">
			<xsl:with-param name="chaine" select="DESCRIPTEURS_N"/>
			<xsl:with-param name="field_number" select="'606'"/>
			<xsl:with-param name="subfield_number" select="'a'"/>
		</xsl:call-template>
	</xsl:if>
</xsl:template>

<!-- Dewey -->
<xsl:template name="dewey">
	<xsl:if test="DOCUMENTS/COTE_D">
		<xsl:element name="f">
			<xsl:attribute name="c">676</xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="DOCUMENTS/COTE_D"/>
			</xsl:element>			
		</xsl:element>
	</xsl:if>
</xsl:template>

<!-- Champs persos -->
<xsl:template name="persos">
	<!-- Thèmes -->
	<xsl:if test="GENRES_N">
		<xsl:call-template name="construct_repeat">
			<xsl:with-param name="chaine" select="GENRES_N"/>
			<xsl:with-param name="field_number" select="'900'"/>
			<xsl:with-param name="subfield_number" select="'a'"/>
		</xsl:call-template>
	</xsl:if>

	<!-- Disciplines -->
	<xsl:if test="DISCIPLINES_N_N">
		<xsl:call-template name="construct_repeat">
				<xsl:with-param name="chaine" select="DISCIPLINES_N_N"/>
				<xsl:with-param name="field_number" select="'902'"/>
				<xsl:with-param name="subfield_number" select="'a'"/>
		</xsl:call-template>
	</xsl:if>
	
	<!-- Genre -->
	<xsl:if test="NATURES_N">
		<xsl:call-template name="construct_repeat">
				<xsl:with-param name="chaine" select="NATURES_N"/>
				<xsl:with-param name="field_number" select="'901'"/>
				<xsl:with-param name="subfield_number" select="'a'"/>
		</xsl:call-template>
	</xsl:if>
	
	<!-- Année de péremption -->
	<xsl:if test="DATE_PER_N_N">
		<xsl:element name="f">
			<xsl:attribute name="c">903</xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="DATE_PER_N_N"/>
			</xsl:element>			
		</xsl:element>
	</xsl:if>

	<!-- Date de saisie -->
	<xsl:if test="DATE_DE_SAISIE_D">
		<xsl:element name="f">
			<xsl:attribute name="c">904</xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">a</xsl:attribute>
				<xsl:value-of select="DATE_DE_SAISIE_D"/>
			</xsl:element>			
		</xsl:element>
	</xsl:if>

	<!-- Type de nature -->
	<xsl:if test="TYPES_NATURE_N">
		<xsl:call-template name="construct_repeat">
				<xsl:with-param name="chaine" select="TYPES_NATURE_N"/>
				<xsl:with-param name="field_number" select="'905'"/>
				<xsl:with-param name="subfield_number" select="'a'"/>
		</xsl:call-template>
	</xsl:if>
	
	<!-- Niveau -->
	<xsl:if test="NIVEAUX_N">
		<xsl:call-template name="construct_repeat">
				<xsl:with-param name="chaine" select="NIVEAUX_N"/>
				<xsl:with-param name="field_number" select="'906'"/>
				<xsl:with-param name="subfield_number" select="'a'"/>
		</xsl:call-template>
	</xsl:if>
</xsl:template>

<!-- Traitement d'une chaine séparée par des '/' -->
<xsl:template name="construct_repeat">
	<xsl:param name="chaine"/>
	<xsl:param name="field_number"/>
	<xsl:param name="subfield_number"/>
	
	<xsl:variable name="chaine_no" select="substring-before($chaine,'/')"/>
	<xsl:element name="f">
		<xsl:attribute name="c"><xsl:value-of select="$field_number"/></xsl:attribute>
		<xsl:element name="s">
			<xsl:attribute name="c"><xsl:value-of select="$subfield_number"/></xsl:attribute>
			<xsl:value-of select="normalize-space($chaine_no)"/>
		</xsl:element>			
	</xsl:element>
	<xsl:if test="substring-after($chaine,'/')">
		<xsl:call-template name="construct_repeat">
			<xsl:with-param name="chaine" select="substring-after($chaine,'/')"/>
			<xsl:with-param name="field_number" select="$field_number"/>
			<xsl:with-param name="subfield_number" select="$subfield_number"/>
		</xsl:call-template>
	</xsl:if>
</xsl:template>

<!-- URL -->
<xsl:template name="url">
	<xsl:if test="RESSOURCES/ADRESSE_L">
		<xsl:element name="f">
			<xsl:attribute name="c">856</xsl:attribute>
			<xsl:element name="s">
				<xsl:attribute name="c">u</xsl:attribute>
				<xsl:value-of select="RESSOURCES/ADRESSE_L"/>
			</xsl:element>			
		</xsl:element>
	</xsl:if>
</xsl:template>

<!-- Périodiques -->
<xsl:template name="periodiques">
	<xsl:element name="f">
		<xsl:attribute name="c">464</xsl:attribute>
		<xsl:element name="s">
			<xsl:attribute name="c">t</xsl:attribute>
			<xsl:value-of select="DOCUMENTS/COLLECTIONS/COLLECTION_C"/>
		</xsl:element>
		<xsl:if test="DOCUMENTS/NO_COLLECTION_D">
			<xsl:element name="s">
				<xsl:attribute name="c">v</xsl:attribute>
				<xsl:value-of select="DOCUMENTS/NO_COLLECTION_D"/>
			</xsl:element>
		</xsl:if>
		<xsl:if test="DOCUMENTS/DATE_DE_PARUTION_D">
			<xsl:element name="s">
				<xsl:attribute name="c">d</xsl:attribute>
				<xsl:value-of select="DOCUMENTS/DATE_DE_PARUTION_D"/>
			</xsl:element>
		</xsl:if>
		<xsl:if test="COLLATION_N">
			<xsl:element name="s">
				<xsl:attribute name="c">p</xsl:attribute>
				<xsl:value-of select="COLLATION_N"/>
			</xsl:element>
		</xsl:if>
	</xsl:element>
</xsl:template>

<!-- Exemplaires -->
<xsl:template name="exemplaires">
	<xsl:param name="n_ex"/>
	<xsl:if test="EXEMPLAIRES[$n_ex]">	
		<xsl:element name="f">
			<xsl:attribute name="c">995</xsl:attribute>
			<xsl:call-template name="code_barre">
				<xsl:with-param name="n_ex" select="$n_ex"/>	
			</xsl:call-template>
			<!-- Cote -->
			<xsl:choose>
				<xsl:when test="EXEMPLAIRES[$n_ex]/COTE_E_X">
					<xsl:element name="s">
						<xsl:attribute name="c">k</xsl:attribute>
						<xsl:value-of select="EXEMPLAIRES[$n_ex]/COTE_E_X"/>
					</xsl:element>
				</xsl:when>
				<xsl:otherwise>
					<xsl:element name="s">
						<xsl:attribute name="c">k</xsl:attribute>
						<xsl:text>ARCHIVES</xsl:text>
					</xsl:element>
				</xsl:otherwise>
			</xsl:choose>
			<!-- Autres éléments de l'exemplaire (section, ...) -->
			<xsl:if test="PUBLICS_N">
				<xsl:element name="s">
					<xsl:attribute name="c">q</xsl:attribute>
					<xsl:value-of select="normalize-space(substring-before(PUBLICS_N,'/'))"/>
				</xsl:element>
			</xsl:if>	
			<xsl:if test="DOCUMENTS/SUPPORT_D">
				<xsl:element name="s">
					<xsl:attribute name="c">r</xsl:attribute>
					<xsl:value-of select="DOCUMENTS/SUPPORT_D"/>
				</xsl:element>
			</xsl:if>
			<xsl:if test="EXEMPLAIRES[$n_ex]/EMPLACEMENT_X">
				<xsl:element name="s">
					<xsl:attribute name="c">t</xsl:attribute>
					<xsl:value-of select="EXEMPLAIRES[$n_ex]/EMPLACEMENT_X"/>
				</xsl:element>
			</xsl:if>
			<!-- Commentaire -->
			<xsl:if test="EXEMPLAIRES[$n_ex]/DIVERS_E_X">
				<xsl:element name="s">
					<xsl:attribute name="c">u</xsl:attribute>
					<xsl:value-of select="EXEMPLAIRES/DIVERS_E_X"/>
				</xsl:element>
			</xsl:if>
		</xsl:element>
		<xsl:if test="EXEMPLAIRES[$n_ex+1]">
			<xsl:call-template name="exemplaires">
				<xsl:with-param name="n_ex" select="$n_ex+1"/>	
			</xsl:call-template>
		</xsl:if>
	</xsl:if>
</xsl:template>
<!-- Numéro d'exemplaire -->
<xsl:template name="code_barre">
		<xsl:param name="n_ex"/>
		<xsl:choose>
			<xsl:when test="EXEMPLAIRES[$n_ex]/CODE_EXEMPLAIRE_X">
				<xsl:element name="s">
					<xsl:attribute name="c">f</xsl:attribute>
					<xsl:value-of select="EXEMPLAIRES[$n_ex]/CODE_EXEMPLAIRE_X"/>
				</xsl:element>
			</xsl:when>
			<xsl:otherwise>
				<xsl:choose>
					<xsl:when test="DOCUMENTS/CODE_BARRE_D">
						<xsl:element name="s">
							<xsl:attribute name="c">f</xsl:attribute>
							<xsl:value-of select="DOCUMENTS/CODE_BARRE_D"/>
						</xsl:element>
					</xsl:when>
					<xsl:otherwise>
						<xsl:element name="s">
							<xsl:attribute name="c">f</xsl:attribute>
							<xsl:text>INCONNU</xsl:text>
						</xsl:element>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:otherwise>
		</xsl:choose>
</xsl:template>

<!-- /DOCUMENTS -->

<xsl:template match="*"/>

</xsl:stylesheet>
