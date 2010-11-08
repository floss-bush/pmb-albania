<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: panier.inc.php,v 1.15 2010-01-21 16:22:36 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch ($action) {
	case 'new_cart':
		$cart_form = str_replace('!!autorisations_users!!', aff_form_autorisations("",1), $cart_form);
		$cart_form = str_replace('!!formulaire_action!!', "./catalog.php?categ=caddie&sub=gestion&quoi=panier&action=valid_new_cart&item=$item", $cart_form);
		$cart_form = str_replace('!!formulaire_annuler!!', "./catalog.php?categ=caddie&sub=gestion&quoi=panier&action=&item=$item", $cart_form);
		$select_cart="
		<select name='cart_type'>
			<option value='NOTI' selected>$msg[caddie_de_NOTI]</option>
			<option value='EXPL'>$msg[caddie_de_EXPL]</option>
			<option value='BULL'>$msg[caddie_de_BULL]</option>
		</select>
		<input type='hidden' name='current_print' value='$current_print'/>";
	 	$cart_form=str_replace('!!cart_type_select!!', $select_cart, $cart_form);
		print $cart_form ;
		break;
	case 'edit_cart':
		$myCart= new caddie($idcaddie);
		$cart_edit_form = str_replace('!!formulaire_action!!', "./catalog.php?categ=caddie&sub=gestion&quoi=panier&action=save_cart&item=$item&idcaddie=$idcaddie", $cart_edit_form);
		$cart_edit_form = str_replace('!!formulaire_annuler!!', "./catalog.php?categ=caddie&sub=gestion&quoi=panier&action=&item=$item", $cart_edit_form);
		$cart_edit_form = str_replace('!!idcaddie!!', $idcaddie, $cart_edit_form);
		$cart_edit_form = str_replace('!!name!!', htmlentities($myCart->name,ENT_QUOTES, $charset), $cart_edit_form);
		$cart_edit_form = str_replace('!!name_suppr!!', htmlentities(addslashes($myCart->name),ENT_QUOTES, $charset), $cart_edit_form);
		$type = "caddie_de_".$myCart->type;
		$cart_edit_form = str_replace('!!cart_type!!', $msg[$type], $cart_edit_form);
		$cart_edit_form = str_replace('!!comment!!', htmlentities($myCart->comment,ENT_QUOTES, $charset), $cart_edit_form);
		$cart_edit_form = str_replace('!!autorisations_users!!', aff_form_autorisations($myCart->autorisations,0), $cart_edit_form);
		print confirmation_delete("./catalog.php?categ=caddie&action=del_cart&idcaddie=");
		print $cart_edit_form ;
		break;
	case 'del_cart':
		$myCart= new caddie($idcaddie);
		$myCart->delete();
		aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=gestion&quoi=panier", "", $msg["caddie_select_afficher"], "", 1, 0, 1,1);
		break;
	case 'save_cart':
		$myCart= new caddie($idcaddie);
		if (is_array($cart_autorisations)) $autorisations=implode(" ",$cart_autorisations);
				else $autorisations="1";
		$myCart->autorisations = $autorisations;
		$myCart->name = $cart_name;
		$myCart->comment = $cart_comment;
		if($form_actif) $myCart->save_cart();
		aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=gestion&quoi=panier", "", $msg["caddie_select_afficher"], "", 1, 0, 1);
		break;
	case 'del_item':
		$myCart= new caddie($idcaddie);
		if ($object_type=="EXPL_CB") $myCart->del_item_blob($item);
			else $myCart->del_item($item);
		print pmb_bidi(aff_cart_titre ($myCart));
		print aff_cart_nb_items ($myCart) ;
		aff_cart_objects ($idcaddie, "./catalog.php?categ=caddie&sub=gestion&quoi=panier&idcaddie=$idcaddie" );
		break;
	case 'valid_new_cart':
		$myCart = new caddie(0);
		$myCart->name = $cart_name;
		$myCart->type = $cart_type;
		$myCart->comment = $cart_comment;
		if (is_array($cart_autorisations)) $autorisations=implode(" ",$cart_autorisations);
				else $autorisations="";
		$myCart->autorisations = $autorisations;
		if($form_actif) $myCart->create_cart();
		aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=gestion&quoi=panier", "", $msg["caddie_select_afficher"], "", 1, 0, 1);
		break;
	default:
		if($idcaddie) {
			$myCart = new caddie($idcaddie);
			print pmb_bidi(aff_cart_titre ($myCart));
			print pmb_bidi(aff_cart_nb_items ($myCart));
			aff_cart_objects ($idcaddie, "./catalog.php?categ=caddie&sub=gestion&quoi=panier&idcaddie=$idcaddie" );
			} else aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=gestion&quoi=panier", "", $msg["caddie_select_afficher"], "", 1, 0, 1);
	}
