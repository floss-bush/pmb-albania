<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: lettre-relance-adhesion.inc.php,v 1.16 2010-01-07 10:50:04 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/marc_table.class.php");
require_once("$class_path/mono_display.class.php");
require_once("$class_path/emprunteur.class.php");

// popup d'impression PDF pour lettre de relance d'abonnement

// la formule de politesse du bas (le signataire)
$var = "pdflettreadhesion_fdp";
eval ("\$fdp=\"".$$var."\";");


// le "Madame, Monsieur," ou tout autre truc du genre "Cher adhérent,"
$var = "pdflettreadhesion_madame_monsieur";
eval ("\$madame_monsieur=\"".$$var."\";");

// le texte
$var = "pdflettreadhesion_texte";
eval ("\$texte=\"".$$var."\";");

// la marge gauche des pages
$var = "pdflettreadhesion_marge_page_gauche";
$marge_page_gauche = $$var;

// la marge droite des pages
$var = "pdflettreadhesion_marge_page_droite";
$marge_page_droite = $$var;

// la largeur des pages
$var = "pdflettreadhesion_largeur_page";
$largeur_page = $$var;

// la hauteur des pages
$var = "pdflettreadhesion_hauteur_page";
$hauteur_page = $$var;

// le format des pages
$var = "pdflettreadhesion_format_page";
$format_page = $$var;

$taille_doc=array($largeur_page,$hauteur_page);

$ourPDF = new $fpdf($format_page, 'mm', $taille_doc);
$ourPDF->Open();

if ($action=="print_all") {
	// restriction localisation le cas échéant
	if ($pmb_lecteurs_localises) {
		if ($empr_location_id=="") $empr_location_id = $deflt2docs_location ;
		if ($empr_location_id!=0) $restrict_localisation = " AND empr_location='$empr_location_id' ";
			else $restrict_localisation = "";
	}

	// filtré par un statut sélectionné
	if ($empr_statut_edit) {
		if ($empr_statut_edit!=0) $restrict_statut = " AND empr_statut='$empr_statut_edit' ";
			else $restrict_statut="";
	} 
	$requete = "SELECT empr.id_empr FROM empr ";
	$restrict_empr = " WHERE 1 ";
	$restrict_requete = $restrict_empr.$restrict_localisation.$restrict_statut." and ".$restricts;
	$requete .= $restrict_requete;
	if ($empr_relance_adhesion==1) $requete.=" and empr_mail=''";
	$requete .= " ORDER BY empr_nom, empr_prenom ";
	
	$res = @mysql_query($requete, $dbh);
	
	while(($empr=mysql_fetch_object($res))) {
		$ourPDF->addPage();
		$ourPDF->SetMargins($marge_page_gauche,$marge_page_gauche);

		if(!$pmb_hide_biblioinfo_letter) biblio_info( $marge_page_gauche, 10) ;
		lecteur_adresse($empr->id_empr, ($marge_page_gauche+90), 45, $dbh, !$pmb_afficher_numero_lecteur_lettres);

		$ourPDF->SetXY ($marge_page_gauche,125);
		$ourPDF->setFont($pmb_pdf_font, '', 12);
		$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $madame_monsieur, 0, 'L', 0);

		// mettre ici le texte 
		$empr_temp = new emprunteur($empr->id_empr, '', FALSE, 0);
		$texte = str_replace("!!date_fin_adhesion!!", $empr_temp->aff_date_expiration, $texte);
		$ourPDF->SetXY ($marge_page_gauche,135);
		$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $texte, 0, 'J', 0);

		//
		$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $fdp, 0, 'R', 0);
	}
	mysql_free_result($res);
} else {
	$ourPDF->addPage();
	$ourPDF->SetMargins($marge_page_gauche,$marge_page_gauche);

	if(!$pmb_hide_biblioinfo_letter) biblio_info( $marge_page_gauche, 10) ;
	lecteur_adresse($id_empr, ($marge_page_gauche+90), 45, $dbh, !$pmb_afficher_numero_lecteur_lettres);

	$ourPDF->SetXY ($marge_page_gauche,125);
	$ourPDF->setFont($pmb_pdf_font, '', 12);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $madame_monsieur, 0, 'L', 0);

	// mettre ici le texte 
	$empr_temp = new emprunteur($id_empr, '', FALSE, 0);
	$texte = str_replace("!!date_fin_adhesion!!", $empr_temp->aff_date_expiration, $texte);
	$ourPDF->SetXY ($marge_page_gauche,135);
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $texte, 0, 'J', 0);

	//
	$ourPDF->multiCell(($largeur_page - $marge_page_droite - $marge_page_gauche), 8, $fdp, 0, 'R', 0);
}		

$ourPDF->OutPut();
