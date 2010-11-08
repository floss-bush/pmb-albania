<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: index.php,v 1.1 2009-07-31 15:17:22 erwanmartin Exp $

//Ce script nécéssite php5

if (phpversion() < 5) {
	die("PHP5 required");
}

header("Content-Type: text/html; charset=utf-8");

error_reporting(~(E_WARNING | E_NOTICE));

$doc = new DOMDocument('1.0');
$xsl = new XSLTProcessor();
$xsl->registerPHPFunctions();

$doc->load("mache_doc_group_to_html.xsl");
$xsl->importStyleSheet($doc);
$xsl->setParameter('', 'working_group', (isset($_GET["group"]) ? $_GET["group"] : ''));
$xsl->setParameter('', 'external_services_basepath', '..');
$xsl->setParameter('', 'navigation_base', '?');

$doc = new DOMDocument('1.0');
$result = $xsl->transformToXML($doc);

print $result;

?>