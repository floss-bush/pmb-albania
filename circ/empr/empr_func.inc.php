<?php
// +-------------------------------------------------+
// ? 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_func.inc.php,v 1.60 2010-08-19 07:30:42 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fonctions pour la gestion des emprunteurs

include("$include_path/templates/empr.tpl.php");
require_once($class_path."/parametres_perso.class.php");
if ($ldap_accessible) require_once($include_path."/ldap_param.inc.php");

// affichage de la liste des langues
function make_empr_lang_combo($lang='') {
	// retourne le combo des langues avec la langue $lang selectionn?e
	// n?cessite l'inclusion de XMLlist.class.php (normalement c'est d?j? le cas partout
	global $include_path;
	global $msg;
	global $charset;

	// langue par d?faut
	if(!$lang) $lang="fr_FR";
	$langues = new XMLlist("$include_path/messages/languages.xml");
	$langues->analyser();
	$clang = $langues->table;
	$combo = "<select name='form_empr_lang' id='empr_lang'>";
	while(list($cle, $value) = each($clang)) {
		// arabe seulement si on est en utf-8
		if (($charset != 'utf-8' and $cle != 'ar') or ($charset == 'utf-8')) {
			if(strcmp($cle, $lang) != 0) $combo .= "<option value='$cle'>$value ($cle)</option>";
				else $combo .= "<option value='$cle' selected>$value ($cle)</option>";
		}
	}
	$combo .= "</select>";
	return $combo;
	}

// affichage de la liste lecteurs pour s?lection
function list_empr($cb, $empr_list, $nav_bar, $nb_total=0, $where_intitule="") {
	global $empr_list_tmpl;
	
	if ($nb_total>0) $empr_list_tmpl = str_replace("<!--!!nb_total!!-->", "(".$nb_total.")", $empr_list_tmpl);

	$empr_list_tmpl = str_replace("!!cle!!", $cb, $empr_list_tmpl);
	$empr_list_tmpl = str_replace("!!where_intitule!!", $where_intitule, $empr_list_tmpl);
	$empr_list_tmpl = str_replace("!!list!!", $empr_list, $empr_list_tmpl);
	$empr_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $empr_list_tmpl);
		
	print pmb_bidi($empr_list_tmpl);
	}

// form de saisie cb emprunteur
function get_cb($title, $message, $title_form, $form_action, $check=0, $cb_initial="", $creation=0) {
	global $empr_cb_tmpl;
	global $empr_cb_tmpl_create;
	global $script1;
	global $script2;
	global $deflt2docs_location, $pmb_lecteurs_localises, $empr_location_id, $param_allloc ;
	
	if ($cb_initial===0) $cb_initial="" ; 
	if ($creation==1) $empr_cb_tmpl = $empr_cb_tmpl_create;
	switch ($check) {
		case '1':
			// script javascript 1 : checke seulement si le champ contient des trucs
			$empr_cb_tmpl = str_replace("!!script!!", $script1, $empr_cb_tmpl);
			break ;
		case '2':
			// script javascript 2 : checke si le champ ne contient que de l'alpha
			$empr_cb_tmpl = str_replace("!!script!!", $script2, $empr_cb_tmpl);
			break ;
		case '0':
		default:
			// aucun test
			$empr_cb_tmpl = str_replace("!!script!!", "", $empr_cb_tmpl);
			break ;
		}
	$empr_cb_tmpl = str_replace("!!titre_formulaire!!", $title_form, $empr_cb_tmpl);
	$empr_cb_tmpl = str_replace("!!form_action!!", $form_action, $empr_cb_tmpl);
	$empr_cb_tmpl = str_replace("!!title!!", $title, $empr_cb_tmpl);
	$empr_cb_tmpl = str_replace("!!message!!", $message, $empr_cb_tmpl);
	$empr_cb_tmpl = str_replace("!!cb_initial!!", (string)$cb_initial, $empr_cb_tmpl);
	
	if ($pmb_lecteurs_localises) {
		if ($empr_location_id) $deflt2docs_location=$empr_location_id;
		elseif ($param_allloc) $deflt2docs_location=0;
		$empr_cb_tmpl = str_replace("!!restrict_location!!", docs_location::gen_combo_box_empr($deflt2docs_location), $empr_cb_tmpl);
	} else 
		$empr_cb_tmpl = str_replace("!!restrict_location!!", "", $empr_cb_tmpl);
	print pmb_bidi($empr_cb_tmpl);
	}

