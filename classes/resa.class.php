<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: resa.class.php,v 1.28 2010-08-05 14:45:33 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// classe emprunteur
// classe remaniée le 7.12.2003. : prise en compte de résas sur le bulletinage

if ( ! defined( 'RESA_CLASS' ) ) {
 	define( 'RESA_CLASS', 1 );

	require_once("$class_path/quotas.class.php");
	
	class reservation {
	
		//---------------------------------------------------------
		//			Propriétés
		//---------------------------------------------------------
		var $id				= 0	;		// id MySQL de la réservation
		var $id_empr		= 0	;		// id MySQL emprunteur
		var $id_notice		= 0	;		// id notice
		var $id_bulletin	= 0	;		// id bulletin si applicable
		var $tstamp			= '';		// time stamp de la réservation
		var $message		= '';		// message d'erreur éventuel
		var $statut			= 0	;		// statuts possibles
		var $notice			= '';		// notice abrégée (pour affichage)
		var $force 			= 0; 		// Forcage de la notice en cas de dépassement de quota de réservation
		var $expl_cb		= ''; 
		var $expl_id		= 0; 
		/* note les statuts possibles :
		0	->	aucun problème pour réserver
		1	->	aucun exemplaire ne peut être reservé
		2	->	un ou des exemplaires peuvent être reservés et un au moins des exemplaires est disponible
		*/
		
		//---------------------------------------------------------
		//			Méthodes
		//---------------------------------------------------------
		
		// <----------------- constructeur ------------------>
		function reservation($id_empr=0, $id_notice=0, $bulletinage=0,$cb='') {
			global $dbh;
			$this->id_empr = $id_empr;
			$this->id_notice = $id_notice;
			if($bulletinage) {
				$this->id_bulletin = $bulletinage;
				$this->id_notice = 0;
			}
			if($cb) {
				$query = "select expl_id,expl_notice,expl_bulletin from exemplaires where expl_cb='$cb' limit 1";
				$result = mysql_query($query, $dbh);
				if (($expl = mysql_fetch_object($result))) {		
										
					$this->id_notice = $expl->expl_notice;
					$this->id_bulletin = $expl->expl_bulletin;
					$this->expl_id = $expl->expl_id;
					$this->expl_cb = $cb;
				} else {
					$this->id_bulletin =0;
					$this->id_notice =0;
				}
			}	
		}
		
		function get_empr_info() {
			global $dbh;
			$query = "select resa_idempr as empr, id_resa, resa_cb, concat(ifnull(concat(empr_nom,' '),''),empr_prenom) as nom_prenom, empr_cb 
			from resa left join empr on resa_idempr=id_empr where resa_idnotice='".$this->id_notice."' and resa_idbulletin='".$this->id_bulletin."' order by resa_date limit 1";
			$result = mysql_query($query, $dbh);
			if (mysql_num_rows($result)) { 		
				if(($row=mysql_fetch_object($result))) {
					$link="<a href=\"./circ.php?categ=pret&form_cb=".$row->empr_cb."\">".$row->nom_prenom."</a>";
					return $link;
				}				
			}
			return '';
		}
		function get_empr_info_cb() {
			global $dbh;
			$query = "select resa_idempr as empr, id_resa, resa_cb, concat(ifnull(concat(empr_nom,' '),''),empr_prenom) as nom_prenom, empr_cb 
			from resa, empr where resa_cb='".$this->expl_cb."' and resa_idempr=id_empr limit 1";
			$result = mysql_query($query, $dbh);
			if (mysql_num_rows($result)) { 		
				if(($row=mysql_fetch_object($result))) {
					$link="<a href=\"./circ.php?categ=pret&form_cb=".$row->empr_cb."\">".$row->nom_prenom."</a>";
					return $link;
				}				
			}
			return '';
		}		
		function check_expl_reservable($id_expl) {
			global $dbh;
			$query = "select e.expl_cb as cb, e.expl_id as id, s.pret_flag as pretable, e.expl_notice as notice, e.expl_bulletin as bulletin, e.expl_note as note, expl_comment, s.statut_libelle as statut";
			$query .= " from exemplaires e, docs_statut s";
			$query .= " where e.expl_id=$id_expl";
			$query .= " and s.idstatut=e.expl_statut";
			$query .= " limit 1";
	
			$result = mysql_query($query, $dbh);
			if (($expl = mysql_fetch_array($result))) {			
				if (!$expl['pretable']) {
					// l'exemplaire est en consultation sur place
					return 0;				
				}
			} else {
				// exemplaire inconnu
				return 0;
			}
			// on check si l'exemplaire est déjà en prêt
			$query = "select pret_idempr from pret where pret_idexpl=$id_expl limit 1";
			$result = mysql_query($query, $dbh);
			if (@ mysql_num_rows($result)) {
				// l'exemplaire est déjà en prêt
				return 0;
			}	
			// on check si l'exemplaire a une réservation
			$query = "select resa_idempr as empr, id_resa, resa_cb, concat(ifnull(concat(empr_nom,' '),''),empr_prenom) as nom_prenom, empr_cb from resa left join empr on resa_idempr=id_empr where resa_idnotice='$expl->notice' and resa_idbulletin='$expl->bulletin' order by resa_date limit 1";
			$result = mysql_query($query, $dbh);
			if (mysql_num_rows($result)) { 
				// l'exemplaire a une réservation
				return 0;
				
			}
			// l'exemplaire est disponible pour valider une réservation
			return 1;
		}
		
		function check_localisation_expl() {
			global $dbh,$msg;
			// recup de la localisation de l'emprunteur
			$query = "select empr_location from empr where id_empr=".$this->id_empr;
			$res = mysql_query($query, $dbh);			
			$empr = mysql_fetch_object($res);
			$empr_location=$empr->empr_location;
			
			if($this->id_notice) $field_expl= " expl_notice=$this->id_notice ";
			else $field_expl= " expl_bulletin=$this->id_bulletin ";
			// vérifier si un exemplaire est disponible dans les localisation autorisées
			$requete="select expl_id from exemplaires e ,docs_statut s
			where  $field_expl and  expl_location in (select resa_loc  from resa_loc where resa_emprloc=$empr_location )
			and s.idstatut=e.expl_statut and s.pret_flag=1
			limit 1";

			$res = mysql_query($requete, $dbh);	
			if(mysql_num_rows($res)) {
				return TRUE;							
			}			
			// recup de la liste des localisations ou l'emprunteur peut réserver un exemplaire
			$requete="select location_libelle from resa_loc, docs_location  where resa_emprloc=$empr_location and idlocation=resa_loc";
			$res = mysql_query($requete, $dbh);	
			$locations="";
			if(mysql_num_rows($res)) {
				while(($row=mysql_fetch_object($res))) {
					if($locations) $locations.=", ";
					$locations.=$row->location_libelle;				
				}
			}
			$this->message = "<strong>".str_replace("!!loc_liste!!",$locations,$msg["resa_no_expl_in_location"])."</strong>";
			return FALSE;
		}
		
		function can_reserve() {
			global $dbh;
			global $msg;
			global $quota_resa;
			global $pmb_transferts_actif, $transferts_choix_lieu_opac,$pmb_location_reservation;
			
			$this->service->error="";
			if (!$this->empr_exists() ){
				$this->service->error="check_empr_exists";
				return FALSE;
			}		
			if(!$this->notice_exists() ){
				$this->service->error="check_notice_exists";
				return FALSE;
			}			
			
			//les quotas
			if (!$quota_resa) {
				//Si un quota atteint (check_quota inclu le message + la mise à 0 ou 1 de $this->force)
				if (($ret=$this->check_quota())) {
					$this->service->message=$ret["ERROR"];
					$this->service->error="check_quota";
					return FALSE;
				}
			}

			if($this->resa_exists() ){
				$this->service->error="check_resa_exists";
				return FALSE;
			}
			if($this->allready_loaned()){
				$this->service->error="check_allready_loaned";
				return FALSE;
			}
			// check_statut inclus la possibilité de réserver ou pas les docs dispo 
			if($this->check_statut()) {
				// $this->service->error est affecté dans check_statut()
				return FALSE;
			}				
			if($pmb_location_reservation) {
				if(!$this->check_localisation_expl()){
					$this->service->error="check_localisation_expl";	
					return FALSE;
				}			
			}		
			return TRUE;
		}
		
		// <----------------- add() : ajout d'une réservation ------------------>
		function add($idloc_retrait=0) {
			global $dbh;
			global $msg;
			global $quota_resa;
			global $pmb_transferts_actif, $transferts_choix_lieu_opac,$pmb_location_reservation;
			
			$this->service->error="";
			if (!$this->empr_exists() ){
				$this->service->error="check_empr_exists";
				return FALSE;
			}		
			if(!$this->notice_exists() ){
				$this->service->error="check_notice_exists";
				return FALSE;
			}			
			//les quotas
			if (!$quota_resa) {
				//Si un quota atteint (check_quota inclu le message + la mise à 0 ou 1 de $this->force)
				if (($ret=$this->check_quota())) {
					$this->service->message=$ret["ERROR"];
					$this->service->error="check_quota";
					return FALSE;
				}
			}

			if($this->resa_exists() ){
				$this->service->error="check_resa_exists";
				return FALSE;
			}
			if($this->allready_loaned()){
				$this->service->error="check_allready_loaned";
				return FALSE;
			}	
			// check_statut inclus la possibilité de réserver ou pas les docs dispo 
			if($this->check_statut()) {
				// $this->service->error est affecté dans check_statut()
				return FALSE;				
			}				
			if($pmb_location_reservation) {
				if(!$this->check_localisation_expl()){
					$this->service->error="check_localisation_expl";	
					return FALSE;
				}			
			}				
			// tout est OK, écriture de la réservation en table
			// On récupère d'abord la durée
			$t=get_time($this->id_empr,$this->id_notice,$this->id_bulletin);
			$query = "INSERT INTO resa (id_resa, resa_idempr, resa_idnotice, resa_idbulletin, resa_date, resa_loc_retrait) ";
			$query .= "VALUES ('', '".$this->id_empr."', ";
			if($this->id_notice) $query .= "'".$this->id_notice."',0 ,";
				elseif ($this->id_bulletin)
					$query .= "0, '".$this->id_bulletin."',";
			$query .= " SYSDATE(),";
						
			if (($pmb_transferts_actif=="1")&&($transferts_choix_lieu_opac=="1")) {
				$rqt = "SELECT empr_location FROM empr WHERE id_empr=".$this->id_empr;
				$idloc_retrait = mysql_result(mysql_query($rqt),0);				
			} 
			$query .= "'$idloc_retrait' )";			
			$result = mysql_query($query, $dbh);
			if(!$result) {
				$this->message = "$query -> $msg[resa_no_create]";
				$this->service->error="resa_no_create";	
				return FALSE;
			}else {
				$this->id = mysql_insert_id($dbh);
				$this->message = $msg["resa_ajoutee"];
				
				// Archivage de la résa: info lecteur et notice et nombre d'exemplaire
				$rqt = "SELECT * FROM empr WHERE id_empr=".$this->id_empr;
				$empr = mysql_fetch_object(mysql_query($rqt));	
						
				$id_notice=$id_bulletin=0;
				if($this->id_notice) {
					$id_notice=	$this->id_notice;
					$query = "SELECT count(*) FROM exemplaires where expl_notice='$id_notice'";
				}elseif($this->id_bulletin) {
					$id_bulletin=$this->id_bulletin;
					$query = "SELECT count(*) FROM exemplaires where expl_bulletin='$id_bulletin'";
				}
				$nb_expl = mysql_result(mysql_query($query),0);
				
				$query = "INSERT INTO resa_archive SET
					resarc_id_empr = '".$this->id_empr."', 
					resarc_idnotice = '".$id_notice."', 
					resarc_idbulletin = '".$id_bulletin."',
					resarc_date = SYSDATE(), 
					resarc_loc_retrait = '$idloc_retrait',
					resarc_from_opac= 0,
					resarc_empr_cp ='".addslashes($empr->empr_cp)."',
					resarc_empr_ville = '".addslashes($empr->empr_ville)."',
					resarc_empr_prof = '".addslashes($empr->empr_prof)."',
					resarc_empr_year = '".$empr->empr_year."',
					resarc_empr_categ = '".$empr->empr_categ."',
					resarc_empr_codestat = '".$empr->empr_codestat ."',
					resarc_empr_sexe = '".$empr->empr_sexe."',
					resarc_empr_location = '".$empr->empr_location."',
					resarc_expl_nb = '$nb_expl'		
				 ";
				mysql_query($query, $dbh);
				$stat_id = mysql_insert_id($dbh);
				// Lier achive et résa pour suivre l'évolution de la résa
				$query = "update resa SET resa_arc='$stat_id' where id_resa='".$this->id."'";
				mysql_query($query, $dbh);
			}
			return TRUE;
		}
		
		// <----------------- delete() : suppression d'une réservation ------------------>
		function delete() {
			global $dbh;
			global $msg;
			//  suppression  de la réservation de la table des réservations
			$id_notice=$id_bulletin=0;
			if($this->id_notice) {
				$id_notice=$this->id_notice;
				$query = "delete from resa where resa_idempr=".$this->id_empr." and resa_idnotice=".$this->id_notice;
			} elseif($this->id_bulletin) {
				$id_bulletin=$this->id_bulletin;
				$query = "delete from resa where resa_idempr=".$this->id_empr." and resa_idbulletin=".$this->id_bulletin;
			}	
			$result = @mysql_query($query, $dbh);
			// archivage
			$rqt_arch = "UPDATE resa_archive SET resarc_anulee = 1 WHERE resarc_id_empr = '".$this->id_empr."' and resarc_idnotice = '".$id_notice."' and	resarc_idbulletin = '".$id_bulletin."' "; 
			mysql_query($rqt_arch, $dbh);
			
			if(!$result) {
				$this->message = $msg["resa_no_suppr"];
				return FALSE;
			} else {
				// on checke l'existence d'autres réservataires
				$query = "select e.empr_nom, e.empr_prenom, e.empr_cb from resa r, empr e";
				$query .= " where r.resa_idempr=e.id_empr";
				if($this->id_notice)
					$query .= " and r.resa_idnotice=".$this->id_notice;
				elseif($this->id_bulletin)
					$query .= " and r.resa_idbulletin=".$this->id_bulletin;
				$query .= " order by r.resa_date limit 1";
				$result = mysql_query($query, $dbh);
				if(mysql_num_rows($result)) {
		
					// d'autres réservataires existent
					$next_empr = mysql_fetch_object($result);
		
					$this->message = $msg[resa_supprimee];
		
					// on regarde la disponibilité du document
					// on compte le nombre total d'exemplaires pour la notice
					if($this->id_notice)
						$query = "select count(1) from exemplaires where expl_notice=".$this->id_notice;
					elseif($this->id_bulletin)
						$query = "select count(1) from exemplaires where expl_bulletin=".$this->id_bulletin;
					$result = mysql_query($query, $dbh);
					$total_ex = mysql_result($result, 0, 0);
		
					// on compte le nombre d'exemplaires sortis
					$query = "select count(1) from exemplaires e, pret p";
					if($this->id_notice)
						$query .= " where e.expl_notice=".$this->id_notice;
					elseif($this->id_bulletin)
						$query .= " where e.expl_bulletin=".$this->id_bulletin;			
					$query .= " and p.pret_idexpl=e.expl_id";
					$result = mysql_query($query, $dbh);
					$total_sortis = mysql_result($result, 0, 0);
		
					// on en déduit le nombre d'exemplaires disponibles
					$total_dispo = $total_ex - $total_sortis;
		
					if($total_dispo) {
						$this->message .= " <strong> $msg[resa_dispo_suivant] ";
						$this->message .= $next_empr->empr_nom." ".$next_empr->empr_prenom."</strong>. (".$next_empr->empr_cb.")";
						}
					return TRUE;
				} else {
					$this->message = $msg["resa_supprimee"];
					return TRUE;
				}
			}
		}
		
		// <----------------- check_quota() : vérification du dépassement de quota -------------------------->
		function check_quota() {
			$error=check_quota_resa($this->id_empr,$this->id_notice,$this->id_bulletin);
			if ($error["ERROR"]) {
				$this->message=$error["MESSAGE"];
				$this->force=$error["FORCE"];
			}
			return $error["ERROR"];
		}
		
		// <----------------- empr_exists() : vérification de l'existence de l'utilisateur ------------------>
		function empr_exists() {
			global $dbh;
			global $msg;
			$query = "select count(1) from empr where id_empr=".$this->id_empr;
			$result = @mysql_query($query, $dbh);
			if(!@mysql_result($result, 0, 0)) {
				$this->message = "<strong>$msg[resa_no_empr]</strong>";
				return FALSE;
				}
			return TRUE;
		}
		
		// <----------------- notice_exists() : vérification de l'existence de la notice  ou du bulletinage ------------------>
		function notice_exists() {
			global $dbh;
			global $msg;
			if($this->id_notice) $query = "select count(1) from notices where notice_id=".$this->id_notice;
				elseif ($this->id_bulletin)
					$query = "select count(1) from bulletins where bulletin_id=".$this->id_bulletin;
			$result = @mysql_query($query, $dbh);
			if(!@mysql_result($result, 0, 0)) {
				$this->message = "<strong>$msg[resa_no_doc]</strong>";
				return FALSE;
				}
			return TRUE;
		}
		
		// <----------------- resa_exists() : vérification de l'existence de la réservation ------------------>
		function resa_exists() {
			global $dbh;
			global $msg;
			$query = "select count(1) from resa where resa_idempr=".$this->id_empr;
			if($this->id_notice) $query .= " and resa_idnotice=".$this->id_notice;
				elseif ($this->id_bulletin)
					$query .= " and resa_idbulletin=".$this->id_bulletin;
			$result = @mysql_query($query, $dbh);
			if(@mysql_result($result, 0, 0)) {
				$this->message = "<strong>$msg[resa_deja_resa]</strong>";
				return TRUE;
				}
			return FALSE;
		}
		
		// <----------------- allready_loaned() : on regarde si l'emprunteur n'a pas déjà ce document ------------------>
		function allready_loaned() {
			global $dbh;
			global $msg;
			$query = "select count(1) from pret p, exemplaires e";
			$query .= " where p.pret_idempr=".$this->id_empr;
			$query .= " and p.pret_idexpl=e.expl_id";
			if($this->id_notice)
				$query .= " and e.expl_notice=".$this->id_notice;
			elseif ($this->id_bulletin)
				$query .= " and e.expl_bulletin=".$this->id_bulletin;
			$result = @mysql_query($query, $dbh);
			if(@mysql_result($result, 0, 0)) {
				$this->message = "<strong>$msg[resa_deja_doc]</strong>";
				return TRUE;
			}
			return FALSE;
		}
		
		// <----------------- check_statut() : le genre de choses qu'on peut attendre en retour ------------------>
		/* fonction complexe à rediscuter : cas possibles :
		- doc en consultation sur place uniquement
		- doc mixed : exemplaire(s) en consultation sur place et exemplaire(s) en circulation
		- doc en circulation ET disponible
		La solution retenue : fetcher tous les exemplaires attachés à la notice et définir des flags de situation
		*/
		function check_statut() {
			global $dbh;
			global $pmb_resa_dispo; // les résa de disponibles sont-elles autorisées ?
			global $msg,$pmb_location_reservation;
			
			if ($this->id_notice) {
				$notice = new mono_display($this->id_notice, 0);
				$this->notice = $notice->header;
			} elseif ($this->id_bulletin) {
				$bulletin = new bulletinage($this->id_bulletin, 0);
				$this->notice = $bulletin->display;
			}
		
			// on checke s'il y a des exemplaires prêtables
			$query = "select expl_id ,expl_cb from exemplaires e, docs_statut s";
			if($this->id_notice) 
				$query .= " where s.idstatut=e.expl_statut and s.pret_flag=1 and e.expl_notice=".$this->id_notice;
			elseif ($this->id_bulletin)
				$query .= " where s.idstatut=e.expl_statut and s.pret_flag=1 and e.expl_bulletin=".$this->id_bulletin;
			if($pmb_location_reservation) {	
				$query_loc = "select empr_location from empr where id_empr=".$this->id_empr;
				$res = mysql_query($query_loc, $dbh);			
				$empr = mysql_fetch_object($res);
				$empr_location=$empr->empr_location;		
				$query.=" and e.expl_location in (select resa_loc from resa_loc where resa_emprloc=$empr_location) ";
			}		
			$result = mysql_query($query, $dbh);
			if(!@mysql_num_rows($result)) {
				// aucun exemplaire n'est disponible pour le prêt
				$this->message .= "$msg[resa]&nbsp;:&nbsp;".$this->notice."<br /><strong>$msg[resa_no_expl]</strong>";
				$this->service->error="check_statut";	
				return 1;
			}

			// on regarde si les résa de disponibles sont autorisées
			if ($pmb_resa_dispo=="1") return 0;
			
			// on checke si un exemplaire est disponible
			// aka. si un des exemplaires en circulation n'est pas mentionné dans la table des prêts,
			// c'est qu'il est disponible à la bibliothèque
			$list_dispo = '';
		
			while($pretable = mysql_fetch_object($result)) {
				$req2 = "select count(1) from pret where pret_idexpl=".$pretable->expl_id;		
				$req2_result = mysql_query($req2, $dbh);
				if(!mysql_result($req2_result, 0, 0)) {
					// l'exemplaire ne figure pas dans la table pret -> dispo
					// on récupère les données exemplaires pour constituer le message
					$req3 = "select p.expl_cote, s.section_libelle, l.location_libelle";
					$req3 .= " from exemplaires p, docs_section s, docs_location l";
					$req3 .= " where p.expl_id=".$pretable->expl_id;
					$req3 .= " and s.idsection=p.expl_section";
					$req3 .= " and l.idlocation=p.expl_location limit 1";
					
					$req3_result = mysql_query($req3, $dbh);
					$req3_obj = mysql_fetch_object($req3_result);
					if($req3_obj->expl_cote) {				
						// Si résa validé il n'est pas disponible en prêt
						$req4 = "select count(1) from resa where resa_cb='".$pretable->expl_cb."' and resa_confirmee='1'";
						$req4_result = mysql_query($req4, $dbh);
						if(!mysql_result($req4_result, 0, 0)) {	
							$list_dispo .= '<br />'.$req3_obj->location_libelle.'.';
							$list_dispo .= $req3_obj->section_libelle.' cote&nbsp;: '.$req3_obj->expl_cote;
						}
					}
				}
			}
		
			if($list_dispo) {

				$this->message = "$msg[resa_doc_dispo]<br />";
				$this->message .= $this->notice.$list_dispo;
				$this->service->error="check_doc_dispo";	
				return 2;
			}
		
			// rien de spécial
			return  0;
		}
		
		
		function get_resa_cb() {
			global $dbh;
			global $pmb_resa_dispo; // les résa de disponibles sont-elles autorisées ?
			global $msg,$pmb_location_reservation,$deflt_docs_location,$pmb_transferts_actif,$transferts_choix_lieu_opac;
			
			$this->expl_affectable=array();
			$this->expl_reservable=array();
			$this->expl_transferable=array();
			
			// on check s'il y a des exemplaires prêtables
			$query = "select expl_id,expl_cb from exemplaires e, docs_statut s";
			if($this->id_notice) 
				$query .= " where s.idstatut=e.expl_statut and s.pret_flag=1 and e.expl_notice=".$this->id_notice;
			elseif ($this->id_bulletin)
				$query .= " where s.idstatut=e.expl_statut and s.pret_flag=1 and e.expl_bulletin=".$this->id_bulletin;
				
				
			if($pmb_location_reservation) {	
				$query_loc = "select empr_location from empr where id_empr=".$this->id_empr;
				$res = mysql_query($query_loc, $dbh);			
				$empr = mysql_fetch_object($res);
				$empr_location=$empr->empr_location;		
				$query.=" and e.expl_location in (select resa_loc from resa_loc where resa_emprloc=$empr_location) ";
			}		
			$result = mysql_query($query, $dbh);
			if(!@mysql_num_rows($result)) {
				// aucun exemplaire n'est disponible pour le prêt
				$this->message .= "$msg[resa]&nbsp;:&nbsp;".$this->notice."<br /><strong>$msg[resa_no_expl]</strong>";
				return 1;
			}
		
			while(($pretable = mysql_fetch_object($result))) {
				$req2 = "select count(1) from pret where pret_idexpl=".$pretable->expl_id;		
				$req2_result = mysql_query($req2, $dbh);
				if(!mysql_result($req2_result, 0, 0)) {
					// l'exemplaire ne figure pas dans la table pret -> dispo
					// on récupère les données exemplaires pour constituer le message
					$req3 = "select p.expl_cote, s.section_libelle, l.location_libelle, expl_location";
					$req3 .= " from exemplaires p, docs_section s, docs_location l";
					$req3 .= " where p.expl_id=".$pretable->expl_id;
					$req3 .= " and s.idsection=p.expl_section";
					$req3 .= " and l.idlocation=p.expl_location limit 1";
					$req3_result = mysql_query($req3, $dbh);
					$req3_obj = mysql_fetch_object($req3_result);
					if($req3_obj->expl_cote) {
						// Si résa validé il n'est pas disponible en prêt
						$req4 = "select count(1) from resa where resa_cb='".addslashes($pretable->expl_cb)."' and resa_confirmee='1'";
						$req4_result = mysql_query($req4, $dbh);
						if(!mysql_result($req4_result, 0, 0)) {					
							$this->expl_affectable[]=$pretable->expl_cb;
						}
						if($req3_obj->expl_location !=$deflt_docs_location) {
							$this->expl_transferable[]=$pretable->expl_cb;
						}	
					}
				}
				$this->expl_reservable[]=$pretable->expl_cb;
			}
			// Calcul du rang de réservation du lecteur
			$rank = 1;	
			
			if ($pmb_transferts_actif=="1") {	
				switch ($transferts_choix_lieu_opac) {
					case "1":
						//retrait de la resa sur lieu choisi par le lecteur					
						$where= " AND resa_loc_retrait=" . $deflt_docs_location;					
					break;					
					case "2":
						//retrait de la resa sur lieu fixé
						$where= " AND resa_loc_retrait=" . $deflt_docs_location;
					break;			
					case "3":
						//retrait de la resa sur lieu exemplaire
						$from= " ,exemplaires ";
						$where= " AND expl_cb='".$pretable->expl_cb."' and expl_location=" . $deflt_docs_location;
					break;	
					default:
						//retrait de la resa sur lieu lecteur
						$from= " ,empr ";
						$where= " AND resa_idempr=id_empr and empr_location=" . $deflt_docs_location;
					break;						
				} //switch $transferts_choix_lieu_opac			
			} 
			// chercher le premier (par ordre de rang, donc de date de début de résa, non validé 
			$rqt = 	"SELECT id_resa, resa_idempr,resa_loc_retrait 
					FROM resa $from
					WHERE resa_idnotice='".$this->id_notice."' 
						AND resa_idbulletin='".$this->id_bulletin."' 
						AND resa_cb='' 
						AND resa_date_fin='0000-00-00' 
						$where
					ORDER BY resa_date ";
					
			/*
			if($pmb_location_reservation) {
				$rqt = "SELECT resa_idempr FROM resa ,empr WHERE resa_idnotice='".$this->id_notice."' AND resa_idbulletin='".$this->id_bulletin."' and resa_idempr=id_empr
				and empr_location in (select resa_loc from resa_loc where resa_emprloc=$empr_location) ORDER BY resa_date";
			} else {
				$rqt = "SELECT resa_idempr FROM resa WHERE resa_idnotice='".$this->id_notice."' AND resa_idbulletin='".$this->id_bulletin."' ORDER BY resa_date";
			}
			*/
			$result = mysql_query($rqt, $dbh);
			while(($resa=mysql_fetch_object($result))) {
				if($resa->resa_idempr == $this->id_empr) break;
				$rank++;
			}
			$this->resa_rank=$rank;
			//print $query ."<br />".$rank."<br />";

		
			// rien de spécial
			return  0;
		}
		
		
		
	
	} # fin de déclaration classe reservation

} # fin de définition
