<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: impr_cote.inc.php,v 1.5 2009-10-26 17:56:24 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if ($pmb_label_construct_script) {
	require_once ("./catalog/caddie/".$pmb_label_construct_script);
} else {
	require_once ("./catalog/caddie/custom_label_no_script.inc.php");
}

function aff_choix_quoi_impr_cote($action = "", $action_redo="", $action_cancel = "", $titre_form = "", $bouton_valider = "") {

	global $cart_choix_quoi_impr_cote;
	global $msg, $charset;
	global $pmb_label_construct_script;
	
	global $id_caddie, $elt_flag, $elt_no_flag;
	global $label_id;

	$cart_choix_quoi_impr_cote = str_replace('!!action!!', $action, $cart_choix_quoi_impr_cote);
	$cart_choix_quoi_impr_cote = str_replace('!!action_cancel!!', $action_cancel, $cart_choix_quoi_impr_cote);
	$cart_choix_quoi_impr_cote = str_replace('!!titre_form!!', $titre_form, $cart_choix_quoi_impr_cote);
	$cart_choix_quoi_impr_cote = str_replace('!!bouton_valider!!', $bouton_valider, $cart_choix_quoi_impr_cote);

	if(!$elt_flag) $elt_flag_chk=''; else $elt_flag_chk="checked='checked'";
	if(!$elt_no_flag) $elt_no_flag_chk=''; else $elt_no_flag_chk="checked='checked'";
	$cart_choix_quoi_impr_cote = str_replace('!!elt_flag_chk!!', $elt_flag_chk, $cart_choix_quoi_impr_cote);
	$cart_choix_quoi_impr_cote = str_replace('!!elt_no_flag_chk!!', $elt_no_flag_chk, $cart_choix_quoi_impr_cote);

	//Lecture des formats de planches d'étiquettes
	$label_fmt_sel = "";
	$label_fmt_sel .= "<label class='etiquette'>" . htmlentities($msg[label_format], ENT_QUOTES, $charset) . "</label>&nbsp;";
	$label_fmt_sel .= "<select id='label_id' name='label_id' onchange=\"document.forms['maj_proc'].setAttribute('action', '".$action_redo."');document.forms['maj_proc'].submit(); \">";

	//Formats disponibles
	$label_fmt_list = getLabelFormatList();	
	foreach ($label_fmt_list as $key => $value) {
		$label_fmt_sel .= "<option value=\"" . $key . "\" ";
		if (!$label_id || ($label_id==$key) ) {
			$label_fmt_sel .= "selected='selected' ";
			$label_id = $key;
		}
		$label_fmt_sel .= ">" .htmlentities($value[label_name], ENT_QUOTES, $charset) . "</option>";
	}
	$label_fmt_sel .= "</select>";
	$cart_choix_quoi_impr_cote = str_replace("<!--label_fmt_sel-->", $label_fmt_sel, $cart_choix_quoi_impr_cote);

	//Affichage format
	$label_fmt_dis = displayLabelFormat($label_id);
	$cart_choix_quoi_impr_cote = str_replace("<!--label_fmt_dis-->", $label_fmt_dis, $cart_choix_quoi_impr_cote);


	//Script verification Format
	$label_fmt_ver = verifLabelFormat($label_id);
	$cart_choix_quoi_impr_cote = str_replace("<!--label_fmt_ver-->", $label_fmt_ver, $cart_choix_quoi_impr_cote);

	//Affichage contenu
	$label_con_dis = displayLabelContent($label_id);
	$cart_choix_quoi_impr_cote = str_replace("<!--label_con_dis-->", $label_con_dis, $cart_choix_quoi_impr_cote);


	//Script verification contenu
	$label_con_ver = verifLabelContent($label_id);
	$cart_choix_quoi_impr_cote = str_replace("<!--label_con_ver-->", $label_con_ver, $cart_choix_quoi_impr_cote);

	return $cart_choix_quoi_impr_cote;
}

if ($idcaddie) {
	$myCart = new caddie($idcaddie);
	print aff_cart_titre($myCart);
	switch ($action) {

		case 'choix_quoi' :
			print aff_cart_nb_items($myCart);
			print aff_choix_quoi_impr_cote("./catalog/caddie/action/impr_cote_suite.php?idcaddie=$idcaddie", "./catalog.php?categ=caddie&sub=action&quelle=impr_cote&action=choix_quoi&object_type=EXPL&idcaddie=".$idcaddie, "./catalog.php?categ=caddie&sub=action&quelle=impr_cote&action=&idcaddie=0", $msg[caddie_choix_panier_impr_cote], $msg[caddie_act_panier_impr_cote], "");
			break;

		default :
			break;
	}

} else
	aff_paniers($idcaddie, "EXPL", "./catalog.php?categ=caddie&sub=action&quelle=impr_cote", "choix_quoi", $msg[caddie_select_panier_impr_cote], "EXPL", 0, 0, 0);