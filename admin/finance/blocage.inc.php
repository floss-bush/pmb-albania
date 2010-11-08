<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: blocage.inc.php,v 1.2 2007-03-10 08:32:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Gestion des amendes

require_once("$include_path/templates/finance.tpl.php");

function show_blocage_parameters() {
	global $msg;
	global $finance_blocage_abt,$finance_blocage_pret,$finance_blocage_amende,$pmb_gestion_abonnement,$pmb_gestion_tarif_prets,$pmb_gestion_amende;
	
	print "<table><tr><th>&nbsp;</th><th>".$msg["finance_blocage_no"]."</th><th>".$msg["finance_blocage_force"]."</th><th>".$msg["finance_blocage_yes"]."</th></tr>
	";
	
	if ($pmb_gestion_abonnement) {
		print "
			<tr><td>".$msg["finance_blocage_abt"]."</td><td style='text-align:center'>";
		if (!$finance_blocage_abt) print "X";
		print "</td><td style='text-align:center'>";
		if ($finance_blocage_abt==1) print "X";
		print "</td><td style='text-align:center'>";
		if ($finance_blocage_abt==2) print "X";
		print "</td></tr>
		";
	}
	if ($pmb_gestion_tarif_prets) {
		print "
			<tr><td>".$msg["finance_blocage_pret"]."</td><td style='text-align:center'>";
		if (!$finance_blocage_pret) print "X";
		print "</td><td style='text-align:center'>";
		if ($finance_blocage_pret==1) print "X";
		print "</td><td style='text-align:center'>";
		if ($finance_blocage_pret==2) print "X";
		print "</td></tr>
		";
	}
	if ($pmb_gestion_amende) {
		print "
			<tr><td>".$msg["finance_blocage_amende"]."</td><td style='text-align:center'>";
		if (!$finance_blocage_amende) print "X";
		print "</td><td style='text-align:center'>";
		if ($finance_blocage_amende==1) print "X";
		print "</td><td style='text-align:center'>";
		if ($finance_blocage_amende==2) print "X";
		print "</td></tr>
		";
	}
	print "</table>";
	print "<div class='row'></div>
		<div class='row'><input type='button' class='bouton' value='".$msg["finance_amende_modifier"]."' onClick=\"document.location='./admin.php?categ=finance&sub=blocage&action=modif';\"></div>";
}

switch ($action) {
	case 'update':
		//Mise à jour !!
		$requete="update parametres set valeur_param='".$blocage_abt."' where type_param='finance' and sstype_param='blocage_abt'";
		mysql_query($requete);
		$finance_blocage_abt=stripslashes($blocage_abt);
		$requete="update parametres set valeur_param='".$blocage_pret."' where type_param='finance' and sstype_param='blocage_pret'";
		mysql_query($requete);
		$finance_blocage_pret=stripslashes($blocage_pret);
		$requete="update parametres set valeur_param='".$blocage_amende."' where type_param='finance' and sstype_param='blocage_amende'";
		mysql_query($requete);
		$finance_blocage_amende=stripslashes($blocage_amende);
		show_blocage_parameters();
		break;
	case 'modif':
		//Formulaire de mise à jour
		if ($pmb_gestion_abonnement) {
			$abt="
				<tr><td>".$msg["finance_blocage_abt"]."</td><td style='text-align:center'><input type='radio' name='blocage_abt' value='0' ";
			if (!$finance_blocage_abt) $abt.= "checked";
			$abt.="></td><td style='text-align:center'><input type='radio' name='blocage_abt' value='1' ";
			if ($finance_blocage_abt==1) $abt.= "checked";
			$abt.="></td><td style='text-align:center'><input type='radio' name='blocage_abt' value='2' ";
			if ($finance_blocage_abt==2) $abt.= "checked";
			$abt.="></td></tr>
			";
		}
		if ($pmb_gestion_tarif_prets) {
			$pret="
				<tr><td>".$msg["finance_blocage_pret"]."</td><td style='text-align:center'><input type='radio' name='blocage_pret' value='0' ";
			if (!$finance_blocage_pret) $pret.= "checked";
			$pret.="></td><td style='text-align:center'><input type='radio' name='blocage_pret' value='1' ";
			if ($finance_blocage_pret==1) $pret.= "checked";
			$pret.="></td><td style='text-align:center'><input type='radio' name='blocage_pret' value='2' ";
				if ($finance_blocage_pret==2) $pret.= "checked";
			$pret.="></td></tr>
			";
		}
		if ($pmb_gestion_amende) {
			$amende="
				<tr><td>".$msg["finance_blocage_amende"]."</td><td style='text-align:center'><input type='radio' name='blocage_amende' value='0' ";
			if (!$finance_blocage_amende) $amende.= "checked";
			$amende.="></td><td style='text-align:center'><input type='radio' name='blocage_amende' value='1' ";
			if ($finance_blocage_amende==1) $amende.= "checked";
			$amende.="></td><td style='text-align:center'><input type='radio' name='blocage_amende' value='2' ";
			if ($finance_blocage_amende==2) $amende.= "checked";
			$amende.="></td></tr>
			";
		}
		$finance_blocage_form=str_replace("!!blocage_abt!!",$abt,$finance_blocage_form);
		$finance_blocage_form=str_replace("!!blocage_pret!!",$pret,$finance_blocage_form);
		$finance_blocage_form=str_replace("!!blocage_amende!!",$amende,$finance_blocage_form);
		print $finance_blocage_form;
		break;
	default:
		//Gestion simple
		show_blocage_parameters();
		break;
}

?>