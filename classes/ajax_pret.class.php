<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax_pret.class.php,v 1.23.2.1 2011-06-09 12:52:46 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$class_path/emprunteur.class.php");
require_once("$include_path/ajax.inc.php");
require_once("$class_path/audit.class.php");
require_once("$class_path/serial_display.class.php");
require_once("$class_path/quotas.class.php");
require_once("$class_path/comptes.class.php");
require_once("$class_path/mono_display.class.php");
require_once($include_path."/parser.inc.php");
require_once("$base_path/circ/pret_func.inc.php");
require_once($include_path."/expl_info.inc.php");

/*
 Pour effectuer un pret:
 // Appel de la class pret:
 $pret = new do_pret();
 // Fonction qui effectue le pret temporaire si pas d'erreur 
$status_xml = $pret->check_pieges($cb_empr, $id_empr,$cb_doc, $id_expl,0);
// Fonction qu effectue le pret définitif
confirm_pret($id_empr, $id_expl); 
 
 
 Fonction check_pieges
 		Effectue le pret temporaire d'un document à un emprunteur
 input:	
 		$cb_empr Cb de l'emprunteur ou ''
 		$id_empr id de l'emprunteur ou 0
 		$cb_doc	Cb du document ou ''
 		$id_expl Id du document ou 0
 		$forcage: En cas de piege forcable, ce parametre permet de forcer le numero du piège
 				retourné dans le paramères forcage.
 				Mettre 0 par défaut
 output:
 		dans un format xml:
 		status 
 				0 : pas d'erreur, le pret temporaire est effectué
 				-1 Erreur non forcable. Voir message d'erreur (error_message)
 				1 Erreur forcable. voir le numéro du piège  (forcage) et message d'erreur (error_message)
 		forcage
 				Si status à 1, c'est le numéro du piège qui ne passe pas. Voir message d'erreur (error_message)
 				Pour effectuer le forcage de ce piège, il faut rapeller la fonction check_pieges avec $forcage à cette valeur
 		error_message
 				Message de l'erreur 
 		id_empr
 		empr_cb
 		id_expl
 		cb_expl
 		expl_notice
 		libelle:
 				Titre du document
 		tdoc_libelle:
 				Support
 */


class do_pret {
	var $id_empr;	
	var $empr_cb;
	var $id_expl;
	var $cb_expl;
	var $msg_finance_pret_force_pret;
	var $msg_293;
	var $msg_652;
	var $msg_294;
	var $tdoc_libelle;
	var $libelle;
	var $error_message;
	var $forcage;
	var $status;
	var $trap_order=array();
	var $trap_func=array();
	var $expl_notice;

	// constructeur
	function do_pret() {
		global $include_path;
		global $msg;
		$this->id_empr = '';
		$this->empr_cb = '';
		
		// Messages utiles au traitement javascript
		$this->msg_finance_pret_force_pret=$msg['finance_pret_force_pret'];
		$this->msg_293=$msg[293];
		$this->msg_652=$msg[652];
		$this->msg_294=$msg[294];
		
		// lecture des fonctions de pièges à exécuter pour faire un pret
		$this->parse_xml_traps($include_path."/trap/trap_pret.xml");
	}

	function parse_xml_traps($filename) {
		
		$fp=fopen($filename,"r") or die("Can't find XML file");
		$xml=fread($fp,filesize($filename));
		fclose($fp);
		$param=_parser_text_no_function_($xml, "TRAPS");
		
		for($i=0; $i<count($param["TRAP"]); $i++) {
			$id=$param["TRAP"][$i]["ID"];
			$this->trap_func[$id]["NAME"]=$param["TRAP"][$i]["FUNCTION"][0]["NAME"];		
			
			// memorise les parametres de la fonction
			for($j=0; $j<count($param["TRAP"][$i]["FUNCTION"][0]["ARG"]); $j++) {			
				$this->trap_func[$id]["ARG"][$j] = $param["TRAP"][$i]["FUNCTION"][0]["ARG"][$j]["value"];
			}			
		}
		//mémoriser l'ordre d'execution des fonctions
		for($i=0; $i<count($param["ORDER"][0]["CHECK"]); $i++) {
			$this->trap_order[$i]=$param["ORDER"][0]["CHECK"][$i]["ID"];
		}	
		
		return 0;
	}
	
