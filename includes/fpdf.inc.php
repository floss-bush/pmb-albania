<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fpdf.inc.php,v 1.64 2010-01-07 10:50:03 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/amende.class.php");
require_once($class_path."/comptes.class.php");
require_once ("$include_path/notice_authors.inc.php");  
require_once($class_path."/serie.class.php");
require_once ("$class_path/author.class.php");  


// Fonctions fpdf
function biblio_info($x, $y, $short=0) {
	
	global $ourPDF,$msg;
	global $biblio_name, $biblio_logo, $biblio_adr1, $biblio_adr2, $biblio_cp, $biblio_town, $biblio_state, $biblio_country, $biblio_phone, $biblio_email, $biblio_website ;
	global $txt_biblio_info ;
	global $pmb_pdf_font;

	if ($short==1) {
			/*
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
		$txt_biblio_info_short = $biblio_adr1.$biblio_adr2.$biblio_cp.$biblio_town.$biblio_state.$biblio_country.$biblio_phone.$biblio_email.$biblio_website ;
		*/
		$ourPDF->SetXY ($x,$y);
		$ourPDF->setFont($pmb_pdf_font, 'B', 16);
		$ourPDF->multiCell(120, 8, $biblio_name, 0, 'L', 0);
		/*
		$ourPDF->SetXY ($x,$y+20);
		$ourPDF->setFont('Arial', '', 10);
		$ourPDF->multiCell(0, 8, $txt_biblio_info_short, 0, 'L', 0);
		*/
	} else { 
		// afin de ne générer qu'une fois l'adr et compagnie 
		if (!$txt_biblio_info) {
			if ($biblio_adr1 != "") $biblio_name = $biblio_name."\n";
			if ($biblio_adr2 != "") $biblio_adr1 = $biblio_adr1."\n";
			if ($biblio_cp != "") $biblio_cp = $biblio_cp." ";
			if (($biblio_cp != "") || ($biblio_town != "")) $biblio_adr2 = $biblio_adr2."\n";
			if ($biblio_state != "") $biblio_state = $biblio_state." ";
			if (($biblio_state != "") || ($biblio_country != "")) $biblio_town = $biblio_town."\n";
			if ($biblio_phone != "") $biblio_phone = $msg['lettre_titre_tel'].$biblio_phone."\n ";
			if ($biblio_email != "") $biblio_email = "@ : ".$biblio_email."\n ";
			if ($biblio_website != "") $biblio_website = "Web : ".$biblio_website."\n ";
			if (($biblio_phone != "") || ($biblio_email != "")) $biblio_country = $biblio_country."\n";
			$txt_biblio_info = $biblio_adr1.$biblio_adr2.$biblio_cp.$biblio_town.$biblio_state.$biblio_country.$biblio_phone.$biblio_email.$biblio_website ;
		}
		
		if ($biblio_logo) $ourPDF->Image("./images/".$biblio_logo, $x, $y ) ;

		$ourPDF->SetXY ($x+60,$y);
		$ourPDF->setFont($pmb_pdf_font, 'B', 16);
		$ourPDF->multiCell(90, 8, $biblio_name, 0, 'C', 0);
	
		$ourPDF->SetXY ($x,$y+50);
		$ourPDF->setFont($pmb_pdf_font, '', 9);
		$ourPDF->multiCell(0, 5, $txt_biblio_info, 0, 'L', 0);
	}
} /* fin biblio_info */

function lecteur_info($id_empr, $x, $y, $link, $short=0, $droite=0) {
	
	global $ourPDF;
	global $msg;
	global $pmb_pdf_font;
	
	$requete = "SELECT id_empr, empr_cb, empr_nom, empr_prenom, empr_adr1, empr_adr2, empr_cp, empr_ville, empr_pays, empr_mail, empr_tel1, empr_tel2, empr_date_adhesion, empr_date_expiration, date_format(empr_date_adhesion, '".$msg["format_date"]."') as aff_empr_date_adhesion, date_format(empr_date_expiration, '".$msg["format_date"]."') as aff_empr_date_expiration FROM empr WHERE id_empr='$id_empr' ";
	$res = mysql_query($requete, $link);
	$empr = mysql_fetch_object($res);

	$requete = "SELECT group_concat(libelle_groupe SEPARATOR ', ') as_all_groupes, 1 as rien from groupe join empr_groupe on groupe_id=id_groupe WHERE lettre_rappel=1 and empr_id='$id_empr' group by rien ";
	$lib_all_groupes=pmb_sql_value($requete);
	if ($lib_all_groupes) $lib_all_groupes="\n".$lib_all_groupes; 

	$ourPDF->SetXY ($x,$y);
	$ourPDF->setFont($pmb_pdf_font, 'B', 12);
	if ($droite) $ourPDF->multiCell(100, 8, $empr->empr_prenom." ".$empr->empr_nom, 0, 'R', 0);
		else $ourPDF->multiCell(100, 8, $empr->empr_prenom." ".$empr->empr_nom, 0, 'L', 0);

	if ($short==1) return ;
	
	if ($empr->empr_adr2 != "") $empr->empr_adr1 = $empr->empr_adr1."\n" ;
	if (($empr->empr_cp != "") || ($empr->empr_ville != "")) $empr->empr_adr2 = $empr->empr_adr2."\n" ;
	$adr = $empr->empr_adr1.$empr->empr_adr2.$empr->empr_cp." ".$empr->empr_ville ;
	if ($empr->empr_pays != "") $adr = $adr."\n".$empr->empr_pays ;
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
				
	$ourPDF->SetXY ($x,$y+8);
	$ourPDF->setFont($pmb_pdf_font, '', 12);
	$ourPDF->multiCell(100, 8, $adr, 0, 'L', 0);
	
	$ourPDF->SetXY ($x,$y+32);
	$ourPDF->setFont($pmb_pdf_font, '', 12);
	$ourPDF->multiCell(100, 7, "\n".$tel.$mail.$lib_all_groupes, 0, 'L', 0);
	
	$ourPDF->SetXY ($x,$y+58);
	$ourPDF->setFont($pmb_pdf_font, 'I', 12);
	$ourPDF->multiCell(100, 7, $msg['fpdf_carte']." ".$empr->empr_cb."\n".$msg['fpdf_adherent']." ".$empr->aff_empr_date_adhesion." ".$msg['fpdf_adherent_au']." ".$empr->aff_empr_date_expiration.".", 0, 'L', 0);
} /* fin lecteur_info */

