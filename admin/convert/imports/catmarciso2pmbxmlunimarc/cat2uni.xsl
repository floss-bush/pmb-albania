<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">
	
	<xsl:output method="xml" indent="yes" encoding="ISO-8859-1"/>
	
	<xsl:template match="/unimarc">
		<unimarc>
			<xsl:apply-templates select="notice"/>
		</unimarc>
	</xsl:template>
	
	<xsl:template match="notice">
		<notice>
			<rs>n</rs>
			<dt>
				<xsl:choose>
					<xsl:when test="dt='a'">a</xsl:when>
					<xsl:when test="dt='b'">b</xsl:when>
					<xsl:when test="dt='c'">c</xsl:when>
					<xsl:when test="dt='d'">d</xsl:when>
					<xsl:when test="dt='e'">e</xsl:when>
					<xsl:when test="dt='f'">f</xsl:when>
					<xsl:when test="dt='g'">g</xsl:when>
					<xsl:when test="dt='h'">g</xsl:when>
					<xsl:when test="dt='i'">i</xsl:when>
					<xsl:when test="dt='j'">j</xsl:when>
					<xsl:when test="dt='k'">k</xsl:when>
					<xsl:when test="dt='l'">l</xsl:when>
					<xsl:when test="dt='o'">m</xsl:when>
					<xsl:when test="dt='r'">r</xsl:when>
					<xsl:otherwise>a</xsl:otherwise>
				</xsl:choose>
			</dt>
			<bl>
				<xsl:choose>
					<xsl:when test="bl='b'">a</xsl:when>
					<xsl:otherwise><xsl:value-of select="bl"/></xsl:otherwise>
				</xsl:choose>
			</bl>
			<hl>
				<xsl:choose>
					<xsl:when test="bl='b'">2</xsl:when>
					<xsl:when test="bl='s'">1</xsl:when>
					<xsl:otherwise>0</xsl:otherwise>
				</xsl:choose>
			</hl>
			<el>*</el>
			<ru>*</ru>
			<xsl:call-template name="code"/>
			<xsl:call-template name="langues"/>
			<xsl:call-template name="titre"/>
			<xsl:call-template name="editeur"/>
			<xsl:call-template name="collation"/>
			<xsl:call-template name="collection"/>
			<xsl:call-template name="notes"/>
			<xsl:call-template name="cdu_music"/>
			<xsl:call-template name="auteur"/>
		</notice>
	</xsl:template>
	
	<!-- ISBN ou Music number -->
	<xsl:template name="code">
		<xsl:if test="f[@c='021'] or f[@c='028']">
			<f c='010'>
				<xsl:if test="f[@c='021']">
					<s c='a'>
						<xsl:value-of select="f[@c='021']/s[@c='a']"/>
					</s>
				</xsl:if>
				<xsl:if test="f[@c='028'] and not(f[@c='021'])">
					<s c='a'>
						<xsl:value-of select="f[@c='028']/s[@c='a']"/>
					</s>
				</xsl:if>
			</f>
		</xsl:if>
	</xsl:template>
	
	<!-- Découpage langue par groupe de 3 caractères -->
	<xsl:template name="decoupe_langue">
		<xsl:param name="langue"/>
		<xsl:param name="souschamp"/>
		
		<xsl:element name="s">
			<xsl:attribute name="c"><xsl:value-of select="$souschamp"/></xsl:attribute>
			<xsl:value-of select="substring($langue,1,3)"/>
		</xsl:element>
		<xsl:if test="string-length($langue)>3">
			<xsl:call-template name="decoupe_langue">
				<xsl:with-param name="langue" select="substring($langue,4)"/>
				<xsl:with-param name="souschamp" select="$souschamp"/>
			</xsl:call-template>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="langues">
		<xsl:if test="f[@c='041']">
			<f c="101">
				<xsl:if test="f[@c='041' and @ind='00']">
					<xsl:call-template name="decoupe_langue">
						<xsl:with-param name="langue" select="f[@c='041' and @ind='00']/s[@c='a']"/>
						<xsl:with-param name="souschamp">a</xsl:with-param>
					</xsl:call-template>
				</xsl:if>
				<xsl:if test="f[@c='041' and @ind='10']">
					<xsl:call-template name="decoupe_langue">
						<xsl:with-param name="langue" select="f[@c='041' and @ind='10']/s[@c='a']"/>
						<xsl:with-param name="souschamp">c</xsl:with-param>
					</xsl:call-template>
				</xsl:if>
			</f>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="titre">
		<f c='200'>
			<s c='a'><xsl:value-of select="f[@c='245']/s[@c='a']"/></s>
			<xsl:if test="f[@c='245']/s[@c='i']">
				<s c='a'><xsl:value-of select="f[@c='245']/s[@c='i']"/></s>
			</xsl:if>
			<xsl:if test="f[@c='245']/s[@c='j']">
				<s c='c'><xsl:value-of select="f[@c='245']/s[@c='j']"/></s>
			</xsl:if>
			<xsl:if test="f[@c='245']/s[@c='k']">
				<xsl:for-each select="f[@c='245']/s[@c='k']">
					<s c='d'><xsl:value-of select="."/></s>
				</xsl:for-each>
			</xsl:if>
			<xsl:if test="f[@c='245']/s[@c='b']">
				<xsl:for-each select="f[@c='245']/s[@c='b']">
					<s c='e'><xsl:value-of select="."/></s>
				</xsl:for-each>
			</xsl:if>
		</f>
	</xsl:template>
	
	<xsl:template name="editeur">
		<xsl:for-each select="f[@c='260']">
			<f c='210'>
				<xsl:if test="./s[@c='a']">
					<s c='a'><xsl:value-of select="./s[@c='a']"/></s>
				</xsl:if>
				<xsl:if test="./s[@c='b']">
					<s c='c'><xsl:value-of select="./s[@c='b']"/></s>
				</xsl:if>
				<xsl:if test="./s[@c='c']">
					<s c='d'><xsl:value-of select="./s[@c='c']"/></s>
				</xsl:if>
			</f>
		</xsl:for-each>
	</xsl:template>
	
	<xsl:template name="collation">
		<xsl:if test="f[@c='300']">
			<f c='215'>
				<xsl:if test="f[@c='300']/s[@c='a']">
					<s c='a'><xsl:value-of select="f[@c='300']/s[@c='a']"/></s>
				</xsl:if>
				<xsl:if test="f[@c='300']/s[@c='b'] or f[@c='300']/s[@c='i']">
					<s c='c'>
						<xsl:if test="f[@c='300']/s[@c='b']">
							<xsl:value-of select="f[@c='300']/s[@c='b']"/>
						</xsl:if>
						<xsl:if test="f[@c='300']/s[@c='b'] and f[@c='300']/s[@c='i']">
							<xsl:text>, </xsl:text>
						</xsl:if>
						<xsl:if test="f[@c='300']/s[@c='i']">
							<xsl:value-of select="f[@c='300']/s[@c='i']"/>
						</xsl:if>
					</s>
				</xsl:if>
				<xsl:if test="f[@c='300']/s[@c='c']">
					<s c='d'><xsl:value-of select="f[@c='300']/s[@c='c']"/></s>
				</xsl:if>
				<xsl:if test="f[@c='300']/s[@c='l']">
					<s c='e'><xsl:value-of select="f[@c='300']/s[@c='l']"/></s>
				</xsl:if>
			</f>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="fonctions_auteur">
		<xsl:param name="fonction_catmarc"/>
		<xsl:choose>
			<xsl:when test="$fonction_catmarc='001'">070</xsl:when>
			<xsl:when test="$fonction_catmarc='002'">080</xsl:when>
			<xsl:otherwise>070</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template name="collection">
		<xsl:if test="f[@c='440'] or f[@c='490']">
			<xsl:if test="f[@c='440']">
				<f c='225'>
					<xsl:if test="f[@c='440']/s[@c='a']">
						<s c='a'><xsl:value-of select="f[@c='440']/s[@c='a']"/></s>
					</xsl:if>
					<xsl:if test="f[@c='440']/s[@c='l']">
						<s c='i'><xsl:value-of select="f[@c='440']/s[@c='l']"/></s>
					</xsl:if>
					<xsl:if test="f[@c='440']/s[@c='v']">
						<s c='v'><xsl:value-of select="f[@c='440']/s[@c='v']"/></s>
					</xsl:if>
				</f>
			</xsl:if>
			<xsl:if test="f[@c='490']">
				<f c='225'>
					<xsl:if test="f[@c='490']/s[@c='a']">
						<s c='a'><xsl:value-of select="f[@c='490']/s[@c='a']"/></s>
					</xsl:if>
					<xsl:if test="f[@c='490']/s[@c='l']">
						<s c='i'><xsl:value-of select="f[@c='490']/s[@c='l']"/></s>
					</xsl:if>
					<xsl:if test="f[@c='490']/s[@c='v']">
						<s c='v'><xsl:value-of select="f[@c='490']/s[@c='v']"/></s>
					</xsl:if>
				</f>
			</xsl:if>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="notes">
		<xsl:if test="f[@c='500']">
			<f c='300'>
				<s c='a'><xsl:value-of select="f[@c='500']/s[@c='a']"/></s>
			</f>
		</xsl:if>
		<xsl:if test="f[@c='505']">
			<f c='327'>
				<s c='a'><xsl:value-of select="f[@c='505']/s[@c='a']"/></s>
			</f>
		</xsl:if>
		<xsl:if test="f[@c='513']">
			<f c='330'>
				<s c='a'><xsl:value-of select="f[@c='513']/s[@c='a']"/></s>
			</f>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="cdu_music">
		<xsl:if test="f[@c='080']">
			<xsl:for-each select="f[@c='080']/s[@c='a']">
				<f c='676'>
					<s c='a'><xsl:value-of select="."/></s>
				</f>
			</xsl:for-each>
		</xsl:if>
		<xsl:if test="f[@c='089']">
			<xsl:for-each select="f[@c='089']/s[@c='a']">
				<f c='686'>
					<s c='a'><xsl:value-of select="."/></s>
				</f>
			</xsl:for-each>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="auteur">
		<!-- Auteur principal personne -->
		<xsl:if test="f[@c='100']">
			<f c='700'>
				<xsl:if test="f[@c='100']/s[@c='a']">
					<s c='a'><xsl:value-of select="f[@c='100']/s[@c='a']"/></s>
				</xsl:if>
				<xsl:if test="f[@c='100']/s[@c='h']">
					<s c='b'><xsl:value-of select="f[@c='100']/s[@c='h']"/></s>
				</xsl:if>
				<xsl:if test="f[@c='100']/s[@c='4']">
					<s c='4'>
						<xsl:call-template name="fonctions_auteur">
							<xsl:with-param name="fonction_catmarc" select="f[@c='100']/s[@c='4']"/>
						</xsl:call-template>
					</s>
				</xsl:if>
			</f>
		</xsl:if>
		<!-- Auteur principal collectif -->
		<xsl:if test="f[@c='110']">
			<f c='710'>
				<xsl:if test="f[@c='110']/s[@c='a']">
					<s c='a'><xsl:value-of select="f[@c='110']/s[@c='a']"/></s>
				</xsl:if>
				<xsl:if test="f[@c='110']/s[@c='h']">
					<s c='b'><xsl:value-of select="f[@c='110']/s[@c='h']"/></s>
				</xsl:if>
				<xsl:if test="f[@c='110']/s[@c='4']">
					<s c='4'>
						<xsl:call-template name="fonctions_auteur">
							<xsl:with-param name="fonction_catmarc" select="f[@c='110']/s[@c='4']"/>
						</xsl:call-template>
					</s>
				</xsl:if>
			</f>
		</xsl:if>
		<!-- Auteur principal congrès -->
		<xsl:if test="f[@c='111']">
			<f c='710'>
				<xsl:if test="f[@c='111']/s[@c='a']">
					<s c='a'><xsl:value-of select="f[@c='110']/s[@c='a']"/></s>
				</xsl:if>
				<xsl:if test="f[@c='111']/s[@c='h']">
					<s c='b'><xsl:value-of select="f[@c='110']/s[@c='h']"/></s>
				</xsl:if>
				<s c='4'>900</s>
			</f>
		</xsl:if>
		<!-- Auteur secondaire personel -->
		<xsl:for-each select="f[@c='700']">
			<f c='702'>
				<xsl:if test="./s[@c='a']">
					<s c='a'><xsl:value-of select="./s[@c='a']"/></s>
				</xsl:if>
				<xsl:if test="./s[@c='h']">
					<s c='b'><xsl:value-of select="./s[@c='h']"/></s>
				</xsl:if>
				<xsl:if test="./s[@c='4']">
					<s c='4'>
						<xsl:call-template name="fonctions_auteur">
							<xsl:with-param name="fonction_catmarc" select="./s[@c='4']"/>
						</xsl:call-template>
					</s>
				</xsl:if>
			</f>
		</xsl:for-each>
		<!-- Auteur secondaire collectif -->
		<xsl:for-each select="f[@c='710']">
			<f c='712'>
				<xsl:if test="./s[@c='a']">
					<s c='a'><xsl:value-of select="./s[@c='a']"/></s>
				</xsl:if>
				<xsl:if test="./s[@c='h']">
					<s c='b'><xsl:value-of select="./s[@c='h']"/></s>
				</xsl:if>
				<xsl:if test="./s[@c='4']">
					<s c='4'>
						<xsl:call-template name="fonctions_auteur">
							<xsl:with-param name="fonction_catmarc" select="./s[@c='4']"/>
						</xsl:call-template>
					</s>
				</xsl:if>
			</f>
		</xsl:for-each>
		<!-- Auteur secondaire congrès -->
		<xsl:for-each select="f[@c='711']">
			<f c='712'>
				<xsl:if test="./s[@c='a']">
					<s c='a'><xsl:value-of select="./s[@c='a']"/></s>
				</xsl:if>
				<xsl:if test="./s[@c='h']">
					<s c='b'><xsl:value-of select="./s[@c='h']"/></s>
				</xsl:if>
				<!--  Fonction congrès -->
				<s c='4'>900</s>
			</f>
		</xsl:for-each>
	</xsl:template>
</xsl:stylesheet>