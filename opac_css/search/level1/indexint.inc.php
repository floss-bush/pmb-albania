<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: indexint.inc.php,v 1.20 2009-12-05 14:10:19 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if ($opac_search_other_function) require_once($include_path."/".$opac_search_other_function);

$aq=new analyse_query(stripslashes($user_query));
$members=$aq->get_query_members("indexint","concat(indexint_name,' ',indexint_comment)","index_indexint","indexint_id");

// contrôle du nombre de résultats à afficher en premier niveau (6 par défaut)
if(!$opac_search_results_first_level) $opac_search_results_first_level=6;

$clause="";
if ($typdoc) $clause.=",notices ";
$clause.= "where ".$members["where"];

if ($opac_search_other_function) $add_notice=search_other_function_clause($clause);
if (($add_notice)&&(!$typdoc)) $clause=",notices ".$clause;
if (($typdoc)||($add_notice)) $clause.=" and indexint=indexint_id";
if ($typdoc) $clause.=" and typdoc='".$typdoc."' ";

$tri = "ORDER BY pert, index_indexint ";
$pert=$members["select"]." as pert";

$indexint = mysql_query("SELECT COUNT(distinct indexint_id) FROM indexint $clause", $dbh);
$nb_result_indexint = mysql_result($indexint, 0 , 0);

//Enregistrement des stats
if($pmb_logs_activate){
	global $nb_results_tab;
	$nb_results_tab['indexint'] = $nb_result_indexint;
}

if($nb_result_indexint) {
	// tout bon, y'a du résultat, on lance le pataquès d'affichage
	print "<div style=search_result id=\"collection\" name=\"collection\">";
	print "<strong>$msg[indexint]</strong> ".$nb_result_indexint." $msg[results] ";
	$requete = "select indexint_id,indexint_name,indexint_comment from indexint $clause $tri LIMIT $opac_search_results_first_level";
	// ??? ER : $found = mysql_query($requete, $dbh);
	/*
	print "<UL>";
	while($mesCategories = mysql_fetch_object($found)) {
		print '<li>';
		$categ_lien = $mesCategories->indexint_id ;
		print "<a href=./index.php?lvl=indexint_see&id=".$categ_lien."><img src='./images/folder.gif' border='0'> ".$mesCategories->indexint_name." ".$mesCategories->indexint_comment."</a>";
		print "</li>";
		// afficher les autres renseignements de la catégorie liée
		print 'id:'.$mesCategories->categ_id.' parent:'.$mesCategories->categ_parent.' see:'.$mesCategories->categ_see;
		}
	print "</UL>";	
	*/
	// si il y a d'autres résultats, je met le lien 'plus de résultats'
	// Le lien validant le formulaire est inséré dans le code avant le formulaire, cela évite les blancs à l'écran
	print "<a href=\"javascript:document.forms['search_indexint'].submit()\">$msg[suite]&nbsp;<img src='./images/search.gif' border='0' align='absmiddle'/></a>";
	$form = "<form name=\"search_indexint\" action=\"./index.php?lvl=more_results\" method=\"post\">";
	$form .= "<input type=\"hidden\" name=\"user_query\" value=\"".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."\">\n";
	if (function_exists("search_other_function_post_values")){ $form .=search_other_function_post_values(); }
	$form .= "<input type=\"hidden\" name=\"mode\" value=\"indexint\">\n";
	$form .= "<input type=\"hidden\" name=\"count\" value=\"".$nb_result_indexint."\">\n";
	$form .= "<input type=\"hidden\" name=\"clause\" value=\"".htmlentities($clause,ENT_QUOTES,$charset)."\">\n";
	$form .= "<input type=\"hidden\" name=\"pert\" value=\"".htmlentities($pert,ENT_QUOTES,$charset)."\">\n";
	$form .= "<input type=\"hidden\" name=\"tri\" value=\"".htmlentities($tri,ENT_QUOTES,$charset)."\"></form></div>\n";
	print $form;
	}
