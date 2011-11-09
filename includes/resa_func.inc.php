<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: resa_func.inc.php,v 1.100.2.2 2011-06-14 14:33:49 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/quotas.class.php");
require_once("$class_path/transfert.class.php");
require_once("$include_path/templates/resa.tpl.php");
require_once("$class_path/resa.class.php");

// defines pour flag affichage info de gestion
define ('NO_INFO_GESTION', 0); // 0 >> aucune info de gestion : liste simple, attention utilisée un peu partout !
define ('GESTION_INFO_GESTION', 1); // pour traitement des résa
define ('LECTEUR_INFO_GESTION', 2); // pour affichage en fiche lecteur

function resa_list ($idnotice=0, $idbulletin=0, $idempr=0, $order="", $where = "", $info_gestion=NO_INFO_GESTION, $url_gestion="") {

	global $dbh ;
	global $msg;
	global $montrerquoi ;
	global $current_module ;
	global $pdflettreresa_priorite_email_manuel;
	global $deflt2docs_location, $pmb_lecteurs_localises, $empr_location_id ;
	global $pmb_transferts_actif,$f_loc, $transferts_choix_lieu_opac;
	global $resa_liste_jscript_GESTION_INFO_GESTION, $ajout_resa_jscript_choix_loc_retrait,$deflt_docs_location;
	global $tdoc,$charset,$transferts_site_fixe, $pmb_location_reservation;
	if (!$montrerquoi) $montrerquoi='all' ;
	if (!$order) $order="notices_m.index_sew, resa_idnotice, resa_idbulletin, resa_date" ;
	if ($pmb_lecteurs_localises && !$idempr){
		if ($f_loc=="")	$f_loc = $deflt_docs_location;
		if ($f_loc)	$sql_expl_loc= " and expl_location='".$f_loc."' ";
	}
	if ($pmb_transferts_actif=="1" && $f_loc && !$idempr) {
		switch ($transferts_choix_lieu_opac) {	
			case "1":
				//retrait de la resa sur lieu choisi par le lecteur
				$sql_suite .= " AND resa_loc_retrait='".$f_loc."' ";
			break;		
			case "2":
				//retrait de la resa sur lieu fixé
				if ($f_loc!=$transferts_site_fixe)
					$sql_suite.= " AND 0";
			break;		
			case "3":
				//retrait de la resa sur lieu exemplaire
				// On affiche les résa que peut satisfaire la loc
				// respecter les droits de réservation du lecteur 
				if($pmb_location_reservation) {			
					$sql_loc_resa.=" and empr_location=resa_emprloc and resa_loc='".$f_loc."' ";
					$sql_loc_resa_from=", resa_loc ";
				}	
				if ($f_loc && !$idempr )$sql_expl_loc= " and expl_location='".$f_loc."' ";
			break;	
			default:
				//retrait de la resa sur lieu lecteur
				$sql_suite .= " AND empr_location='".$f_loc."' ";
				if ($f_loc && !$idempr )$sql_expl_loc= " and expl_location='".$f_loc."' ";
			break;
		}	
	}elseif($pmb_location_reservation && $f_loc && !$idempr) {			
		$sql_loc_resa.=" and empr_location=resa_emprloc and resa_loc='".$f_loc."' ";
		$sql_loc_resa_from=", resa_loc ";
	}	
	
	$sql="SELECT resa_idnotice, resa_idbulletin, resa_date, resa_date_debut, resa_date_fin, resa_cb, resa_confirmee, resa_idempr, empr_nom, empr_prenom, empr_cb, location_libelle, resa_loc_retrait, ";
	$sql.=" trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit, id_resa, ";
	$sql.=" ifnull(notices_m.typdoc,notices_s.typdoc) as typdoc, ";
	$sql.=" IF(resa_date_fin>=sysdate() or resa_date_fin='0000-00-00',0,1) as perimee, date_format(resa_date_debut, '".$msg["format_date"]."') as aff_resa_date_debut, if(resa_date_fin='0000-00-00', '', date_format(resa_date_fin, '".$msg["format_date"]."')) as aff_resa_date_fin, date_format(resa_date, '".$msg["format_date"]."') as aff_resa_date " ;
	$sql.=" FROM (((resa LEFT JOIN notices AS notices_m ON resa_idnotice = notices_m.notice_id ) ";
	$sql.=" LEFT JOIN bulletins ON resa_idbulletin = bulletins.bulletin_id) ";
	$sql.=" LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), ";
	$sql.=" empr, docs_location $sql_loc_resa_from ";
	$sql.=" WHERE resa_idempr = id_empr AND idlocation = empr_location ";
	$sql.=$sql_suite;
	
	if ($idempr) 
		$sql.=" AND id_empr='$idempr'";
		
	if ($montrerquoi=='validees')
		$sql .= " AND resa_cb<>''";
		
	if ($montrerquoi=='invalidees')
		$sql .= " AND resa_cb=''";
	
	if ($montrerquoi=='valid_noconf') {
		$sql .= " AND resa_cb!=''";
		$sql .= " AND resa_confirmee=0";
	}
	if ($where) 
		$sql.=" AND ".$where ;
	
	$sql.=" $sql_loc_resa GROUP BY resa_idnotice, resa_idbulletin, resa_idempr ";
	$sql.=" ORDER BY ".$order ;
	
	if ($idnotice || $idbulletin) {
		$sql="SELECT resa_idnotice, resa_idbulletin, resa_date, resa_date_debut, resa_date_fin, resa_cb, resa_confirmee, resa_idempr, empr_nom, empr_prenom, empr_cb, location_libelle, resa_loc_retrait, ";
		$sql.=" trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit, id_resa, ";
		$sql.=" ifnull(notices_m.typdoc,notices_s.typdoc) as typdoc, ";
		$sql.=" IF(resa_date_fin>=sysdate() or resa_date_fin='0000-00-00',0,1) as perimee, date_format(resa_date_debut, '".$msg["format_date"]."') as aff_resa_date_debut, if(resa_date_fin='0000-00-00', '', date_format(resa_date_fin, '".$msg["format_date"]."')) as aff_resa_date_fin, date_format(resa_date, '".$msg["format_date"]."') as aff_resa_date " ;
		$sql.=" FROM (((resa LEFT JOIN notices AS notices_m ON resa_idnotice = notices_m.notice_id ) ";
		$sql.=" LEFT JOIN bulletins ON resa_idbulletin = bulletins.bulletin_id) ";
		$sql.=" LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), ";
		$sql.=" empr, docs_location  ";
		$sql.=" WHERE resa_idempr = id_empr AND idlocation = empr_location  AND resa_idnotice = '$idnotice' AND resa_idbulletin='$idbulletin' ";
		$sql.=" ORDER BY  resa_date" ;
		$f_loc=0;
	}	
	
	$req = mysql_query($sql) or die("Erreur SQL !<br />".$sql."<br />".mysql_error()); 
	
	switch ($info_gestion) {
		case GESTION_INFO_GESTION:
			$aff_final .= 
				"<script type=\"text/javascript\">
					function confirm_delete() {
						result = confirm('".$msg["resa_confirm_suppr"]."');
		       			if(result)
		           			return 1;
					}
				</script>
				<form class='form-$current_module' name='check_resa' action='$url_gestion' method='post'><div class='left'>" ;
			
			//les boutons radios
			$aff_final .= "<span class='usercheckbox'><input type='radio' name='montrerquoi' value='all' id='all' onclick='this.form.submit();' ";
			if ($montrerquoi=='all') $aff_final .= "checked" ;
			$aff_final .= "><label for='all'>".$msg['resa_show_all']."</label></span>&nbsp;<span class='usercheckbox'><input type='radio' name='montrerquoi' value='validees' id='validees' onclick='this.form.submit();' ";
			if ($montrerquoi=='validees') $aff_final .= "checked" ;
			$aff_final .= "><label for='validees'>".$msg['resa_show_validees']."</label></span>&nbsp;<span class='usercheckbox'><input type='radio' name='montrerquoi' value='invalidees' id='invalidees' onclick='this.form.submit();' ";
			if ($montrerquoi=='invalidees') $aff_final .= "checked" ;
			$aff_final .= "><label for='invalidees'>".$msg['resa_show_invalidees']."</label></span>&nbsp;<span class='usercheckbox'><input type='radio' name='montrerquoi' value='valid_noconf' id='valid_noconf' onclick='this.form.submit();' ";
			if ($montrerquoi=='valid_noconf') $aff_final .= "checked" ;
			$aff_final .= "><label for='valid_noconf'>".$msg['resa_show_non_confirmees']."</label></span>";
			
			if ($pmb_transferts_actif=="1" || $pmb_location_reservation) {
				//la liste de sélection de la localisation
				$aff_final .= "<br />".$msg["transferts_circ_resa_lib_localisation"];
				$aff_final .= "<select name='f_loc' onchange='document.check_resa.submit();'>";
				$res = mysql_query("SELECT idlocation, location_libelle FROM docs_location order by location_libelle");
				$aff_final .= "<option value='0'>".$msg["all_location"]."</option>";
				//on parcours la liste des options
				while ($value = mysql_fetch_array($res)) {
					//debut de l'option
					$aff_final .= "<option value='".$value[0]."'";
					if ($value[0]==$f_loc)
						//c'est l'option par défaut
						$aff_final .= " selected";
					
					//fin de l'option
					$aff_final .= ">".$value[1]."</option>";
				}
				$aff_final .= "</select>";
			}
			//le lien pour l'edition
			if (SESSrights & EDIT_AUTH) $lien_edit_resa_traiter = "<a href='./edit.php?categ=notices&sub=resa_a_traiter'>".$msg[1100]."</a>" ;
			
			$aff_final .= "</div><div class='right'>".$lien_edit_resa_traiter."</div>";
			$aff_final .= "<div class='row'>&nbsp;</div>" ;
			
			jscript_checkbox() ;
			break;
		case LECTEUR_INFO_GESTION:
			if (($pmb_transferts_actif=="1")&&($transferts_choix_lieu_opac=="1")) {
				$aff_final .= $ajout_resa_jscript_choix_loc_retrait;
			}
			break;
		default:
		case NO_INFO_GESTION:
			break;
	}
	
	if (!mysql_num_rows($req)) {
		switch ($info_gestion) {
			case GESTION_INFO_GESTION:
				$aff_final .= "</form>" ;
				break;
			case LECTEUR_INFO_GESTION:
				break;
			default:
			case NO_INFO_GESTION:
				break;
		}
		return $aff_final ;
	}
		
	$aff_final .= "
	<script type='text/javascript' src='./javascript/sorttable.js'></script>
	<table  class='sortable' width='100%'>";
	
	$aff_final .= "<tr>" ;
	if (!$idnotice && !$idbulletin) $aff_final .= "<th>$msg[233]</th>" ;
	if (!$idempr) {
		$aff_final .= "<th>$msg[empr_nom_prenom]</th>";
		if ($pmb_lecteurs_localises) $aff_final .= "<th>$msg[empr_location]</th>";
	}
	
	$aff_final .= 	"<th>$msg[366]</th>".
	 				"<th>$msg[374]</th>".
	 				"<th>$msg[resa_condition]</th>".
	 				"<th>$msg[resa_date_fin]</th>";
	
	
	switch ($info_gestion) {
		case GESTION_INFO_GESTION:
			$aff_final .= "<th>" . $msg["resa_validee"] . "</th>";
			$aff_final .= "<th>" . $msg["resa_confirmee"] . "</th>";
			if ($pmb_transferts_actif=="1")
				$aff_final .= "<th>" . $msg["resa_loc_retrait"] . "</th>";
			$aff_final .= "<th>" . $msg["resa_selectionner"] . "</th>" ;
			if ($pmb_transferts_actif=="1") {
				$aff_final .= "<th>&nbsp;</th>";
			}
			break;
		case LECTEUR_INFO_GESTION:
			$aff_final .= "<th>" . $msg["resa_confirmee"] . "</th>";
			if ($pmb_transferts_actif=="1")
				$aff_final .= "<th>" . $msg["resa_loc_retrait"] . "</th>";
			$aff_final .= "<th class='sorttable_nosort'>" . $msg["resa_suppr_th"] . "</th>" ;
			break;
		default:
		case NO_INFO_GESTION:
			break;
		}
	
	$aff_final .= "</tr>";
	$odd_even=0;
	
	//on parcours la liste des réservations
	while ($data = mysql_fetch_array($req)) {
		$resa_idnotice = $data['resa_idnotice'];
		$resa_idbulletin = $data['resa_idbulletin'];
		$resa_idempr = $data['resa_idempr'] ;
		$precedenteresa_idbulletin=0;
		$precedenteresa_idnotice=0;
		
		$no_aff=0;	
		if(!($idnotice || $idbulletin))
		if($f_loc &&!$idempr && $data['resa_cb'] && $data['resa_confirmee']){
			// Dans la liste des résa à traiter, on n'affiche pas la résa qui a été affecté par un autre site
			$query = "SELECT expl_location FROM exemplaires WHERE expl_cb='".$data['resa_cb']."' ";
			$res = @mysql_query($query, $dbh);
			if(($data_expl = mysql_fetch_array($res))){
				if($data_expl['expl_location']!=$f_loc) {
					$no_aff=1;
					continue;
				}	
			}
		}
		if($idempr)$f_loc=0;
		$rank = recupere_rang($resa_idempr, $resa_idnotice, $resa_idbulletin,$f_loc) ;
		$resa=new reservation($resa_idempr,$resa_idnotice, $resa_idbulletin);
		$resa->get_resa_cb();
		
		if (($resa_idnotice != $precedenteresa_idnotice) || ($resa_idbulletin != $precedenteresa_idbulletin)) {
			$precedenteresa_idnotice=$resa_idnotice;
			$precedenteresa_idbulletin=$resa_idbulletin;
					
			// détermination de la date à afficher dans la case retour pour le rang 1
			// disponible, réservé ou date de retour du premier exemplaire
			
			// on compte le nombre total d'exemplaires prêtables pour la notice
			$query = "SELECT count(1) FROM exemplaires, docs_statut WHERE expl_statut=idstatut AND pret_flag=1 $sql_expl_loc ";
			if ($resa_idnotice)  $query .= " AND expl_notice=".$resa_idnotice;
				elseif ($resa_idbulletin) $query .= " AND expl_bulletin=".$resa_idbulletin;
			$tresult = @mysql_query($query, $dbh);
			$total_ex = mysql_result($tresult, 0, 0);
			if($sql_expl_loc && !$total_ex) $no_aff=1;
			// on compte le nombre d'exemplaires sortis
			$query = "SELECT count(1) as qte FROM exemplaires , pret WHERE pret_idexpl=expl_id $sql_expl_loc ";
			if ($resa_idnotice) $query .= " and expl_notice=".$resa_idnotice;
				elseif ($resa_idbulletin) $query .= " and expl_bulletin=".$resa_idbulletin;

			$tresult = @mysql_query($query, $dbh);
			$total_sortis = mysql_result($tresult, 0, 0);
			
			// on en déduit le nombre d'exemplaires disponibles
			$total_dispo = $total_ex - $total_sortis;
			
			$lien_transfert = false;
			
			if($total_dispo>0) {
				// un exemplaire est disponible pour le réservataire (affichage : disponible)
				$situation = "<strong>$msg[359]</strong>";
				if($data['resa_cb']&& $data['aff_resa_date_fin']) $situation = "<strong>".$msg['expl_reserve']."</strong>";
				elseif($rank>$total_dispo)	$situation = "<strong>".$msg['expl_resa_already_reserved']."</strong>";
				if ( ($pmb_transferts_actif=="1") && ($info_gestion==GESTION_INFO_GESTION) ) {
					$dest_loc = resa_loc_retrait($data['id_resa']);
				
					if ($dest_loc!=0) {
						$query = "SELECT count(1) FROM exemplaires, docs_statut WHERE expl_statut=idstatut AND pret_flag=1";
						$query .= " AND expl_location=".$dest_loc;
						if ($resa_idnotice)  $query .= " AND expl_notice=".$resa_idnotice;
							elseif ($resa_idbulletin) $query .= " AND expl_bulletin=".$resa_idbulletin;
						$tresult = mysql_query($query, $dbh);
						$total_ex = mysql_result($tresult, 0);
						
						if ($total_ex==0) {
							//on a pas d'exemplaires sur le site de retrait
							//on regarde si on en ailleurs
							$query = "SELECT count(1) FROM exemplaires, docs_statut WHERE expl_statut=idstatut AND pret_flag=1";
							$query .= " AND expl_location<>".$dest_loc;
							if ($resa_idnotice)  $query .= " AND expl_notice=".$resa_idnotice;
								elseif ($resa_idbulletin) $query .= " AND expl_bulletin=".$resa_idbulletin;
							$tresult = mysql_query($query, $dbh);
							$total_ex = mysql_result($tresult, 0);
							
							if ($total_ex!=0) { 
								//on en a au moins un ailleurs!
								//on regarde si un des exemplaires n'est pas en transfert pour cette resa !
								$query = "SELECT count(1) FROM transferts WHERE etat_transfert=0 AND origine=4 AND origine_comp=".$data['id_resa'];
								$tresult = mysql_query($query, $dbh);
								$nb_trans = mysql_result($tresult, 0);
								
								if ($nb_trans!=0) {
									//on a un transfert en cours
									$situation = "<strong>" . $msg["transferts_circ_resa_lib_en_transfert"] . "</strong>";
								} else
									$lien_transfert = true;
							}
						} //if ($total_ex==0)
					} //if ($dest_loc!=0)
				} //if ( ($pmb_transferts_actif=="1") && ($info_gestion==GESTION_INFO_GESTION) )
			} else {
				if($total_dispo) {
					// un ou des exemplaires sont disponibles, mais pas pour ce réservataire (affichage : reservé)
					$situation = $msg["resa_expl_reserve"];
				} else {
					// rien n'est disponible, on trouve la date du premier retour
					$query = "SELECT date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour from pret p, exemplaires e ";
					if ($resa_idnotice) $query .= " WHERE e.expl_notice=".$resa_idnotice;
						elseif ($resa_idbulletin) $query .= " WHERE e.expl_bulletin=".$resa_idbulletin;
					$query .= " AND e.expl_id=p.pret_idexpl";
					$query .= " ORDER BY p.pret_retour LIMIT 1";
					$tresult = mysql_query($query, $dbh);
					if (mysql_num_rows($tresult)) {
						$situation = mysql_result($tresult, 0, 0);
					}else {
						$situation = $msg["resa_no_expl"];
					}
					if ( ($pmb_transferts_actif=="1") && ($f_loc!=0) && $transferts_choix_lieu_opac!=3) {
						//on regarde si un des exemplaires n'est pas en transfert pour cette resa !
						$query = "SELECT count(1) FROM transferts WHERE origine_comp=".$data['id_resa'];
						$no_aff=0;
						$tresult = mysql_query($query, $dbh);
						$nb_trans = mysql_result($tresult, 0);
						if ($nb_trans!=0) {
							//on a un transfert en cours
							$situation = "<strong>" . $msg["transferts_circ_resa_lib_en_transfert"] . "</strong>";
						} else {
							$query = "SELECT count(1) FROM exemplaires, docs_statut WHERE expl_statut=idstatut AND pret_flag=1";
							$query .= " AND expl_location<>".$f_loc;
							if ($resa_idnotice)  $query .= " AND expl_notice=".$resa_idnotice;
								elseif ($resa_idbulletin) $query .= " AND expl_bulletin=".$resa_idbulletin;
							$tresult = mysql_query($query, $dbh);
							$total_ex = mysql_result($tresult, 0);
							
							if ($total_ex!=0) { 
								//on en a au moins un ailleurs!
								// sont-il déjà prêtés ou réservé
								$query = "SELECT count(1) FROM exemplaires, docs_statut
								 WHERE expl_statut=idstatut AND pret_flag=1";
								$query .= " AND expl_location<>".$f_loc;
								if ($resa_idnotice)  $query .= " AND expl_notice=".$resa_idnotice;
								elseif ($resa_idbulletin) $query .= " AND expl_bulletin=".$resa_idbulletin;		
								$query .= " and expl_id not in(select 	pret_idexpl from pret, exemplaires where pret_idexpl=expl_id ";
								if ($resa_idnotice)  $query .= " AND expl_notice=".$resa_idnotice;
								elseif ($resa_idbulletin) $query .= " AND expl_bulletin=".$resa_idbulletin;		
								$query .= ")";
								$query .= " and expl_cb not in(select resa_cb from resa, exemplaires where resa_cb=expl_cb ";
								if ($resa_idnotice)  $query .= " AND expl_notice=".$resa_idnotice;
								elseif ($resa_idbulletin) $query .= " AND expl_bulletin=".$resa_idbulletin;		
								$query .= ")";
										
								$tresult = mysql_query($query, $dbh);
								$nb_trans = mysql_result($tresult, 0);
								if (!$nb_trans) {
									$situation = $msg["resa_no_expl"];
								} else
									$lien_transfert = true;
							}	
						}						
					}	
				}
			}
		} else 
			$situation='';
		if(!$no_aff || ($idnotice || $idbulletin)) {
			// on affiche les résultats 
			if ($odd_even==0) {
				$aff_final .= "\n<tr class='odd' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='odd'\">";
				$odd_even=1;
			} else if ($odd_even==1) {
				$aff_final .= "\n<tr class='even' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='even'\">";
				$odd_even=0;
			}	
			//$type_doc_aff=" [".$tdoc->table[$data['typdoc']]."] ";
			$type_doc_aff= "alt='".htmlentities($tdoc->table[$data['typdoc']],ENT_QUOTES, $charset)."' title='".htmlentities($tdoc->table[$data['typdoc']],ENT_QUOTES, $charset)."' "; 
			if (SESSrights & CATALOGAGE_AUTH) {
				if ($resa_idnotice) $link = "<a href='./catalog.php?categ=isbd&id=".$resa_idnotice."' $type_doc_aff>".$data['tit']."</a>";
				elseif ($resa_idbulletin) $link = "<a href='./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=".$resa_idbulletin."' $type_doc_aff>".$data['tit']."</a>";
			} else $link = $data['tit'];
			if (!$idnotice && !$idbulletin) $aff_final .= "<td><b>$link</b></td>";    
			if (!$idempr) {
				if (SESSrights & CIRCULATION_AUTH) $aff_final .= "<td><a href=\"./circ.php?categ=pret&form_cb=".rawurlencode($data['empr_cb'])."\">".$data['empr_nom'].", ".$data['empr_prenom']."</a></td>"; 
				else $aff_final .= "<td>".$data['empr_nom'].", ".$data['empr_prenom']."</td>"; 
				if ($pmb_lecteurs_localises) $aff_final .= "<td>".$data['location_libelle']."</td>"; 
			}
			$aff_final .= "<td><center>".$rank."</center></td>"; 
			$aff_final .= "<td><center>".$data['aff_resa_date']."</center></td>"; 
			$aff_final .= "<td><center>".$situation."</center></td>"; 
			$aff_final .= "<td><center>".$data['aff_resa_date_fin']."</center></td>"; 
			
			// gestion du formulaire de validation/suppression
			switch ($info_gestion) {
				case GESTION_INFO_GESTION:
					$aff_final .= "\n<td style='text-align:center;'>";
					if ($data['resa_cb']) $aff_final .= "<font color='red'><b>X</b></font>" ; 
					else $aff_final .= "&nbsp;" ;
					$aff_final .= "</td>\n" ;
					$aff_final .= "\n<td style='text-align:center;'>";
					if ($data['resa_confirmee']) $aff_final .= "<font color='red'><b>X</b></font>" ; 
					else $aff_final .= "&nbsp;" ;
					$aff_final .= "</td>";
					if ($pmb_transferts_actif=="1") {
						$loc_retrait = resa_loc_retrait($data["id_resa"]);
						$rqt = "SELECT location_libelle FROM docs_location WHERE idlocation='".$loc_retrait."'";
						$libloc = @mysql_result(mysql_query($rqt),0);
						$aff_final .= "<td>".$libloc."</td>";
					}
					$aff_final .= "<td style='text-align:center;'><input type='checkbox' name='suppr_id_resa[]' value='".$data['id_resa']."' id='suppr_resa' /></td>" ;
					if ($pmb_transferts_actif=="1") {
						if ($lien_transfert) 
							$aff_final .= "<td><a href='#' onclick=\"choisiExpl(this);\" id_resa=\"".$data['id_resa']."\" idnotice=\"$resa_idnotice\" idbul=\"$resa_idbulletin\" loc=\"$f_loc\" alt=\"".$msg["transferts_circ_resa_lib_choix_expl"]."\" title=\"".$msg["transferts_circ_resa_lib_choix_expl"]."\">".
											"<img src='./images/peb_in.png'></a></td>";
						else
							$aff_final .= "<td>&nbsp;</td>";
					}
					break;
				case LECTEUR_INFO_GESTION:
					$aff_final .= "\n<td style='text-align:center;'>";
					if ($data['resa_confirmee']) $aff_final .= "<font color='red'><b>X</b></font>" ; else $aff_final .= "&nbsp;" ;
					$aff_final .= "</td>" ;
					if ($pmb_transferts_actif=="1") {
						if (($transferts_choix_lieu_opac=="1")&&($data['aff_resa_date_fin']=="")) {
							//choix du lieu de retrait
							$rqt = "SELECT idlocation, location_libelle FROM docs_location ORDER BY location_libelle";
							$res_loc = mysql_query($rqt);
							$liste_loc = "";
							while($value = mysql_fetch_object($res_loc)) {
								$liste_loc .= "<option value='".$value->idlocation."'";
								if ($value->idlocation==$data['resa_loc_retrait'])
									$liste_loc .= " selected";
								$liste_loc .= ">" . $value->location_libelle . "</option>";
							}						
							$aff_final .= str_replace("!!liste_loc!!",$liste_loc,"<td><select onchange=\"chgLocRetrait(" . $data['id_resa'] . ", this.options[this.selectedIndex].value)\">!!liste_loc!!</select></td>"); 
						} else {
							//on affiche le lieu de retrait
							$loc_retrait = resa_loc_retrait($data['id_resa']);
							$rqt = "SELECT location_libelle FROM docs_location WHERE idlocation='".$loc_retrait."'";
							$res = mysql_query($rqt);
							if (mysql_num_rows($res))
								$libloc = mysql_result($res,0);
							$aff_final .= "<td>".$libloc."</td>";
						}
					}
					$aff_final .= "\n<td style='text-align:center;'>";
					$aff_final .= "<input type='button' class='bouton' name='suppr_resa' value='".$msg['raz']."' id='suppr_resa' ";
					$aff_final .= "onClick=\"document.location='./circ.php?categ=pret&sub=suppr_resa_from_fiche&action=suppr_resa&suppr_id_resa[]=".$data['id_resa']."&id_empr=$idempr'\" />" ;
					$aff_final .= "</td>";
					break;
				default:
				case NO_INFO_GESTION:
					break;
				}
			$aff_final .= "</tr>\n";
		}	
	} //fin du while
	
	$aff_final .= "</table>";
	
	switch ($info_gestion) {
		case GESTION_INFO_GESTION:
			$aff_final .= "<table style='background:none;border-right:0px;border-left:0px;border-bottom:0px;border-top:0px;'>
					<tr><td style='background:none;border-right:0px;border-left:0px;border-bottom:0px;border-top:0px;text-align:left;'>";
			if ($pdflettreresa_priorite_email_manuel!=3) $aff_final .= "<input type='hidden' name='impression_confirmation' value='0' /><input type='button' class='bouton' value='".$msg[resa_impression_confirmation]."' onclick=\"this.form.action.value='suppr_resa'; this.form.impression_confirmation.value=1; this.form.submit();\"/></td>";
			$aff_final .= "<input type='hidden' name='action' value='' />";
			$aff_final .= "<td style='background:none;border-right:0px;border-left:0px;border-bottom:0px;border-top:0px;text-align:left;'><input type='button' class='bouton' value='".$msg[resa_valider_suppression]."'  onclick=\"if(confirm_delete()){this.form.action.value='suppr_resa'; this.form.submit();}\" />";
					
			$aff_final .= "</td><td style='background:none;border-right:0px;border-left:0px;border-bottom:0px;border-top:0px;text-align:right;'>";
			$aff_final .= "<input type='button' class='bouton' onClick=\"setCheckboxes('check_resa', 'suppr_id_resa', true); return false;\" value='".$msg['resa_tout_cocher']."' />";
			$aff_final .= "</td></tr></table></form>" ;
			break;
		case LECTEUR_INFO_GESTION:
			break;
		default:
		case NO_INFO_GESTION:
			break;
	}
	
	mysql_free_result ($req); 
	
	return $aff_final ;
}

