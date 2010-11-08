<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serials_coll.inc.php,v 1.21 2009-05-16 11:08:24 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// inclusion du template de gestion des périodiques
require_once("$include_path/templates/serials.tpl.php");
require_once("./catalog/serials/serial_func.inc.php");
require_once("$class_path/category.class.php");
require_once("$class_path/serials.class.php");
require_once("$class_path/serial_display.class.php");
require_once("$class_path/editor.class.php");
require_once("$class_path/author.class.php");
require_once("$class_path/indexint.class.php");
require_once("$class_path/analyse_query.class.php");

$base_url = "./catalog.php?categ=serials&sub=search&user_query=$user_query";

if (!$user_query) $user_query ="*" ;

$serial_edit_access = str_replace('!!message!!',$msg[1914] , $serial_edit_access);
$serial_edit_access = str_replace('!!etat!!',collect , $serial_edit_access);

print $serial_edit_access;

// nombre de références par pages
if ($nb_per_page_empr != "")
	$nb_per_page = $nb_per_page_empr ;
	else $nb_per_page = 10;


// comptage du nombre de résultats
$where="";
if ($user_query) {
	$aq=new analyse_query(stripslashes($user_query));
	if ($aq->error) {
		error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
		exit();
	}
	$members=$aq->get_query_members("notices","index_wew","index_sew","notice_id");
	$where.=$members["where"]." and ";
}
$where.="niveau_biblio='s' AND niveau_hierar='1'";
$count_query = mysql_query("SELECT count(distinct notice_id) FROM notices WHERE $where", $dbh);
$nbr_lignes = mysql_result ($count_query, 0, 0);

if(!$page) $page=1;
$debut =($page-1)*$nb_per_page;

if($nbr_lignes) {

	$myQuery = mysql_query("SELECT *,".$members["select"]." as pert FROM notices WHERE ".$where." group by notice_id ORDER BY pert desc,index_sew LIMIT $debut,$nb_per_page_a_search", $dbh);

	$affichage_final = "<br /><table class='hidelink'>";
	
	while($myPerio=mysql_fetch_object($myQuery)) {
		$class_entete = "even";
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$class_entete'\" ";
		$url = "./catalog.php?categ=serials&sub=view&serial_id=".$myPerio->notice_id; 
		$isbd = new serial_display($myPerio->notice_id, 1);

		// lien d'ajout d'une notice mère à un caddie
		$selector_prop = "toolbar=no, dependent=yes,resizable=yes, scrollbars=yes";
		$cart_click_noti = "onClick=\"openPopUp('./cart.php?object_type=NOTI&item=".$myPerio->notice_id."', 'cart', 600, 700, -2, -2, '".$selector_prop."')\"";

		$affichage_final .= "<tr class='$class_entete' $tr_javascript >";
		$affichage_final .= "<td><img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title='".$msg[400]."' ".$cart_click_noti."></td>";
		$affichage_final .= "<td colspan='6'><a href='".$url."'>".$isbd->isbd."</a></td></tr>";
		
   		// affichage des bulletinages associés
		$myQueryBull = mysql_query("SELECT bulletin_id FROM bulletins WHERE bulletin_notice='$myPerio->notice_id' ORDER BY date_date DESC, bulletin_id DESC ", $dbh);
		$bulletins="";
		while($bul = mysql_fetch_object($myQueryBull)) {
			$class_suite="odd";
        		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$class_suite'\"";
				$bulletin = new bulletinage($bul->bulletin_id);
				$url =  "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=".$bul->bulletin_id;

				// gestion des paniers de bulletins
				$selector_prop = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";
				$cart_click_bull = "onClick=\"openPopUp('./cart.php?object_type=BULL&item=".$bul->bulletin_id."', 'cart', 600, 700, -2, -2, '$selector_prop')\"";

				$bulletins .= "<tr class='$class_suite' $tr_javascript>
				<td>&nbsp;</td>
				<td><img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title='".$msg[400]."' ".$cart_click_bull."></td>
				<td><a href='".$url."'>".$bulletin->bulletin_numero."</a></td>
				<td>".$bulletin->mention_date."</td>
				<td>".$bulletin->aff_date_date."</td>
				<td>".$bulletin->bulletin_titre."</td>";
			if (sizeof($bulletin->expl)) $bulletins .= "<td>".sizeof($bulletin->expl)." ".$msg['bulletin_nb_exemplaires']."</td>";
				else $bulletins .= "<td>&nbsp;</td>";
				
			$bulletins .= "</tr>";
       			}
		$affichage_final .= $bulletins ;
		}

	$affichage_final .= "</table>";
	print pmb_bidi($affichage_final) ;
	} else {
		// la requête n'a produit aucun résultat
		error_message($msg[46], str_replace('!!user_query!!', stripslashes($user_query), $msg[1153]), 1, './edit.php?categ=serials&sub=collect');
		}
