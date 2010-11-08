<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: lettre-facture.inc.php,v 1.13 2009-06-02 19:17:42 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// popup d'impression PDF pour facture
// reçoit : id_cde

if (!$id_fac) {print "<script> self.close(); </script>" ; die;}

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

if ($acquisition_pdffac_print) {
	require_once($acquisition_pdffac_print);
} else {
	
	require_once("$class_path/entites.class.php");
	require_once("$class_path/coordonnees.class.php");
	require_once("$class_path/actes.class.php");
	require_once("$class_path/lignes_actes.class.php");
	require_once("$class_path/liens_actes.class.php");
	require_once("$class_path/rubriques.class.php");
	require_once("$class_path/types_produits.class.php");
	require_once("$class_path/paiements.class.php");
	
	
	//paramètres modifiables-----------------------------------------------------------------------------
	
	if (!$acquisition_pdffac_text_size) $fs = '10';	//Taille de la police 
		else $fs = $acquisition_pdffac_text_size; 
		
	$format_page = explode('x',$acquisition_pdffac_format_page);
	if(!$format_page[0]) $largeur_page = '210';			//largeur de page
		else $largeur_page = $format_page[0];
	if(!$format_page[1]) $hauteur_page = '297';		//hauteur de page
		else $hauteur_page = $format_page[1];
	
	if(!$acquisition_pdffac_orient_page) $orient_page = 'P';		//orientation page (P=portrait, L=paysage)
		else $orient_page = $acquisition_pdffac_orient_page;
	
	$marges_page = explode(',', $acquisition_pdffac_marges_page);
	if (!$marges_page[0]) $marge_haut = '10';		//marge haut
		else $marge_haut = $marges_page[0];
	if (!$marges_page[1]) $marge_bas = '20';		//marge bas
		else $marge_bas = $marges_page[1];
	if (!$marges_page[2]) $marge_droite = '10';		//marge droite
		else $marge_droite = $marges_page[2];
	if (!$marges_page[3]) $marge_gauche = '10';		//marge gauche
		else $marge_gauche = $marges_page[3];
	
	$pos_raison = explode(',', $acquisition_pdffac_pos_raison);
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
	
	$pos_date = explode(',', $acquisition_pdffac_pos_date);
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
	
	$pos_adr_fac = explode(',', $acquisition_pdffac_pos_adr_fac);
	if (!$pos_adr_fac[0]) $x_adr_fac = '10';			//Distance adr facturation / bord gauche de page
		else $x_adr_fac = $pos_adr_fac[0];
	if (!$pos_adr_fac[1]) $y_adr_fac = '20';			//Distance adr facturation / bord haut de page
		else $y_adr_fac = $pos_adr_fac[1];
	if (!$pos_adr_fac[2]) $l_adr_fac = '60';			//Largeur adr facturation
		else $l_adr_fac = $pos_adr_fac[2];
	if (!$pos_adr_fac[3]) $h_adr_fac = '5';				//Hauteur adr facturation
		else $h_adr_fac = $pos_adr_fac[3];
	if (!$pos_adr_fac[4]) $fs_adr_fac = '10';			//Police adr facturation
		else $fs_adr_fac = $pos_adr_fac[4];
	$text_adr_liv = $msg['acquisition_adr_fac'];
	$text_adr_fac_tel = $msg['acquisition_tel'].".";
	$text_adr_fac_fax = $msg['acquisition_fax'].".";
	$text_adr_fac_email = $msg['acquisition_mail']." :";
	
	$pos_adr_fou = explode(',', $acquisition_pdffac_pos_adr_fou);
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
	$text_adr_fou = $msg['acquisition_act_fou2'];
	
	$pos_num = explode(',', $acquisition_pdffac_pos_num);
	if (!$pos_num[0]) $x_num = '10';				//Distance num facture / bord gauche de page
		else $x_num = $pos_num[0];
	if (!$pos_num[1]) $y_num = '60';				//Distance num facture / bord haut de page
		else $y_num = $pos_num[1];
	if (!$pos_num[2]) $l_num = '0';					//Largeur num facture
		else $l_num = $pos_num[2];
	if (!$pos_num[3]) $h_num = '6';				//Hauteur num facture
		else $h_num = $pos_num[3];
	if (!$pos_num[4]) $fs_num = '14';				//Police num facture
		else $fs_num = $pos_num[4];
	$text_num_fac = $msg['acquisition_act_num_fac'];
	$text_fac_ref_fou = $msg['acquisition_fac_ref_fou'];
	$text_num_cde = $msg['acquisition_act_num_cde'];
	
	$pos_tab = explode(',', $acquisition_pdffac_tab_fac);
	if (!$pos_tab[0]) $h_tab = '5';				//Hauteur de ligne table facture
		else $h_tab = $pos_tab[0];
	if (!$pos_tab[1]) $fs_tab = '10';			//Police table facture
		else $fs_tab = $pos_tab[1];
	$x_tab = $marge_gauche;						//position table facture / bord droit page 
	$y_tab = $marge_haut;						//position table facture / haut page sur pages 2 et + 
	
	$pos_tot = explode(',', $acquisition_pdffac_pos_tot);
	if (!$pos_tot[0]) $x_tot = '10';			//Distance total facture / bord gauche de page
		else $x_tot = $pos_tot[0];
	if (!$pos_tot[1]) $l_tot = '40';			//Largeur cellule total facture
		else $l_tot = $pos_tot[1];
	if (!$pos_tot[2]) $h_tot = '5';				//Hauteur total facture
		else $h_tot = $pos_tot[2];
	if (!$pos_tot[3]) $fs_tot = '10';			//Police total facture
		else $fs_tot = $pos_tot[3];
	
	$pos_footer = explode(',', $acquisition_pdffac_pos_footer);
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
	
	//On récupère les infos de la facture
	$fac = new actes($id_fac);
	$lignes = $fac->getLignes();
	$bibli = new entites ($fac->num_entite);
	$coord_fac = new coordonnees($fac->num_contact_fact);
	
	$fou = new entites($fac->num_fournisseur);
	$coord_fou = entites::get_coordonnees($fac->num_fournisseur, '1');
	$coord_fou = mysql_fetch_object($coord_fou);
	
	$id_cde = liens_actes::getParent($id_fac);
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
	if($fou->raison_sociale != '') $adr_fou = $fou->raison_sociale."\n";
	if($coord_fou->libelle != '') $adr_fou.= $coord_fou->libelle;
	if($coord_fou->adr1 != '') $adr_fou.= $coord_fou->adr1."\n";
	if($coord_fou->adr2 != '') $adr_fou.= $coord_fou->adr2."\n";
	if($coord_fou->cp != '') $adr_fou.= $coord_fou->cp." ";
	if($coord_fou->ville != '') $adr_fou.= $coord_fou->ville."\n\n";
	if($coord_fou->contact != '') $adr_fou.= $text_adr_fou.$coord_fou->contact;
	$ourPDF->setFontSize($fs_adr_fou);
	$ourPDF->SetXY($x_adr_fou, $y_adr_fou);
	$ourPDF->MultiCell($l_adr_fou, $h_adr_fou, $adr_fou, 0, 'L', 0);
	
	//Affichage adresse facturation
	if($coord_fac->libelle != '') $adr_fac = $coord_fac->libelle."\n"; 
	if($coord_fac->adr1 != '') $adr_fac.= $coord_fac->adr1."\n";
	if($coord_fac->adr2 != '') $adr_fac.= $coord_fac->adr2."\n";
	if($coord_fac->cp != '') $adr_fac.= $coord_fac->cp." ";
	if($coord_fac->ville != '') $adr_fac.= $coord_fac->ville."\n";
	if($coord_fac->tel1 != '') $adr_fac.= $text_adr_fac_tel." ".$coord_fac->tel1."\n";
	if($coord_fac->fax != '') $adr_fac.= $text_adr_fac_fax." ".$coord_fac->fax."\n";
	if($coord_fac->email != '') $adr_fac.= $text_adr_fac_email." ".$coord_fac->email."\n";
	$ourPDF->setFontSize($fs_adr_fac);
	$ourPDF->SetXY($x_adr_fac, $y_adr_fac);
	$ourPDF->MultiCell($l_adr_fac, $h_adr_fac, $adr_fac, 0, 'L', 0);
	
	//Affichage numero facture et numero commande
	$numero = str_replace('!!numero!!', $fac->numero, $text_num_fac);
	$numero = str_replace('!!date!!', formatdate($fac->date_acte), $numero);
	$numero.= "\n".$text_num_cde." ".$cde->numero."\n";
	$numero.= $text_fac_ref_fou." ".$fac->reference;
	$ourPDF->SetFontSize($fs_num);
	$ourPDF->SetXY($x_num, $y_num);
	$ourPDF->MultiCell($l_num, $h_num, $numero, 0, 'L', 0);
	$ourPDF->Ln();
	
	//Affichage lignes facture
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
	$w_lib = round($w*40/100);
	$x_qte = $x_lib + $w_lib;
	$w_qte = round($w*10/100); 
	$x_pri = $x_qte + $w_qte;
	$w_pri = round($w*10/100);
	$x_dat = $x_pri + $w_pri;
	$w_dat = round($w*20/100);
	
	if ($acquisition_gestion_tva) $prix.= $msg['acquisition_act_tab_priht']."\n".$msg['acquisition_tva']."\n".$msg['acquisition_rem']; 
		else $prix.= " ".$msg['acquisition_act_tab_prittc']."\n".$msg['acquisition_rem'];	
	$tot_ht = 0;
	$tot_tva = 0;
	$tot_ttc = 0;
	
	printEntete();
	
	while (($row = mysql_fetch_object($lignes))) {
	
		$typ = new types_produits($row->num_type);
		$col1 = $typ->libelle."\n".$row->code;
		if ($row->num_rubrique) {
			$rub = new rubriques($row->num_rubrique);
			$col5.= $rub->num_cp_compta;
		}
		
		$col4 = number_format($row->prix, 2,'.','')." ".$fac->devise."\n".number_format($row->tva,2,'.','')." %\n".number_format($row->remise,2,'.','')." %";
		
			$h = $h_tab * max( 	$ourPDF->NbLines($w_code, $col1),
						$ourPDF->NbLines($w_lib, $row->libelle),
						$ourPDF->NbLines($w_qte, $row->nb),
						$ourPDF->NbLines($w_pri, $col4),
						$ourPDF->NbLines($w_dat, $col5) );
							
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
			$ourPDF->MultiCell($w_qte, $h_tab, $row->nb, 0, 'R');
			$ourPDF->SetXY($x_pri, $y);
			$ourPDF->Rect($x_pri, $y, $w_pri, $h);
			$ourPDF->MultiCell($w_pri, $h_tab, $col4, 0, 'R');
			$ourPDF->SetXY($x_dat, $y);
			$ourPDF->Rect($x_dat, $y, $w_dat, $h);
			$ourPDF->MultiCell($w_dat, $h_tab, $col5, 0, 'R');
			$y = $y+$h;
	
	
		//calcul des montants ht, ttc, tva			
		if ($acquisition_gestion_tva) {
			$lig_ht = $row->nb * $row->prix * (1-($row->remise/100)) ;
			$tot_ht = $tot_ht + $lig_ht;
			$tot_tva = $tot_tva + ($lig_ht*($row->tva/100) );
			$tot_ttc = $tot_ht + $tot_tva;						
		} else {
			$lig_ttc = $row->nb * $row->prix * (1-($row->remise/100)) ;
			$tot_ttc = $tot_ttc + $lig_ttc;
		}
	
	}
	$y = $ourPDF->SetY($y);
	
	
	
	//affichage des montants ht, ttc, tva	
	$ourPDF->Ln();
	$y = $ourPDF->GetY();
	if ($acquisition_gestion_tva) $h = $h_tot * 3;
		else $h = $h_tot;
	$s = $y + $h;
	
	if ($s > ($hauteur_page-$marge_bas)){
	
		$ourPDF->AddPage();
		$ourPDF->SetXY($x_tot, $marge_haut);
		$y = $ourPDF->GetY(); 
	}
	
	if ($acquisition_gestion_tva){
		$ourPDF->Cell($l_tot, $h_tot, $msg['acquisition_total_ht'], 1, 0, 'L',0);
		$ourPDF->Cell($l_tot, $h_tot, number_format(round($tot_ht, 2),2,'.','')." ".$fac->devise, 1, 1, 'R',0);
		$ourPDF->Cell($l_tot, $h_tot, $msg['acquisition_tva'], 1, 0, 'L',0);
		$ourPDF->Cell($l_tot, $h_tot, number_format(round($tot_tva, 2),2,'.','')." ".$fac->devise, 1, 1,'R',0);	 		 	
	}
	
	$ourPDF->Cell($l_tot, $h_tot, $msg['acquisition_total_ttc'], 1, 0, 'L',0);
	$ourPDF->Cell($l_tot, $h_tot, number_format(round($tot_ttc, 2),2,'.','')." ".$fac->devise, 1, 1, 'R',0);	 	
	
	
	$ourPDF->SetAutoPageBreak(true, $marge_bas);
	$ourPDF->SetX($marge_gauche);
	$ourPDF->Ln();
	
	
	$ourPDF->SetFontSize($fs);
	
	
	$ourPDF->OutPut();
}	
	

