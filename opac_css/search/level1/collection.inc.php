<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: collection.inc.php,v 1.20 2009-12-05 14:10:19 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// premier niveau de recherche OPAC sur collections

if ($opac_search_other_function) require_once($include_path."/".$opac_search_other_function);

// on regarde comment la saisie utilisateur se présente
$aq=new analyse_query(stripslashes($user_query));
$members=$aq->get_query_members("collections","collection_name","index_coll","collection_id");
$clause="";
if ($typdoc) $clause.=",notices ";
$clause.= "where ".$members["where"];

if ($opac_search_other_function) $add_notice=search_other_function_clause($clause);
if (($add_notice)&&(!$typdoc)) $clause=",notices ".$clause;
if (($typdoc)||($add_notice)) $clause.=" and coll_id=collection_id";
if ($typdoc) $clause.=" and typdoc='".$typdoc."' ";

$tri = "ORDER BY pert, index_coll";
$pert=$members["select"]." as pert";

$collections = mysql_query("SELECT COUNT(distinct collection_id) FROM collections $clause", $dbh);
$nb_result_collections = mysql_result($collections, 0 , 0);

//Enregistrement des stats
if($pmb_logs_activate){
	global $nb_results_tab;
	$nb_results_tab['collections'] = $nb_result_collections;
}

if($nb_result_collections) {
	// tout bon, y'a du résultat, on lance le pataquès d'affichage
	$requete = "select collection_id,collection_name from collections $clause $tri LIMIT $opac_search_results_first_level";
	// ??? ER : $found = mysql_query($requete, $dbh);
	print "<div style=search_result id=\"collection\" name=\"collection\">";
	print "<strong>$msg[collections]</strong> ".$nb_result_collections." $msg[results] ";

	/* while($mesCollections = mysql_fetch_object($found)) {
		print "<li><a href=index.php?lvl=coll_see&id=".$mesCollections->collection_id.">".$mesCollections->collection_name."</a></li>\n";
		} */
	// si il y a d'autres résultats, je met le lien 'plus de résultats' 
	$form = "<form name=\"search_collection\" action=\"./index.php?lvl=more_results\" method=\"post\">\n";
	$form .= "<input type=\"hidden\" name=\"user_query\" value=\"".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."\">\n";
	if (function_exists("search_other_function_post_values")){ $form .=search_other_function_post_values(); }
	$form .= "<input type=\"hidden\" name=\"mode\" value=\"collection\">";
	$form .= "<input type=\"hidden\" name=\"count\" value=\"".$nb_result_collections."\">\n";
	$form .= "<input type=\"hidden\" name=\"clause\" value=\"".htmlentities($clause,ENT_QUOTES,$charset)."\">\n";
	$form .= "<input type=\"hidden\" name=\"tri\" value=\"".htmlentities($tri,ENT_QUOTES,$charset)."\">\n";
	$form .= "<input type=\"hidden\" name=\"pert\" value=\"".htmlentities($pert,ENT_QUOTES,$charset)."\">\n";
	$form .= "</form></div>\n";
	print "<a href=\"javascript:document.forms['search_collection'].submit()\">$msg[suite]&nbsp;<img src='./images/search.gif' border='0' align='absmiddle'/></a><br />";
	print $form;
	}
