<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: external.inc.php,v 1.11 2009-12-05 14:10:19 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// second niveau de recherche OPAC sur titre
// inclusion classe pour affichage notices (level 1)
require_once($base_path.'/includes/templates/notice.tpl.php');
require_once($base_path.'/classes/notice.class.php');
require_once($class_path."/search.class.php");

global $external_sources;
$selected_sources = implode(',', $field_0_s_2);

if ($_SESSION["ext_type"]=="multi")
	$es=new search("search_fields_unimarc");
else
	$es=new search("search_simple_fields_unimarc");

$es->show_results_unimarc("./index.php?lvl=more_results&mode=external","./index.php?search_type_asked=external_search&external_type=simple", true);

//Enregistrement des stats
if($pmb_logs_activate){
	global $nb_results_tab;
	$nb_results_tab['external'] = $count;
}