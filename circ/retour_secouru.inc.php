<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: retour_secouru.inc.php,v 1.26 2010-07-06 10:07:39 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/emprunteur.class.php");
require_once("$class_path/serial_display.class.php");
require_once("$include_path/resa.inc.php");
require_once("$class_path/expl_to_do.class.php");
// define pour différent flags de situation document
define ('EX_OK', 1);
define ('EX_INCONNU', 2);
define ('HAS_RESA_GOOD', 4); // l'exemplaire est réservé pour ce lecteur
define ('NON_PRETABLE', 8);
define ('HAS_NOTE', 16);
define ('HAS_RESA_FALSE', 32); // l'exemplaire est réservé pour un autre lecteur
define ('ALREADY_LOANED', 64); // cet emprunteur a déjà emprunté ce document
define ('ALREADY_BORROWED', 128); // ce document est emprunté par un autre emprunteur

if (!$do) {
	$file=$_FILES['fichier_secouru']['tmp_name'];
	copy($file,"temp/".basename($file));
	$file="temp/".basename($file);
}

function read_line($fp) {
	global $nline;
	
	$r=false;
	while (!feof($fp)) {
		$line=@fgets($fp);
		if ($line!==false) {
			if (trim($line)) {
				$r=true;
				$nline++;
				break;
			}
		}
	}
	if ($r) return trim($line); else return false;
}

function is_reader($cb) {
	$requete="select id_empr from empr where empr_cb='".addslashes($cb)."'";
	$resultat=mysql_query($requete);
	
	if (mysql_num_rows($resultat)) return true; else return false;
}

function is_cb_ex($cb) {
	$requete="select expl_id from exemplaires where expl_cb='".addslashes($cb)."'";
	$resultat=mysql_query($requete);
	
	if (mysql_num_rows($resultat)) return true; else return false;
}

if (!$do) {
	//Test du fichier
	$fp=@fopen($file,"r");
	//Lecture de la première ligne
	$line=fgets($fp);
	$nline=1;
	if ($line===false) { 
		print "<div class='erreur'>Fichier non valide !</div>";
		fclose($fp);
	} else {		
		$etat="attente";
		$line=trim($line);

		while ($etat!="stop") {
			switch ($etat) {
				case "attente":
					if (($line!="RETOUR SECOURU")&&($line!="PRET SECOURU")) {
						$error=true;
						$error_message="PRET SECOURU ou RETOUR SECOURU attendu";
						$etat="stop";
					} else {
						if ($line=="PRET SECOURU") $etat="debut_pret"; else {
							$etat="debut_retour";
						}
					}
					break;
				case "debut_pret":
					$line=read_line($fp);
					if ($line===false) {
						$etat="stop";
					} else {
						if (is_reader($line)) {
							$reader=$line;
							$etat="pret";
						} else {
							$error=true;
							$error_message="Un code barre lecteur est attendu";
							$etat="stop";
						}
					}
				case "pret":
					$line=read_line($fp);
					if ($line===false) {
						$etat="stop";
					} else {
						if (($line=="PRET SECOURU")||($line=="RETOUR SECOURU")) {
							$etat="attente";
						} else {
							if (!is_cb_ex($line)) {
								$error=true;
								$error_message="Un code barre exemplaire est attendu";
								$etat="stop";	
							} else $etat="pret";
						} 
					}
					break;
				case "debut_retour":
					$line=read_line($fp);
					if ($line===false) {
						$etat="stop";
					} else {
						if (($line=="PRET SECOURU")||($line=="RETOUR SECOURU")) {
								$etat="attente";
						} else {
							if (!is_cb_ex($line)) {
								$error=true;
								$error_message="Un code barre exemplaire est attendu";
								$etat="stop";	
							} else $etat="debut_retour";
						}
					}
					break;
			}
		}
		fclose($fp);
		if ($error) {
			print "<div class='erreur'>Erreur à la ligne ".$nline." : ".$error_message."</div>";
			print "<center><input type='button' value='Lancer quand même la récupération' onClick=\"document.location='./circ.php?categ=retour_secouru_int&file=".rawurlencode($file)."&do=1'\" class='bouton'>&nbsp;<input type='button' value='Annuler' onClick=\"document.location='./circ.php?categ=retour_secouru';\" class='bouton'></center>";
		} else {
			print "<div class='erreur'>Fichier valide : lancement de la récupération...</div>";
			print "<script>document.location=\"./circ.php?categ=retour_secouru_int&file=".rawurlencode($file)."&do=1\";</script>";
		}
	}
}

