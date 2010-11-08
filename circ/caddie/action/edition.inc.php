<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: edition.inc.php,v 1.2 2007-07-15 07:36:03 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($idemprcaddie) {
	$myCart= new empr_caddie($idemprcaddie);
	print aff_empr_cart_titre ($myCart);
	switch ($action) {
		case 'choix_quoi':
			print aff_empr_cart_nb_items ($myCart) ;
			$action = "./circ/caddie/action/edit.php?idemprcaddie=$idemprcaddie" ;
			$action_cancel = "./circ.php?categ=caddie&sub=action&quelle=edition&action=&idemprcaddie=0" ;
			$empr_cart_choix_quoi_edition = str_replace('!!action!!', $action, $empr_cart_choix_quoi_edition);
			$empr_cart_choix_quoi_edition = str_replace('!!action_cancel!!', $action_cancel, $empr_cart_choix_quoi_edition);
			$empr_cart_choix_quoi_edition = str_replace('!!titre_form!!', $msg["caddie_choix_edition"], $empr_cart_choix_quoi_edition);
			$suppl = "<input type='hidden' name='dest' value=''>&nbsp;
				<input type='button' class='bouton' value='$msg[caddie_choix_edition_HTML]' onclick=\"this.form.dest.value='HTML'; this.form.submit();\" />&nbsp;
				<input type='button' class='bouton' value='$msg[caddie_choix_edition_TABLEAUHTML]' onclick=\"this.form.dest.value='TABLEAUHTML'; this.form.submit();\" />&nbsp;
				<input type='button' class='bouton' value='$msg[caddie_choix_edition_TABLEAU]' onclick=\"this.form.dest.value='TABLEAU'; this.form.submit();\" />" ;
			$empr_cart_choix_quoi_edition = str_replace('<!-- !!boutons_supp!! -->', $suppl, $empr_cart_choix_quoi_edition);
			print $empr_cart_choix_quoi_edition ;
			break;
		case 'suite':
			print aff_empr_cart_nb_items ($myCart) ;
			break;
		default:
			break;
		}

	} else aff_paniers_empr($idemprcaddie, "./circ.php?categ=caddie&sub=action&quelle=edition", "choix_quoi", $msg["caddie_select_edition"], "", 0, 0, 0);
