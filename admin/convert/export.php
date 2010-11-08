<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export.php,v 1.13 2009-05-04 15:09:03 kantin Exp $

//Interface de lancement de l'import
$base_path="../..";
$base_auth="ADMINISTRATION_AUTH|CATALOGAGE_AUTH";

$base_title="\$msg[admin_convert_export_titre]";
require($base_path."/includes/init.inc.php");

require_once("$include_path/parser.inc.php");
require_once("$include_path/templates/export_form.tpl.php");
require_once($class_path."/export_param.class.php");


function _item_($param) {
	global $catalog;
	global $n_typ_total;
	$t['NAME']=$param['EXPORTNAME'];
	$t['INDEX']=$n_typ_total;
	$n_typ_total++;
	if ($param['EXPORT']=="yes") $catalog[]=$t;
}

//Lecture des différents exports possibles
$catalog=array();
$n_typ_total=0;
if (file_exists("imports/catalog_subst.xml"))
	$fic_catal = "imports/catalog_subst.xml";
else
	$fic_catal = "imports/catalog.xml";

_parser_($fic_catal, array("ITEM" => "_item_"), "CATALOG");

//Création de la liste des types d'import
$export_type="<select name=\"export_type\">\n";
for ($i=0; $i<count($catalog); $i++) {
	$export_type.="<option value=\"".$catalog[$i]['INDEX']."\">".$catalog[$i]['NAME']."</option>\n";
}
$export_type.="</select>";

$form=str_replace("!!export_type!!",$export_type,$form);

//Filtres

//Propriétaires
$requete="select idlender,lender_libelle from lenders";
$lenders="<select name=\"lender\" onChange=\"show_list(this);\">\n";
$lenders.="<option value=\"x\">".$msg['admin_convert_propri']."</option>\n";
$resultat=mysql_query($requete);
while (list($idlender,$lender_libelle)=mysql_fetch_row($resultat)) {
	$lenders.="<option value=\"$idlender\">".$lender_libelle."</option>\n";
}
$lenders.="</select>\n";
$form=str_replace("!!lenders!!",$lenders,$form);

//Types de documents
$requete="select idlender, lender_libelle from lenders";
$resultat=mysql_query($requete);
while (list($id_lender,$lender_libelle)=mysql_fetch_row($resultat)) {
	//Récupération des codes exemplaires du proptiétaire
	$requete="select idtyp_doc, concat(tdoc_libelle) as lib from docs_type, exemplaires, lenders where idtyp_doc=expl_typdoc and expl_owner=$id_lender and (idlender=tdoc_owner or tdoc_owner=0) group by expl_typdoc";
	$typ_doc_lists.="<div id=\"dtypdoc$id_lender\" style=\"display:none\">";
	$typ_doc_lists.=gen_liste($requete,"idtyp_doc","lib","typdoc".$id_lender."[]","","","","","","",1);
	$typ_doc_lists.="</div>";
}
//Tous les types
$requete="select idtyp_doc, concat(tdoc_libelle) as lib from docs_type order by lib";
$typ_doc_lists.="<div id=\"dtypdocx\" style=\"display:block\">";
$typ_doc_lists.=gen_liste($requete,"idtyp_doc","lib","typdocx[]","","","","","","",1);
$typ_doc_lists.="</div>";
$form=str_replace("!!typ_doc_lists!!",$typ_doc_lists,$form);

//Status
$requete="select idlender, lender_libelle from lenders";
$resultat=mysql_query($requete);
while (list($id_lender,$lender_libelle)=mysql_fetch_row($resultat)) {
	//Récupération des codes exemplaires du propriétaire ayant le statut
	$requete="select idstatut, concat(statut_libelle) as lib from docs_statut, exemplaires, lenders where idstatut=expl_statut and expl_owner=$id_lender and (idlender=statusdoc_owner or statusdoc_owner=0) group by expl_statut";
	$statut_lists.="<div id=\"dstatut$id_lender\" style=\"display:none\">";
	$statut_lists.=gen_liste($requete,"idstatut","lib","statut".$id_lender."[]","","","","","","",1);
	$statut_lists.="</div>";
}
//Tous les status
$requete="select idstatut, concat(statut_libelle) as lib from docs_statut order by lib";
$statut_lists.="<div id=\"dstatutx\" style=\"display:block\">";
$statut_lists.=gen_liste($requete,"idstatut","lib","statutx[]","","","","","","",1);
$statut_lists.="</div>";
$form=str_replace("!!statut_lists!!",$statut_lists,$form);

$param = new export_param(EXP_DEFAULT_GESTION);
$form=str_replace("!!form_param!!",$param->check_default_param(),$form);
echo pmb_bidi($form);

print "</body></html>";
