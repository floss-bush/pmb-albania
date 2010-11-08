<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id$

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if ($idemprcaddie) {
	$myCart = new empr_caddie($idemprcaddie);
	print aff_empr_cart_titre ($myCart);
	switch ($action) {
		case 'add_item':
			if($form_cb) {
				if ($empr_location_id>0) $where = " and empr_location=$empr_location_id "; 
				$query = "select id_empr, empr_nom, empr_prenom from empr where (empr_cb='$form_cb' or empr_nom like '$form_cb%') $where ";
				$result = mysql_query($query, $dbh);
				if (!mysql_num_rows($result)) {
					// emprunteur inconnu
					$message_ajout_empr =  "<strong>$form_cb&nbsp;: ".$msg['empr_caddie_unknown_barcode']."</strong>";
				} elseif (mysql_num_rows($result)==1) {
					$empr_trouve = mysql_fetch_object($result);
					$myCart->add_item($empr_trouve->id_empr);
					$message_ajout_empr =  "<strong>".$empr_trouve->empr_nom."&nbsp;".$empr_trouve->empr_prenom."&nbsp;: ".$msg['empr_caddie_collect_added']."</strong>";
				} else {
					$message_ajout_empr =  "<strong>$form_cb&nbsp;: ".$msg['empr_caddie_toomany_barcode']."</strong>";
				}
			}
			print $message_ajout_empr;
			$myCart->compte_items();
			print aff_empr_cart_nb_items ($myCart) ;
			print get_cb("", $msg[empr_caddie_collect_form_message], $msg[empr_caddie_collect_form_title], "./circ.php?categ=caddie&sub=gestion&quoi=barcode&action=add_item&idemprcaddie=$idemprcaddie", 0, "", 0) ;
			break;
		default:
			print aff_empr_cart_nb_items ($myCart) ;
			print get_cb("", $msg[empr_caddie_collect_form_message], $msg[empr_caddie_collect_form_title], "./circ.php?categ=caddie&sub=gestion&quoi=barcode&action=add_item&idemprcaddie=$idemprcaddie", 0, "", 0) ;
			break;
		}
	} else aff_paniers_empr($idcaddie, "./circ.php?categ=caddie&sub=gestion&quoi=barcode", "", $msg["caddie_select_ajouter"], "", 0, 0, 0);