// ********************* Imprime l'adresse d'un lecteur **********************************
function lecteur_adresse($id_empr, $x, $y, $link, $no_cb=false, $show_nomgroupe=false) {
	global $ourPDF;
	global $msg;
	global $pmb_pdf_font;
	
	//Vérifions si l'on demande un positionnement absolu
	global $pmb_lettres_bloc_adresse_position_absolue;
	$absolue_config = explode(" ", $pmb_lettres_bloc_adresse_position_absolue);
	if ((count($absolue_config) == 3) && ($absolue_config[0] != 0)) {
		$x = $absolue_config[1]+0;
		$y = $absolue_config[2]+0;
	}
	
	global $pmb_lettres_code_mail_position_absolue;
	$absolue_config_code = explode(" ", $pmb_lettres_code_mail_position_absolue);
	$x_code = 0;
	$y_code = 0;
	if ((count($absolue_config_code) == 3) && ($absolue_config_code[0] != 0)) {
		$x_code = $absolue_config_code[1]+0;
		$y_code = $absolue_config_code[2]+0;
	}
	
	$concerne="";
	$temp_id_empr=$id_empr;
	if($show_nomgroupe) {
		//Recherche du groupe d'appartenance
		$requete="select id_groupe,resp_groupe from groupe,empr_groupe where id_groupe=groupe_id and empr_id=$id_empr and resp_groupe and lettre_rappel limit 1";
		$res=mysql_query($requete);
		if(mysql_num_rows($res)) {
			$temp_id_empr=mysql_result($res,0,1);
		} else  $temp_id_empr=$id_empr;
		
		//Si le responsable n'est pas l'emprunteur, on précise qui est relancé
		if ($temp_id_empr!=$id_empr) {
			$requete="select concat(empr_prenom,' ',empr_nom) from empr where id_empr=$id_empr"; //Idée de Quentin
			$res=mysql_query($requete);
			$concerne="\n".sprintf($msg["adresse_retard_concerne"],mysql_result($res,0,0))."\n";
		} 
	}	
	
	$requete = "SELECT id_empr, empr_cb, empr_nom, empr_prenom, empr_adr1, empr_adr2, empr_cp, empr_ville, empr_pays, empr_mail, empr_tel1, empr_tel2  FROM empr WHERE id_empr='$temp_id_empr' LIMIT 1 ";
	$res = mysql_query($requete, $link);
	$empr = mysql_fetch_object($res);
	
	$requete = "SELECT group_concat(libelle_groupe SEPARATOR ', ') as_all_groupes, 1 as rien from groupe join empr_groupe on groupe_id=id_groupe WHERE lettre_rappel=1 and empr_id='$id_empr' group by rien ";
	$lib_all_groupes=pmb_sql_value($requete);
	if ($lib_all_groupes) $lib_all_groupes="\n".$lib_all_groupes; 

	$ourPDF->SetXY ($x,$y);
	$adr = $empr->empr_prenom." ".$empr->empr_nom."\n";
	$ourPDF->setFont($pmb_pdf_font, '', 12);
	if ($empr->empr_adr2 != "") $empr->empr_adr1 = $empr->empr_adr1."\n" ;
	if (($empr->empr_cp != "") || ($empr->empr_ville != "")) $empr->empr_adr2 = $empr->empr_adr2."\n" ;
	$adr.= $empr->empr_adr1.$empr->empr_adr2.$empr->empr_cp." ".$empr->empr_ville ;	
	
	if ($empr->empr_pays != "") $adr.="\n".$empr->empr_pays ;
	if ($empr->empr_tel1 != "") {
		$tel = "\n".$msg['fpdf_tel1']." ".$empr->empr_tel1;
	} elseif ($empr->empr_tel2 != "") {
		$adr.="\n" ;
		$tel = $tel.$msg['fpdf_tel2']." ".$empr->empr_tel2;
	} else {
		$tel = "" ;
	}	
	if ($empr->empr_mail != "") {
		$tel = $tel."\n" ;
		$mail = $msg['fpdf_email']." ".$empr->empr_mail;
	} else {
		$mail = "" ;
	}
	
	$ourPDF->SetDrawColor(255,255,255);
	$ourPDF->SetFillColor(255,255,255);
	$ourPDF->multiCell(100, 6, $adr, 0, 'L', true);
	
	if ($no_cb==false) {
		$ourPDF->SetXY (($x_code ? $x_code : $x),($y_code ? $ourPDF->GetY()+$y_code :$ourPDF->GetY()));
		$ourPDF->setFont($pmb_pdf_font, 'I', 10);
		$ourPDF->multiCell(100, 6, $msg['fpdf_carte']." ".$empr->empr_cb." ".$empr->empr_mail. $lib_all_groupes.$concerne, 0, 'L', true);
	}
} /* fin lecteur_adresse */

// ******************** Imprime le libellé du groupe suivi le cas échéant des coordonnées du responsable
function groupe_adresse($id_groupe, $x, $y, $link, $no_cb=false) {
	global $ourPDF;
	global $pmb_pdf_font;
	global $pmb_afficher_numero_lecteur_lettres;
	
	$requete = "SELECT libelle_groupe, resp_groupe  FROM groupe WHERE id_groupe='$id_groupe' ";
	$res = mysql_query($requete, $link);
	$groupe = mysql_fetch_object($res);
	
	$ourPDF->SetXY ($x,$y);
	$ourPDF->setFont($pmb_pdf_font, '', 12);
	$ourPDF->multiCell(100, 8, $groupe->libelle_groupe, 0, 'L', 0);
	
	if ($groupe->resp_groupe) {
		$y=$y+8;
		lecteur_adresse($groupe->resp_groupe, $x, $y, $link, $no_cb || !$pmb_afficher_numero_lecteur_lettres) ;
		}
	} /* fin groupe_adresse */

