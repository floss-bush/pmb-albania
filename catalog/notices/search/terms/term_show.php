<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: term_show.php,v 1.10 2007-07-28 07:04:29 touraine37 Exp $

$base_path="../../../..";                            
$base_auth = ""; 

require_once ("$base_path/includes/init.inc.php"); 
require_once("$class_path/term_show.class.php"); 
require_once ("$javascript_path/misc.inc.php");


//Récupération des paramètres du formulaire appellant
$base_query = "history=".rawurlencode(stripslashes($term));

echo $jscript_term;

function parent_link($categ_id,$categ_see) {
	global $charset;
	global $base_path;
	global $thesaurus_categories_show_empty_categ;
	
	if ($categ_see) $categ=$categ_see; else $categ=$categ_id;
	$tcateg =  new category($categ);
	if ($thesaurus_categories_show_empty_categ) 
		$visible=true;
	else
		$visible=false;
	if (($_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["SEARCH_TYPE"]!="term_search")&&($_SESSION["CURRENT"])) {
		$no_rec_history=1;
	} else $no_rec_history=0;
	if ($tcateg->has_notices()) {
		$link="<a href='".$base_path."/catalog.php?categ=search&mode=1&aut_id=$categ&etat=aut_search&aut_type=categ&no_rec_history=$no_rec_history' target=_top><img src='$base_path/images/search.gif' border=0 align='absmiddle'></a>";
		$visible=true;	
	}
	$r=array("VISIBLE"=>$visible,"LINK"=>$link);
	return $r;
}

if ($term) {
	if (($_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["SEARCH_TYPE"]=="term_search")&&($_SESSION["CURRENT"]!==false)) {
		$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"]["term_click"]=$term;
		$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]=$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY_START"].", page ".$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["PAGE"]." terme ".htmlentities(stripslashes($term),ENT_QUOTES,$charset);	
	}
}
if (!$first) { 
	$ts=new term_show(stripslashes($term), "term_show.php", $base_query, "parent_link", 0, $id_thes);
	echo $ts->show_notice();
}
?>