// cette fonction va retourner un tableau des résa pas traitées
function resa_list_resa_a_traiter () {
	/* Traitement :
		chercher toutes les réservations non traitées (resa_cb ="")
		construire le tableau avec le titre de l'ouvrage, le nom du réservataire et son rang
	*/
	global $dbh ;
	global $msg;
	global $pmb_lecteurs_localises;
	
	$tableau_final=array();	
	
	$order="tit, resa_idnotice, resa_idbulletin, resa_date" ;
	
	$sql="SELECT resa_idnotice, resa_idbulletin, resa_date, resa_date_fin, resa_cb, resa_idempr, empr_nom, empr_prenom, empr_cb, empr_location, ";
	$sql.=" trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit, id_resa, ";
	$sql.=" IF(resa_date_fin>=sysdate() or resa_date_fin='0000-00-00',0,1) as perimee, date_format(resa_date_debut, '".$msg["format_date"]."') as aff_resa_date_debut, if(resa_date_fin='0000-00-00', '', date_format(resa_date_fin, '".$msg["format_date"]."')) as aff_resa_date_fin, date_format(resa_date, '".$msg["format_date"]."') as aff_resa_date " ;
	$sql.=" FROM (((resa LEFT JOIN notices AS notices_m ON resa_idnotice = notices_m.notice_id ) ";
	$sql.=" LEFT JOIN bulletins ON resa_idbulletin = bulletins.bulletin_id) ";
	$sql.=" LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), empr ";
	$sql.=" where resa_idempr=id_empr and (resa_cb='' or resa_cb is null) ";
	$sql.=" group by resa_idnotice, resa_idbulletin, resa_idempr ";
	$sql.=" order by ".$order ;
	
	$req = mysql_query($sql) or die("Erreur SQL !<br />".$sql."<br />".mysql_error()); 
	
	if (!mysql_num_rows($req)) return $tableau_final;
	
	while ($data = mysql_fetch_array($req)) {
		if($pmb_lecteurs_localises){
			$requete = "SELECT location_libelle as empr_loc_libelle FROM docs_location WHERE idlocation= '".$data['empr_location']."' "; 
			$result = @mysql_query($requete, $dbh);
			$res_empr = mysql_fetch_object($result);
			
		}
		
		$rank = recupere_rang($data['resa_idempr'], $data['resa_idnotice'], $data['resa_idbulletin']) ;
		$tableau_final[] = array(
					'resa_tit' => $data['tit'],
					'resa_idnotice' => $data['resa_idnotice'],
					'resa_idbulletin' => $data['resa_idbulletin'],
					'resa_idempr' => $data['resa_idempr'],
					'resa_empr' => $data['empr_nom']." ".$data['empr_prenom'],
					'resa_empr_loc_libelle' => $res_empr->empr_loc_libelle,
					'rank' => $rank ) ;
	} // fin while
	
	mysql_free_result ($req); 
	
	return $tableau_final ;
}


