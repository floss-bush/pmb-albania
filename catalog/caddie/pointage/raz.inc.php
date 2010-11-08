<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: raz.inc.php,v 1.3 2007-03-10 09:03:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if ($idcaddie) {
	$myCart = new caddie($idcaddie);
	print pmb_bidi(aff_cart_titre ($myCart));
	$droit = verif_droit_caddie($idcaddie) ;
	if ($droit) $myCart->depointe_items();
	print pmb_bidi(aff_cart_nb_items ($myCart)) ;
	} else aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=pointage&moyen=raz", "", $msg[caddie_pointage_raz], "", 0, 0, 0);

