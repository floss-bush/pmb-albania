<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: resa_planning.inc.php,v 1.6 2009-05-16 10:52:45 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fichier initialement créé et maintenu en partie gestion.

require_once($base_path.'/includes/resa_func.inc.php'); 
require_once($include_path."/mail.inc.php") ;
require_once($include_path."/divers.inc.php") ;
require_once($include_path."/templates/resa_planning.tpl.php") ;
require_once($class_path."/resa_planning.class.php") ;
require_once($base_path.'/classes/notice.class.php');

if ($opac_resa && $opac_resa_planning) { //resa autorisées dans l'opac et mode planning 

	if ($popup_resa) { // est-on appelé par le popup ? Si oui, pose réservation
		
		if (!$resa_date_debut) {
			
			print resa_planning() ;
			
		} else {
			
			//On vérifie les dates
			$ty = getdate();
			$d = $ty[year].$ty[mon].$ty[mday];
	
			$tresa_date_debut = explode('-', extraitdate($resa_date_debut));
			if (strlen($tresa_date_debut[2])==1) $tresa_date_debut[2] = '0'.$tresa_date_debut[2];
			if (strlen($tresa_date_debut[1])==1) $tresa_date_debut[1] = '0'.$tresa_date_debut[1];
			$resa_date_debut = implode('', $tresa_date_debut);
			
			$tresa_date_fin = explode('-', extraitdate($resa_date_fin));
			if (strlen($tresa_date_fin[2])==1) $tresa_date_fin[2] = '0'.$tresa_date_fin[2];
			if (strlen($tresa_date_fin[1])==1) $tresa_date_fin[1] = '0'.$tresa_date_fin[1];
			$resa_date_fin = implode('', $tresa_date_fin); 	
			
			
			if ( (@checkdate($tresa_date_debut[1], $tresa_date_debut[2], $tresa_date_debut[0])) 
					&& (@checkdate($tresa_date_fin[1], $tresa_date_fin[2], $tresa_date_fin[0])) 
					&& (strlen($resa_date_debut)==8) && (strlen($resa_date_fin)==8) 
					&& ($resa_date_debut >= $d) && ($resa_date_debut < $resa_date_fin) ) {
			
				$r = new resa_planning();
				$r->resa_idempr=$_SESSION['id_empr_session'];
				$r->resa_idnotice=$id_notice;
				$r->resa_date_debut=implode('-', $tresa_date_debut);
				$r->resa_date_fin=implode('-', $tresa_date_fin);;
				$r->save();
				print resa_planning(FALSE);
				
			} else {

				print resa_planning();
				
			}
	
		}
	
	} else {  //Sinon, affichage ou suppression des résas de l'emprunteur
	
		$nb_resa_suppr=0;
		if ($delete) {
			// *** Traitement de la suppression d'une résa planifiée 
			$q = "delete from resa_planning WHERE id_resa='".$id_resa."' ";
			$r = mysql_query($q, $dbh);
			// suppression
			$rqt = "delete from resa where id_resa='".$suppr_id_resa."' ";
			$res = mysql_query ($rqt, $dbh) ;
			$nb_resa_suppr = mysql_affected_rows() ;
		}

	
		print "<h3><span>".$msg['empr_bt_show_resa']."</span></h3>";
		
		$requete3 = "SELECT id_resa, resa_idempr, resa_idnotice, resa_date, resa_date_debut, resa_date_fin, IF(resa_date_fin>=sysdate() or resa_date_fin='0000-00-00',0,1) as perimee, date_format(resa_date_fin, '".$msg["format_date_sql"]."') as aff_date_fin FROM resa_planning WHERE resa_idempr=".$_SESSION["id_empr_session"];
		$result3 = @mysql_query($requete3, $dbh);
		while ($resa = mysql_fetch_array($result3)) {
			$message_null_resa="";
			$id_resa = $resa['id_resa'];
			$resa_idempr = $resa['resa_idempr'];
			$resa_idnotice = $resa['resa_idnotice'];
			$resa_date = $resa['resa_date'];
			$resa_date_debut = $resa['resa_date_debut'];
			$resa_date_fin = $resa['resa_date_fin'];
			if ($resa_idnotice) {
				// affiche la notice correspondant à la réservation
				$requete = "SELECT * FROM notices WHERE notice_id='".$resa_idnotice."' ";
				$res = @mysql_query($requete, $dbh);
				$obj=mysql_fetch_object($res);
				$notice = new notice($obj);
				print pmb_bidi($notice->print_resume(1,$css));
				print pmb_bidi(" &gt; <a href=empr.php?lvl=resa_planning&delete=1&id_resa=".$id_resa.">".$msg['resa_effacer_resa']."</a><br />");
			} 
			
			print "<blockquote><b>".$msg['resa_date_debut']."</b> ".formatdate($resa_date_debut)."&nbsp;<b>".$msg['resa_date_fin']."</b> ".formatdate($resa_date_fin)."&nbsp;" ;
			if ($resa['perimee']) print " ".$msg['resa_overtime']." " ;
				else print " ".$msg['resa_attente_validation']." " ;
			print "</blockquote>" ;
		}
		print pmb_bidi($message_null_resa);
		
	}
}



