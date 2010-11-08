<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: douchette.inc.php,v 1.10 2009-05-16 11:12:02 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($idcaddie) {
	$myCart = new caddie($idcaddie);
	print pmb_bidi(aff_cart_titre ($myCart));
	switch ($action) {
		case 'add_item':
			if($form_cb_expl) {
				$expl_ajout_ok = 1 ;
				$query = "select expl_id from exemplaires where expl_cb='$form_cb_expl'";
				$result = mysql_query($query, $dbh);
				if(!mysql_num_rows($result)) {
					// exemplaire inconnu
					$message_ajout_expl =  "<strong>$form_cb_expl&nbsp;: $msg[367]</strong>";
					$expl_ajout_ok = 0 ;
				} else {
					$expl_trouve = mysql_fetch_object($result);
					$item = $expl_trouve->expl_id;
					if($stuff = get_expl_info($item)) {
						$stuff = check_pret($stuff);
					} else {
						$message_ajout_expl = "<strong>$form_cb_expl&nbsp;: $msg[395]</strong>";
						$expl_ajout_ok = 0 ;
					}
				}
			}
			if ($expl_ajout_ok) $res_ajout = $myCart->add_item($item,"EXPL");
			else $res_ajout = $myCart->add_item_blob($form_cb_expl,"EXPL_CB" );
			$myCart->compte_items();
			print aff_cart_nb_items ($myCart) ;
			
			// form de saisie cb exemplaire
			print get_cb_expl($msg["caddie_add_expl"], $msg[661], "./catalog.php?categ=caddie&sub=collecte&moyen=douchette&action=add_item&idcaddie=$idcaddie", 1);
			if ($expl_ajout_ok) {
				if ($res_ajout==CADDIE_ITEM_OK) {
					print "<hr /><div class='row'><span class='erreur'>".$msg["caddie_".$myCart->type."_added"]."</span></div><hr />";
					print $begin_result_expl_liste_unique;
					print pmb_bidi(print_info($stuff,0,1)); 
				}
				if ($res_ajout==CADDIE_ITEM_NULL) {
					print "<hr /><div class='row'><span class='erreur'>$msg[caddie_item_null]</span></div><hr />";
				}
				if ($res_ajout==CADDIE_ITEM_IMPOSSIBLE_BULLETIN) {
					print "<hr /><div class='row'><span class='erreur'>$msg[caddie_item_impossible_bulletin]</span></div><hr />";
				}	
			} else print "<hr /><div class='row'><span class='erreur'>$message_ajout_expl</span></div><hr />" ;
			
			break;
		default:
			print aff_cart_nb_items ($myCart) ;
			// form de saisie cb exemplaire
			print get_cb_expl($msg["caddie_add_expl"], $msg[661], "./catalog.php?categ=caddie&sub=collecte&moyen=douchette&action=add_item&idcaddie=$idcaddie", 1);
			break;
		}

} else aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=collecte&moyen=douchette", "", $msg["caddie_select_ajouter"], "", 0, 0, 0);
