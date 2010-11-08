<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: titres_uniformes_list.inc.php,v 1.4 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// nombre de références par pages
if ($nb_per_page_titre_uniforme != "") 
	$nb_per_page = $nb_per_page_titre_uniforme ;
	else $nb_per_page = 10;

// traitement de la saisie utilisateur
include("$include_path/marc_tables/$lang/empty_words");
require_once($class_path."/analyse_query.class.php");

if($user_input)
	//a priori pas utile. Armelle
	$clef = reg_diacrit($user_input);

// $titres_uniformes_list_tmpl : template pour la liste auteurs
$titres_uniformes_list_tmpl = "
<br />
<br />
<div class='row'>
	<h3><! --!!nb_autorite_found!!-- >".$msg["aut_titre_uniforme_result"]." !!cle!! </h3>
	</div>
	<table>
		!!list!!
	</table>
<div class='row'>
	!!nav_bar!!
	</div>
";

	// on récupére le nombre de lignes qui vont bien
if(!$nbr_lignes) {
	if(!$user_input) {
		$requete = "SELECT count(1) FROM titres_uniformes ";
		if ($last_param) 
			$requete = "SELECT count(1) FROM titres_uniformes ".$tri_param." ".$limit_param;
		} else {
			$aq=new analyse_query(stripslashes($user_input),0,0,1,1);
		if ($aq->error) {
			titre_uniforme::search_form();
			error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
			exit;
		}
		$requete=$aq->get_query_count("titres_uniformes","tu_name","index_tu","tu_id");
	}
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = mysql_result($res, 0, 0);
	 
} else $aq=new analyse_query(stripslashes($user_input),0,0,1,1);

if(!$page) $page=1;
$debut =($page-1)*$nb_per_page;

if($nbr_lignes) {
	$titres_uniformes_list_tmpl=str_replace( "<! --!!nb_autorite_found!!-- >",$nbr_lignes.' ',$titres_uniformes_list_tmpl);
	$url_base = "$PHP_SELF?categ=titres_uniformes&sub=reach&user_input=".rawurlencode(stripslashes($user_input)) ;
	
	// on lance la vraie requête
	if(!$user_input) {
		$requete = "SELECT * FROM titres_uniformes ";
		$requete .= "ORDER BY index_tu LIMIT $debut,$nb_per_page ";
		if ($last_param) $requete = "SELECT * FROM titres_uniformes ".$tri_param." ".$limit_param;
	} else {
		$members=$aq->get_query_members("titres_uniformes","tu_name","index_tu","tu_id");
		$requete = "select *, ".$members["select"]." as pert from titres_uniformes where ".$members["where"]." group by tu_id order by pert desc, index_tu limit $debut,$nb_per_page";
	}

	$res = @mysql_query($requete, $dbh);
	$parity=1;
	while(($titre_uniforme=mysql_fetch_object($res))) {
		$tire_uniforme_entry = $titre_uniforme->tu_name;		
		$link_titre_uniforme = "./autorites.php?categ=titres_uniformes&sub=titre_uniforme_form&id=$titre_uniforme->tu_id&user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page";
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		$parity += 1;
		
		$notice_count_sql = "SELECT count(*) FROM notices_titres_uniformes WHERE ntu_num_tu = ".$titre_uniforme->tu_id;
		$notice_count = mysql_result(mysql_query($notice_count_sql), 0, 0);
		
	    $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\"  ";
        $titre_uniforme_list .= "
        <tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
         	<td valign='top' onmousedown=\"document.location='$link_titre_uniforme';\">
				$tire_uniforme_entry
			</td>";
		if($notice_count && $notice_count!=0)
			$titre_uniforme_list .=  "<td onmousedown=\"document.location='./catalog.php?categ=search&mode=9&etat=aut_search&aut_type=titre_uniforme&aut_id=$titre_uniforme->tu_id'\">".$notice_count."</td>";
		else $titre_uniforme_list .= "<td>&nbsp;</td>";	
		$titre_uniforme_list .=  "</tr>";
			
	} // fin while

	mysql_free_result($res);

	if (!$last_param) $nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
        else $nav_bar = "";
		
	// affichage du résultat
	list_titres_uniformes($user_input, $titre_uniforme_list, $nav_bar);

} else {
	// la requête n'a produit aucun résultat
	titre_uniforme::search_form();
	error_message($msg[211], str_replace('!!author_cle!!', stripslashes($user_input), $msg["aut_titre_uniforme_no_result"]), 0, './autorites.php?categ=titres_uniformes&sub=&id=');
}

function list_titres_uniformes($cle, $titre_uniforme_list, $nav_bar) {
	global $titres_uniformes_list_tmpl;
	global $charset ;
	
	$titres_uniformes_list_tmpl = str_replace("!!cle!!", htmlentities(stripslashes($cle),ENT_QUOTES, $charset), $titres_uniformes_list_tmpl);
	$titres_uniformes_list_tmpl = str_replace("!!list!!", $titre_uniforme_list, $titres_uniformes_list_tmpl);
	$titres_uniformes_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $titres_uniformes_list_tmpl);
	titre_uniforme::search_form();
	print pmb_bidi($titres_uniformes_list_tmpl);
}