function expl_info($cb_doc, $x, $y, $link, $short=0, $longmax=99999) {
	global $ourPDF;
	global $msg ;
	global $pmb_pdf_font;
	
	$requete = "SELECT notices_m.notice_id as m_id, notices_s.notice_id as s_id, expl_cb, expl_cote, pret_date, pret_retour, tdoc_libelle, section_libelle, location_libelle, trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit, ";
	$requete.= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
	$requete.= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, "; 
	$requete.= " IF(pret_retour>sysdate(),0,1) as retard, notices_m.tparent_id, notices_m.tnvol " ; 
	$requete.= " FROM (((exemplaires LEFT JOIN notices AS notices_m ON expl_notice = notices_m.notice_id ) LEFT JOIN bulletins ON expl_bulletin = bulletins.bulletin_id) LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), docs_type, docs_section, docs_location, pret ";
	$requete.= " WHERE expl_cb='".$cb_doc."' and expl_typdoc = idtyp_doc and expl_section = idsection and expl_location = idlocation and pret_idexpl = expl_id  ";

	$res = mysql_query($requete, $link) or die ("<br />".mysql_error());
	$expl = mysql_fetch_object($res);
	
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
		if($expl->tnvol)
			$tit_serie .= ', '.$expl->tnvol;
	}
	if($tit_serie) {
		$expl->tit = $tit_serie.'. '.$expl->tit;
	}

	if ($short==1) {
		$ourPDF->SetXY ($x,$y);
		$ourPDF->setFont($pmb_pdf_font, 'B', 10);
		$ourPDF->multiCell(190, 8, substr($expl->tit.$auteur,0,$longmax) , 0, 'L', 0);
	
		$ourPDF->SetXY ($x+10,$y+4);
		$ourPDF->setFont($pmb_pdf_font, '', 9);
		$ourPDF->multiCell(140, 8, $msg['fpdf_date_pret']." ".$expl->aff_pret_date, 0, 'L', 0);
		$ourPDF->SetXY ($x+70,$y+4);
		$ourPDF->setFont($pmb_pdf_font, 'B', 9);
		$ourPDF->multiCell(70, 8, $msg['fpdf_retour_prevu']." ".$expl->aff_pret_retour, 0, 'L', 0);
		$ourPDF->SetXY ($x+10,$y+8);
		$ourPDF->setFont($pmb_pdf_font, 'I', 8);
		$ourPDF->multiCell(190, 8, $expl->location_libelle.": ".$expl->section_libelle.": ".$expl->expl_cote." (".$expl->expl_cb.")", 0, 'L', 0);
	} else {

		$ourPDF->SetXY ($x,$y);
		$ourPDF->setFont($pmb_pdf_font, 'BU', 14);
		$ourPDF->multiCell(190, 8, substr($expl->tit." (".$expl->tdoc_libelle.")",0,$longmax), 0, 'L', 0);
	
		$ourPDF->SetXY ($x+10,$y+6);
		$ourPDF->setFont($pmb_pdf_font, '', 10);
		$ourPDF->multiCell(190-30, 8, $msg['fpdf_date_pret']." ".$expl->aff_pret_date, 0, 'L', 0);
		$ourPDF->SetXY ($x+70,$y+6);
		$ourPDF->setFont($pmb_pdf_font, 'B', 10);
		$ourPDF->multiCell((190 - 70), 8, $msg['fpdf_retour_prevu']." ".$expl->aff_pret_retour, 0, 'L', 0);

		$ourPDF->SetXY ($x+10,$y+10);
		$ourPDF->setFont($pmb_pdf_font, 'I', 8);
		$ourPDF->multiCell(190, 8, $expl->location_libelle.": ".$expl->section_libelle.": ".$expl->expl_cote." (".$expl->expl_cb.")", 0, 'L', 0);
	}	
} /* fin expl_info */

function not_bull_info_resa ($id_empr, $notice, $bulletin, $x, $y, $link, $longmax=99999) {
	global $ourPDF;
	global $msg;
	global $pmb_pdf_font;
	
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
			
	$res = mysql_query($requete, $link) or die ("<br />".mysql_error());
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
			
			$ourPDF->SetXY ($x,$y);
			$ourPDF->setFont($pmb_pdf_font, 'BU', 14);
			$ourPDF->multiCell(190, 8, substr($resa->tit.$auteur,0,$longmax), 0, 'L', 0);
			
			if ($resa->aff_resa_date_debut) $tmpmsg_res = $msg['fpdf_reserve_du']." ".$resa->aff_resa_date_debut." ".$msg['fpdf_adherent_au']." ".$resa->aff_resa_date_fin;
			else $tmpmsg_res = $msg['fpdf_attente_valid'];
			$ourPDF->SetXY ($x+10,$y+6);
			$ourPDF->setFont('Arial', '', 10);
			$ourPDF->multiCell(140, 8, $tmpmsg_res, 0, 'L', 0);
			
			$date_resa = " ".$msg['fpdf_reserv_enreg']." ".$resa->date_pose_resa."." ;
			$ourPDF->SetXY ($x+10,$y+10);
			$ourPDF->setFont('Arial', '', 8);
			$ourPDF->multiCell(140, 8, $msg['fpdf_rang']." ".($j+1).$date_resa, 0, 'L', 0);
			return ;
		}
	} // fin for
} /* fin not_bull_info_resa */

