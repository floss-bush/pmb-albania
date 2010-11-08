<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: supprpanier.inc.php,v 1.2 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($idemprcaddie) {
	$myCart= new empr_caddie($idemprcaddie);
	print aff_empr_cart_titre ($myCart);
	switch ($action) {
		case 'choix_quoi':
			print aff_empr_cart_nb_items ($myCart) ;
			print aff_empr_choix_quoi ("./circ.php?categ=caddie&sub=action&quelle=supprpanier&action=del_cart&idemprcaddie=$idemprcaddie", "./circ.php?categ=caddie&sub=action&quelle=supprpanier&action=&idemprcaddie=0", $msg["caddie_choix_supprpanier"], $msg["caddie_act_vider_le_panier"],"return confirm('$msg[caddie_confirm_supprpanier]')");
			break;
		case 'del_cart':
			print "<br /><h3>$msg[caddie_situation_before_suppr]</h3>";
			print aff_empr_cart_nb_items ($myCart) ;
			if ($elt_flag) $myCart->del_item_flag();
			if ($elt_no_flag) $myCart->del_item_no_flag();
			print "<br /><h3>$msg[caddie_situation_after_suppr]</h3>";
			print aff_empr_cart_nb_items ($myCart) ;
			break;
		default:
			break;
		}

	} else aff_paniers_empr($idemprcaddie, "./circ.php?categ=caddie&sub=action&quelle=supprpanier", "choix_quoi", $msg["caddie_select_supprpanier"], "", 0, 0, 0);