if ($do==1) {
	$file=stripslashes($file);
	$fp=@fopen($file,"r");
	//Lecture de la première ligne
	$line=fgets($fp);
	$nline=1;
	if ($line===false) { 
		print "<div class='erreur'>Fichier non valide !</div>";
		fclose($fp);
	} else {		
		$etat="attente";
		$line=trim($line);

		while ($etat!="stop") {
			switch ($etat) {
				case "attente":
					if (($line!="RETOUR SECOURU")&&($line!="PRET SECOURU")) {
						$error=true;
						$error_message="PRET SECOURU ou RETOUR SECOURU attendu";
						$etat="stop";
					} else {
						if ($line=="PRET SECOURU") $etat="debut_pret"; else {
							print "<b>Retour</b><blockquote>";
							$etat="debut_retour";
						}
					}
					break;
				case "debut_pret":
					$line=read_line($fp);
					if ($line===false) {
						$etat="stop";
					} else {
						if (is_reader($line)) {
							$reader=$line;
							print "<b>Prêt</b><blockquote>";
							$etat="pret";
						} else {
							$error=true;
							$error_message="Un code barre lecteur est attendu";
							$etat="stop";
						}
					}
				case "pret":
					$line=read_line($fp);
					if ($line===false) {
						print "</blockquote>";
						$etat="stop";
					} else {
						if (($line=="PRET SECOURU")||($line=="RETOUR SECOURU")) {
							print "</blockquote>";
							$etat="attente";
						} else {
							rec_pret($reader,$line);
							$etat="pret";
						} 
					}
					break;
				case "debut_retour":
					$line=read_line($fp);
					if ($line===false) {
						print "</blockquote>";
						$etat="stop";
					} else {
						if (($line=="PRET SECOURU")||($line=="RETOUR SECOURU")) {
								print "</blockquote>";
								$etat="attente";
						} else {
							rec_retour($line);
							$etat="debut_retour";
						}
					}
					break;
			}
		}
		if ($error) 
			print "<div class='erreur'>Erreur à la ligne ".$nline." : ".$error_message."</div>"; 
		else
			print "<div class='erreur'>Récupération terminée</div>";
		fclose($fp);
	}
}

// <-------------- check_empr_secouru --------------->
// teste l'id_empr passée en paramètre (check si l'emprunteur existe)
function check_empr_secouru($id) {
	global $dbh;
	if (!$id)
		return FALSE;
	$query = "select count(1) as qte from empr where id_empr='$id' ";
	$result = mysql_query($query, $dbh);
	return mysql_result($result, 0, 0);

}