function resa_ranger_list () {
	
	global $dbh ;
	global $msg;
	global $begin_result_liste;
	global $end_result_liste;
	
	$sql="SELECT resa_cb, expl_id from resa_ranger left join exemplaires on resa_cb=expl_cb ";
	$res = mysql_query($sql, $dbh) ;
	while ($ranger = mysql_fetch_object($res)) {
		if ($ranger->expl_id) {
			if($stuff = get_expl_info($ranger->expl_id)) {
				$stuff = check_pret($stuff);
				$aff_final .=  print_info($stuff,0,0,0);
			} else {
				$aff_final .=  "<strong>".$ranger->resa_cb."&nbsp;: ${msg[395]}</strong>";
			}
		} else {
			$aff_final .=  "<strong>".$ranger->resa_cb."&nbsp;: ${msg[395]}</strong>";
		}
	}
	if ($aff_final) return $begin_result_liste.$aff_final.$end_result_liste;
		else return $msg['resa_liste_docranger_nodoc'] ;
}

// permet de savoir si un CB expl est déjà affecté à une résa
function verif_cb_utilise ($cb) {
	global $dbh ;
	$rqt = "select id_resa from resa where resa_cb='".addslashes($cb)."' ";
	$res = mysql_query ($rqt, $dbh) ;
	$nb=mysql_num_rows($res) ;
	if (!$nb) return 0 ;
	$obj=mysql_fetch_object($res) ;
	return $obj->id_resa ;
}
	
