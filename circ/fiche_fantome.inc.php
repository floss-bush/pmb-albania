<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fiche_fantome.inc.php,v 1.1 2009-02-02 16:19:14 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// popup d'impression PDF pour la fiche fantome, créé pour les Archives Nationales du Monde du Travail   
// reçoit : id_empr et cb_doc

$ourPDF = new $fpdf('P', 'mm', 'A4');
$ourPDF->Open();
$ourPDF->addPage();
$ourPDF->SetLeftMargin(10);																		
$ourPDF->SetTopMargin(10);

function add_line($titre,$contens,$size_titre=50,$interligne=10){
	global $ourPDF,$pmb_pdf_font;

	$pos_y=$ourPDF->GetY();	
	$ourPDF->setFont($pmb_pdf_font, 'BI', 14);
	
	while($ourPDF->GetStringWidth($titre)<$size_titre-13){
		$titre.='.';
	}
	$ourPDF->multiCell($size_titre, $interligne, $titre, 0, 'L', 0);	
	$ourPDF->SetXY ($size_titre,$pos_y);
	$ourPDF->setFont($pmb_pdf_font, 'B', 14);
	$ourPDF->multiCell(150, $interligne, $contens, 0, 'L', 0);		
}

// Emprunteur
$requete = "SELECT empr_nom, empr_prenom FROM empr WHERE id_empr='$id_empr' ";
$res = mysql_query($requete);
$empr = mysql_fetch_object($res);
$emprunteur=$empr->empr_nom.' '.$empr->empr_prenom;

// recup titre	
$requete = "SELECT bulletin_numero,notices_m.notice_id as m_id, notices_s.notice_id as s_id, expl_cb, expl_cote, pret_date, pret_retour, tdoc_libelle, section_libelle, location_libelle, trim(concat(ifnull(notices_m.tit1,''),ifnull(notices_s.tit1,''),' ',ifnull(bulletin_numero,''), if (mention_date, concat(' (',mention_date,')') ,''))) as tit, ";
$requete.= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
$requete.= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, "; 
$requete.= " IF(pret_retour>sysdate(),0,1) as retard, notices_m.tparent_id, notices_m.tnvol " ; 
$requete.= " FROM (((exemplaires LEFT JOIN notices AS notices_m ON expl_notice = notices_m.notice_id ) LEFT JOIN bulletins ON expl_bulletin = bulletins.bulletin_id) LEFT JOIN notices AS notices_s ON bulletin_notice = notices_s.notice_id), docs_type, docs_section, docs_location, pret ";
$requete.= " WHERE expl_cb='".$cb_doc."' and expl_typdoc = idtyp_doc and expl_section = idsection and expl_location = idlocation and pret_idexpl = expl_id  ";
$res = mysql_query($requete);
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
	if($expl->tnvol)	$tit_serie .= ', '.$expl->tnvol;
}
if($tit_serie) {
	$expl->tit = $tit_serie.'. '.$expl->tit;
}

// cote: soit param persio de notice, ou bien la vraie cote de l'exemplaire
$p_perso=new parametres_perso("notices");
$cote=$expl->expl_cote;
if(!$cote)	$cote=$p_perso->read_base_fields_perso("ancienne_cote",$expl->s_id); 

$ourPDF->SetY (50);
add_line("Titre",$expl->tit );
add_line("No. exemplaire",$cote);
if($expl->bulletin_numero) add_line("Numéro",$numero);
add_line("Code",$cb_doc);
add_line("Date",$expl->aff_pret_date.' '. date("H:i"));
add_line("Emprunteur",$emprunteur);
$ourPDF->OutPut();

?>
