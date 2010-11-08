<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: lettre-livraison.inc.php,v 1.13 2009-06-02 19:17:42 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// popup d'impression PDF pour bon de livraison
// reçoit : id_liv


if (!$id_liv) {print "<script> self.close(); </script>" ; die;}

//Footer personalisé
class PDF extends FPDF
{
	function Footer() {
		
		global $msg;
		global $y_footer, $fs_footer;
		
	    $this->SetY(-$y_footer);
	    //Numéro de page centré
	    $this->Cell(0,$fs_footer,$msg['acquisition_act_page'].$this->PageNo().' / '.$this->AliasNbPages,0,0,'C');
	}
}

if ($acquisition_pdfliv_print) {
	require_once($acquisition_pdfliv_print);
} else {
	
	require_once("$class_path/entites.class.php");
	require_once("$class_path/coordonnees.class.php");
	require_once("$class_path/actes.class.php");
	require_once("$class_path/lignes_actes.class.php");
	require_once("$class_path/liens_actes.class.php");
	require_once("$class_path/types_produits.class.php");
	
	//paramètres modifiables-----------------------------------------------------------------------------
	
	if (!$acquisition_pdfliv_text_size) $fs = '10';	//Taille de la police 
		else $fs = $acquisition_pdfliv_text_size; 
	
	$format_page = explode('x',$acquisition_pdfliv_format_page);
	if(!$format_page[0]) $largeur_page = '210';			//largeur de page
		else $largeur_page = $format_page[0];
	if(!$format_page[1]) $hauteur_page = '297';		//hauteur de page
		else $hauteur_page = $format_page[1];
	
	if(!$acquisition_pdfliv_orient_page) $orient_page = 'P';		//orientation page (P=portrait, L=paysage)
		else $orient_page = $acquisition_pdfliv_orient_page;
	
	$marges_page = explode(',', $acquisition_pdfliv_marges_page);
	if (!$marges_page[0]) $marge_haut = '10';		//marge haut
		else $marge_haut = $marges_page[0];
	if (!$marges_page[1]) $marge_bas = '20';		//marge bas
		else $marge_bas = $marges_page[1];
	if (!$marges_page[2]) $marge_droite = '10';		//marge droite
		else $marge_droite = $marges_page[2];
	if (!$marges_page[3]) $marge_gauche = '10';		//marge gauche
		else $marge_gauche = $marges_page[3];
	
	$pos_raison = explode(',', $acquisition_pdfliv_pos_raison);
	if (!$pos_raison[0]) $x_raison = '10';			//Distance raison sociale / bord gauche de page
		else $x_raison = $pos_raison[0];
	if (!$pos_raison[1]) $y_raison = '10';			//Distance raison sociale / bord haut de page
		else $y_raison = $pos_raison[1];
	if (!$pos_raison[2]) $l_raison = '100';			//Largeur raison sociale
		else $l_raison = $pos_raison[2];
	if (!$pos_raison[3]) $h_raison = '10';			//Hauteur raison sociale
		else $h_raison = $pos_raison[3];
	if (!$pos_raison[4]) $fs_raison = '16';			//Police raison sociale
		else $fs_raison = $pos_raison[4];
	
	$pos_date = explode(',', $acquisition_pdfliv_pos_date);
	if (!$pos_date[0]) $x_date = '170';				//Distance date / bord gauche de page
		else $x_date = $pos_date[0];
	if (!$pos_date[1]) $y_date = '10';				//Distance date / bord haut de page
		else $y_date = $pos_date[1];
	if (!$pos_date[2]) $l_date = '0';				//Largeur date
		else $l_date = $pos_date[2];
	if (!$pos_date[3]) $h_date = '6';				//Hauteur date
		else $h_date = $pos_date[3];
	if (!$pos_date[4]) $fs_date = '8';				//Police date
		else $fs_date = $pos_date[4];
	
	$pos_adr_liv = explode(',', $acquisition_pdfliv_pos_adr_liv);
	if (!$pos_adr_liv[0]) $x_adr_liv = '10';			//Distance adr livraison / bord gauche de page
		else $x_adr_liv = $pos_adr_liv[0];
	if (!$pos_adr_liv[1]) $y_adr_liv = '20';			//Distance adr livraison / bord haut de page
		else $y_adr_liv = $pos_adr_liv[1];
	if (!$pos_adr_liv[2]) $l_adr_liv = '60';			//Largeur adr livraison
		else $l_adr_liv = $pos_adr_liv[2];
	if (!$pos_adr_liv[3]) $h_adr_liv = '5';				//Hauteur adr livraison
		else $h_adr_liv = $pos_adr_liv[3];
	if (!$pos_adr_liv[4]) $fs_adr_liv = '10';			//Police adr livraison
		else $fs_adr_liv = $pos_adr_liv[4];
	$text_adr_liv = $msg['acquisition_adr_liv'];
	$text_adr_liv_tel = $msg['acquisition_tel'].".";
	
	$pos_adr_fou = explode(',', $acquisition_pdfliv_pos_adr_fou);
	if (!$pos_adr_fou[0]) $x_adr_fou = '110';			//Distance adr fournisseur / bord gauche de page
		else $x_adr_fou = $pos_adr_fou[0];
	if (!$pos_adr_fou[1]) $y_adr_fou = '20';			//Distance adr fournisseur / bord haut de page
		else $y_adr_fou = $pos_adr_fou[1];
	if (!$pos_adr_fou[2]) $l_adr_fou = '100';			//Largeur adr fournisseur
		else $l_adr_fou = $pos_adr_fou[2];
	if (!$pos_adr_fou[3]) $h_adr_fou = '5';				//Hauteur adr fournisseur
		else $h_adr_fou = $pos_adr_fou[3];
	if (!$pos_adr_fou[4]) $fs_adr_fou = '10';			//Police adr fournisseur
		else $fs_adr_fou = $pos_adr_fou[4];
	$text_adr_fou = $msg['acquisition_ach_fou2'];
	
	$pos_num = explode(',', $acquisition_pdfliv_pos_num);
	if (!$pos_num[0]) $x_num = '10';				//Distance num livraison / bord gauche de page
		else $x_num = $pos_num[0];
	if (!$pos_num[1]) $y_num = '60';			//Distance num livraison / bord haut de page
		else $y_num = $pos_num[1];
	if (!$pos_num[2]) $l_num = '0';					//Largeur num livraison
		else $l_num = $pos_num[2];
	if (!$pos_num[3]) $h_num = '6';				//Hauteur num livraison
		else $h_num = $pos_num[3];
	if (!$pos_num[4]) $fs_num = '14';				//Police num livraison
		else $fs_num = $pos_num[4];
	$text_num_liv = $msg['acquisition_act_num_liv'];
	$text_liv_ref_fou = $msg['acquisition_liv_ref_fou'];
	$text_num_cde = $msg['acquisition_act_num_cde'];
	
	$pos_tab_ = explode(',', $acquisition_pdfliv_tab_liv);
	if (!$pos_tab[0]) $h_tab = '5';				//Hauteur de ligne table livraison
		else $h_tab = $pos_tab[0];
	if (!$pos_tab[1]) $fs_tab = '10';			//Police table livraison
		else $fs_tab = $pos_tab[1];
	$x_tab = $marge_gauche;						//position table livraison / bord droit page 
	$y_tab = $marge_haut;						//position table livraison / haut page sur pages 2 et + 
	
	$pos_footer = explode(',', $acquisition_pdfliv_pos_footer);
	if (!$pos_footer[0]) $y_footer = '15';			//Distance footer / bas de page
		else $y_footer = $pos_footer[0];
	if (!$pos_footer[1]) $fs_footer = '8';			//Police footer
		else $fs_footer = $pos_footer[1];
	
	//---------------------------------------------------------------------------------------------------------------------
	
	$taille_doc=array($largeur_page,$hauteur_page);
	$w = $largeur_page-$marge_gauche-$marge_droite;
	$ourPDF = new PDF($orient_page, 'mm', $taille_doc);
	$ourPDF->Open();
	$ourPDF->SetMargins($marge_gauche, $marge_haut, $marge_droite);
	
	//On récupère les infos de la livraison
	$liv = new actes($id_liv);
	$lignes = $liv->getLignes();
	$bibli = new entites ($liv->num_entite);
	$coord_liv = new coordonnees($liv->num_contact_livr);
	
	$fou = new entites($liv->num_fournisseur);
	$coord_fou = entites::get_coordonnees($liv->num_fournisseur, '1');
	$coord_fou = mysql_fetch_object($coord_fou);
	
	$id_cde = liens_actes::getParent($id_liv);
	$cde = new actes($id_cde);
	
	$ourPDF->addPage();
	
	$ourPDF->setFont($pmb_pdf_font);
	
	//Affichage date 
	$date =  formatdate(today());
	$ourPDF->setFontSize($fs_date);
	$ourPDF->SetXY($x_date, $y_date);
	$ourPDF->Cell($l_date, $h_date, $date, 0, 0, 'L', 0);
	
	//Affichage raison sociale
	$raison =  $bibli->raison_sociale;
	$ourPDF->setFontSize($fs_raison);
	$ourPDF->SetXY($x_raison, $y_raison);
	$ourPDF->Cell($l_raison, $h_raison, $raison, 0, 0, 'L', 0);
	
	//Affichage coordonnees fournisseur
	$adr_fou = $text_adr_fou."\n";
	if($fou->raison_sociale != '') $adr_fou.= $fou->raison_sociale."\n";
	if($coord_fou->libelle != '') $adr_fou.= $coord_fou->libelle;
	if($coord_fou->adr1 != '') $adr_fou.= $coord_fou->adr1."\n";
	if($coord_fou->adr2 != '') $adr_fou.= $coord_fou->adr2."\n";
	if($coord_fou->cp != '') $adr_fou.= $coord_fou->cp." ";
	if($coord_fou->ville != '') $adr_fou.= $coord_fou->ville;
	$ourPDF->setFontSize($fs_adr_fou);
	$ourPDF->SetXY($x_adr_fou, $y_adr_fou);
	$ourPDF->MultiCell($l_adr_fou, $h_adr_fou, $adr_fou, 0, 'L', 0);
	
	//Affichage adresse livraison
	$adr_liv = $msg['acquisition_adr_liv']."\n";
	if($coord_liv->libelle != '') $adr_liv.= $coord_liv->libelle."\n"; 
	if($coord_liv->adr1 != '') $adr_liv.= $coord_liv->adr1."\n";
	if($coord_liv->adr2 != '') $adr_liv.= $coord_liv->adr2."\n";
	if($coord_liv->cp != '') $adr_liv.= $coord_liv->cp." ";
	if($coord_liv->ville != '') $adr_liv.= $coord_liv->ville."\n";
	$ourPDF->setFontSize($fs_adr_liv);
	$ourPDF->SetXY($x_adr_liv, $y_adr_liv);
	$ourPDF->MultiCell($l_adr_liv, $h_adr_liv, $adr_liv, 1, 'L', 0);
	
	//Affichage numero bon de livraison et numero commande
	$numero = str_replace('!!numero!!', $liv->numero, $text_num_liv);
	$numero = str_replace('!!date!!', formatdate($liv->date_acte), $numero);
	$numero.= "\n".$text_num_cde." ".$cde->numero."\n";
	$numero.= $text_liv_ref_fou." ".$liv->reference;
	$ourPDF->SetFontSize($fs_num);
	$ourPDF->SetXY($x_num, $y_num);
	$ourPDF->MultiCell($l_num, $h_num, $numero, 0, 'L', 0);
	$ourPDF->Ln();
	
	
	//Affichage lignes livraison
	$ourPDF->SetAutoPageBreak(false);
	$ourPDF->AliasNbPages();
	
	$ourPDF->SetFontSize($fs_tab);
	$ourPDF->SetFillColor(230);
	$ourPDF->Ln();
	$y = $ourPDF->GetY();
	$ourPDF->SetXY($x_tab,$y);
	
	$x_code =  $x_tab;
	$w_code = round($w*20/100);
	$x_lib = $x_code + $w_code;
	$w_lib = round($w*60/100);
	$x_qte = $x_lib + $w_lib;
	$w_qte = round($w*10/100); 
	
	printEntete();
	
	while (($row = mysql_fetch_object($lignes))) {
	
		$typ = new types_produits($row->num_type);
		$col1 = $typ->libelle."\n".$row->code;
		
			$h = $h_tab * max( 	$ourPDF->NbLines($w_code, $col1),
						$ourPDF->NbLines($w_lib, $row->libelle),
						$ourPDF->NbLines($w_qte, $row->nb) );
							
			$s = $y+$h;		
			if ($s > ($hauteur_page-$marge_bas)){
		
				$ourPDF->AddPage();
				$ourPDF->SetXY($x_tab, $y_tab);
				$y = $ourPDF->GetY();
				printEntete();
				
			} 
			$ourPDF->SetXY($x_code, $y);
			$ourPDF->Rect($x_code, $y, $w_code, $h);
			$ourPDF->MultiCell($w_code, $h_tab, $col1, 0, 'L');
			$ourPDF->SetXY($x_lib, $y);
			$ourPDF->Rect($x_lib, $y, $w_lib, $h);
			$ourPDF->MultiCell($w_lib, $h_tab, $row->libelle, 0, 'L');
			$ourPDF->SetXY($x_qte, $y);
			$ourPDF->Rect($x_qte, $y, $w_qte, $h);
			$ourPDF->MultiCell($w_qte, $h_tab, $row->nb, 0, 'L');
			$y = $y+$h;
	
	}
	$y = $ourPDF->SetY($y);
	
	
	
	$ourPDF->SetAutoPageBreak(true, $marge_bas);
	$ourPDF->SetX($marge_gauche);
	$ourPDF->Ln();
	
	$ourPDF->OutPut();
}	
	