// <-------------- check_document() --------------->
// récupère différents paramètres sur le document à emprunter
/* ce qui nous intéresse :
- si le document est inconnu : on ne fait rien bien entendu -> retour EX_INCONNU
- si le document est déja en prêt -> allready_BORROWED
- si l'exemplaire a une note -> l'utilisateur doit confirmer le prêt (HAS_NOTE)
- si le document est en consultation sur place -> l'utilisateur doit confirmer le prêt retour SUR_PLACE
- si le document est réservé pour un autre lecteur -> l'utilisateur doit confirmer le prêt  retour HAS_RESA
- si le document est réservé pour ce lecteur -> on efface la réservation et on retourne EX_OK */
function check_document($id_expl, $id_empr) {

	global $dbh;

	$retour -> flag = 0;

	if (!$id_expl || !$id_empr)
		return $retour -> flag;

	// on tente de récupérer les infos exemplaire utiles
	$query = "select e.expl_cb as cb, e.expl_id as id, s.pret_flag as pretable, e.expl_notice as notice, e.expl_bulletin as bulletin, e.expl_note as note, expl_comment, s.statut_libelle as statut";
	$query.= " from exemplaires e, docs_statut s";
	$query.= " where e.expl_id=$id_expl";
	$query.= " and s.idstatut=e.expl_statut";
	$query.= " limit 1";
	$result = mysql_query($query, $dbh);

	// exemplaire inconnu
	if (!mysql_num_rows($result)) {
		$retour -> flag = EX_INCONNU;
		return $retour;
	}
	$expl = mysql_fetch_object($result);

	$retour -> expl_cb = $expl -> cb;

	// une autre query pour savoir si l'exemplaire est en prêt...
	$query = "select pret_idempr from pret where pret_idexpl=$id_expl limit 1";
	$result = mysql_query($query, $dbh);
	if (@ mysql_num_rows($result)) {
		// l'exemplaire est déjà en prêt
		$empr = mysql_result($result, '0', 'pret_idempr');
		// l'emprunteur est l'emprunteur actuel
		if ($empr == $id_empr) $retour -> flag += ALREADY_LOANED;
			else $retour -> flag += ALREADY_BORROWED;
		}

	// cas de l'exemplaire qui a une note
	if ($expl -> note) {
		$retour -> flag += HAS_NOTE;
		$retour -> note = $expl -> note;
		}

	// cas de l'exemplaire en consultation sur place
	if (!$expl -> pretable) {
		// l'exemplaire est en consultation sur place
		$retour -> flag += NON_PRETABLE;
		$retour -> note = $expl -> statut;
		}

	// cas des réservations
	// on checke si l'exemplaire a une réservation
	$query = "select resa_idempr as empr, id_resa, resa_cb from resa where resa_idnotice='$expl->notice' and resa_idbulletin='$expl->bulletin' order by resa_date limit 1";
	$result = mysql_query($query, $dbh);
	if (mysql_num_rows($result)) {
		$reservataire = mysql_result($result, 0, 'empr');
		$id_resa = mysql_result($result, 0, 'id_resa');
		$resa_cb = mysql_result($result, 0, 'resa_cb');
		
		$retour -> idnotice = $expl -> notice;
		$retour -> idbulletin = $expl -> bulletin;
		$retour -> id_resa = $id_resa ;
		$retour -> resa_cb = $resa_cb ;
		if ($reservataire == $id_empr) {
			// la réservation est pour ce lecteur
			$retour -> flag += HAS_RESA_GOOD;
			} else {
				// réservé pour un autre lecteur
				$retour -> flag += HAS_RESA_FALSE;
				}
		}
	return $retour;
	}

// ajoute le prêt en table
function add_pret($id_empr, $id_expl, $cb_doc) {
	// le lien MySQL
	global $dbh;
	global $msg;
	global $pmb_quotas_avances;
	
	/* on prépare la date de début*/
	$pret_date = time();

	/* on cherche la durée du prêt */
	if($pmb_quotas_avances) {
		//Initialisation de la classe
		$qt=new quota("LEND_TIME_QUOTA");
		$struct["READER"]=$id_empr;
		$struct["EXPL"]=$id_expl;
		$duree_pret=$qt->get_quota_value($struct);
		if ($duree_pret==-1) $duree_pret=0; 
	} else {
		$query = "SELECT duree_pret";
		$query.= " FROM exemplaires, docs_type";
		$query.= " WHERE expl_id='".$id_expl;
		$query.= "' and idtyp_doc=expl_typdoc LIMIT 1";

		$result = @ mysql_query($query, $dbh) or die("can't SELECT exemplaires ".$query);
		$expl_properties = mysql_fetch_object($result);
		$duree_pret = $expl_properties -> duree_pret;
	} 	
	// calculer la date de retour prévue 
	$pret_retour = $pret_date +3600 * 24 * $duree_pret;
	
	// insérer le prêt 
	$query = "INSERT INTO pret SET ";
	$query.= "pret_idempr = '".$id_empr."', ";
	$query.= "pret_idexpl = '".$id_expl."', ";
	$query.= "pret_date   = sysdate(), ";
	$query.= "pret_retour = '".date("Y-m-d", $pret_retour)."', ";
	$query.= "retour_initial = '".date("Y-m-d", $pret_retour)."' ";
	$result = @ mysql_query($query, $dbh) or die(mysql_error()."<br />can't INSERT into pret".$query);
	
	// insérer la trace en stat, récupérer l'id et le mettre dans la table des prêts pour la maj ultérieure
	$stat_avant_pret = pret_construit_infos_stat ($id_expl) ;
	$stat_id = stat_stuff ($stat_avant_pret) ;
	$query = "update pret SET pret_arc_id='$stat_id' where ";
	$query.= "pret_idempr = '".$id_empr."' and ";
	$query.= "pret_idexpl = '".$id_expl."' ";
	$result = @ mysql_query($query, $dbh) or die("can't update pret for stats ".$query);
	audit::insert_creation (AUDIT_PRET, $stat_id) ;
	
	$query = "update exemplaires SET ";
	$query.= "last_loan_date = sysdate() ";
	$query.= "where expl_id= '".$id_expl."' ";
	$result = @ mysql_query($query, $dbh) or die("can't update last_loan_date in exemplaires : ".$query);

	$query = "update empr SET ";
	$query.= "last_loan_date = sysdate() ";
	$query.= "where id_empr= '".$id_empr."' ";
	$result = @ mysql_query($query, $dbh) or die("can't update last_loan_date in empr : ".$query);

}

