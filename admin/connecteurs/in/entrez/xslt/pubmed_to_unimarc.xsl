<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version = '1.0' 
	xmlns:xsl='http://www.w3.org/1999/XSL/Transform'
	xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
	xmlns:eFetchResult="http://www.ncbi.nlm.nih.gov/soap/eutils/efetch_pubmed">
	
<xsl:output method="xml" indent='yes'/>
	
<xsl:template match="/">
	<xsl:if test="/SOAP-ENV:Envelope/SOAP-ENV:Body/eFetchResult:eFetchResult/eFetchResult:PubmedArticleSet">
		<xsl:element name="unimarc">
			<xsl:apply-templates/>
		</xsl:element>
	</xsl:if>
</xsl:template>


<xsl:template match="eFetchResult:PubmedArticleSet/eFetchResult:PubmedArticle">
	<notice>
		<xsl:element name="rs">*</xsl:element>
		<xsl:element name="ru">*</xsl:element>
		<xsl:element name="el">1</xsl:element>
		<xsl:element name="bl">a</xsl:element>
		<xsl:element name="hl">2</xsl:element><!-- niveau hierarchique:  -->
		<xsl:element name="dt"><xsl:value-of select="./dt"/></xsl:element>
		
		<xsl:if test="eFetchResult:MedlineCitation">
			<xsl:for-each select="eFetchResult:MedlineCitation"> 
				<xsl:call-template name="parse_medlinecitation"/>
				<xsl:call-template name="url"/>
				
				<xsl:for-each select="eFetchResult:Article[@PubModel='Print']"> 
					<xsl:call-template name="title"/>
					<xsl:call-template name="presentation"/>
					<xsl:call-template name="autorite"/>
					<xsl:call-template name="langue"/>
					<xsl:call-template name="journal_dateparution"/>
					<xsl:call-template name="journal_title"/>
					<xsl:call-template name="bulletin"/>
					<xsl:call-template name="perio"/>
					<xsl:call-template name="typedoc"/>
				</xsl:for-each>
				
				<xsl:for-each select="eFetchResult:Article[@PubModel='Print-Electronic']"> 
					<xsl:call-template name="title"/>
					<xsl:call-template name="presentation"/>
					<xsl:call-template name="autorite"/>
					<xsl:call-template name="langue"/>
					<xsl:call-template name="journal_dateparution"/>
					<xsl:call-template name="journal_title"/>
					<xsl:call-template name="bulletin"/>
					<xsl:call-template name="perio"/>
					<xsl:call-template name="typedoc"/>
				</xsl:for-each>
				
				<xsl:for-each select="eFetchResult:Article[@PubModel='Electronic']"> 
					<xsl:call-template name="title"/>
					<xsl:call-template name="presentation"/>
					<xsl:call-template name="autorite"/>
					<xsl:call-template name="langue"/>
					<xsl:call-template name="journal_dateparution"/>
					<xsl:call-template name="journal_title"/>
					<xsl:call-template name="article_affiliation"/>
					<xsl:call-template name="bulletin"/>
					<xsl:call-template name="perio"/>
					<xsl:call-template name="typedoc"/>
				</xsl:for-each>
			</xsl:for-each>
		</xsl:if>
		
		<xsl:if test="eFetchResult:PubmedData/eFetchResult:ArticleIdList/eFetchResult:ArticleId[@IdType='doi']">
			<xsl:element name="f">
			<xsl:attribute name="c">014</xsl:attribute>	
				<s c="a"><xsl:value-of select="eFetchResult:PubmedData/eFetchResult:ArticleIdList/eFetchResult:ArticleId[@IdType='doi']"/></s>
				<s c="b"><xsl:text>DOI</xsl:text></s>	
			</xsl:element>
		</xsl:if>
	</notice>
</xsl:template>

