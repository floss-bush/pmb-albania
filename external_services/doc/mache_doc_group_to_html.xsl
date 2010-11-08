<?xml version="1.0" encoding="UTF-8"?>
<!-- Feuille de génération de documentation HTML de l'API PMB
****************************************************************************************
© 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
****************************************************************************************
$Id: mache_doc_group_to_html.xsl,v 1.1 2009-07-31 15:17:22 erwanmartin Exp $ 
Conception: Erwan Martin:
Design copié de la feuille de style wsdl-viewer.xsl, voir http://tomi.vanek.sk
-->

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://rien" xmlns:func="http://exslt.org/functions"
extension-element-prefixes="func" xmlns:pmb="http://sigb.net/es/misc/" xmlns:exsl="http://exslt.org/common">

<xsl:output method="html"/>
<xsl:param name="external_services_basepath"></xsl:param>
<xsl:param name="working_group"></xsl:param>
<xsl:param name="lang">fr_FR</xsl:param>
<xsl:param name="navigation_base"></xsl:param>

<xsl:variable name="css">

/**
	wsdl-viewer.css
*/

/**
=========================================
	Body
=========================================
*/
html {
	background-color: teal;
}

body {
	margin: 0;
	padding: 0;
	height: auto;
	color: white;
	background-color: teal;
	font: normal 80%/120% Arial, Helvetica, sans-serif;
}

#outer_box {
	padding: 3px 3px 3px 194px;
}

#inner_box {
	width: auto;
	background-color: white;
	color: black;
	border: 1px solid navy;
}

table {
	color: black;
}

/**
=========================================
	Fixed box with links
=========================================
*/
#outer_links { 
	position: fixed;
	left: 0px;
	top: 0px;
	margin: 3px;
	padding: 1px;
	z-index: 200; 
	width: 180px;
	height: auto;
	background-color: gainsboro;
	padding-top: 2px;
	border: 1px solid navy;
}

* html #outer_links /* Override above rule for IE */ 
{ 
	position: absolute; 
	width: 188px;
	top: expression(offsetParent.scrollTop + 0); 
} 

#links {
	margin: 1px;
	padding: 3px;
	background-color: white;
	height: 350px;
	overflow: auto;
	border: 1px solid navy;
}

#links ul {
	left: -999em;
	list-style: none;
	margin: 0;
	padding: 0;
	z-index: 100;
}

#links li {
	margin: 0;
	padding: 2px 4px;
	width: auto;
	z-index: 100;
}

#links ul li {
	margin: 0;
	padding: 2px 4px;
	width: auto;
	z-index: 100;
}

#links a {
	display: block;
	padding: 0 2px;
	color: blue;
	width: auto;
	border: 1px solid white;
	text-decoration: none;
	white-space: nowrap;
}

#links a:hover {
	color: white;
	background-color: gray;
	border: 1px solid gray;
} 


/**
=========================================
	Navigation tabs
=========================================
*/

#outer_nav {
	background-color: yellow;
	padding: 0;
	margin: 0;
}

#nav {
	height: 100%;
	width: auto;
	margin: 0;
	padding: 0;
	background-color: gainsboro;
	border-top: 1px solid gray;
	border-bottom: 3px solid gray;
	z-index: 100;
	font: bold 90%/120% Arial, Helvetica, sans-serif;
	letter-spacing: 2px;
} 

#nav ul { 
	background-color: gainsboro;
	height: auto;
	width: auto;
	list-style: none;
	margin: 0;
	padding: 0;
	z-index: 100;

	border: 1px solid silver; 
	border-top-color: black; 
	border-width: 1px 0 9px; 
} 

#nav li { 
	display: inline; 
	padding: 0;
	margin: 0;
} 

#nav a { 
	position: relative;
	top: 3px;
	float:left; 
	width:auto; 
	padding: 8px 10px 6px 10px;
	margin: 3px 3px 0;
	border: 1px solid gray; 
	border-width: 2px 2px 3px 2px;

	color: black; 
	background-color: silver; 
	text-decoration:none; 
	text-transform: uppercase;
}

#nav a:hover { 
	margin-top: 1px;
	padding-top: 9px;
	padding-bottom: 7px;
	color: blue;
	background-color: gainsboro;
} 

#nav a.current:link,
#nav a.current:visited,
#nav a.current:hover {
	background: white; 
	color: black; 
	text-shadow:none; 
	margin-top: 0;
	padding-top: 11px;
	padding-bottom: 9px;
	border-bottom-width: 0;
	border-color: red; 
}