//Entete de tableau
function printEntete() {
	
	global $msg;
	global $ourPDF, $y;
	global $x_tab,$y_tab,$h_tab;
	global $x_code,$x_lib,$x_qte,$x_pri,$x_dat;
	global $w_code,$w_lib,$w_qte,$w_pri,$w_dat;
	global $hauteur_page, $marge_bas;  
	global $prix;	

$h = $h_tab * max( 	$ourPDF->NbLines($w_code, $msg['acquisition_act_tab_typ']."\n".$msg['acquisition_act_tab_code']),
			$ourPDF->NbLines($w_lib,$msg['acquisition_act_tab_lib']),
			$ourPDF->NbLines($w_qte, $msg['acquisition_act_tab_qte']),
			$ourPDF->NbLines($w_pri, $prix),
			$ourPDF->NbLines($w_dat, $msg['acquisition_num_cp_compta']) );
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
	$ourPDF->SetXY($x_pri, $y);
	$ourPDF->Rect($x_pri, $y, $w_pri, $h, 'FD');
	$ourPDF->MultiCell($w_pri, $h_tab, $prix, 0, 'L');
	$ourPDF->SetXY($x_dat, $y);
	$ourPDF->Rect($x_dat, $y, $w_dat, $h, 'FD');
	$ourPDF->MultiCell($w_dat, $h_tab, $msg['acquisition_num_cp_compta'], 0, 'L');
	$y = $y+$h;
}
?>