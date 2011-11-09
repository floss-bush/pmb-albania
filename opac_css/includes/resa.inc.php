<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: resa.inc.php,v 1.46 2011-03-01 08:27:31 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fichier initialement créé et maintenu en partie gestion.

require_once($base_path.'/includes/resa_func.inc.php');
require_once($include_path."/mail.inc.php");
require_once($base_path.'/classes/notice.class.php');
require_once($base_path.'/classes/resa.class.php');

// Si id de bulletin, on ne s'occupe pas de sa notice du pério pour la résa
if($id_bulletin) $id_notice=0;

if ($opac_resa) {
	// est-on appelé par le popup
	if ($popup_resa) print "<h3><span>".$msg["resa_resa_titre_add"]."</span></h3>";
	
	if ($delete && ($id_notice || $id_bulletin)) {
		// *** Traitement de la suppression d'une résa affectée 
		$recup_id_resa = "select id_resa, resa_cb FROM resa WHERE resa_idempr=".$_SESSION["id_empr_session"];
		if ($id_notice) $recup_id_resa .= " AND resa_idnotice = $id_notice"; 
		else $recup_id_resa .= " AND resa_idbulletin = $id_bulletin";
		$resrecup_id_resa = mysql_query($recup_id_resa, $dbh);
		$obj_recupidresa = mysql_fetch_object($resrecup_id_resa) ;
		$suppr_id_resa = $obj_recupidresa->id_resa ;
		
		// récup éventuelle du cb
		$cb_recup = $obj_recupidresa->resa_cb ;
		// archivage resa
		$rqt_arch = "UPDATE resa_archive, resa SET resarc_anulee = 1 WHERE id_resa = '".$suppr_id_resa."' AND resa_arc = resarc_id ";	
		mysql_query($rqt_arch, $dbh);
		// suppression
		$rqt = "delete from resa where id_resa='".$suppr_id_resa."' ";
		$res = mysql_query ($rqt, $dbh) ;
		$nb_resa_suppr = mysql_affected_rows() ;
		
		// réaffectation du doc éventuellement
		if ($cb_recup) {
			if (!affecte_cb ($cb_recup) && $cb_recup) {
				if($pmb_transferts_actif){
					$rqt = "SELECT expl_location
						FROM transferts, transferts_demande, exemplaires						
						WHERE id_transfert=num_transfert and num_expl=expl_id  and expl_cb='".$cb_recup."' AND etat_transfert=0" ;
					$res = mysql_query ( $rqt );
					if (mysql_num_rows($res)){	
						$obj_expl=mysql_fetch_object($res);	 		
						// Document à traiter au lieu de à ranger, car transfert en cours?			
						$sql = "UPDATE exemplaires set expl_retloc='".$obj_expl->expl_location."' where expl_cb='".$cb_recup."' limit 1";						
						mysql_query($sql);
						$pas_ranger=1;
					}
				}
				if(!$pas_ranger){
					// cb non réaffecté, il faut transférer les infos de la résa dans la table des docs à ranger
					$rqt = "insert into resa_ranger (resa_cb) values ('".$cb_recup."') ";
					$res = mysql_query ($rqt, $dbh) ;
				}	
				alert_mail_users_pmb($id_notice, $id_bulletin, $_SESSION["id_empr_session"], 1) ;
			}
			alert_mail_users_pmb($id_notice, $id_bulletin, $_SESSION["id_empr_session"], 2) ;
		} else alert_mail_users_pmb($id_notice, $id_bulletin, $_SESSION["id_empr_session"], 1) ;
		if ($id_notice) {
			$opac_notices_depliable = 0 ;
			$opac_notices_format = 8 ;
			$ouvrage_resa = aff_notice($id_notice) ;
		} else {
			$ouvrage_resa = bulletin_affichage_reduit($id_bulletin) ;
		}
		if ($nb_resa_suppr) print "<span class='alerte'>".$msg["resa_cleared"]."</span><br />";
		print pmb_bidi($ouvrage_resa."<br />") ;
	
	} elseif (!$opac_resa_planning && ($id_notice || $id_bulletin)) { // ce n'est pas une suppression de résa et c'est une résa 'classique' 
		
	
		if (($pmb_transferts_actif=="1")&&($transferts_choix_lieu_opac=="1")&&($idloc=="")) {
			//les transferts sont actifs, avec un choix du lieu de retrait et pas de choix encore fait
			//=> on affiche les localisations
			
			if($pmb_location_reservation) {			
				$loc_req="SELECT idlocation, location_libelle FROM docs_location WHERE location_visible_opac=1  and idlocation in (select resa_loc from resa_loc where resa_emprloc=$empr_location) ORDER BY location_libelle ";
			} else {
				$loc_req="SELECT idlocation, location_libelle FROM docs_location WHERE location_visible_opac=1 ORDER BY location_libelle";
			}
			$res = mysql_query($loc_req);$tmpHtml = "<form method='post' action='do_resa.php?lvl=".$lvl."&id_notice=".$id_notice."&id_bulletin=".$id_bulletin."'>";
			$tmpHtml .= $msg["reservation_selection_localisation"]."<br /><select name='idloc'>";
			
			//on parcours la liste des localisations
			while ($value = mysql_fetch_array($res)) {
				if($value[0]==$empr_location) $selected=" selected='selected' ";
				else $selected="";
				$tmpHtml .= "<option value='" . $value[0] . "' $selected >" . $value[1] . "</option>";
			}
			$tmpHtml .= "</select><br /><br /><input type='submit' value='" . $msg["reservation_bt_choisir_localisation"] . "'></form>";
			echo $tmpHtml;
			
		} else {
	
			// test au cas où tentative de passer une résa hors URL de résa autorisée...
			$requete_resa = "SELECT count(1) FROM resa WHERE resa_idnotice='$id_notice' and resa_idbulletin='$id_bulletin'";
			$nb_resa_encours = mysql_result(mysql_query($requete_resa,$dbh), 0, 0) ;
			if ($opac_max_resa && $nb_resa_encours>=$opac_max_resa) {
				$id_notice = 0;
				$id_bulletin = 0 ;
			}
			if ($id_notice || $id_bulletin) { // c'est une pose de résa
				if ($id_notice) {
					$opac_notices_depliable = 0 ;
					$liens_opac = array() ;
					$ouvrage_resa = aff_notice($id_notice, 1) ;
				} else {
					$ouvrage_resa = bulletin_affichage_reduit($id_bulletin,1) ;
				}
				$message_resa = "" ;
				$resa_check = check_statut($id_notice, $id_bulletin) ;
				$already = allready_loaned($id_notice, $id_bulletin, $_SESSION["id_empr_session"]) ;
				if ($resa_check==1 && !$already) {
					// document sélectionné -> création de la réservation
					$res_resa_OK = check_quota_resa ($_SESSION["id_empr_session"], $id_notice, $id_bulletin) ;
					if ($res_resa_OK['ERROR']) {
						$message_resa = $msg["resa_failed"]." : ".$res_resa_OK['MESSAGE'] ;
					} else {
						$requete2 = "SELECT COUNT(1) FROM resa WHERE resa_idempr=".$_SESSION["id_empr_session"]." AND resa_idnotice='".$id_notice."' and resa_idbulletin='".$id_bulletin."' ";
						$result2 = @mysql_query($requete2, $dbh);
						$nb = mysql_result($result2,0,0);
						if ($nb) {
							// on ne peut pas réserver deux fois un même ouvrage
							$message_resa = $msg["resa_doc_deja_reserve"]." ";
						} else {
							$has_expl=1;
							if($pmb_location_reservation) {	
								$rqt = "SELECT expl_id FROM exemplaires WHERE expl_notice='".$id_notice."' 
									AND expl_bulletin='".$id_bulletin."' and expl_location in (select resa_loc from resa_loc where resa_emprloc=$empr_location)";
								$res_expl = mysql_query ($rqt, $dbh);
								$has_expl=0;
								if (mysql_num_rows($res_expl)) {
								 	while(($obj_expl=mysql_fetch_object($res_expl))) {			 		
								 		if(reservation::check_expl_reservable($obj_expl->expl_id)) {
								 			// cette localisation possède un exemplaire pouvant répondre à sa demande de réservation
								 			$has_expl=1;
								 		}
								 	}
								} 
							}
							if($has_expl) {
								if (($pmb_transferts_actif=="1")&&($transferts_choix_lieu_opac=="1")) {
									//les transferts sont activés et un lieu a été choisi
									$requete3 = "INSERT INTO resa (resa_idempr, resa_idnotice, resa_idbulletin, resa_date, resa_loc_retrait) ";
									$requete3 .= "VALUES ('".$_SESSION["id_empr_session"]."','$id_notice','$id_bulletin', SYSDATE(), $idloc)";
								} else {
									$requete3 = "INSERT INTO resa (resa_idempr, resa_idnotice, resa_idbulletin, resa_date) ";
									$requete3 .= "VALUES ('".$_SESSION["id_empr_session"]."','$id_notice','$id_bulletin', SYSDATE())";
								}
								$result3 = @mysql_query($requete3, $dbh);
								$message_resa = $msg["added_resa"];
								alert_mail_users_pmb($id_notice, $id_bulletin, $_SESSION["id_empr_session"]) ;
							} else {
								$message_resa=$msg["resa_doc_no_reservable"] ;	
							}
						}
						$id_resa_ajoutee = mysql_insert_id($dbh) ;
						if ($id_resa_ajoutee) {
							// Archivage de la résa: info lecteur et notice et nombre d'exemplaire
							$rqt = "SELECT * FROM empr WHERE id_empr=".$_SESSION["id_empr_session"];
							$empr = mysql_fetch_object(mysql_query($rqt));	
																
							if($id_notice) {
								$query = "SELECT count(*) FROM exemplaires where expl_notice='$id_notice'";
							}elseif($id_bulletin) {
								$query = "SELECT count(*) FROM exemplaires where expl_bulletin='$id_bulletin'";
							}
							$nb_expl = mysql_result(mysql_query($query),0);
							
							$query = "INSERT INTO resa_archive SET
								resarc_id_empr = '".$_SESSION["id_empr_session"]."', 
								resarc_idnotice = '".$id_notice."', 
								resarc_idbulletin = '".$id_bulletin."',
								resarc_date = SYSDATE(), 
								resarc_loc_retrait = '0',
								resarc_from_opac= '1',
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
							$query = "update resa SET resa_arc='$stat_id' where id_resa='".$id_resa_ajoutee."'";
							mysql_query($query, $dbh);		
							
							$rqt_recup_ajout = "SELECT resa_idempr, resa_idnotice, resa_idbulletin, resa_date_fin, resa_cb, IF(resa_date_fin>sysdate() or resa_date_fin='0000-00-00',0,1) as perimee, date_format(resa_date_fin, '".$msg["format_date_sql"]."') as aff_date_fin FROM resa WHERE id_resa='".$id_resa_ajoutee."' " ;
								
							$res_recup_ajout = mysql_query($rqt_recup_ajout, $dbh);
							$resa_ajoutee = mysql_fetch_object($res_recup_ajout) ;
							$rang = recupere_rang($resa_ajoutee->resa_idempr, $resa_ajoutee->resa_idnotice, $resa_ajoutee->resa_idbulletin) ;
							if($msg["resa_rank"]) $message_resa.= " - ".sprintf($msg["resa_rank"],$rang)." <br />" ;
							else $message_resa.= "<br />";
							if (!$resa_ajoutee->perimee) {
								if ($resa_ajoutee->resa_cb) $message_resa .= " ".sprintf($msg["expl_reserved_til"],$resa_ajoutee->aff_date_fin)." " ;
							} else  $message_resa .= " ".$msg["resa_overtime"]." " ;
						} // fin if ($id_resa_ajoutee)
					} // fin else (if $res_resa_OK['ERROR']) 
					print pmb_bidi("<span class='alerte'>".$message_resa."</span><br />".$ouvrage_resa );
				} else { // else if checkstatut
					if ($already) print pmb_bidi("<span class='alerte'>".$already."</span><br />".$ouvrage_resa) ; 
					else print pmb_bidi("<span class='alerte'>".$message_resa."</span><br />".$ouvrage_resa) ;
				} // fin else if checkstatut
			} // fin if($id_notice || $id_bulletin)
		}
	}
	
	if (!$popup_resa) {
		// récupération des résas de l'emprunteur
		print "<h3><span>".$msg["empr_bt_show_resa"]."</span></h3>";
		$requete3 = "SELECT id_resa, resa_idempr, resa_idnotice, resa_idbulletin, resa_date, resa_date_fin, resa_cb, IF(resa_date_fin>=sysdate() or resa_date_fin='0000-00-00',0,1) as perimee, date_format(resa_date_fin, '".$msg["format_date_sql"]."') as aff_date_fin FROM resa WHERE resa_idempr=".$_SESSION["id_empr_session"];
		$result3 = @mysql_query($requete3, $dbh);
		$tableau_resa="<table class='fiche-lecteur'>";
		if(mysql_numrows($result3) && ($msg["resa_liste_titre"] || $msg["resa_liste_rank"] || $msg["resa_liste_del"]))
			$tableau_resa.="<tr><th>".$msg["resa_liste_titre"]."</th><th>".$msg["resa_liste_rank"]."</th><th>".$msg["resa_liste_del"]."</th></tr>";
		while ($resa = mysql_fetch_array($result3)) {
			$message_null_resa="";
			$id_resa = $resa['id_resa'];
			$resa_idempr = $resa['resa_idempr'];
			$resa_idnotice = $resa['resa_idnotice'];
			$resa_idbulletin = $resa['resa_idbulletin'];
			$resa_date = $resa['resa_date'];
			if ($resa_idnotice) { 
				// affiche la notice correspondant à la réservation
				$requete = "SELECT * FROM notices WHERE notice_id='$resa_idnotice' ";
				$res = @mysql_query($requete, $dbh);
				$obj=mysql_fetch_object($res);
				$notice = new notice($obj);
				$titre= pmb_bidi($notice->print_resume(1,$css));
				$link_del= pmb_bidi("<a href=empr.php?tab=reza&lvl=resa&delete=1&id_notice=$resa_idnotice>".$msg['resa_effacer_resa']."</a>");
			} else {
				// c'est un bulletin donc j'affiche le nom de périodique et le nom du bulletin (date ou n°)
				$requete = "SELECT bulletin_id, bulletin_numero, bulletin_notice, mention_date, date_date, date_format(date_date, '".$msg["format_date_sql"]."') as aff_date_date FROM bulletins WHERE bulletin_id='$resa_idbulletin'";
  		        $res = mysql_query($requete, $dbh);
				$obj = mysql_fetch_object($res) ;
				$notice3 = new notice($obj->bulletin_notice);
				$titre=pmb_bidi($notice3->print_resume(1,$css));
				
				// affichage de la mention de date utile : mention_date si existe, sinon date_date
				if ($obj->mention_date) $titre.= pmb_bidi("(".$obj->mention_date.")\n"); 
				elseif ($obj->date_date) $titre.= pmb_bidi("(".$obj->aff_date_date.")\n");     
				
				$link_del="<a href='empr.php?tab=reza&lvl=resa&delete=1&id_bulletin=$resa_idbulletin'>".$msg['resa_effacer_resa']."</a>";	
			}
			
			$rang = recupere_rang($resa_idempr, $resa_idnotice, $resa_idbulletin) ;
			$rank_texte=sprintf($msg[rank],$rang) ;
			if (!$resa['perimee']) {
				if ($resa['resa_cb'])  $rank_texte.= " ".sprintf($msg["expl_reserved_til"],$resa['aff_date_fin'])." " ;
				else $rank_texte.= " ".$msg["resa_attente_validation"];
			} else  $rank_texte.= " ".$msg["resa_overtime"];
			
			if ($parity++ % 2) $pair_impair = "even"; else $pair_impair = "odd";
			$tableau_resa.="<tr class='$pair_impair'> <td>$titre</td><td>$rank_texte</td><td>$link_del</td></tr>";
			
		}
		$tableau_resa.="</table>";
		
		print  "$tableau_resa
				<br /><small><br />".$msg["empr_resa_how_to"]." <br />
				<form style='margin-bottom:0px;padding-bottom:0px;' action='empr.php' method='post' name='FormName'>
				<INPUT type='button' class='bouton' 'name='lvlx' value='".$msg["empr_make_resa"]."' onClick=\"document.location='./index.php'\">
				</form>
				</small>";
	}
	
	
} // fin if $opac_resa


/* fonction complexe à rediscuter : cas possibles :
- doc en consultation sur place uniquement
- doc mixed : exemplaire(s) en consultation sur place et exemplaire(s) en circulation
- doc en circulation ET disponible
La solution retenue : fetcher tous les exemplaires attachés à la notice et définir des flags de situation
*/
