<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: subcollection.inc.php,v 1.19 2009-12-05 14:10:19 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if ($opac_search_other_function) require_once($include_path."/".$opac_search_other_function);

// on regarde comment la saisie utilisateur se présente
$aq=new analyse_query(stripslashes($user_query));
$members=$aq->get_query_members("sub_collections","sub_coll_name","index_sub_coll","sub_coll_id");

$clause="";
if ($typdoc) $clause.=",notices ";
$clause.= "WHERE ".$members["where"];

if ($opac_search_other_function) $add_notice=search_other_function_clause($clause);
if (($add_notice)&&(!$typdoc)) $clause=",notices ".$clause;
if (($typdoc)||($add_notice)) $clause.=" and subcoll_id=sub_coll_id";
if ($typdoc) $clause.=" and typdoc='".$typdoc."' ";

$tri = "ORDER BY pert,index_sub_coll";
$pert=$members["select"]." as pert";

$subcollections = mysql_query("SELECT COUNT(sub_coll_id) FROM sub_collections $clause", $dbh);
$nb_result_subcollections = mysql_result($subcollections, 0 , 0); 

//Enregistrement des stats
if($pmb_logs_activate){
	global $nb_results_tab;
	$nb_results_tab['subcollections'] = $nb_result_subcollections;
}

if ($nb_result_subcollections) {
	// tout bon, y'a du résultat, on lance le pataquès d'affichage
	$requete = "select sub_coll_id,sub_coll_name from sub_collections $clause $tri LIMIT $opac_search_results_first_level";
	// ??? ER : $found = mysql_query($requete, $dbh);
	print "<div style=search_result id=\"subcollection\" name=\"subcollection\">";
	print "<strong>$msg[subcollections]</strong> ".$nb_result_subcollections." $msg[results] ";
	/*while($mesSubCollections = mysql_fetch_object($found)) {
		print "<li><a href=index.php?lvl=subcoll_see&id=".$mesSubCollections->sub_coll_id.">".$mesSubCollections->sub_coll_name."</a></li>\n";
		} */
	// si il y a d'autres résultats, je met le lien 'plus de résultats'
	$form = "<div style=search_result><form name=\"search_sub_collection\" action=\"./index.php?lvl=more_results\" method=\"post\">\n";
	$form .= "<input type=\"hidden\" name=\"user_query\" value=\"".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."\">\n";
	if (function_exists("search_other_function_post_values")){ $form .=search_other_function_post_values(); }
	$form .= "<input type=\"hidden\" name=\"mode\" value=\"souscollection\">\n";
	$form .= "<input type=\"hidden\" name=\"count\" value=\"".$nb_result_subcollections."\">\n";
	$form .= "<input type=\"hidden\" name=\"clause\" value=\"".htmlentities($clause,ENT_QUOTES,$charset)."\">\n";
	$form .= "<input type=\"hidden\" name=\"pert\" value=\"".htmlentities($pert,ENT_QUOTES,$charset)."\">\n";
	$form .= "<input type=\"hidden\" name=\"tri\" value=\"".htmlentities($tri,ENT_QUOTES,$charset)."\"></form></div>\n";
	print "<a href=\"javascript:document.forms['search_sub_collection'].submit()\">$msg[suite] <img src='./images/search.gif' border='0' align='absmiddle'/></a>\n";
	print $form;
	print "</div>";
}