<xsl:template match="eFetchResult:PubmedBookArticle">
	<xsl:element name="notice">
		<xsl:element name="rs">*</xsl:element>
		<xsl:element name="ru">*</xsl:element>
		<xsl:element name="el">1</xsl:element>
		<xsl:element name="bl">m</xsl:element>
		<xsl:element name="hl">0</xsl:element><!-- niveau hierarchique:  -->
		<xsl:element name="dt">a</xsl:element>
		
		<xsl:if test="eFetchResult:BookDocument/eFetchResult:PMID">
			<f c="001"><xsl:value-of select="eFetchResult:BookDocument/eFetchResult:PMID"/></f>
			
			<xsl:element name="f">
				<xsl:attribute name="c">014</xsl:attribute>			
				<s c="a"><xsl:value-of select="eFetchResult:BookDocument/eFetchResult:PMID"/></s>
				<s c="b"><xsl:text>PMID</xsl:text></s>
			</xsl:element>
		</xsl:if>		
		
		<xsl:call-template name="isbn"/>
		<xsl:call-template name="title"/>
		<xsl:call-template name="collection"/>	
		<xsl:call-template name="publishers"/>
		<xsl:call-template name="parution_date"/>
		<xsl:call-template name="book_authors"/>
		<xsl:call-template name="resume"/>
		<xsl:call-template name="link"/>
		<xsl:call-template name="sections"/>
		
	</xsl:element>
</xsl:template>

<xsl:template name="isbn">
	<xsl:if test="eFetchResult:BookDocument/eFetchResult:Book/eFetchResult:Isbn">
		<f c="010">
			<s c="a"><xsl:value-of select="eFetchResult:BookDocument/eFetchResult:Book/eFetchResult:Isbn"/></s>
		</f>
	</xsl:if>	
</xsl:template>

	
<xsl:template name="parse_medlinecitation">
	<xsl:call-template name="record_identifier"/>
</xsl:template>
	
<xsl:template name="record_identifier">
	<xsl:if test="eFetchResult:PMID">
		<f c="001"><xsl:value-of select="eFetchResult:PMID"/></f>
		
		<xsl:element name="f">
			<xsl:attribute name="c">014</xsl:attribute>			
			<s c="a"><xsl:value-of select="eFetchResult:PMID"/></s>
			<s c="b"><xsl:text>PMID</xsl:text></s>
		</xsl:element>
	</xsl:if>	
</xsl:template>

<xsl:template name="url">
	<xsl:if test="eFetchResult:PMID">
		<xsl:element name="f">
			<xsl:attribute name="c">856</xsl:attribute>	
			<s c="u">
				<xsl:text>http://www.ncbi.nlm.nih.gov/pubmed/</xsl:text><xsl:value-of select="eFetchResult:PMID"/>
			</s>	
		</xsl:element>	
	</xsl:if>	
</xsl:template>
	
<xsl:template name="title">
	<xsl:element name="f">
		<xsl:attribute name="c">200</xsl:attribute>	
		<xsl:choose>
			<xsl:when test="eFetchResult:ArticleTitle">
				<xsl:if test="eFetchResult:ArticleTitle">
					<s c="a"><xsl:value-of select="eFetchResult:ArticleTitle"/></s>	
				</xsl:if>
			</xsl:when>
			<xsl:otherwise>
				<xsl:choose>
					<xsl:when test="eFetchResult:BookDocument/eFetchResult:ArticleTitle">
						<s c="a"><xsl:value-of select="eFetchResult:BookDocument/eFetchResult:ArticleTitle"/></s>
						<s c="i"><xsl:value-of select="eFetchResult:BookDocument/eFetchResult:Book/eFetchResult:BookTitle"/></s>	
					</xsl:when>
					<xsl:otherwise>
						<s c="a"><xsl:value-of select="eFetchResult:BookDocument/eFetchResult:Book/eFetchResult:BookTitle"/></s>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:otherwise>
		</xsl:choose>

	</xsl:element>	
</xsl:template>
	
<xsl:template name="presentation">
	<xsl:if test="eFetchResult:Abstract/eFetchResult:AbstractText">
		<xsl:for-each select="eFetchResult:Abstract/eFetchResult:AbstractText">
			<xsl:element name="f">
				<xsl:attribute name="c">330</xsl:attribute>	
				<s c="a"><xsl:value-of select="./@Label"/><xsl:text>: </xsl:text><xsl:value-of select="."/></s>
			</xsl:element>		
		</xsl:for-each>
	</xsl:if>