// Ancien prototype générant une erreur sur une version PHP:
// function get_loc_resa_transfert ($cb,&$id_resa=0) {
// Cette fonction ne semble plus utilisée	
function get_loc_resa_transfert ($cb,&$id_resa) {	
	global $dbh;
	global $pmb_utiliser_calendrier, $deflt2docs_location,$pmb_location_reservation,$pmb_transferts_actif;
	
	// chercher s'il s'agit d'une notice ou d'un bulletin
	$rqt = "SELECT expl_notice, expl_bulletin FROM exemplaires WHERE expl_cb='".$cb."' ";
	$res = mysql_query ($rqt, $dbh) ;
	$nb=mysql_num_rows($res) ;
	if (!$nb) return 0 ;
	
	$obj=mysql_fetch_object($res) ;
	
	if ($id_resa==0)
		// chercher le premier (par ordre de rang, donc de date de début de résa, non validé
		$rqt = 	"SELECT id_resa, resa_idempr,resa_loc_retrait 
				FROM resa 
				WHERE resa_idnotice='".$obj->expl_notice."' 
					AND resa_idbulletin='".$obj->expl_bulletin."' 
					AND resa_cb='' 
					AND resa_date_fin='0000-00-00' 
				ORDER BY resa_date ";
	else
		//on sait de qu'elle resa on parle .....
		$rqt = 	"SELECT id_resa, resa_idempr,resa_loc_retrait FROM resa WHERE id_resa='".$id_resa."'";
			
	$res = mysql_query ($rqt, $dbh) ;
	
	if (!mysql_num_rows($res)) return 0 ;
	
	$obj_resa=mysql_fetch_object($res) ;
	
	
	$loc_retait=resa_loc_retrait($obj_resa->id_resa);	
	$id_resa= $obj_resa->id_resa ;
	if ($loc_retait!=$deflt2docs_location) return $loc_retait;
	
	
	$nb_days = get_time($obj_resa->resa_idempr,$obj->expl_notice,$obj->expl_bulletin) ;
	
	$rqt_date = "select date_add(sysdate(), INTERVAL '$nb_days' DAY) as date_fin ";
	$resultatdate = mysql_query($rqt_date);
	$res = mysql_fetch_object($resultatdate) ;
	$date_fin = $res->date_fin ;
	
	if ($pmb_utiliser_calendrier) {
		$rqt_date = "select date_ouverture from ouvertures where ouvert=1 and num_location=$deflt2docs_location and to_days(date_ouverture)>=to_days('$date_fin') order by date_ouverture ";
		$resultatdate=mysql_query($rqt_date);
		$res=@mysql_fetch_object($resultatdate) ;
		if ($res->date_ouverture) $date_fin=$res->date_ouverture ;
	}
	
	// mettre resa_cb à jour pour cette resa
	$rqt = "update resa set resa_cb='".$cb."' " ;
	$rqt .= ", resa_date_debut=sysdate() " ;
	$rqt .= ", resa_date_fin='$date_fin' and resa_loc_retrait='$deflt2docs_location' ";
	$rqt .= " where id_resa='".$obj_resa->id_resa."' ";
	$res = mysql_query ($rqt, $dbh) or die(mysql_error()." <br />$rqt");
	
	$id_resa= $obj_resa->id_resa ;
	return $loc_retait;
}

