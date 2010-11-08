<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: amendes_relances.inc.php,v 1.6 2010-09-03 07:11:30 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Gestion des amendes

require_once("$include_path/templates/finance.tpl.php");
require_once($class_path."/quotas.class.php");

function show_amende_parameters() {
	global $msg;
	global $charset;
	global $finance_relance_1,$finance_relance_2,$finance_relance_3,$finance_statut_perdu;
	$requete="select statut_libelle from docs_statut where idstatut='".$finance_statut_perdu."'";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
		$statut_perdu=mysql_result($resultat,0,0);
	} else $statut_perdu="";
	print "
		<div class='row'>
			<div class='colonne3' style='text-align:right;padding-right:10px'>".$msg["finance_relance_1"]."</div><div class='colonne3'>$finance_relance_1</div><div class='colonne_suite'>&nbsp;</div>
		</div>
		<div class='row'>
			<div class='colonne3' style='text-align:right;padding-right:10px'>".$msg["finance_relance_2"]."</div><div class='colonne3'>$finance_relance_2</div><div class='colonne_suite'>&nbsp;</div>
		</div>
		<div class='row'>
			<div class='colonne3' style='text-align:right;padding-right:10px'>".$msg["finance_relance_3"]."</div><div class='colonne3'>$finance_relance_3</div><div class='colonne_suite'>&nbsp;</div>
		</div>
		<div class='row'>
			<div class='colonne3' style='text-align:right;padding-right:10px'>".$msg["finance_statut_perdu"]."</div><div class='colonne3'>".$statut_perdu."</div><div class='colonne_suite'>&nbsp;</div>
		</div>
		<div class='row'></div>
		<div class='row'><input type='button' class='bouton' value='".$msg["finance_amende_modifier"]."' onClick=\"document.location='./admin.php?categ=finance&sub=amendes_relance&action=modif';\"></div>
	";
}

function show_lost_status_form() {
	global $dbh;
	global $msg;
	global $finance_statut_perdu,$finance_recouvrement_lecteur_statut;
	$result ="
	<form method='POST' action='admin.php?categ=finance&sub=amendes_relance&action=updateloststatus'>
	<h3>".$msg["finance_statut_perdu"]."</h3>	
		<div class='form-contenu'>		
			<div class='row'> 
				<div class='colonne2'>
					".$msg["finance_statut_expl_perdu"].":
				</div>		
				<div class='colonne_suite'>
					!!statut_expl!!
				</div>
			</div>
			<div class='row'> 
				<div class='colonne2'>
					".$msg["finance_statut_lecteur_en_recouvrement"].":
				</div>		
				<div class='colonne_suite'>
					!!statut_empr!!
				</div>				
			</div>	
			<div class='row'>	
			</div>	
		</div>	
		<div class='row'>	
			<input type='submit' class='bouton' value='".$msg["finance_amende_modifier"]."'\">			
		</div>	
	</form>	
	";	
	
	$requete="select idstatut,statut_libelle from docs_statut";
	$resultat=mysql_query($requete, $dbh);
	$list_statut="<select name='statut_perdu' id='statut_perdu'>\n";
	while ($r=mysql_fetch_object($resultat)) {
		$list_statut.="<option value='".$r->idstatut."' ";
		if ($r->idstatut==$finance_statut_perdu) $list_statut.="selected='selected' ";
		$list_statut.=">".htmlentities($r->statut_libelle,ENT_QUOTES,$charset)."</option>\n";
	}
	$list_statut.="</select>\n";
	$result = str_replace( "!!statut_expl!!",$list_statut,$result);	
	
	
	$requete="select idstatut,statut_libelle from empr_statut";
	$resultat=mysql_query($requete, $dbh);
	$list_statut="<select name='statut_empr' id='statut_empr'>\n<option value='0' ";
	if(!$finance_recouvrement_lecteur_statut)$list_statut.="selected='selected' ";
	$list_statut.=">".htmlentities($msg["finance_statut_lecteur_no_change"],ENT_QUOTES,$charset)."</option>\n";
	while ($r=mysql_fetch_object($resultat)) {
		$list_statut.="<option value='".$r->idstatut."' ";
		if ($r->idstatut==$finance_recouvrement_lecteur_statut) $list_statut.="selected='selected' ";
		$list_statut.=">".htmlentities($r->statut_libelle,ENT_QUOTES,$charset)."</option>\n";
	}
	$list_statut.="</select>\n";	
	$result = str_replace( "!!statut_empr!!",$list_statut,$result);	
	echo $result;
}