// fonction de pose de réservation en planning
function resa_planning($deb=TRUE) {

	global $dbh;
	global $msg;
	global $id_notice ;
	global $liens_opac ;
	global $form_resa_dates, $form_resa_ok ;
	global $popup_resa, $opac_max_resa;
	global $resa_date_debut,$resa_date_fin;
	
	print "<h3><span>".$msg['resa_resa_titre_add']."</span></h3>";
	
	if ($deb) {
		// test au cas où tentative de passer une résa hors URL de résa autorisée...
		$requete_resa = "SELECT count(1) FROM resa_planning WHERE resa_idnotice='$id_notice' '";
		$nb_resa_encours = mysql_result(mysql_query($requete_resa,$dbh), 0, 0) ;
		if ($opac_max_resa && $nb_resa_encours>=$opac_max_resa) {
			$id_notice = 0;
		}
		print $form_resa_dates ;
	}
	if (!$id_notice) return "Erreur, pas de notice sélectionnée" ;
	
	if ($id_notice) {
		$opac_notices_depliable = 1 ;
		$liens_opac = array() ;
		$ouvrage_resa = aff_notice($id_notice, 1) ;
	}
	if (!$deb) {
		$form_resa_ok = str_replace('!!date_deb!!', formatdate($resa_date_debut), $form_resa_ok);
		$form_resa_ok = str_replace('!!date_fin!!', formatdate($resa_date_fin), $form_resa_ok);
		print $form_resa_ok;
	}
	
	//Affichage des réservations planifiées sur le document courant par le lecteur
	
	$requete3 = "SELECT id_resa, resa_idempr, resa_idnotice, resa_date, resa_date_debut, resa_date_fin, resa_validee, IF(resa_date_fin>=sysdate() or resa_date_fin='0000-00-00',0,1) as perimee, date_format(resa_date_fin, '".$msg["format_date_sql"]."') as aff_date_fin ";
	$requete3.= "FROM resa_planning ";
	$requete3.= "WHERE resa_idempr='".$_SESSION['id_empr_session']."' and resa_idnotice='".$id_notice."' ";
	$result3 = @mysql_query($requete3, $dbh);

	$message_resa="";
	while ($resa = mysql_fetch_array($result3)) {
		$id_resa = $resa['id_resa'];
		$resa_idempr = $resa['resa_idempr'];
		$resa_idnotice = $resa['resa_idnotice'];
		$resa_idbulletin = $resa['resa_idbulletin'];
		$resa_date = $resa['resa_date'];
		$resa_date_debut = $resa['resa_date_debut'];
		$resa_date_fin = $resa['resa_date_fin'];
		$resa_validee = $resa['resa_validee'];
		$message_resa.= "<blockquote><b>".$msg['resa_date_debut']."</b> ".formatdate($resa_date_debut)."&nbsp;<b>".$msg['resa_date_fin']."</b> ".formatdate($resa_date_fin)."&nbsp;" ;
		if (!$resa['perimee']) {
			if ($resa['resa_validee'])  $message_resa.= " ".$msg['resa_validee'] ;
				else $message_resa.= " ".$msg['resa_attente_validation']." " ;
		} else  $message_resa.= " ".$msg['resa_overtime']." " ;
		$message_resa.= "</blockquote>" ;
	}
	
	print pmb_bidi($message_resa);
	
	
	print $ouvrage_resa ;
}