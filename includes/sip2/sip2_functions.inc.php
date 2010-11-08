<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sip2_functions.inc.php,v 1.10 2010-09-23 12:41:23 ngantier Exp $

require_once($class_path."/emprunteur.class.php");
require_once("$class_path/mono_display.class.php");
require_once("$class_path/ajax_pret.class.php");
require_once("$class_path/ajax_retour_class.php");
require_once("$class_path/quotas.class.php");
require_once("$class_path/expl_to_do.class.php");
// pour debug faire: header("Log-message: toto");

function _login_response_($values) {
	$ret=array();
	if (($values["LOGIN_USER_ID"][0]!="ftetart")||($values["LOGIN_PASSWORD"][0]!="enfe=00")) {
		$ret["OK"]="0";
	} else {
		$ret["OK"]="1";
	}
	return $ret;
}

function _acs_status_($values) {
	global $id,$opac_pret_prolongation,$deflt_docs_location;
	$_SESSION[$id]["STATUS"]=$values;
	
	$ret=array();
	$ret["ON_LINE_STATUS"]="Y";
	$ret["CHECKIN_OK"]="Y";
	$ret["CHECKOUT_OK"]="Y";
	//if ($opac_pret_prolongation) $ret["ACS_RENEWAL_POLICY"]="Y"; else $ret["ACS_RENEWAL_POLICY"]="N";
	$ret["ACS_RENEWAL_POLICY"]="Y";	
	$ret["STATUS_UPDATE_OK"]="Y";
	$ret["OFF_LINE_OK"]="N";
	$ret["TIMEOUT_PERIOD"]="300";
	$ret["RETRIES_ALLOWED"]="003";
	$ret["DATE_TIME_SYNC"]=date("Ymd    His",time());
	$ret["PROTOCOL_VERSION"]="2.00";
	//Champs variables
	$requete="select location_libelle from docs_location where idlocation=".$deflt_docs_location;
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) $ret["INSTITUTION_ID"][0]=mysql_result($resultat,0,0); else $ret["INSTITUTION_ID"][0]=$deflt_docs_location;
	$requete="select location_libelle from docs_location where idlocation=".$deflt_docs_location;
	$resultat=mysql_query($requete);
	if ($resultat) {
		$ret["LIBRARY_NAME"][0]=mysql_result($resultat,0,0);
	}
	$ret["SUPPORTED_MESSAGES"][0]="YYYYYYNYYNYYYYNN";
	return $ret;
}

