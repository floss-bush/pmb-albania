<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: supprpanier.inc.php,v 1.8 2009-05-16 11:11:51 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($idcaddie) {
	$myCart= new caddie($idcaddie);
	print aff_cart_titre ($myCart);
	switch ($action) {
		case 'choix_quoi':
			print aff_cart_nb_items ($myCart) ;
			print aff_choix_quoi ("./catalog.php?categ=caddie&sub=action&quelle=supprpanier&action=del_cart&idcaddie=$idcaddie", "./catalog.php?categ=caddie&sub=action&quelle=supprpanier&action=&idcaddie=0", $msg["caddie_choix_supprpanier"], $msg["caddie_act_vider_le_panier"],"return confirm('$msg[caddie_confirm_supprpanier]')");
			break;
		case 'del_cart':
			print "<br /><h3>$msg[caddie_situation_before_suppr]</h3>";
			print aff_cart_nb_items ($myCart) ;
			if ($elt_flag) $myCart->del_item_flag($elt_flag_inconnu);
			if ($elt_no_flag) $myCart->del_item_no_flag($elt_no_flag_inconnu);
			print "<br /><h3>$msg[caddie_situation_after_suppr]</h3>";
			print aff_cart_nb_items ($myCart) ;
			break;
		default:
			break;
		}

	} else aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=action&quelle=supprpanier", "choix_quoi", $msg["caddie_select_supprpanier"], "", 0, 0, 0);
