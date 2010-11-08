<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: lettre.class.php,v 1.1 2010-08-11 10:08:22 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/amende.class.php");
require_once($class_path."/comptes.class.php");
require_once ("$include_path/notice_authors.inc.php");  
require_once($class_path."/serie.class.php");
require_once ("$class_path/author.class.php");  


class lettre{
	
	var $biblio_info = "";
	var $lecteur_info = "";
	var $groupe_info = "";
	var $amendes_info = "";
	var $expl_info = "";
	var $idempr = 0;
	var $id_groupe = 0;
	var $lettreXml = "";
	var $entete = "";
	var $type_lettre = "";
	
	/*
	 * Constructeur
	 */
	function lettre($id_empr=0,$type_lettre="",$id_groupe=0){
		$this->idempr = $id_empr;
		$this->type_lettre = $type_lettre;
		$this->id_groupe = $id_groupe;
		$this->entete = "<header>\n";
		$this->biblio_info();
		if($id_groupe){
			$this->groupe_info($this->id_groupe);
			$this->entete .= $this->biblio_info.$this->groupe_info.$this->lecteur_info;
		} else {
			$this->lecteur_info($this->idempr);
			$this->entete .= $this->biblio_info.$this->lecteur_info;
		}
		$this->entete .= "</header>\n";	
		$this->construire_xml($this->type_lettre);
	}
	
	/*
	 * Retourne le Xml bien formé
	 */
	function getXml(){
		return $this->lettreXml;
	}
	
	function construire_xml($type_lettre){
		$this->lettreXml .= "<lettre>\n";
		$this->lettreXml .= $this->entete;
		$this->lettreXml .= "</lettre>\n";
	}
	
	/*
	 * Bloc d'info de la biblio
	 */
	function biblio_info() {
	
		global $biblio_name, $biblio_logo, $biblio_adr1, $biblio_adr2, $biblio_cp, $biblio_town, $biblio_state, $biblio_country, $biblio_phone, $biblio_email, $biblio_website ;
		global $msg, $charset ;
		
		$this->biblio_info="\t<library>\n";
		 
		// afin de ne générer qu'une fois l'adr et compagnie 
		if ($this->biblio_info) {
			if ($biblio_name != "") $this->biblio_info .= "\t\t<name>".htmlspecialchars($biblio_name,ENT_QUOTES,$charset)."</name>\n";
			if ($biblio_adr1 != "") $this->biblio_info .= "\t\t<adr1>".htmlspecialchars($biblio_adr1,ENT_QUOTES,$charset)."</adr1>\n";
			if ($biblio_cp != "") $this->biblio_info .= "\t\t<cp>".htmlspecialchars($biblio_cp,ENT_QUOTES,$charset)."</cp>\n";
			if ($biblio_adr2 != "") $this->biblio_info .= "\t\t<adr2>".htmlspecialchars($biblio_adr2,ENT_QUOTES,$charset)."</adr2>\n";
			if ($biblio_state != "") $this->biblio_info .= "\t\t<state>".htmlspecialchars($biblio_state,ENT_QUOTES,$charset)."</state>\n";
			if ($biblio_town != "") $this->biblio_info .= "\t\t<town>".htmlspecialchars($biblio_town,ENT_QUOTES,$charset)."</town>\n";
			if ($biblio_phone != "") $this->biblio_info .= "\t\t<phone>".htmlspecialchars($msg['lettre_titre_tel'].$biblio_phone,ENT_QUOTES,$charset)."</phone>\n ";
			if ($biblio_email != "") $this->biblio_info .= "\t\t<mail>@ : ".htmlspecialchars($biblio_email,ENT_QUOTES,$charset)."</mail>\n ";
			if ($biblio_website != "") $this->biblio_info .= "\t\t<website>Web : ".htmlspecialchars($biblio_website,ENT_QUOTES,$charset)."</website>\n";
			if ($biblio_country != "") $this->biblio_info .= "\t\t<country>".htmlspecialchars($biblio_country,ENT_QUOTES,$charset)."</country>\n";
		}
			
		if ($biblio_logo) $this->biblio_info .= "\t\t<logo>./images/".htmlspecialchars($biblio_logo,ENT_QUOTES,$charset)."</logo>\n";
		
		$this->biblio_info .= "\t</library>\n";  
	} 
	