function update_loststatus_fromform() {
	global $statut_perdu, $dbh, $finance_statut_perdu,$statut_empr, $finance_recouvrement_lecteur_statut;
	
	$requete="update parametres set valeur_param='".$statut_perdu."' where type_param='finance' and sstype_param='statut_perdu'";
	mysql_query($requete, $dbh);
	$finance_statut_perdu=stripslashes($statut_perdu);
	
	$requete="update parametres set valeur_param='".$statut_empr."' where type_param='finance' and sstype_param='recouvrement_lecteur_statut'";
	mysql_query($requete, $dbh);
	$finance_recouvrement_lecteur_statut=stripslashes($statut_empr);
}

if ($pmb_gestion_amende==1) {
	$admin_layout = str_replace('!!menu_sous_rub!!', $msg["finance_amendes_relances"], $admin_layout);  
	  print $admin_layout;
		switch ($action) {
			case 'update':
				//Mise à jour !!
				$requete="update parametres set valeur_param='".$relance_1."' where type_param='finance' and sstype_param='relance_1'";
				mysql_query($requete);
				$finance_relance_1=stripslashes($relance_1);
				$requete="update parametres set valeur_param='".$relance_2."' where type_param='finance' and sstype_param='relance_2'";
				mysql_query($requete);
				$finance_relance_2=stripslashes($relance_2);
				$requete="update parametres set valeur_param='".$relance_3."' where type_param='finance' and sstype_param='relance_3'";
				mysql_query($requete);
				$finance_relance_3=stripslashes($relance_3);
				$requete="update parametres set valeur_param='".$statut_perdu."' where type_param='finance' and sstype_param='statut_perdu'";
				mysql_query($requete);
				$finance_statut_perdu=stripslashes($statut_perdu);
				show_amende_parameters();
				break;
			case 'modif':
				//Formulaire de mise à jour
				$finance_amende_relance_form=str_replace("!!relance_1!!",htmlentities($finance_relance_1,ENT_QUOTES,$charset),$finance_amende_relance_form);
				$finance_amende_relance_form=str_replace("!!relance_2!!",htmlentities($finance_relance_2,ENT_QUOTES,$charset),$finance_amende_relance_form);
				$finance_amende_relance_form=str_replace("!!relance_3!!",htmlentities($finance_relance_3,ENT_QUOTES,$charset),$finance_amende_relance_form);
				$requete="select idstatut,statut_libelle from docs_statut";
				$resultat=mysql_query($requete);
				$list_statut="<select name='statut_perdu' id='statut_perdu'>\n";
				while ($r=mysql_fetch_object($resultat)) {
					$list_statut.="<option value='".$r->idstatut."' ";
					if ($r->idstatut==$finance_statut_perdu) $list_statut.="selected='selected' ";
					$list_statut.=">".htmlentities($r->statut_libelle,ENT_QUOTES,$charset)."</option>\n";
				}
				$list_statut.="</select>\n";
				$finance_amende_relance_form=str_replace("!!statut_perdu!!",$list_statut,$finance_amende_relance_form);
				print $finance_amende_relance_form;
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
	$admin_menu_quotas="<span><a href='./admin.php?categ=finance&sub=amendes_relance&action=edit_loststatus'>".$msg["finance_statut_perdu"]."</a></span>&nbsp;";
	for ($i=0; $i<count($_quotas_types_); $i++) {	
		if ($_quotas_types_[$i]["FILTER_ID"]=="amende_relance") {
			$admin_menu_quotas.="<span><a href='./admin.php?categ=finance&sub=amendes_relance&quota=".$_quotas_types_[$i]["ID"]."'>".$_quotas_types_[$i]["SHORT_COMMENT"]."</a></span>\n";
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
			switch ($action) {
				case "edit_loststatus":
					show_lost_status_form();
					break;
				case "updateloststatus":
					update_loststatus_fromform();
					show_lost_status_form();
					break;
				default:
					break;
			}
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