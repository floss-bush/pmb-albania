<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liste_bulletinage.inc.php,v 1.5 2010-01-07 10:50:04 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// Impression PDF du bulletinage
require_once($class_path."/abts_pointage.class.php");
$pointage=new abts_pointage($serial_id);

function bulletinage_bulletin($fiche, $x, $y, $link, $short=0, $longmax=99999) {
	global $ourPDF;
	global $msg ;
	global $pmb_pdf_font;

	$ourPDF->SetXY ($x,$y);
	$ourPDF->setFont($pmb_pdf_font, '', 8);
	$ourPDF->multiCell(190, 8, formatdate($fiche['date_parution'])  , 0, 'L', 0);

	$ourPDF->SetXY ($x+20,$y);
	$ourPDF->setFont($pmb_pdf_font, '', 8);
	
	$titre = $fiche['libelle_notice']." / ".$fiche['libelle_abonnement'];
	$lgTitre = strlen($titre);
	if($lgTitre>75) $titre = substr($titre,0,75)."...";	
	$ourPDF->multiCell(140, 8, $titre.". ".$fiche['cote'], 0, 'L', 0);
	
	$ourPDF->SetXY ($x+140,$y);
	$ourPDF->setFont($pmb_pdf_font, 'B', 8);
	$ourPDF->multiCell(190, 8, $fiche['libelle_numero'], 0, 'L', 0);
	
}		

function bulletinage_categorie($titre, $x, $y, $link, $short=0, $longmax=99999) {
	global $ourPDF;
	global $msg ;
	global $pmb_pdf_font;
	
	$ourPDF->SetXY ($x,$y);
	$ourPDF->setFont($pmb_pdf_font, 'B', 12);
	$ourPDF->multiCell(190, 8, $titre  , 0, 'L', 0);				
}		

$ourPDF = new $fpdf('P', 'mm', 'A4');
$ourPDF->Open();

$liste_bulletin=$pointage->proceed();

$a_recevoir = $en_retard = $en_alerte = "";
$cpt_a_recevoir = $cpt_en_retard = $cpt_en_alerte = 0;					
if($liste_bulletin){
	//Tri par type de retard
	asort($liste_bulletin);
	foreach($liste_bulletin as $retard => $bulletin_retard){

				
		if($retard==0){
			$titre = $msg["pointage_label_a_recevoir"];				
		}	
		if($retard==1){
			$titre = $msg["pointage_label_en_retard"];	
		}			
		if ($retard==2){
			$titre = $msg["pointage_label_depasse"];	
		}
		if ($retard<=2){
			$ourPDF->addPage();

			$ourPDF->SetLeftMargin(10);
			$ourPDF->SetTopMargin(10);
	
			// paramétrage spécifique à ce document :
			$offsety = 0;
			if(!$pmb_hide_biblioinfo_letter) biblio_info( 10, 10, 1) ;
			$offsety=(ceil($ourPDF->GetStringWidth($biblio_name)/90)-1)*10; //90=largeur de la cell, 10=hauteur d'une ligne
			$i=0;
			$nb_page=0;
			$nb_par_page = 41;
			$nb_1ere_page = 39;
			$taille_bloc = 5;
			$titre.=" (".count($bulletin_retard)."):";					
			bulletinage_categorie ($titre,10,25+$offsety,$dbh, 1, 80);	
	
			$cpt=0;
			$contenu='';
			foreach($bulletin_retard as $id_bull => $fiche){
				if ($nb_page==0 && $i<$nb_1ere_page) {
					$pos_page = 50+$offsety+$taille_bloc*$i;
				}
				if (($nb_page==0 && $i==$nb_1ere_page) || ((($i-$nb_1ere_page) % $nb_par_page)==0)) {
					$ourPDF->addPage();
					$nb_page++;
				}
				if ($nb_page>=1) {
					$pos_page = 10+($taille_bloc*($i-$nb_1ere_page-($nb_page-1)*$nb_par_page));
				}
				bulletinage_bulletin ($fiche,10,$pos_page,$dbh, 1, 80);	
				$i++;														
			}
		}	
	}	
}	
header("Content-Type: application/pdf");
$ourPDF->OutPut();

?>