<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: indexint_list.inc.php,v 1.30 2009-05-16 11:12:00 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function list_indexint($cle, $indexint_list, $nav_bar) {
	global $indexint_list_tmpl;
	global $charset,$id_pclass;
	$indexint_list_tmpl = str_replace("!!cle!!", $cle, $indexint_list_tmpl);
	$indexint_list_tmpl = str_replace("!!list!!", $indexint_list, $indexint_list_tmpl);
	$indexint_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $indexint_list_tmpl);

	indexint::search_form($id_pclass);
	print pmb_bidi($indexint_list_tmpl);
}

// nombre de références par pages
if ($nb_per_page_serie != "") 
	$nb_per_page = $nb_per_page_serie ;
	else $nb_per_page = 10;

// traitement de la saisie utilisateur
include("$include_path/marc_tables/$lang/empty_words");
require_once($class_path."/analyse_query.class.php");

if($user_input)
	//a priori pas utile. Armelle
	$clef = reg_diacrit($user_input);


// $indexint_list_tmpl : template pour la liste
$indexint_list_tmpl = "
<br />
<br />
<div class='row'>
	<h3><! --!!nb_autorite_found!!-- >$msg[indexint_found] !!cle!! </h3>
	</div>
	<table>
		!!list!!
	</table>
<div class='row'>
	!!nav_bar!!
	</div>";

if ($thesaurus_classement_mode_pmb != 0) { //la liste des pclassement n'est pas affichée en mode monopclassement
	//Post de $id_pclass;
	if($id_pclass==0) {
		//tout voir
		$pclass_req=" and id_pclass = num_pclass ";
		$pclass_req_where=" WHERE id_pclass = num_pclass ";
		$pclass_and_req=" and id_pclass = num_pclass ";
		$pclass_req_and=" id_pclass = num_pclass and ";
		$pclass_url="";	
	} else {
		// Voir qu'un seul plan de classement	
		$pclass_req=" id_pclass='$id_pclass' and id_pclass = num_pclass ";
		$pclass_req_where=" WHERE id_pclass='$id_pclass' and id_pclass = num_pclass ";
		$pclass_and_req=" and id_pclass='$id_pclass' and id_pclass = num_pclass ";
		$pclass_req_and=" id_pclass='$id_pclass' and id_pclass = num_pclass and ";
		$pclass_url="&id_pclass=$id_pclass";
	}	
} else {
	$pclass_req_where=" WHERE id_pclass='$thesaurus_classement_defaut' and id_pclass = num_pclass ";
	$pclass_req=" id_pclass='$thesaurus_classement_defaut' and id_pclass = num_pclass ";
	$pclass_req_where=" WHERE id_pclass='$thesaurus_classement_defaut' and id_pclass = num_pclass ";
	$pclass_and_req=" and id_pclass='$thesaurus_classement_defaut' and id_pclass = num_pclass ";
	$pclass_req_and=" id_pclass='$thesaurus_classement_defaut' and id_pclass = num_pclass  and ";
	$pclass_url="&id_pclass=$thesaurus_classement_defaut";	
}
// on récupére le nombre de lignes qui vont bien
if(!$nbr_lignes) {
	if(!$user_input) {
		$requete = "SELECT count(1) FROM indexint,pclassement $pclass_req_where";
		if ($last_param) 
			$requete = "SELECT count(1) FROM indexint,pclassement $pclass_req_where ".$tri_param." ".$limit_param;
	} else {
		if (!$exact) {
			$aq=new analyse_query(stripslashes($user_input));
			if ($aq->error) {
				indexint::search_form($id_pclass);
				error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
				exit;
			}
			$requete=$aq->get_query_count("indexint","concat(indexint_name,' ',indexint_comment)","index_indexint","indexint_id");
		} else {
			$requete="select count(distinct indexint_id) from indexint,pclassement where $pclass_req_and indexint_name like '".str_replace("*","%",$user_input)."' order by indexint_name";
		}
	}
	$res = mysql_query($requete, $dbh) or die (mysql_error()."<br /><br />$requete");
	$nbr_lignes = mysql_result($res, 0, 0);
} else {
	if (!$exact) $aq=new analyse_query(stripslashes($user_input));	
}

