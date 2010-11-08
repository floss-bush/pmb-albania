<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: mailing.inc.php,v 1.3 2009-05-16 11:20:27 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/amende.class.php");
require_once($class_path."/comptes.class.php");
require_once ("$include_path/notice_authors.inc.php");  
require_once($class_path."/serie.class.php");
require_once ("$class_path/author.class.php");  

// génère un pavé d'adresse de la bibliothèque, séparé par des \n faire nl2br pour mettre cela en HTML 
function m_biblio_info($short=0) {
	
	global $biblio_name, $biblio_logo, $biblio_adr1, $biblio_adr2, $biblio_cp, $biblio_town, $biblio_state, $biblio_country, $biblio_phone, $biblio_email, $biblio_website ;
	global $txt_biblio_info ;

	if ($short==1) {
		return $biblio_name;
	} else { 
		// afin de ne générer qu'une fois l'adr et compagnie 
		if (!$txt_biblio_info) {
			if ($biblio_adr1 != "") $biblio_name = $biblio_name."\n";
			if ($biblio_adr2 != "") $biblio_adr1 = $biblio_adr1."\n";
			if ($biblio_cp != "") $biblio_cp = $biblio_cp." ";
			if (($biblio_cp != "") || ($biblio_town != "")) $biblio_adr2 = $biblio_adr2."\n";
			if ($biblio_state != "") $biblio_state = $biblio_state." ";
			if (($biblio_state != "") || ($biblio_country != "")) $biblio_town = $biblio_town."\n";
			if ($biblio_phone != "") $biblio_phone = $biblio_phone."\n ";
			if ($biblio_email != "") $biblio_email = "@ : ".$biblio_email."\n ";
			if ($biblio_website != "") $biblio_website = "Web : ".$biblio_website."\n ";
			if (($biblio_phone != "") || ($biblio_email != "")) $biblio_country = $biblio_country."\n";
			$txt_biblio_info = $biblio_adr1.$biblio_adr2.$biblio_cp.$biblio_town.$biblio_state.$biblio_country.$biblio_phone.$biblio_email.$biblio_website ;
		}
		return $biblio_name.$txt_biblio_info;
	}
} /* fin biblio_info */

function m_lecteur_info($empr) {
	
	global $msg;
	
	$res_final=array();
	
	$requete = "SELECT group_concat(libelle_groupe SEPARATOR ', ') as_all_groupes, 1 as rien from groupe join empr_groupe on groupe_id=id_groupe WHERE lettre_rappel=1 and empr_id='".$empr->id_empr."' group by rien ";
	$lib_all_groupes=pmb_sql_value($requete);
	if ($lib_all_groupes) $lib_all_groupes="\n".$lib_all_groupes; 

	if ($empr->empr_prenom) $empr->empr_nom=$empr->empr_prenom." ".$empr->empr_nom; 
	$res_final[]=$empr->empr_nom;
	
	if ($empr->empr_adr2 != "") $empr->empr_adr1 = $empr->empr_adr1."\n" ;
	if (($empr->empr_cp != "") || ($empr->empr_ville != "")) $empr->empr_adr2 = $empr->empr_adr2."\n" ;
	$adr = $empr->empr_adr1.$empr->empr_adr2.$empr->empr_cp." ".$empr->empr_ville ;
	if ($empr->empr_pays != "") $adr = $adr."\n".$empr->empr_pays ;
	$res_final[]=$adr;
	
	if ($empr->empr_tel1 != "") {
		$tel = $tel.$msg['fpdf_tel1']." ".$empr->empr_tel1." " ;
		}
	if ($empr->empr_tel2 != "") {
		$tel = $tel.$msg['fpdf_tel2']." ".$empr->empr_tel2;
		} 
	if ($empr->empr_mail != "") {
		if ($tel) $tel = $tel."\n" ;
		$mail = $msg['fpdf_email']." ".$empr->empr_mail;
		} 
	
	$res_final[]="\n".$tel.$mail.$lib_all_groupes;			
	$res_final[]="";
	$res_final[]=$msg['fpdf_carte']." ".$empr->empr_cb;
	$res_final[]=$msg['fpdf_adherent']." ".$empr->aff_empr_date_adhesion." ".$msg['fpdf_adherent_au']." ".$empr->aff_empr_date_expiration ;

	return implode("\n",$res_final);

} /* fin m_lecteur_info */