// ************************* Imprime la ligne de retard pour un exemplaire sur la lettre du lecteur
function expl_retard($cb_doc, $x, $y, $largeur, $retrait, $link) {
	
	global $ourPDF;
	global $msg;
	global $pmb_gestion_financiere, $pmb_gestion_amende;
	global $pmb_pdf_font;
	
	$valeur=0;
	$dates_resa_sql = " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour " ;
	$requete = "SELECT notices_m.notice_id as m_id, notices_s.notice_id as s_id, pret_idempr, expl_id, expl_cb,expl_cote, pret_date, pret_retour, tdoc_libelle, section_libelle, location_libelle, trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date!='', concat(' (',mention_date,')') ,''))) as tit, ".$dates_resa_sql.", " ;
	$requete.= " notices_m.tparent_id, notices_m.tnvol " ; 
	$requete.= " FROM (((exemplaires LEFT JOIN notices AS notices_m ON expl_notice = notices_m.notice_id ) LEFT JOIN bulletins ON expl_bulletin = bulletins.bulletin_id) LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), docs_type, docs_section, docs_location, pret ";
	$requete.= " WHERE expl_cb='".$cb_doc."' and expl_typdoc = idtyp_doc and expl_section = idsection and expl_location = idlocation and pret_idexpl = expl_id  ";
	
	$res = mysql_query($requete, $link) or die (mysql_error()." $requete");
	$expl = mysql_fetch_object($res);
	
	// récupération du titre de série
	if ($expl->tparent_id && $expl->m_id) {
		$parent = new serie($expl->tparent_id);
		$tit_serie = $parent->name;
		if($expl->tnvol)
			$tit_serie .= ', '.$expl->tnvol;
	}
	if($tit_serie) {
		$expl->tit = $tit_serie.'. '.$expl->tit;
	}	
	$libelle=$expl->tdoc_libelle;
	$responsabilites=get_notice_authors($expl->m_id) ;
	//print_r($responsabilites);
	$as = array_keys ($responsabilites["responsabilites"], "0" ) ;
	for ($i = 0 ; $i < count($as) ; $i++) {
		$indice = $as[$i] ;
		$auteur_1 = $responsabilites["auteurs"][$indice] ;
		$auteur = new auteur($auteur_1["id"]);
		$aut1_libelle[]= $auteur->isbd_entry;
		
	}
	if ($aut1_libelle) {
		$auteurs_liste = implode ("; ",$aut1_libelle) ;	
		if ($auteurs_liste) $libelle .= ' / '. $auteurs_liste;
		
	}	
	$libelle=$expl->tit." (".$libelle.")" ;
	//substr($libelle,0,50);
	
	$ourPDF->SetXY ($x,$y);
	$ourPDF->setFont($pmb_pdf_font, 'BU', 10);
	
	while( $ourPDF->GetStringWidth($libelle) > 178) {
		$libelle=substr($libelle,0,count($libelle)-2);
	}	
	//print $ourPDF->GetStringWidth($libelle);
	$ourPDF->multiCell(($largeur - $x), 8, $libelle, 0, 'L', 0);
		
	$ourPDF->SetXY ($x+$retrait,$y+4);
	$ourPDF->setFont($pmb_pdf_font, '', 10);
	$ourPDF->multiCell(($largeur - $retrait - $x), 8, $msg['fpdf_date_pret']." ".$expl->aff_pret_date, 0, 'L', 0);
	$ourPDF->SetXY (($x+$retrait+52),$y+4);
	$ourPDF->setFont($pmb_pdf_font, 'B', 10);
	$ourPDF->multiCell(($largeur - $x - $retrait - 52), 8, $msg['fpdf_retour_prevu']." ".$expl->aff_pret_retour, 0, 'L', 0);

	$ourPDF->SetXY ($x+$retrait,$y+8);
	$ourPDF->setFont($pmb_pdf_font, 'I', 8);
	$ourPDF->multiCell(($largeur - $retrait - $x), 8, $expl->location_libelle.": ".$expl->section_libelle.", ".$expl->expl_cote." (".$expl->expl_cb.")", 0, 'L', 0);

	if (($pmb_gestion_financiere)&&($pmb_gestion_amende)) {
		$amende=new amende($expl->pret_idempr);
		$amd=$amende->get_amende($expl->expl_id);
		if ($amd["valeur"]) {
			$ourPDF->SetXY (($x+$retrait+120),$y+8);
			$ourPDF->multiCell(($largeur - $x - $retrait - 120), 8, sprintf($msg["relance_lettre_retard_amende"],comptes::format_simple($amd["valeur"])), 0, 'R', 0);
			$valeur=$amd["valeur"];
		}
	}
	return $valeur;
} /* fin expl_retard */

// ************************* Imprime la ligne de retard pour un exemplaire sur la lettre du lecteur
function expl_retard_empr($id_empr, $cb_doc, $x, $y, $largeur, $retrait, $link) {
	
	global $ourPDF;
	global $msg;
	global $pmb_pdf_font;
	
	$requete = "SELECT id_empr, empr_cb, empr_nom, empr_prenom, empr_adr1, empr_adr2, empr_cp, empr_ville, empr_pays, empr_mail, empr_tel1, empr_tel2  FROM empr WHERE id_empr='$id_empr' LIMIT 1 ";
	$res = mysql_query($requete, $link);
	$empr = mysql_fetch_object($res);
	$ourPDF->SetXY ($x,$y);
	$ourPDF->setFont($pmb_pdf_font, '', 12);
	$ourPDF->multiCell(100, 8, $empr->empr_prenom." ".$empr->empr_nom, 0, 'L', 0);
	$y=$y+4;
	expl_retard($cb_doc, $x, $y, $largeur, $retrait+10, $link) ;
} // fin expl_retard_empr

function date_edition($x, $y) {
	global $ourPDF;
	global $msg;
	global $pmb_pdf_fontfixed;
	
	$ourPDF->SetXY ($x,$y);
	$ourPDF->setFont($pmb_pdf_fontfixed, 'I', 12);
	$ourPDF->multiCell(140, 8, $msg['fpdf_edite']." ".formatdate(date("Y-m-d",time())), 0, 'L', 0);
}
	
function date_jour($x, $y) {

	global $ourPDF;
	global $pmb_pdf_fontfixed,$msg,$biblio_town,$pmb_pdf_font;
	$ourPDF->SetXY ($x,$y);
	$ourPDF->setFont($pmb_pdf_font, '', 10);
	$c=str_replace("!!ville!!",$biblio_town,$msg['lettre_date_header']);
	$c=str_replace("!!date!!",formatdate(date("Y-m-d",time())),$c);
	$ourPDF->multiCell(100, 8, $c, 0, 'R', 0);	
}

