<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cart.inc.php,v 1.15 2008-11-27 15:54:26 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de switch paniers

// inclusions principales
require_once("$class_path/caddie.class.php");
require_once("$include_path/cart.inc.php");
require_once("$include_path/templates/cart.tpl.php");

switch ($action) {
	case 'new_cart':
		$cart_form = str_replace('!!autorisations_users!!', aff_form_autorisations("",1), $cart_form);
		$cart_form = str_replace('!!formulaire_action!!', "./circ.php?categ=resa&mode=3&unq=".md5(microtime())."&id_empr=$id_empr&groupID=$groupID&action=valid_new_cart&item=$item", $cart_form);
		$cart_form = str_replace('!!formulaire_annuler!!', "./circ.php?categ=resa&mode=3&unq=".md5(microtime())."&id_empr=$id_empr&groupID=$groupID&action=&item=$item", $cart_form);
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
		aff_paniers($idcaddie, "NOTI", "./circ.php?categ=resa&mode=3&unq=".md5(microtime())."&id_empr=$id_empr&groupID=$groupID", "add_item", "Sélectionnez un caddie pour en afficher le contenu", "NOTI", 0, 1, 1);
		break;
	case 'del_item':
		$myCart= new caddie($idcaddie);
		$myCart->del_item($item);
		print pmb_bidi("<div class=\"row\"><b>Panier&nbsp;: ".$myCart->name.' ('.$myCart->type.')</b></div>');
		aff_cart_notices($myCart->get_cart(), $myCart->type, $idcaddie);
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
		aff_paniers($idcaddie, "NOTI", "./circ.php?categ=resa&mode=3&unq=".md5(microtime())."&id_empr=$id_empr&groupID=$groupID", "add_item", "Sélectionnez un caddie pour en afficher le contenu", "NOTI", 0, 1, 1);
		break;
	default:
		if($idcaddie) {
			$myCart = new caddie($idcaddie);
			print pmb_bidi("<div class=\"row\"><b>Panier&nbsp;: ".$myCart->name.' ('.$myCart->type.')</b></div>');
			aff_cart_notices($myCart->get_cart(), $myCart->type, $idcaddie);
			} else aff_paniers($idcaddie, "NOTI", "./circ.php?categ=resa&mode=3&unq=".md5(microtime())."&id_empr=$id_empr&groupID=$groupID", "add_item", "Sélectionnez un caddie pour en afficher le contenu", "NOTI", 0, 1, 1);
	}

// affichage du contenu du caddie à partir de $liste qui contient les object_id
function aff_cart_notices($liste, $caddie_type="", $idcaddie=0) {
global $msg;
global $dbh;
global $begin_result_liste, $end_result_liste;
global $end_result_list;
global $id_empr;
global $groupID;

if(!sizeof($liste) || !is_array($liste)) {
	print $msg[399];
	return;
	} else {
		// boucle de parcours des notices trouvées
		// inclusion du javascript de gestion des listes dépliables
		// début de liste
		print $begin_result_liste;
		while(list($cle, $notice) = each($liste)) {
			// affichage de la liste des notices sous la forme 'expandable'
			$requete = "SELECT * FROM notices WHERE notice_id=$notice LIMIT 1";
			$fetch = $myQuery = mysql_query($requete, $dbh);
			if(mysql_num_rows($fetch)) {
				$notice = mysql_fetch_object($fetch);
				if($notice->niveau_biblio == 'm'){
					// notice de monographie
					$link = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_notice=!!id!!";
					$lien_suppr_cart = "";
					$display = new mono_display($notice, 6, $link, 1, '', $lien_suppr_cart, "", 1 );
					print pmb_bidi($display->result);
				} elseif($notice->niveau_biblio == 'b'){
					//bulletin
					$rqt_bull_info = "SELECT s.notice_id as id_notice_mere, bulletin_id as id_du_bulletin, b.notice_id as id_notice_bulletin FROM notices as s, notices as b, bulletins WHERE b.notice_id=$notice->notice_id and s.notice_id=bulletin_notice and num_notice=b.notice_id";
					$bull_ids=@mysql_fetch_object(mysql_query($rqt_bull_info));
					$link = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_bulletin=$bull_ids->id_du_bulletin";
					$display = new mono_display($notice, 6, $link, 1, '', $lien_suppr_cart, "", 1 );
					print pmb_bidi($display->result);
				} elseif($notice->niveau_biblio == 'a'){
					 //on a affaire à un périodique
					// préparation des liens pour lui
					$link_serial = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&mode=view_serial&serial_id=!!id!!";
					$link_analysis = '';
					$link_bulletin = "./circ.php?categ=resa&id_empr=$id_empr&groupID=$groupID&id_bulletin=!!id!!";
					$lien_suppr_cart = "";
					$serial = new serial_display($notice, 6, $link_serial, $link_analysis, $link_bulletin, $lien_suppr_cart, "", 0 );
					print pmb_bidi($serial->result);
				}
			}
		} // fin de liste
		print $end_result_liste;
		}
}