function _patron_information_response_($values) {
	global $id,$lang,$opac_resa;
	global $see_all_pret; 
	$see_all_pret=1; 
		
	(string)$rep_lang=(string)$values["LANGUAGE"];
	switch ((string)$values["LANGUAGE"]) {
		case "001":
			$lang="en_UK";
			break;
		case "002":
			$lang="fr_FR";
			break;
		case "008":
			$lang="es_ES";
			break;
		case "004":
			$lang="it_IT";
			break;
		default:
			(string)$rep_lang="000";
			break;
	}
	//Recherche dans la localisation
	$localisation=$values["INSTITUTION_ID"][0];
	$empr_cb=$values["PATRON_IDENTIFIER"][0];
	$empr_pwd=$values["PATRON_PASSWORD"][0];
	
	$requete="select id_empr from empr where empr_cb='".addslashes($empr_cb)."'";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
		$id_empr=mysql_result($resultat,0,0);
		$empr=new emprunteur($id_empr,'','',1);
	
		if (!$localisation) $localisation=$empr->empr_location_l;
	
		//Calcul des summary
		//print_r($empr);
		$ret["CHARGED_ITEMS_COUNT"]=str_pad(count($empr->prets),4,"0",STR_PAD_LEFT); //str_pad($empr->nb_reservations,4,"0",STR_PAD_LEFT);
		$ret["OVERDUE_ITEMS_COUNT"]=str_pad($empr->retard,4,"0",STR_PAD_LEFT);
		$nb_total_resa=$empr->nb_reservations;
		$rqt_resas=mysql_query("select count(id_resa) as nb from resa where resa_idempr=$id_empr and resa_confirmee=0 group by resa_confirmee");
		if (mysql_num_rows($rqt_resas)) 
			$nb_resa_non_confirmes=mysql_result($rqt_resas,0,0);
		else $nb_resa_non_confirmes=0;
		$ret["HOLD_ITEMS_COUNT"]=str_pad($nb_total_resa-$nb_resa_non_confirmes,4,"0",STR_PAD_LEFT);
		if ($empr->nb_amendes) $ret["FINE_ITEMS_COUNT"]=str_pad($empr->nb_amendes,4,"0",STR_PAD_LEFT); else $ret["FINE_ITEMS_COUNT"]="    ";
		$ret["RECALL_ITEMS_COUNT"]="    ";
		$ret["UNAVAILABLE_HOLDS_COUNT"]=str_pad($nb_resa_non_confirmes,4,"0",STR_PAD_LEFT);
		$pret=($empr->blocage_retard||$empr->blocage_amendes||$empr->blocage_abt||(!$empr->allow_loan)?"Y":" ");
		$patron_status=" ".($empr->allow_prol?" ":"Y")." ".$pret."  ";
		$patron_status.=($empr->blocage_retard?"Y Y":"   ")." ".($empr->blocage_amendes?"Y":" ").(($empr->blocage_abt||$empr->blocage_tarifs)?"Y":" ")."  ";
		$ret["PATRON_IDENTIFIER"][0]=$empr->cb;
		$ret["PERSONAL_NAME"][0]=$empr->prenom." ".$empr->nom;
		$ret["VALID_PATRON"][0]="Y";
		if ($empr->pwd==$empr_pwd) $ret["VALID_PATRON_PASSWORD"][0]="Y"; else  $ret["VALID_PATRON_PASSWORD"][0]="N";
		if ($total=$empr->compte_amendes+$empr->amendes_en_cours)
			$ret["FEE_AMOUNT"][0]=$total;
		$ret["HOME_ADDRESS"][0]=$empr->adr1."\n".$empr->adr2."\n".$empr->cp." ".$empr->ville;
		if ($empr->tel1) $ret["HOME_PHONE_NUMBER"][0]=$empr->tel1;
		if ($empr->mail) $ret["EMAIL_ADDRESS"][0]=$empr->mail;
		
		//Envoie des infos exemplaires selon demande
		$p=strpos($values["SUMMARY"],"Y");
		if ($p!==false) {
			switch ($p) {
				case 0:
					//Ouvrages réservés dispos
					$rqt_resa="select resa_cb from resa where resa_idempr=$id_empr and resa_confirmee=1";
					$res_resa=mysql_query($rqt_resa);
					$nb_resa=mysql_num_rows($res_resa);
					$resas=array();
					while ($resa=mysql_fetch_object($res_resa)) {
						$resas[]=$resa->resa_cb;
					}
					$n=0;
					if ($values["START_ITEM"][0]) $start=$values["START_ITEM"][0]-1; else $start=0;
					if ($values["END_ITEM"][0]) $end=$values["END_ITEM"][0]; else $end=$nb_resa;
					for ($i=$start; $i<$end; $i++) {
						//$ret["CHARGED_ITEMS"][$n]="retour le : ".$empr->prets[$i]["date_retour"].": ".$empr->prets[$i]["libelle"];
						$ret["HOLD_ITEMS"][$n]=$resas[$i];
						$n++;
					}
					break;
				case 2:
					//Ouvrages en prêt
					$n=0;
					if ($values["START_ITEM"][0]) $start=$values["START_ITEM"][0]-1; else $start=0;
					if ($values["END_ITEM"][0]) $end=$values["END_ITEM"][0]; else $end=count($empr->prets);
					for ($i=$start; $i<$end; $i++) {
						//$ret["CHARGED_ITEMS"][$n]="retour le : ".$empr->prets[$i]["date_retour"].": ".$empr->prets[$i]["libelle"];
						$ret["CHARGED_ITEMS"][$n]=$empr->prets[$i]["cb"];
						$n++;
					}
					break;
				case 1:
					//Ouvrages en retard
					$n=0;
					if ($values["START_ITEM"][0]) $start=$values["START_ITEM"][0]-1; else $start=0;
					if ($values["END_ITEM"][0]) $end=$values["END_ITEM"][0]; else $end=$empr->retard;
					for ($i=0; $i<count($empr->prets); $i++) {
						if ($empr->prets[$i]["pret_retard"]) {
							if (($n==$start)&&($start<$end)) {
								//$ret["OVERDUE_ITEMS"][$n]="retour le : ".$empr->prets[$i]["date_retour"].": ".$empr->prets[$i]["libelle"];
								$ret["OVERDUE_ITEMS"][$n]=$empr->prets[$i]["cb"];
								$n++;
								$start++;
							}
						} 
					}
					break;
				case 3:
					//Ouvrages en amende
					break;
				case 5:
					//Ouvrages réservés non dispos
					$rqt_resa="select resa_idnotice,resa_idbulletin from resa where resa_idempr=$id_empr and resa_confirmee=0";
					$res_resa=mysql_query($rqt_resa);
					$nb_resa=mysql_num_rows($res_resa);
					$resas=array();
					while ($resa=mysql_fetch_object($res_resa)) {
						if ($resa->resa_idnotice) {
							//Récupération d'un exemplaire au hasard de la notice
							$rqt_expl="select expl_cb from exemplaires where expl_notice=".$resa->resa_idnotice." limit 1";
							$resa_cb=mysql_result(mysql_query($rqt_expl),0,0);
						} else {
							//Récupération d'un exemplaire au hasard d'un bulletin
							$rqt_expl="select expl_cb from exemplaires where expl_bulletin=".$resa->resa_idbbulletin." limit 1";
							$resa_cb=mysql_result(mysql_query($rqt_expl),0,0);
						}
						$resas[]=$resa_cb;
					}
					$n=0;
					if ($values["START_ITEM"][0]) $start=$values["START_ITEM"][0]-1; else $start=0;
					if ($values["END_ITEM"][0]) $end=$values["END_ITEM"][0]; else $end=$nb_resa;
					for ($i=$start; $i<$end; $i++) {
						//$ret["CHARGED_ITEMS"][$n]="retour le : ".$empr->prets[$i]["date_retour"].": ".$empr->prets[$i]["libelle"];
						$ret["UNAVAILABLE_HOLD_ITEMS"][$n]=$resas[$i];
						$n++;
					}
					break;
			}
		}
	} else {
		$patron_status="              ";
		$ret["PATRON_IDENTIFIER"][0]=$empr_cb;
		$ret["PERSONAL_NAME"][0]=" ";
		$ret["VALID_PATRON"][0]="N";
		//Calcul des summary
		$ret["HOLD_ITEMS_COUNT"]="    ";
		$ret["OVERDUE_ITEMS_COUNT"]="    ";
		$ret["CHARGED_ITEMS_COUNT"]="    ";
		$ret["FINE_ITEMS_COUNT"]="    ";
		$ret["RECALL_ITEMS_COUNT"]="    ";
		$ret["UNAVAILABLE_HOLDS_COUNT"]="    ";
	}
	$ret["PATRON_STATUS"]=$patron_status;
	$ret["LANGUAGE"]=$rep_lang;
	$ret["TRANSACTION_DATE"]=date("Ymd    His",time());
	$ret["INSTITUTION_ID"][0]=$localisation;
	return $ret;
}