function lettre_retard_par_lecteur($id_empr) {

	global $ourPDF, $dbh, $msg , $nb_page, $nb_1ere_page, $nb_par_page, $pmb_gestion_financiere, $pmb_gestion_amende, $niveau;
	global $pmb_pdf_font;
	// les variables sont lues en dehors
	global $marge_page_gauche, $marge_page_droite, $largeur_page, $fdp, $after_list, $limite_after_list, $before_list, $madame_monsieur, $nb_1ere_page, $nb_par_page, $taille_bloc_expl, $debut_expl_1er_page, $debut_expl_page, $before_recouvrement,$after_recouvrement;
	global $pmb_afficher_numero_lecteur_lettres;
	global $pmb_hide_biblioinfo_letter;
	
	//Pour les amendes
	$valeur=0;	
	$ourPDF->addPage();
	
	//date_jour($largeur_page-$marge_page_droite-30,10);
	date_jour($largeur_page/2,98);
	if(!$pmb_hide_biblioinfo_letter) biblio_info( $marge_page_gauche, 15) ;
	lecteur_adresse($id_empr, ($marge_page_gauche+90), 45, $dbh, !$pmb_afficher_numero_lecteur_lettres, true);
	
	$ourPDF->SetXY ($marge_page_gauche,105);
	$ourPDF->setFont($pmb_pdf_font, '', 10);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $madame_monsieur, 0, 'L', 0);
	$ourPDF->SetXY ($marge_page_gauche,$ourPDF->GetY()+4);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 5, $before_list, 0, 'J', 0);
		
	//Calcul des frais de relance
	if (($pmb_gestion_financiere)&&($pmb_gestion_amende)) {
		$id_compte=comptes::get_compte_id_from_empr($id_empr,2);
		if ($id_compte) {
			$cpte=new comptes($id_compte);
			$frais_relance=$cpte->summarize_transactions("","",0,$realisee=-1);
			if ($frais_relance<0) $frais_relance=-$frais_relance; else $frais_relance=0;
		}
	}
	
	if($niveau!=3) {
		$rqt = "select expl_cb from pret, exemplaires where pret_idempr='".$id_empr."' and pret_retour < curdate() and pret_idexpl=expl_id order by pret_date " ;
		$req = mysql_query($rqt, $dbh) or die($msg['err_sql'].'<br />'.$rqt.'<br />'.mysql_error()); 
			
		while ($data = mysql_fetch_array($req)) {
			if (($pos_page=$ourPDF->GetY())>260) {
				$ourPDF->addPage();
				$pos_page=$debut_expl_page;
			}
			$valeur+=expl_retard ($data['expl_cb'],$marge_page_gauche,$pos_page,($largeur_page - $marge_page_droite - $marge_page_gauche), 10,$dbh);
		}		
		print_amendes($valeur,$frais_relance);		
		
		$ourPDF->SetX ($marge_page_gauche);
		$ourPDF->setFont($pmb_pdf_font, '', 10);
		
	} else {
		
		$requete="select expl_cb from exemplaires, pret where pret_idempr=$id_empr and pret_idexpl=expl_id and niveau_relance=3";
		$res_recouvre=mysql_query($requete);
		while ($rrc=mysql_fetch_object($res_recouvre)) {
			$liste_r3[]=$rrc->expl_cb;
		}	
		$rqt = "select expl_cb from pret, exemplaires where pret_idempr='".$id_empr."' and pret_retour < curdate() and pret_idexpl=expl_id order by pret_date " ;
		$req = mysql_query($rqt, $dbh) or die($msg['err_sql'].'<br />'.$rqt.'<br />'.mysql_error()); 		
		while ($data = mysql_fetch_object($req)) {
			// Pas répéter les retard si déjà en niveau 3
			if(in_array($data->expl_cb,$liste_r3)===false){
				$liste_r[] = $data->expl_cb;
			}		
		}	
	
		if($liste_r) {
			// Il y a des retard simple: on affiche d'abord les retards simples 
			foreach($liste_r as $cb_expl) {
				if (($pos_page=$ourPDF->GetY())>260) {
					$ourPDF->addPage();
					$pos_page=$debut_expl_page;
				}
				$valeur+=expl_retard ($cb_expl,$marge_page_gauche,$pos_page,($largeur_page - $marge_page_droite - $marge_page_gauche), 10,$dbh);		
			}
			$ourPDF->setFont($pmb_pdf_font, '', 10);
			$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 5, $before_recouvrement, 0, 'J', 0);
			// affiche retards niveau 3
			foreach($liste_r3 as $cb_expl) {
				if (($pos_page=$ourPDF->GetY())>260) {
					$ourPDF->addPage();
					$pos_page=$debut_expl_page;
				}
				$valeur+=expl_retard ($cb_expl,$marge_page_gauche,$pos_page,($largeur_page - $marge_page_droite - $marge_page_gauche), 10,$dbh);							
			}			
			print_amendes($valeur,$frais_relance);		
			
		} else {
			// il n'y a que des retards niveau 3
			foreach($liste_r3 as $cb_expl) {
				if (($pos_page=$ourPDF->GetY())>260) {
					$ourPDF->addPage();
					$pos_page=$debut_expl_page;
				}
				$valeur+=expl_retard ($cb_expl,$marge_page_gauche,$pos_page,($largeur_page - $marge_page_droite - $marge_page_gauche), 10,$dbh);							
			}		
			print_amendes($valeur,$frais_relance);		
			$ourPDF->setFont($pmb_pdf_font, '', 10);
			$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 5, $after_recouvrement, 0, 'J', 0);		
		}		
		//if (($niveau==3)&&(($pmb_gestion_financiere)&&($pmb_gestion_amende))) {			
	}
	$ourPDF->setFont($pmb_pdf_font, '', 10);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 5, $after_list, 0, 'J', 0);
	
	$ourPDF->setFont($pmb_pdf_font, 'I', 10);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 5, $fdp, 0, 'R', 0);
} // fin lettre_retard_par_lecteur


