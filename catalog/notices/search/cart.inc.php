<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cart.inc.php,v 1.35 2009-12-07 15:33:58 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// inclusions principales
require_once("$class_path/caddie.class.php");
require_once("$class_path/serials.class.php");
require_once("$class_path/emprunteur.class.php") ;
require_once("$class_path/sort.class.php");
require_once("$include_path/cart.inc.php");
require_once("$include_path/templates/cart.tpl.php");
require_once("$include_path/expl_info.inc.php");
require_once("$include_path/bull_info.inc.php");

$selector_prop = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";
$cart_click_bull = "onClick=\"openPopUp('./print_cart.php?action=print_prepare&object_type=BULL&item=!!item!!', 'cart', 500, 400, -2, -2, '$selector_prop')\"";
$cart_click_expl = "onClick=\"openPopUp('./print_cart.php?action=print_prepare&object_type=EXPL&item=!!item!!', 'cart', 500, 400, -2, -2, '$selector_prop')\"";

switch ($action) {
	case 'new_cart':
		$cart_form = str_replace('!!autorisations_users!!', aff_form_autorisations("",1), $cart_form);
		$cart_form = str_replace('!!formulaire_action!!', "./catalog.php?categ=search&mode=3&action=valid_new_cart&item=$item", $cart_form);
		$cart_form = str_replace('!!formulaire_annuler!!', "./catalog.php?categ=search&mode=3&action=&item=$item", $cart_form);
		$select_cart="
		<select name='cart_type'>
			<option value='NOTI' selected>$msg[caddie_de_NOTI]</option>
			<option value='EXPL'>$msg[caddie_de_EXPL]</option>
			<option value='BULL'>$msg[caddie_de_BULL]</option>
		</select>
		<input type='hidden' name='current_print' value='$current_print'/>";
	 	$cart_form=str_replace('!!cart_type_select!!', $select_cart, $cart_form);
		print $cart_form ;
		break;
	case 'del_cart':
		$myCart= new caddie($idcaddie);
		$myCart->delete();
		aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=search&mode=3", "add_item", $msg['caddie_select_afficher'], "", 0, 1, 1);
		break;
	case 'del_item':
		$myCart= new caddie($idcaddie);
		$myCart->del_item($item);
		print "<div class=\"row\"><b>Panier&nbsp;: ".$myCart->name.' ('.$myCart->type.')</b></div>';
		//aff_cart_notices($myCart->get_cart(), $myCart->type, $idcaddie);
		aff_cart_objects ($idcaddie, "./catalog.php?categ=search&mode=3&idcaddie=$idcaddie", true );
		break;
	case 'valid_new_cart':
		$myCart = new caddie(0);
		$myCart->name = pmb_preg_replace('/\"|\'/', ' ', stripslashes($cart_name));
		$myCart->type = $cart_type;
		$myCart->comment = pmb_preg_replace('/\"|\'/', ' ', stripslashes($cart_comment));
		if (is_array($cart_autorisations)) $autorisations=implode(" ",$cart_autorisations);
		else $autorisations="";
		$myCart->autorisations = $autorisations;
		
		$myCart->create_cart();
		aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=search&mode=3", "add_item", $msg['caddie_select_afficher'], "", 0, 1, 1);
		break;
	default:
		if($idcaddie) {
			//Historique
			$myCart = new caddie($idcaddie);
			if ($page=="") {
				$_SESSION["CURRENT"]=count($_SESSION["session_history"]);
				$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["NOLINK"]=true;
				$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]=$myCart->name;
				$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_TITLE"]=sprintf($msg["histo_cart"],$myCart->type);
				$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]=array();
				$_POST["page"]=1;
				$page=1;
			}
			if ($_SESSION["CURRENT"]!==false) {
				$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["URI"]="catalog.php?categ=search&mode=3&action=add_item&object_type=NOTI&idcaddie=1&item=";
				$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["GET"]=$_GET;
				$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["POST"]=$_POST;
				$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["PAGE"]=$page;
				$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["HUMAN_QUERY"]=$msg["histo_cart_alone"]." : ".$myCart->name;
				$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["SEARCH_TYPE"]="cart";
				$_SESSION["session_history"][$_SESSION["CURRENT"]]["NOTI"]["NOPRINT"]=true;
			}
			$lien = "./catalog.php?categ=caddie&sub=gestion&quoi=panier&action=&object_type=".$myCart->type."&idcaddie=".$myCart->idcaddie."&item=0";
			print pmb_bidi("<div class=\"row\"><b>".$msg[caddie_intro]." <a href='".$lien."'>".$myCart->name.'</a> ('.$myCart->type.')</b></div>');
			//aff_cart_notices($myCart->get_cart(), $myCart->type, $idcaddie);
			aff_cart_objects ($idcaddie, "./catalog.php?categ=search&mode=3&idcaddie=$idcaddie", true, true );
		} else aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=search&mode=3", "add_item", $msg["caddie_select_afficher"], "", 0, 1, 1);
	}