function affecte_cb ($cb,$id_resa=0) {
	global $dbh;
	global $pmb_utiliser_calendrier, $pmb_location_reservation,$pmb_transferts_actif,$transferts_choix_lieu_opac,$deflt_docs_location,$pmb_location_reservation;
	
	// chercher s'il s'agit d'une notice ou d'un bulletin
	$rqt = "SELECT expl_notice, expl_bulletin FROM exemplaires WHERE expl_cb='".$cb."' ";
	$res = mysql_query ($rqt, $dbh) ;
	$nb=mysql_num_rows($res) ;
	if (!$nb) return 0 ;
	
	$obj=mysql_fetch_object($res) ;
	
	if ($id_resa==0) {
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
					$where= " AND expl_cb='$cb' and expl_location=" . $deflt_docs_location;	
				break;	
				default:
					//retrait de la resa sur lieu lecteur
					if(!$pmb_location_reservation)$from= " ,empr ";
					$where= " AND resa_idempr=id_empr and empr_location=" . $deflt_docs_location;
				break;						
			} //switch $transferts_choix_lieu_opac			
		}
		if($pmb_location_reservation) {			
			$from_loc_resa= " ,empr, resa_loc ";		
			$sql_loc_resa=" and resa_idempr=id_empr and empr_location=resa_emprloc and resa_loc='$deflt_docs_location' "; 				
		}

		// chercher le premier (par ordre de rang, donc de date de début de résa, non validé
		$rqt = 	"SELECT id_resa, resa_idempr,resa_loc_retrait 
				FROM resa $from $from_loc_resa
				WHERE resa_idnotice='".$obj->expl_notice."' 
					AND resa_idbulletin='".$obj->expl_bulletin."' 
					AND resa_cb='' 
					AND resa_date_fin='0000-00-00' 
					$where
					$sql_loc_resa
				ORDER BY resa_date ";
	} else {
		//on sait de qu'elle resa on parle .....
		$rqt = 	"SELECT id_resa, resa_idempr,resa_loc_retrait FROM resa WHERE id_resa='".$id_resa."'";
	}		
	$res = mysql_query ($rqt, $dbh) ;
	
	if (!mysql_num_rows($res)) return 0 ;
	
	$obj_resa=mysql_fetch_object($res) ;
	/*
	$rqt_loc_retrait="";
	if($pmb_transferts_actif) {
		$rqt = "SELECT empr_location FROM resa INNER JOIN empr ON resa_idempr = id_empr WHERE id_resa=".$obj_resa->id_resa;
		$res = mysql_query($rqt);
		$empr_location = mysql_result($res,0) ;
		if($obj_resa->resa_loc_retrait==$deflt_docs_location || $empr_location==$deflt_docs_location) {
			$rqt_loc_retrait.= ", resa_loc_retrait='$deflt2docs_location' " ;
		} else return 0 ;
	}
		*/
	$nb_days = get_time($obj_resa->resa_idempr,$obj->expl_notice,$obj->expl_bulletin) ;
	
	$rqt_date = "select date_add(sysdate(), INTERVAL '$nb_days' DAY) as date_fin ";
	$resultatdate = mysql_query($rqt_date);
	$res = mysql_fetch_object($resultatdate) ;
	$date_fin = $res->date_fin ;
	
	if ($pmb_utiliser_calendrier) {
		$rqt_date = "select date_ouverture from ouvertures where ouvert=1 and num_location=$deflt_docs_location and to_days(date_ouverture)>=to_days('$date_fin') order by date_ouverture ";
		$resultatdate=mysql_query($rqt_date);
		$res=@mysql_fetch_object($resultatdate) ;
		if ($res->date_ouverture) $date_fin=$res->date_ouverture ;
	}
	
	// mettre resa_cb à jour pour cette resa
	$rqt = "update resa set resa_cb='".$cb."' " ;
	$rqt .= ", resa_date_debut=sysdate() " ;
	$rqt .= ", resa_date_fin='$date_fin', resa_loc_retrait='$deflt_docs_location' ";
	$rqt .= " where id_resa='".$obj_resa->id_resa."' ";
	$res = mysql_query ($rqt, $dbh) or die(mysql_error()." <br />$rqt");
	
	$rqt = "delete from resa_ranger where resa_cb='".$cb."' ";
	$res = mysql_query ($rqt, $dbh);
	return $obj_resa->id_resa ;
}