#nav a:active { 
	background-color: silver; 
	color: white;
} 



/**
=========================================
	Content
=========================================
*/
#header {
	margin: 0;
	padding: .5em 4em;
	color: white;
	background-color: red;
	border: 1px solid darkred;
}

#content {
	margin: 0;
	padding: 0 2em .5em;
}

#footer {
	clear: both;
	margin: 0;
	padding: .5em 2em;
	color: gray;
	background-color: gainsboro;
	font-size: 80%;
	border-top: 1px dotted gray;
	text-align: right
}

.single_column {
	padding: 10px 10px 10px 10px;
	/*margin: 0px 33% 0px 0px; */
	margin: 3px 0;
}

#flexi_column {
	padding: 10px 10px 10px 10px;
	/*margin: 0px 33% 0px 0px; */
	margin: 0px 212px 0px 0px;
}

#fix_column {
	float: right;
	padding: 10px 10px 10px 10px;
	margin: 0px;
	width: 205px;
	/*width: 30%; */
	voice-family: "\"}\"";
	voice-family:inherit;
	/* width: 30%; */
	width: 205px;
}
html&gt;body #rightColumn {
	width: 205px; /* ie5win fudge ends */
} /* Opera5.02 shows a 2px gap between. N6.01Win sometimes does.
	Depends on amount of fill and window size and wind direction. */

/**
=========================================
	Label / value
=========================================
*/

.page {
	border-bottom: 3px dotted navy;
	margin: 0;
	padding: 10px 0 20px 0;
}

.value, .label {
	margin: 0;
	padding: 0;
}

.label {
	float: left;
	width: 200px;
	text-align: right;
	font-weight: bold;
	padding-bottom: .5em;
	margin-right: 0;
	color: darkblue;
	clear: left;
}

.value {
	margin-left: 210px;
	color: black;
	padding-bottom: .5em;
}

strong, strong a {
	color: darkblue;
	font-weight: bold;
	letter-spacing: 1px;
	margin-left: 2px;
}

.subfield_description {
	color: #505050;
}

.input_description {
	font-family: monospace;
	font-size: 13px;
}

.requirement_descripter {
	font-size: 11px;
	color: darkblue;
}

/**
=========================================
	Links
=========================================
*/

a.local:link,
a.local:visited {
	color: blue; 
	margin-left: 10px;
	border-bottom: 1px dotted blue;
	text-decoration: none;
	font-style: italic;
}

a.local:hover {
	background-color: gainsboro; 
	color: darkblue;
	padding-bottom: 1px;
	border-bottom: 1px solid darkblue;
}

a.target:link,
a.target:visited,
a.target:hover
{
	text-decoration: none;
	background-color: transparent;
	border-bottom-type: none;
}

/**
=========================================
	Box, Shadow
=========================================
*/

.box {
	padding: 6px;
	color: black;
	background-color: gainsboro;
	border: 1px solid gray;
}

.shadow {
	background: silver;
	position: relative;
	top: 5px;
	left: 4px;
}

.shadow div {
	position: relative;
	top: -5px;
	left: -4px;
}

/**
=========================================
	Floatcontainer
=========================================
*/

.spacer
{
	display: block;
	height: 0;
	font-size: 0;
	line-height: 0;
	margin: 0;
	padding: 0;
	border-style: none;
	clear: both; 
	visibility:hidden;
}

.floatcontainer:after {
	content: ".";
	display: block;
	height: 0;
	font-size:0; 
	clear: both;
	visibility:hidden;
}
.floatcontainer{
	display: inline-table;
} /* Mark Hadley's fix for IE Mac */ /* Hides from IE Mac \*/ * 
html .floatcontainer {
	height: 1%;
}
.floatcontainer{
	display:block;
} /* End Hack 
*/ 

/**
=========================================
	Source code
=========================================
*/

.indent {
	margin: 2px 0 2px 20px;
}

.xml-element, .xml-proc, .xml-comment {
	margin: 2px 0;
	padding: 2px 0 2px 0;
}

.xml-element {
	word-spacing: 3px;
	color: red;
	font-weight: bold;
	font-style:normal;
	border-left: 1px dotted silver;
}

.xml-element div {
	margin: 2px 0 2px 40px;
}

.xml-att {
	color: blue;
	font-weight: bold;
}