function _patron_status_response_($values) {
	global $id,$lang,$opac_resa;
	global $see_all_pret; 
	$see_all_pret=1; 
	
	(string)$rep_lang=(string)$values["LANGUAGE"];
	switch ((string)$values["LANGUAGE"]) {
		case "001":
			$lang="en_UK";
			break;
		case "002":
			$lang="fr_FR";
			break;
		case "008":
			$lang="es_ES";
			break;
		case "004":
			$lang="it_IT";
			break;
		default:
			(string)$rep_lang="000";
			break;
	}
	//Recherche
	$localisation=$values["INSTITUTION_ID"][0];
	$empr_cb=$values["PATRON_IDENTIFIER"][0];
	$empr_pwd=$values["PATRON_PASSWORD"][0];
	
	$requete="select id_empr from empr where empr_cb='".addslashes($empr_cb)."'";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
		$id_empr=mysql_result($resultat,0,0);
		$empr=new emprunteur($id_empr,'','',1);
		if (!$localisation) $localisation=$empr->empr_location_l;
		$pret=($empr->blocage_retard||$empr->blocage_amendes||$empr->blocage_abt||(!$empr->allow_loan)?"Y":" ");
		$patron_status=" ".($empr->allow_prol?" ":"Y")."Y".$pret."  ";
		$patron_status.=($empr->blocage_retard?"Y Y":"   ")." ".($empr->blocage_amendes?"Y":" ").(($empr->blocage_abt||$empr->blocage_tarifs)?"Y":" ")."  ";
		$ret["PATRON_IDENTIFIER"][0]=$empr->cb;
		$ret["PERSONAL_NAME"][0]=$empr->prenom." ".$empr->nom;
		$ret["VALID_PATRON"][0]="Y";
		if ($empr->pwd==$empr_pwd) $ret["VALID_PATRON_PASSWORD"][0]="Y"; else  $ret["VALID_PATRON_PASSWORD"][0]="N";
		if ($total=$empr->compte_amendes+$empr->amendes_en_cours)
			$ret["FEE_AMOUNT"][0]=$total;
	} else {
		$patron_status="              ";
		$ret["PATRON_IDENTIFIER"][0]=$empr_cb;
		$ret["PERSONAL_NAME"][0]=" ";
		$ret["VALID_PATRON"][0]="N";
	}
	$ret["PATRON_STATUS"]=$patron_status;
	$ret["LANGUAGE"]=$rep_lang;
	$ret["TRANSACTION_DATE"]=date("Ymd    His",time());
	$ret["INSTITUTION_ID"][0]=$localisation;
	return $ret;
}

