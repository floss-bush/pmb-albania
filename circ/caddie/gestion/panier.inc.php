<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: panier.inc.php,v 1.2 2010-01-21 16:22:36 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch ($action) {
	case 'new_cart':
		$empr_cart_form = str_replace('!!autorisations_users!!', aff_form_autorisations("",1), $empr_cart_form);
		$empr_cart_form = str_replace('!!formulaire_action!!', "./circ.php?categ=caddie&sub=gestion&quoi=panier&action=valid_new_cart&item=$item", $empr_cart_form);
		$empr_cart_form = str_replace('!!formulaire_annuler!!', "./circ.php?categ=caddie&sub=gestion&quoi=panier&action=&item=$item", $empr_cart_form);
		print $empr_cart_form ;
		break;
	case 'edit_cart':
		$myCart= new empr_caddie($idemprcaddie);
		$empr_cart_edit_form = str_replace('!!formulaire_action!!', "./circ.php?categ=caddie&sub=gestion&quoi=panier&action=save_cart&item=$item&idemprcaddie=$idemprcaddie", $empr_cart_edit_form);
		$empr_cart_edit_form = str_replace('!!formulaire_annuler!!', "./circ.php?categ=caddie&sub=gestion&quoi=panier&action=&item=$item", $empr_cart_edit_form);
		$empr_cart_edit_form = str_replace('!!idemprcaddie!!', $idemprcaddie, $empr_cart_edit_form);
		$empr_cart_edit_form = str_replace('!!name!!', htmlentities($myCart->name,ENT_QUOTES, $charset), $empr_cart_edit_form);
		$empr_cart_edit_form = str_replace('!!name_suppr!!', htmlentities(addslashes($myCart->name),ENT_QUOTES, $charset), $empr_cart_edit_form);
		$empr_cart_edit_form = str_replace('!!comment!!', htmlentities($myCart->comment,ENT_QUOTES, $charset), $empr_cart_edit_form);
		$empr_cart_edit_form = str_replace('!!autorisations_users!!', aff_form_autorisations($myCart->autorisations,0), $empr_cart_edit_form);
		print confirmation_delete("./circ.php?categ=caddie&action=del_cart&idemprcaddie=");
		print $empr_cart_edit_form ;
		break;
	case 'del_cart':
		$myCart= new empr_caddie($idemprcaddie);
		$myCart->delete();
		aff_paniers_empr($idemprcaddie, "./circ.php?categ=caddie&sub=gestion&quoi=panier", "", $msg["caddie_select_afficher"], "", 1, 0, 1);
		break;
	case 'save_cart':
		$myCart= new empr_caddie($idemprcaddie);
		if (is_array($cart_autorisations)) $autorisations=implode(" ",$cart_autorisations);
				else $autorisations="1";
		$myCart->autorisations = $autorisations;
		$myCart->name = $cart_name;
		$myCart->comment = $cart_comment;
		if($form_actif) $myCart->save_cart();
		aff_paniers_empr($idemprcaddie, "./circ.php?categ=caddie&sub=gestion&quoi=panier", "", $msg["caddie_select_afficher"], "", 1, 0, 1);
		break;
	case 'del_item':
		$myCart= new empr_caddie($idemprcaddie);
		$myCart->del_item($item);
		print aff_empr_cart_titre ($myCart);
		print aff_empr_cart_nb_items ($myCart) ;
		aff_empr_cart_objects ($idemprcaddie, "./circ.php?categ=caddie&sub=gestion&quoi=panier&idemprcaddie=$idemprcaddie" );
		break;
	case 'valid_new_cart':
		$myCart = new empr_caddie(0);
		$myCart->name = $cart_name;
		$myCart->comment = $cart_comment;
		if (is_array($cart_autorisations)) $autorisations=implode(" ",$cart_autorisations);
				else $autorisations="";
		$myCart->autorisations = $autorisations;
		if($form_actif) $myCart->create_cart();
		aff_paniers_empr($idemprcaddie, "./circ.php?categ=caddie&sub=gestion&quoi=panier", "", $msg["caddie_select_afficher"], "", 1, 0, 1);
		break;
	default:
		if($idemprcaddie) {
			$myCart = new empr_caddie($idemprcaddie);
			print aff_empr_cart_titre ($myCart);
			print aff_empr_cart_nb_items ($myCart);
			aff_empr_cart_objects ($idemprcaddie, "./circ.php?categ=caddie&sub=gestion&quoi=panier&idemprcaddie=$idemprcaddie" );
			} else aff_paniers_empr($idemprcaddie, "./circ.php?categ=caddie&sub=gestion&quoi=panier", "", $msg["caddie_select_afficher"], "", 1, 0, 1);
	}