if(!$page) $page=1;
$debut =($page-1)*$nb_per_page;



if($nbr_lignes) {
	$indexint_list_tmpl=str_replace( "<! --!!nb_autorite_found!!-- >",$nbr_lignes.' ',$indexint_list_tmpl);
	// on lance la vraie requête
	if(!$user_input) {
		$requete = "SELECT * FROM indexint,pclassement $pclass_req_where $pclass_and_req ORDER BY indexint_name,name_pclass LIMIT $debut,$nb_per_page ";
		if ($last_param) $requete = "SELECT * FROM indexint,pclassement $pclass_req_where ".$tri_param." ".$limit_param;
	} else {
		if (!$exact) {
			$members=$aq->get_query_members("indexint","concat(indexint_name,' ',indexint_comment)","index_indexint","indexint_id");
			$requete="select *,".$members["select"]." as pert from indexint,pclassement where $pclass_req_and ".$members["where"]." group by indexint_id order by pert desc, index_indexint limit $debut,$nb_per_page";
		} else $requete="select * from indexint,pclassement where $pclass_req_and indexint_name like '".str_replace("*","%",$user_input)."' group by indexint_id order by indexint_name,name_pclass limit $debut,$nb_per_page";
	}
	$res = @mysql_query($requete, $dbh);
	$parity=1;
	$url_base = "$PHP_SELF?categ=indexint&sub=reach$pclass_url&user_input=".rawurlencode(stripslashes($user_input))."&exact=$exact" ;
	while(($indexint=mysql_fetch_object($res))) {
		if ($parity % 2) $pair_impair = "even"; else $pair_impair = "odd";
		$parity += 1;
		
		$notice_count_sql = "SELECT count(*) FROM notices WHERE indexint = ".$indexint->indexint_id;
		$notice_count = mysql_result(mysql_query($notice_count_sql), 0, 0);
		
        $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\"  ";
        
    	if($thesaurus_classement_mode_pmb!=0){
			$pclass_name="[".$indexint->name_pclass."] ";
    	}	
    
    	$indexint_list.= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
		    		<td valign='top' onmousedown=\"document.location='./autorites.php?categ=indexint&sub=indexint_form&id=$indexint->indexint_id&user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page&exact=$exact$pclass_url';\">
				$pclass_name 
				".htmlentities($indexint->indexint_name,ENT_QUOTES, $charset)."
				</td>
		    		<td valign='top' onmousedown=\"document.location='./autorites.php?categ=indexint&sub=indexint_form&id=$indexint->indexint_id&user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page&exact=$exact$pclass_url';\">
				".htmlentities($indexint->indexint_comment,ENT_QUOTES, $charset)."
				</td>";
		if($notice_count && $notice_count!=0)
			 $indexint_list .= "<td onmousedown=\"document.location='./catalog.php?categ=search&mode=1&etat=aut_search&aut_type=indexint&aut_id=$indexint->indexint_id'\">".$notice_count."</td>";
		else $indexint_list .= "<td>&nbsp;</td>";
		$indexint_list .= "</tr>";	
	}
	mysql_free_result($res);
	
	if (!$last_param) $nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
        else $nav_bar = "";
        
	// affichage du résultat
	if ($user_input) {
		if ($exact)
			$c_user_input= $msg["rech_exacte"];
		else
			$c_user_input=$msg["rech_commentaire"];
	}
	list_indexint($user_input.$c_user_input, $indexint_list, $nav_bar);

} else {
	// la requête n'a produit aucun résultat
	indexint::search_form($id_pclass);
	error_message($msg["indexint_search"], str_replace('!!titre_cle!!', stripslashes($user_input), $msg["indexint_noresult"]), 0, './autorites.php?categ=indexint&sub=&id=');
}
