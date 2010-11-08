<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: import.php,v 1.7 2008-07-15 15:44:06 ohennequin Exp $


//Interface de lancement de l'import
$base_path="../..";
$base_auth="ADMINISTRATION_AUTH|CATALOGAGE_AUTH";
$base_title="\$msg[ie_import_running]";
require($base_path."/includes/init.inc.php");

require_once("$include_path/parser.inc.php");
require_once("$include_path/templates/import_form.tpl.php");

function _item_($param) {
	global $catalog;
	global $catalog_visible;
	
	$catalog[]=$param['NAME'];
	$catalog_visible[]=$param['VISIBLE'];
}

//Lecture des différents imports possibles
if (file_exists("imports/catalog_subst.xml"))
	$fic_catal = "imports/catalog_subst.xml";
else
	$fic_catal = "imports/catalog.xml";

$catalog=array();
_parser_($fic_catal,array("ITEM"=>"_item_"),"CATALOG");

//Création de la liste des types d'import
$import_type="<select name=\"import_type\">\n";
for ($i=0; $i<count($catalog); $i++) {
	if ($catalog_visible[$i]!="no") {
	   $import_type.="<option value=\"$i\">".$catalog[$i]."</option>\n";
	}
}
$import_type.="</select>";

$form=str_replace("!!import_type!!",$import_type,$form);

echo pmb_bidi($form);
print "</body></html>";
?>