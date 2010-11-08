<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>
<xsl:stylesheet version = '1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
<xsl:output method="xml" indent='yes' encoding="ISO-8859-1" omit-xml-declaration="yes" />
	
<!-- Attention le fichier ../../../../includes/marc_tables/fr_FR/doctype.xml est requis -->	
<!-- Attention le fichier ../../../../includes/marc_tables/fr_FR/recordtype.xml est requis -->	
<!-- Attention le fichier ../../../../includes/marc_tables/fr_FR/function.xml est requis -->	
<!-- Attention le fichier ../../../../includes/marc_tables/fr_FR/lang.xml est requis -->	

	<xsl:template match="/unimarc/notice">
		 <c level="item">
 			<did>
				<xsl:call-template name="titres"/>
				<xsl:call-template name="genreForm"/>
 				<xsl:call-template name="auteurs">
					<xsl:with-param name="balise">2</xsl:with-param>
				</xsl:call-template>
				<xsl:call-template name="collation"/>
  				<xsl:call-template name="lien"/>
				<xsl:call-template name="langPub"/>														 
				<xsl:call-template name="resume"/>
				<xsl:call-template name="cote"/>
				<xsl:call-template name="numNotice"/>
				<xsl:call-template name="dates"/>
			</did>
			 <xsl:call-template name="notes"/>
			 <xsl:call-template name="edition"/>
			 <xsl:call-template name="auteurs">
				<xsl:with-param name="balise">1</xsl:with-param>
			</xsl:call-template>
			 <xsl:call-template name="controlAccess"/>
		</c>
   </xsl:template>
	
	
	<xsl:template name="titres">
		<unittitle>
			<xsl:value-of select="normalize-space(f[@c='200']/s[@c='a'])"/>
		</unittitle>	

		<xsl:if test="bl='a' and f[@c='461']/s[@c='t']!='' ">
			<unittitle type="in">
				<xsl:value-of select="normalize-space(f[@c='461']/s[@c='t'])"/>
				<xsl:if test="f[@c='463']/s[@c='d'] or f[@c='463']/s[@c='e'] or f[@c='463']/s[@c='v']">
					<xsl:text> (</xsl:text>
					<xsl:value-of select="normalize-space(f[@c='463']/s[@c='v'])"/>
					<xsl:choose>
						<xsl:when test="normalize-space(f[@c='463']/s[@c='e'])!=''"><xsl:value-of select="concat(', ',normalize-space(f[@c='463']/s[@c='e']))" /></xsl:when>
						<xsl:otherwise><xsl:value-of select="concat(', ',normalize-space(f[@c='463']/s[@c='d']))" /></xsl:otherwise>
					</xsl:choose>
					<xsl:text> )</xsl:text>
				</xsl:if>
			</unittitle>
		</xsl:if>
		
		<xsl:if test="f[@c='200']/s[@c='d']">
			<unittitle type="parallel">
				<xsl:value-of select="normalize-space(f[@c='200']/s[@c='d'])"/>
			</unittitle>
		</xsl:if>
		<xsl:if test="f[@c='200']/s[@c='e']">
			<unittitle type="otherinfo">
				<xsl:value-of select="normalize-space(f[@c='200']/s[@c='e'])"/>
			</unittitle>
		</xsl:if>		
		<xsl:if test="f[@c='200']/s[@c='c']">
			<unittitle type="titre_auteur_different">
				<xsl:value-of select="normalize-space(f[@c='200']/s[@c='c'])"/>
			</unittitle>
		</xsl:if>		
		<xsl:if test="bl!='a' and f[@c='461']/s[@c='t']!=''">
			<unittitle type="titre_de_serie">
				<xsl:value-of select="normalize-space(f[@c='461']/s[@c='t'])"/>
			</unittitle>
			<xsl:if test="f[@c='461']/s[@c='v'] ">
				<unittitle type="numero_de_serie">
					<xsl:value-of select="normalize-space(f[@c='461']/s[@c='v'])"/>
				</unittitle>
			</xsl:if>	
		</xsl:if>		

		<xsl:for-each select="f[@c='900'][s[@c='n']='titre_de_forme']">
			<unittitle type="titre_de_forme">
				<xsl:value-of select="normalize-space(s[@c='a'])"/>
			</unittitle>
		</xsl:for-each>
		<xsl:for-each select="f[@c='900'][s[@c='n']='titre_general']">
			<unittitle type="titre_general">
				<xsl:value-of select="normalize-space(s[@c='a'])"/>
			</unittitle>
		</xsl:for-each>	
		
		<xsl:for-each select="f[@c='900'][s[@c='n']='titre_original']">
			<unittitle type="titre_original">
				<xsl:value-of select="normalize-space(s[@c='a'])"/>
			</unittitle>
		</xsl:for-each>	

		<xsl:for-each select="f[@c='900'][s[@c='n']='titres_precedents']">
			<unittitle type="titres_precedents">
				<xsl:value-of select="normalize-space(s[@c='a'])" />	
			</unittitle>
		</xsl:for-each>

		<xsl:for-each select="f[@c='900'][s[@c='n']='titres_suivants']">
			<unittitle type="titres_suivants">
				<xsl:value-of select="normalize-space(s[@c='a'])" />	
			</unittitle>
		</xsl:for-each>

	</xsl:template>
	
	
	<xsl:template name="genreForm">
    	<physdesc>
		<xsl:if test="normalize-space(dt)!='' and dt!='*' ">
			<genreform>
		  		<xsl:call-template name="type_doc"><xsl:with-param name="entree" select="normalize-space(dt)"/></xsl:call-template>
		  	</genreform>
		</xsl:if>
		<genreform>
			<xsl:call-template name="niveau_biblio"><xsl:with-param name="entree" select="normalize-space(bl)"/></xsl:call-template>
		</genreform>
		</physdesc>
   </xsl:template>
   
   
	<xsl:template name="auteurs">
		<xsl:param name="balise"/>
		<xsl:if test="f[@c='700'] or f[@c='701'] or f[@c='702'] or f[@c='710'] or f[@c='711'] or f[@c='712']"><!--Auteurs-->
			<xsl:choose>
				<xsl:when test="$balise='1'">
					<controlaccess>
						<xsl:call-template name="auteur"/>
					</controlaccess>
				</xsl:when>
				<xsl:otherwise>
					<origination>
						<xsl:call-template name="auteur"/>
					</origination>
				</xsl:otherwise>			
			</xsl:choose>
		</xsl:if>
	</xsl:template>

	
	<xsl:template name="auteur">
				<xsl:if test="f[@c='700']/s[@c='a']"><!--Auteur principal-->
					<persname>
						<xsl:if test="f[@c='700']/s[@c='4']">
							<xsl:attribute name="role">
								<xsl:call-template name="fonction_auteur">
										<xsl:with-param name="entree" select="normalize-space(f[@c='700']/s[@c='4'])"/>
								</xsl:call-template>
							</xsl:attribute>
						</xsl:if>
						<xsl:value-of select="normalize-space(f[@c='700']/s[@c='a'])"/>
						<xsl:if test="(f[@c='700']/s[@c='b'])">, <xsl:value-of select="normalize-space(f[@c='700']/s[@c='b'])"/></xsl:if>
					</persname>
				</xsl:if>
				
				<xsl:if test="f[@c='701']/s[@c='a']"><!--Autres auteurs--> 
					<xsl:for-each select="f[@c='701']"> 
						<persname>
							<xsl:if test="./s[@c='4']">
								<xsl:attribute name="role">
									<xsl:call-template name="fonction_auteur">
										<xsl:with-param name="entree" select="normalize-space(./s[@c='4'])"/>
									</xsl:call-template>
								</xsl:attribute>
							</xsl:if>
							<xsl:value-of select="normalize-space(./s[@c='a'])"/>
							<xsl:if test="(./s[@c='b'])">, <xsl:value-of select="normalize-space(./s[@c='b'])"/></xsl:if>
						</persname>
					</xsl:for-each>
				</xsl:if>
				
				<xsl:if test="f[@c='702']/s[@c='a']"> <!--Auteurs secondaires--> 
					<xsl:for-each select="f[@c='702']">
						<persname>
							<xsl:if test="./s[@c='4']">
								<xsl:attribute name="role">
									<xsl:call-template name="fonction_auteur">
										<xsl:with-param name="entree" select="normalize-space(./s[@c='4'])"/>
									</xsl:call-template>
								</xsl:attribute>
							</xsl:if>
							<xsl:value-of select="normalize-space(./s[@c='a'])"/>
							<xsl:if test="(./s[@c='b'])">, <xsl:value-of select="normalize-space(./s[@c='b'])"/></xsl:if>
						</persname>
					</xsl:for-each>
				</xsl:if>
				
				<xsl:if test="f[@c='710']/s[@c='a']"><!--Auteur principal-->
					<corpname>
						<xsl:if test="f[@c='710']/s[@c='4']">
							<xsl:attribute name="role">
								<xsl:call-template name="fonction_auteur">
									<xsl:with-param name="entree" select="normalize-space(f[@c='710']/s[@c='4'])"/>
								</xsl:call-template>
							</xsl:attribute>
						</xsl:if>
						<xsl:value-of select="normalize-space(f[@c='710']/s[@c='a'])"/>
						<xsl:if test="(f[@c='710']/s[@c='b'])">, <xsl:value-of select="normalize-space(f[@c='710']/s[@c='b'])"/></xsl:if>
					</corpname>
				</xsl:if>
			
				<xsl:if test="f[@c='711']/s[@c='a']"><!--Autres auteurs--> 
					<xsl:for-each select="f[@c='711']">
						<corpname>
							<xsl:if test="./s[@c='4']">
								<xsl:attribute name="role">
									<xsl:call-template name="fonction_auteur">
										<xsl:with-param name="entree" select="normalize-space(./s[@c='4'])"/>
									</xsl:call-template>
								</xsl:attribute>
							</xsl:if>
							<xsl:value-of select="normalize-space(./s[@c='a'])"/>
							<xsl:if test="(./s[@c='b'])">, <xsl:value-of select="normalize-space(./s[@c='b'])"/></xsl:if>
						</corpname>
					</xsl:for-each>
				</xsl:if>
			
				<xsl:if test="f[@c='712']/s[@c='a']"><!--Auteurs secondaires--> 
					<xsl:for-each select="f[@c='712']">
						<corpname>
							<xsl:if test="./s[@c='4']">
								<xsl:attribute name="role">
									<xsl:call-template name="fonction_auteur">
										<xsl:with-param name="entree" select="normalize-space(./s[@c='4'])"/>
									</xsl:call-template>
								</xsl:attribute>
							</xsl:if>
							<xsl:value-of select="normalize-space(./s[@c='a'])"/>
							<xsl:if test="(./s[@c='b'])">, <xsl:value-of select="normalize-space(./s[@c='b'])"/></xsl:if>
						</corpname>
					</xsl:for-each>
				</xsl:if>
	</xsl:template>
	
	
	<xsl:template name="collation">
		<xsl:choose>
			<xsl:when test="bl!='a'">
				<xsl:if test="f[@c='215']/s[@c='a'] or f[@c='215']/s[@c='c'] or f[@c='215']/s[@c='d'] or f[@c='215']/s[@c='e']">
					<physdesc>
						<xsl:if test="f[@c='215']/s[@c='a']">
							<extent type="importance matérielle">
								<xsl:value-of select="normalize-space(f[@c='215']/s[@c='a'])"/>
							</extent>
						</xsl:if>
						<xsl:if test="f[@c='215']/s[@c='c']">
							<extent type="autres caractéristiques matérielles">
								<xsl:value-of select="normalize-space(f[@c='215']/s[@c='c'])"/>
							</extent>
						</xsl:if>
						<xsl:if test="f[@c='215']/s[@c='d']">
							<dimensions>
								<xsl:value-of select="normalize-space(f[@c='215']/s[@c='d'])"/>
							</dimensions>
						</xsl:if>
						<xsl:if test="f[@c='215']/s[@c='e']">
							<extent type="matériel d'accompagnement">
								<xsl:value-of select="normalize-space(f[@c='215']/s[@c='e'])"/>
							</extent>
						</xsl:if>						
					</physdesc>
				</xsl:if>
			</xsl:when>
			<xsl:otherwise>
				<xsl:if test="f[@c='215']/s[@c='a']">
					<physdesc>
						<extent type="pagination">
							<xsl:value-of select="normalize-space(f[@c='215']/s[@c='a'])"/>
						</extent>
					</physdesc>
				</xsl:if>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	
	<xsl:template name="lien">
		<xsl:if test="f[@c='856']/s[@c='u']">
		  	<dao>
				<extref>
					<xsl:attribute name="href">
						<xsl:value-of select="normalize-space(f[@c='856']/s[@c='u'])"/>
					</xsl:attribute>
					<xsl:variable name="desc" select="normalize-space(f[@c='856']/s[@c='q'])"/>
					<xsl:choose>
						<xsl:when test="$desc=''">
							<xsl:text>ressource en ligne</xsl:text>
						</xsl:when>
						<xsl:when test="substring($desc,1,3)='RSS' ">
							<xsl:text>flux RSS</xsl:text>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="$desc"/>
						</xsl:otherwise>	
					</xsl:choose>		
				</extref>
			</dao>
		</xsl:if>
	</xsl:template>


	<xsl:template name="langPub">
		<xsl:if test="f[@c='101']/s[@c='a']">
			<langmaterial>
				<xsl:for-each select="f[@c='101']/s[@c='a']">
						<language>
							<xsl:call-template name="code_langue">
								<xsl:with-param name="entree" select="normalize-space(.)"/>
							</xsl:call-template>
						</language>
				</xsl:for-each>
			</langmaterial>
		</xsl:if>
	</xsl:template>
	
	
	<xsl:template name="cote">
		<xsl:for-each select="f[@c='900'][s[@c='n']='cote']">
			<unitid type="cote">
				<xsl:value-of select="normalize-space(s[@c='a'])"/>
			</unitid>
		</xsl:for-each>
	</xsl:template>
	
	
	<xsl:template name="numNotice">
		<xsl:for-each select="f[@c='900'][s[@c='n']='num_notice']">
			<unitid type="num_notice">
				<xsl:value-of select="normalize-space(s[@c='a'])"/>
			</unitid>
		</xsl:for-each>
	</xsl:template>
	
	
	<xsl:template name="dates">
		<xsl:if test="f[@c='210']/s[@c='d']">
			<unitdate label="date d'édition">
				<xsl:value-of select="normalize-space(f[@c='210']/s[@c='d'])"/>
			</unitdate>
		</xsl:if>
		<xsl:if test="f[@c='900'][s[@c='n']='debut_periode'] or f[@c='900'][s[@c='n']='fin_periode']">
			<unitdate label="periode">
				<xsl:if test="f[@c='900'][s[@c='n']='debut_periode']"> <!-- si date de début seule -->
					<xsl:value-of select="normalize-space(f[@c='900'][s[@c='n']='debut_periode']/s[@c='a'])"/>
				</xsl:if>
				<xsl:if test="f[@c='900'][s[@c='n']='debut_periode'] and f[@c='900'][s[@c='n']='fin_periode']"> <!-- si 2 dates -->
					<xsl:text> - </xsl:text>
				</xsl:if>
				<xsl:if test="f[@c='900'][s[@c='n']='fin_periode']"> <!-- si date de fin seule -->
					<xsl:value-of select="normalize-space(f[@c='900'][s[@c='n']='fin_periode']/s[@c='a'])"/>
				</xsl:if>
			</unitdate>
		</xsl:if>
	</xsl:template>
	
	
	<xsl:template name="controlAccess">
		<xsl:if test="f[@c='606']/s[@c='a']">
			<controlaccess>
				<xsl:for-each select="f[@c='606']/s[@c='a']">
					<subject>	
						<xsl:value-of select="normalize-space(.)"/>
					</subject>
				</xsl:for-each>
			</controlaccess>
		</xsl:if>
	</xsl:template>
	
	
	<xsl:template name="notes">
			<xsl:if test="f[@c='300']/s[@c='a']">
				<odd type="notes_generales"><head>Notes générales</head>
					<xsl:for-each select="f[@c='300']/s[@c='a']">
						<p>	
							<xsl:value-of select="normalize-space(.)"/>
						</p>
					</xsl:for-each>
				</odd>
			</xsl:if>
			<xsl:if test="f[@c='327']/s[@c='a']">
				<scopecontent><head>Notes de contenu</head>	
					<xsl:for-each select="f[@c='327']/s[@c='a']">
						<p>
							<xsl:value-of select="normalize-space(.)"/>
						</p>
					</xsl:for-each>
				</scopecontent>	
			</xsl:if>
			
			<xsl:if test="f[@c='900'][s[@c='n']='notes_edition']">
				<odd type="notes_edition"> <head>Notes relatives à l'édition</head>
					<xsl:for-each select="f[@c='900'][s[@c='n']='notes_edition']">
						<p>
							<xsl:value-of select="normalize-space(s[@c='a'])"/>
						</p>
					</xsl:for-each>
				</odd>
			</xsl:if>
			
			<xsl:if test="f[@c='900'][s[@c='n']='notes_these']">
				<odd type="notes_these"> <head>Notes de thèse</head>
					<xsl:for-each select="f[@c='900'][s[@c='n']='notes_these']">
						<p>
							<xsl:value-of select="normalize-space(s[@c='a'])"/>
						</p>
					</xsl:for-each>
				</odd>
			</xsl:if>
			<xsl:if test="f[@c='010']/s[@c='a']">
				<odd type="isbn"><head>Numéro d'identification</head>
					<xsl:for-each select="f[@c='010']/s[@c='a']">
						<p>
							<xsl:value-of select="normalize-space(.)"/>
						</p>
					</xsl:for-each>
				</odd>
			</xsl:if>
			<xsl:if test="f[@c='900'][s[@c='n']='pays_publication']">
				<odd type="pays_publication"> <head>Pays de publication</head>
					<xsl:for-each select="f[@c='900'][s[@c='n']='pays_publication']">
						<p>
							<xsl:value-of select="normalize-space(s[@c='a'])"/>
						</p>
					</xsl:for-each>
				</odd>
			</xsl:if>
			<xsl:if test="f[@c='900'][s[@c='n']='statut_communication']">
				<odd type="statut_communication"> <head>Statut de communication</head>
					<xsl:for-each select="f[@c='900'][s[@c='n']='statut_communication']">
						<p>
							<xsl:value-of select="normalize-space(s[@c='a'])"/>
						</p>
					</xsl:for-each>
				</odd>
			</xsl:if>
			<xsl:if test="f[@c='900'][s[@c='n']='statut_reproduction']">
				<odd type="statut_reproduction"> <head>Statut de reproduction</head>
					<xsl:for-each select="f[@c='900'][s[@c='n']='statut_reproduction']">
						<p>
							<xsl:value-of select="normalize-space(s[@c='a'])"/>
						</p>
					</xsl:for-each>
				</odd>
			</xsl:if>
			<xsl:if test="f[@c='900'][s[@c='n']='etat_materiel']">
				<odd type="etat_materiel"><head>Etat matériel</head>
					<xsl:for-each select="f[@c='900'][s[@c='n']='etat_materiel']">
						<p>
							<xsl:value-of select="normalize-space(s[@c='a'])"/>
						</p>
					</xsl:for-each>
				</odd>
			</xsl:if>
			<xsl:if test="f[@c='900'][s[@c='n']='info_editeurs']">
				<odd type="info_editeurs"> <head>Informations éditeurs</head>
					<xsl:for-each select="f[@c='900'][s[@c='n']='info_editeurs']">
						<p>
							<xsl:value-of select="normalize-space(s[@c='a'])"/>
						</p>
					</xsl:for-each>
				</odd>
			</xsl:if>
	</xsl:template>

	
	<xsl:template name="edition">
		<xsl:if test="f[@c='225'] or f[@c='210'] or f[@c='205']/s[@c='a']">
			<bibliography>
					<bibref>
						<xsl:if test="f[@c='210']">
							<xsl:for-each select="f[@c='210']">
								<imprint>
									<xsl:if test="./s[@c='c']">
										<publisher>
											<xsl:value-of select="normalize-space(./s[@c='c'])"/>
										</publisher>
									</xsl:if>
									<xsl:if test="./s[@c='a']">
										<geogname>
											<xsl:value-of select="normalize-space(./s[@c='a'])"/>
										</geogname>
									</xsl:if>
								</imprint>
							</xsl:for-each>
							<xsl:if test="./s[@c='d']">
								<imprint>
									<date>
										<xsl:value-of select="normalize-space(./s[@c='d'])"/>
									</date>
								 </imprint>
							</xsl:if>
						</xsl:if>
						<xsl:if test="f[@c='225']/s[@c='a']">
							<bibseries>
								<title type="collection">
									<xsl:value-of select="normalize-space(f[@c='225']/s[@c='a'])"/>
								</title>
								<xsl:if test="f[@c='225']/s[@c='i']">
									<title type="sous-collection">
										<xsl:value-of select="normalize-space(f[@c='225']/s[@c='i'])"/>
									</title>							
								</xsl:if>
								<xsl:if test="f[@c='225']/s[@c='v']">
									<num type="numéro dans la collection">
										<xsl:value-of select="normalize-space(f[@c='225']/s[@c='v'])"/>
									</num>
								</xsl:if>
								<xsl:if test="f[@c='225']/s[@c='x']">
									<num type='issn'><xsl:value-of select="normalize-space(f[@c='225']/s[@c='x'])"/></num>
								</xsl:if>
							</bibseries>
						</xsl:if>
						<xsl:if test="f[@c='205']/s[@c='a']">
							<edition>
								<xsl:value-of select="normalize-space(f[@c='205']/s[@c='a'])"/>
							</edition>
						</xsl:if>
					</bibref>
			</bibliography>
		</xsl:if>
	</xsl:template>
	
	
	<xsl:template name="resume">
		<xsl:if test="f[@c='330']/s[@c='a']">
			<abstract>	
				<xsl:value-of select="normalize-space(f[@c='330']/s[@c='a'])"/>
			</abstract>
		</xsl:if>
	</xsl:template>
	
	
	<!-- ===================================== FONCTIONS ======================================== -->
	
	<!--Pour lier le code au type de notice-->
	<xsl:template name="type_doc">
		<xsl:param name="entree"/>
		<xsl:variable name="noeud" select="document('../../includes/marc_tables/fr_FR/doctype.xml')"/>
		<xsl:value-of select="$noeud/XMLlist/entry[@code=$entree]"></xsl:value-of>
	</xsl:template>
	
	<!--Pour lier le code au support de notice-->
	<xsl:template name="niveau_biblio">
		<xsl:param name="entree"/>
		<xsl:variable name="noeud" select="document('../../includes/marc_tables/fr_FR/recordtype.xml')"/>
		<xsl:value-of select="$noeud/XMLlist/entry[@code=$entree]"></xsl:value-of>
	</xsl:template>
	
	<!--Pour lier le code à la fonction de l'auteur-->
	<xsl:template name="fonction_auteur">
		<xsl:param name="entree"/>
		<xsl:variable name="noeud" select="document('../../includes/marc_tables/fr_FR/function.xml')"/>
		<xsl:value-of select="$noeud/XMLlist/entry[@code=$entree]"></xsl:value-of>
	</xsl:template>
	
	<!--Pour lier le code à la langue-->
	<xsl:template name="code_langue">
		<xsl:param name="entree"/>
		<xsl:variable name="noeud" select="document('../../includes/marc_tables/fr_FR/lang.xml')"/>
		<xsl:value-of select="$noeud/XMLlist/entry[@code=$entree]"></xsl:value-of>
	</xsl:template>
	
</xsl:stylesheet> 
