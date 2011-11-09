<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: user_update.inc.php,v 1.28.2.2 2011-09-13 15:56:27 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$droits = 0;

/* le user admin ne peut perdre le droit admin */
if ($id==1) $form_admin = 1 ;

if($form_admin) $droits = $droits + ADMINISTRATION_AUTH;
if($form_catal) $droits = $droits + CATALOGAGE_AUTH;
if($form_circ) 	$droits = $droits + CIRCULATION_AUTH;
if($form_auth) 	$droits = $droits + AUTORITES_AUTH;
if($form_edition) 	$droits = $droits + EDIT_AUTH;
if($form_sauv) 	$droits = $droits + SAUV_AUTH;
if($form_pref) 	$droits = $droits + PREF_AUTH;
if($form_dsi) 	$droits = $droits + DSI_AUTH;
if($form_acquisition)	$droits = $droits + ACQUISITION_AUTH;
if($form_restrictcirc)	$droits = $droits + RESTRICTCIRC_AUTH;
if($form_thesaurus)	$droits = $droits + THESAURUS_AUTH;
if($form_transferts) $droits = $droits + TRANSFERTS_AUTH;
if($form_extensions) $droits = $droits + EXTENSIONS_AUTH;
if($form_demandes) $droits = $droits + DEMANDES_AUTH;
if($form_fiches) $droits = $droits + FICHES_AUTH;

// no duplication
$requete = " SELECT count(1) FROM users WHERE (username='$form_login' AND userid!='$id' )  LIMIT 1 ";
$res = mysql_query($requete, $dbh);
$nbr = mysql_result($res, 0, 0);

