<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export.inc.php,v 1.10 2009-05-04 15:09:04 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$include_path/parser.inc.php");
require_once($class_path."/export_param.class.php");

function _item_($param) {
	global $catalog;
	global $n_typ_total;
	$t[NAME]=$param[EXPORTNAME];
	$t[INDEX]=$n_typ_total;
	$n_typ_total++;
	if ($param[EXPORT]=="yes") $catalog[]=$t;
}

function aff_choix_quoi_export($action="", $action_cancel="", $titre_form="", $bouton_valider="") {
	
	global $cart_choix_quoi_exporter;
	global $catalog;
	global $base_path;
	
	$cart_choix_quoi_exporter = str_replace('!!action!!', $action, $cart_choix_quoi_exporter);
	$cart_choix_quoi_exporter = str_replace('!!action_cancel!!', $action_cancel, $cart_choix_quoi_exporter);
	$cart_choix_quoi_exporter = str_replace('!!titre_form!!', $titre_form, $cart_choix_quoi_exporter);
	$cart_choix_quoi_exporter = str_replace('!!bouton_valider!!', $bouton_valider, $cart_choix_quoi_exporter);

	//Lecture des différents exports possibles
	$catalog=array();
	$n_typ_total=0;
	if (file_exists("$base_path/admin/convert/imports/catalog_subst.xml"))
		$fic_catal = "$base_path/admin/convert/imports/catalog_subst.xml";
	else
		$fic_catal = "$base_path/admin/convert/imports/catalog.xml";
		
	_parser_($fic_catal, array("ITEM"=>"_item_"), "CATALOG");

	//Création de la liste des types d'import
	$export_type="<select name=\"export_type\" id=\"export_type\">\n";
	for ($i=0; $i<count($catalog); $i++) {
		$export_type.="<option value=\"".$catalog[$i][INDEX]."\">".$catalog[$i][NAME]."</option>\n";
	}
	$export_type.="</select>";

	$cart_choix_quoi_exporter=str_replace("!!export_type!!",$export_type,$cart_choix_quoi_exporter);
	
	$param = new export_param(EXP_DEFAULT_GESTION);
	$cart_choix_quoi_exporter=str_replace("!!form_param!!",$param->check_default_param(),$cart_choix_quoi_exporter);
	return $cart_choix_quoi_exporter;
}



if($idcaddie) {
	$myCart= new caddie($idcaddie);
	print pmb_bidi(aff_cart_titre ($myCart));
	switch ($action) {
		case 'choix_quoi':
			print pmb_bidi(aff_cart_nb_items ($myCart)) ;
			print aff_choix_quoi_export ("./catalog.php?categ=caddie&sub=action&quelle=export&action=exporter&idcaddie=$idcaddie", "./catalog.php?categ=caddie&sub=action&quelle=export&action=&idcaddie=0", $msg["caddie_choix_export"], $msg["caddie_bouton_exporter"]);
			break;
		case 'exporter':
			export_param::init_session();
			$param_exp=new export_param(EXP_SESSION_CONTEXT);
			echo "<div>
				<iframe name=\"ieexport\" frameborder=\"0\" scrolling=\"yes\" width=\"600\" height=\"500\" src=\"./admin/convert/start_export_caddie.php?elt_flag=$elt_flag&elt_no_flag=$elt_no_flag&keep_expl=$keep_expl&idcaddie=$idcaddie&export_type=$export_type&".$param_exp->get_parametres_to_string()."\">
				</div>
				<noframes>
				</noframes>";
			break;
		default:
			break;
		}

	} else aff_paniers($idcaddie, "NOTI", "./catalog.php?categ=caddie&sub=action&quelle=export", "choix_quoi", $msg["caddie_select_export"], "", 0, 0, 0);
