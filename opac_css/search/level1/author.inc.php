<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: author.inc.php,v 1.28 2009-12-05 14:10:19 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// premier niveau de recherche OPAC sur auteurs

// inclusion classe pour affichage auteur (level 1)
require_once($base_path.'/includes/templates/author.tpl.php');
require_once($base_path.'/classes/author.class.php');

if ($opac_search_other_function) require_once($include_path."/".$opac_search_other_function);

// on regarde comment la saisie utilisateur se présente

$aq=new analyse_query(stripslashes($user_query),0,0,1,1);
$clause="";
$members=$aq->get_query_members("authors","concat(author_name,', ',author_rejete)","index_author","author_id");
if ($typdoc) $clause.=",notices, responsability";
$clause.=" where ".$members["where"];

if ($opac_search_other_function) $add_notice=search_other_function_clause($clause);
if (($add_notice)&&(!$typdoc)) $clause=",notices, responsability ".$clause;
if (($typdoc)||($add_notice)) $clause.=" and responsability_author=author_id and notice_id=responsability_notice";
if ($typdoc) $clause.=" and typdoc='".$typdoc."' ";

$tri = "ORDER BY pert desc, index_author";
$pert=$members["select"]." as pert";

$auteurs = mysql_query("SELECT COUNT(distinct author_id) FROM authors $clause and author_type='70' ", $dbh);
$nb_result_auteurs_physiques = mysql_result($auteurs, 0 , 0); 
$auteurs = mysql_query("SELECT COUNT(distinct author_id) FROM authors $clause and author_type='71' ", $dbh);
$nb_result_auteurs_collectivites = mysql_result($auteurs, 0 , 0); 
$auteurs = mysql_query("SELECT COUNT(distinct author_id) FROM authors $clause and author_type='72' ", $dbh);
$nb_result_auteurs_congres = mysql_result($auteurs, 0 , 0); 
$nb_result_auteurs=$nb_result_auteurs_physiques+$nb_result_auteurs_collectivites+$nb_result_auteurs_congres;

//Enregistrement des stats
if($pmb_logs_activate){
	global $nb_results_tab;
	$nb_results_tab['auteurs'] = $nb_result_auteurs;
	$nb_results_tab['collectivites'] = $nb_result_auteurs_collectivites;
	$nb_results_tab['congres'] = $nb_result_auteurs_congres;
	$nb_results_tab['physiques'] = $nb_result_auteurs_physiques;
}

if ($nb_result_auteurs) {
	if($nb_result_auteurs_physiques == $nb_result_auteurs) {
		// Il n'y a que des auteurs physiques, affichage type: Auteurs xx résultat(s) afficher
		$titre_resume[0]=$msg["authors"];
		$nb_result_resume[0]=$nb_result_auteurs;
		$link_type_resume[0]="70";		
	} else if($nb_result_auteurs_collectivites == $nb_result_auteurs) {
		// Il n'y a que des collectivites, affichage type: Collectivités xx résultat(s) afficher
		$titre_resume[0]=$msg["collectivites_search"];
		$nb_result_resume[0]=$nb_result_auteurs;
		$link_type_resume[0]="71";		
	} else if($nb_result_auteurs_congres == $nb_result_auteurs) {
		// Il n'y a que des congres, affichage type: Collectivités xx résultat(s) afficher
		$titre_resume[0]=$msg["congres_search"];
		$nb_result_resume[0]=$nb_result_auteurs;
		$link_type_resume[0]="72";		
	} else {
		// il y a un peu de tout, affichage en titre type: Auteurs xx résultat(s) afficher
		$titre_resume[0]=$msg["authors"];
		$nb_result_resume[0]=$nb_result_auteurs;
		$link_type_resume[0]="";		
		
		if($nb_result_auteurs_physiques) {
		// Il n'y a des auteurs physiques, affichage en sous-titre titre: Auteurs physiques xx résultat(s) afficher
			$titre_resume[]=$msg["personnes_physiques_search"];
			$nb_result_resume[]=$nb_result_auteurs_physiques;
			$link_type_resume[]="70";		
		} 
		if($nb_result_auteurs_collectivites) {
			// Il n'y a des collectivites, affichage en sous-titre titre: Collectivités xx résultat(s) afficher
			$titre_resume[]=$msg["collectivites_search"];
			$nb_result_resume[]=$nb_result_auteurs_collectivites;
			$link_type_resume[]="71";					
		} 
		if($nb_result_auteurs_congres) {
			// Il n'y a des congres, affichage en sous-titre titre: Congrès xx résultat(s) afficher
			$titre_resume[]=$msg["congres_search"];
			$nb_result_resume[]=$nb_result_auteurs_congres;
			$link_type_resume[]="72";					
		}
	}
	print "<div style=search_result id=\"auteur\" name=\"auteur\">";
	for($i=0;$i<count($titre_resume);$i++)  {
		if($i==1) print "<blockquote>";
		print "<strong>$titre_resume[$i]</strong> ".$nb_result_resume[$i]." $msg[results] ";
		// Le lien validant le formulaire est inséré avant le formulaire, cela évite les blancs à l'écran
		if($link_type_resume[$i]) $clause_link=$clause." and author_type='".$link_type_resume[$i]."'";
		else $clause_link=$clause;
		print "<a href=\"javascript:		
			document.forms.search_authors.count.value='".$nb_result_resume[$i]."';
			document.forms.search_authors.clause.value='".htmlentities(addslashes($clause_link),ENT_QUOTES,$charset)."'; 
			document.forms.search_authors.author_type.value='$link_type_resume[$i]'; 			
			document.forms['search_authors'].submit();\">
			$msg[suite]&nbsp;<img src=./images/search.gif border='0' align='absmiddle'/></a>";
		print "<br />";
	}
	if($i>1) print "</blockquote>";
	
	// tout bon, y'a du résultat, on lance le pataquès d'affichage
	$requete = "select * from authors $clause $tri LIMIT $opac_search_results_first_level";
	$form = "<div style=search_result><form name=\"search_authors\" action=\"./index.php?lvl=more_results\" method=\"post\">\n";
	if (function_exists("search_other_function_post_values")){ $form .=search_other_function_post_values(); }
	$form .= "<input type=\"hidden\" name=\"user_query\" value=\"".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."\">\n";
	$form .= "<input type=\"hidden\" name=\"mode\" value=\"auteur\">\n";
	$form .= "<input type=\"hidden\" name=\"author_type\" value=\"\">\n";
	$form .= "<input type=\"hidden\" name=\"count\" value=\"".$nb_result_auteurs."\">\n";
	$form .= "<input type=\"hidden\" name=\"clause\" value=\"".htmlentities($clause,ENT_QUOTES,$charset)."\">\n";
	$form .= "<input type=\"hidden\" name=\"pert\" value=\"".htmlentities($pert,ENT_QUOTES,$charset)."\">\n";
	$form .= "<input type=\"hidden\" name=\"tri\" value=\"".htmlentities($tri,ENT_QUOTES,$charset)."\"></form></div>\n";
	print $form;
	print "</div>";
}