function _item_information_response_($values) {
	global $msg;
	$expl_cb=$values["ITEM_IDENTIFIER"][0];
	global $selfservice_pret_carte_invalide_msg;
	global $selfservice_pret_pret_interdit_msg;
	global $selfservice_pret_deja_prete_msg;
	global $selfservice_pret_deja_reserve_msg;
	global $selfservice_pret_quota_bloc_msg;
	global $selfservice_pret_non_pretable_msg;
	global $selfservice_pret_expl_inconnu_msg;
	
	$requete = "SELECT exemplaires.*, pret.*, docs_location.*, docs_section.*, docs_statut.*, tdoc_libelle, ";
	$requete .= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
	$requete .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, ";
	$requete .= " IF(pret_retour>sysdate(),0,1) as retard " ;
	$requete .= " FROM exemplaires LEFT JOIN pret ON exemplaires.expl_id=pret.pret_idexpl ";
	$requete .= " left join docs_location on exemplaires.expl_location=docs_location.idlocation ";
	$requete .= " left join docs_section on exemplaires.expl_section=docs_section.idsection ";
	$requete .= " left join docs_statut on exemplaires.expl_statut=docs_statut.idstatut ";
	$requete .= " left join docs_type on exemplaires.expl_typdoc=docs_type.idtyp_doc  ";
	$requete .= " WHERE expl_cb='".addslashes($expl_cb)."' ";
	$requete .= " order by location_libelle, section_libelle, expl_cote, expl_cb ";
	
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
		//Calcul du statut
		$expl = mysql_fetch_object($resultat);
		if ($expl->pret_flag) {
			if($expl->pret_retour) {
				$statut="01";
				$error=true;
				$error_message=$selfservice_pret_deja_prete_msg;
			} else {
				// tester si réservé
				$result_resa = mysql_query("select 1 from resa where resa_cb='".addslashes($expl->expl_cb)."' ");
				$reserve = @mysql_num_rows($result_resa);
				if ($reserve) {
					$statut="08";
					$error=true;
					$error=$selfservice_pret_deja_reserve_msg;
				} else $statut="03";
			}
		} else {
			$statut="01";
			$error=true;
			$error_message=$selfservice_pret_non_pretable_msg;
		}
		$hold_queue=@mysql_num_rows($result_resa)*1;
		$ret["CIRCULATION_STATUS"]=$statut;
		$ret["SECURITY_MARKER"]="00";
		$ret["FEE_TYPE"]="01";
		$ret["TRANSACTION_DATE"]=date("Ymd    His",time());
		$ret["HOLD_QUEUE_LENGTH"][0]=$hold_queue;
		if ($expl->pret_retour) $ret["DUE_DATE"][0]=$expl->aff_pret_retour;
		$ret["ITEM_IDENTIFIER"][0]=$expl_cb;
		if ($expl->expl_bulletin) {
			$isbd = new bulletinage_display($expl->expl_bulletin);
			$ret["TITLE_IDENTIFIER"][0]=$isbd->display;
		} else {
			$isbd= new mono_display($expl->expl_notice, 1);
			$ret["TITLE_IDENTIFIER"][0]= $isbd->header_texte;
		}
	} else {
		$ret["CIRCULATION_STATUS"]="01";
		$ret["SECURITY_MARKER"]="00";
		$ret["FEE_TYPE"]="01";
		$ret["TRANSACTION_DATE"]=date("Ymd    His",time());
		$ret["ITEM_IDENTIFIER"][0]=$expl_cb;
		$ret["TITLE_IDENTIFIER"][0]= $expl_cb." : document inconnu";
		$error=true;
		$error_message=$selfservice_pret_expl_inconnu_msg;
	}
	//if ($error) $ret["SCREEN_MESSAGE"][0]=$error_message;
	if ($expl_cb=="0000000000000000") $ret=array();
	return $ret;
}