function resa_transfert($id_resa,$cb) {
	
	global $transferts_choix_lieu_opac, $transferts_site_fixe;
	global $deflt_docs_location;

	$res_trans = 0; 

	//pour la gestion des transferts
	$trans = new transfert();
	
	//les informations de l'exemplaire
	$rqt = "SELECT expl_location FROM exemplaires WHERE expl_cb='" . $cb ."'";
	$res = mysql_query($rqt);
	$expl_loc = mysql_result($res,0) ;
	
	switch ($transferts_choix_lieu_opac) {	
		case "1":
			//retrait de la resa sur lieu choisi par le lecteur
			$rqt = "SELECT resa_loc_retrait FROM resa WHERE id_resa='".$id_resa."'";
			$res = mysql_query($rqt);
			$loc_retrait = mysql_result($res,0);
			
			if ($loc_retrait != $expl_loc) {
				//l'exemplaire n'est pas sur le bon site
				//on genere un transfert du site de l'exemplaire vers le site de retrait
				$trans->transfert_pour_resa($cb, $loc_retrait, $id_resa);
				$res_trans = $loc_retrait;
			}
			
			break;
			
		case "2":
			//retrait de la resa sur lieu fixé
			if ($transferts_site_fixe != $expl_loc) {
				//l'exemplaire n'est pas sur le bon site
				//on genere un transfert du site de l'exemplaire vers le site fixé
				$trans->transfert_pour_resa($cb, $transferts_site_fixe, $id_resa);
				$res_trans = $transferts_site_fixe;
			}
			break;
			
		case "3":
			//retrait de la resa sur lieu exemplaire
			//==>on fait rien !
		break;				
		default:
			//retrait de la resa sur lieu lecteur
			//on recupere la localisation de l'emprunteur
			$rqt = "SELECT empr_location FROM resa INNER JOIN empr ON resa_idempr = id_empr WHERE id_resa=".$id_resa;
			$res = mysql_query($rqt);
			$empr_location = mysql_result($res,0) ;

			if ($empr_location != $expl_loc) {
				//l'exemplaire n'est pas sur le bon site
				//on genere un transfert du site de l'exemplaire vers le site du lecteur
				$trans->transfert_pour_resa($cb, $empr_location, $id_resa);
				$res_trans = $empr_location;
			}		
		break;
	
	}
	
	return $res_trans;
}