// ********************* Imprime l'adresse d'un lecteur **********************************
function m_lecteur_adresse($empr) {
	
	global $msg;
	
	$res_final=array();
	
	if ($empr->empr_prenom) $empr->empr_nom=$empr->empr_prenom." ".$empr->empr_nom; 
	$res_final[]=$empr->empr_nom;
	
	if ($empr->empr_adr2 != "") $empr->empr_adr1 = $empr->empr_adr1."\n" ;
	if (($empr->empr_cp != "") || ($empr->empr_ville != "")) $empr->empr_adr2 = $empr->empr_adr2."\n" ;
	$adr = $empr->empr_adr1.$empr->empr_adr2.$empr->empr_cp." ".$empr->empr_ville ;
	if ($empr->empr_pays != "") $adr = $adr."\n".$empr->empr_pays ;
	$res_final[]=$adr;
	
	if ($empr->empr_tel1 != "") {
		$tel = $tel.$msg['fpdf_tel1']." ".$empr->empr_tel1." " ;
		}
	if ($empr->empr_tel2 != "") {
		$tel = $tel.$msg['fpdf_tel2']." ".$empr->empr_tel2;
		} 
	if ($empr->empr_mail != "") {
		if ($tel) $tel = $tel."\n" ;
		$mail = $msg['fpdf_email']." ".$empr->empr_mail;
		} 
	
	$res_final[]="\n".$tel.$mail;			

	return implode("\n",$res_final);
	} /* fin m_lecteur_adresse */


// Liste des prêts en cours
function m_liste_prets($destinataire) {
	global $dbh, $msg;	

	$res_final=array();
	// $rqt = "select expl_cb from pret, exemplaires where pret_idempr='".$destinataire->id_empr."' and pret_idexpl=expl_id order by pret_date " ;
	$requete = "SELECT notices_m.notice_id as m_id, notices_s.notice_id as s_id, expl_cb, expl_cote, pret_date, pret_retour, tdoc_libelle, section_libelle, location_libelle, trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit, ";
	$requete.= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
	$requete.= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, "; 
	$requete.= " IF(pret_retour>sysdate(),0,1) as retard, notices_m.tparent_id, notices_m.tnvol " ; 
	$requete.= " FROM (((exemplaires LEFT JOIN notices AS notices_m ON expl_notice = notices_m.notice_id ) LEFT JOIN bulletins ON expl_bulletin = bulletins.bulletin_id) LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), docs_type, docs_section, docs_location, pret ";
	$requete.= " WHERE pret_idempr='".$destinataire->id_empr."' and expl_typdoc = idtyp_doc and expl_section = idsection and expl_location = idlocation and pret_idexpl = expl_id  ";

	$req = mysql_query($requete) or die($msg['err_sql'].'<br />'.$requete.'<br />'.mysql_error()); 
	while ($expl = mysql_fetch_object($req)) {
		
		$responsabilites = get_notice_authors(($expl->m_id+$expl->s_id)) ;
		$as = array_search ("0", $responsabilites["responsabilites"]) ;
		if ($as!== FALSE && $as!== NULL) {
			$auteur_0 = $responsabilites["auteurs"][$as] ;
			$auteur = new auteur($auteur_0["id"]);
			$header_aut .= $auteur->isbd_entry;
		} else {
			$aut1_libelle=array();
			$as = array_keys ($responsabilites["responsabilites"], "1" ) ;
			for ($i = 0 ; $i < count($as) ; $i++) {
				$indice = $as[$i] ;
				$auteur_1 = $responsabilites["auteurs"][$indice] ;
				$auteur = new auteur($auteur_1["id"]);
				$aut1_libelle[]= $auteur->isbd_entry;
			}	
			$header_aut .= implode (", ",$aut1_libelle) ;
		}
		$header_aut ? $auteur=" / ".$header_aut : $auteur="";
	
		// récupération du titre de série
		if ($expl->tparent_id && $expl->m_id) {
			$parent = new serie($expl->tparent_id);
			$tit_serie = $parent->name;
			if($expl->tnvol) $tit_serie .= ', '.$expl->tnvol;
		}
		if($tit_serie) $expl->tit = $tit_serie.'. '.$expl->tit;

		$res_final[]="<b>".$expl->tit."</b> (".$expl->tdoc_libelle.")";
		$res_final[]="<blockquote>".$msg['fpdf_date_pret']." ".$expl->aff_pret_date."&nbsp;&nbsp;".$msg['fpdf_retour_prevu']." ".$expl->aff_pret_retour;
		$res_final[]=$expl->location_libelle.": ".$expl->section_libelle.": ".$expl->expl_cote." (".$expl->expl_cb.")</blockquote>";
	}
	return implode("\n",$res_final);
}