function _checkout_response_($values) {
	global $pmb_antivol,$msg;
	global $see_all_pret; 	
	global $selfservice_pret_carte_invalide_msg;
	global $selfservice_pret_pret_interdit_msg;
	global $selfservice_pret_deja_prete_msg;
	global $selfservice_pret_deja_reserve_msg;
	global $selfservice_pret_quota_bloc_msg;
	global $selfservice_pret_non_pretable_msg;
	global $selfservice_pret_expl_inconnu_msg;
		
	$see_all_pret=1; 
	//Transaction obligatoire car déjà effectuée !
	//$force_checkout=($values["NO_BLOCK"]=="Y"?true:false);
	$localisation=$values["INSTITUTION_ID"][0];
	$empr_cb=$values["PATRON_IDENTIFIER"][0];
	$expl_cb=$values["ITEM_IDENTIFIER"][0];
	$fee_ack=($values["FEE_ACKNOWLEDGED"][0]=="Y"?true:false);
	$cancel=($values["CANCEL"][0]=="Y"?true:false);
	
	$magnetic="N";
	$desensitize="N";
	$titre=$expl_cb;
	$due_date="";
	
	//Recherche de l'exemplaire
	$requete = "SELECT exemplaires.*, pret.*, docs_location.*, docs_section.*, docs_statut.*, tdoc_libelle, ";
	$requete .= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
	$requete .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, ";
	$requete .= " IF(pret_retour>sysdate(),0,1) as retard " ;
	$requete .= " FROM exemplaires LEFT JOIN pret ON exemplaires.expl_id=pret.pret_idexpl ";
	$requete .= " left join docs_location on exemplaires.expl_location=docs_location.idlocation ";
	$requete .= " left join docs_section on exemplaires.expl_section=docs_section.idsection ";
	$requete .= " left join docs_statut on exemplaires.expl_statut=docs_statut.idstatut ";
	$requete .= " left join docs_type on exemplaires.expl_typdoc=docs_type.idtyp_doc  ";
	$requete .= " WHERE expl_cb='".addslashes($expl_cb)."' ";
	$requete .= " order by location_libelle, section_libelle, expl_cote, expl_cb ";
	$resultat=mysql_query($requete);
	
	if (mysql_num_rows($resultat)) {
		$expl = mysql_fetch_object($resultat);
		
		//Recherche de l'emprunteur
		$requete="select id_empr from empr where empr_cb='".addslashes($empr_cb)."'";
		$resultat=mysql_query($requete);
		if (!mysql_num_rows($resultat)) {
			$error=true;
			$error_message=$selfservice_pret_carte_invalide_msg;
			$ok=0;
		} else {
			$id_empr=mysql_result($resultat,0,0);
			$empr=new emprunteur($id_empr,'','',1);
			$pret=($empr->blocage_retard||$empr->blocage_amendes||$empr->blocage_abt||(!$empr->allow_loan)?false:true);
			if (!$pret) {
				$ok=0;
				$error=true;
				$error_message=$selfservice_pret_pret_interdit_msg;
			} else {
				if ($expl->pret_flag) {
					if ($expl->expl_bulletin) {
						$isbd = new bulletinage_display($expl->expl_bulletin);
						$titre=$isbd->display;
					} else {
						$isbd= new mono_display($expl->expl_notice, 1);
						$titre= $isbd->header_texte;
					}
					if($expl->pret_retour) {
						$error=true;
						$error_message=$selfservice_pret_deja_prete_msg;
						$ok=0;
					} else {
						// tester si réservé
						$result_resa = mysql_query("select 1 from resa where resa_cb='".addslashes($expl->expl_cb)."' and resa_idempr!='".addslashes($id_empr)."'");
						$reserve = @mysql_num_rows($result_resa);
						if ($reserve) {
							$error=true;
							$error_message=$selfservice_pret_deja_reserve_msg;
							$ok=0;
						} else {
							//On fait le prêt
							$pret=new do_pret();
							$pret->check_pieges($empr_cb, 0,$expl_cb, 0,0);
							if (!$pret->status) {
								$ok=1;
								$pret->confirm_pret($id_empr, $expl->expl_id);
								//Recherche de la date de retour
								$requete="select date_format(pret_retour, '".$msg["format_date"]."') as retour from pret where pret_idexpl=".$expl->expl_id;
								$resultat=mysql_query($requete);
								$error=true;
								$error_message=$titre." / retour le : ".@mysql_result($resultat,0,0);
								$due_date=@mysql_result($resultat,0,0);
							} else {
								$ok=0;
								$error=true;
								$error_message=$selfservice_pret_quota_bloc_msg;
								$ret["SCREEN_MESSAGE"][1]=$pret->error_message;
							}
							//Est-ce un support magnétique
							if ($pmb_antivol) {
								if ($expl->type_antivol==2) $magnetic="Y";
							}
							
						}
					}
				} else {
					$error=true;
					$error_message=$selfservice_pret_non_pretable_msg;
					$ok=0;
				}
			}
		}
	} else {
		$error=true;
		$error_message=$selfservice_pret_expl_inconnu_msg;
		$titre=$expl_cb;
		$ok=0;
	}
	$ret["OK"]=$ok;
	$ret["RENEWAL_OK"]="N";
	$ret["MAGNETIC_MEDIA"]=$magnetic;
	$ret["DESENSITIZE"]=$desensitize;
	$ret["TRANSACTION_DATE"]=date("Ymd    His",time());
	$ret["INSTITUTION_ID"][0]=$localisation;
	$ret["PATRON_IDENTIFIER"][0]=$empr_cb;
	$ret["ITEM_IDENTIFIER"][0]=$expl_cb;
	$ret["TITLE_IDENTIFIER"][0]=$titre;
	$ret["DUE_DATE"][0]=$due_date;
	if ($error) {
		$ret["SCREEN_MESSAGE"][0]=$error_message;
	}
	return $ret;
}