</xsl:template>

<xsl:template name="autorite">
	<xsl:if test="eFetchResult:Affiliation">
		<xsl:element name="f">
			<xsl:attribute name="c">
				<xsl:text>710</xsl:text>
			</xsl:attribute>
			<s c="a"><xsl:value-of select="substring-before(eFetchResult:Affiliation,', ')"/></s>
			<s c="e"><xsl:value-of select="substring-after(eFetchResult:Affiliation,', ')"/></s>
		</xsl:element>
	</xsl:if>	
	<xsl:for-each select="eFetchResult:AuthorList/eFetchResult:Author">
	<xsl:element name="f">
		<xsl:attribute name="c">
			<xsl:choose>
				<xsl:when test="position()=1 and not(../../eFetchResult:Affiliation)">
					<xsl:text>700</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:text>701</xsl:text>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:attribute>	
			<s c="a">
				<xsl:choose>
					<xsl:when test="eFetchResult:ForeName">
						<xsl:value-of select="concat(eFetchResult:LastName,' ',eFetchResult:ForeName)"/>
					</xsl:when>
					<xsl:when test="eFetchResult:FirstName">
						<xsl:value-of select="concat(eFetchResult:LastName,' ',eFetchResult:FirstName)"/>
					</xsl:when>
					<xsl:when test="eFetchResult:MiddleName and eFetchResult:FirstName">
						<xsl:value-of select="concat(eFetchResult:LastName,' ',eFetchResult:MiddleName,' ',eFetchResult:FirstName)"/>
					</xsl:when>
					<xsl:when test="eFetchResult:MiddleName and eFetchResult:ForeName">
						<xsl:value-of select="concat(eFetchResult:LastName,' ',eFetchResult:MiddleName,' ',eFetchResult:ForeName)"/>
					</xsl:when>
				</xsl:choose>
			</s>	
	</xsl:element>
	</xsl:for-each>	
</xsl:template>
	
<xsl:template name="langue">
	<xsl:if test="eFetchResult:Language">
		<xsl:element name="f">
			<xsl:attribute name="c">101</xsl:attribute>	
			<s c="a">
				<xsl:value-of select="eFetchResult:Language"/>
			</s>	
		</xsl:element>
	</xsl:if>
</xsl:template>
	
<xsl:template name="journal_dateparution">
	<xsl:if test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate">
		<xsl:element name="f">
			<xsl:attribute name="c">910</xsl:attribute>	
				<s c="a">
					<xsl:value-of select="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month"/><xsl:text> </xsl:text><xsl:value-of select="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Year"/>
				</s>		
		</xsl:element>
	</xsl:if>
</xsl:template>	

<xsl:template name="journal_title">
	<xsl:if test="eFetchResult:Journal/eFetchResult:Title">
		<xsl:element name="f">
			<xsl:attribute name="c">205</xsl:attribute>	
				<s c="a">
					<xsl:value-of select="eFetchResult:Journal/eFetchResult:Title"/>
				</s>		
		</xsl:element>
	</xsl:if>
</xsl:template>	
	
	
<xsl:template name="article_affiliation">
	<xsl:element name="f">
		<xsl:attribute name="c">210</xsl:attribute>	
		<xsl:if test="eFetchResult:Affiliation">
			<s c="a">
				<xsl:value-of select="eFetchResult:Affiliation"/>
			</s>	
		</xsl:if>
	</xsl:element>	
</xsl:template>

