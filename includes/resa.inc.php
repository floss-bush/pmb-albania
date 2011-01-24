<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: resa.inc.php,v 1.35 2010-11-24 07:42:16 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($include_path."/mail.inc.php") ;
require_once($include_path."/sms.inc.php") ;

function alert_empr_resa($id_resa=0, $id_empr_concerne=0, $print_mode=0) {
	global $dbh;
	global $msg, $charset;
	global $PMBuserid, $PMBuseremailbcc ;
	global $pdflettreresa_priorite_email ;
	global $pdflettreresa_before_list , $pdflettreresa_madame_monsieur, $pdflettreresa_after_list, $pdflettreresa_fdp;
	global $biblio_name, $biblio_email ;
	global $biblio_adr1, $biblio_adr2, $biblio_cp, $biblio_town, $biblio_phone ; 
	global $bouton_impr_conf, $pdflettreresa_priorite_email_manuel;
	global $pmb_transferts_actif,$transferts_choix_lieu_opac;
	global $empr_sms_activation;	
	global $empr_sms_msg_resa_dispo;  		
	// si c'est une impression à partir du bouton, on prend le paramètre ad hoc
	if ($bouton_impr_conf) $pdflettreresa_priorite_email = $pdflettreresa_priorite_email_manuel ;

	if ($pdflettreresa_priorite_email==3) return ;	
	$query = "select distinct "; 	
	$query .= "trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit, ";  
	$query .= "date_format(resa_date_fin, '".$msg["format_date"]."') as aff_resa_date_fin, ";
	$query .= "date_format(resa_date_debut, '".$msg["format_date"]."') as aff_resa_date_debut, ";
	$query .= "empr_prenom, empr_nom, empr_cb, empr_mail, empr_tel1, empr_sms, id_resa, ";
	$query .= "trim(concat(ifnull(notices_m.niveau_biblio,''), ifnull(notices_s.niveau_biblio,''))) as niveau_biblio, ";
	$query .= "trim(concat(ifnull(notices_m.notice_id,''), ifnull(notices_s.notice_id,''))) as id_notice ";
	$query .= "from (((resa LEFT JOIN notices AS notices_m ON resa_idnotice = notices_m.notice_id ) LEFT JOIN bulletins ON resa_idbulletin = bulletins.bulletin_id) LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), empr ";
	$query .= "where id_resa in (".$id_resa.") and resa_idempr=id_empr";
	if ($id_empr_concerne) $query .= " and id_empr=$id_empr_concerne ";

	$result = mysql_query($query, $dbh);
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=".$charset."\n";

	$var = "pdflettreresa_fdp";
	eval ("\$pdflettreresa_fdp=\"".$$var."\";");
	
	// le texte après la liste des ouvrages en résa
	$var = "pdflettreresa_after_list";
	eval ("\$pdflettreresa_after_list=\"".$$var."\";");
		
	// le texte avant la liste des ouvrges en réservation
	$var = "pdflettreresa_before_list";
	eval ("\$pdflettreresa_before_list=\"".$$var."\";");
	
	// le "Madame, Monsieur," ou tout autre truc du genre "Cher adhérent,"
	$var = "pdflettreresa_madame_monsieur";
	eval ("\$pdflettreresa_madame_monsieur=\"".$$var."\";");
	
	$tab_resa = array();
	while ($empr=mysql_fetch_object($result)) {
		$id_empr = $empr->id_empr ;				
		$rqt_maj = "update resa set resa_confirmee=1 where id_resa in (".$id_resa.") AND resa_cb is not null and resa_cb!=''" ;
		if ($id_empr_concerne) $rqt_maj .= " and resa_idempr=$id_empr_concerne ";
		mysql_query($rqt_maj, $dbh);
		if (($pdflettreresa_priorite_email==1 || $pdflettreresa_priorite_email==2) && $empr->empr_mail) {
			$to = $empr->empr_prenom." ".$empr->empr_nom." <".$empr->empr_mail.">";
			$output_final = "<html><body>" ;
			$pdflettreresa_madame_monsieur=str_replace("!!empr_first_name!!", $empr->empr_prenom,$pdflettreresa_madame_monsieur);
			$output_final .= "$pdflettreresa_madame_monsieur <br />".$pdflettreresa_before_list ;
			if($empr->niveau_biblio == 'm' || $empr->niveau_biblio == 'b'){
				$affichage=new mono_display($empr->id_notice,0,'','','','','','','','','','','',true,'','');
				$output_final .= "<hr /><strong>".$affichage->header."</strong>";
			} elseif($empr->niveau_biblio == 's' || $empr->niveau_biblio == 'a'){
				$affichage_perio=new serial_display($empr->id_notice,0);
				$output_final .= "<hr /><strong>".$affichage_perio->header."</strong>";
			}
			$output_final .= "<br />";
			$output_final .= $msg['fpdf_valide']." ".$empr->aff_resa_date_debut." ".$msg['fpdf_valable']." ".$empr->aff_resa_date_fin ;
			$lieu_retrait="";
			if($pmb_transferts_actif && $transferts_choix_lieu_opac==3) {
				$rqt = "select resa_confirmee, resa_cb,resa_loc_retrait from resa where id_resa in (".$id_resa.")  and resa_cb is not null and resa_cb!='' ";
				$res = mysql_query ($rqt, $dbh) ;
				if(($resa_lue = mysql_fetch_object($res))) {
					if ($resa_lue->resa_confirmee) {
						if ($resa_lue->resa_loc_retrait) {
							$loc_retait=$resa_lue->resa_loc_retrait;
						} else {
							$rqt = "select expl_location from exemplaires where expl_cb='".$resa_lue->resa_cb."' ";
							$res = mysql_query ($rqt, $dbh) ;
							if(($res_expl = mysql_fetch_object($res))) {	
								$loc_retait=$res_expl->expl_location;						
							}
						}
						$rqt = "select location_libelle from docs_location where idlocation=".$loc_retait;
						$res = mysql_query ($rqt, $dbh) ;
						if(($res_expl = mysql_fetch_object($res))) {	
							$lieu_retrait=str_replace("!!location!!",$res_expl->location_libelle,$msg["resa_lettre_lieu_retrait"]);						
						}		
					}
				}	
			}
			$output_final .= "<br />$lieu_retrait<br /><hr />$pdflettreresa_after_list <br />".$pdflettreresa_fdp ;
			$output_final .= "<br /><br />".mail_bloc_adresse() ;
			$output_final .= "</body></html> ";
			if(is_resa_confirme($empr->id_resa)) {
				$res_envoi=mailpmb($empr->empr_prenom." ".$empr->empr_nom, $empr->empr_mail,$msg["mail_obj_resa_validee"]." : ".$empr->empr_prenom." ".mb_strtoupper($empr->empr_nom,$charset)." (".$empr->empr_cb.")",$output_final,$biblio_name, $biblio_email, $headers, "", $PMBuseremailbcc, 1);	
			}	
			if (!$res_envoi || $pdflettreresa_priorite_email==2) {
				if(is_resa_confirme($empr->id_resa)) array_push($tab_resa,$empr->id_resa);
			}
		} elseif ($pdflettreresa_priorite_email!=3) {
			if(is_resa_confirme($empr->id_resa)) array_push($tab_resa,$empr->id_resa);
		}				
		if(is_resa_confirme($empr->id_resa) && $empr_sms_activation && $empr->empr_tel1 && $empr->empr_sms && $empr_sms_msg_resa_dispo){		
			$res_envoi_sms=send_sms($empr->empr_prenom." ".$empr->empr_nom, $empr->empr_tel1,$msg["mail_obj_resa_validee"],$empr_sms_msg_resa_dispo,$biblio_name, $biblio_email, $headers, "", $PMBuseremailbcc, 1);
		}		
	} // end while
	$valeur_tab = implode(',',$tab_resa);		
	if($valeur_tab && !$print_mode) print "<script type='text/javascript'>openPopUp('./pdf.php?pdfdoc=lettre_resa&id_resa=$valeur_tab', 'lettre_confirm_resa".$id_resa."', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes');</script>";
}
	
//Fonction de test si la resa est valide ou non
function is_resa_confirme($id_resa=0){
	global $dbh;
	$rqt = "select * from resa where id_resa=$id_resa and resa_cb is not null and resa_cb!='' order by resa_idempr ";
	$res = mysql_query ($rqt, $dbh) ;
	
	while ($resa_lue = mysql_fetch_object($res)) {
		if ($resa_lue->resa_confirmee) {
			// archivage 
			$rqt_arch = "UPDATE resa_archive, resa, exemplaires SET
			resarc_confirmee = 1,
			resarc_loc_retrait = resa_loc_retrait,
			resarc_cb = resa_cb,
			resarc_debut = resa_date_debut,
			resarc_fin = resa_date_fin, 
			resarc_expl_typdoc = expl_typdoc,
			resarc_expl_cote = expl_cote,
			resarc_expl_statut = expl_statut,
			resarc_expl_location = expl_location,
			resarc_expl_codestat =expl_codestat,
			resarc_expl_owner = expl_owner,
			resarc_expl_section = expl_section			
			WHERE id_resa = $id_resa AND resa_arc = resarc_id AND  resa_cb = expl_cb "; 
			mysql_query($rqt_arch, $dbh);
			
			return true;
		}
	} 
	return false;
	
}
