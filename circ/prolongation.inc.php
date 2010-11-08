<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: prolongation.inc.php,v 1.25 2010-07-06 10:07:39 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// script de prolongation d'un prêt

/* on dispose en principe de :
$form_cb -> code barre de l'exemplaire concerné
$cb_doc -> code barre de l'exemplaire
$date_retour -> la nouvelle date de retour (format MySQL)
$date_retour_lib -> nouvelle date de retour au format dd mm yyyy
*/  

require_once("$class_path/pret.class.php");
require_once("$class_path/serial_display.class.php");
require_once("$class_path/serials.class.php");

function prolonger($id_prolong) {
	global $id_empr,$date_retour, $date_retour_lib, $form_cb, $cb_doc, $confirm;
	global $dbh, $alert_sound_list, $msg;
	global $pmb_pret_restriction_prolongation, $pmb_pret_nombre_prolongation, $force_prolongation, $bloc_prolongation;
	
		$prolongation=TRUE;	
	
		//Récupération des ids de notices et de bulletin par rapport à l'id de l'exemplaire placé en paramètre 	
		$query = "select expl_cb, expl_notice, expl_bulletin from exemplaires where expl_id='$id_prolong' limit 1";
		$result = mysql_query($query, $dbh);
	
		if(mysql_num_rows($result)) {
			$retour = mysql_fetch_object($result);
			
			$cb_doc=$retour->expl_cb;
			//Récupération du nombre de prolongations effectuées pour l'exemplaire
			$query_prolong = "select cpt_prolongation, retour_initial,  pret_date from pret where pret_idexpl=".$id_prolong." limit 1";
			$result_prolong = mysql_query($query_prolong, $dbh);
			$data = mysql_fetch_array($result_prolong);
			$cpt_prolongation = $data['cpt_prolongation']; 
			$retour_initial =  $data['retour_initial'];
			$pret_date =  $data['pret_date'];
			$pret_day=split(" ",$pret_date);
			if($pret_day[0] != today())	$cpt_prolongation++;			
			if ($force_prolongation!=1) {
				//Rechercher s'il subsiste une réservation sur le bulletin ou la notice
				$query_resa = "select count(1) from resa where resa_idnotice=".$retour->expl_notice." and resa_idbulletin=".$retour->expl_bulletin;
				$result_resa = mysql_query($query_resa, $dbh);
				$has_resa = mysql_result($result_resa,0,0);
			
				if (!$has_resa) {
					if ($pmb_pret_restriction_prolongation>0) {
						//limitation simple du prêt
						if($pmb_pret_restriction_prolongation==1) {
							$pret_nombre_prolongation=$pmb_pret_nombre_prolongation;
							$forcage_prolongation=1;
						} else {
							//Initialisation des quotas pour nombre de prolongations
							$qt = new quota("PROLONG_NMBR_QUOTA");
							//Tableau de passage des paramètres
							$struct["READER"] = $id_empr;
							$struct["EXPL"] = $id_prolong;
				
							$pret_nombre_prolongation=$qt -> get_quota_value($struct);		
				
							$forcage_prolongation=$qt -> get_force_value($struct);
						}
						if($cpt_prolongation>$pret_nombre_prolongation) {
							$prolongation=FALSE;
						}
					}	
				} else {
					$prolongation=FALSE;
					$forcage_prolongation=1;
				}
			}						
			//est-ce qu'on a le droit de prolonger
			if ($prolongation==TRUE) {
				$query = "update pret set cpt_prolongation='".$cpt_prolongation."' where pret_idexpl=".$id_prolong." limit 1";
				mysql_query($query, $dbh);
						
				// mettre ici la routine de prolongation
				$pretProlong = new pret ($id_empr, $id_prolong, $form_cb, "", "");
				$resultProlongation = $pretProlong->prolongation ($date_retour);
				$erreur_affichage="";
			} else {
				if ($retour->expl_notice!=0) {
					$q= new notice($retour->expl_notice);
					$nom=$q->tit1;
				} elseif ($retour->expl_bulletin!=0) {		
					$query = "select bulletin_notice, bulletin_numero,date_date from bulletins where bulletin_id =".$retour->expl_bulletin;
					$res = mysql_query($query, $dbh);	
					$bull = mysql_fetch_object($res);
					$q= new serial($bull->bulletin_notice);
					$nom=$q->tit1.". ".$bull->bulletin_numero." (".formatdate($bull->date_date).")";
				}
				if($has_resa) {
					if ($bloc_prolongation==0) {
						$erreur_affichage="<table border='0' cellpadding='1' height='40' border='1'><tr><td width='33'><span><img src='./images/quest.png' /></span></td>
								<td width='100%'>";
						$erreur_affichage.=$msg["document_prolong"]." '$nom' : <span class='erreur'>${msg[393]}</span>";
						$erreur_affichage.="<input type='button' class='bouton' value='${msg[76]}' onClick=\"document.location='./circ.php?categ=pret&form_cb=".rawurlencode($form_cb)."'\">";
						$erreur_affichage.="&nbsp;<input type='button' class='bouton' value='${msg[pret_plolongation_forcage]}' onClick=\"document.location='circ.php?categ=pret&sub=pret_prolongation&form_cb=".rawurlencode($form_cb)."&cb_doc=$cb_doc&id_doc=".$id_prolong."&date_retour=$date_retour&force_prolongation=$forcage_prolongation'\">";
						$erreur_affichage.="</td></tr></table>";
					} else {
						$erreur_affichage.=$msg["document_prolong"]." '$nom' : <span class='erreur'>${msg[393]}</span><br />";
					}
				} else {
					if ($bloc_prolongation==0) {
						$erreur_affichage = "<hr />
						<div class='row'>
							<div class='colonne10'><img src='./images/error.png' /></div>
							<div class='colonne-suite'>".$msg["document_prolong"]." '$nom' : <span class='erreur'>".$msg[pret_plolongation_refuse]."</span></div>";
						$alert_sound_list[]="critique";
						$erreur_affichage.= "<input type='button' class='bouton' value='${msg[76]}' onClick=\"document.location='./circ.php?categ=pret&id_empr=$id_empr'\" />";
						$erreur_affichage.= "&nbsp;<input type='button' class='bouton' value='${msg[pret_plolongation_forcage]}' onClick=\"document.location='./circ.php?categ=pret&sub=pret_prolongation&form_cb=".rawurlencode($form_cb)."&cb_doc=$cb_doc&id_doc=".$id_prolong."&date_retour=$date_retour&force_prolongation=$forcage_prolongation'\" />";	
						$erreur_affichage.= "</div><br />";
					} else {
						$erreur_affichage.=$msg["document_prolong"]." '$nom' : <span class='erreur'>".$msg[pret_plolongation_refuse]."</span><br />";	
					}
				}			
			}
		}
	return $erreur_affichage; 
}