<xsl:template name="bulletin">
	<xsl:element name="f">
		<xsl:if test="eFetchResult:Journal/eFetchResult:JournalIssue">
			<xsl:attribute name="c">463</xsl:attribute>	
				<xsl:variable name="vol">	
					<xsl:if test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:Volume">
						<xsl:value-of select="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:Volume"/>	
					</xsl:if>
				</xsl:variable>
				<xsl:variable name="issue">	
					<xsl:if test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:Issue">
						<xsl:value-of select="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:Issue"/>	
					</xsl:if>
				</xsl:variable>
				<xsl:choose>
					<xsl:when test="$issue!='' and $vol!=''">
						<s c="v">
							<xsl:value-of select="concat('vol. ',$vol,', no. ',$issue)"/>
						</s>
					</xsl:when>
					<xsl:when test="$issue!='' and $vol=''">
						<s c="v">
							<xsl:value-of select="concat('no. ',$issue)"/>
						</s>
					</xsl:when>
					<xsl:when test="$issue='' and $vol!=''">
						<s c="v">
							<xsl:value-of select="concat('vol. ',$vol)"/>
						</s>
					</xsl:when>
				</xsl:choose>
			<s c="9">lnk:bull</s>	
		</xsl:if>
		<xsl:variable name="day">
			<xsl:if test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Day">
				<xsl:value-of select="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Day"/>	
			</xsl:if>
		</xsl:variable>
		<xsl:variable name="month">
			<xsl:choose>
				<xsl:when test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month = 'Jan'">01</xsl:when>
				<xsl:when test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month = 'Feb'">02</xsl:when>
				<xsl:when test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month = 'Mar'">03</xsl:when>
				<xsl:when test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month = 'Apr'">04</xsl:when>
				<xsl:when test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month = 'May'">05</xsl:when>
				<xsl:when test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month = 'Jun'">06</xsl:when>
				<xsl:when test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month = 'Jul'">07</xsl:when>
				<xsl:when test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month = 'Aug'">08</xsl:when>
				<xsl:when test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month = 'Sep'">09</xsl:when>
				<xsl:when test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month = 'Oct'">10</xsl:when>
				<xsl:when test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month = 'Nov'">11</xsl:when>
				<xsl:when test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month = 'Dec'">12</xsl:when>
				<xsl:otherwise test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month">
					<xsl:value-of select="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month" />
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<xsl:variable name="year">
			<xsl:if test="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Year">
				<xsl:value-of select="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Year"/>	
			</xsl:if>
		</xsl:variable>
		<xsl:choose>
			<xsl:when test="$month!='' and $day !='' and $year !=''">
				<s c="d"><xsl:value-of select="concat($year,'-',$month,'-',$day)"/></s>
				<s c="e"><xsl:value-of select="concat($day,' ',eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month,' ',$year)"/></s>
			</xsl:when>
			<xsl:when test="$month='' and $day ='' and $year !=''">
				<s c="d"><xsl:value-of select="concat($year,'-','01','-','01')"/></s>
				<s c="e"><xsl:value-of select="eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month"/></s>
			</xsl:when>
			<xsl:when test="$month!='' and $day ='' and $year !=''">
				<s c="d"><xsl:value-of select="concat($year,'-',$month,'-','01')"/></s>
				<s c="e"><xsl:value-of select="concat(eFetchResult:Journal/eFetchResult:JournalIssue/eFetchResult:PubDate/eFetchResult:Month,' ',$year)"/></s>
			</xsl:when>
		</xsl:choose>
	</xsl:element>	
</xsl:template>


<xsl:template name="perio">
	<xsl:if test="eFetchResult:Journal">
	<xsl:element name="f">
		<xsl:attribute name="c">461</xsl:attribute>	
			<xsl:if test="eFetchResult:Journal/eFetchResult:Title">
				<s c="t">
					<xsl:value-of select="eFetchResult:Journal/eFetchResult:Title"/>
				</s>
			</xsl:if>	
			<xsl:if test="eFetchResult:Journal/eFetchResult:ISSN">
				<s c="x">
					<xsl:value-of select="eFetchResult:Journal/eFetchResult:ISSN"/>
				</s>	
			</xsl:if>
			<s c="9">lnk:perio</s>	
	</xsl:element>	
	</xsl:if>
</xsl:template>

