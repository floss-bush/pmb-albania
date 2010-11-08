<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>
<xsl:stylesheet version = '1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
<xsl:output method="xml" indent='yes' encoding="ISO-8859-1" />
	
<!-- Attention le fichier ../../../../includes/marc_tables/fr_FR/doctype.xml est inséré -->	
<!-- Attention le fichier ../../../../includes/marc_tables/fr_FR/recordtype.xml est inséré -->	
<!-- Attention le fichier ../../../../includes/marc_tables/fr_FR/function.xml est inséré -->	
	<xsl:template match="/unimarc">
		<ead>
			<eadheader>
				<xsl:call-template name="metadonnees"/>
			 </eadheader>
			 <archdesc level="fonds">
				 <did>
					 <unittitle>Fonds de documentation</unittitle>
				 </did>
				 <xsl:apply-templates select="notice"/>
			</archdesc>	 
		</ead>
	</xsl:template>
	
	<xsl:template match="/unimarc/notice">
			 <dsc>
				 <c level="item">
		 			<did>
						<xsl:call-template name="titre"/>
						<xsl:call-template name="genreForm"/>
						<xsl:call-template name="langPub"/>															 
						<xsl:call-template name="collation"/>
						<xsl:call-template name="resume"/>
						<xsl:call-template name="dates"/>
						<xsl:call-template name="test_auteur">
							<xsl:with-param name="balise">2</xsl:with-param>
						</xsl:call-template>	 
					</did>
					 <xsl:call-template name="note"/><!-- Prix, Materiel d'accompagnement, isbn -->
					 <xsl:call-template name="editeur"/>
					 <xsl:call-template name="test_auteur">
						<xsl:with-param name="balise">1</xsl:with-param>
					</xsl:call-template>
					 <xsl:call-template name="controlAccess"/>
				</c>
		  </dsc>
   </xsl:template>
	
	
   <xsl:template name="metadonnees">
      <eadid countrycode="FR">ID<xsl:value-of select="generate-id()"/>
      </eadid>
      <filedesc>
         <titlestmt>
            <titleproper>Inventaire en EAD</titleproper>
            <author></author>
         </titlestmt>
      </filedesc>
      <profiledesc>
         <creation></creation>
         <langusage>Document rédigé en <language langcode="fre">français</language>
         </langusage>
      </profiledesc>
   </xsl:template>


	
	<xsl:template name="genreForm">
      <physdesc>
		  <genreform> type de document:  <xsl:call-template name="code_notice"><xsl:with-param name="entree" select="normalize-space(dt)"/></xsl:call-template>
						  support de document: <xsl:call-template name="support_notice"><xsl:with-param name="entree" select="normalize-space(bl)"/></xsl:call-template>
		  </genreform>
		  <xsl:if test="f[@c='856']/s[@c='u'] or f[@c='856']/s[@c='q']">
			 <extref>
			 <xsl:choose>
				<xsl:when test="f[@c='856']/s[@c='u']">
					<xsl:attribute name="href">
						<xsl:value-of select="normalize-space(f[@c='856']/s[@c='u'])"/>
					</xsl:attribute>
				</xsl:when>
				<xsl:otherwise>
					<xsl:attribute name="href">adresse_inconnue</xsl:attribute>	
				</xsl:otherwise>		
			</xsl:choose> 
			<xsl:value-of select="normalize-space(f[@c='856']/s[@c='q'])"/>
			</extref>
		</xsl:if> 
	  </physdesc>
   </xsl:template>
	
	<xsl:template name="titre">
		<xsl:if test="f[@c='200']/s[@c='a']">
			<unittitle>
				<xsl:value-of select="normalize-space(f[@c='200']/s[@c='a'])"/>
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
		<xsl:if test="f[@c='461']/s[@c='t']">
			<unittitle type="titre_de_serie">
				<xsl:value-of select="normalize-space(f[@c='461']/s[@c='t'])"/>
			</unittitle>
		</xsl:if>
		<xsl:if test="f[@c='461']/s[@c='v']">
			<unittitle type="numero_de_serie">
				<xsl:value-of select="normalize-space(f[@c='461']/s[@c='v'])"/>
			</unittitle>
		</xsl:if>
		<xsl:if test="f[@c='503']/s[@c='a']">
			<unittitle type="numero_de_forme">
				<xsl:value-of select="normalize-space(f[@c='461']/s[@c='v'])"/>
			</unittitle>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="test_auteur">
		<xsl:param name="balise"/>
		<xsl:if test="f[@c='700'] or f[@c='701'] or f[@c='702'] or f[@c='710'] or f[@c='711'] or f[@c='712']"><!--Pour voir s'il y a un auteur-->
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
				<xsl:if test="f[@c='700']/s[@c='a']"><!--Pour l'auteur principal-->
						<persname>
							<xsl:choose>
								<xsl:when test="f[@c='700']/s[@c='4']">
									<xsl:attribute name="role">
										<xsl:call-template name="code_auteur">
												<xsl:with-param name="entree" select="normalize-space(f[@c='700']/s[@c='4'])"/>
										</xsl:call-template>
									</xsl:attribute>
								</xsl:when>
								<xsl:otherwise>
									<xsl:attribute name="role">auteur</xsl:attribute>
								</xsl:otherwise>			
							</xsl:choose>
							<xsl:value-of select="normalize-space(f[@c='700']/s[@c='a'])"/>
							<xsl:if test="(f[@c='700']/s[@c='b'])">, <xsl:value-of select="normalize-space(f[@c='700']/s[@c='b'])"/></xsl:if>
						</persname>
				</xsl:if>
				
				<xsl:if test="f[@c='701']/s[@c='a']">
					<xsl:for-each select="f[@c='701']"> <!--Pour parcourir tout les autres auteurs--> 
								<persname>
									<xsl:choose>
										<xsl:when test="./s[@c='4']">
											<xsl:attribute name="role">
											<xsl:call-template name="code_auteur">
												<xsl:with-param name="entree" select="normalize-space(./s[@c='4'])"/>
											</xsl:call-template>
											</xsl:attribute>
										</xsl:when>
										<xsl:otherwise>
											<xsl:attribute name="role">auteur</xsl:attribute>
										</xsl:otherwise>			
									</xsl:choose>
									<xsl:value-of select="normalize-space(./s[@c='a'])"/>
									<xsl:if test="(./s[@c='b'])">, <xsl:value-of select="normalize-space(./s[@c='b'])"/></xsl:if>
								</persname>
					</xsl:for-each>
				</xsl:if>
				
				<xsl:if test="f[@c='702']/s[@c='a']">
					<xsl:for-each select="f[@c='702']">
							<persname role="auteur">
								<xsl:choose>
										<xsl:when test="./s[@c='4']">
											<xsl:attribute name="role">
											<xsl:call-template name="code_auteur">
												<xsl:with-param name="entree" select="normalize-space(./s[@c='4'])"/>
											</xsl:call-template>
											</xsl:attribute>
										</xsl:when>
										<xsl:otherwise>
											<xsl:attribute name="role">auteur</xsl:attribute>
										</xsl:otherwise>			
									</xsl:choose>
								<xsl:value-of select="normalize-space(./s[@c='a'])"/>
								<xsl:if test="(./s[@c='b'])">, <xsl:value-of select="normalize-space(./s[@c='b'])"/></xsl:if>
							</persname>
					</xsl:for-each>
				</xsl:if>
				
				<xsl:if test="f[@c='710']/s[@c='a']">
						<corpname role="auteur">
							<xsl:choose>
								<xsl:when test="f[@c='710']/s[@c='4']">
									<xsl:attribute name="role">
										<xsl:call-template name="code_auteur">
											<xsl:with-param name="entree" select="normalize-space(f[@c='710']/s[@c='4'])"/>
										</xsl:call-template>
									</xsl:attribute>
								</xsl:when>
								<xsl:otherwise>
									<xsl:attribute name="role">auteur</xsl:attribute>
								</xsl:otherwise>			
							</xsl:choose>
								<xsl:value-of select="normalize-space(f[@c='710']/s[@c='a'])"/>
								<xsl:if test="(f[@c='710']/s[@c='b'])">, <xsl:value-of select="normalize-space(f[@c='710']/s[@c='b'])"/></xsl:if>
						</corpname>
				</xsl:if>
			
				<xsl:if test="f[@c='711']/s[@c='a']">
					<xsl:for-each select="f[@c='711']">
						<corpname role="auteur">
							<xsl:choose>
										<xsl:when test="./s[@c='4']">
											<xsl:attribute name="role">
											<xsl:call-template name="code_auteur">
												<xsl:with-param name="entree" select="normalize-space(./s[@c='4'])"/>
											</xsl:call-template>
											</xsl:attribute>
										</xsl:when>
										<xsl:otherwise>
											<xsl:attribute name="role">auteur</xsl:attribute>
										</xsl:otherwise>			
							</xsl:choose>
								<xsl:value-of select="normalize-space(./s[@c='a'])"/>
								<xsl:if test="(./s[@c='b'])">, <xsl:value-of select="normalize-space(./s[@c='b'])"/></xsl:if>
						</corpname>
					</xsl:for-each>
				</xsl:if>
			
				<xsl:if test="f[@c='712']/s[@c='a']">
					<xsl:for-each select="f[@c='712']">
						<corpname role="auteur">
							<xsl:choose>
										<xsl:when test="./s[@c='4']">
											<xsl:attribute name="role">
											<xsl:call-template name="code_auteur">
												<xsl:with-param name="entree" select="normalize-space(./s[@c='4'])"/>
											</xsl:call-template>
											</xsl:attribute>
										</xsl:when>
										<xsl:otherwise>
											<xsl:attribute name="role">auteur</xsl:attribute>
										</xsl:otherwise>			
									</xsl:choose>
								<xsl:value-of select="normalize-space(./s[@c='a'])"/>
								<xsl:if test="(./s[@c='b'])">, <xsl:value-of select="normalize-space(./s[@c='b'])"/></xsl:if>
							</corpname>
					</xsl:for-each>
				</xsl:if>
	</xsl:template>
	
	<xsl:template name="dates">
		<xsl:if test="f[@c='210']/s[@c='d']">
			<unitdate label="date_edition">
				<xsl:value-of select="normalize-space(f[@c='210']/s[@c='d'])"/>
			</unitdate>
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
	
	<xsl:template name="note">
			<xsl:if test="f[@c='300']/s[@c='a']">
				<odd type="note general"><head> Note General</head>
					<xsl:for-each select="f[@c='300']/s[@c='a']">
						<p>	
							<xsl:value-of select="normalize-space(.)"/>
						</p>
					</xsl:for-each>
				</odd>
			</xsl:if>
			<xsl:if test="f[@c='327']/s[@c='a']">
				<scopecontent>	
					<xsl:for-each select="f[@c='327']/s[@c='a']">
						<p>
							<xsl:value-of select="normalize-space(.)"/>
						</p>
					</xsl:for-each>
				</scopecontent>	
			</xsl:if>
			<xsl:if test="f[@c='010']/s[@c='a']">
				<odd type="isbn"><head> Numéro identification</head>
					<xsl:for-each select="f[@c='010']/s[@c='a']">
						<p>
							<xsl:value-of select="normalize-space(.)"/>
						</p>
					</xsl:for-each>
				</odd>
			</xsl:if>
			<xsl:if test="f[@c='215']/s[@c='e']">
				<odd type="matériel d'accompagnement"> <head> Materiel d'Accompagnement</head>
					<xsl:for-each select="f[@c='215']/s[@c='e']">
						<p>
							<xsl:value-of select="normalize-space(.)"/>
						</p>
					</xsl:for-each>
				</odd>
			</xsl:if>
	</xsl:template>
	
	<xsl:template name="collation">
		<xsl:if test="f[@c='215']/s[@c='a'] or f[@c='215']/s[@c='c'] or f[@c='215']/s[@c='d']">
			<physdesc>
				<xsl:if test="f[@c='215']/s[@c='a']">
					<extent type="collation">
						<xsl:value-of select="normalize-space(f[@c='215']/s[@c='a'])"/>
					</extent>
				</xsl:if>
				<xsl:if test="f[@c='215']/s[@c='c']">
					<extent type="collation">
						<xsl:value-of select="normalize-space(f[@c='215']/s[@c='c'])"/>
					</extent>
				</xsl:if>
				<xsl:if test="f[@c='215']/s[@c='d']">
					<dimensions>
						<xsl:value-of select="normalize-space(f[@c='215']/s[@c='d'])"/>
					</dimensions>
				</xsl:if>
			</physdesc>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="editeur">
		<xsl:if test="f[@c='225'] or f[@c='210'] or f[@c='205']/s[@c='a']">
			<bibliography>
				<xsl:if test="normalize-space(f[@c='200']/s[@c='a'])">
					<head>
					<xsl:value-of select="normalize-space(f[@c='200']/s[@c='a'])"/>
					</head>
				</xsl:if>
					<bibref>
						<xsl:for-each select="f[@c='210']">
							<imprint>
								<xsl:if test="./s[@c='a']">
										<geogname>
											<xsl:value-of select="normalize-space(./s[@c='a'])"/>
										</geogname>
								</xsl:if>
								<xsl:if test="./s[@c='c']">
										<publisher>
											<xsl:value-of select="normalize-space(./s[@c='c'])"/>
										</publisher>
								</xsl:if>
								<xsl:if test="./s[@c='d']">
										<date>
											<xsl:value-of select="normalize-space(./s[@c='d'])"/>
										</date>
								</xsl:if>
							</imprint>
						</xsl:for-each>
						<!--<xsl:if test="f[@c='210']">
							<imprint>
								<xsl:if test="f[@c='210']/s[@c='a']">
									<xsl:for-each select="f[@c='210']/s[@c='a']">
										<geogname>
											<xsl:value-of select="normalize-space(.)"/>
										</geogname>
									</xsl:for-each>
								</xsl:if>
								<xsl:if test="f[@c='210']/s[@c='c']">
									<xsl:for-each select="f[@c='210']/s[@c='c']">
										<publisher>
											<xsl:value-of select="normalize-space(.)"/>
										</publisher>
									</xsl:for-each>
								</xsl:if>
								<xsl:if test="f[@c='210']/s[@c='d']">
									<xsl:for-each select="f[@c='210']/s[@c='d']">
										<date>
											<xsl:value-of select="normalize-space(.)"/>
										</date>
									</xsl:for-each>
								</xsl:if>
							</imprint>
						</xsl:if>-->
						<xsl:if test="f[@c='225']/s[@c='a'] or f[@c='225']/s[@c='v']">
							<bibseries>
								<xsl:if test="f[@c='225']/s[@c='x']">
									<xsl:attribute name="encodinganalog">
											<xsl:value-of select="normalize-space(f[@c='225']/s[@c='x'])"></xsl:value-of>
										</xsl:attribute>
								</xsl:if>
								<title>
									<xsl:value-of select="normalize-space(f[@c='225']/s[@c='a'])"/>
								</title>
								<xsl:if test="f[@c='225']/s[@c='v']">
									<num>
										<xsl:value-of select="normalize-space(f[@c='225']/s[@c='v'])"/>
									</num>
								</xsl:if>
							</bibseries>
						</xsl:if>
						<xsl:if test="f[@c='225']/s[@c='i']">
							<bibseries>
								<title>
									<xsl:value-of select="normalize-space(f[@c='225']/s[@c='i'])"/>
								</title>
							</bibseries>
						</xsl:if>
						<xsl:if test="f[@c='205']/s[@c='a']">
							<edition>
								<title>
									<xsl:value-of select="normalize-space(f[@c='205']/s[@c='a'])"/>
								</title>
							</edition>
						</xsl:if>
					</bibref>
			</bibliography>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="resume">
		<xsl:if test="f[@c='330']/s[@c='a']">
					<xsl:for-each select="f[@c='330']/s[@c='a']">
						<abstract>	
							<xsl:value-of select="normalize-space(.)"/>
						</abstract>
					</xsl:for-each>
			</xsl:if>
	</xsl:template>
	
	
	<!-- ===================================== FONCTION ======================================== -->
	
	<!--Pour lier le code au type de notice-->
	<xsl:template name="code_notice">
		<xsl:param name="entree"/>
		<xsl:variable name="noeud" select="document('../../includes/marc_tables/fr_FR/doctype.xml')"/>
		<!--<xsl:variable name="noeud" select="document('/home/mbertin/public_html/pmb/includes/marc_tables/fr_FR/doctype.xml')"/>-->
		<xsl:value-of select="$noeud/XMLlist/entry[@code=$entree]"></xsl:value-of>
	</xsl:template>
	
	<!--Pour lier le code au support de notice-->
	<xsl:template name="support_notice">
		<xsl:param name="entree"/>
		<!--<xsl:variable name="noeud" select="document('../../../../includes/marc_tables/fr_FR/recordtype.xml')"/>-->
		<xsl:variable name="noeud" select="document('../../includes/marc_tables/fr_FR/recordtype.xml')"/>
		<xsl:value-of select="$noeud/XMLlist/entry[@code=$entree]"></xsl:value-of>
	</xsl:template>
	
	<!--Pour lier le code à la fonction de l'auteur-->
	<xsl:template name="code_auteur">
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