function _checkin_response_($values) {
	global $pmb_antivol;
	global $selfservice_pret_expl_inconnu_msg;
	
	$localisation=$values["INSTITUTION_ID"][0];
	$expl_cb=$values["ITEM_IDENTIFIER"][0];
	$cancel=($values["CANCEL"][0]=="Y"?true:false);
	
	$magnetic="N";
	$resensitize="N";
	$ok=0;
	$titre=$expl_cb;
	
	$requete="select expl_id,expl_bulletin,expl_notice,type_antivol,empr_cb from exemplaires join pret on (expl_id=pret_idexpl) join empr on (pret_idempr=id_empr) where expl_cb='".addslashes($expl_cb)."'";
	$resultat=mysql_query($requete);
	if (!$resultat) {
		$ok=0;
		$error=true;
		$ret["SCREEN_MESSAGE"][0]=$selfservice_pret_expl_inconnu_msg;	
	} else {
		$expl=mysql_fetch_object($resultat);
		$empr_cb=$expl->empr_cb;
		if ($expl->expl_bulletin) {
			$isbd = new bulletinage_display($expl->expl_bulletin);
			$titre=$isbd->display;
		} else {
			$isbd= new mono_display($expl->expl_notice, 1);
			$titre= $isbd->header_texte;
		}
		
		if ($pmb_antivol) {
			if ($expl->type_antivol==2) $magnetic="Y";
		}
		
		$retour = new expl_to_do($expl_cb);
 		// Fonction qu effectue le retour d'un document
 		$retour->do_retour_selfservice();

 		if ($retour->status==-1) {
 			//Problème
 			$ok=0; 			
 		} else {
 			//Pas de problème
 			$ok=1;
 			$resensitize="Y";
 		}		
		$ret["SCREEN_MESSAGE"][0]=$retour->message_loc;
		$ret["SCREEN_MESSAGE"][1]=$retour->message_resa;
		$ret["SCREEN_MESSAGE"][2]=$retour->message_retard;
		$ret["SCREEN_MESSAGE"][3]=$retour->message_amende;		
	}
	$ret["OK"]=$ok;
	$ret["RESENSITIZE"]=$resensitize;
	$ret["MAGNETIC_MEDIA"]=$magnetic;
	$ret["ALERT"]="N";
	$ret["TRANSACTION_DATE"]=date("Ymd    His",time());
	$ret["INSTITUTION_ID"][0]=$localisation;
	$ret["ITEM_IDENTIFIER"][0]=$expl_cb;
	$ret["PERMANENT_LOCATION"][0]=$localisation;
	$ret["TITLE_IDENTIFIER"][0]=$titre;
	$ret["PATRON_IDENTIFIER"][0]=$empr_cb;

	return $ret;
}