<xsl:template name="typedoc">
	<xsl:if test="eFetchResult:PublicationTypeList">
	<xsl:element name="f">
		<xsl:attribute name="c">900</xsl:attribute>
			<xsl:variable name="doctype">	
				<xsl:choose>	
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Abstracts'">Abstract</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Meeting Abstracts'">Abstract</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Academic Dissertations'">Thesis</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Annual Reports'">Report</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Technical Report'">Report</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Book Reviews'">Review</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Review'">Review</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Classical Article'">Article</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Corrected and Republished Article'">Article</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Journal Article'">Article</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Newspaper Article'">Article</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Comment'">Erratum</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Published Erratum'">Erratum</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Congresses'">Conference proceedings</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Database'">Database</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Dictionary'">Dictionary</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Directory'">Directory</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Editorial'">Editorial</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Encyclopedias'">Encyclopedia</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Letter'">Letter</xsl:when>
					<xsl:when test="eFetchResult:PublicationTypeList/eFetchResult:PublicationType = 'Unpublished Works'">Preprint</xsl:when>
					<xsl:otherwise test="eFetchResult:PublicationTypeList">Article</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<s c="a"><xsl:value-of select="$doctype"/></s>
			<s c="l"><xsl:text>Sub-Type</xsl:text></s>
			<s c="n"><xsl:text>subtype</xsl:text></s>
	</xsl:element>	
	</xsl:if>
</xsl:template>	

<xsl:template name="publishers">
	<xsl:element name="f">
		<xsl:attribute name="c">210</xsl:attribute>	
		<xsl:if test="eFetchResult:BookDocument/eFetchResult:Book/eFetchResult:Publisher/eFetchResult:PublisherLocation">
			<s c="a">
				<xsl:value-of select="eFetchResult:BookDocument/eFetchResult:Book/eFetchResult:Publisher/eFetchResult:PublisherLocation"/>
			</s>	
		</xsl:if>	
		<xsl:if test="eFetchResult:BookDocument/eFetchResult:Book/eFetchResult:Publisher/eFetchResult:PublisherName">
			<s c="c">
				<xsl:value-of select="eFetchResult:BookDocument/eFetchResult:Book/eFetchResult:Publisher/eFetchResult:PublisherName"/>
			</s>	
		</xsl:if>
	</xsl:element>	
</xsl:template>

<xsl:template name="parution_date">
	<xsl:element name="f">
		<xsl:attribute name="c">210</xsl:attribute>
		<xsl:if test="eFetchResult:PubmedBookData/eFetchResult:History/eFetchResult:PubMedPubDate[@PubStatus='pubmed']">
			<s c="d">
				<xsl:if test="eFetchResult:PubmedBookData/eFetchResult:History/eFetchResult:PubMedPubDate[@PubStatus='pubmed']/eFetchResult:Day">
					<xsl:if test="string-length(eFetchResult:PubmedBookData/eFetchResult:History/eFetchResult:PubMedPubDate[@PubStatus='pubmed']/eFetchResult:Day) = 1">
						<xsl:text>0</xsl:text>
					</xsl:if>
					<xsl:value-of select="eFetchResult:PubmedBookData/eFetchResult:History/eFetchResult:PubMedPubDate[@PubStatus='pubmed']/eFetchResult:Day" />
					<xsl:text>/</xsl:text>
				</xsl:if>
				<xsl:if test="eFetchResult:PubmedBookData/eFetchResult:History/eFetchResult:PubMedPubDate[@PubStatus='pubmed']/eFetchResult:Month">
					<xsl:if test="string-length(eFetchResult:PubmedBookData/eFetchResult:History/eFetchResult:PubMedPubDate[@PubStatus='pubmed']/eFetchResult:Month) = 1">
						<xsl:text>0</xsl:text>
					</xsl:if>
					<xsl:value-of select="eFetchResult:PubmedBookData/eFetchResult:History/eFetchResult:PubMedPubDate[@PubStatus='pubmed']/eFetchResult:Month" />
					<xsl:text>/</xsl:text>
				</xsl:if>
				<xsl:value-of select="eFetchResult:PubmedBookData/eFetchResult:History/eFetchResult:PubMedPubDate[@PubStatus='pubmed']/eFetchResult:Year" />
			</s>
		</xsl:if>
	</xsl:element>	
</xsl:template>

