<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax_retour_class.php,v 1.15 2010-07-06 10:18:34 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$class_path/emprunteur.class.php");

require_once("$class_path/serial_display.class.php");
require_once("$class_path/comptes.class.php");
require_once("$class_path/mono_display.class.php");
require_once("$class_path/audit.class.php");
require_once("$class_path/emprunteur.class.php");
require_once("$class_path/amende.class.php");
require_once("$class_path/calendar.class.php");
require_once("$base_path/circ/pret_func.inc.php");
require_once("$include_path/resa_func.inc.php");

/*
 Pour effectuer un retour de pret:
 // Appel de la class retour:
 $retour = new retour();
 // Fonction qu effectue le retour d'un document
 $status_xml = $retour->do_retour($cb_doc);


 Fonction do_retour
 		Effectue le retour d'un document emprunté
 input:			
 		$cb_doc	Cb du document 
	
 output:
 		status 
 				0 : pas d'erreur, le retour est effectué
 				-1 Erreur. Voir message d'erreur (error_message)
 		error_message
 				Message de l'erreur 
 		retour_message		
 		libelle:
 				Titre du document
 		type_doc
 		location
 		section
 		empr_nom
 		empr_prenom
 		empr_cb		
 		... 
 */

class retour {
	var $expl_cb;		
	var $msg_293;
	var $msg_652;
	var $msg_294;
	var $msg_rfid_retour_emprunteur_titre;
	var $expl_id;
	var $error_message;
	var $status ;
	var $expl_section;
	var $expl_location;
	var $expl_typdoc;
	var $expl_cote;
	var $expl_statut;
	var $expl_codestat;	
	var $expl_owner;	
	var $libelle;
	var $expl_note;	
	var $expl_comment;	
	var $expl_bulletin;
	var $expl_notice;
	var $lastempr_cb;
	var $lastempr_nom;
	var $lastempr_prenom;
	var $pret_date;
	var $empr_cp;
	var $empr_nom;
	var $empr_prenom;
	var $empr_pays;
	var $empr_codestat;
	var $empr_msg;
	var $empr_date_adhesion;
	var $empr_date_expiration;
	var $groupes;	
	var $empr_ville;
	var $empr_prof;
	var $empr_year;
	var $empr_categ;
	var $empr_sexe;
	var $empr_statut;
	var $pret_arc_id;
	var $codestat;	
	var $pret_idempr;
	var $pret_idexpl;
	var $pret_retour;
	var $aff_pret_date;
	var $aff_pret_retour;
	var $id_resa;
	var $resa_idempr;
	var $resa_idnotice;
	var $resa_idbulletin;
	var $resa_date;
	var $resa_date_fin;
	var $aff_resa_date;
	var $aff_resa_date_fin;
	var $resa_cb;
	var $cb_reservataire;
	var $nom_reservataire;
	var $prenom_reservataire;
	var $id_reservataire;	
	var $url_reservation;
	var $retour_message;
		
	// constructeur
	function retour() {
		global $include_path;
		global $msg;	
		$this->expl_cb = '';	
		// Messages utiles au traitement javascript
		$this->msg_293=$msg[293];
		$this->msg_652=$msg[652];
		$this->msg_294=$msg[294];
		$this->msg_rfid_retour_emprunteur_titre=$msg['rfid_retour_emprunteur_titre'];
	}


	function check_barcode($cb) {
		global $dbh;
		global $msg;	
		
		$query = "select * from exemplaires where expl_cb='$cb' ";
		$result = mysql_query($query, $dbh);
		$expl = mysql_fetch_object($result);
		if(!$expl->expl_id) {
			// exemplaire inconnu
			$this->error_message=$msg[367];
			$this->status=-1;
			return -1;
		} else {
			$this->expl_id = $expl->expl_id;	
			$this->expl_section = $expl->expl_section;
			$this->expl_location = $expl->expl_location;	
			$this->expl_typdoc = $expl->expl_typdoc;
			$this->nbparts	 = $expl->expl_nbparts;
			$this->expl_cote = $expl->expl_cote;
			$this->expl_comment=$expl->expl_comment;
			// récupération des infos exemplaires
			if ($expl->expl_notice) {
				$notice = new mono_display($expl->expl_notice, 0);
				$this->libelle = $notice->header_texte;
				$this->expl_notice=$expl->expl_notice;
			} else {
				$bulletin = new bulletinage_display($expl->expl_bulletin);
				$this->libelle = $bulletin->display ;
				$this->expl_bulletin=$expl->expl_bulletin;
			}
			$pos=strpos($this->libelle,'<a');
			if($pos) $this->libelle = substr($this->libelle,0,strpos($this->libelle,'<a'));		
			if ($expl->expl_lastempr) {
				// récupération des infos emprunteur
				$query_last_empr = "select empr_cb, empr_nom, empr_prenom from empr where id_empr='".$expl->expl_lastempr."' ";
				$result_last_empr = mysql_query($query_last_empr, $dbh);
				if(mysql_num_rows($result_last_empr)) {
					$last_empr = mysql_fetch_object($result_last_empr);
					$this->lastempr_cb = $last_empr->empr_cb;
					$this->lastempr_nom = $last_empr->empr_nom;
					$this->lastempr_prenom = $last_empr->empr_prenom;
				}
			}
		}
		return 0;
	}