// efface une résa pour un emprunteur donné et réaffecte le cb éventuellement
function del_resa($id_empr, $id_notice, $id_bulletin, $cb_encours_de_pret) {
	
	global $dbh;
	
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

function rec_pret($reader,$line) {
	global $msg,$dbh;
	//Recherche du lecteur
	$requete="select id_empr from empr where empr_cb='".addslashes($reader)."'";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
		$id_empr=mysql_result($resultat,0,0);
		//Recherche du lecteur
		$requete="select expl_id from exemplaires where expl_cb='".addslashes($line)."'";
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) {
			$expl_id=mysql_result($resultat,0,0);
			print pmb_bidi("<div class='erreur'>Prêt <a href='./circ.php?categ=visu_ex&form_cb_expl=".rawurlencode($line)."'>".$line."</a> pour <a href='./circ.php?categ=pret&form_cb=".rawurlencode($reader)."'>".$reader."</a></div>");	
			if (check_empr_secouru($id_empr)) {
				$empr_temp = new emprunteur($id_empr, '', FALSE, 1);
				$statut = check_document($expl_id, $id_empr);
				if ($statut -> flag & ALREADY_LOANED || $statut -> flag & ALREADY_BORROWED) {
					if ($statut -> flag & ALREADY_LOANED) {
						print "			<div class='row'>
											<span class='erreur'>$msg[386]</span></div>
											<br />";
					}
					if ($statut -> flag & ALREADY_BORROWED) {
						print "			<div class='row'>
											<span class='erreur'>$msg[387]</span></div>
											<br />";
					}
				} else {
					if ($statut -> flag && ($statut -> flag & HAS_RESA_GOOD)) {
						// archivage resa
						$rqt_arch = "UPDATE resa_archive, resa SET resarc_pretee = 1 WHERE id_resa = '".$statut->id_resa."' AND resa_arc = resarc_id ";	
						mysql_query($rqt_arch, $dbh);
						
						// suppression de la resa pour ce lecteur
						del_resa($id_empr, $statut -> idnotice, $statut -> idbulletin, $statut -> expl_cb);
					}
					// ajout du prêt
					add_pret($id_empr, $expl_id, $line);
					print "<div class='erreur'>effectué</div>";	
				}
			} else {
				print "<div class='erreur'>".$reader." : Lecteur inconnu"."</div>";	
			}
		} else {
			print "<div class='erreur'>".$line." : Exemplaire inconnu"."</div>";	
		}
	} else {
		print "<div class='erreur'>".$reader." : Lecteur inconnu"."</div>";	
	}
}

function rec_retour($line) {
	global $action_piege,$piege_resa;
	$form_cb_expl=$line;
	$expl=new expl_to_do($form_cb_expl);
	//print $expl->cb_tmpl;
	//if(!$form_cb_expl) exit;
	$expl->do_form_retour($action_piege,$piege_resa);
	print $expl->expl_form;
	return;
	// la suite n'est plus utilisé	
	
	if ($form_cb_expl) {
		print "<hr />";
		// étape 1 : on regarde si le code-barre est connu
		if($stuff=check_barcode($form_cb_expl)) {
			$stuff = check_pret($stuff);
			$stuff = check_resa($stuff);
			// on a maintenant un gros objet $stuff avec toutes les infos pour traiter les choses
			// les propriétés de cet objet sont visibles dans la fonction show_reports() plus bas
			// show_report($stuff); // uncomment for debugging
			// appel de la fonction do_retour, qui va gérer tout cela
			do_retour_secouru($stuff);
		} else {
			print "<div class='erreur'>".$form_cb_expl." : Exemplaire inconnu"."</div>";			
		}
	}
}