function print_amendes($valeur,$frais_relance) {
	global $pmb_pdf_font,$ourPDF,$largeur_page, $marge_page_droite, $marge_page_gauche,$msg,$debut_expl_page;
	//Si il y a des amendes
	$ourPDF->SetY ($ourPDF->GetY()+2);	
	$ourPDF->setFont($pmb_pdf_font, '', 10);
	$ourPDF->SetWidths(array(70,30));	

	if ($ourPDF->GetY()>260) {
		$ourPDF->addPage();
		$ourPDF->SetY($debut_expl_page);
	}	
	if ($valeur) {
		$ourPDF->SetX ($marge_page_gauche+40);
		 $ourPDF->Row(array($msg["relance_lettre_retard_total_amendes"], comptes::format_simple($valeur) ));
	}
	if ($frais_relance) {
		$ourPDF->SetX ($marge_page_gauche+40);
		$ourPDF->Row(array($msg["relance_lettre_retard_frais_relance"], comptes::format_simple($frais_relance) ));
	}
	if (($frais_relance)||($valeur)) {
		$ourPDF->SetX ($marge_page_gauche+40);
		$ourPDF->Row(array($msg["relance_lettre_retard_total_du"], comptes::format_simple($valeur+$frais_relance) ));
	}	
	$ourPDF->SetY ($ourPDF->GetY()+4);
}

// ******************** Imprime les lettres de retard pour un groupe ****************************
function lettre_retard_par_groupe($id_groupe, $lecteurs_ids=array()) {

	global $ourPDF, $dbh, $msg;
	global $pmb_pdf_font;
	
	// les variables sont lues en dehors
	global $marge_page_gauche, $marge_page_droite, $largeur_page, $fdp, 
		$after_list, $limite_after_list, $before_list, $madame_monsieur, $nb_1ere_page, 
		$nb_par_page, $taille_bloc_expl, $debut_expl_1er_page, $debut_expl_page;
	global $pmb_hide_biblioinfo_letter;
		
	$ourPDF->addPage();
	date_jour($largeur_page-$marge_page_droite-30,10);
	if(!$pmb_hide_biblioinfo_letter) biblio_info( $marge_page_gauche, 15) ;
	groupe_adresse($id_groupe, ($marge_page_gauche+90), 45, $dbh);
	
	$ourPDF->SetXY ($marge_page_gauche,125);
	$ourPDF->setFont($pmb_pdf_font, '', 12);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $madame_monsieur, 0, 'L', 0);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $before_list, 0, 'J', 0);
	
	if ($lecteurs_ids)
		$lecteur_ids_text = " AND id_empr in (".implode(",",$lecteurs_ids).")";
	else
		$lecteur_ids_text = "";
		
	$rqt = "select  empr_id, expl_cb from pret, exemplaires, empr_groupe, empr where groupe_id='".$id_groupe."' and pret_retour < curdate() and pret_idexpl=expl_id and empr_id=pret_idempr and empr_id=id_empr $lecteur_ids_text order by empr_nom, empr_prenom, pret_date " ;
	$req = mysql_query($rqt, $dbh) or die ($msg['err_sql'].'<br />'.$rqt.'<br />'.mysql_error()); 
	$i=0;
	$nb_page=0;
	$indice_page = 0 ;
	while ($data = mysql_fetch_array($req)) {
		if ($nb_page==0 && $i==$nb_1ere_page) {
			$ourPDF->addPage();
			$nb_page++;
			$indice_page = 0 ;
		} elseif (($nb_page>=1) && ((($i-$nb_1ere_page) % $nb_par_page)==0)) { 
			$ourPDF->addPage();
			$nb_page++;
			$indice_page = 0 ;
		}
		if ($nb_page==0) $pos_page = $debut_expl_1er_page+$taille_bloc_expl*$indice_page;
			else $pos_page = $debut_expl_page+$taille_bloc_expl*$indice_page;
		expl_retard_empr ($data['empr_id'], $data['expl_cb'], $marge_page_gauche,$pos_page,($largeur_page - $marge_page_droite - $marge_page_gauche), 10,$dbh);
		$i++;
		$indice_page++;
	}
	$ourPDF->setFont($pmb_pdf_font, '', 12);
	if (($pos_page+$taille_bloc_expl)>$limite_after_list) {
		$ourPDF->addPage();
		$pos_after_list = $debut_expl_page;
	} else {
		$pos_after_list = $pos_page+$taille_bloc_expl;
	}
	$ourPDF->SetXY ($marge_page_gauche,($pos_after_list));
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $after_list."\n\n", 0, 'J', 0);
	$ourPDF->setFont($pmb_pdf_font, 'I', 12);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $fdp, 0, 'R', 0);
} // fin lettre_retard_par_groupe

// **************** Réservations *************************************

