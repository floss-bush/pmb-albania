<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: amendes.inc.php,v 1.4 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Gestion des amendes

require_once("$include_path/templates/finance.tpl.php");
require_once($class_path."/quotas.class.php");

function show_amende_parameters() {
	global $msg;
	global $charset;
	global $finance_amende_jour,$finance_delai_avant_amende,$finance_delai_recouvrement,$finance_amende_maximum,$finance_delai_1_2,$finance_delai_2_3;
	print "
		<div class='row'>
			<div class='colonne3' style='text-align:right;padding-right:10px'>".$msg["finance_amende_mnt"]."</div><div class='colonne3'>$finance_amende_jour</div><div class='colonne_suite'>&nbsp;</div>
		</div>
		<div class='row'>
			<div class='colonne3' style='text-align:right;padding-right:10px'>".$msg["finance_amende_delai"]."</div><div class='colonne3'>$finance_delai_avant_amende</div><div class='colonne_suite'>&nbsp;</div>
		</div>
		<div class='row'>
			<div class='colonne3' style='text-align:right;padding-right:10px'>".$msg["finance_amende_delai_1_2"]."</div><div class='colonne3'>$finance_delai_1_2</div><div class='colonne_suite'>&nbsp;</div>
		</div>
		<div class='row'>
			<div class='colonne3' style='text-align:right;padding-right:10px'>".$msg["finance_amende_delai_2_3"]."</div><div class='colonne3'>$finance_delai_2_3</div><div class='colonne_suite'>&nbsp;</div>
		</div>
		<div class='row'>
			<div class='colonne3' style='text-align:right;padding-right:10px'>".$msg["finance_amende_delai_recouvrement"]."</div><div class='colonne3'>$finance_delai_recouvrement</div><div class='colonne_suite'>&nbsp;</div>
		</div>
		<div class='row'>
			<div class='colonne3' style='text-align:right;padding-right:10px'>".$msg["finance_amende_max"]."</div><div class='colonne3'>$finance_amende_maximum</div><div class='colonne_suite'>&nbsp;</div>
		</div>
		<div class='row'></div>
		<div class='row'><input type='button' class='bouton' value='".$msg["finance_amende_modifier"]."' onClick=\"document.location='./admin.php?categ=finance&sub=amendes&action=modif';\"></div>
	";
}

if ($pmb_gestion_amende==1) {
	$admin_layout = str_replace('!!menu_sous_rub!!', $msg["finance_amendes"], $admin_layout);  
    print $admin_layout;
	switch ($action) {
		case 'update':
			//Mise à jour !!
			$requete="update parametres set valeur_param='".$amende_jour."' where type_param='finance' and sstype_param='amende_jour'";
			mysql_query($requete);
			$finance_amende_jour=stripslashes($amende_jour);
			$requete="update parametres set valeur_param='".$amende_delai."' where type_param='finance' and sstype_param='delai_avant_amende'";
			mysql_query($requete);
			$finance_delai_avant_amende=stripslashes($amende_delai);
			$requete="update parametres set valeur_param='".$amende_delai_recouvrement."' where type_param='finance' and sstype_param='delai_recouvrement'";
			mysql_query($requete);
			$finance_delai_recouvrement=stripslashes($amende_delai_recouvrement);
			$requete="update parametres set valeur_param='".$amende_max."' where type_param='finance' and sstype_param='amende_maximum'";
			mysql_query($requete);
			$finance_amende_maximum=stripslashes($amende_max);
			$requete="update parametres set valeur_param='".$amende_1_2."' where type_param='finance' and sstype_param='delai_1_2'";
			mysql_query($requete);
			$finance_delai_1_2=stripslashes($amende_1_2);
			$requete="update parametres set valeur_param='".$amende_2_3."' where type_param='finance' and sstype_param='delai_2_3'";
			mysql_query($requete);
			$finance_delai_2_3=stripslashes($amende_2_3);
			show_amende_parameters();
			break;
		case 'modif':
			//Formulaire de mise à jour
			$finance_amende_form=str_replace("!!amende_jour!!",htmlentities($finance_amende_jour,ENT_QUOTES,$charset),$finance_amende_form);
			$finance_amende_form=str_replace("!!amende_delai!!",htmlentities($finance_delai_avant_amende,ENT_QUOTES,$charset),$finance_amende_form);
			$finance_amende_form=str_replace("!!amende_delai_recouvrement!!",htmlentities($finance_delai_recouvrement,ENT_QUOTES,$charset),$finance_amende_form);
			$finance_amende_form=str_replace("!!amende_max!!",htmlentities($finance_amende_maximum,ENT_QUOTES,$charset),$finance_amende_form);
			$finance_amende_form=str_replace("!!amende_1_2!!",htmlentities($finance_delai_1_2,ENT_QUOTES,$charset),$finance_amende_form);
			$finance_amende_form=str_replace("!!amende_2_3!!",htmlentities($finance_delai_2_3,ENT_QUOTES,$charset),$finance_amende_form);
			print $finance_amende_form;
			break;
		default:
			//Gestion simple
			show_amende_parameters();
			break;
	}
} else {
	$menu_sous_rub=$msg["finance_amendes"];
	
	//Gestion par quotas
	if ($quota) $qt=new quota($quota,"$include_path/quotas/own/$lang/finances.xml"); else quota::parse_quotas("$include_path/quotas/own/$lang/finances.xml");
	$admin_menu_quotas="";
	for ($i=0; $i<count($_quotas_types_); $i++) {	
		if ($_quotas_types_[$i]["FILTER_ID"]=="amende") {
			$admin_menu_quotas.="<span><a href='./admin.php?categ=finance&sub=amendes&quota=".$_quotas_types_[$i]["ID"]."'>".$_quotas_types_[$i]["SHORT_COMMENT"]."</a></span>\n";
			if ($quota==$_quotas_types_[$i]["ID"]) {
				$menu_sous_rub.=" > ".$_quotas_types_[$i]["SHORT_COMMENT"];
				if ($elements) $menu_sous_rub.=" > ".$qt->get_title_by_elements_id($elements);
			}
		}
	}
	$admin_layout = str_replace('!!menu_sous_rub!!', $menu_sous_rub, $admin_layout);  
    print $admin_layout;
	print "<div class='row'>".$admin_menu_quotas."</div><div class='row'>&nbsp;</div>";	
	
	switch ($quota) {
		case "":
			break;
		default:
			if (!$elements) {
				$query_compl="&quota=$quota";
				include("./admin/quotas/quotas_list.inc.php");
			} else {
				$query_compl="&quota=$quota";
				include("./admin/quotas/quota_table.inc.php");
			}
			break;
	}
	
}

?>