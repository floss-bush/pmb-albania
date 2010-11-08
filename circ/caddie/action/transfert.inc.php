<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: transfert.inc.php,v 1.2 2008-01-06 14:07:37 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($idemprcaddie) {
	$myCart= new empr_caddie($idemprcaddie);
	switch ($action) {
		case 'transfert':
			print aff_empr_cart_titre ($myCart);
			print aff_empr_cart_nb_items ($myCart) ;
			aff_paniers_empr($idemprcaddie, "./circ.php?categ=caddie&sub=action&quelle=transfert&idemprcaddie_origine=$idemprcaddie", "transfert_suite", $msg["caddie_select_transfert_dest"], "", 0, 0, 0);
			break;
		case 'transfert_suite':
			$idemprcaddie_origine = verif_droit_empr_caddie($idemprcaddie_origine) ;
			if ($idemprcaddie_origine) {
				$myCartOrigine= new empr_caddie($idemprcaddie_origine);
				// procédure d'ajout
				print aff_empr_cart_titre ($myCartOrigine);
				print aff_empr_cart_nb_items ($myCartOrigine) ;
				print aff_empr_choix_quoi("./circ.php?categ=caddie&sub=action&quelle=transfert&action=transfert_final&idemprcaddie=$idemprcaddie&idemprcaddie_origine=$idemprcaddie_origine", "./circ.php?categ=caddie&sub=action&quelle=transfert&action=&idemprcaddie=", $msg["caddie_choix_transfert"], $msg["caddie_bouton_transferer"]);
				print aff_empr_cart_titre ($myCart);
				print aff_empr_cart_nb_items ($myCart) ;
				}
			break;
		case 'transfert_final':
			$idemprcaddie_origine = verif_droit_empr_caddie($idemprcaddie_origine) ;
			if ($idemprcaddie_origine) {
				$myCartOrigine= new empr_caddie($idemprcaddie_origine);
				print aff_empr_cart_titre ($myCart);
				print aff_empr_cart_nb_items ($myCart) ;
				if ($elt_flag) {
					$liste = $myCartOrigine->get_cart("FLAG") ;
					while(list($cle, $object) = each($liste)) {
						$myCart->add_item($object) ;
						}
					}
				if ($elt_no_flag) {
					$liste = $myCartOrigine->get_cart("NOFLAG") ;
					while(list($cle, $object) = each($liste)) {
						$myCart->add_item($object) ;
						}
					}
				// procédure d'ajout
				$myCart->compte_items();
				echo "<h3>".$msg[empr_caddie_menu_action_apres_transfert]."</h3>";
				print aff_empr_cart_nb_items ($myCart) ;
				}
			break;
		default:
			break;
		}

	} else aff_paniers_empr($item, "./circ.php?categ=caddie&sub=action&quelle=transfert", "transfert", $msg["caddie_select_transfert"], "", 0, 0, 0);