function lettre_resa_par_lecteur($id_empr) {

	global $ourPDF, $dbh, $msg , $nb_page, $nb_1ere_page, $nb_par_page;
	
	// les variables sont lues en dehors
	global $marge_page_gauche, $marge_page_droite, $largeur_page, $fdp, $after_list, $limite_after_list, $before_list, $madame_monsieur, $nb_1ere_page, $nb_par_page, $taille_bloc_expl, $debut_expl_1er_page, $debut_expl_page;
	global $pmb_pdf_font;
	global $pmb_afficher_numero_lecteur_lettres;
	global $pmb_hide_biblioinfo_letter;
	
	$ourPDF->addPage();
	if(!$pmb_hide_biblioinfo_letter) biblio_info( $marge_page_gauche, 10) ;
	lecteur_adresse($id_empr, ($marge_page_gauche+90), 45, $dbh, !$pmb_afficher_numero_lecteur_lettres);
	
	$ourPDF->SetXY ($marge_page_gauche,125);
	$ourPDF->setFont($pmb_pdf_font, '', 12);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $madame_monsieur, 0, 'L', 0);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $before_list, 0, 'J', 0);
	$rqt = "select id_resa from resa where resa_idempr='$id_empr' and resa_cb is not null and resa_cb!='' order by resa_date_debut " ;
	
	$req = mysql_query($rqt, $dbh) or die('Erreur SQL !<br />'.$rqt.'<br />'.mysql_error()); 
	
	$i=0;
	$nb_page=0;
	while ($data = mysql_fetch_array($req)) {
		if ($nb_page==0 && $i<$nb_1ere_page) {
			$pos_page = $debut_expl_1er_page+$taille_bloc_expl*$i;
		}
		if ($nb_page==0 && $i==$nb_1ere_page) {
			$ourPDF->addPage();
			$nb_page++;
		}
		if ($nb_page=1 && $i>=$nb_1ere_page) {
			$pos_page = $debut_expl_page+($taille_bloc_expl*($i-$nb_1ere_page));
		}
		if ($nb_page>1 && $i>$nb_1ere_page) {
			$pos_page = $debut_expl_page+($taille_bloc_expl*($i-$nb_1ere_page-$nb_page*$nb_par_page));
		}
		if ($nb_page>1 && (($i-$nb_1ere_page) % $nb_par_page)==0) {
			$ourPDF->addPage();
			$nb_page++;
		}
		notice_resa ($data['id_resa'],$marge_page_gauche,$pos_page,($largeur_page - $marge_page_droite - $marge_page_gauche), 10,$dbh);
		$i++;
		//echo "<br /> $i "; 
	}
	//echo "<br />pos_page: $pos_page <br />taille_bloc_expl: $taille_bloc_expl <br />limite_after_list: $limite_after_list "; exit ;
	$ourPDF->setFont($pmb_pdf_font, '', 12);
	// dépassement sur autre page de cette partie
	if (($pos_page+$taille_bloc_expl)>$limite_after_list) {
		$ourPDF->addPage();
		$pos_after_list = $debut_expl_page;
	} else {
		$pos_after_list = $pos_page+$taille_bloc_expl;
	}
	$ourPDF->SetXY ($marge_page_gauche,($pos_after_list));
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $after_list."\n\n", 0, 'J', 0);
	$ourPDF->setFont($pmb_pdf_font, 'I', 12);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $fdp, 0, 'R', 0);
} // fin lettre_resa_par_lecteur


function lettre_resa_planning_par_lecteur($id_empr) {

	global $ourPDF, $dbh, $msg , $nb_page, $nb_1ere_page, $nb_par_page, $pmb_afficher_numero_lecteur_lettres;
	
	// les variables sont lues en dehors
	global $marge_page_gauche, $marge_page_droite, $largeur_page, $fdp, $after_list, $limite_after_list, $before_list, $madame_monsieur, $nb_1ere_page, $nb_par_page, $taille_bloc_expl, $debut_expl_1er_page, $debut_expl_page;
	global $pmb_pdf_font;
	global $pmb_afficher_numero_lecteur_lettres;
	global $pmb_hide_biblioinfo_letter;
	
	$ourPDF->addPage();
	if(!$pmb_hide_biblioinfo_letter) biblio_info( $marge_page_gauche, 10) ;
	lecteur_adresse($id_empr, ($marge_page_gauche+90), 45, $dbh, !$pmb_afficher_numero_lecteur_lettres);
	
	$ourPDF->SetXY ($marge_page_gauche,125);
	$ourPDF->setFont($pmb_pdf_font, '', 12);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $madame_monsieur, 0, 'L', 0);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $before_list, 0, 'J', 0);
	$rqt = "select id_resa from resa_planning where resa_idempr='$id_empr' order by resa_date_debut " ;
	
	$req = mysql_query($rqt, $dbh) or die('Erreur SQL !<br />'.$rqt.'<br />'.mysql_error()); 
	
	$i=0;
	$nb_page=0;
	while ($data = mysql_fetch_array($req)) {
		if ($nb_page==0 && $i<$nb_1ere_page) {
			$pos_page = $debut_expl_1er_page+$taille_bloc_expl*$i;
		}
		if ($nb_page==0 && $i==$nb_1ere_page) {
			$ourPDF->addPage();
			$nb_page++;
		}
		if ($nb_page=1 && $i>=$nb_1ere_page) {
			$pos_page = $debut_expl_page+($taille_bloc_expl*($i-$nb_1ere_page));
			}
		if ($nb_page>1 && $i>$nb_1ere_page) {
			$pos_page = $debut_expl_page+($taille_bloc_expl*($i-$nb_1ere_page-$nb_page*$nb_par_page));
		}
		if ($nb_page>1 && (($i-$nb_1ere_page) % $nb_par_page)==0) {
			$ourPDF->addPage();
			$nb_page++;
		}
		notice_resa_planning ($data['id_resa'],$marge_page_gauche,$pos_page,($largeur_page - $marge_page_droite - $marge_page_gauche), 10,$dbh);
		$i++;
		//echo "<br /> $i "; 
	}
	//echo "<br />pos_page: $pos_page <br />taille_bloc_expl: $taille_bloc_expl <br />limite_after_list: $limite_after_list "; exit ;
	$ourPDF->setFont($pmb_pdf_font, '', 12);
	// dépassement sur autre page de cette partie
	if (($pos_page+$taille_bloc_expl)>$limite_after_list) {
		$ourPDF->addPage();
		$pos_after_list = $debut_expl_page;
	} else {
		$pos_after_list = $pos_page+$taille_bloc_expl;
	}
	$ourPDF->SetXY ($marge_page_gauche,($pos_after_list));
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $after_list."\n\n", 0, 'J', 0);
	$ourPDF->setFont($pmb_pdf_font, 'I', 12);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $fdp, 0, 'R', 0);
} // fin lettre_resa_planning_par_lecteur



