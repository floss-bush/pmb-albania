<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export.inc.php,v 1.3 2008-07-15 15:44:06 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$include_path/parser.inc.php");

function _item_($param) {
	global $catalog;
	global $n_typ_total;
	$t[NAME]=$param[EXPORTNAME];
	$t[INDEX]=$n_typ_total;
	$n_typ_total++;
	if ($param[EXPORT]=="yes") $catalog[]=$t;
}

function aff_empr_choix_quoi_export($action="", $action_cancel="", $titre_form="", $bouton_valider="") {
	
	global $empr_cart_choix_quoi_exporter;
	global $catalog;
	
	$empr_cart_choix_quoi_exporter = str_replace('!!action!!', $action, $empr_cart_choix_quoi_exporter);
	$empr_cart_choix_quoi_exporter = str_replace('!!action_cancel!!', $action_cancel, $empr_cart_choix_quoi_exporter);
	$empr_cart_choix_quoi_exporter = str_replace('!!titre_form!!', $titre_form, $empr_cart_choix_quoi_exporter);
	$empr_cart_choix_quoi_exporter = str_replace('!!bouton_valider!!', $bouton_valider, $empr_cart_choix_quoi_exporter);

	//Lecture des différents exports possibles
	$catalog=array();
	$n_typ_total=0;
	if (file_exists("$base_path/admin/convert/imports/catalog_subst.xml"))
		$fic_catal = "$base_path/admin/convert/imports/catalog_subst.xml";
	else
		$fic_catal = "$base_path/admin/convert/imports/catalog.xml";
		
	_parser_($fic_catal, array("ITEM"=>"_item_"),"CATALOG");

	//Création de la liste des types d'import
	$export_type="<select name=\"export_type\" id=\"export_type\">\n";
	for ($i=0; $i<count($catalog); $i++) {
		$export_type.="<option value=\"".$catalog[$i][INDEX]."\">".$catalog[$i][NAME]."</option>\n";
	}
	$export_type.="</select>";

	$empr_cart_choix_quoi_exporter=str_replace("!!export_type!!",$export_type,$empr_cart_choix_quoi_exporter);
	
	return $empr_cart_choix_quoi_exporter;
	}

if($idemprcaddie) {
	$myCart= new empr_caddie($idemprcaddie);
	print aff_empr_cart_titre ($myCart);
	switch ($action) {
		case 'choix_quoi':
			print aff_empr_cart_nb_items ($myCart) ;
			print aff_empr_choix_quoi_export ("./circ.php?categ=caddie&sub=action&quelle=export&action=exporter&idemprcaddie=$idemprcaddie", "./circ.php?categ=caddie&sub=action&quelle=export&action=&idemprcaddie=0", $msg["caddie_choix_export"], $msg["caddie_bouton_exporter"]);
			break;
		case 'exporter':
			echo "<div>
	<iframe name=\"ieexport\" frameborder=\"0\" scrolling=\"yes\" width=\"600\" height=\"500\" src=\"./admin/convert/start_export_caddie.php?elt_flag=$elt_flag&elt_no_flag=$elt_no_flag&keep_expl=$keep_expl&idcaddie=$idemprcaddie&export_type=$export_type\">
	</div>
	<noframes>
	</noframes>";
			break;
		default:
			break;
		}

	} else aff_paniers_empr($idemprcaddie, "./circ.php?categ=caddie&sub=action&quelle=export", "choix_quoi", $msg["caddie_select_export"], "", 0, 0, 0);
