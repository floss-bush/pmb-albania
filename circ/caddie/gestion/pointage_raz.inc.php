<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pointage_raz.inc.php,v 1.1 2007-07-14 10:48:51 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if ($idemprcaddie) {
	$myCart = new empr_caddie($idemprcaddie);
	print aff_empr_cart_titre ($myCart);
	$droit = verif_droit_empr_caddie($idemprcaddie) ;
	if ($droit) $myCart->depointe_items();
	print aff_empr_cart_nb_items ($myCart) ;
	} else aff_paniers_empr($idemprcaddie, "./circ.php?categ=caddie&sub=gestion&quoi=razpointage&moyen=raz", "", $msg[caddie_pointage_raz], "", 0, 0, 0);

