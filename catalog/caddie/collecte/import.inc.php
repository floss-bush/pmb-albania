<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: import.inc.php,v 1.6 2008-01-06 14:07:36 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($idcaddie) {
	print pmb_bidi(aff_cart_titre ($myCart));
	switch ($action) {
		case 'add_item':
			$myCart->add_item($item,"EXPL");
			$myCart->compte_items();
			print aff_cart_nb_items ($myCart) ;
			break;
		default:
			print aff_cart_nb_items ($myCart) ;
			break;
		}

	} else aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=collecte&moyen=import", "", $msg["caddie_select_ajouter"], "", 0, 0, 0);