<xsl:template name="book_authors">
	<xsl:for-each select="eFetchResult:BookDocument/eFetchResult:Book/eFetchResult:AuthorList/eFetchResult:Author">
		<xsl:value-of select="."/>
		<xsl:element name="f">
			<xsl:attribute name="c">
				<xsl:choose>
					<xsl:when test="position()=1">
						<xsl:choose>
							<xsl:when test="eFetchResult:CollectiveName">
								<xsl:text>710</xsl:text>
							</xsl:when>
							<xsl:otherwise>
								<xsl:text>700</xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:when>
					<xsl:otherwise>
						<xsl:choose>
							<xsl:when test="eFetchResult:CollectiveName">
								<xsl:text>711</xsl:text>
							</xsl:when>
							<xsl:otherwise>
								<xsl:text>701</xsl:text>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:otherwise>
				</xsl:choose>	
			</xsl:attribute>
				<xsl:choose>
					<xsl:when test="eFetchResult:CollectiveName">
						<s c="a"><xsl:value-of select="eFetchResult:CollectiveName"/></s>
					</xsl:when>
					<xsl:otherwise>
						<xsl:if test="eFetchResult:LastName">
							<s c="a"><xsl:value-of select="eFetchResult:LastName"/></s>
						</xsl:if>
						<xsl:if test="eFetchResult:ForeName">
							<s c="b"><xsl:value-of select="eFetchResult:ForeName"/></s>
						</xsl:if>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:if test="../@Type = 'authors'">
					<s c="4"><xsl:text>070</xsl:text></s>
				</xsl:if>				
				<xsl:if test="../@Type = 'editors'">
					<s c="4"><xsl:text>340</xsl:text></s>
				</xsl:if>
		</xsl:element>
	</xsl:for-each>
</xsl:template>

<xsl:template name="resume">
	<xsl:if test="eFetchResult:BookDocument/eFetchResult:Abstract/eFetchResult:AbstractText">
		<xsl:for-each select="eFetchResult:BookDocument/eFetchResult:Abstract/eFetchResult:AbstractText">
			<xsl:element name="f">
				<xsl:attribute name="c">330</xsl:attribute>	
				<s c="a">
					<xsl:if test="./@Label">
						<xsl:value-of select="./@Label"/><xsl:text>: </xsl:text>
					</xsl:if>
					<xsl:value-of select="."/>
				</s>
			</xsl:element>		
		</xsl:for-each>
	</xsl:if>
</xsl:template>

<xsl:template name="sections">
	<xsl:if test="eFetchResult:BookDocument/eFetchResult:Sections/eFetchResult:Section">
		<f c="327">
			<s c="a">
				<xsl:for-each select="eFetchResult:BookDocument/eFetchResult:Sections/eFetchResult:Section">
					<xsl:if test="eFetchResult:SectionTitle">
						<xsl:if test="eFetchResult:LocationLabel">
							<xsl:value-of select="eFetchResult:LocationLabel" /><xsl:text>. </xsl:text>
						</xsl:if>
						<xsl:value-of select="eFetchResult:SectionTitle" />
						<xsl:text>
</xsl:text>
					</xsl:if>
				</xsl:for-each>
			</s>
		</f>
	</xsl:if>
</xsl:template>

<xsl:template name="collection">
	<xsl:if test="eFetchResult:BookDocument/eFetchResult:Book/eFetchResult:CollectionTitle">
		<f c="225">
			<s c="a"><xsl:value-of select="eFetchResult:BookDocument/eFetchResult:Book/eFetchResult:CollectionTitle"/></s>
		</f>
	</xsl:if>
</xsl:template>

<xsl:template name="link">
	<xsl:if test="eFetchResult:BookDocument/eFetchResult:ArticleIdList/eFetchResult:ArticleId[@IdType= 'bookaccession']">
		<f c="856">
			<s c="u"><xsl:text>http://www.ncbi.nlm.nih.gov/books/</xsl:text><xsl:value-of select="eFetchResult:BookDocument/eFetchResult:ArticleIdList/eFetchResult:ArticleId[@IdType= 'bookaccession']"></xsl:value-of></s>
		</f>
	</xsl:if>
</xsl:template>

</xsl:stylesheet>