	function check_pieges($empr_cb, $id_empr,$cb_expl, $id_expl,$forcage)
	{
		$this->id_empr = $id_empr;
		$this->empr_cb = $empr_cb;	
		$this->id_expl = $id_expl;
		$this->cb_expl = $cb_expl;	
		$this->forcage = 0;	
			
		//Ordre d'execution des fonctions
		for($i=0; $i<count($this->trap_order); $i++) {
			$id=$this->trap_order[$i];
			// S'il n'y a pas de forcage, on check tous les pièges
			if(($forcage < $i) || ($id==1) || ($id==2)  )	{
				// Le test est à faire
									
				$p=$this->trap_func[$id]["ARG"];
				// Construction du code de l'appel à la fonction
				$cmd = "\$this->status = \$this->" . $this->trap_func[$id]["NAME"] . "(";
				// ajout des paramètres à l'appel de la fonction
				for($j=0; $j<count($p); $j++) {
					$cmd.= "\$this->"."$p[$j] ";
					if (($j+1) < count($p) ) {
						$cmd.= ", ";
					}
				}
				// Fin du code de l'appel de la fonction 				
				$cmd.= ");";		
				// Execution de la fonction de piège		
				$status=0;
				$exec_stat = eval ($cmd);	
					
				if($this->status!=0) {
					$this->forcage =$i;
					break;
				}				
			}				
		}
		if($this->status==0) {		
			//Effectuer le pret (temporaire si issu de RFID)
			$this->add_pret($this->id_empr, $this->id_expl, $this->cb_expl);
		}	
		$array[0]=$this;
		$buf_xml = array2xml($array);				
		return $buf_xml;
	}
	function mode1_check_pieges($empr_cb, $id_empr,$cb_expl, $id_expl,$forcage)
	{
		$this->id_empr = $id_empr;
		$this->empr_cb = $empr_cb;	
		$this->id_expl = $id_expl;
		$this->cb_expl = $cb_expl;	
		$this->forcage = 0;	
			
		//Ordre d'execution des fonctions
		for($i=0; $i<count($this->trap_order); $i++) {
			$id=$this->trap_order[$i];
			// S'il n'y a pas de forcage, on check tous les pièges
			if(($forcage < $i) || ($id==1) || ($id==2)  )	{
				// Le test est à faire
									
				$p=$this->trap_func[$id]["ARG"];
				// Construction du code de l'appel à la fonction
				$cmd = "\$this->status = \$this->" . $this->trap_func[$id]["NAME"] . "(";
				// ajout des paramètres à l'appel de la fonction
				for($j=0; $j<count($p); $j++) {
					$cmd.= "\$this->"."$p[$j] ";
					if (($j+1) < count($p) ) {
						$cmd.= ", ";
					}
				}
				// Fin du code de l'appel de la fonction 				
				$cmd.= ");";		
				// Execution de la fonction de piège		
				$status=0;
				$exec_stat = eval ($cmd);	
					
				if($this->status!=0) {
					$this->forcage =$i;
					break;
				}				
			}				
		}
		if($this->status==0) {		
			//Effectuer le pret (temporaire si issu de RFID)
			$this->add_pret($this->id_empr, $this->id_expl, $this->cb_expl);
		}	
		
		$return_val["error_message"]=$this->error_message;
		$return_val["forcage"]=$this->forcage;
		$return_val["status"]=$this->status;
		//$array[0]=$this;
		//$buf_xml = array2xml($array);				
		return $return_val;
	}
	function mode1_get_info_expl( $cb_expl) {
		global $msg;
		global $dbh;
		$this->cb_expl = $cb_expl;
		if ($cb_expl ) {
			$query = "select * from exemplaires  left join docs_type on exemplaires.expl_typdoc=docs_type.idtyp_doc where expl_cb='$cb_expl' ";		
			$result = mysql_query($query, $dbh);
			if (($r= mysql_fetch_array($result))) {
				$this->error_message="";	
				// empr ok	
				$this->id_expl = $r['expl_id'];
				
				$this->tdoc_libelle = $r['tdoc_libelle'];
				if($r['expl_nbparts']>1)
					$this->tdoc_libelle.=" (".$r['expl_nbparts'].")";
				$this->expl_notice = $r['expl_notice'];
				if ($this->expl_notice) {
					$notice = new mono_display($this->expl_notice, 0);
					$this->libelle = $notice->header_texte;
				} else {
					$bulletin = new bulletinage_display( $r['expl_bulletin']);
					$this->libelle = $bulletin->display ;
					$this->expl_notice = $r['expl_bulletin'];
				}
				$pos=strpos($this->libelle,'<a');
				if($pos) $this->libelle = substr($this->libelle,0,strpos($this->libelle,'<a'));		
								
			} else {
				$this->error_message=$msg[367];
			}
		} else {
			$this->error_message=$msg[367];
		}
		
		$return_val["error_message"]=$this->error_message;
		$return_val["expl_id"]=$this->id_expl;
		$return_val["cb_expl"]=$cb_expl;
		$return_val["tdoc_libelle"]=$this->tdoc_libelle;
		$return_val["expl_notice"]=$this->expl_notice;
		$return_val["libelle"]=$this->libelle;
		$return_val["expl_comment"]=$this->expl_comment;
		//$array[0]=$this;
		//$buf_xml = array2xml($array);				
		return $return_val;
	}