//Entete de tableau
function printEntete() {
	
	global $msg;
	global $ourPDF, $y;
	global $x_tab,$y_tab,$h_tab;
	global $x_code,$x_lib,$x_qte;
	global $w_code,$w_lib,$w_qte;
	global $hauteur_page, $marge_bas;  

	$h = $h_tab * max( 	$ourPDF->NbLines($w_code, $msg['acquisition_act_tab_typ']."\n".$msg['acquisition_act_tab_code']),
			$ourPDF->NbLines($w_lib,$msg['acquisition_act_tab_lib']),
			$ourPDF->NbLines($w_qte, $msg['acquisition_act_tab_qte']) );
	$s = $y+$h;		
	if ($s > ($hauteur_page-$marge_bas)){

		$ourPDF->AddPage();
		$ourPDF->SetXY($x_tab, $y_tab);
		$y = $ourPDF->GetY();
		
	} 
	$ourPDF->SetXY($x_code, $y);
	$ourPDF->Rect($x_code, $y, $w_code, $h, 'FD');
	$ourPDF->MultiCell($w_code, $h_tab, $msg['acquisition_act_tab_typ']."\n".$msg['acquisition_act_tab_code'], 0, 'L');
	$ourPDF->SetXY($x_lib, $y);
	$ourPDF->Rect($x_lib, $y, $w_lib, $h, 'FD');
	$ourPDF->MultiCell($w_lib, $h_tab, $msg['acquisition_act_tab_lib'], 0, 'L');
	$ourPDF->SetXY($x_qte, $y);
	$ourPDF->Rect($x_qte, $y, $w_qte, $h, 'FD');
	$ourPDF->MultiCell($w_qte, $h_tab, $msg['acquisition_act_tab_qte'], 0, 'L');
	$y = $y+$h;
}
?>