// form de saisie cb emprunteur
function get_login_empr_pret($title, $message, $title_form, $form_action, $check=0, $cb_initial="") {
	global $login_empr_pret_tmpl;
	global $script1;
	global $script2;
	
	if ($cb_initial===0) $cb_initial="" ; 
	$login_empr_pret_tmpl = str_replace("!!titre_formulaire!!", $title_form, $login_empr_pret_tmpl);
	$login_empr_pret_tmpl = str_replace("!!form_action!!", $form_action, $login_empr_pret_tmpl);
	$login_empr_pret_tmpl = str_replace("!!title!!", $title, $login_empr_pret_tmpl);
	$login_empr_pret_tmpl = str_replace("!!message!!", $message, $login_empr_pret_tmpl);
	$login_empr_pret_tmpl = str_replace("!!cb_initial!!", (string)$cb_initial, $login_empr_pret_tmpl);
	
	print pmb_bidi($login_empr_pret_tmpl);
	}

// affichage du form emprunteurs (g?re modif et cr?ation).
function show_empr_form($form_action, $form_cancel, $link, $id, $cb,$duplicate_empr_from_id="") {
	global $empr_form;
	global $msg;
	global $charset;
	global $dbh ;
	global $biblio_email;
	global $aff_list_empr;
	global $deflt2docs_location;
	global $pmb_lecteurs_localises ;
	global $pmb_gestion_abonnement,$pmb_gestion_financiere;
	global $database_window_title ;
	global $lang;
	global $pmb_rfid_activate, $pmb_rfid_serveur_url;
		
	//Ici on r?cup?re les valeurs des champs personalis?s
	$p_perso=new parametres_perso("empr");
	// si $id est fourni, il s'agit d'une modification. on r?cup?re les donn?es dans $link
	if($id) {
		// modification
		echo window_title($database_window_title.$msg[55]);
		$entete=$msg[55];		
		if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url) {
			$script_rfid_encode="if(script_rfid_encode()==false) return false;";
		} else 	$script_rfid_encode='';
		$empr_form = str_replace("!!questionrfid!!",   $script_rfid_encode, $empr_form);
		$requete = "SELECT * FROM empr WHERE id_empr='$id' ";
		$res = mysql_query($requete, $link);
		if($res) {
			$empr = mysql_fetch_object($res);
			} else {
				error_message( $msg[53], $msg[54], 0);
				}

		} else {
			// création
			$entete=$msg[15];
			$empr_form = str_replace("!!questionrfid!!",  '' , $empr_form);
			}
	if ($duplicate_empr_from_id) {
		$empr_form = str_replace("!!id!!",   "", $empr_form);
		$empr_form = str_replace("!!entete!!", $msg["empr_duplicate"], $empr_form);
	} else {
		 $empr_form = str_replace("!!id!!",   $id, $empr_form);
		 $empr_form = str_replace("!!entete!!", $entete, $empr_form);
	}
	$empr_form = str_replace("!!form_action!!",   $form_action, $empr_form);
	
	if($empr->empr_cb) { //Si il y a un code lecteur
		if (!$duplicate_empr_from_id) $empr_form = str_replace("!!cb!!",      $empr->empr_cb,      $empr_form);
			else $empr_form = str_replace("!!cb!!",      $cb,      $empr_form);
		$date_clic   = "onClick=\"openPopUp('./select.php?what=calendrier&caller=empr_form&date_caller=".preg_replace('/-/', '', $empr->empr_date_adhesion)."&param1=form_adhesion&param2=form_adhesion_lib&auto_submit=NO&date_anterieure=YES', 'date_adhesion', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"  ";

		$adhesion = "
				<input type='hidden' name='form_adhesion' value='".preg_replace('/-/', '', $empr->empr_date_adhesion)."' />
				<input class='bouton' type='button' name='form_adhesion_lib' value='".formatdate($empr->empr_date_adhesion)."' ".$date_clic." />";
				
		$empr_form = str_replace("!!adhesion!!", $adhesion, $empr_form);

		$date_clic   = "onClick=\"openPopUp('./select.php?what=calendrier&caller=empr_form&date_caller=".preg_replace('/-/', '', $empr->empr_date_expiration)."&param1=form_expiration&param2=form_expiration_lib&auto_submit=NO&date_anterieure=YES', 'date_adhesion', 205, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"  ";

		$expiration  = "
				<input type='hidden' id='form_expiration' name='form_expiration' value='".preg_replace('/-/', '', $empr->empr_date_expiration)."' />
				<input class='bouton' type='button' id='form_expiration_lib' name='form_expiration_lib' value='".formatdate($empr->empr_date_expiration)."' ".$date_clic." />";
		
		$empr_form = str_replace("!!expiration!!", $expiration, $empr_form);

		// ajout ici des trucs sur la relance adh?sion
		$empr_temp = new emprunteur($id, '', FALSE, 0) ;
		$aff_relance = "";
		if ($empr_temp->adhesion_renouv_proche() || $empr_temp->adhesion_depassee()) {
			if ($empr_temp->adhesion_depassee()) $mess_relance = $msg[empr_date_depassee];
				else $mess_relance = $msg[empr_date_renouv_proche];

			$rqt="select duree_adhesion from empr_categ where id_categ_empr='$empr_temp->categ'";
			$res_dur_adhesion = mysql_query($rqt, $dbh);
			$row = mysql_fetch_row($res_dur_adhesion);
			$nb_jour_adhesion_categ = $row[0];

			$rqt_date = "select date_add('$empr_temp->date_expiration',INTERVAL 1 DAY) as nouv_date_debut,
					date_add('$empr_temp->date_expiration',INTERVAL $nb_jour_adhesion_categ DAY) as nouv_date_fin ";
			$resultatdate=mysql_query($rqt_date) or die ("<br /> $rqt_date ".mysql_error());
			$resdate=mysql_fetch_object($resultatdate);

			$nouv_date_debut = $resdate->nouv_date_debut ;
			$nouv_date_fin = $resdate->nouv_date_fin ;

			$nouv_date_debut_formatee = formatdate($nouv_date_debut) ;
			$nouv_date_fin_formatee = formatdate($nouv_date_fin) ;

			// on conserve la date d'adhésion initiale
			$action_prolonger = "this.form.form_expiration.value = '$nouv_date_fin';
				this.form.form_expiration_lib.value = '$nouv_date_fin_formatee';
				";

			$action_relance_courrier = "openPopUp('./pdf.php?pdfdoc=lettre_relance_adhesion&id_empr=$id', 'lettre', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes'); return(false) ";

			$aff_relance = "<div class='row'>
						<span class='erreur'>$mess_relance</span><br />
						<input class='bouton' type='button' value=\"".$msg[prolonger]."\" onClick=\"$action_prolonger\" />&nbsp;
						<input class='bouton' type='button' value=\"".$msg[prolong_courrier]."\" onClick=\"$action_relance_courrier\" />";

			if ($empr_temp->mail && $biblio_email ) {
				$action_relance_mail = "if (confirm('".$msg["mail_retard_confirm"]."')) {openPopUp('./mail.php?type_mail=mail_relance_adhesion&id_empr=$id', 'mail', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes'); } return(false) ";
				$aff_relance .= "&nbsp;<input class='bouton' type='button' value=\"".$msg[prolong_mail]."\" onClick=\"$action_relance_mail\" />";
				}

			$aff_relance .= "</div>";
			
			if (($pmb_gestion_financiere)&&($pmb_gestion_abonnement)) {
				$aff_relance.="<div class='row'><input type='radio' name='debit' value='0' id='debit_0' checked><label for='debit_0'>".$msg["finance_abt_no_debit"]."</label>&nbsp;<input type='radio' name='debit' value='1' id='debit_1' >";
				$aff_relance.="<label for='debit_1'>".$msg["finance_abt_debit_wo_caution"]."</label>&nbsp;";
				if ($pmb_gestion_abonnement==2) $aff_relance.="<input type='radio' name='debit' value='2' id='debit_2'><label for='debit_2'>".$msg["finance_abt_debit_wt_caution"]."</label>";
				$aff_relance.="</div>";
				}
			
			}
		$empr_form = str_replace("!!adhesion_proche_depassee!!", $aff_relance, $empr_form);

		//Liste des types d'abonnement
		$list_type_abt="";
		if (($pmb_gestion_abonnement==2)&&($pmb_gestion_financiere)) {
			$requete="select * from type_abts order by type_abt_libelle ";
			$resultat_abt=mysql_query($requete);
			
			$user_loc=$deflt2docs_location;
			$t_type_abt=array();
			while ($res_abt=mysql_fetch_object($resultat_abt)) {
				$locs=explode(",",$res_abt->localisations);
				$as=array_search($user_loc,$locs);
				if ((($as!==false)&&($as!==null))||(!$res_abt->localisations)) {
					$t_type_abt[]=$res_abt;
				}
			}
			if (count($t_type_abt)) {
				$list_type_abt="<div class='row'>\n<label for='type_abt'>".$msg["finance_type_abt"]."</label></div>\n<div class='row'>\n<select name='type_abt' id='type_abt'>\n";
				for ($i=0; $i<count($t_type_abt); $i++) {
					$list_type_abt.="<option value='".$t_type_abt[$i]->id_type_abt."'";
					if ($empr->type_abt==$t_type_abt[$i]->id_type_abt) $list_type_abt.=" selected";
					$list_type_abt.=">".htmlentities($t_type_abt[$i]->type_abt_libelle,ENT_QUOTES,$charset)."</option>\n";
				}
				$list_type_abt.="</select></div>";
			}
		}
		if ($list_type_abt) $list_type_abt.="<div class='row'><hr /></div>\n";
		$empr_form = str_replace("!!typ_abonnement!!",$list_type_abt,$empr_form);
		} else { // cr?ation de lecteur
			$empr->empr_date_adhesion = today() ;
			$empr_form = str_replace("!!cb!!",      $cb,         $empr_form);
			$date_clic   = "onClick=\"openPopUp('./select.php?what=calendrier&caller=empr_form&date_caller=".preg_replace('/-/', '', $empr->empr_date_adhesion)."&param1=form_adhesion&param2=form_adhesion_lib&auto_submit=NO&date_anterieure=YES', 'date_adhesion', 250, 260, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"  ";
			$adhesion = "$msg[1401]$msg[1901]
					<input type='hidden' name='form_adhesion' value='".preg_replace('/-/', '', $empr->empr_date_adhesion)."'>
					<input class='bouton' type='button' name='form_adhesion_lib' value='".formatdate($empr->empr_date_adhesion)."' ".$date_clic." />";
			$empr_form = str_replace("!!adhesion!!", $adhesion, $empr_form);
			$empr_form = str_replace("!!adhesion_proche_depassee!!", "", $empr_form);
			$empr_form = str_replace("!!expiration!!",   "<input type='hidden' name='form_expiration' value=''>",   $empr_form);
		
			//Liste des types d'abonnement
			$list_type_abt="";
			if (($pmb_gestion_abonnement==2)&&($pmb_gestion_financiere)) {
				$requete="select * from type_abts";
				$resultat_abt=mysql_query($requete);
				
				$user_loc=$deflt2docs_location;
				$t_type_abt=array();
				while ($res_abt=mysql_fetch_object($resultat_abt)) {
					$locs=explode(",",$res_abt->localisations);
					$as=array_search($user_loc,$locs);
					if ((($as!==false)&&($as!==null))||(!$res_abt->localisations)) {
						$t_type_abt[]=$res_abt;
					}
				}
				if (count($t_type_abt)) {
					$list_type_abt="<div class='row'>\n<label for='type_abt'>".$msg["finance_type_abt"]."</label></div>\n<div class='row'>\n<select name='type_abt' id='type_abt'>\n";
					for ($i=0; $i<count($t_type_abt); $i++) {
						$list_type_abt.="<option value='".$t_type_abt[$i]->id_type_abt."'>".htmlentities($t_type_abt[$i]->type_abt_libelle,ENT_QUOTES,$charset)."</option>\n";
					}
					$list_type_abt.="</select></div>";
				}
			}
			if ($list_type_abt) $list_type_abt.="<div class='row'><hr /></div>\n";
			$empr_form = str_replace("!!typ_abonnement!!",$list_type_abt,$empr_form);
		}
		
	$empr_form = str_replace("!!nom!!",      htmlentities($empr->empr_nom   ,ENT_QUOTES, $charset), $empr_form);
	$empr_form = str_replace("!!prenom!!",      htmlentities($empr->empr_prenom   ,ENT_QUOTES, $charset), $empr_form);
	$empr_form = str_replace("!!adr1!!",      htmlentities($empr->empr_adr1   ,ENT_QUOTES, $charset),   $empr_form);
	$empr_form = str_replace("!!adr2!!",      htmlentities($empr->empr_adr2   ,ENT_QUOTES, $charset),   $empr_form);
	$empr_form = str_replace("!!cp!!",      htmlentities($empr->empr_cp   ,ENT_QUOTES, $charset), $empr_form);
	$empr_form = str_replace("!!ville!!",      htmlentities($empr->empr_ville   ,ENT_QUOTES, $charset),   $empr_form);
	$empr_form = str_replace("!!pays!!",      htmlentities($empr->empr_pays   ,ENT_QUOTES, $charset),   $empr_form);
	$empr_form = str_replace("!!mail!!",      htmlentities($empr->empr_mail   ,ENT_QUOTES, $charset),   $empr_form);
	$empr_form = str_replace("!!tel1!!",      htmlentities($empr->empr_tel1   ,ENT_QUOTES, $charset),   $empr_form);
	if(!$empr->empr_sms) $empr_sms_chk=''; else $empr_sms_chk="checked='checked'";
	$empr_form = str_replace('!!sms!!', $empr_sms_chk, $empr_form);
	$empr_form = str_replace("!!tel2!!",      htmlentities($empr->empr_tel2   ,ENT_QUOTES, $charset),   $empr_form);
	$empr_form = str_replace("!!prof!!",      htmlentities($empr->empr_prof   ,ENT_QUOTES, $charset),   $empr_form);
	if ($empr->empr_year != 0) $empr_form = str_replace("!!year!!",      htmlentities($empr->empr_year   ,ENT_QUOTES, $charset),   $empr_form);
		else $empr_form = str_replace("!!year!!", "", $empr_form);
	if (!$empr->empr_lang) $empr->empr_lang=$lang;
	$empr_form = str_replace('!!combo_empr_lang!!', make_empr_lang_combo($empr->empr_lang), $empr_form);
	
	if (!$duplicate_empr_from_id) { 
		$empr_form = str_replace('!!empr_login!!', $empr->empr_login, $empr_form);
		$empr_form = str_replace("!!empr_msg!!",      htmlentities($empr->empr_msg   ,ENT_QUOTES, $charset),   $empr_form);
	} else {
		$empr_form = str_replace('!!empr_login!!', "", $empr_form);
		$empr_form = str_replace("!!empr_msg!!", "",   $empr_form);
	}
	// on r?cup?re le select cat?gorie
	$requete = "SELECT id_categ_empr, libelle, duree_adhesion FROM empr_categ ORDER BY libelle ";
	$res = mysql_query($requete, $link);
	$nbr_lignes = mysql_num_rows($res);
	for($i=0; $i < $nbr_lignes; $i++) {
		$row = mysql_fetch_row($res);
		$categ_content .= "<option value='$row[0]'";
		if($row[0] == $empr->empr_categ) $categ_content .= " selected='selected'";
		$categ_content .= ">$row[1]</option>";
	}
	$empr_form = str_replace("!!categ!!",      $categ_content,   $empr_form);

	// on récupère le select statut
	$requete = "SELECT idstatut, statut_libelle FROM empr_statut ORDER BY statut_libelle ";
	
	//Si il n'y a pas de statut on prend celui définit pour l'utilisateur
	if(!$empr->empr_statut){
		global $deflt_empr_statut;
		$empr->empr_statut=$deflt_empr_statut;
	}
	
	$res = mysql_query($requete, $link);
	$nbr_lignes = mysql_num_rows($res);
	for($i=0; $i < $nbr_lignes; $i++) {
		$row = mysql_fetch_row($res);
		$statut_content .= "<option value='$row[0]'";
		if($row[0] == $empr->empr_statut) $statut_content .= " selected='selected'";
		$statut_content .= ">$row[1]</option>";
	}
	$empr_form = str_replace("!!statut!!",      $statut_content,   $empr_form);

	// et le select code stat
	// on r?cup?re le select cod stat
	$requete = "SELECT idcode, libelle FROM empr_codestat ORDER BY libelle ";
	$res = mysql_query($requete, $link);
	$nbr_lignes = mysql_num_rows($res);

	for($i=0; $i < $nbr_lignes; $i++) {
		$row = mysql_fetch_row($res);
		$cstat_content .= "<option value='$row[0]'";
		if($row[0] == $empr->empr_codestat) $cstat_content .= " selected='selected'";
		$cstat_content .= ">$row[1]</option>";
		}

	// mise ? jour du sexe
	switch($empr->empr_sexe) {
		case 1:
			$empr_form = str_replace("sexe_select_1", 'selected', $empr_form);
			break;
		case 2:
			$empr_form = str_replace("sexe_select_2", 'selected', $empr_form);
			break;
		default:
			$empr_form = str_replace("sexe_select_0", 'selected', $empr_form);
			break;
		}
	$empr_form = preg_replace("/sexe_select_[0-2]/m", '', $empr_form);
	$empr_form = str_replace("!!cstat!!",      $cstat_content,   $empr_form);

	// mise ? jour du groupe
	$requete = "SELECT id_groupe, libelle_groupe, max(if(empr_id='$id','$id',0)) as inscription FROM groupe left join empr_groupe on id_groupe=groupe_id  group by id_groupe, libelle_groupe ORDER BY libelle_groupe ";
	$groupe_form_aff = gen_liste_multiple ($requete, "id_groupe", "libelle_groupe", "inscription", "id_grp[]", "", $id, 0, $msg[empr_form_aucungroupe], 0,$msg[empr_form_nogroupe], 5) ;
	$empr_form = str_replace("!!groupe_ajout!!", $groupe_form_aff, $empr_form);

	$empr_form = str_replace("!!cancel!!",      $form_cancel,   $empr_form);

	// ldap MaxMan
	if ($empr->empr_ldap){
		$form_ldap="checked" ;
	}else{
		$form_ldap="";
	}
		//$empr_form = str_replace('!!empr_password!!', $empr_password, $empr_form);
	$empr_form = str_replace("!!ldap!!",$form_ldap,$empr_form);

	$empr_form = str_replace('!!empr_password!!', '', $empr_form);
	
	if (!$empr->empr_location) $empr->empr_location=$deflt2docs_location ;
	if ($pmb_lecteurs_localises) {
		$loc = "<div class='row'>
					<div class='row'>
						<label for='form_empr_location' class='etiquette'>$msg[empr_location]</label>
						</div>
					<div class='row'>
						!!localisation!!
						</div>
					</div>";
	
		$loc = str_replace('!!localisation!!', docs_location::gen_combo_box_empr($empr->empr_location, 0), $loc);
		$empr_form = str_replace('<!-- !!localisation!! -->', $loc, $empr_form);
		} else {
			$loc = "<input type='hidden' name='empr_location_id' value='".$empr->empr_location."'>" ; 
			$empr_form = str_replace('<!-- !!localisation!! -->', $loc, $empr_form);
			}
			
	//Champs persos
	$perso_=$p_perso->show_editable_fields($id);
	if (count($perso_["FIELDS"])) $perso = "<div class='row'></div>" ;
		else $perso="";
	$class="colonne2";
	for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
		$p=$perso_["FIELDS"][$i];
		$perso.="<div class='$class'>";
		$perso.="<div class='row'><label for='".$p["NAME"]."' class='etiquette'>".$p["TITRE"]."</label></div>\n";
		$perso.="<div class='row'>";
		$perso.=$p["AFF"]."</div>";
		$perso.="</div>";
		if ($class=="colonne2") $class="colonne_suite"; else $class="colonne2";
	}
	if ($class=="colonne_suite") $perso.="<div class='$class'>&nbsp;</div>";
	$perso.=$perso_["CHECK_SCRIPTS"];
	$empr_form=str_replace("!!champs_perso!!",$perso,$empr_form);
	print pmb_bidi($empr_form);
	}