	/*
	 * Bloc info du lecteur
	 */
	function lecteur_info($id_empr){
		
		global $msg, $dbh,$charset,$niveau, $forcename;
		
		$requete = "SELECT group_concat(libelle_groupe SEPARATOR ', ') as_all_groupes, 1 as rien from groupe join empr_groupe on groupe_id=id_groupe WHERE lettre_rappel=1 and empr_id='$id_empr' group by rien ";
		$lib_all_groupes=pmb_sql_value($requete);
		if ($lib_all_groupes) 
			$lib_all_groupes = "\t\t<lib_all_groupes>".htmlspecialchars($lib_all_groupes,ENT_QUOTES,$charset)."</lib_all_groupes>\n"; 
		
		$concerne="";
		$temp_id_empr=$id_empr;
		
		if(!$forcename){
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
				$concerne="\t\t<concerne>".htmlspecialchars(sprintf($msg["adresse_retard_concerne"],mysql_result($res,0,0)),ENT_QUOTES,$charset)."</concerne>\n";
			} 
		}	
		
		$requete = "SELECT id_empr, empr_cb, empr_nom, empr_prenom, empr_adr1, empr_adr2, empr_cp, empr_ville, empr_pays, empr_mail, empr_tel1, empr_tel2, empr_date_adhesion, empr_date_expiration, date_format(empr_date_adhesion, '".$msg["format_date"]."') as aff_empr_date_adhesion, date_format(empr_date_expiration, '".$msg["format_date"]."') as aff_empr_date_expiration FROM empr WHERE id_empr='$temp_id_empr' ";
		$res = mysql_query($requete, $dbh);
		$empr = mysql_fetch_object($res);

		$this->lecteur_info = "\t<patron>\n";
		$this->lecteur_info .= "\t\t<id>".htmlspecialchars($id_empr,ENT_QUOTES,$charset)."</id>\n";
		$this->lecteur_info .= "\t\t<name>".htmlspecialchars($empr->empr_prenom,ENT_QUOTES,$charset)."</name>\n";
		$this->lecteur_info .= "\t\t<surname>".htmlspecialchars($empr->empr_nom,ENT_QUOTES,$charset)."</surname>\n";
	
	
		if ($empr->empr_adr1 != "") $this->lecteur_info .= "\t\t<adr1>".htmlspecialchars($empr->empr_adr1,ENT_QUOTES,$charset)."</adr1>\n" ;
		if ($empr->empr_adr2 != "") $this->lecteur_info .= "\t\t<adr2>".htmlspecialchars($empr->empr_adr2,ENT_QUOTES,$charset)."</adr2>\n" ;
		if ($empr->empr_cp != "") $this->lecteur_info .= "\t\t<cp>".htmlspecialchars($empr->empr_cp,ENT_QUOTES,$charset)."</cp>\n";
		if ($empr->empr_ville != "") $this->lecteur_info .= "\t\t<town>".htmlspecialchars($empr->empr_ville,ENT_QUOTES,$charset)."</town>\n" ;
		if ($empr->empr_pays != "") $this->lecteur_info .= "\t\t<country>".htmlspecialchars($empr->empr_pays,ENT_QUOTES,$charset)."</country>\n" ;
		if ($empr->empr_tel1 != "") 
			$this->lecteur_info .= "\t\t<phone1>".htmlspecialchars($msg['fpdf_tel1']." ".$empr->empr_tel1,ENT_QUOTES,$charset)."</phone1>\n" ;
		if ($empr->empr_tel2 != "") 
			$this->lecteur_info .= "\t\t<phone2>".htmlspecialchars($msg['fpdf_tel2']." ".$empr->empr_tel2,ENT_QUOTES,$charset)."</phone2>\n";
		if ($empr->empr_mail != "")
			$this->lecteur_info .= "\t\t<mail>".htmlspecialchars($empr->empr_mail)."</mail>\n"; 

