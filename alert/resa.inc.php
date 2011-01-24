<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: resa.inc.php,v 1.30 2010-12-02 11:19:26 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$temp_aff = resa_a_traiter() . resa_a_ranger() . resa_depassees_a_traiter();

if ($temp_aff) $aff_alerte .= "<ul>".$msg['resa_menu_alert'].$temp_aff."</ul>" ;

function resa_a_traiter () {
	global $dbh ;
	global $msg;
	global $pmb_transferts_actif,$transferts_choix_lieu_opac,$deflt_docs_location, $pmb_location_reservation,$transferts_site_fixe,$pmb_lecteurs_localises;
	
	$sql="SELECT resa_idnotice, resa_idbulletin FROM resa, exemplaires, docs_statut  WHERE (resa_cb is null OR resa_cb='') 
	and resa_idnotice=expl_notice and resa_idbulletin=expl_bulletin 
	and expl_statut=idstatut AND pret_flag=1	
	limit 1";
	
	if($pmb_lecteurs_localises && $deflt_docs_location){
		$sql="SELECT resa_idnotice, resa_idbulletin FROM resa, exemplaires, docs_statut  WHERE (resa_cb is null OR resa_cb='') 
		and resa_idnotice=expl_notice and resa_idbulletin=expl_bulletin  
		and expl_location='".$deflt_docs_location."'
		and expl_statut=idstatut AND pret_flag=1	
		limit 1";		
	}	
	// respecter les droits de réservation du lecteur 
	if($pmb_location_reservation)
		$sql="SELECT resa_idnotice, resa_idbulletin FROM resa, empr, resa_loc, exemplaires , docs_statut WHERE 
		resa_idnotice=expl_notice and resa_idbulletin=expl_bulletin 
		and expl_location='".$deflt_docs_location."' 
		and	expl_statut=idstatut AND pret_flag=1 
		and	resa_idempr = id_empr AND (resa_cb is null OR resa_cb='') 
		and empr_location=resa_emprloc and resa_loc='$deflt_docs_location' 
		limit 1";
	
	if ($pmb_transferts_actif=="1") {
		switch ($transferts_choix_lieu_opac) {
			case "1":
				//retrait de la resa sur lieu choisi par le lecteur
				$sql="SELECT resa_idnotice, resa_idbulletin FROM resa, empr WHERE resa_idempr = id_empr AND (resa_cb is null OR resa_cb='') AND resa_loc_retrait='".$deflt_docs_location."' limit 1";
			break;		
			case "2":
				//retrait de la resa sur lieu fixé
				if ($deflt_docs_location==$transferts_site_fixe)
					$sql="SELECT resa_idnotice, resa_idbulletin FROM resa WHERE (resa_cb is null OR resa_cb='') limit 1";
				else return "";	
				 	
			break;		
			case "3":
				//retrait de la resa sur lieu exemplaire
				// respecter les droits de réservation du lecteur 
				if($pmb_location_reservation)
					$sql = "select resa_idnotice, resa_idbulletin from resa, exemplaires,empr, resa_loc where resa_idempr = id_empr AND (resa_cb is null OR resa_cb='') and empr_location=resa_emprloc and resa_loc='$deflt_docs_location' and 
							resa_idnotice=expl_notice and resa_idbulletin=expl_bulletin and expl_location=resa_loc limit 1";
				else 
					$sql = "select resa_idnotice, resa_idbulletin from resa, exemplaires,empr where resa_idempr = id_empr AND (resa_cb is null OR resa_cb='') and 
							resa_idnotice=expl_notice and resa_idbulletin=expl_bulletin and expl_location='".$deflt_docs_location."' limit 1";
			break;		
			default:
				//retrait de la resa sur lieu lecteur
				$sql="SELECT resa_idnotice, resa_idbulletin FROM resa, empr WHERE resa_idempr = id_empr AND (resa_cb is null OR resa_cb='') AND empr_location='".$deflt_docs_location."' limit 1";
			break;			
		}
	
	}
//print "$sql";exit;
	$req = mysql_query($sql,$dbh) or die ("Erreur SQL !<br />".$sql."<br />".mysql_error()); 
	if(mysql_num_rows($req)) 
		return "<li><a href='./circ.php?categ=listeresa&sub=encours' target='_parent'>".$msg['resa_menu_a_traiter']."</a></li>";
	else
		return "";
	
/*
	$sql.=" GROUP BY resa_idnotice, resa_idbulletin ";
	$sql.=" ORDER BY resa_idnotice, resa_idbulletin, resa_date " ;
	$req = mysql_query($sql) or die ("Erreur SQL !<br />".$sql."<br />".mysql_error()); 

	$nb_resa_a_faire = 0;
	while ($data = mysql_fetch_array($req)) {
		$resa_idnotice = $data['resa_idnotice'];
		$resa_idbulletin = $data['resa_idbulletin'];
		$resa_nombre = $data['nb_resa'];
		
		// on compte le nombre total d'exemplaires prêtables pour la notice
		$query = "SELECT count(1) FROM exemplaires, docs_statut WHERE expl_statut=idstatut AND pret_flag=1 $sql_expl_loc ";
		if ($resa_idnotice)  $query .= " AND expl_notice=".$resa_idnotice;
		elseif ($resa_idbulletin) $query .= " AND expl_bulletin=".$resa_idbulletin;
		$tresult = @mysql_query($query, $dbh);
		$total_ex = mysql_result($tresult, 0, 0);

		// on compte le nombre d'exemplaires sortis
		$query = "SELECT count(1) as qte FROM exemplaires , pret WHERE pret_idexpl=expl_id $sql_expl_loc ";
		if ($resa_idnotice) $query .= " and expl_notice=".$resa_idnotice;
		elseif ($resa_idbulletin) $query .= " and expl_bulletin=".$resa_idbulletin;
		
		$tresult = @mysql_query($query, $dbh);
		$total_sortis = mysql_result($tresult, 0, 0);
	
		// on compte le nombre d'exemplaires affectés aux résas
		if ($pmb_transferts_actif=="1"){
			$query = "select count(1) from resa, exemplaires where resa_idnotice=".$resa_idnotice." and resa_idbulletin=".$resa_idbulletin." and resa_cb!='' and resa_cb=expl_cb and expl_location='".$deflt_docs_location."'";
		}else {			
			$query = "select count(1) from resa where resa_idnotice=".$resa_idnotice." and resa_idbulletin=".$resa_idbulletin." and resa_cb!='' ";
		}	
		$tresult = @mysql_query($query, $dbh);
		$total_affectes = mysql_result($tresult, 0, 0);
		
		// on en déduit le nombre d'exemplaires disponibles
		$total_dispo = $total_ex - $total_sortis - $total_affectes ;
		// DEBUG echo "Not: $resa_idnotice Bull: $resa_idbulletin NbResa: $resa_nombre /dispo: $total_dispo = $total_ex - $total_sortis - $total_affectes <br />" ;
		// on a au moins UNE résa et au moins UN dispo :
		if ($total_dispo && $resa_nombre) {
			// un exemplaire est disponible pour cette resa
			$nb_resa_a_faire ++;
		}
	} 
	
	if ($nb_resa_a_faire)
		return "<li><a href='./circ.php?categ=listeresa&sub=encours' target='_parent'>".$msg['resa_menu_a_traiter']."</a></li>" ;
	else
		return "" ;
*/
}

