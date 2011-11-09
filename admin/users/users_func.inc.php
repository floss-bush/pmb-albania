<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: users_func.inc.php,v 1.29.4.1 2011-09-13 09:57:48 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/entites.class.php");
require_once("$class_path/coordonnees.class.php");

// affichage du form de création/modification utilisateur
function user_form($login="", $nom="", $prenom="", $flag=3, $id=0, $lang="", $nb_per_page_search=10, $nb_per_page_select=10, $nb_per_page_gestion=10, $form_param_default="", $form_user_email="", $form_user_alert_resamail="0", $usr_grp=FALSE ) {

	global $msg;
	global $admin_user_form;
	global $charset;
	global $password_field;
	global $include_path ;
	
	$user_encours=$_COOKIE["PhpMyBibli-LOGIN"];
	if(($id == 1) || ($login==$user_encours) || ($id==0)) // $id est admin ou $login est l'utilisateur en cours
		$admin_user_form =str_replace('!!bouton_suppression!!', "", $admin_user_form);
	else 
		$admin_user_form =str_replace('!!bouton_suppression!!', " <input class='bouton' type='button' value=' $msg[63] ' onClick=\"javascript:confirmation_delete(!!id!!,'$login')\" /> ", $admin_user_form);
	
	if(!$id) $title = $msg[85]; // ajout
	else $title = $msg[90]; 	// modification

	$admin_user_form = str_replace('!!id!!', $id, $admin_user_form);
	$admin_user_form = str_replace('!!title!!', $title, $admin_user_form);
	$admin_user_form = str_replace('!!login!!', $login, $admin_user_form);
	$admin_user_form = str_replace('!!nom!!', $nom, $admin_user_form);
	$admin_user_form = str_replace('!!prenom!!', $prenom, $admin_user_form);
	$admin_user_form = str_replace('!!nb_per_page_search!!', $nb_per_page_search, $admin_user_form);
	$admin_user_form = str_replace('!!nb_per_page_select!!', $nb_per_page_select, $admin_user_form);
	$admin_user_form = str_replace('!!nb_per_page_gestion!!', $nb_per_page_gestion, $admin_user_form);
	
	if(!$id) $admin_user_form = str_replace('!!password_field!!', $password_field, $admin_user_form);
	else $admin_user_form = str_replace('!!password_field!!', '', $admin_user_form);

	$flag & ADMINISTRATION_AUTH ? $admin_flg_form = "checked " : $admin_flg_form = "";
	$flag & CIRCULATION_AUTH ? $circ_flg_form = "checked " : $circ_flg_form = "";
	$flag & CATALOGAGE_AUTH ? $catal_flg_form = "checked " : $catal_flg_form = "";
	$flag & AUTORITES_AUTH ? $auth_flg_form = "checked " : $auth_flg_form = "";
	$flag & EDIT_AUTH ? $edit_flg_form = "checked " : $edit_flg_form = "";
	$flag & SAUV_AUTH ? $sauv_flg_form = "checked " : $sauv_flg_form = "";
	$flag & DSI_AUTH ? $dsi_flg_form = "checked " : $dsi_flg_form = "";
	$flag & PREF_AUTH ? $pref_flg_form = "checked " : $pref_flg_form = "";
	$flag & ACQUISITION_AUTH ? $acquisition_flg_form = "checked " : $acquisition_flg_form = "";
	$flag & RESTRICTCIRC_AUTH ? $restrictcirc_flg_form = "checked " : $restrictcirc_flg_form = "";
	$flag & THESAURUS_AUTH ? $thesaurus_flg_form = "checked " : $thesaurus_flg_form = "";
	$flag & TRANSFERTS_AUTH ? $transferts_flg_form = "checked " : $transferts_flg_form = "";
	$flag & EXTENSIONS_AUTH ? $extensions_flg_form = "checked " : $extensions_flg_form = "";
	$flag & DEMANDES_AUTH ? $demandes_flg_form = "checked " : $extensions_flg_form = "";
	$flag & FICHES_AUTH ? $fiches_flg_form = "checked " : $fiches_flg_form = "";
	
	$admin_user_form = str_replace('!!admin_flg!!', $admin_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!catal_flg!!', $catal_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!circ_flg!!', $circ_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!auth_flg!!', $auth_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!edit_flg!!', $edit_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!sauv_flg!!', $sauv_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!dsi_flg!!', $dsi_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!pref_flg!!', $pref_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!acquisition_flg!!', $acquisition_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!restrictcirc_flg!!', $restrictcirc_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!thesaurus_flg!!', $thesaurus_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!transferts_flg!!', $transferts_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!extensions_flg!!', $extensions_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!demandes_flg!!', $demandes_flg_form, $admin_user_form);
	$admin_user_form = str_replace('!!fiches_flg!!', $fiches_flg_form, $admin_user_form);
	
	if ($form_user_alert_resamail==1) $alert_resa_mail=" checked"; 
	else $alert_resa_mail="";
	$admin_user_form = str_replace('!!alter_resa_mail!!', $alert_resa_mail, $admin_user_form);
	$admin_user_form = str_replace('!!user_email!!', $form_user_email, $admin_user_form);
			

	if(!$id) $form_type = '1';
	else $form_type = '0';

	// récupération des codes langues
	$la = new XMLlist("$include_path/messages/languages.xml", 0);
	$la->analyser();
	$languages = $la->table;

	// constitution du sélecteur
	$selector = "<select name='user_lang'>	";
	while(list($codelang, $libelle) = each($languages)) {
		// arabe seulement si on est en utf-8
		if (($charset != 'utf-8' and $codelang != 'ar') or ($charset == 'utf-8')) {
			if($lang == $codelang) $selector .= "<option value='".htmlentities($codelang,ENT_QUOTES, $charset)."' SELECTED>".htmlentities($libelle,ENT_QUOTES, $charset)."</option>";
			else $selector .= "<option value='".htmlentities($codelang,ENT_QUOTES, $charset)."'>".htmlentities($libelle,ENT_QUOTES, $charset)."</option>";
		}
	}
	$selector .= '</select>';
	
	$admin_user_form = str_replace('!!select_lang!!', $selector, $admin_user_form);
	$admin_user_form = str_replace('!!form_type!!', $form_type, $admin_user_form);
	$admin_user_form = str_replace('!!form_param_default!!', $form_param_default, $admin_user_form);
	
	//groupes
	if ($usr_grp!==FALSE) {
		$q = "select * from users_groups order by grp_name ";
		$sel_group = gen_liste($q, 'grp_id', 'grp_name', 'sel_group', '', $usr_grp, '0', $msg[128], '0',$msg[128]);
		$sel_group = "<label class='etiquette'>".htmlentities($msg['admin_usr_grp_aff'], ENT_QUOTES, $charset).'</label><br />'.$sel_group;
		$admin_user_form = str_replace('<!-- sel_group -->', $sel_group, $admin_user_form);
	}
	print confirmation_delete("./admin.php?categ=users&sub=users&action=del&id=");
	print $admin_user_form;
	
}

function show_users($dbh) {

	global $msg;
	global $admin_user_list;
	global $admin_user_link1;
	
	// affichage du tableau des utilisateurs
	$requete = "SELECT * FROM users ORDER BY username";
	$res = mysql_query($requete, $dbh);

	$nbr = mysql_num_rows($res);

	while(($row=mysql_fetch_object($res))) {

		// réinitialisation des chaînes
		$dummy = $admin_user_list;
		$dummy1 = $admin_user_link1;
		
		$flag = "<img src='./images/flags/".$row->user_lang.".gif' width='24' height='16' vspace='3'>";

		$dummy =str_replace('!!user_link!!', $dummy1, $dummy);
		$dummy =str_replace('!!user_name!!', "$row->prenom $row->nom", $dummy);
		$dummy =str_replace('!!user_login!!', $row->username, $dummy);

		if($row->rights & ADMINISTRATION_AUTH)
			$dummy =str_replace('!!nuseradmin!!', '<img src=./images/coche.gif align=top hspace=3>' , $dummy);
		else 
			$dummy =str_replace('!!nuseradmin!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);

		if($row->rights & CATALOGAGE_AUTH)
			$dummy =str_replace('!!nusercatal!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nusercatal!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);

		if($row->rights & CIRCULATION_AUTH)
			$dummy =str_replace('!!nusercirc!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nusercirc!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);

		if($row->rights & PREF_AUTH)
			$dummy =str_replace('!!nuserpref!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nuserpref!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);

		if($row->rights & AUTORITES_AUTH)
			$dummy =str_replace('!!nuserauth!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nuserauth!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);
		if($row->rights & EDIT_AUTH)
			$dummy =str_replace('!!nuseredit!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nuseredit!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);
		if($row->rights & SAUV_AUTH)
			$dummy =str_replace('!!nusersauv!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nusersauv!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);

		if($row->rights & DSI_AUTH)
			$dummy =str_replace('!!nuserdsi!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nuserdsi!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);
			
		if($row->rights & ACQUISITION_AUTH)
			$dummy =str_replace('!!nuseracquisition!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nuseracquisition!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);
			
		if($row->rights & RESTRICTCIRC_AUTH)
			$dummy =str_replace('!!nuserrestrictcirc!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nuserrestrictcirc!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);

		if($row->rights & THESAURUS_AUTH)
			$dummy =str_replace('!!nuserthesaurus!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nuserthesaurus!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);
			
		if($row->rights & TRANSFERTS_AUTH)
			$dummy =str_replace('!!nusertransferts!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nusertransferts!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);

		if($row->rights & EXTENSIONS_AUTH)
			$dummy =str_replace('!!nuserextensions!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nuserextensions!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);
		
		if($row->rights & DEMANDES_AUTH)
			$dummy =str_replace('!!nuserdemandes!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);
		else 
			$dummy =str_replace('!!nuserdemandes!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);
		if($row->rights & FICHES_AUTH)
			$dummy =str_replace('!!nuserfiches!!', '<img src=./images/coche.gif align=top hspace=3>', $dummy);	
		else 
			$dummy =str_replace('!!nuserfiches!!', '<img src=./images/uncoche.gif align=top hspace=3>', $dummy);		
						
		$dummy = str_replace('!!lang_flag!!', $flag, $dummy);
		$dummy = str_replace('!!nuserlogin!!', $row->username, $dummy);
		$dummy = str_replace('!!nuserid!!', $row->userid, $dummy);
		
		if($row->user_alert_resamail)
			$dummy =str_replace('!!user_alert_resamail!!', $msg['alert_resa_user_mail'].'<img src=./images/tick.gif align=top hspace=3>' , $dummy);
		else 
			$dummy =str_replace('!!user_alert_resamail!!', '', $dummy);
		
		print $dummy;
		}
	print "<div class='row'>
		<input class='bouton' type='button' value=' $msg[85] ' onClick=\"document.location='./admin.php?categ=users&sub=users&action=add'\" />
		</div>";

}
	
	
function get_coordonnees_etab($user_id='0', $field_values, $current_field, $form_name) {

	global $dbh, $msg, $charset;
	global $acquisition_active;
	global $user_acquisition_adr_form;
	
	if (!$acquisition_active ) return;
	if (!($field_values[7] & ACQUISITION_AUTH)) return;
	if ($user_id=='0') return;
	
	//Affichage de la liste des bibliothèques auxquelles a accès l'utilisateur
	$q = entites::list_biblio($user_id);
	$res = mysql_query($q, $dbh);
	$nbr = mysql_num_rows($res);
	
	if ($nbr == '0') return;

	$tab1 = explode('|', $field_values[$current_field]);

	$tab_adr=array();
	foreach ($tab1 as $key=>$value) {
		$tab2=explode(',', $value);
		$tab_adr[$tab2[0]]['id_adr_fac']=$tab2[1];
		$tab_adr[$tab2[0]]['id_adr_liv']=$tab2[2];
	}

	while($row=mysql_fetch_object($res)){
		
		$acquisition_user_param.= "<div class='row'>";
		$acquisition_user_param.= "<label class='etiquette'>".htmlentities($row->raison_sociale, ENT_QUOTES, $charset)."</label>";
		
		$temp_adr_form = $user_acquisition_adr_form;
		
		if ($tab_adr[$row->id_entite]['id_adr_fac']) {
			$coord = new coordonnees($tab_adr[$row->id_entite]['id_adr_fac']);
			$id_adr_fac = $coord->id_contact;
			if($coord->libelle != '') $adr_fac = htmlentities($coord->libelle, ENT_QUOTES, $charset)."\n";
			if($coord->contact != '') $adr_fac.= htmlentities($coord->contact, ENT_QUOTES, $charset)."\n";
			if($coord->adr1 != '') $adr_fac.= htmlentities($coord->adr1, ENT_QUOTES, $charset)."\n";
			if($coord->adr2 != '') $adr_fac.= htmlentities($coord->adr2, ENT_QUOTES, $charset)."\n";
			if($coord->cp !='') $adr_fac.= htmlentities($coord->cp, ENT_QUOTES, $charset).' ';
			if($coord->ville != '') $adr_fac.= htmlentities($coord->ville, ENT_QUOTES, $charset);
		} else {
			$id_adr_fac = '0';
			$adr_fac = '';
		}

		if ($tab_adr[$row->id_entite]['id_adr_liv']) {
			$coord = new coordonnees($tab_adr[$row->id_entite]['id_adr_liv']);
			$id_adr_liv = $coord->id_contact;
			if($coord->libelle != '') $adr_liv = htmlentities($coord->libelle, ENT_QUOTES, $charset)."\n";
			if($coord->contact != '') $adr_liv.= htmlentities($coord->contact, ENT_QUOTES, $charset)."\n"; 
			if($coord->adr1 != '') $adr_liv.= htmlentities($coord->adr1, ENT_QUOTES, $charset)."\n";
			if($coord->adr2 != '') $adr_liv.= htmlentities($coord->adr2, ENT_QUOTES, $charset)."\n";
			if($coord->cp !='') $adr_liv.= htmlentities($coord->cp, ENT_QUOTES, $charset).' ';
			if($coord->ville != '') $adr_liv.= htmlentities($coord->ville, ENT_QUOTES, $charset);
		} else {
			$id_adr_liv = 0;
			$adr_liv = '';
		}

		$temp_adr_form = str_replace('!!id_bibli!!',$row->id_entite, $temp_adr_form);
		$temp_adr_form = str_replace('!!id_adr_liv!!',$id_adr_liv, $temp_adr_form);
		$temp_adr_form = str_replace('!!adr_liv!!',$adr_liv, $temp_adr_form);
		$temp_adr_form = str_replace('!!id_adr_fac!!',$id_adr_fac, $temp_adr_form);
		$temp_adr_form = str_replace('!!adr_fac!!',$adr_fac, $temp_adr_form);
						
		$acquisition_user_param.= $temp_adr_form;
		$acquisition_user_param.= "</div>";
		
	}
	$acquisition_user_param = str_replace('!!form_name!!', $form_name, $acquisition_user_param);
	$acquisition_user_param="<hr /><div class='row'>".htmlentities($msg['acquisition_user_deflt_adr'], ENT_QUOTES, $charset).$acquisition_user_param."</div>";
	return $acquisition_user_param;			
}


function set_coordonnees_etab() {

	global $id_adr_fac, $id_adr_liv;

	$acquisition_user_param = "speci_coordonnees_etab = '' ";	
	if (!is_array($id_adr_fac)) return $acquisition_user_param ;
	
	ksort($id_adr_fac);
	reset($id_adr_fac);
	$i=0;
	$j=count($id_adr_fac);
	while (list($key, $val) = each($id_adr_fac)) {
		$acquisition_user_param.=$key.','.$val.','.$id_adr_liv[$key];
		$i++;
		if ($i < $j) $acquisition_user_param.='|';
	};
	
	$acquisition_user_param = "speci_coordonnees_etab = '".$acquisition_user_param."' "; 
	return $acquisition_user_param;			
}