// Liste des réservations en cours
function m_liste_resas($destinataire) {
	global $dbh, $msg;
	$rqt = "select resa_idnotice, resa_idbulletin from resa where resa_idempr='".$destinataire->id_empr."' " ;
	$req = mysql_query($rqt) or die($msg['err_sql'].'<br />'.$rqt.'<br />'.mysql_error()); 
	$all_resa="";
	while ($data = mysql_fetch_array($req)) {
		$all_resa.=m_not_bull_info_resa ($destinataire->id_empr, $data['resa_idnotice'],$data['resa_idbulletin']);
	}
	return $all_resa ;
} // fin if résas

function m_not_bull_info_resa ($id_empr, $notice, $bulletin) {
	global $msg;
	
	$res_final=array();
	$dates_resa_sql = "date_format(resa_date, '".$msg["format_date"]."') as date_pose_resa, IF(resa_date_fin>sysdate() or resa_date_fin='0000-00-00',0,1) as perimee, if(resa_date_debut='0000-00-00', '', date_format(resa_date_debut, '".$msg["format_date"]."')) as aff_resa_date_debut, if(resa_date_fin='0000-00-00', '', date_format(resa_date_fin, '".$msg["format_date"]."')) as aff_resa_date_fin " ;
	if ($notice) {
		$requete = "SELECT notice_id, resa_date, resa_idempr, tit1 as tit, ".$dates_resa_sql;
		$requete.= "FROM notices, resa ";
		$requete.= "WHERE notice_id='".$notice."' and resa_idnotice=notice_id order by resa_date ";
		} else {
			$requete = "SELECT notice_id, resa_date, resa_idempr, trim(concat(tit1,' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit, ".$dates_resa_sql;
			$requete.= "FROM bulletins, resa, notices ";
			$requete.= "WHERE resa_idbulletin='$bulletin' and resa_idbulletin = bulletins.bulletin_id and bulletin_notice = notice_id order by resa_date ";
			}
			
	$res = mysql_query($requete) or die ("<br />".mysql_error());
	$nb_resa = mysql_num_rows($res) ;
	
	for ($j=0 ; $j<$nb_resa ; $j++ ) {
		$resa = mysql_fetch_object($res);
		if ($resa->resa_idempr == $id_empr) {
			$responsabilites = get_notice_authors($resa->notice_id) ;
			$as = array_search ("0", $responsabilites["responsabilites"]) ;
			if ($as!== FALSE && $as!== NULL) {
				$auteur_0 = $responsabilites["auteurs"][$as] ;
				$auteur = new auteur($auteur_0["id"]);
				$header_aut .= $auteur->isbd_entry;
				} else {
					$aut1_libelle=array();
					$as = array_keys ($responsabilites["responsabilites"], "1" ) ;
					for ($i = 0 ; $i < count($as) ; $i++) {
						$indice = $as[$i] ;
						$auteur_1 = $responsabilites["auteurs"][$indice] ;
						$auteur = new auteur($auteur_1["id"]);
						$aut1_libelle[]= $auteur->isbd_entry;
						}
					
					$header_aut .= implode (", ",$aut1_libelle) ;
					}
			$header_aut ? $auteur=" / ".$header_aut : $auteur="";

			if ($resa->aff_resa_date_debut) $tmpmsg_res = $msg['fpdf_reserve_du']." ".$resa->aff_resa_date_debut." ".$msg['fpdf_adherent_au']." ".$resa->aff_resa_date_fin;
				else $tmpmsg_res = $msg['fpdf_attente_valid'];
			
			$res_final[]="<b>".$resa->tit.$auteur."</b>";
			$res_final[]="<blockquote>".$tmpmsg_res;
			$date_resa = " ".$msg['fpdf_reserv_enreg']." ".$resa->date_pose_resa."." ;
			$res_final[]=$msg['fpdf_rang']." ".($j+1).$date_resa."</blockquote>";
			}
		} // fin for
	return implode("\n",$res_final);
	} /* fin not_bull_info_resa */


