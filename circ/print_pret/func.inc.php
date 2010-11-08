<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func.inc.php,v 1.2 2009-05-16 11:12:04 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


require_once("$class_path/mono_display.class.php");
require_once("$class_path/serial_display.class.php");
require_once("$class_path/emprunteur.class.php");


function get_info_empr($id_empr) {
	$info_empr=new emprunteur ($id_empr);
	return $info_empr;
}
function get_info_expl_old($cb_expl) {
	global $dbh,$msg;
	
	if ($cb_expl ) {
		$query = "select * from exemplaires  left join docs_type on exemplaires.expl_typdoc=docs_type.idtyp_doc where expl_cb='$cb_expl' ";		
		$result = mysql_query($query, $dbh);
		if (($r= mysql_fetch_array($result))) {
			$info_expl->error_message="";	
			// empr ok	
			$info_expl->id_expl = $r['expl_id'];
			$info_expl->cb_expl = $r['expl_cb'];
			$info_expl->tdoc_libelle = $r['tdoc_libelle'];
			$info_expl->expl_notice = $r['expl_notice'];
			if ($info_expl->expl_notice) {
				$notice = new mono_display($info_expl->expl_notice, 0);
				$info_expl->libelle = $notice->header;
			} else {
				$bulletin = new bulletinage_display( $r['expl_bulletin']);
				$info_expl->libelle = $bulletin->display ;
				$info_expl->expl_notice = $r['expl_bulletin'];
			}
			$pos=strpos($info_expl->libelle,'<a');
			if($pos) $info_expl->libelle = substr($info_expl->libelle,0,strpos($info_expl->libelle,'<a'));		
							
		} else {
			$info_expl->error_message=$msg[367];
		}
	} else {
		$info_expl->error_message=$msg[367];
	}
	return $info_expl;
}

function get_info_expl($cb_doc) {
	global $dbh,$msg;
	
	$requete = "SELECT notices_m.notice_id as m_id, notices_s.notice_id as s_id, expl_cb, expl_cote, pret_date, pret_retour, tdoc_libelle, section_libelle, location_libelle, trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit, ";
	$requete.= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
	$requete.= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, "; 
	$requete.= " IF(pret_retour>sysdate(),0,1) as retard, notices_m.tparent_id, notices_m.tnvol " ; 
	$requete.= " FROM (((exemplaires LEFT JOIN notices AS notices_m ON expl_notice = notices_m.notice_id ) LEFT JOIN bulletins ON expl_bulletin = bulletins.bulletin_id) LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), docs_type, docs_section, docs_location, pret ";
	$requete.= " WHERE expl_cb='".$cb_doc."' and expl_typdoc = idtyp_doc and expl_section = idsection and expl_location = idlocation and pret_idexpl = expl_id  ";

	$res = mysql_query($requete, $dbh) or die ("<br />".mysql_error());
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
	return $expl;

} /* fin get_info_expl */
?>