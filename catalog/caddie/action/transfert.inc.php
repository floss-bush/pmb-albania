<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: transfert.inc.php,v 1.9 2008-04-17 09:13:53 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($idcaddie) {	
	$myCart= new caddie($idcaddie);
	switch ($action) {
		case 'transfert':
			print pmb_bidi(aff_cart_titre ($myCart));
			print aff_cart_nb_items ($myCart) ;
			aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=action&quelle=transfert&idcaddie_origine=$idcaddie", "transfert_suite", $msg["caddie_select_transfert_dest"], "", 0, 0, 0,true);
			break;
		case 'transfert_suite':
			$idcaddie_origine = verif_droit_caddie($idcaddie_origine) ;
			if ($idcaddie_origine) {
				$myCartOrigine= new caddie($idcaddie_origine);
				// procédure d'ajout
				print pmb_bidi(aff_cart_titre ($myCartOrigine));
				print aff_cart_nb_items ($myCartOrigine) ;
				// le caddie d'origine est BULL, le caddie destination est NOTI, il fait afficher le choix de notice de bulletin ou notices de dépouillement
				if ($myCart->type=='NOTI' && $myCartOrigine->type=='BULL') $aff_choix_dep = true;
				else $aff_choix_dep = false ;
				print aff_choix_quoi("./catalog.php?categ=caddie&sub=action&quelle=transfert&action=transfert_final&idcaddie=$idcaddie&idcaddie_origine=$idcaddie_origine", "./catalog.php?categ=caddie&sub=action&quelle=transfert&action=&idcaddie=", $msg["caddie_choix_transfert"], $msg["caddie_bouton_transferer"], "", $aff_choix_dep);
				print pmb_bidi(aff_cart_titre ($myCart));
				print aff_cart_nb_items ($myCart) ;
				}
			break;
		case 'transfert_final':
			$idcaddie_origine = verif_droit_caddie($idcaddie_origine) ;
			if ($idcaddie_origine) {
				$myCartOrigine= new caddie($idcaddie_origine);
				print pmb_bidi(aff_cart_titre ($myCart));
				print aff_cart_nb_items ($myCart) ;
				if ($myCart->type=='NOTI' && $myCartOrigine->type=='BULL') {
					// cas du transfert depuis caddie de BULL vers caddie de notices
					if ($bull_not) {
						// transfert des notices de bulletin
						if ($elt_flag) {
							$liste = $myCartOrigine->get_cart("FLAG") ;
							while(list($cle, $object) = each($liste)) {
								$myCart->add_item($object, $myCartOrigine->type) ;
							}
						}
						if ($elt_no_flag) {
							$liste = $myCartOrigine->get_cart("NOFLAG") ;
							while(list($cle, $object) = each($liste)) {
								$myCart->add_item($object, $myCartOrigine->type) ;
							}
						}
					}
					if ($bull_dep) {
						// transfert des notices de dépouillement
						if ($elt_flag) {
							$liste = $myCartOrigine->get_cart("FLAG") ;
							while(list($cle, $object) = each($liste)) {
								$myCart->add_item($object, $myCartOrigine->type, "DEP") ;
							}
						}
						if ($elt_no_flag) {
							$liste = $myCartOrigine->get_cart("NOFLAG") ;
							while(list($cle, $object) = each($liste)) {
								$myCart->add_item($object, $myCartOrigine->type, "DEP") ;
							}
						}
					}		
				} else {
					// on est dans le cas "normal"
					if ($elt_flag) {
						$liste = $myCartOrigine->get_cart("FLAG") ;
						while(list($cle, $object) = each($liste)) {
							$myCart->add_item($object, $myCartOrigine->type) ;
						}
					}
					if ($elt_no_flag) {
						$liste = $myCartOrigine->get_cart("NOFLAG") ;
						while(list($cle, $object) = each($liste)) {
							$myCart->add_item($object, $myCartOrigine->type) ;
						}
					}
				}					
				$myCart->compte_items();
				// procédure d'ajout
				echo "<h3>".$msg[empr_caddie_menu_action_apres_transfert]."</h3>";
				print aff_cart_nb_items ($myCart) ;
				}
			break;
		default:
			break;
		}

	} else aff_paniers($item, "NOTI", "./catalog.php?categ=caddie&sub=action&quelle=transfert", "transfert", $msg["caddie_select_transfert"], "", 0, 0, 0);