	// mise à jour des stat des infos du prêt
	function maj_stat_pret () {
		global $dbh;
		global $msg;
		global $dbh, $empr_archivage_prets, $empr_archivage_prets_purge; 
			
		$query = "update pret_archive set ";
		$query .= "arc_debut='".$this->pret_date."', ";
		$query .= "arc_fin=now(), ";
		if ($empr_archivage_prets) $query .= "arc_id_empr='".addslashes($this->id_empr)."', ";
		$query .= "arc_empr_cp='".			addslashes($this->empr_cp)		."', ";
		$query .= "arc_empr_ville='".		addslashes($this->empr_ville)	."', ";
		$query .= "arc_empr_prof='".		addslashes($this->empr_prof)	."', ";
		$query .= "arc_empr_year='".		addslashes($this->empr_year)	."', ";
		$query .= "arc_empr_categ='".		$this->empr_categ    			."', ";
		$query .= "arc_empr_codestat='".	$this->empr_codestat 			."', ";
		$query .= "arc_empr_sexe='".		$this->empr_sexe     			."', ";
		$query .= "arc_empr_statut='".		$this->empr_statut    	 		."', ";
		$query .= "arc_expl_typdoc='".		$this->expl_typdoc   			."', ";
		$query .= "arc_expl_id='".			$this->expl_id   				."', ";
		$query .= "arc_expl_notice='".		$this->expl_notice   			."', ";
		$query .= "arc_expl_bulletin='".	$this->expl_bulletin  			."', ";
		$query .= "arc_expl_cote='".		addslashes($this->expl_cote)	."', ";
		$query .= "arc_expl_statut='".		$this->expl_statut   			."', ";
		$query .= "arc_expl_location='".	$this->expl_location 			."', ";
		$query .= "arc_expl_section='".		$this->expl_section 			."', ";
		$query .= "arc_expl_codestat='".	$this->expl_codestat 			."', ";
		$query .= "arc_expl_owner='".		$this->expl_owner    			."', ";		
		$query .= "arc_niveau_relance='".	$this->niveau_relance  			."', ";
		$query .= "arc_date_relance='".		$this->date_relance    			."', ";
		$query .= "arc_printed='".			$this->printed    				."', ";
		$query .= "arc_cpt_prolongation='".	$this->cpt_prolongation 		."' ";
						
		$query .= " where arc_id='".$this->pret_arc_id."' ";
		
		$res = mysql_query($query, $dbh);
		// purge des vieux trucs
		if ($empr_archivage_prets_purge) {
			//on ne purge qu'une fois par session et par jour
			if (!isset($_SESSION["last_empr_archivage_prets_purge_day"]) || ($_SESSION["last_empr_archivage_prets_purge_day"] != date("m.d.y"))) {
				mysql_query("update pret_archive set arc_id_empr=0 where arc_id_empr!=0 and date_add(arc_fin, interval $empr_archivage_prets_purge day) < sysdate()") or die(mysql_error()."<br />"."update pret_archive set arc_id_empr=0 where arc_id_empr!=0 and date_add(arc_fin, interval $empr_archivage_prets_purge day) < sysdate()");
				$_SESSION["last_empr_archivage_prets_purge_day"] = date("m.d.y");
			}
		}		

		return $res ;
	}