		if($empr->empr_cb)
			$this->lecteur_info .= "\t\t<cb>".htmlspecialchars($msg['fpdf_carte']." ".$empr->empr_cb,ENT_QUOTES,$charset)."</cb>\n";
		if($empr->aff_empr_date_adhesion)	
			$this->lecteur_info .= "\t\t<subscripion_date>".htmlspecialchars($empr->aff_empr_date_adhesion,ENT_QUOTES,$charset)."</subscripion_date>\n";
		if($empr->aff_empr_date_expiration)
			$this->lecteur_info .= "\t\t<deadline>".htmlspecialchars($empr->aff_empr_date_expiration,ENT_QUOTES,$charset)."</deadline>\n";
			
		$this->lecteur_info .= $lib_all_groupes;
		$this->lecteur_info .= $concerne;
		$this->lecteur_info .= "\t\t<empr_niveau>".$niveau."</empr_niveau>";
		
		$this->lecteur_info .= "\t</patron>\n";
	}
	
	/*
	 * Info du groupe
	 */
	function groupe_info($id_groupe,$no_cb=false) {
		global $dbh, $charset;
		global $pmb_pdf_font;
		global $pmb_afficher_numero_lecteur_lettres;
		
		$requete = "SELECT libelle_groupe, resp_groupe  FROM groupe WHERE id_groupe='$id_groupe' ";
		$res = mysql_query($requete, $dbh);
		$groupe = mysql_fetch_object($res);
		
		$this->groupe_info = "\t<group>\n";
		$this->groupe_info .= "\t\t<group_label>".htmlspecialchars($groupe->libelle_groupe,ENT_QUOTES,$charset)."</group_label>\n";
		$this->groupe_info .= "\t</group>\n";
		
		if ($groupe->resp_groupe) {			
			$this->lecteur_info($groupe->resp_groupe,0, $no_cb || !$pmb_afficher_numero_lecteur_lettres) ;
		}
	}
	
	/*
	 * Bloc des amendes
	 */
	function print_amendes($valeur,$frais_relance) {
		$this->amendes_info = "\t<fees>\n";
		$this->amendes_info .= "\t\t<fees_amount>".htmlspecialchars(comptes::format_simple($valeur),ENT_QUOTES,$charset)."</fees_amount>\n";
		$this->amendes_info .= "\t\t<postal_charge>".htmlspecialchars(comptes::format_simple($frais_relance),ENT_QUOTES,$charset)."</postal_charge>\n";
		$this->amendes_info .= "\t\t<total>".htmlspecialchars(comptes::format_simple($valeur+$frais_relance),ENT_QUOTES,$charset)."</total>\n";
		$this->amendes_info .= "\t</fees>\n";
	}
		
}

/*
 * Récupération des données de la lettre de relance
 */
class lettre_relance extends lettre {

	function lettre_relance($id_empr=0,$type_lettre,$id_groupe=0){
		$this->lettre($id_empr,$type_lettre,$id_groupe);
	}
	
	function construire_xml($type_lettre){
		$this->lettreXml .= "<lettre>\n";
		$this->lettreXml .= $this->entete;
		$this->lettreXml .= "<specifics>\n"; 
		switch($type_lettre){
			case 'liste_pret':
			case 'lettre_retard':
				$this->lettreXml .= $this->lettre_retard_par_lecteur($this->idempr);
				$this->lettreXml .= $this->amendes_info;
				break;
			case 'liste_pret_groupe':
			case 'lettre_retard_groupe':
				$this->lettreXml .= $this->lettre_retard_par_groupe($this->id_groupe);
				$this->lettreXml .= $this->amendes_info;
				break;
		}		
		
		$this->lettreXml .= "</specifics>\n";	
		$this->lettreXml .= "</lettre>\n";
	}	
	
	function lettre_retard_par_lecteur($id_empr) {
		global $dbh, $msg ,$pmb_gestion_financiere, $pmb_gestion_amende, $niveau;
		
		//Pour les amendes
		$valeur=0;		
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
				$valeur += $this->expl_retard ($data['expl_cb']);
			}		
			$this->print_amendes($valeur,$frais_relance);
					