function _request_sc_resend_($values) {
	//Wath ever the values !
	return array();
}


function _renew_response_($values) {
	global $opac_pret_prolongation, $opac_pret_duree_prolongation,$pmb_pret_restriction_prolongation,$pmb_pret_nombre_prolongation,$dbh,$msg;
	global $selfservice_pret_prolonge_non_msg;
	global $protocol_prolonge;
	
	$empr_cb=$values["PATRON_IDENTIFIER"][0];
	$expl_cb=$values["ITEM_IDENTIFIER"][0];

	$localisation=$values["INSTITUTION_ID"][0];	
	$magnetic="N";
	$desensitize="N";
	$titre=$expl_cb;
	
	$due_date="";	
	$ok=1;
	$prolonge="Y";
	
	if($opac_pret_prolongation){		
		$prolongation = TRUE;
		$requete="select expl_id,id_empr, expl_bulletin,expl_notice,type_antivol,empr_cb from exemplaires join pret on (expl_id=pret_idexpl) join empr on (pret_idempr=id_empr) where expl_cb='".addslashes($expl_cb)."'";
		$resultat=mysql_query($requete);
		if (!$resultat) {
			$error_message="Le document n'existe pas ou n'est pas en prêt!";	
		} else {	
			$expl=mysql_fetch_object($resultat);
			$expl_id=$expl->expl_id;
			$id_empr=$expl->id_empr;	
			
			//on recupere les informations du pret 
			$query = "select cpt_prolongation, retour_initial, pret_date, pret_retour from pret where pret_idexpl=".$expl_id." limit 1";
			$result = mysql_query($query, $dbh);
			$data = mysql_fetch_array($result);
			$cpt_prolongation = $data['cpt_prolongation']; 
			$retour_initial =  $data['retour_initial'];
			$cpt_prolongation++;
			
			$duree_prolongation=$opac_pret_duree_prolongation;	
			$today=sql_value("SELECT CURRENT_DATE()");
			if ($pmb_pret_restriction_prolongation==0) {
				// Aucune limitation des prolongations
				$prolongation=true;
				$duree_prolongation=$opac_pret_duree_prolongation;	
			} else if ($pmb_pret_restriction_prolongation>0) {
				$pret_nombre_prolongation=$pmb_pret_nombre_prolongation;
				if(($pmb_pret_restriction_prolongation==1) && ($cpt_prolongation>$pret_nombre_prolongation)) {
					// Limitation simple de la prolongation
					$prolongation=FALSE;
				} else if($pmb_pret_restriction_prolongation==2) {
					// Limitation du pret par les quotas
					//Initialisation des quotas pour nombre de prolongations
					$qt = new quota("PROLONG_NMBR_QUOTA");
					//Tableau de passage des paramètres
					$struct["READER"] = $id_empr;
					$struct["EXPL"] = $expl_id;						
					$pret_nombre_prolongation=$qt -> get_quota_value($struct);		

					if($cpt_prolongation>$pret_nombre_prolongation) $prolongation=FALSE;

					//Initialisation des quotas la durée de prolongations
					$qt = new quota("PROLONG_TIME_QUOTA");
					$struct["READER"] = $id_empr;
					$struct["EXPL"] = $expl_id;	
					$duree_prolongation=$qt -> get_quota_value($struct);	
				} // fin if gestion par quotas
			} 

			$date_prolongation=sql_value("SELECT DATE_ADD('$retour_initial', INTERVAL $duree_prolongation DAY)");
			$diff=sql_value("SELECT DATEDIFF('$retour_initial','$today')");
			if($diff<-$duree_prolongation || $diff>$duree_prolongation) {
				$prolongation=FALSE;
			}
			// Recherche de la nouvelle date de retour
			$req_date_calendrier = "select date_ouverture from ouvertures where ouvert=1 and num_location='".$data['expl_location']."' order by date_ouverture asc";
			$res_date_calendrier = mysql_query($req_date_calendrier);
			while(($date_calendrier = mysql_fetch_object($res_date_calendrier))){
				$ecart = sql_value("SELECT DATEDIFF('$date_calendrier->date_ouverture','$date_prolongation')");
				if($ecart >= 0 ){
					$date_prolongation = $date_calendrier->date_ouverture;
					break; 
				}
			}
									
			if($prolongation==TRUE)	{					
				// Memorisation de la nouvelle date de prolongation	
				$query = "update pret set cpt_prolongation='".$cpt_prolongation."', pret_retour='".$date_prolongation."' where pret_idexpl=".$expl_id;
				$result = mysql_query($query, $dbh);
				$due_date=$date_prolongation;
				$due_date=sql_value("select date_format('$date_prolongation', '".$msg["format_date"]."')");
				//$due_date=@mysql_result($resultat,0,0);
			} else {
				$error_message="$selfservice_pret_prolonge_non_msg";						
			}
		}	
	
	} else{		
		$error_message="Prolongation non activée";					
	}
	
	if ($error_message) {
		//Attention, pour les deux lignes suivantes, cela dépend d'un paramètre NEDAP ou IDENT 
		if($protocol_prolonge){
			$ok=0;
			$prolonge="N";
		}
		$ret["SCREEN_MESSAGE"][0]=$error_message;
	}	
	$ret["OK"]=$ok;
	$ret["RENEWAL_OK"]=$prolonge;
	$ret["MAGNETIC_MEDIA"]=$magnetic;
	$ret["DESENSITIZE"]=$desensitize;
	$ret["TRANSACTION_DATE"]=date("Ymd    His",time());	
	$ret["INSTITUTION_ID"][0]=$localisation;
	$ret["PATRON_IDENTIFIER"][0]=$empr_cb;	
	$ret["ITEM_IDENTIFIER"][0]=$expl_cb;	
	$ret["TITLE_IDENTIFIER"][0]=$titre;	
	$ret["DUE_DATE"][0]=$due_date;
	return $ret;
}
function sql_value($rqt) {
	if(($result=mysql_query($rqt))) {
		if(($row = mysql_fetch_row($result)))	return $row[0];
	}	
	return '';
}
?>

