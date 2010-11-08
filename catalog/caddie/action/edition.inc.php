<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: edition.inc.php,v 1.8 2010-08-11 10:08:20 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("./classes/notice_tpl_gen.class.php");

if($idcaddie) {
	$myCart= new caddie($idcaddie);
	print pmb_bidi(aff_cart_titre ($myCart));
	switch ($action) {
		case 'choix_quoi':
			print pmb_bidi(aff_cart_nb_items ($myCart)) ;
			$action = "./catalog/caddie/action/edit.php?idcaddie=$idcaddie" ;
			$action_cancel = "./catalog.php?categ=caddie&sub=action&quelle=edition&action=&idcaddie=0" ;
			$cart_choix_quoi_edition = str_replace('!!action!!', $action, $cart_choix_quoi_edition);
			$cart_choix_quoi_edition = str_replace('!!action_cancel!!', $action_cancel, $cart_choix_quoi_edition);
			$cart_choix_quoi_edition = str_replace('!!titre_form!!', $msg["caddie_choix_edition"], $cart_choix_quoi_edition);
			$suppl = "<input type='hidden' name='dest' value=''>&nbsp;
				<input type='button' class='bouton' value='$msg[caddie_choix_edition_HTML]' onclick=\"this.form.dest.value='HTML'; this.form.submit();\" />&nbsp;
				<input type='button' class='bouton' value='$msg[caddie_choix_edition_TABLEAUHTML]' onclick=\"this.form.dest.value='TABLEAUHTML'; this.form.submit();\" />&nbsp;
				<input type='button' class='bouton' value='$msg[caddie_choix_edition_TABLEAU]' onclick=\"this.form.dest.value='TABLEAU'; this.form.submit();\" />" ;
			$sel_notice_tpl=$msg['caddie_select_notice_tpl']."&nbsp;".notice_tpl_gen::gen_tpl_select("notice_tpl",0,'',1,1);
			if($sel_notice_tpl) {
				$suppl.= "&nbsp;<input type='button' class='bouton' value='".$msg['etatperso_export_notice']."' onclick=\"this.form.dest.value='EXPORT_NOTI'; this.form.submit();\" />";
			}	
			$cart_choix_quoi_edition = str_replace('<!-- !!boutons_supp!! -->', $suppl, $cart_choix_quoi_edition);		
			$cart_choix_quoi_edition = str_replace('<!-- notice_template -->', $sel_notice_tpl, $cart_choix_quoi_edition);
			print $cart_choix_quoi_edition ;
			break;
		case 'suite':
			print pmb_bidi(aff_cart_nb_items ($myCart)) ;
			switch ($myCart->type) {
				case "EXPL":
				case "NOTI":
				case "BULL":
				default:
					break;
				}
			break;
		default:
			break;
		}

	} else aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=action&quelle=edition", "choix_quoi", $msg["caddie_select_edition"], "", 0, 0, 0);