			$retards = "\t<retards>\n".$this->expl_info."\t</retards>\n";
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
					$valeur += $this->expl_retard ($cb_expl);		
				}
				// affiche retards niveau 3
				foreach($liste_r3 as $cb_expl) {
					$valeur += $this->expl_retard ($cb_expl,3);							
				}			
				$this->print_amendes($valeur,$frais_relance);		
				$retards = "\t<retards>\n".$this->expl_info."\t</retards>\n";
			} else {
				// il n'y a que des retards niveau 3
				foreach($liste_r3 as $cb_expl) {
					$valeur += $this->expl_retard ($cb_expl,3);
					$retards .= $this->expl_info;							
				}		
				$this->print_amendes($valeur,$frais_relance);
				$retards = "\t<retards>\n".$this->expl_info."\t</retards>\n";				
			}					
		}		
		
		return $retards;
	} 
	
	function expl_retard($cb_doc,$niveau=0,$id_empr=0) {
	
		global $msg, $dbh, $charset;
		global $pmb_gestion_financiere, $pmb_gestion_amende;
				
		$valeur=0;
		$dates_resa_sql = " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour " ;
		$requete = "SELECT notices_m.notice_id as m_id, notices_s.notice_id as s_id, pret_idempr, expl_id, expl_cb,expl_cote, pret_date, pret_retour, tdoc_libelle, section_libelle, location_libelle, trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date!='', concat(' (',mention_date,')') ,''))) as tit, ".$dates_resa_sql.", " ;
		$requete.= " notices_m.tparent_id, notices_m.tnvol " ; 
		$requete.= " FROM (((exemplaires LEFT JOIN notices AS notices_m ON expl_notice = notices_m.notice_id ) LEFT JOIN bulletins ON expl_bulletin = bulletins.bulletin_id) LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), docs_type, docs_section, docs_location, pret ";
		$requete.= " WHERE expl_cb='".$cb_doc."' and expl_typdoc = idtyp_doc and expl_section = idsection and expl_location = idlocation and pret_idexpl = expl_id  ";
		
		$res = mysql_query($requete, $dbh) or die (mysql_error()." $requete");
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
		
		$this->expl_info .= " \t<expl>\n";
		$this->expl_info .= "\t\t<label>".htmlspecialchars($libelle,ENT_QUOTES,$charset)."</label>\n";
		$this->expl_info .= "\t\t<loan_date>".htmlspecialchars($expl->aff_pret_date,ENT_QUOTES,$charset)."</loan_date>\n";
		$this->expl_info .= "\t\t<return_date>".htmlspecialchars($expl->aff_pret_retour,ENT_QUOTES,$charset)."</return_date>\n";
		$this->expl_info .= "\t\t<location>".htmlspecialchars($expl->location_libelle,ENT_QUOTES,$charset)."</location>\n";
		$this->expl_info .= "\t\t<section>".htmlspecialchars($expl->section_libelle,ENT_QUOTES,$charset)."</section>\n";
		$this->expl_info .= "\t\t<expl_cb>".htmlspecialchars($expl->expl_cb,ENT_QUOTES,$charset)."</expl_cb>\n";
		$this->expl_info .= "\t\t<expl_cote>".htmlspecialchars($expl->expl_cote,ENT_QUOTES,$charset)."</expl_cote>\n";	
		$this->expl_info .= "\t\t<tdoc_label>".htmlspecialchars($expl->tdoc_libelle,ENT_QUOTES,$charset)."</tdoc_label>\n";
			
		if (($pmb_gestion_financiere)&&($pmb_gestion_amende)) {
			$amende=new amende($expl->pret_idempr);
			$amd=$amende->get_amende($expl->expl_id);
			if ($amd["valeur"]) {
				$this->expl_info .= "\t\t<expl_fee>".htmlspecialchars(comptes::format_simple($amd["valeur"]),ENT_QUOTES,$charset)."</expl_fee>\n";
				$valeur=$amd["valeur"];
			}
		}
		if($niveau) $this->expl_info .= "\t\t<expl_niveau>3</expl_niveau>\n";
		if($id_empr){
			$req_empr = "select empr_nom as nom, empr_prenom as prenom from empr where id_empr=$id_empr";
			$res = mysql_query($req_empr,$dbh);
			$empr = mysql_fetch_object($res);
			$this->expl_info .= "<empr_surname>".htmlspecialchars($empr->nom,ENT_QUOTES,$charset)."</empr_surname>";
			$this->expl_info .= "<empr_name>".htmlspecialchars($empr->prenom,ENT_QUOTES,$charset)."</empr_name>";
		}
		
		$this->expl_info .= " \t</expl>\n";
		
		return $valeur;
		
	}
	
	function lettre_retard_par_groupe($id_groupe, $lecteurs_ids=array()) {

		global $dbh, $msg;
		global $pmb_hide_biblioinfo_letter;
			
		if ($lecteurs_ids)
			$lecteur_ids_text = " AND id_empr in (".implode(",",$lecteurs_ids).")";
		else
			$lecteur_ids_text = "";
			
		$rqt = "select  empr_id, expl_cb from pret, exemplaires, empr_groupe, empr where groupe_id='".$id_groupe."' and pret_retour < curdate() and pret_idexpl=expl_id and empr_id=pret_idempr and empr_id=id_empr $lecteur_ids_text order by empr_nom, empr_prenom, pret_date " ;
		$req = mysql_query($rqt, $dbh) or die ($msg['err_sql'].'<br />'.$rqt.'<br />'.mysql_error()); 
		while ($data = mysql_fetch_array($req)) {
			$this->expl_retard($data['expl_cb'],0,$data['empr_id']);
		}
		$retards = "\t<retards>".$this->expl_info."</retards>";
		
		return $retards;
	} 
}