function resa_loc_retrait($id_resa) {
	global $transferts_choix_lieu_opac, $transferts_site_fixe;

	$res_trans = 0; 
	
	switch ($transferts_choix_lieu_opac) {
		case "1":
			//retrait de la resa sur lieu choisi par le lecteur
			$rqt = "SELECT resa_loc_retrait FROM resa WHERE id_resa='".$id_resa."'";
			$res = mysql_query($rqt);
			$res_trans = mysql_result($res,0);
		break;			
		case "2":
			//retrait de la resa sur lieu fixé
			$res_trans = $transferts_site_fixe;
		break;			
		case "3":
			//retrait de la resa sur lieu exemplaire
			//==>on fait rien !
		break;
		default:
			//retrait de la resa sur lieu lecteur
			//on recupere la localisation de l'emprunteur
			$rqt = "SELECT empr_location FROM resa INNER JOIN empr ON resa_idempr = id_empr WHERE id_resa='".$id_resa."'";
			$res = mysql_query($rqt);
			$res_trans = mysql_result($res,0) ;
		break;		
	
	}
	
	return $res_trans;

}

function desaffecte_cb ($cb,$id_resa=0) {
	global $dbh ;
	if ($id_resa!=0)
		$rqt = "UPDATE resa SET resa_cb='', resa_date_debut='0000-00-00', resa_date_fin='0000-00-00' WHERE resa_cb='".$cb."' AND id_resa='".$id_resa."'";
	else
		$rqt = "UPDATE resa SET resa_cb='', resa_date_debut='0000-00-00', resa_date_fin='0000-00-00' WHERE resa_cb='".$cb."' ";
	$res = mysql_query ($rqt, $dbh) ;
	return mysql_affected_rows($dbh) ;
}

function recupere_cb ($id) {
	global $dbh ;
	$rqt = "select resa_cb from resa where id_resa='".$id."' ";
	$res = mysql_query ($rqt, $dbh) ;
	$nb=mysql_num_rows($res) ;
	if (!$nb) return "" ;
	$obj=mysql_fetch_object($res) ;
	return $obj->resa_cb ;
}

//   calcul du rang d'un emprunteur sur une réservation
function recupere_rang($id_empr, $id_notice, $id_bulletin,$loc=0) {
	global $dbh;
	global $pmb_lecteurs_localises, $pmb_location_reservation,$deflt_docs_location;
	$rank = 1;
	if (!$id_notice) $id_notice=0;
	if (!$id_bulletin) $id_bulletin=0 ;
	if($pmb_lecteurs_localises){	
		if($pmb_location_reservation && $loc) {			
			$query = "SELECT resa_idempr 
				FROM resa, empr, resa_loc  
				WHERE 
				resa_idnotice='".$id_notice."' AND resa_idbulletin='".$id_bulletin."'  
				and id_empr=resa_idempr
				and empr_location=resa_emprloc and resa_loc=$loc 
				ORDER BY resa_date";
		} else {
			$query = "SELECT resa_idempr FROM resa WHERE resa_idnotice='".$id_notice."' AND resa_idbulletin='".$id_bulletin."' ORDER BY resa_date";			
		}	
	} else{
		$query = "SELECT resa_idempr FROM resa WHERE resa_idnotice='".$id_notice."' AND resa_idbulletin='".$id_bulletin."' ORDER BY resa_date";		
	}
	$result = mysql_query($query, $dbh);
	while($resa=mysql_fetch_object($result)) {
		if($resa->resa_idempr == $id_empr) break;
		$rank++;
	}
	return $rank;
}