	function check_emprunteur_exist( $id_empr,$empr_cb){
		global $msg;
		global $dbh;
		if ($empr_cb || $id_empr) {
			if($id_empr)
				$query = "select id_empr, empr_cb from empr where id_empr='$id_empr' ";
			else 
				$query = "select id_empr, empr_cb from empr where empr_cb='$empr_cb' ";			
			$result = mysql_query($query, $dbh);
			if (($r=mysql_fetch_array($result)))
			{
				$this->error_message="";	
				// check empr ok	
				$this->id_empr = $r['id_empr'];
				$this->empr_cb = $r['empr_cb'];
				return 0;
			}
		}
		$this->error_message=$msg[388];
		return -1;
	}
	
	function check_document_exist($id_expl, $cb_expl) {
		global $msg;
		global $dbh;
		if ($cb_expl || $id_expl) {
			if($id_expl)
				$query = "select * from exemplaires  left join docs_type on exemplaires.expl_typdoc=docs_type.idtyp_doc where expl_id='$id_expl'";
			else 
				$query = "select * from exemplaires  left join docs_type on exemplaires.expl_typdoc=docs_type.idtyp_doc where expl_cb='$cb_expl' ";		
			$result = mysql_query($query, $dbh);
			if (($r= mysql_fetch_array($result))) {
				$this->error_message="";	
				// empr ok	
				$this->id_expl = $r['expl_id'];
				$this->cb_expl = $r['expl_cb'];
				$this->tdoc_libelle = $r['tdoc_libelle'];
				if($r['expl_nbparts']>1)
					$this->tdoc_libelle.=" (".$r['expl_nbparts'].")";
				$this->expl_notice = $r['expl_notice'];
				
				if ($this->expl_notice) {
					$notice = new mono_display($this->expl_notice, 0);
					$this->libelle = $notice->header_texte;
				} else {
					$bulletin = new bulletinage_display( $r['expl_bulletin']);
					$this->libelle = $bulletin->display ;
					$this->expl_notice = $r['expl_bulletin'];
				}
				$pos=strpos($this->libelle,'<a');
				if($pos) $this->libelle = substr($this->libelle,0,strpos($this->libelle,'<a'));		
				
				return 0;
			}
		}
		$this->error_message=$msg[367];
		return -1;
	}

	function get_info_expl( $cb_expl) {
		global $msg;
		global $dbh;
		if ($cb_expl ) {
			$query = "select * from exemplaires  left join docs_type on exemplaires.expl_typdoc=docs_type.idtyp_doc where expl_cb='$cb_expl' ";		
			$result = mysql_query($query, $dbh);
			if (($r= mysql_fetch_array($result))) {
				$this->error_message="";	
				// empr ok	
				$this->id_expl = $r['expl_id'];
				$this->cb_expl = $r['expl_cb'];
				$this->tdoc_libelle = $r['tdoc_libelle'];
				if($r['expl_nbparts']>1)
					$this->tdoc_libelle.=" (".$r['expl_nbparts'].")";
				$this->expl_notice = $r['expl_notice'];
				if ($this->expl_notice) {
					$notice = new mono_display($this->expl_notice, 0);
					$this->libelle = $notice->header_texte;
				} else {
					$bulletin = new bulletinage_display( $r['expl_bulletin']);
					$this->libelle = $bulletin->display ;
					$this->expl_notice = $r['expl_bulletin'];
				}
				$pos=strpos($this->libelle,'<a');
				if($pos) $this->libelle = substr($this->libelle,0,strpos($this->libelle,'<a'));		
								
			} else {
				$this->error_message=$msg[367];
			}
		} else {
			$this->error_message=$msg[367];
		}
		$array[0]=$this;
		$buf_xml = array2xml($array);				
		return $buf_xml;
	}