// ************************* Imprime la ligne de resa pour une notice sur la lettre de confirmation de réservation
function notice_resa($id_resa_print, $x, $y, $largeur, $retrait, $link) {
	
	global $ourPDF;
	global $msg;
	global $pmb_pdf_font;
	global $pmb_transferts_actif,$transferts_choix_lieu_opac;
	
	$dates_resa_sql = " date_format(resa_date_debut, '".$msg["format_date"]."') as aff_resa_date_debut, date_format(resa_date_fin, '".$msg["format_date"]."') as aff_resa_date_fin " ;
	$requete = "SELECT notices_m.notice_id as m_id, notices_s.notice_id as s_id, resa_date_debut, resa_date_fin, resa_cb, resa_loc_retrait, trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit, ".$dates_resa_sql ;
	$requete.= "FROM (((resa LEFT JOIN notices AS notices_m ON resa_idnotice = notices_m.notice_id ) LEFT JOIN bulletins ON resa_idbulletin = bulletins.bulletin_id) LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id) ";
	$requete.= "WHERE id_resa='".$id_resa_print."' ";
	
	$res = mysql_query($requete, $link) or die (mysql_error()." $requete");
	$expl = mysql_fetch_object($res);
	
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
	
	$rqt_detail = "select resa_confirmee, resa_cb,location_libelle, expl_cote from resa 
	left join exemplaires on expl_cb=resa_cb 
	left join docs_location on idlocation=expl_location
	where id_resa =$id_resa_print  and resa_cb is not null and resa_cb!='' ";
	$res_detail = mysql_query ($rqt_detail) ;
	$expl_detail = mysql_fetch_object($res_detail);
	
	$ourPDF->SetXY ($x,$y);
	$ourPDF->setFont($pmb_pdf_font, 'BU', 10);
	$ourPDF->multiCell(($largeur - $x), 5, $expl->tit.$auteur,0, 'L', 0);
	$ourPDF->SetXY ($x+$retrait,$y+7);
	$ourPDF->setFont($pmb_pdf_font, '', 10);
	$ourPDF->multiCell(($largeur - $retrait - $x), 7, $msg[291]." : ".$expl_detail->resa_cb." $msg[296] : ".$expl_detail->expl_cote, 0, 'L', 0);
	$ourPDF->SetXY ($x+$retrait,$y+10);
	$ourPDF->setFont($pmb_pdf_font, '', 10);
	$ourPDF->multiCell(($largeur - $retrait - $x), 10, $msg['fpdf_valide']." ".$expl->aff_resa_date_debut."  ".$msg['fpdf_valable']." ", 0, 'L', 0);
	$ourPDF->SetXY (($x+$retrait+65),$y+10);
	$ourPDF->setFont($pmb_pdf_font, 'B', 10);
	$ourPDF->multiCell(($largeur - $x - $retrait - 65), 10, $expl->aff_resa_date_fin, 0, 'L', 0);
	
	if($pmb_transferts_actif && $transferts_choix_lieu_opac==3) {
		$rqt = "select resa_confirmee, resa_cb,resa_loc_retrait from resa where id_resa =$id_resa_print  and resa_cb is not null and resa_cb!='' ";
		$res = mysql_query ($rqt) ;
		if(($resa_lue = mysql_fetch_object($res))) {
			if ($resa_lue->resa_confirmee) {
				if ($resa_lue->resa_loc_retrait) {
					$loc_retait=$resa_lue->resa_loc_retrait;
				} else {
					$rqt = "select expl_location from exemplaires where expl_cb='".$resa_lue->resa_cb."' ";
					$res = mysql_query ($rqt) ;
					if(($res_expl = mysql_fetch_object($res))) {	
						$loc_retait=$res_expl->expl_location;
					}
				}
				$rqt = "select location_libelle from docs_location where idlocation=".$loc_retait;
				$res = mysql_query ($rqt) ;
				if(($res_expl = mysql_fetch_object($res))) {	
					$lieu_retrait=str_replace("!!location!!",$res_expl->location_libelle,$msg["resa_lettre_lieu_retrait"]);						
				}	
				$ourPDF->SetXY (($x+$retrait+110),$y+8);
				$ourPDF->setFont($pmb_pdf_font, 'B', 10);
				$ourPDF->multiCell(($largeur - $x - $retrait - 82), 8, $lieu_retrait, 0, 'L', 0);	
			}
		}
	} else {
		$ourPDF->SetXY (($x+$retrait+110),$y+8);
		$ourPDF->setFont($pmb_pdf_font, 'B', 10);
		$lieu_retrait=str_replace("!!location!!",$expl_detail->location_libelle,$msg["resa_lettre_lieu_retrait"]);
		$ourPDF->multiCell(($largeur - $x - $retrait - 82), 8, $lieu_retrait, 0, 'L', 0);	
	}
	
} /* fin notice_resa */


function notice_resa_planning($id_resa_print, $x, $y, $largeur, $retrait, $link) {
	
	global $ourPDF;
	global $msg;
	global $pmb_pdf_font;
	
	$dates_resa_sql = " date_format(resa_date_debut, '".$msg["format_date"]."') as aff_resa_date_debut, date_format(resa_date_fin, '".$msg["format_date"]."') as aff_resa_date_fin " ;
	$requete = "SELECT notice_id, resa_date_debut, resa_date_fin, trim(tit1) as tit, ".$dates_resa_sql ;
	$requete.= "FROM resa_planning LEFT JOIN notices ON resa_idnotice = notice_id  ";
	$requete.= "WHERE id_resa='".$id_resa_print."' ";
	
	$res = mysql_query($requete, $link) or die (mysql_error()." $requete");
	$expl = mysql_fetch_object($res);
	
	$responsabilites = get_notice_authors($expl->notice_id) ;
		
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
	
	$ourPDF->SetXY ($x,$y);
	$ourPDF->setFont($pmb_pdf_font, 'BU', 10);
	$ourPDF->multiCell(($largeur - $x), 8, $expl->tit.$auteur, 0, 'L', 0);
	
	$ourPDF->SetXY ($x+$retrait,$y+4);
	$ourPDF->setFont($pmb_pdf_font, '', 10);
	$ourPDF->multiCell(($largeur - $retrait - $x), 8, $msg['resa_planning_date_debut']." ".$expl->aff_resa_date_debut." ".$msg['resa_planning_date_fin']." ".$expl->aff_resa_date_fin, 0, 'L', 0);
	} /* fin notice_resa */