//Récupération de la durée de réservation pour une notice ou un bulletin et un emprunteur
function get_time($id_empr,$id_notice,$id_bulletin) {
	global $pmb_quotas_avances;
	
	//Si les quotas avancés sont actifs
	if ($pmb_quotas_avances) {
		$struct=array();
		if ($id_notice) {
			$struct["NOTI"]=$id_notice;
			$quota_type="BOOK_TIME_QUOTA";
		} else {
			$struct["BULL"]=$id_bulletin;
			$quota_type="BOOK_TIME_SERIAL_QUOTA";
		}
		$struct["READER"]=$id_empr;
		$qt=new quota($quota_type);
		$t=$qt->get_quota_value($struct);
		if ($t==-1) $t=0;
	} else {
		//Sinon je regarde la durée de réservation la plus défavorable par type de document
		if ($id_notice)
			$requete="select min(duree_resa) from docs_type, exemplaires where expl_notice=$id_notice and expl_typdoc=idtyp_doc";
		else
			$requete="select min(duree_resa) from docs_type, exemplaires where expl_bulletin=$id_bulletin and expl_typdoc=idtyp_doc";
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) $t=mysql_result($resultat,0,0); else $t=0;
	}
	return $t;
}

function check_quota_resa($id_empr,$id_notice,$id_bulletin) {
	global $dbh;
	global $msg;
	global $pmb_quotas_avances;
	global $_quotas_elements_;
	global $pmb_resa_quota_pret_depasse;
	
	//Initialisation résultat
	$error=array();
	$error["ERROR"]=false;
	
	//Si les quotas avancés sont autorisés
	if ($pmb_quotas_avances) {
		$struct=array();
		//Quota de notice ou bulletin ?
		if ($id_notice) {
			$quota_type="BOOK_NMBR_QUOTA";
			$struct["NOTI"]=$id_notice;
			$elt_name="NOTICETYPE";
		} else {
			$quota_type="BOOK_NMBR_SERIAL_QUOTA";
			$struct["BULL"]=$id_bulletin;
			$elt_name="BULLETINTYPE";
		}
		//Initialisation du quota
		$qt=new quota($quota_type);
		$struct["READER"]=$id_empr;
		
		//Si résa bloquée en cas de dépassement de quota de prêt
		if (!$pmb_resa_quota_pret_depasse) {
			//Le quota de prêt est-il atteint pour cette notice ou bulletin
			//Récupération de l'élément indirect à tester
			$elt=$qt->get_element_by_name($elt_name);
			//Récupération de l'exemplaire le plus défavorable associé à la réservation
			$object_id=$qt->get_object_for_indirect_element($_quotas_elements_[$elt],$struct);
			//Initialisation du quota de prêt
			$qt_pret=new quota("LEND_NMBR_QUOTA");
		
			$struct_pret["READER"]=$id_empr;
			$struct_pret["EXPL"]=$object_id;
			$r=$qt_pret->check_quota($struct_pret);
		} else $r=false;
		
		//Si quota de prêt non violé alors on regarde les quotas de réservation
		if (!$r) {
			//Vérification
			$r=$qt->check_quota($struct);
			//Si quota violé
			if ($r) {
				$error["ERROR"]=true;
				//Erreur
				$error["MESSAGE"]=$qt->error_message;
				//Peut-on forcer ou pas la résa
				$error["FORCE"] = $qt->force;
				return $error;
			}
		} else {
			$error["ERROR"]=true;
			//Erreur
			$error["MESSAGE"]=$qt_pret->error_message."<br />".$msg["resa_quota_pret_error"];
			//Peut-on forcer ou pas la résa		
			$r_force=$qt->check_quota($struct);
			if($r_force){
				$error["FORCE"] = $qt->force;				
			}
			else $error["FORCE"] = 0;			
			return $error;
		}
		return $error;
	} else return $error;
}

// retourne un tableau constitué des exemplaires disponibles pour une résa donnée
function expl_dispo ($no_notice=0, $no_bulletin=0) {
	global $dbh;
	global $pmb_lecteurs_localises, $pmb_location_reservation,$deflt_docs_location;
	
	if($pmb_location_reservation) {			
		$sql_loc_resa.=" and exemplaires.expl_location=resa_emprloc and resa_loc='".$deflt_docs_location."' ";
		$sql_loc_resa_from=", resa_loc ";
	}
	if ($pmb_lecteurs_localises) {
		$sql_localisation=", case  when exemplaires.expl_location = $deflt_docs_location then 1 else 0 END as loc_ici ";
		$sql_order_localisation=" loc_ici desc, ";
	}	
	// on récupère les données des exemplaires
	$requete = "SELECT expl_id, expl_cb, expl_cote, expl_notice, expl_bulletin, pret_retour, location_libelle, section_libelle, statut_libelle 	$sql_localisation ";
	$requete .= " FROM docs_location, docs_section, docs_statut $sql_loc_resa_from , ";
	$requete .= " exemplaires LEFT JOIN pret ON exemplaires.expl_id=pret.pret_idexpl";
	$requete .= " WHERE expl_notice='$no_notice' and expl_bulletin='$no_bulletin' ";
	$requete .= " AND docs_statut.pret_flag=1";
	$requete .= " AND exemplaires.expl_location=docs_location.idlocation";
	$requete .= " AND exemplaires.expl_section=docs_section.idsection ";
	$requete .= " AND exemplaires.expl_statut=docs_statut.idstatut $sql_loc_resa";
	$requete .= " order by $sql_order_localisation location_libelle, section_libelle, expl_cote ";
	
	$result = mysql_query($requete, $dbh);	

	while($expl = mysql_fetch_object($result)) {
		if(!$expl->pret_retour && !verif_cb_utilise($expl->expl_cb))  
			if (!$pmb_lecteurs_localises) $expl->loc_ici =1;
			$tableau[] = array (
				'expl_id' => $expl->expl_id,
				'expl_cb' => $expl->expl_cb,
				'expl_notice' => $expl->expl_notice,					
				'expl_bulletin' => $expl->expl_bulletin,					
				'expl_cote' => $expl->expl_cote,
				'location' => $expl->location_libelle,
				'section' => $expl->section_libelle,
				'statut' => $expl->statut_libelle ,
				'loc_ici' => $expl->loc_ici ) ;			
	}
	return $tableau ;
}
