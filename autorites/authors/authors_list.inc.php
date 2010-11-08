<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: authors_list.inc.php,v 1.34 2009-05-16 11:12:03 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// nombre de références par pages
if ($nb_per_page_author != "") 
	$nb_per_page = $nb_per_page_author ;
	else $nb_per_page = 10;

// traitement de la saisie utilisateur
include("$include_path/marc_tables/$lang/empty_words");
require_once($class_path."/analyse_query.class.php");

if($user_input)
	//a priori pas utile. Armelle
	$clef = reg_diacrit($user_input);

//On teste à quelle type d'autorités on a affaire pour les traitements suivants
switch($type_autorite){
	
	case 70 :
		//personne physique
		$id_type = "WHERE author_type='70' ";
		$val_type = "AND author_type='70' ";
		$libelleResult = $msg[209];
		break;
		
	case 71 :
		//collectivité
		$id_type="WHERE author_type='71' ";
		$val_type = "AND author_type='71' ";
		$libelleResult = $msg["aut_resul_collectivite"];
		break;
		
	case 72 :
		//congrès
		$id_type="WHERE author_type='72' ";
		$val_type = "AND author_type='72' ";
		$libelleResult = $msg["aut_resul_congres"];
		break;
	
	default:
		$id_type='';
		$val_type='';
		$libelleResult = $msg[209];
		break;
}	
	
// $authors_list_tmpl : template pour la liste auteurs
$authors_list_tmpl = "
<br />
<br />
<div class='row'>
	<h3><! --!!nb_autorite_found!!-- >$libelleResult !!cle!! </h3>
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
		$requete = "SELECT count(1) FROM authors ".$id_type; 
		if ($last_param) 
			$requete = "SELECT count(1) FROM authors ".$tri_param." ".$limit_param;
	} else {
		$aq=new analyse_query(stripslashes($user_input),0,0,1,1);
		if ($aq->error) {
			auteur::search_form($type_autorite);
			error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
			exit;
		}
		$requete=$aq->get_query_count("authors","concat(author_name,', ',author_rejete)","index_author","author_id");
		$requete.= $val_type;
	}
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = mysql_result($res, 0, 0);
} else $aq=new analyse_query(stripslashes($user_input),0,0,1,1);

if(!$page) $page=1;
$debut =($page-1)*$nb_per_page;

if($nbr_lignes) {
	$authors_list_tmpl=str_replace( "<! --!!nb_autorite_found!!-- >",$nbr_lignes.' ',$authors_list_tmpl);
	$url_base = "$PHP_SELF?categ=auteurs&sub=reach&user_input=".rawurlencode(stripslashes($user_input));
	
	// on lance la vraie requête
	if(!$user_input) {
		$requete = "SELECT * FROM authors ".$id_type;
		$requete .= "ORDER BY index_author LIMIT $debut,$nb_per_page ";
		if ($last_param) $requete = "SELECT * FROM authors ".$tri_param." ".$limit_param;
	} else {
		$members=$aq->get_query_members("authors","concat(author_name,', ',author_rejete)","index_author","author_id");
		$requete = "select *, ".$members["select"]." as pert from authors where ".$members["where"]." ".$val_type." group by author_id order by pert desc, index_author limit $debut,$nb_per_page";
	}
	$res = @mysql_query($requete, $dbh);
	$parity=1;
	while(($author=mysql_fetch_object($res))) {
		$aut = new auteur($author->author_id,1);
		$author_entry=$aut->isbd_entry;
		$link_auteur = "./autorites.php?categ=auteurs&sub=author_form&id=$author->author_id&user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page";
		if($author->author_see) {
			// auteur avec renvoi
			// récupération des données de l'auteur cible
			$temp_requete = "SELECT * FROM authors WHERE author_id=$author->author_see LIMIT 1 ";
			$temp_res = mysql_query($temp_requete, $dbh);
			$see = mysql_fetch_object($temp_res);
	
			if($see->author_rejete) $author_voir = $see->author_name.',&nbsp;'.$see->author_rejete;
				else $author_voir = $see->author_name;
	
			if($see->author_date)
				$author_voir .= "&nbsp;($see->author_date)";
	
			$author_voir = "<a href='./autorites.php?categ=auteurs&sub=author_form&id=$see->author_id&user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page'>$author_voir</a>";
			$author_entry .= ".&nbsp;-&nbsp;<u>$msg[210]</u>&nbsp;:&nbsp;".$author_voir;
		}
		
		$notice_count_sql = "SELECT count(distinct responsability_notice) FROM responsability WHERE responsability_author = ".$author->author_id;
		$notice_count = mysql_result(mysql_query($notice_count_sql), 0, 0);
			
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		$parity += 1;
	    $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
        $author_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer' > 
              			<td valign='top' onmousedown=\"document.location='$link_auteur';\" title='".$aut->info_bulle."'>
						$author_entry
						</td>";
		if($notice_count && $notice_count!=0) 
			$author_list .= "<td onmousedown=\"document.location='./catalog.php?categ=search&mode=0&etat=aut_search&aut_id=$author->author_id';\">".($notice_count)."</td>";
		else $author_list .= "<td>&nbsp;</td>";					
		$author_list .= "</tr>";
			
	} // fin while

	mysql_free_result($res);

	$url_base = $url_base."&type_autorite=".$type_autorite;
	if (!$last_param) $nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
        else $nav_bar = "";
		
	// affichage du résultat
	list_authors($user_input, $author_list, $nav_bar,$type_autorite);

	} else {
		// la requête n'a produit aucun résultat
		auteur::search_form($type_autorite);
		error_message($msg[211], str_replace('!!author_cle!!', stripslashes($user_input), $msg[212]), 0, './autorites.php?categ=auteurs&sub=&id=');
		}

function list_authors($cle, $author_list, $nav_bar,$type_autorite) {
	global $authors_list_tmpl;
	global $charset ;	
	$authors_list_tmpl = str_replace("!!cle!!", htmlentities(stripslashes($cle),ENT_QUOTES, $charset), $authors_list_tmpl);
	$authors_list_tmpl = str_replace("!!list!!", $author_list, $authors_list_tmpl);
	$authors_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $authors_list_tmpl);
	auteur::search_form($type_autorite);
	print pmb_bidi($authors_list_tmpl);
	}

