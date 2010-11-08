<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: supprbase.inc.php,v 1.3 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($idemprcaddie) {
	$myCart= new empr_caddie($idemprcaddie);
	print aff_empr_cart_titre ($myCart);
	switch ($action) {
		case 'choix_quoi':
			print aff_empr_cart_nb_items ($myCart) ;
			print aff_empr_choix_quoi ("./circ.php?categ=caddie&sub=action&quelle=supprbase&action=del_base&idemprcaddie=$idemprcaddie", "./circ.php?categ=caddie&sub=action&quelle=supprbase&action=&idemprcaddie=0", $msg["caddie_choix_supprbase"], $msg["supprimer"], "return confirm('$msg[caddie_confirm_supprbase]')");
			break;
		case 'del_base':
			print "<br /><h3>$msg[caddie_situation_before_suppr]</h3>";
			print aff_empr_cart_nb_items ($myCart) ;
			$res_aff_suppr_base = "" ;
			if ($elt_flag) {
				$liste = $myCart->get_cart("FLAG") ;
				while(list($cle, $object) = each($liste)) {
					if ($myCart->del_item_base($object)==CADDIE_ITEM_SUPPR_BASE_OK) $myCart->del_item_all_caddies ($object) ;
					else  { 
						$res_aff_suppr_base .= aff_cart_unique_object ($object, $myCart->type, $url_base="./circ.php?categ=caddie&sub=gestion&quoi=panier&idemprcaddie=$idemprcaddie" ) ;
					}
				}
			}
			if ($elt_no_flag) {
				$liste = $myCart->get_cart("NOFLAG") ;
				while(list($cle, $object) = each($liste)) {
					if ($myCart->del_item_base($object)==CADDIE_ITEM_SUPPR_BASE_OK) $myCart->del_item_all_caddies ($object) ;
					else  { 
						$res_aff_suppr_base .= aff_cart_unique_object ($object, $myCart->type, $url_base="./circ.php?categ=caddie&sub=gestion&quoi=panier&idemprcaddie=$idemprcaddie" ) ;
					}
				}
			}
			if ($res_aff_suppr_base) {
				print "<br /><h3>$msg[caddie_supprbase_elt_used]</h3>";
				// inclusion du javascript de gestion des listes dépliables
				// début de liste
				print $begin_result_liste;
				print $res_aff_suppr_base ;
				print $end_result_liste;
			}
			print "<br /><h3>$msg[caddie_situation_after_suppr]</h3>";
			$myCart->compte_items();
			print aff_empr_cart_nb_items ($myCart) ;
			aff_empr_cart_objects ($idemprcaddie, "./circ.php?categ=caddie&sub=gestion&quoi=panier&idemprcaddie=$idemprcaddie" );
			break;
		default:
			break;
	}

} else aff_paniers_empr($idemprcaddie, "./circ.php?categ=caddie&sub=action&quelle=supprbase", "choix_quoi", $msg["caddie_select_supprbase"], "", 0, 0, 0);