	function check_pret() {
		global $dbh;
		global $msg;
			
		// récupération des infos du prêt
		$query = "select *, date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, IF(pret_retour>sysdate(),0,1) as retard from pret where pret_idexpl=".$this->expl_id." limit 1";
		$result = mysql_query($query, $dbh);
	
		if(mysql_num_rows($result)) 
		{
			$pret = mysql_fetch_object($result);	
			// le document était bien en prêt -> récupération des infos du prêt
			$this->pret_idempr = $pret->pret_idempr;
			$this->pret_idexpl = $pret->pret_idexpl;
			$this->pret_date = $pret->pret_date;
			$this->pret_retour = $pret->pret_retour;
			$this->aff_pret_date = $pret->aff_pret_date;
			$this->aff_pret_retour = $pret->aff_pret_retour;
			$this->pret_arc_id = $pret->pret_arc_id;
			$this->niveau_relance = $pret->niveau_relance;
			$this->date_relance = $pret->date_relance;
			$this->printed = $pret->printed;
			$this->cpt_prolongation  = $pret->cpt_prolongation;		
				
			// récupération des infos emprunteur
			$query = "select * , date_format(empr_date_adhesion, '".$msg["format_date"]."') as aff_empr_date_adhesion,
			date_format(empr_date_expiration, '".$msg["format_date"]."') as aff_empr_date_expiration from empr where id_empr=".$pret->pret_idempr." limit 1";
			$result = mysql_query($query, $dbh);
			if(mysql_num_rows($result)) {
	
				// stockage des infos sur l'emprunteur
				$empr = mysql_fetch_object($result);
				$this->empr_cb = $empr->empr_cb;
				$this->id_empr = $empr->id_empr;
				$this->empr_nom = $empr->empr_nom;
				$this->empr_prenom = $empr->empr_prenom;
				$this->empr_ville = $empr->empr_ville;
				$this->empr_cp = $empr->empr_cp;
				$this->empr_pays = $empr->empr_pays;
				$this->empr_prof = $empr->empr_prof;
				$this->empr_year = $empr->empr_year;
				$this->empr_categ = $empr->empr_categ;
				$this->empr_codestat = $empr->empr_codestat;
				$this->empr_sexe = $empr->empr_sexe;
				$this->empr_statut = $empr->empr_statut;
				$this->empr_msg = $empr->empr_msg;
				$this->empr_date_adhesion = $empr->aff_empr_date_adhesion;
				$this->empr_date_expiration = $empr->aff_empr_date_expiration;				
				
				$query_groupe = "select libelle_groupe from groupe, empr_groupe where empr_id='".$pret->pret_idempr."' and groupe_id=id_groupe";
				$result_g = mysql_query($query_groupe, $dbh);
				while ($groupes=mysql_fetch_object($result_g)) $groupesarray[]=$groupes->libelle_groupe ;
				$this->groupes = @implode("/",$groupesarray);
			}
		}

		return 0;
	}	
	
	function check_resa() {
		global $dbh;
		global $msg; 
		
		if (!$this->expl_notice) $this->expl_notice=0;
		if (!$this->expl_bulletin) $this->expl_bulletin=0 ;
		$rqt = "select *, IF(resa_date_fin>sysdate(),0,1) as perimee, date_format(resa_date_fin, '".$msg["format_date"]."') as aff_resa_date_fin, date_format(resa_date, '".$msg["format_date"]."') as aff_resa_date from resa where resa_idnotice='".$this->expl_notice."' and resa_idbulletin='".$this->expl_bulletin."' order by resa_date limit 1 ";
		
		$result = mysql_query($rqt, $dbh) or die (mysql_error()) ;
		if(mysql_num_rows($result)) {
			// des réservations ont été trouvées -> récupération des infos résa
			$resa = mysql_fetch_object($result);
			$this->id_resa = $resa->id_resa;
			$this->resa_idempr = $resa->resa_idempr;
			$this->resa_idnotice = $resa->resa_idnotice;
			$this->resa_idbulletin = $resa->resa_idbulletin;
			$this->resa_date = $resa->resa_date;
			$this->resa_date_fin = $resa->resa_date_fin;
			$this->aff_resa_date = $resa->aff_resa_date;
			$this->aff_resa_date_fin = $resa->aff_resa_date_fin;
			$this->resa_cb = $resa->resa_cb;
			
			// récupération des infos sur le réservataire
			$query = "select empr_nom, empr_prenom, empr_cb, id_empr from empr where id_empr=".$resa->resa_idempr." limit 1";
			$result = mysql_query($query, $dbh);
			if(mysql_num_rows($result)) {
				// stockage des infos sur le réservataire
				$empr = mysql_fetch_object($result);
				$this->cb_reservataire = $empr->empr_cb;
				$this->nom_reservataire = $empr->empr_nom;
				$this->prenom_reservataire = $empr->empr_prenom;
				$this->id_reservataire = $empr->id_empr;
			}
			$this->error_message=$msg["rfid_retour_document_reserve_message"];
		}
		return 0;	
	}