.xml-att-val {
	color: blue;
	font-weight: normal;
}

.xml-proc {
	color: darkred;
	font-weight: normal;
	font-style: italic;
}

.xml-comment {
	color: green;
	font-weight: normal;
	font-style: italic;
}

.xml-text {
	color: green;
	font-weight: normal;
	font-style: normal;
}


/**
=========================================
	Heading
=========================================
*/
h1, h2, h3 {
	margin: 10px 10px 2px;
	font-family: Georgia, Times New Roman, Times, Serif;
	font-weight: normal;
	}

h1 {
	font-weight: bold;
	letter-spacing: 3px;
	font-size: 220%;
	line-height: 100%;
}

h2 {
	font-weight: bold;
	font-size: 175%;
	line-height: 200%;
}

h3 {
	font-size: 150%;
	line-height: 150%;
	font-style: italic;
}

/**
=========================================
	Content formatting
=========================================
*/
.port {
	margin-bottom: 10px;
	padding-bottom: 10px;
	border-bottom: 1px dashed gray;
}

.operation {
	margin-bottom: 20px;
	padding-bottom: 10px;
	border-bottom: 1px dashed gray;
}


/* --------------------------------------------------------
	Printing
*/

/*
@media print
{
	#outer_links, #outer_nav { 
		display: none;
	}
*/

	#outer_box {
		padding: 3px;
	}
/* END print media definition
}
*/

</xsl:variable>

<func:function name="pmb:msg">
	<xsl:param name="code"></xsl:param>
	<xsl:param name="group"></xsl:param>
	<xsl:choose>
		<xsl:when test="starts-with($code,'msg:')">
	  		<func:result select="document(concat($external_services_basepath, '/', $group,'/messages/', $lang, '.xml'))/XMLlist/entry[@code=substring-after($code, ':')]"/>
		</xsl:when>
		<xsl:otherwise>
			 <func:result select="$code"/>
		</xsl:otherwise>
	</xsl:choose>
</func:function>

<xsl:template name="lf2br">
		<!-- import $StringToTransform -->
		<xsl:param name="StringToTransform"/>
		<xsl:choose>
			<!-- string contains linefeed -->
			<xsl:when test="contains($StringToTransform,'&#xA;')">
				<!-- output substring that comes before the first linefeed -->
				<!-- note: use of substring-before() function means        -->
				<!-- $StringToTransform will be treated as a string,       -->
				<!-- even if it is a node-set or result tree fragment.     -->
				<!-- So hopefully $StringToTransform is really a string!   -->
				<xsl:value-of select="substring-before($StringToTransform,'&#xA;')"/>
				<!-- by putting a 'br' element in the result tree instead  -->
				<!-- of the linefeed character, a <br> will be output at   -->
				<!-- that point in the HTML                                -->
				<xsl:value-of select="'&lt;br&gt;'" disable-output-escaping="yes"/>
				<!-- repeat for the remainder of the original string -->
				<xsl:call-template name="lf2br">
					<xsl:with-param name="StringToTransform">
						<xsl:value-of select="substring-after($StringToTransform,'&#xA;')"/>
					</xsl:with-param>
				</xsl:call-template>
			</xsl:when>
			<!-- string does not contain newline, so just output it -->
			<xsl:otherwise>
				<xsl:value-of select="$StringToTransform"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>


