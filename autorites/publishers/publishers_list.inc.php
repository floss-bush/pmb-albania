<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: publishers_list.inc.php,v 1.24 2009-05-26 20:40:58 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// nombre de références par pages
if ($nb_per_page_publisher != "") $nb_per_page = $nb_per_page_publisher ;
else $nb_per_page = 10;

// traitement de la saisie utilisateur

include("$include_path/marc_tables/$lang/empty_words");
require_once($class_path."/analyse_query.class.php");

if($user_input)
//a priori pas utile. Armelle
$clef = reg_diacrit($user_input);

// $ed_list_tmpl : template pour la liste editeurs
$ed_list_tmpl = "
<br />
<br />
<div class='row'>
	<h3><! --!!nb_autorite_found!!-- >$msg[154] !!cle!! </h3>
	</div>
	<table>
		!!list!!
	</table>
<div class='row'>
	!!nav_bar!!
	</div>
";

function list_ed($cb, $empr_list, $nav_bar) {
	global $ed_list_tmpl;
	$ed_list_tmpl = str_replace("!!cle!!", $cb, $ed_list_tmpl);
	$ed_list_tmpl = str_replace("!!list!!", $empr_list, $ed_list_tmpl);
	$ed_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $ed_list_tmpl);
	editeur::search_form();
	print pmb_bidi($ed_list_tmpl);
}

// on récupére le nombre de lignes qui vont bien
if(!$nbr_lignes) {
	if(!$user_input) {
		$requete = "SELECT count(1) FROM publishers ";
		if ($last_param) $requete = "SELECT count(1) FROM publishers ".$tri_param." ".$limit_param;
	} else {
		$aq=new analyse_query(stripslashes($user_input),0,0,1,1);
		if ($aq->error) {
			editeur::search_form();
			error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
			exit;
		}
		$requete=$aq->get_query_count("publishers","ed_name","index_publisher","ed_id");
	}
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = mysql_result($res, 0, 0);
} else 
	$aq=new analyse_query(stripslashes($user_input),0,0,1,1);

if(!$page) $page=1;
$debut =($page-1)*$nb_per_page;

if($nbr_lignes) {
	$ed_list_tmpl=str_replace( "<! --!!nb_autorite_found!!-- >",$nbr_lignes.' ',$ed_list_tmpl);
	// on lance la vraie requête
	if(!$user_input) {
		$requete = "SELECT * FROM publishers ORDER BY ed_name LIMIT $debut,$nb_per_page ";
		if ($last_param) $requete = "SELECT * FROM publishers ".$tri_param." ".$limit_param;
	} else {
		$members=$aq->get_query_members("publishers","ed_name","index_publisher","ed_id");
		$requete="select *,".$members["select"]." as pert from publishers where ".$members["where"]." group by ed_id order by pert desc, index_publisher limit $debut,$nb_per_page";
	}
	
	$res = @mysql_query($requete, $dbh);
	$parity=1;
	$url_base = "$PHP_SELF?categ=editeurs&sub=reach&user_input=".rawurlencode(stripslashes($user_input)) ;
	while(($ed=mysql_fetch_object($res))) {
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
				$pair_impair = "odd";
		}
		$parity += 1;
		
		$notice_count_sql = "SELECT count(*) FROM notices WHERE ed1_id=".$ed->ed_id;
		$notice_count1 = mysql_result(mysql_query($notice_count_sql), 0, 0);
		$notice_count_sql = "SELECT count(*) FROM notices WHERE ed2_id=".$ed->ed_id;
		$notice_count = $notice_count1+mysql_result(mysql_query($notice_count_sql), 0, 0);
		
	    $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
        $ed_list.= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
                	<td valign='top' onmousedown=\"document.location='./autorites.php?categ=editeurs&sub=editeur_form&id=$ed->ed_id&user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page';\">
					$ed->ed_name
					</td>
					<td valign='top' onmousedown=\"document.location='./autorites.php?categ=editeurs&sub=editeur_form&id=$ed->ed_id&user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page';\">
					$ed->ed_ville
					</td>
					<td align='right'>";
					
		if($ed->ed_web) $ed_list .= "<a href='$ed->ed_web' target='_new'>$ed->ed_web</a>";
			else $ed_list .= '&nbsp;';
			
		$ed_list .= "</td>"; 
		
		if($notice_count && $notice_count!=0)
			$ed_list .= "<td onmousedown=\"document.location='./catalog.php?categ=search&mode=2&etat=aut_search&aut_type=publisher&aut_id=$ed->ed_id'\">".$notice_count."</td>";
		else 
			$ed_list .= '<td>&nbsp;</td>';		
		$ed_list .= "</tr>";
			
	} // fin while
	mysql_free_result($res);
	
	if (!$last_param) $nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
	else $nav_bar = "";
		  	
	// affichage du résultat
	list_ed($user_input, $ed_list, $nav_bar);

} else {
	// la requête n'a produit aucun résultat
	editeur::search_form();
	error_message($msg[152], str_replace('!!ed_cle!!', stripslashes($user_input), $msg[153]), 0, './autorites.php?categ=editeurs&sub=&id=');
}
