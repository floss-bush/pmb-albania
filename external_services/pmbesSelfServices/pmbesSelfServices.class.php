<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pmbesSelfServices.class.php,v 1.5 2010-08-17 12:31:10 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/external_services.class.php");
require_once($class_path."/external_services_caches.class.php");

require_once($class_path."/emprunteur.class.php");
require_once("$class_path/mono_display.class.php");
require_once("$class_path/ajax_pret.class.php");
require_once("$class_path/ajax_retour_class.php");
require_once("$class_path/quotas.class.php");
require_once("$class_path/expl_to_do.class.php");


class pmbesSelfServices extends external_services_api_class{
	var $error=false;		//Y-a-t-il eu une erreur
	var $error_message="";	//Message correspondant à l'erreur
	
	function restore_general_config() {
		
	}
	
	function form_general_config() {
		return false;
	}
	
	function save_general_config() {
		
	}
	
	function self_checkout($expl_cb,$id_empr,$PMBUserId=-1) {
		global $msg;
		global $charset;	
		global $selfservice_pret_carte_invalide_msg;
		global $selfservice_pret_pret_interdit_msg;
		global $selfservice_pret_deja_prete_msg;
		global $selfservice_pret_deja_reserve_msg;
		global $selfservice_pret_quota_bloc_msg;
		global $selfservice_pret_non_pretable_msg;
		global $selfservice_pret_expl_inconnu_msg;
					
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
			if ($expl->expl_bulletin) {
				$isbd = new bulletinage_display($expl->expl_bulletin);
				$titre=$isbd->display;
			} else {
				$isbd= new mono_display($expl->expl_notice, 1);
				$titre= $isbd->header_texte;
			}
			//Recherche de l'emprunteur
			$requete="select empr_cb id_empr from empr where id_empr='$id_empr'";
			$resultat=mysql_query($requete);
			if (!mysql_num_rows($resultat)) {
				$error=true;
				$error_message=$selfservice_pret_carte_invalide_msg;
				$ok=0;
			} else {
				$empr_cb=mysql_result($resultat,0,0);
				$empr=new emprunteur($id_empr,'','',1);
				$pret=($empr->blocage_retard||$empr->blocage_amendes||$empr->blocage_abt||(!$empr->allow_loan)?false:true);
				if (!$pret) {
					$ok=0;
					$error=true;
					$error_message=$selfservice_pret_pret_interdit_msg;
				} else {
					if ($expl->pret_flag) {
						
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
									$error_message="Retour le : ".@mysql_result($resultat,0,0);
									$due_date=@mysql_result($resultat,0,0);
								} else {
									$ok=0;
									$error=true;
									$error_message=$selfservice_pret_quota_bloc_msg;
									$ret["message_quota"]=$pret->error_message;
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
		if ($charset!= "utf-8") $error_message=utf8_encode($error_message);
		
		$ret["status"]=$ok;
		$ret["message"]=$error_message;
		$ret["transaction_date"]=date("Ymd    His",time());
		if($charset != "utf-8")$ret["title"]=utf8_encode($titre);
		else $ret["title"]=$titre;
		$ret["due_date"]=$due_date;
		
	
		return $ret;
	}
	
	function self_checkin($expl_cb,$PMBUserId=-1) {
		global $selfservice_pret_expl_inconnu_msg;
		global $charset;
			
		$ok=0;
		$titre=$expl_cb;
		$ret["status"]="";
		$ret["message"]="";
		$ret["message_loc"]="";
		$ret["message_resa"]="";
		$ret["message_retard"]="";
		$ret["message_amende"]="";
		$ret["title"]="";
		
		
		$requete="select expl_id,expl_bulletin,expl_notice,type_antivol,empr_cb from exemplaires join pret on (expl_id=pret_idexpl) join empr on (pret_idempr=id_empr) where expl_cb='".addslashes($expl_cb)."'";
		$resultat=mysql_query($requete);
		if (!$resultat) {
			$ok=0;
			if($charset != "utf-8")	$ret["message"]=utf8_encode($selfservice_pret_expl_inconnu_msg);
			else $ret["message"]=$selfservice_pret_expl_inconnu_msg;
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
			
			$retour = new expl_to_do($expl_cb);
	 		// Fonction qu effectue le retour d'un document
	 		$retour->do_retour_selfservice();
	
	 		if ($retour->status==-1) {
	 			//Problème
	 			$ok=0; 			
	 		} else {
	 			//Pas de problème
	 			$ok=1;
	 		}		
	 		if($charset != "utf-8"){
				$ret["message_loc"]=utf8_encode($retour->message_loc);
				$ret["message_resa"]=utf8_encode($retour->message_resa);
				$ret["message_retard"]=utf8_encode($retour->message_retard);
				$ret["message_amende"]=utf8_encode($retour->message_amende);
	 		}else{
				$ret["message_loc"]=$retour->message_loc;
				$ret["message_resa"]=$retour->message_resa;
				$ret["message_retard"]=$retour->message_retard;
				$ret["message_amende"]=$retour->message_amende;	 			
	 		}
		}
		
		$ret["status"]=$ok;
		$ret["transaction_date"]=date("Ymd    His",time());
		if($charset != "utf-8")$ret["title"]=utf8_encode($titre);
		else $ret["title"]=$titre;
		return $ret;
	}
	
	function self_renew($expl_cb,$PMBUserId=-1) {
		global $opac_pret_prolongation, $opac_pret_duree_prolongation,$pmb_pret_restriction_prolongation,$pmb_pret_nombre_prolongation,$dbh,$msg;
		global $selfservice_pret_prolonge_non_msg;
		
		$titre=$expl_cb;
		$error_message="";
		$due_date=date("Ymd    His",time());	
		$ok=1;
		$ret["status"]="";
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
						$pret_nombre_prolongation=$qt->get_quota_value($struct);		
	
						if($cpt_prolongation>$pret_nombre_prolongation) $prolongation=FALSE;
	
						//Initialisation des quotas la durée de prolongations
						$qt = new quota("PROLONG_TIME_QUOTA");
						$struct["READER"] = $id_empr;
						$struct["EXPL"] = $expl_id;	
						$duree_prolongation=$qt->get_quota_value($struct);	
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
					$ok=0;
					$error_message="$selfservice_pret_prolonge_non_msg";						
				}
			}	
		
		} else{		
			$error_message="Prolongation non activée";					
		}		
		if ($charset!= "utf-8") $error_message=utf8_encode($error_message);

		$ret["status"]=$ok;
		$ret["message"]=$error_message;	
		$ret["transaction_date"]=date("Ymd    His",time());		
		if($charset != "utf-8")$ret["title"]=utf8_encode($titre);
		else $ret["title"]=$titre;	
		$ret["due_date"]=$due_date;
		return $ret;
	}
	function sql_value($rqt) {
		if(($result=mysql_query($rqt))) {
			if(($row = mysql_fetch_row($result)))	return $row[0];
		}	
		return '';
	}	
	
}
?>