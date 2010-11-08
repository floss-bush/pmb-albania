<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: mailing.inc.php,v 1.1 2007-07-15 11:22:40 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($idemprcaddie) {
	$myCart= new empr_caddie($idemprcaddie);
	print aff_empr_cart_titre ($myCart);
	print aff_empr_cart_nb_items ($myCart) ;
	switch ($action) {
		case 'envoi':
			print "<iframe name='mailing_empr' frameborder='0' scrolling='yes' width='100%' height='700' src='./circ/caddie/action/mailing.php?idemprcaddie=$idemprcaddie&sub=redige'>
				<noframes>
				</noframes>" ;
			break;
		default:
			break;
		}

	} else aff_paniers_empr($idemprcaddie, "./circ.php?categ=caddie&sub=action&quelle=mailing", "envoi", $msg["empr_caddie_select_mailing"], "", 0, 0, 0);