/*
 * Récupération des données de la lettre de reservation
 */
class lettre_reservation extends lettre{
	
	var $id_empr_tmp = array();
	var $notice_resa = "";
	var $notice_resa_planning = "";
	
	function lettre_reservation($ids_resa=array(),$type_lettre){
		global $dbh;
		
		if($type_lettre=='lettre_resa_planning'){
			$rqt = "select resa_idempr from resa_planning where id_resa in ('".implode("','",$ids_resa)."')  ";
			$res = mysql_query ($rqt, $dbh) ;
		} else {
			$rqt = "select resa_idempr from resa where id_resa in ('".implode("','",$ids_resa)."') ";
			$res = mysql_query ($rqt, $dbh) ;
		}	
		
		while (($resa_validee=mysql_fetch_object($res))){
			if(array_search($resa_validee->resa_idempr,$this->id_empr_tmp) === false){
				$this->lettre($resa_validee->resa_idempr,$type_lettre);
				$this->id_empr_tmp[]=$resa_validee->resa_idempr;	
			}
		}
	}
	
	function construire_xml($type_lettre){
		$this->notice_resa = "";
		$this->lettreXml .= "<lettre>\n";
		$this->lettreXml .= $this->entete;
		$this->lettreXml .= "<specifics>\n"; 
		switch($type_lettre){
			case 'lettre_resa':
				$this->lettre_resa($this->idempr,$type_lettre);
				$this->lettreXml .= "<resas>".$this->notice_resa."</resas>";
				break;
			case 'lettre_resa_planning':
				$this->lettre_resa($this->idempr,$type_lettre);
				$this->lettreXml .= "<resas>".$this->notice_resa_planning."</resas>";
				break;
		}		
		
		$this->lettreXml .= "</specifics>\n";	
		$this->lettreXml .= "</lettre>\n";
	}	
	
	function lettre_resa($id_empr,$type_lettre=""){
		global $dbh;
		
		if($type_lettre == 'lettre_resa_planning'){
			$rqt = "select id_resa from resa_planning where resa_idempr='$id_empr' order by resa_date_debut " ;
			$req = mysql_query($rqt, $dbh) or die('Erreur SQL !<br />'.$rqt.'<br />'.mysql_error()); 
			
			while($resa = mysql_fetch_object($req)){
				$this->notice_resa_planning($resa->id_resa);
			}
		} else {		
			$rqt = "select id_resa from resa where resa_idempr='$id_empr' and resa_cb is not null and resa_cb!='' order by resa_date_debut " ;
			$req = mysql_query($rqt, $dbh) or die('Erreur SQL !<br />'.$rqt.'<br />'.mysql_error()); 
	
			while($resa = mysql_fetch_object($req)){
				$this->notice_resa($resa->id_resa);
			}
		}		
	}
	
