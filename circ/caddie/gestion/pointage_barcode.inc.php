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
		case 'pointe_item':
			if($form_cb) {
				if ($empr_location_id>0) $where = " and empr_location=$empr_location_id "; 
				$query = "select id_empr, empr_nom, empr_prenom from empr, empr_caddie_content where (empr_cb='$form_cb' or empr_nom like '$form_cb%') and id_empr=object_id and empr_caddie_id=$idemprcaddie $where ";
				$result = mysql_query($query, $dbh);
				if (!mysql_num_rows($result)) {
					// emprunteur inconnu
					$message_pointe_empr =  "<strong>$form_cb&nbsp;: ".$msg['empr_caddie_unknown_barcode']."</strong>";
				} elseif (mysql_num_rows($result)==1) {
					$empr_trouve = mysql_fetch_object($result);
					$myCart->pointe_item($empr_trouve->id_empr);
					$message_pointe_empr =  "<strong>".$empr_trouve->empr_nom."&nbsp;".$empr_trouve->empr_prenom."&nbsp;: ".$msg['empr_caddie_pointage_pointe']."</strong>";
				} else {
					$message_pointe_empr =  "<strong>$form_cb&nbsp;: ".$msg['empr_caddie_toomany_barcode']."</strong>";
				}
			}
			print $message_pointe_empr;
			$myCart->compte_items();
			print aff_empr_cart_nb_items ($myCart) ;
			print get_cb("", $msg[empr_caddie_pointage_form_message], $msg[empr_caddie_pointage_form_title], "./circ.php?categ=caddie&sub=gestion&quoi=pointagebarcode&action=pointe_item&idemprcaddie=$idemprcaddie", 0, "", 0) ;
			break;
		default:
			print aff_empr_cart_nb_items ($myCart) ;
			print get_cb("", $msg[empr_caddie_pointage_form_message], $msg[empr_caddie_pointage_form_title], "./circ.php?categ=caddie&sub=gestion&quoi=pointagebarcode&action=pointe_item&idemprcaddie=$idemprcaddie", 0, "", 0) ;
			break;
		}
	} else aff_paniers_empr($idemprcaddie, "./circ.php?categ=caddie&sub=gestion&quoi=pointagebarcode&moyen=barcode", "", $msg[caddie_select_pointe], "", 0, 0, 0);