// affichage du contenu du caddie à partir de $liste qui contient les object_id
function aff_cart_notices($liste, $caddie_type, $idcaddie=0) {
	global $msg;
	global $dbh;
	global $begin_result_liste;
	global $end_result_liste;
	global $page, $nbr_lignes, $nb_per_page;
	
	//Calcul des variables pour la suppression d'items
	if($nb_per_page){
		$modulo = $nbr_lignes%$nb_per_page;
		if($modulo == 1){
			$page_suppr = (!$page ? 1 : $page-1);
		} else {
			$page_suppr = $page;
		}	
		$nb_after_suppr = ($nbr_lignes ? $nbr_lignes-1 : 0);	
	}
	
	if(!sizeof($liste) || !is_array($liste)) {
		print $msg[399];
		return;
	} else {
		// en fonction du type de caddie on affiche ce qu'il faut
		if ($caddie_type=="NOTI") {
			// boucle de parcours des notices trouvées
			// inclusion du javascript de gestion des listes dépliables
			// début de liste
			print $begin_result_liste;
			while(list($cle, $notice) = each($liste)) {
				// affichage de la liste des notices sous la forme 'expandable'
				$requete = "SELECT * FROM notices WHERE notice_id=$notice LIMIT 1";
				$fetch = mysql_query($requete, $dbh);
				if(mysql_num_rows($fetch)) {
					$notice = mysql_fetch_object($fetch);
					if($notice->niveau_biblio != 's' && $notice->niveau_biblio != 'a') {
						// notice de monographie
						$link = './catalog.php?categ=isbd&id=!!id!!';
						$link_expl = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!'; 
						$link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!'; 
						$lien_suppr_cart = "<a href='./catalog.php?categ=search&mode=3&action=del_item&object_type=NOTI&idcaddie=$idcaddie&item=$notice->notice_id&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title='".$msg[caddie_icone_suppr_elt]."' /></a>";
						$display = new mono_display($notice, 6, $link, 1, $link_expl, $lien_suppr_cart, $link_explnum,1, 0, 1, 1);
						print pmb_bidi($display->result);
					} else {
						// on a affaire à un périodique
						// préparation des liens pour lui
						$link_serial = './catalog.php?categ=serials&sub=view&serial_id=!!id!!';
						$link_analysis = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!';
						$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!';
						$lien_suppr_cart = "<a href='./catalog.php?categ=search&mode=3&action=del_item&object_type=NOTI&idcaddie=$idcaddie&item=$notice->notice_id&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title='".$msg[caddie_icone_suppr_elt]."' /></a>";
						// function serial_display ($id, $level='1', $action_serial='', $action_analysis='', $action_bulletin='', $lien_suppr_cart="", $lien_explnum="", $bouton_explnum=1,$print=0,$show_explnum=1, $show_statut=0, $show_opac_hidden_fields=true, $draggable=0 ) {
						$serial = new serial_display($notice, 6, $link_serial, $link_analysis, $link_bulletin, $lien_suppr_cart, "", 1, 0, 1, 1, true, 1);
						print pmb_bidi($serial->result);
					}
				}
			} // fin de liste
			print $end_result_liste;
		} // fin si NOTI
		// si EXPL
		if ($caddie_type=="EXPL") {
			// boucle de parcours des exemplaires trouvés
			// inclusion du javascript de gestion des listes dépliables
			// début de liste
			print $begin_result_liste;
			while(list($cle, $expl) = each($liste)) {
				if($stuff = get_expl_info($expl)) {
					$stuff->lien_suppr_cart = "<a href='./catalog.php?categ=search&mode=3&action=del_item&object_type=EXPL&idcaddie=$idcaddie&item=$expl&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title='".$msg[caddie_icone_suppr_elt]."' /></a>";
					$stuff = check_pret($stuff);
					print pmb_bidi(print_info($stuff,0,1));
				} else {
						print "<strong>$form_cb_expl&nbsp;: ${msg[395]}</strong>";
				}
			} // fin de liste
			print $end_result_liste;
		} // fin si EXPL
		if ($caddie_type=="BULL") {
			// boucle de parcours des bulletins trouvés
			// inclusion du javascript de gestion des listes dépliables
			// début de liste
			print $begin_result_liste;
			while(list($cle, $expl) = each($liste)) {
				if($bull_aff = show_bulletinage_info($expl)) {
					$javascript_template ="
						<div id=\"el!!id!!Parent\" class=\"notice-parent\">
    						<img src=\"./images/plus.gif\" class=\"img_plus\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['admin_param_detail']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\">
    						<span class=\"notice-heada\">!!heada!!</span>
    						<br />
						</div>
						<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">
        				   		!!CONTENU!!
 						</div>";
					$lien_suppr_cart = "<a href='./catalog.php?categ=search&mode=3&action=del_item&object_type=EXPL&idcaddie=$idcaddie&item=$expl&page=$page_suppr&nbr_lignes=$nb_after_suppr&nb_per_page=$nb_per_page'><img src='./images/basket_empty_20x20.gif' align='middle' alt='basket' title='".$msg[caddie_icone_suppr_elt]."' /></a>";
					$aff = str_replace('!!id!!', $expl, $javascript_template);
					$aff = str_replace('!!unique!!', md5(microtime()), $aff);
					$aff = str_replace('!!heada!!', $lien_suppr_cart.$bull_aff->header, $aff);
					$aff = str_replace('!!CONTENU!!', $bull_aff->display, $aff);
					print pmb_bidi($aff);
				} else {
					print "<strong>$form_cb_expl&nbsp;: ${msg[395]}</strong>";
				}
			} // fin de liste
			print $end_result_liste;
		} // fin si BULL
	}
}