	function check_emprunteur_adhesion_false($id_empr) {
		global $msg;
		global $pmb_pret_adhesion_depassee;
		$empr_temp = new emprunteur($id_empr, '', FALSE, 0);
		$empr_date_depassee = $empr_temp->adhesion_depassee();
		//Si l'adhésion de l'emprunteur dépassée
		if (!($pmb_pret_adhesion_depassee == 0 && $empr_date_depassee)) {
			$this->error_message="";
			return 0;
		}
		$this->error_message=$msg['pret_impossible_adhesion'];
		return -1;
	}
	
	function check_document_has_note($id_expl) {
		global $msg;
		global $dbh;
		$query = "select expl_note, expl_comment from exemplaires where expl_id=$id_expl";
		$result = mysql_query($query, $dbh);
		if (($expl = mysql_fetch_array($result))) {
			// L'exemplaire a une note
			if ($expl['expl_note']) {
				$this->error_message=$expl['expl_note'];
				return 1;
			} else if ($expl['expl_comment']) {
				$this->expl_comment=$expl['expl_comment'];
				return 0;
			}
		} else {
			// exemplaire inconnu
			$this->error_message=$msg[367];
			return -1;
		}
		$this->error_message="";
		return 0;
	}

	function check_document_has_todo($id_expl) {
		global $msg;
		global $dbh,$deflt_docs_location;
		$query = "select expl_retloc from exemplaires where expl_id=$id_expl and expl_retloc='".$deflt_docs_location."' ";
		$result = mysql_query($query, $dbh);
		if (mysql_num_rows($result)) {
			// L'exemplaire a un traitement non effectuer. interdire le prêt
			$this->error_message=$msg["circ_pret_piege_expl_todo"];
			return 1;
		}	
		return 0;
	}	
	