	function do_retour_doc() {
	
		global $dbh;
		global $msg;
		global $pmb_gestion_amende,$pmb_gestion_financiere,$pmb_blocage_retard, $pmb_blocage_max, $pmb_blocage_delai, $pmb_blocage_coef;
	
		// récupération localisation exemplaire
		$query = "select t.tdoc_libelle as type_doc";
		$query .= ", l.location_libelle as location";
		$query .= ", s.section_libelle as section";
		$query .= " from docs_type t";
		$query .= ", docs_location l";
		$query .= ", docs_section s";
		$query .= " where t.idtyp_doc=".$this->expl_typdoc;
		$query .= " and l.idlocation=".$this->expl_location;
		$query .= " and s.idsection=".$this->expl_section;
		$query .= " limit 1";
	
		$result = mysql_query($query, $dbh);
		$info_doc = mysql_fetch_object($result);
		
		$this->type_doc=$info_doc->type_doc;
		if($this->nbparts>1)
					$this->type_doc.=" (".$this->nbparts.")";
		$this->location=$info_doc->location;
		$this->section=$info_doc->section;
			
		if ($this->expl_note) {
			$this->error_message=$msg[377];
		}	
		if ($this->pret_idempr) {	
			// calcul du retard éventuel
			$rqt_date = "select ((TO_DAYS(CURDATE()) - TO_DAYS('$this->pret_retour'))) as retard ";
			$resultatdate=mysql_query($rqt_date);
			$resdate=mysql_fetch_object($resultatdate);
			$retard = $resdate->retard;
			if($retard > 0) {
				//Calcul du vrai nombre de jours
				$date_debut=explode("-",$this->pret_retour);
				$ndays=calendar::get_open_days($date_debut[2],$date_debut[1],$date_debut[0],date("d"),date("m"),date("Y"));
				if ($ndays>0) {
					$retard = (int)$ndays;
					$this->error_message=$msg[369]." : ".$retard." ".$msg[370];
				}
			}
			//Calcul du blocage
			if ($pmb_blocage_retard) {
				$date_debut=explode("-",$this->pret_retour);
				$ndays=calendar::get_open_days($date_debut[2],$date_debut[1],$date_debut[0],date("d"),date("m"),date("Y"));
				if ($ndays>$pmb_blocage_delai) {
					$ndays=$ndays*$pmb_blocage_coef;
					if (($ndays>$pmb_blocage_max)&&($pmb_blocage_max!=0)) {
						$ndays=$pmb_blocage_max;
					}
				} else $ndays=0;
				if ($ndays>0) {
					//Calcul de la date de fin
					$date_fin=calendar::add_days(date("d"),date("m"),date("Y"),$ndays);
					//Mise à jour
					mysql_query("update empr set date_fin_blocage='".$date_fin."' where id_empr='".$this->pret_idempr."'");
					$this->error_message=sprintf($msg["blocage_retard_pret"],formatdate($date_fin));
				}
			}		
			//Vérification des amendes
			if (($pmb_gestion_financiere) && ($pmb_gestion_amende)) {
				$amende=new amende($this->pret_idempr);
				$amende_t=$amende->get_amende($this->pret_idexpl);
				//Si il y a une amende, je la débite
				if ($amende_t["valeur"]) {
					$this->error_message=$msg["finance_retour_amende"]."&nbsp;: ".comptes::format($amende_t["valeur"]);
					$compte_id=comptes::get_compte_id_from_empr($this->pret_idempr,2);
					if ($compte_id) {
						$cpte=new comptes($compte_id);
						if ($cpte->id_compte) {
							$cpte->record_transaction("",$amende_t["valeur"],-1,sprintf($msg["finance_retour_amende_expl"],$this->pret_idexpl),0);
							$this->error_message.=" ".$msg["finance_retour_amende_recorded"];
						}
					}
				}
			}
			// Suppression prêt et la mise en table de stat	
			$query = "delete from pret where pret_idexpl = '" . $this->pret_idexpl . "' ";
			$result = mysql_query($query, $dbh);
			
			if($result) {
				$this->retour_message=$msg["retour_ok"];
				if (!$this->maj_stat_pret()) {
					// impossible de maj en table stat
					$this->error_message=$msg[371];
				}
			} else {
				// impossible de supprimer en table pret
				$this->error_message=$msg[372];
			}
			// traitement de l'éventuelle réservation
			if ($this->resa_idempr) {
				// le doc en retour peut servir à valider une résa suivante
				if (!verif_cb_utilise ($this->cb_expl)) {
					$affect = affecte_cb ($this->cb_expl) ;
					// affichage message de réservation
					if ($affect) {
						$this->error_message=$msg["rfid_retour_document_reserve_message"];
						$this->url_reservation="./circ.php?categ=pret&form_cb=".rawurlencode($this->cb_reservataire);
					} // fin if affect
				} // fin if !verif_cb_utilise
			} // fin if resa
		} else {
			$this->error_message=$msg[605];
		}
	}

	
	function do_retour( $cb_expl) {	
		
		$this->cb_expl=$cb_expl;
		$this->error_message='';
		$this->status=0;
		if ($this->check_barcode($cb_expl)==0) {
			$this->check_pret();
			$this->check_resa();
			$this->do_retour_doc();
		}			
		$array[0]=$this;
		$buf_xml = array2xml($array);		
		return $buf_xml;
	}

// Fin class		
}


?>