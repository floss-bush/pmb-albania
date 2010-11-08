<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: collections_list.inc.php,v 1.27 2009-05-16 11:12:04 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//collections_list.inc : liste les éditeurs correspondants à la regex user_input
// affichage de la liste collections pour sélection

// nombre de références par pages
if ($nb_per_page_collection != "") 
	$nb_per_page = $nb_per_page_collection ;
	else $nb_per_page = 10;

// initialisation variables
$nav_bar = '';
$collection_list = '';

// traitement de la saisie utilisateur

include("$include_path/marc_tables/$lang/empty_words");
require_once($class_path."/analyse_query.class.php");

if($user_input)
	//a priori pas utile. Armelle
	$clef = reg_diacrit($user_input);

// $collection_list_tmpl : template pour la liste editeurs
$collection_list_tmpl = "
<br />
<br />
<div class='row'>
	<h3><! --!!nb_autorite_found!!-- >$msg[173] !!cle!! </h3>
	</div>
	<table>
		!!list!!
	</table>
<div class='row'>
	!!nav_bar!!
	</div>
";

function list_collection($coll, $collection_list, $nav_bar) {
	global $collection_list_tmpl;
	global $charset;
	$collection_list_tmpl = str_replace("!!cle!!", $coll, $collection_list_tmpl);
	$collection_list_tmpl = str_replace("!!list!!", $collection_list, $collection_list_tmpl);
	$collection_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $collection_list_tmpl);
	collection::search_form();
	print pmb_bidi($collection_list_tmpl);
	}

// on récupére le nombre de lignes qui vont bien
if(!$nbr_lignes) {
	if(!$user_input) {
		$requete = "SELECT count(1) FROM collections $tri_param ";
		if ($last_param) $requete = "SELECT count(1) FROM collections ".$tri_param." ".$limit_param;
		} else {
			$aq=new analyse_query(stripslashes($user_input));
			if ($aq->error) {
				collection::search_form();
				error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
				exit;
			}
			$requete = $aq->get_query_count("collections","collection_name","index_coll","collection_id");
			}
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = mysql_result($res, 0, 0);
	} else $aq=new analyse_query(stripslashes($user_input));

if(!$page) $page=1;
$debut =($page-1)*$nb_per_page;

if($nbr_lignes) {
	$collection_list_tmpl=str_replace( "<! --!!nb_autorite_found!!-- >",$nbr_lignes.' ',$collection_list_tmpl);	
	// on lance la vraie requête
	if(!$user_input) {
		$requete = "SELECT * FROM collections A left join publishers B on ed_id=collection_parent ";
		$requete .= " ORDER BY index_coll LIMIT $debut,$nb_per_page ";
		if ($last_param) $requete = "SELECT * FROM collections A left join publishers B on ed_id=collection_parent ".$tri_param." ".$limit_param;
		} else {
			$members=$aq->get_query_members("collections","collection_name","index_coll","collection_id");
			$requete="select collections.*, publishers.*, ".$members["select"]." as pert from collections left join publishers on ed_id=collection_parent where ".$members["where"]." group by collection_id order by pert desc,index_coll,index_publisher limit $debut,$nb_per_page";
			}
	$res = @mysql_query($requete, $dbh);
	$parity=1;
	$url_base = "$PHP_SELF?categ=collections&sub=reach&user_input=".rawurlencode(stripslashes($user_input)) ;
	while(($coll=mysql_fetch_object($res))) {
		if ($parity % 2) {
			$pair_impair = "even";
			} else {
				$pair_impair = "odd";
				}
		$parity += 1;

		$notice_count_sql = "SELECT count(*) FROM notices WHERE coll_id = ".$coll->collection_id;
		$notice_count = mysql_result(mysql_query($notice_count_sql), 0, 0);
		
	        $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\"  ";
                $collection_list.= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
                 			<td valign='top' onmousedown=\"document.location='./autorites.php?categ=collections&sub=collection_form&id=$coll->collection_id&user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page';\">
					".htmlentities($coll->collection_name,ENT_QUOTES, $charset);
		if($coll->collection_parent)
			$collection_list .= "&nbsp;($coll->ed_name)";
		$collection_list .= "</td>
							<td>".htmlentities($coll->collection_issn,ENT_QUOTES, $charset)."</td>";
		if($notice_count && $notice_count!=0)
			$collection_list .=  "<td onmousedown=\"document.location='./catalog.php?categ=search&mode=2&etat=aut_search&aut_type=collection&aut_id=$coll->collection_id'\">".$notice_count."</td>";
		else $collection_list .= "<td>&nbsp;</td>";
		$collection_list .=  "</tr>";
	} // fin while
	mysql_free_result($res);
	
	if (!$last_param) $nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
		else $nav_bar="";   
		     	
	// affichage du résultat
	list_collection($user_input, $collection_list, $nav_bar);

	} else {
		// la requête n'a produit aucun résultat
		collection::search_form();
		error_message($msg[175], str_replace('!!cle!!', stripslashes($user_input), $msg[174]), 0, './autorites.php?categ=collections&sub=&id=');
		}