// effectue les opérations de retour et mise en stat
function do_retour_secouru($stuff) {

	global $dbh;
	global $msg;
	global $alert_sound_list;

	if(!is_object($stuff))
		die("erreur grave dans le module ./circ/retour_secouru.inc [do_retour_secouru()]. Contactez l'admin");

	print pmb_bidi('<strong>'.$stuff->libelle.'</strong>');
	// récupération localisation exemplaire
	$query = "select t.tdoc_libelle as type_doc";
	$query .= ", l.location_libelle as location";
	$query .= ", s.section_libelle as section";
	$query .= " from docs_type t";
	$query .= ", docs_location l";
	$query .= ", docs_section s";
	$query .= " where t.idtyp_doc=".$stuff->expl_typdoc;
	$query .= " and l.idlocation=".$stuff->expl_location;
	$query .= " and s.idsection=".$stuff->expl_section;
	$query .= " limit 1";

	$result = mysql_query($query, $dbh);
	$info_doc = mysql_fetch_object($result);
	print pmb_bidi('<br />'.$info_doc->type_doc);
	print pmb_bidi('.&nbsp;'.$info_doc->location);
	print pmb_bidi('.&nbsp;'.$info_doc->section);
	print pmb_bidi('.&nbsp;'.$stuff->expl_cote);
	if($stuff->pret_idempr) {
		// l'exemplaire était effectivement emprunté
		// on affiche les infos de l'emprunteur
		print "<hr /><div class='row'>${msg[368]} : </div>";
		print "<a href='./circ.php?categ=pret&form_cb=".rawurlencode($stuff->empr_cb)."'>";
		print pmb_bidi($stuff->empr_prenom.' '.$stuff->empr_nom.'</a>');
		
		if ($stuff->empr_msg) {
			$message_fiche_empr= "
					<div class='row'>
					<div class='colonne10'><img src='./images/info.png' /></div>
					<div class='colonne-suite'><span class='erreur'>$stuff->empr_msg</span></div>
					</div><br />";
			$alert_sound_list[]="information";
			print $message_fiche_empr ;
			}
			
		// calcul du retard éventuel
		$rqt_date = "select ((TO_DAYS(CURDATE()) - TO_DAYS('$stuff->pret_retour'))) as retard ";
		$resultatdate=mysql_query($rqt_date);
		$resdate=mysql_fetch_object($resultatdate);
		$retard = $resdate->retard;
		
		if($retard > 0)
			print "<hr /><div class='erreur'>${msg[369]}&nbsp;: $retard ${msg[370]}</div>";

		// zone du dernier emrunteur
		if($stuff->expl_lastempr) {
			print "<hr /><div class='row'>$msg[expl_prev_empr] ";
			$link = "<a href='./circ.php?categ=pret&form_cb=".rawurlencode($stuff->lastempr_cb)."'>";
			print pmb_bidi($link.$stuff->lastempr_prenom.' '.$stuff->lastempr_nom.' ('.$stuff->lastempr_cb.')</a>');
			print "</div><hr />";
			}

		// code de suppression prêt et la mise en table de stat
		if (del_pret($stuff)) {
			if(!stat_stuff($stuff)) {
				// impossible d'insérer en table stat
				print "<div class='erreur'>${msg[371]}</div>";
			}
		} else {
			// impossible de supprimer en table pret
			print "<div class='erreur'>${msg[372]}</div>";
		}
	} else {
		print "<div class='erreur'>${msg[605]}</div>";
	}

	if ($stuff->expl_note)
		print pmb_bidi("<hr /><div class='erreur'>${msg[377]} :</div><div class='message_important'>".$stuff->expl_note."</div>");
	if ($stuff->expl_comment)
		print pmb_bidi("<hr /><div class='erreur'>".$msg[expl_zone_comment]." :</div>".$stuff->expl_comment."<br />");

		// traitement de l'éventuelle réservation
	if ($stuff->resa_idempr) {
		// le doc en retour peut servir à valider une résa suivante
		if (!verif_cb_utilise ($stuff->expl_cb)) {
			$affect = affecte_cb ($stuff->expl_cb) ;
			// affichage message de réservation
			if ($affect) {
				print pmb_bidi("<div class='erreur'>$msg[352]</div>
					<div class='row'>
					${msg[373]}
					<strong><a href='./circ.php?categ=pret&form_cb=".rawurlencode($stuff->cb_reservataire)."'>".$stuff->prenom_reservataire."&nbsp;".$stuff->nom_reservataire."</a></strong>
					&nbsp;( $stuff->cb_reservataire )
					</div>");
				$alert_sound_list[]="information";

				if ($affect) alert_empr_resa($affect) ;
						 // print "<script type='text/javascript'>window.open('./pdf.php?pdfdoc=lettre_resa&id_resa=$affect', 'lettre_confirm_resa', 'toolbar=no, dependent=yes, width=600, height=500, resizable=yes, scrollbars=yes');</script>";
			}
		}	
	}

	}