	function check_document_pretable($id_expl) {
		global $msg;
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
				if (!$expl['note'])
					$this->error_message = $expl['statut'];
				else
					$this->error_message = $expl['note'] . " / " . $expl['statut'];
				return 1;	
			}
		} else {
			// exemplaire inconnu
			$this->error_message=$msg[367];
			return -1;
		}
		$this->error_message=$expl['expl_comment'];
		return 0;
	}	
	
	function check_document_already_loaned($id_empr, $id_expl) {
		global $msg;
		global $dbh;
		$query = "select pret_idempr from pret where pret_idexpl=$id_expl limit 1";
		$result = mysql_query($query, $dbh);
		if (@ mysql_num_rows($result)) {
			// l'exemplaire est déjà en prêt
			$empr = mysql_result($result, '0', 'pret_idempr');
			// l'emprunteur n'est l'emprunteur actuel
			if ($empr == $id_empr) {
				$this->error_message=$msg[386];
				return -1;
			}
		}
		$this->error_message="";
		return 0;
	}		

	function check_document_already_borrowed($id_empr, $id_expl) {
		global $msg;
		global $dbh;
		$query = "select pret_idempr from pret where pret_idexpl=$id_expl limit 1";
		$result = mysql_query($query, $dbh);
		if (@ mysql_num_rows($result)) {
			// l'exemplaire est déjà en prêt
			$empr = mysql_result($result, '0', 'pret_idempr');
			// l'emprunteur n'est l'emprunteur actuel
			if ($empr != $id_empr) {
				$this->error_message=$msg[387];
				return -1;
			}
		}
		$this->error_message="";
		return 0;
	}		
	
	
	function check_document_has_resa_false($id_empr, $id_expl) {
		global $msg;
		global $dbh;
		global $pmb_resa_planning;
		// on tente de récupérer les infos exemplaire utiles
		$query = "select e.expl_cb as cb, e.expl_id as id, s.pret_flag as pretable, e.expl_notice as notice, e.expl_bulletin as bulletin, e.expl_note as note, expl_comment, s.statut_libelle as statut";
		$query .= " from exemplaires e, docs_statut s";
		$query .= " where e.expl_id=$id_expl";
		$query .= " and s.idstatut=e.expl_statut";
		$query .= " limit 1";
		$result = mysql_query($query, $dbh);
	
		// exemplaire inconnu
		if (!mysql_num_rows($result)) {
			$this->error_message=$msg[367];
			return -1;
		}
		$expl = mysql_fetch_object($result);
		$retour->expl_cb = $expl->cb;
		// on checke si l'exemplaire a une réservation
		$query = "select resa_idempr as empr, id_resa, resa_cb, concat(ifnull(concat(empr_nom,' '),''),empr_prenom) as nom_prenom, empr_cb from resa left join empr on resa_idempr=id_empr where resa_idnotice='$expl->notice' and resa_idbulletin='$expl->bulletin' order by resa_date limit 1";
		$result = mysql_query($query, $dbh);
		if (mysql_num_rows($result)) {
			$reservataire = mysql_result($result, 0, 'empr');
			$resa_cb = mysql_result($result, 0, 'resa_cb');
			
			if ($reservataire != $id_empr) {
				if ($expl->cb == $resa_cb) { // réservé (validé) pour un autre lecteur
					$this->error_message=$msg[383];
					return 1;
				}	
			}
		}
		// cas des réservations planifiées		
		if ($pmb_resa_planning) {
			// On compte les réservations planifiées sur ce document à des dates ultérieures
			$q = "select resa_idempr as empr, id_resa, concat(ifnull(concat(empr_nom,' '),''),empr_prenom) as nom_prenom ";
			$q .= "from resa_planning left join empr on resa_idempr=id_empr ";
			$q .= "where resa_idnotice = '" . $expl->notice . "' ";
			$q .= "and resa_date_debut > curdate() ";
			$q .= "order by resa_date_debut ";
			$r = mysql_query($q, $dbh);
			$nb_resa = mysql_num_rows($r);
	
			// On compte les exemplaires disponibles
			$q = "select count(1) ";
			$q .= "from exemplaires left join pret on expl_notice = pret_idexpl ";
			$q .= "and pret_idexpl is null ";
			$q .= "where expl_notice = '" . $expl->notice . "' ";
			$r = mysql_query($q, $dbh);
			$nb_dispo = mysql_result($r, 0, 0);
	
			if (($nb_dispo - $nb_resa) <= 0) { // réservé (validé) pour un autre lecteur
				$this->error_message=$msg['resa_planning_encours'];
				return 1;
			}	
		}	
		$this->error_message="";
		return 0;
	}
		
	function check_quotas($id_empr, $id_expl) {
		global $msg;
		global $pmb_quotas_avances;	
		if ($pmb_quotas_avances) {
			//Initialisation des quotas pour nombre de documents prêtables
			$qt = new quota("LEND_NMBR_QUOTA");
			//Tableau de passage des paramètres
			$struct["READER"] = $id_empr;
			$struct["EXPL"] = $id_expl;
			//Test du quota pour l'exemplaire et l'emprunteur
			if ($qt->check_quota($struct)) {
				//Si erreur, récupération du message et peut-on forcer ou non ?
				$this->error_message= $qt->error_message;
				if( $qt->force) {
					return 1;
				} 
				return -1;	
			}
		}
		$this->error_message="";
		return 0;
	}
	
	function del_pret($id_expl) {
		// le lien MySQL
		global $dbh;
		global $msg;
		// récupérer la stat insérée pour la supprimer !
		$query = "select pret_arc_id ,pret_temp from pret where pret_idexpl = '" . $id_expl . "' ";
		$result = mysql_query($query, $dbh);
		$stat_id = mysql_fetch_object($result);
		if($stat_id->pret_temp ) {
			$result = mysql_query("delete from pret_archive where arc_id='" . $stat_id->pret_arc_id . "' ", $dbh);
			audit::delete_audit (AUDIT_PRET, $stat_id->pret_arc_id) ;
		
			// supprimer le prêt annulé
			$query = "delete from pret where pret_idexpl = '" . $id_expl . "' ";
			$result = mysql_query($query, $dbh);
			
		}	
		$array[0]=$this;
		$buf_xml = array2xml($array);				
		return $buf_xml;
	}
	
	function add_pret($id_empr, $id_expl, $cb_expl) {
		// le lien MySQL
		global $dbh;
		global $msg;
		global $_SERVER;
		// insérer le prêt sans stat et gestion financière
		$query = "INSERT INTO pret SET ";
		$query .= "pret_idempr = '" . $id_empr . "', ";
		$query .= "pret_idexpl = '" . $id_expl . "', ";
		$query .= "pret_date   = sysdate(), ";
		$query .= "pret_retour = 'today()', ";
		$query .= "retour_initial = 'today()', ";
		$query .= "pret_temp = '".$_SERVER['REMOTE_ADDR']."'";
		$result = @ mysql_query($query, $dbh) or die("can't INSERT into pret" . $query);
		
		$query = "delete from resa_ranger ";
		$query .= "where resa_cb='".$cb_expl."'";
		$result = @ mysql_query($query, $dbh) or die("can't delete cb_doc in resa_ranger : ".$query);	
	
	}
	
	function resa_pret_gestion($id_empr, $id_expl,$stat_id=0) {
		global $msg;
		global $dbh;
		global $pmb_resa_planning;
		$this->error_message="resa_pret_gestion ";
		// on tente de récupérer les infos exemplaire utiles
		$query = "select e.expl_cb as cb, e.expl_id as id, s.pret_flag as pretable, e.expl_notice as notice, e.expl_bulletin as bulletin, e.expl_note as note, expl_comment, s.statut_libelle as statut";
		$query .= " from exemplaires e, docs_statut s";
		$query .= " where e.expl_id=$id_expl";
		$query .= " and s.idstatut=e.expl_statut";
		$query .= " limit 1";
		$result = mysql_query($query, $dbh);
	
		// exemplaire inconnu
		if (!mysql_num_rows($result)) {
			$this->error_message=$msg[367];
			return -1;
		}
		$expl = mysql_fetch_object($result);
		$retour->expl_cb = $expl->cb;
		// on checke si l'exemplaire a une réservation
		$query = "select resa_idempr as empr, id_resa, resa_cb, concat(ifnull(concat(empr_nom,' '),''),empr_prenom) as nom_prenom, empr_cb from resa left join empr on resa_idempr=id_empr where resa_idnotice='$expl->notice' and resa_idbulletin='$expl->bulletin' order by resa_date limit 1";
		$result = mysql_query($query, $dbh);
		if (mysql_num_rows($result)) {
			//$reservataire = mysql_result($result, 0, 'empr');
			//$resa_cb = mysql_result($result, 0, 'resa_cb');
			// archivage resa
			$rqt_arch = "UPDATE resa_archive, resa SET resarc_pretee = 1, resarc_arcpretid = $stat_id WHERE id_resa = '".mysql_result($result, 0, 'id_resa')."' AND resa_arc = resarc_id ";	
			mysql_query($rqt_arch, $dbh);
			$this->del_resa($id_empr, $expl->notice, $expl->bulletin, $expl->cb);			
		}
	}	
	
	function del_resa($id_empr, $id_notice, $id_bulletin, $cb_encours_de_pret) {	
		global $dbh;
		$this->error_message.="del_resa ";
		if (!$id_empr || (!$id_notice && !$id_bulletin))
			return FALSE;
	
		if (!$id_notice)
			$id_notice = 0;
		if (!$id_bulletin)
			$id_bulletin = 0;
		$rqt = "select resa_cb, id_resa from resa where resa_idnotice='".$id_notice."' and resa_idbulletin='".$id_bulletin."'  and resa_idempr='".$id_empr."' ";
		$res = mysql_query($rqt, $dbh);
		$obj = mysql_fetch_object($res);
		$cb_recup = $obj->resa_cb;
		$id_resa = $obj->id_resa;
	
		// suppression
		$rqt = "delete from resa where id_resa='".$id_resa."' ";
		$res = mysql_query($rqt, $dbh);
		
		// si on delete une resa à partir d'un prêt, on invalide la résa qui était validée avec le cb, mais on ne change pas les dates, ça sera fait par affect_cb
		$rqt_invalide_resa = "update resa set resa_cb='' where resa_cb='".$cb_encours_de_pret."' " ;  
		$res = mysql_query ($rqt_invalide_resa, $dbh) ;
													
		// réaffectation du doc éventuellement
		if ($cb_recup != $cb_encours_de_pret) {
			// les cb sont différents
			if (!verif_cb_utilise($cb_recup)) {
				// le cb qui était affecté à la résa qu'on vient de supprimer n'est pas utilisé
				// on va affecter le cb_récupéré à une resa non validée
				$res_affectation = affecte_cb($cb_recup) ;
				if (!$res_affectation && $cb_recup) {
					// cb non réaffecté, il faut transférer les infos de la résa dans la table des docs à ranger
					$rqt = "insert into resa_ranger (resa_cb) values ('".$cb_recup."') ";
					$res = mysql_query($rqt, $dbh);
					}
				}
			}
		// Au cas où il reste des résa invalidées par resa_cb, on leur colle les dates comme il faut...
		$rqt_invalide_resa = "update resa set resa_date_debut='0000-00-00', resa_date_fin='0000-00-00' where resa_cb='' " ;  
		$res = mysql_query ($rqt_invalide_resa, $dbh) ;
		return TRUE;
	}
	
	function confirm_pret($id_empr, $id_expl) {
		// le lien MySQL
		global $dbh;
		global $msg;
		global $pmb_quotas_avances, $pmb_utiliser_calendrier;
		global $pmb_gestion_financiere, $pmb_gestion_tarif_prets;
		global $include_path, $lang;
		global $deflt2docs_location;
		global $pmb_pret_date_retour_adhesion_depassee;
		
		//supprimer le pret temporaire
		$query = "delete from pret where pret_idexpl = '" . $id_expl . "' ";
		$result = mysql_query($query, $dbh);
		
		/* on prépare la date de début*/
		$pret_date = today();
		/* on cherche la durée du prêt */
		if ($pmb_quotas_avances) {
			//Initialisation de la classe
			$qt = new quota("LEND_TIME_QUOTA");
			$struct["READER"] = $id_empr;
			$struct["EXPL"] = $id_expl;
			$duree_pret = $qt->get_quota_value($struct);
			if ($duree_pret == -1) $duree_pret = 0;
		} else {
			$query = "SELECT duree_pret";
			$query .= " FROM exemplaires, docs_type";
			$query .= " WHERE expl_id='" . $id_expl;
			$query .= "' and idtyp_doc=expl_typdoc LIMIT 1";
			$result = @ mysql_query($query, $dbh) or die("can't SELECT exemplaires " . $query);
			$expl_properties = mysql_fetch_object($result);
			$duree_pret = $expl_properties->duree_pret;
		}			

		// calculer la date de retour prévue, tenir compte de la date de fin d'adhésion
		if (!$duree_pret) $duree_pret="0" ; 
		if($pmb_pret_date_retour_adhesion_depassee) {
			$rqt_date = "select empr_date_expiration,if(empr_date_expiration>date_add('".$pret_date."', INTERVAL '$duree_pret' DAY),0,1) as pret_depasse_adhes, date_add('".$pret_date."', INTERVAL '$duree_pret' DAY) as date_retour from empr where id_empr='".$id_empr."'";
		} else {	
			$rqt_date = "select empr_date_expiration,if(empr_date_expiration>date_add('".$pret_date."', INTERVAL '$duree_pret' DAY),0,1) as pret_depasse_adhes, if(empr_date_expiration>date_add('".$pret_date."', INTERVAL '$duree_pret' DAY),date_add('".$pret_date."', INTERVAL '$duree_pret' DAY),empr_date_expiration) as date_retour from empr where id_empr='".$id_empr."'";
		}
		$resultatdate = mysql_query($rqt_date) or die(mysql_error()."<br /><br />$rqt_date<br /><br />");
		$res = mysql_fetch_object($resultatdate) ;
		$date_retour = $res->date_retour ;
		$pret_depasse_adhes = $res->pret_depasse_adhes ;
		$empr_date_expiration= $res->empr_date_expiration;
		
		if ($pmb_utiliser_calendrier) {
			if (($pret_depasse_adhes==0) || $pmb_pret_date_retour_adhesion_depassee) {
				$rqt_date = "select date_ouverture from ouvertures where ouvert=1 and to_days(date_ouverture)>=to_days('$date_retour') and num_location=$deflt2docs_location order by date_ouverture ";
				$resultatdate=mysql_query($rqt_date);
				$res=@mysql_fetch_object($resultatdate) ;
				if ($res->date_ouverture) $date_retour=$res->date_ouverture ;
			} else {
				$rqt_date = "select date_ouverture from ouvertures where date_ouverture>=sysdate() and ouvert=1 and to_days(date_ouverture)<=to_days('$date_retour') and num_location=$deflt2docs_location order by date_ouverture DESC";
				$resultatdate=mysql_query($rqt_date);
				$res=@mysql_fetch_object($resultatdate) ;
				if ($res->date_ouverture) $date_retour=$res->date_ouverture ;
			}
			// Si la date_retour, calculée ci-dessus d'après le calendrier, dépasse l'adhésion, alors que c'est interdit,
			// la date de retour doit etre le dernier jour ouvert
			if(!$pmb_pret_date_retour_adhesion_depassee){
				$rqt_date = "SELECT DATEDIFF('$empr_date_expiration','$date_retour')as diff";
				$resultatdate=mysql_query($rqt_date);
				$res=@mysql_fetch_object($resultatdate) ;
				if ($res->diff<0) {
					$rqt_date = "select date_ouverture from ouvertures where date_ouverture>=sysdate() and ouvert=1 and to_days(date_ouverture)<=to_days('$empr_date_expiration') and num_location=$deflt2docs_location order by date_ouverture DESC";
					$resultatdate=mysql_query($rqt_date);
					$res=@mysql_fetch_object($resultatdate) ;
					if ($res->date_ouverture) $date_retour=$res->date_ouverture ;									
				}
			}				
		}
	
		// insérer le prêt 
		$query = "INSERT INTO pret SET ";
		$query .= "pret_idempr = '" . $id_empr . "', ";
		$query .= "pret_idexpl = '" . $id_expl . "', ";
		$query .= "pret_date   = sysdate(), ";
		$query .= "pret_retour = '$date_retour', ";
		$query .= "retour_initial = '$date_retour' ";
		$result = @ mysql_query($query, $dbh) or die("can't INSERT into pret" . $query);
	
		// insérer la trace en stat, récupérer l'id et le mettre dans la table des prêts pour la maj ultérieure
		$stat_avant_pret = pret_construit_infos_stat($id_expl);
		$stat_id = stat_stuff($stat_avant_pret);
		$query = "update pret SET pret_arc_id='$stat_id' where ";
		$query .= "pret_idempr = '" . $id_empr . "' and ";
		$query .= "pret_idexpl = '" . $id_expl . "' ";
		$result = @ mysql_query($query, $dbh) or die("can't update pret for stats " . $query);
	
		$query = "update exemplaires SET ";
		$query .= "last_loan_date = sysdate() ";
		$query .= "where expl_id= '" . $id_expl . "' ";
		$result = @ mysql_query($query, $dbh) or die("can't update last_loan_date in exemplaires : " . $query);
	
		$query = "update empr SET ";
		$query .= "last_loan_date = sysdate() ";
		$query .= "where id_empr= '" . $id_empr . "' ";
		$result = @ mysql_query($query, $dbh) or die("can't update last_loan_date in empr : " . $query);
	
		//Débit du compte lecteur si nécessaire
		if (($pmb_gestion_financiere) && ($pmb_gestion_tarif_prets)) {
			$tarif_pret = 0;
			switch ($pmb_gestion_tarif_prets) {
				case 1 :
					//Gestion simple
					$query = "SELECT tarif_pret";
					$query .= " FROM exemplaires, docs_type";
					$query .= " WHERE expl_id='" . $id_expl;
					$query .= "' and idtyp_doc=expl_typdoc LIMIT 1";
	
					$result = @ mysql_query($query, $dbh) or die("can't SELECT exemplaires " . $query);
					$expl_tarif = mysql_fetch_object($result);
					$tarif_pret = $expl_tarif->tarif_pret;
					break;
				case 2 :
					//Gestion avancée
					//Initialisation Quotas
					global $_parsed_quotas_;
					$_parsed_quotas_ = false;
					$qt_tarif = new quota("COST_LEND_QUOTA", "$include_path/quotas/own/$lang/finances.xml");
					$struct["READER"] = $id_empr;
					$struct["EXPL"] = $id_expl;
					$tarif_pret = $qt_tarif->get_quota_value($struct);
					break;
			}
			$tarif_pret = $tarif_pret * 1;
			if ($tarif_pret) {
				$compte_id = comptes :: get_compte_id_from_empr($id_empr, 3);
				if ($compte_id) {
					$cpte = new comptes($compte_id);
					$cpte->record_transaction("", abs($tarif_pret), -1, sprintf($msg["finance_pret_expl"], $id_expl), 0);
				}
			}
		}
		$this->resa_pret_gestion($id_empr, $id_expl, $stat_id);	
		$array[0]['statut']=1;
		$buf_xml = array2xml($array);				
		return $buf_xml;
	}

// Fin class		
}

?>