<xsl:template match="/">
	<xsl:choose>
		<xsl:when test="boolean(document(concat($external_services_basepath, '/', $working_group,'/manifest.xml'))) = false">
			<xsl:call-template name="list_groups"/>
		</xsl:when>
		<xsl:otherwise>
			<xsl:variable name="group_manifest" select="document(concat($external_services_basepath, '/', $working_group,'/manifest.xml'))"/>
			<xsl:call-template name="show_group">
				<xsl:with-param name="group_manifest" select="$group_manifest"/>
			</xsl:call-template>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="list_groups">
	<xsl:variable name="catalog" select="document(concat($external_services_basepath, '/catalog.xml'))"/>
	<html><head>
		<title>
			Documentation de l'API PMB - Liste des groupes de fonctions
		</title>
        <style type="text/css">
            <xsl:value-of select="$css" disable-output-escaping="yes"/>
         </style>
	</head><body id="operations">
		<div id="outer_box">
            <div id="inner_box" onload="pagingInit()">
            	<div id="header">
					<h1>API PMB: Liste des groupes de fonctions</h1>
				</div>
				<h2 class="target">Liste des groupes</h2>
				<ul>
					<xsl:for-each select="$catalog/catalog/item">
						<xsl:choose>
							<xsl:when test="$navigation_base != ''">
								<li><a href="{$navigation_base}group={@name}"><xsl:value-of select="@name"/></a></li>
							</xsl:when>
							<xsl:otherwise>
								<li><xsl:value-of select="@name"/></li>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:for-each>
				</ul>
				<br />
			</div>
	      	<div id="footer">
			Le design de cette page a été copié sur celui de la feuille wsdl-viewer.xsl (<a href="http://tomi.vanek.sk/">http://tomi.vanek.sk</a>)
			</div>
		</div>
	</body>
	</html>

</xsl:template>

<xsl:template name="show_group">
	<xsl:param name="group_manifest"/>
	<html><head>
		<title>
			Documentation du groupe de fonction <xsl:value-of select="$group_manifest/manifest/name"/> de l'API PMB
		</title>
        <style type="text/css">
            <xsl:value-of select="$css" disable-output-escaping="yes"/>
         </style>
	</head><body id="operations">
		<div id="outer_box">
            <div id="inner_box" onload="pagingInit()">
            	<a name="top"/>
            	<div id="header">
					<h1>API PMB: Groupe <xsl:value-of select="$group_manifest/manifest/name"/></h1>
				</div>
				<div id="content">
					<div class="page">

						<xsl:if test="$navigation_base != ''">
							<a href="{$navigation_base}">Retour à la liste des groupes</a>
						</xsl:if>

						<h2 class="target">Informations sur le groupe</h2>
						<ul>
							<div class="label">Nom du groupe:</div> <div class="value"><xsl:value-of select="$group_manifest/manifest/name"/></div>
							<div class="label">Description:</div> <div class="value"><xsl:value-of select="pmb:msg($group_manifest/manifest/description, $working_group)"/></div>
							<xsl:if test="count($group_manifest/manifest/requirements/requirement) > 0">
								<div class="label">Nécessite les groupes suivant:</div> 
								<div class="value">
									<ul>
										<xsl:for-each select="$group_manifest/manifest/requirements/requirement">
											<xsl:choose>
												<xsl:when test="$navigation_base != ''">
													<li><a href="{$navigation_base}group={@group}"><xsl:value-of select="@group"/></a></li>
												</xsl:when>
												<xsl:otherwise>
													<li><xsl:value-of select="@group"/></li>
												</xsl:otherwise>
											</xsl:choose>
										</xsl:for-each>
									</ul>
								</div>
							</xsl:if>
							<xsl:if test="count($group_manifest/manifest/types/type) > 0">
								<div class="label">Déclare ou fait référence aux types suivants:</div> 
								<div class="value">
									<ol>
										<xsl:for-each select="$group_manifest/manifest/types/type">
											<li><a href="#type_{@name}"><xsl:value-of select="@name"/></a></li>
										</xsl:for-each>
									</ol>
								</div>
							</xsl:if>
							<xsl:if test="count($group_manifest/manifest/methods/method) > 0">
								<div class="label">Déclare les méthodes suivantes:</div> 
								<div class="value">
									<ol>
										<xsl:for-each select="$group_manifest/manifest/methods/method">
											<li><a href="#method_{@name}"><xsl:value-of select="@name"/></a></li>
										</xsl:for-each>
									</ol>
								</div>
							</xsl:if>
						</ul>
					</div>
					<xsl:if test="count($group_manifest/manifest/types/type) > 0">
						<h2>Types déclarés ou référencés</h2>
						<xsl:call-template name="types">
							<xsl:with-param name="group_manifest" select="$group_manifest"/>
						</xsl:call-template>
					</xsl:if>
					<xsl:if test="count($group_manifest/manifest/methods/method) > 0">
						<h2>Méthodes</h2>
						<xsl:call-template name="methods">
							<xsl:with-param name="group_manifest" select="$group_manifest"/>
						</xsl:call-template>
					</xsl:if>
				</div>
			</div>
	      	<div id="footer">
			Le design de cette page a été copié sur celui de la feuille wsdl-viewer.xsl (<a href="http://tomi.vanek.sk/">http://tomi.vanek.sk</a>)
			</div>
		</div>
	</body>
	</html>
</xsl:template>

<xsl:template name="types">
	<xsl:param name="group_manifest"/>
	<ol>
		<xsl:for-each select="$group_manifest/manifest/types/type">
		<li class="operation">
		<a name="type_{@name}"/>
		<h3><b><xsl:value-of select="@name"/></b></h3>
			<ul>
				<xsl:choose>
					<xsl:when test="@imported">
						<xsl:variable name="temp_name" select="@name"/>
						<xsl:variable name="buffer2" select="document(concat($external_services_basepath, '/', @imported_from,'/manifest.xml'))/manifest/types/type[@name=$temp_name]"/>
						<div class="label">Nom du type:</div> <div class="value"><xsl:value-of select="@name"/></div>
						<div class="label">Description:</div> <div class="value"><xsl:value-of select="pmb:msg($buffer2/@description, @imported_from)"/></div>
						<div class="label">Localisation:</div> 
						<div class="value">Importé du groupe 
							<xsl:choose>
								<xsl:when test="$navigation_base != ''">
									<a href="{$navigation_base}group={@imported_from}#type_{@name}"><xsl:value-of select="@imported_from"/></a>
								</xsl:when>
								<xsl:otherwise>
									<xsl:value-of select="@imported_from"/>
								</xsl:otherwise>
							</xsl:choose>
						</div>
						<div class="label">Contenu:</div><div class="value"></div>
						<xsl:call-template name="params_or_results_typecontents">
						  <xsl:with-param name="node_name">part</xsl:with-param>
						  <xsl:with-param name="parent_node" select="$buffer2"/>
						  <xsl:with-param name="current_group" select="@imported_from"/>
						</xsl:call-template>
					</xsl:when>
					<xsl:otherwise>
						<div class="label">Nom du type:</div> <div class="value"><xsl:value-of select="@name"/></div>
						<div class="label">Description:</div> <div class="value"><xsl:value-of select="pmb:msg(@description, $working_group)"/></div>
						<div class="label">Localisation:</div> <div class="value">local au groupe</div>
						<div class="label">Contenu:</div><div class="value"></div>
						<xsl:call-template name="params_or_results_typecontents">
						  <xsl:with-param name="node_name">part</xsl:with-param>
						  <xsl:with-param name="parent_node" select="."/>
						  <xsl:with-param name="current_group" select="$working_group"/>
						</xsl:call-template>
					</xsl:otherwise>
				</xsl:choose>
				<br clear="both"/>
				<div style="text-align: right">
					<a href="#top">Sommaire</a>
				</div>
			</ul>
		</li>
		</xsl:for-each>
	</ol>
</xsl:template>

<xsl:template name="methods">
	<xsl:param name="group_manifest"/>
	<ol>
		<xsl:for-each select="$group_manifest/manifest/methods/method">
		<li class="operation">
		<a name="method_{@name}"/>
		<h3><b><xsl:value-of select="@name"/></b></h3>
			<ul>
				<div class="label">Nom de la méthode:</div> <div class="value"><xsl:value-of select="@name"/></div>
				<div class="label">Description:</div> <div class="value"><xsl:value-of select="pmb:msg(@comment, $working_group)"/></div>
				<div class="label">Version:</div> <div class="value"><xsl:value-of select="@version"/></div>
				<xsl:if test="count(requirements/requirement) > 0">
					<div class="label">Nécessite les méthodes suivantes:</div> 
					<div class="value">
						<ul>
							<xsl:for-each select="requirements/requirement">
								<xsl:choose>
									<xsl:when test="$navigation_base != ''">
										<li><a href="{$navigation_base}group={@group}#method_{@name}"><xsl:value-of select="@name"/></a><span class="requirement_descripter"> du groupe </span><a href="{$navigation_base}group={@group}"><xsl:value-of select="@group"/></a><span class="requirement_descripter">, en version </span><xsl:value-of select="@version"/></li>
									</xsl:when>
									<xsl:otherwise>
										<li><xsl:value-of select="@name"/><span class="requirement_descripter"> du groupe </span><xsl:value-of select="@group"/><span class="requirement_descripter">, en version </span><xsl:value-of select="@version"/></li>
									</xsl:otherwise>
								</xsl:choose>
							</xsl:for-each>
						</ul>
					</div>
				</xsl:if>
				<div class="label">Description des entrées:</div><div class="value"><pre><xsl:value-of select="pmb:msg(inputs/description, $working_group)"/></pre></div>
				  <xsl:call-template name="params_or_results_typecontents">
				    <xsl:with-param name="node_name">param</xsl:with-param>
				    <xsl:with-param name="parent_node" select="inputs"/>
				    <xsl:with-param name="current_group" select="$working_group"/>
				  </xsl:call-template>
				<div class="label">Description des retours:</div><div class="value"><pre><xsl:value-of select="pmb:msg(outputs/description, $working_group)"/></pre></div>
				  <xsl:call-template name="params_or_results_typecontents">
				    <xsl:with-param name="node_name">result</xsl:with-param>
				    <xsl:with-param name="parent_node" select="outputs"/>
				    <xsl:with-param name="current_group" select="$working_group"/>
				  </xsl:call-template>
			</ul>
			<br />
			<div style="text-align: right">
				<a href="#top">Sommaire</a>
			</div>
		</li>
		</xsl:for-each>
	</ol>
</xsl:template>

<xsl:template name="params_or_results_typecontents">
	<xsl:param name="node_name">param</xsl:param>
	<xsl:param name="parent_node"></xsl:param>
	<xsl:param name="current_group"></xsl:param>
	<xsl:variable name="current_group_manifest" select="document(concat($external_services_basepath, '/', $current_group,'/manifest.xml'))"/>
	<xsl:variable name="temp" select="$parent_node/*[local-name() = $node_name]"/>
	<xsl:for-each select="$temp">
		<div class="value box" style="margin-bottom: 3px;">
			<table width="100%">
				<tr>
					<td style="padding-right:50px;">
						<b>
							<xsl:value-of select="@name"/>
						</b>
						<xsl:choose>
							<xsl:when test="@type='scalar'">
								<span style="color:darkblue">
									<small> type </small>
									<xsl:value-of select="@dataType"/>
									<xsl:variable name="buffer" select="@dataType"/>
									<xsl:choose>
										<xsl:when test="count($current_group_manifest/manifest/types/type[@name=$buffer])>0">
											<xsl:choose>
												<xsl:when test="$current_group_manifest/manifest/types/type[@name=$buffer]/@imported != ''">
													<xsl:variable name="buffer2" select="document(concat($external_services_basepath, '/', $current_group_manifest/manifest/types/type[@name=$buffer]/@imported_from,'/manifest.xml'))/manifest/types"/>
													<xsl:variable name="type_parent_node" select="$buffer2/type[@name=$buffer]"/>
													<xsl:call-template name="type_to_list">
												    	<xsl:with-param name="node_name">part</xsl:with-param>
												    	<xsl:with-param name="parent_node" select="$type_parent_node"/>
												    	<xsl:with-param name="current_group" select="$current_group_manifest/manifest/types/type[@name=$buffer]/@imported_from"/>
													</xsl:call-template>
												</xsl:when>
												<xsl:otherwise>
													<xsl:variable name="type_parent_node" select="$current_group_manifest/manifest/types/type[@name=$buffer]"/>
													<xsl:call-template name="type_to_list">
												    	<xsl:with-param name="node_name">part</xsl:with-param>
												    	<xsl:with-param name="parent_node" select="$type_parent_node"/>
												    	<xsl:with-param name="current_group" select="$current_group"/>
													</xsl:call-template>
												</xsl:otherwise>
											</xsl:choose>
										</xsl:when>
									</xsl:choose>
								</span>
							</xsl:when>
							<xsl:when test="@type='array'">
								<span style="color:darkblue">
									<small> type tableau de </small>
									<xsl:variable name="temp2" select="*[local-name() = $node_name]"/>
									<xsl:choose>
										<xsl:when test="count($temp2)=1">
											<xsl:value-of select="$temp2/@dataType"/>
											<xsl:variable name="buffer" select="$temp2/@dataType"/>
												<xsl:choose>
												<xsl:when test="count($current_group_manifest/manifest/types/type[@name=$buffer])>0">
													<xsl:choose>
														<xsl:when test="$current_group_manifest/manifest/types/type[@name=$buffer]/@imported != ''">
															<xsl:variable name="buffer2" select="document(concat($external_services_basepath, '/', $current_group_manifest/manifest/types/type[@name=$buffer]/@imported_from,'/manifest.xml'))/manifest/types"/>
															<xsl:variable name="type_parent_node" select="$buffer2/type[@name=$buffer]"/>
															<xsl:call-template name="type_to_list">
														    	<xsl:with-param name="node_name">part</xsl:with-param>
														    	<xsl:with-param name="parent_node" select="$type_parent_node"/>
														    	<xsl:with-param name="current_group" select="$current_group_manifest/manifest/types/type[@name=$buffer]/@imported_from"/>
															</xsl:call-template>
														</xsl:when>
														<xsl:otherwise>
															<xsl:variable name="type_parent_node" select="$current_group_manifest/manifest/types/type[@name=$buffer]"/>
															<xsl:call-template name="type_to_list">
														    	<xsl:with-param name="node_name">part</xsl:with-param>
														    	<xsl:with-param name="parent_node" select="$type_parent_node"/>
														    	<xsl:with-param name="current_group" select="$current_group"/>
															</xsl:call-template>
														</xsl:otherwise>
													</xsl:choose>
												</xsl:when>
											</xsl:choose>
										</xsl:when>
										<xsl:otherwise>
											la structure suivante:
											  <xsl:call-template name="type_to_list">
											    <xsl:with-param name="node_name"><xsl:copy-of select="$node_name"/></xsl:with-param>
											    <xsl:with-param name="parent_node" select="."/>
											    <xsl:with-param name="current_group" select="$current_group"/>
											  </xsl:call-template>
										</xsl:otherwise>
									</xsl:choose>
								</span>
							</xsl:when>
							<xsl:when test="@type='structure'">
								<span style="color:darkblue">
									<small> type </small>
									structure
									<xsl:variable name="temp2" select="*[local-name() = $node_name]"/>
									<xsl:choose>
										<xsl:when test="count($temp2)=1">
											<xsl:value-of select="$temp2/@dataType"/>
											<xsl:variable name="buffer" select="$temp2/@dataType"/>
												<xsl:choose>
												<xsl:when test="count($current_group_manifest/manifest/types/type[@name=$buffer])>0">
													<xsl:choose>
														<xsl:when test="$current_group_manifest/manifest/types/type[@name=$buffer]/@imported != ''">
															<xsl:variable name="buffer2" select="document(concat($external_services_basepath, '/', $current_group_manifest/manifest/types/type[@name=$buffer]/@imported_from,'/manifest.xml'))/manifest/types"/>
															<xsl:variable name="type_parent_node" select="$buffer2/type[@name=$buffer]"/>
															<xsl:call-template name="type_to_list">
														    	<xsl:with-param name="node_name">part</xsl:with-param>
														    	<xsl:with-param name="parent_node" select="$type_parent_node"/>
														    	<xsl:with-param name="current_group" select="$current_group_manifest/manifest/types/type[@name=$buffer]/@imported_from"/>
															</xsl:call-template>
														</xsl:when>
														<xsl:otherwise>
															<xsl:variable name="type_parent_node" select="$current_group_manifest/manifest/types/type[@name=$buffer]"/>
															<xsl:call-template name="type_to_list">
														    	<xsl:with-param name="node_name">part</xsl:with-param>
														    	<xsl:with-param name="parent_node" select="$type_parent_node"/>
														    	<xsl:with-param name="current_group" select="$current_group"/>
															</xsl:call-template>
														</xsl:otherwise>
													</xsl:choose>
												</xsl:when>
											</xsl:choose>
										</xsl:when>
										<xsl:otherwise>
											  <xsl:call-template name="type_to_list">
											    <xsl:with-param name="node_name"><xsl:copy-of select="$node_name"/></xsl:with-param>
											    <xsl:with-param name="parent_node" select="."/>
											    <xsl:with-param name="current_group" select="$current_group"/>
											  </xsl:call-template>
										</xsl:otherwise>
									</xsl:choose>
								</span>
							</xsl:when>
						</xsl:choose>
					</td>
					<td align="right" class="input_description">
				    <xsl:call-template name="lf2br">
	            		<xsl:with-param name="StringToTransform" select="pmb:msg(@description, $current_group)"/>
	    			</xsl:call-template>
					</td>
				</tr>
			</table>
		</div>
	</xsl:for-each>
</xsl:template>

<xsl:template name="type_to_list">
	<xsl:param name="node_name">param</xsl:param>
	<xsl:param name="parent_node"></xsl:param>
	<xsl:param name="current_group"></xsl:param>
	<xsl:variable name="current_group_manifest" select="document(concat($external_services_basepath, '/', $current_group,'/manifest.xml'))"/>
	<xsl:variable name="temp" select="$parent_node/*[local-name() = $node_name]"/>
	<xsl:for-each select="$temp">
		<ul type="square">
			<li>
				<span style="color:black"><xsl:value-of select="@name"/></span>
				<xsl:choose>
				<xsl:when test="@type='scalar'">
					<span style="color:darkblue">
						<small> type </small>
						<xsl:variable name="buffer" select="@dataType"/>
						<xsl:choose>
							<xsl:when test="(count(ancestor::*[@name = $buffer]) = 0) and count($current_group_manifest/manifest/types/type[@name=$buffer])>0">
								<xsl:value-of select="$current_group_manifest/manifest/types/type/@imported"/>
								<xsl:choose>
									<xsl:when test="$current_group_manifest/manifest/types/type[@name=$buffer]/@imported != ''">
										<xsl:variable name="buffer2" select="document(concat($external_services_basepath, '/', $current_group_manifest/manifest/types/type[@name=$buffer]/@imported_from,'/manifest.xml'))/manifest/types"/>
										<xsl:variable name="type_parent_node" select="$buffer2/type[@name=$buffer]"/>
										<xsl:call-template name="type_to_list">
									    	<xsl:with-param name="node_name">part</xsl:with-param>
									    	<xsl:with-param name="parent_node" select="$type_parent_node"/>
									    	<xsl:with-param name="current_group" select="$current_group_manifest/manifest/types/type[@name=$buffer]/@imported_from"/>
										</xsl:call-template>
									</xsl:when>
									<xsl:otherwise>
										<xsl:variable name="type_parent_node" select="$current_group_manifest/manifest/types/type[@name=$buffer]"/>
										<xsl:call-template name="type_to_list">
									    	<xsl:with-param name="node_name">part</xsl:with-param>
									    	<xsl:with-param name="parent_node" select="$type_parent_node"/>
									    	<xsl:with-param name="current_group" select="$current_group"/>
										</xsl:call-template>
									</xsl:otherwise>
								</xsl:choose>
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="@dataType"/>	&#xA0;&#xA0;&#xA0;&#xA0;&#xA0;&#xA0;&#xA0;<i class="subfield_description"><xsl:value-of select="pmb:msg(@description, $current_group)"/></i>
							</xsl:otherwise>
						</xsl:choose>
					</span>
				</xsl:when>
				<xsl:when test="@type='array'">
					<span style="color:darkblue">
						<small> type tableau de </small>
						<xsl:variable name="temp2" select="*[local-name() = $node_name]"/>
						<xsl:choose>
							<xsl:when test="count($temp2)=1">
								<xsl:value-of select="$temp2/@dataType"/>
							</xsl:when>
							<xsl:otherwise>
								la structure suivante: &#xA0;&#xA0;&#xA0;&#xA0;&#xA0;&#xA0;&#xA0;<i clear="both" class="subfield_description"><xsl:value-of select="pmb:msg(@description, $current_group)"/></i>
								  <xsl:call-template name="type_to_list">
								    <xsl:with-param name="node_name"><xsl:copy-of select="$node_name"/></xsl:with-param>
								    <xsl:with-param name="parent_node" select="."/>
								    <xsl:with-param name="current_group" select="$current_group"/>
								  </xsl:call-template>
							</xsl:otherwise>
						</xsl:choose>
					</span>
				</xsl:when>
				<xsl:when test="@type='structure'">
					<span style="color:darkblue">
						<small> type </small>
						structure &#xA0;&#xA0;&#xA0;&#xA0;&#xA0;&#xA0;&#xA0;<i clear="both" class="subfield_description"><xsl:value-of select="pmb:msg(@description, $current_group)"/></i>
						<xsl:variable name="temp2" select="*[local-name() = $node_name]"/>
						<xsl:choose>
							<xsl:when test="count($temp2)=1">
								<xsl:value-of select="$temp2/@dataType"/>
							</xsl:when>
							<xsl:otherwise>
								  <xsl:call-template name="type_to_list">
								    <xsl:with-param name="node_name"><xsl:copy-of select="$node_name"/></xsl:with-param>
								    <xsl:with-param name="parent_node" select="."/>
								    <xsl:with-param name="current_group" select="$current_group"/>
								  </xsl:call-template>
							</xsl:otherwise>
						</xsl:choose>
					</span>
				</xsl:when>
			</xsl:choose>
			</li>
		</ul>
	</xsl:for-each>
</xsl:template>

</xsl:stylesheet>