if ($nbr > 0) {
	error_form_message($form_login.$msg["user_login_already_used"]);
} elseif($form_actif) {
	// visibilité des exemplaires
	if ($pmb_droits_explr_localises) {
		$requete_droits_expl="select idlocation from docs_location order by location_libelle";
		$resultat_droits_expl=mysql_query($requete_droits_expl);
		$form_expl_visibilite=array();
		while ($j=mysql_fetch_array($resultat_droits_expl)) {
			$temp_global="form_expl_visibilite_".$j["idlocation"];
			global $$temp_global;
			switch ($$temp_global) {
				case "explr_invisible":
					$form_expl_visibilitei[] = $j["idlocation"];
				break;
				case "explr_visible_mod":
					$form_expl_visibilitevm[] .= $j["idlocation"];
				break;
				case "explr_visible_unmod":
					$form_expl_visibilitevu[] .= $j["idlocation"];
				break;	
			}	
		}
		
		if (count($form_expl_visibilitei)) 
			$form_expl_visibilite[0]= implode(',',$form_expl_visibilitei);
		else
			$form_expl_visibilite[0]="0";
		
		if (count($form_expl_visibilitevm))	
			$form_expl_visibilite[1]= implode(',',$form_expl_visibilitevm);
		else 
			$form_expl_visibilite[1]="0";

		if (count($form_expl_visibilitevu))
			$form_expl_visibilite[2]= implode(',',$form_expl_visibilitevu);
		else
			$form_expl_visibilite[2]="0";

		mysql_free_result($resultat_droits_expl);
	} else {
		$form_expl_visibilite[0]="0";
		$form_expl_visibilite[1]="0";
		$form_expl_visibilite[2]="0";
	} //fin visibilité des exemplaires
	 
	// O.K.  if item already exists UPDATE else INSERT
	if(!$id) {
		if(!empty($form_login) && $form_pwd==$form_pwd2) {
			$requete = "INSERT INTO users (userid, deflt_styles, create_dt, last_updated_dt, username, pwd, nom, prenom, rights, user_lang, nb_per_page_search, nb_per_page_select, ";
			$requete.= "nb_per_page_gestion, user_email, user_alert_resamail, explr_invisible, explr_visible_mod, explr_visible_unmod";
			if (isset($sel_group)) {
				$requete.= ", grp_num";
			}
			$requete.= ") VALUES";
			$requete .= "(null,'light',curdate(),curdate()";
			$requete .= ",'$form_login'";
			$requete .= ",password('$form_pwd')";
			$requete .= ",'$form_nom'";
			$requete .= ",'$form_prenom'";
			$requete .= ",'$droits'";
			$requete .= ", '$user_lang'";
			$requete .= ", '$form_nb_per_page_search'";
			$requete .= ", '$form_nb_per_page_select'";
			$requete .= ", '$form_nb_per_page_gestion'";
			$requete .= ", '$form_user_email'";
			if (!$form_user_alter_resa_mail) $form_user_alter_resa_mail="0" ;
			$requete .= ", '$form_user_alter_resa_mail'";
			$requete .= ", '".$form_expl_visibilite[0]."'";
			$requete .= ", '".$form_expl_visibilite[1]."'";
			$requete .= ", '".$form_expl_visibilite[2]."'";
			if (isset($sel_group)) {
				$requete.= ", '$sel_group' ";
			}
			$requete.= ") ";
			$res = @mysql_query($requete, $dbh);
			$id=mysql_insert_id($dbh);
			echo "<script>document.location=\"./admin.php?categ=users&sub=users&action=modif&id=$id\";</script>";
		}
	} else {
		$requete = "SELECT username,nom,prenom,rights, user_lang, nb_per_page_search, nb_per_page_select, nb_per_page_gestion, explr_invisible, explr_visible_mod, explr_visible_unmod, grp_num  ";
		$requete .= "FROM users WHERE userid='$id' LIMIT 1 ";
		$res = @mysql_query($requete, $dbh);
		$nbr = mysql_num_rows($res);
		
		$requete_param = "SELECT * FROM users WHERE userid='$id' LIMIT 1 ";
		$res_param = mysql_query($requete_param, $dbh);
		$field_values = mysql_fetch_row ( $res_param );
		
		if($nbr==1) {
			$row=mysql_fetch_row($res);
			$dummy=array();
			if($row[0] != $form_login && !empty($form_login)) {
				$dummy[0] = "username='$form_login'";
			}
			$dummy[1] = "nom='$form_nom'";
			$dummy[2] = "prenom='$form_prenom'";
			$dummy[3] = "rights='$droits'";
			$dummy[4] = "user_lang='$user_lang'";
			$dummy[5] = "nb_per_page_search='$form_nb_per_page_search'";
			$dummy[6] = "nb_per_page_select='$form_nb_per_page_select'";
			$dummy[7] = "nb_per_page_gestion='$form_nb_per_page_gestion'";
			$dummy[8] = "explr_invisible='".$form_expl_visibilite[0]."'";
			$dummy[9] = "explr_visible_mod='".$form_expl_visibilite[1]."'";
			$dummy[10]= "explr_visible_unmod='".$form_expl_visibilite[2]."'";
			if (isset($sel_group)) {
				$dummy[11]= "grp_num='$sel_group'";
			}		
			/* insérer ici la maj des param et deflt */
			$i = 0;
			while ($i < mysql_num_fields($res_param)) {
				$field = mysql_field_name($res_param, $i) ;
				$field_deb = substr($field,0,6);
				switch ($field_deb) {
					case "deflt_" :
						if ($field == "deflt_styles") {
							$dummy[$i+12]=$field."='".$form_style."'";
						} elseif ($field == "deflt_docs_section") {
							$formlocid="f_ex_section".$form_deflt_docs_location ;
							$dummy[$i+12]=$field."='".$$formlocid."'";
						} else {
							$var_form = "form_".$field;
							$dummy[$i+12]=$field."='".$$var_form."'";
						}
						break;
					case "deflt2" :
						$var_form = "form_".$field;
						$dummy[$i+12]=$field."='".$$var_form."'";
						break ;
					case "param_" :
						$var_form = "form_".$field;
						$dummy[$i+12]=$field."='".$$var_form."'";
						break ;
					case "value_" :
						$var_form = "form_".$field;
						$dummy[$i+12]=$field."='".$$var_form."'";
						break ;
					case "deflt3" :
						$var_form = "form_".$field;
						$dummy[$i+12]=$field."='".$$var_form."'";
						break ;
					case "xmlta_" :
						$var_form = "form_".$field;
						$dummy[$i+12]=$field."='".$$var_form."'";
						break ;
					case "speci_" :
						$speci_func = substr($field, 6);
						eval('$dummy[$i+12].= set_'.$speci_func.'();');
						break;
					default :
						break ;
				}
				
				$i++;
			}

			$dummy[] = "user_email='$form_user_email'";
			if (!$form_user_alert_resamail) $form_user_alert_resamail="0" ;
			$dummy[] = "user_alert_resamail='$form_user_alert_resamail'";

			if(!empty($dummy)) {
				$set = join($dummy, ", ");
			}

			if(!empty($set)) {
				$set = "SET last_updated_dt=curdate(),".$set;
				$requete = "UPDATE users $set WHERE userid=$id ";
				$res = mysql_query($requete, $dbh);
			}
		}
	}
}	

show_users($dbh);
echo window_title("$msg[7] $msg[25]");