	/* 
	 * Info de la ligne de resa pour une notice sur la lettre de confirmation de réservation
	 */
	function notice_resa($id_resa_print) {
		
		
		global $msg, $dbh, $charset;
		global $pmb_transferts_actif,$transferts_choix_lieu_opac;
		
		$dates_resa_sql = " date_format(resa_date_debut, '".$msg["format_date"]."') as aff_resa_date_debut, date_format(resa_date_fin, '".$msg["format_date"]."') as aff_resa_date_fin " ;
		$requete = "SELECT notices_m.notice_id as m_id, notices_s.notice_id as s_id, resa_date_debut, resa_date_fin, resa_cb, resa_loc_retrait, trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit, ".$dates_resa_sql ;
		$requete.= "FROM (((resa LEFT JOIN notices AS notices_m ON resa_idnotice = notices_m.notice_id ) LEFT JOIN bulletins ON resa_idbulletin = bulletins.bulletin_id) LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id) ";
		$requete.= "WHERE id_resa='".$id_resa_print."' ";
		
		$res = mysql_query($requete, $dbh) or die (mysql_error()." $requete");
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
		
		$this->notice_resa .= "\t\t<notice_resa>\n";
		$this->notice_resa .= "\t<tit>".htmlspecialchars($expl->tit.$auteur,ENT_QUOTES,$charset)."</tit>\n";
		$this->notice_resa .= "\t<author>".htmlspecialchars($auteur,ENT_QUOTES,$charset)."</author>\n";
		$this->notice_resa .= "\t<resa_cb>".htmlspecialchars($expl->resa_cb,ENT_QUOTES,$charset)."</resa_cb>\n";
		$this->notice_resa .= "\t<expl_cote>".htmlspecialchars($expl->expl_cote,ENT_QUOTES,$charset)."</expl_cote>\n";
		$this->notice_resa .= "\t<start_date>".htmlspecialchars($expl->aff_resa_date_debut,ENT_QUOTES,$charset)."</start_date>\n";
		$this->notice_resa .= "\t<deadline>".htmlspecialchars($expl->aff_resa_date_fin,ENT_QUOTES,$charset)."</deadline>\n";
		
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
					$this->notice_resa .= "\t\t<location>".htmlspecialchars($lieu_retrait,ENT_QUOTES,$charset)."</location>\n";	
				}
			}
		} else {
			$lieu_retrait=str_replace("!!location!!",$expl_detail->location_libelle,$msg["resa_lettre_lieu_retrait"]);
			$this->notice_resa .= "\t\t<location>".htmlspecialchars($lieu_retrait,ENT_QUOTES,$charset)."</location>\n";	
		}
		
		$this->notice_resa .="\t</notice_resa>\n";
	} /* fin notice_resa */

	/*
	 * Bloc d'info notice_resa_planning
	 */
	function notice_resa_planning($id_resa_print) {
	
		global $msg, $dbh, $charset;
		
		$dates_resa_sql = " date_format(resa_date_debut, '".$msg["format_date"]."') as aff_resa_date_debut, date_format(resa_date_fin, '".$msg["format_date"]."') as aff_resa_date_fin " ;
		$requete = "SELECT notice_id, resa_date_debut, resa_date_fin, trim(tit1) as tit, ".$dates_resa_sql ;
		$requete.= "FROM resa_planning LEFT JOIN notices ON resa_idnotice = notice_id  ";
		$requete.= "WHERE id_resa='".$id_resa_print."' ";
		
		$res = mysql_query($requete, $dbh) or die (mysql_error()." $requete");
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
		
		$this->notice_resa_planning .= "\t<notice_resa>\n";
		$this->notice_resa_planning .= "\t\t<tit>".htmlspecialchars($expl->tit,ENT_QUOTES,$charset)."</tit>\n";
		$this->notice_resa_planning .= "\t\t<author>".htmlspecialchars($auteur,ENT_QUOTES,$charset)."</author>\n";
		$this->notice_resa_planning .= "\t\t<start_date>".htmlspecialchars($expl->aff_resa_date_debut,ENT_QUOTES,$charset)."</start_date>\n";
		$this->notice_resa_planning .= "\t\t<deadline>".htmlspecialchars($expl->aff_resa_date_fin,ENT_QUOTES,$charset)."</deadline>\n";
		$this->notice_resa_planning .= "\t</notice_resa>\n";
	}
}