function resa_a_ranger () {
	
	global $dbh ;
	global $msg,$deflt_docs_location;
	
	$sql="SELECT count(1) from resa_ranger,exemplaires where resa_cb=expl_cb and expl_location='$deflt_docs_location' limit 1 ";
	$res = mysql_query($sql, $dbh) ;
	if (mysql_result($res, 0, 0)) return "<li><a href='./circ.php?categ=listeresa&sub=docranger' target='_parent'>".$msg['resa_menu_a_ranger']."</a></li>" ;
	return "" ;
}

function resa_depassees_a_traiter () {
	global $dbh ;
	global $msg,$pmb_transferts_actif, $deflt_docs_location,$transferts_choix_lieu_opac;
		
	$sql="SELECT 1 FROM resa, empr WHERE resa_idempr = id_empr AND resa_date_fin < CURDATE() and resa_date_fin <>  '0000-00-00' ";
	if ($pmb_transferts_actif=="1") {
		switch ($transferts_choix_lieu_opac) {
			case "1":
				//retrait de la resa sur lieu choisi par le lecteur
				$sql .= " AND resa_loc_retrait='".$deflt_docs_location."' ";
			break;		
			case "2":
				//retrait de la resa sur lieu fixé
			break;		
			case "3":
				//retrait de la resa sur lieu exemplaire
			break;	
			default:
				//retrait de la resa sur lieu lecteur
				$sql .= " AND empr_location='".$deflt_docs_location."' ";
			break;					
		}
	
	}
	
	// comptage des résas dépassées 
	//$sql="SELECT 1 FROM resa where resa_date_fin < CURDATE() and resa_date_fin <> '0000-00-00' limit 1 ";
	$req = mysql_query($sql) or die ("Erreur SQL !<br />".$sql."<br />".mysql_error()); 
	if (!mysql_num_rows($req)) {
		return "" ;
	}
	return "<li><a href='./circ.php?categ=listeresa&sub=depassee' target='_parent'>".$msg['resa_menu_a_depassees']."</a></li>" ;

}
