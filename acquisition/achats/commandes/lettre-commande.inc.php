<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: lettre-commande.inc.php,v 1.29.4.1 2011-08-31 14:50:56 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// popup d'impression PDF pour commande
// reçoit : id_cde

if (!$id_cde) {print "<script> self.close(); </script>" ; die;}

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

if ($acquisition_pdfcde_print) {
	require_once($acquisition_pdfcde_print);
} else {
	
	require_once("$class_path/entites.class.php");
	require_once("$class_path/coordonnees.class.php");
	require_once("$class_path/actes.class.php");
	require_once("$class_path/lignes_actes.class.php");
	require_once("$class_path/types_produits.class.php");
	require_once("$class_path/paiements.class.php");
	require_once("$class_path/rubriques.class.php");
	require_once("$base_path/acquisition/achats/func_achats.inc.php");
	
	//paramètres modifiables-----------------------------------------------------------------------------
	
	if (!$acquisition_pdfcde_text_size) $fs = '10';	//Taille de la police 
		else $fs = $acquisition_pdfcde_text_size; 
	$format_page = explode('x',$acquisition_pdfcde_format_page);
	if(!$format_page[0]) $largeur_page = '210';			//largeur de page
		else $largeur_page = $format_page[0];
	if(!$format_page[1]) $hauteur_page = '297';		//hauteur de page
		else $hauteur_page = $format_page[1];
	
	if(!$acquisition_pdfcde_orient_page) $orient_page = 'P';		//orientation page (P=portrait, L=paysage)
		else $orient_page = $acquisition_pdfcde_orient_page;
	
	$marges_page = explode(',', $acquisition_pdfcde_marges_page);
	if (!$marges_page[0]) $marge_haut = '10';		//marge haut
		else $marge_haut = $marges_page[0];
	if (!$marges_page[1]) $marge_bas = '20';		//marge bas
		else $marge_bas = $marges_page[1];
	if (!$marges_page[2]) $marge_droite = '10';		//marge droite
		else $marge_droite = $marges_page[2];
	if (!$marges_page[3]) $marge_gauche = '10';		//marge gauche
		else $marge_gauche = $marges_page[3];
	
	$pos_logo = explode(',', $acquisition_pdfcde_pos_logo);
	if (!$pos_logo[0]) $x_logo = '10';				//Distance du logo / bord gauche de page
		else $x_logo = $pos_logo[0];
	if (!$pos_logo[1]) $y_logo = '10';				//Distance du logo / bord haut de page
		else $y_logo = $pos_logo[1];
	if (!$pos_logo[2]) $l_logo = '20';				//Largeur logo
		else $l_logo = $pos_logo[2];
	if (!$pos_logo[3]) $h_logo = '20';				//Hauteur logo
		else $h_logo = $pos_logo[3];
	
	$pos_raison = explode(',', $acquisition_pdfcde_pos_raison);
	if (!$pos_raison[0]) $x_raison = '35';			//Distance raison sociale / bord gauche de page
		else $x_raison = $pos_raison[0];
	if (!$pos_raison[1]) $y_raison = '10';			//Distance raison sociale / bord haut de page
		else $y_raison = $pos_raison[1];
	if (!$pos_raison[2]) $l_raison = '100';			//Largeur raison sociale
		else $l_raison = $pos_raison[2];
	if (!$pos_raison[3]) $h_raison = '10';			//Hauteur raison sociale
		else $h_raison = $pos_raison[3];
	if (!$pos_raison[4]) $fs_raison = '16';			//Police raison sociale
		else $fs_raison = $pos_raison[4];
	
	$pos_date = explode(',', $acquisition_pdfcde_pos_date);
	if (!$pos_date[0]) $x_date = '150';				//Distance date / bord gauche de page
		else $x_date = $pos_date[0];
	if (!$pos_date[1]) $y_date = '10';				//Distance date / bord haut de page
		else $y_date = $pos_date[1];
	if (!$pos_date[2]) $l_date = '0';				//Largeur date
		else $l_date = $pos_date[2];
	if (!$pos_date[3]) $h_date = '6';				//Hauteur date
		else $h_date = $pos_date[3];
	if (!$pos_date[4]) $fs_date = '8';				//Police date
		else $fs_date = $pos_date[4];
	$sep_ville_date = $msg['acquisition_act_sep_ville_date'];	//Séparateur entre ville et date
	
	$pos_adr_fac = explode(',', $acquisition_pdfcde_pos_adr_fac);
	if (!$pos_adr_fac[0]) $x_adr_fac = '10';			//Distance adr facturation / bord gauche de page
		else $x_adr_fac = $pos_adr_fac[0];
	if (!$pos_adr_fac[1]) $y_adr_fac = '35';			//Distance adr facturation / bord haut de page
		else $y_adr_fac = $pos_adr_fac[1];
	if (!$pos_adr_fac[2]) $l_adr_fac = '60';			//Largeur adr facturation
		else $l_adr_fac = $pos_adr_fac[2];
	if (!$pos_adr_fac[3]) $h_adr_fac = '5';				//Hauteur adr facturation
		else $h_adr_fac = $pos_adr_fac[3];
	if (!$pos_adr_fac[4]) $fs_adr_fac = '10';			//Police adr facturation
		else $fs_adr_fac = $pos_adr_fac[4];
	$text_adr_fac_tel = $msg['acquisition_tel'].".";
	$text_adr_fac_fax = $msg['acquisition_fax'].".";
	$text_adr_fac_email = $msg['acquisition_mail']." :";
	
	$pos_adr_liv = explode(',', $acquisition_pdfcde_pos_adr_liv);
	if (!$pos_adr_liv[0]) $x_adr_liv = '10';			//Distance adr livraison / bord gauche de page
		else $x_adr_liv = $pos_adr_liv[0];
	if (!$pos_adr_liv[1]) $y_adr_liv = '75';			//Distance adr livraison / bord haut de page
		else $y_adr_liv = $pos_adr_liv[1];
	if (!$pos_adr_liv[2]) $l_adr_liv = '60';			//Largeur adr livraison
		else $l_adr_liv = $pos_adr_liv[2];
	if (!$pos_adr_liv[3]) $h_adr_liv = '5';				//Hauteur adr livraison
		else $h_adr_liv = $pos_adr_liv[3];
	if (!$pos_adr_liv[4]) $fs_adr_liv = '10';			//Police adr livraison
		else $fs_adr_liv = $pos_adr_liv[4];
	$text_adr_liv = $msg['acquisition_adr_liv'];
	$text_adr_liv_tel = $msg['acquisition_tel'].".";
	
	$pos_adr_fou = explode(',', $acquisition_pdfcde_pos_adr_fou);
	if (!$pos_adr_fou[0]) $x_adr_fou = '100';			//Distance adr fournisseur / bord gauche de page
		else $x_adr_fou = $pos_adr_fou[0];
	if (!$pos_adr_fou[1]) $y_adr_fou = '55';			//Distance adr fournisseur / bord haut de page
		else $y_adr_fou = $pos_adr_fou[1];
	if (!$pos_adr_fou[2]) $l_adr_fou = '100';			//Largeur adr fournisseur
		else $l_adr_fou = $pos_adr_fou[2];
	if (!$pos_adr_fou[3]) $h_adr_fou = '6';				//Hauteur adr fournisseur
		else $h_adr_fou = $pos_adr_fou[3];
	if (!$pos_adr_fou[4]) $fs_adr_fou = '14';			//Police adr fournisseur
		else $fs_adr_fou = $pos_adr_fou[4];
	//$text_adr_fou = $msg['acquisition_act_formule'];
	
	
	$pos_num = explode(',', $acquisition_pdfcde_pos_num);
	if (!$pos_num[0]) $x_num = '10';				//Distance num commande / bord gauche de page
		else $x_num = $pos_num[0];
	if (!$pos_num[1]) $y_num = '110';				//Distance num commande / bord haut de page
		else $y_num = $pos_num[1];
	if (!$pos_num[2]) $l_num = '0';					//Largeur num commande
		else $l_num = $pos_num[2];
	if (!$pos_num[3]) $h_num = '10';				//Hauteur num commande
		else $h_num = $pos_num[3];
	if (!$pos_num[4]) $fs_num = '16';				//Police num commande
		else $fs_num = $pos_num[4];
	$text_num = $msg['acquisition_act_num_cde'];
	$text_num_cli = $msg['acquisition_num_cp_client'];
	
	$text_before = $acquisition_pdfcde_text_before;			//texte avant table commande
	$text_after = $acquisition_pdfcde_text_after;			//texte après table commande
	
	$pos_tab = explode(',', $acquisition_pdfcde_tab_cde);
	if (!$pos_tab[0]) $h_tab = '5';				//Hauteur de ligne table commande
		else $h_tab = $pos_tab[0];
	if (!$pos_tab[1]) $fs_tab = '10';			//Police table commande
		else $fs_tab = $pos_tab[1];
	$x_tab = $marge_gauche;						//position table commande / bord droit page 
	$y_tab = $marge_haut;						//position table commande / haut page sur pages 2 et + 
	
	$pos_tot = explode(',', $acquisition_pdfcde_pos_tot);
	if (!$pos_tot[0]) $x_tot = '10';			//Distance total commande / bord gauche de page
		else $x_tot = $pos_tot[0];
	if (!$pos_tot[1]) $l_tot = '40';			//Largeur cellule total commande
		else $l_tot = $pos_tot[1];
	if (!$pos_tot[2]) $h_tot = '5';				//Hauteur total commande
		else $h_tot = $pos_tot[2];
	if (!$pos_tot[3]) $fs_tot = '10';			//Police total commande
		else $fs_tot = $pos_tot[3];
	
	$pos_sign = explode(',', $acquisition_pdfcde_pos_sign);
	if (!$pos_sign[0]) $x_sign = '10';			//Distance signature / bord gauche de page
		else $x_sign = $pos_sign[0];
	if (!$pos_sign[1]) $l_sign = '60';				//Largeur cellule signature
		else $l_sign = $pos_sign[1];
	if (!$pos_sign[2]) $h_sign = '5';			//Hauteur signature
		else $h_sign = $pos_sign[2];
	if (!$pos_sign[3]) $fs_sign = '10';			//Police signature
		else $fs_sign = $pos_sign[3];
		
	if (!$acquisition_pdfcde_text_sign) $text_sign = $msg['acquisition_act_sign'];
		else $text_sign = $acquisition_pdfcde_text_sign;
	
	$pos_footer = explode(',', $acquisition_pdfcde_pos_footer);
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
	
	//On récupère les infos de la commande
	
	$cde = new actes($id_cde);
	$lignes = $cde->getLignes();
	$bibli = new entites ($cde->num_entite);
	$coord_liv = new coordonnees($cde->num_contact_livr);
	$coord_fac = new coordonnees($cde->num_contact_fact);
	
	$fou = new entites($cde->num_fournisseur);
	$coord_fou = entites::get_coordonnees($cde->num_fournisseur, '1');
	$coord_fou = mysql_fetch_object($coord_fou);
	
	$ourPDF->addPage();
	
	$ourPDF->setFont($pmb_pdf_font);
	
	
	//Affichage logo
	if($bibli->logo != '') {
		$ourPDF->Image($bibli->logo, $x_logo, $y_logo, $l_logo, $h_logo);
	}
	
	//Affichage raison sociale
	$raison =  $bibli->raison_sociale;
	$ourPDF->setFontSize($fs_raison);
	$ourPDF->SetXY($x_raison, $y_raison);
	$ourPDF->MultiCell($l_raison, $h_raison, $raison, 0, 'L', 0);
	
	//Affichage date $ville
	if(stripos)$ville_end=stripos($coord_fac->ville,"cedex");
	else $ville_end=strpos($coord_fac->ville,"cedex");
	if($ville_end!==false) $ville=trim(substr($coord_fac->ville,0,$ville_end));
	else $ville=$coord_fac->ville;
	$date = $ville.$sep_ville_date.formatdate($cde->date_acte);
	$ourPDF->setFontSize($fs_date);
	$ourPDF->SetXY($x_date, $y_date);
	$ourPDF->Cell($l_date, $h_date, $date, 0, 0, 'L', 0);
	
	//Affichage coordonnees fournisseur
	//si pas de raison sociale définie, on reprend le libellé
	//si il y a une raison sociale, pas besoin 
	if($fou->raison_sociale != '') {
		$adr_fou = $fou->raison_sociale."\n";
	} else { 
		$adr_fou = $coord_fou->libelle."\n";
	}
	if($coord_fou->adr1 != '') $adr_fou.= $coord_fou->adr1."\n";
	if($coord_fou->adr2 != '') $adr_fou.= $coord_fou->adr2."\n";
	if($coord_fou->cp != '') $adr_fou.= $coord_fou->cp." ";
	if($coord_fou->ville != '') $adr_fou.= $coord_fou->ville."\n\n";
	if ($coord_fou->contact != '') $adr_fou.= $coord_fou->contact;
	$ourPDF->setFontSize($fs_adr_fou);
	$ourPDF->SetXY($x_adr_fou, $y_adr_fou);
	$ourPDF->MultiCell($l_adr_fou, $h_adr_fou, $adr_fou, 0, 'L', 0);
	
	//Affichage adresse facturation
	$adr_fac=$msg['acquisition_adr_fac'].":\n"; 
	if($coord_fac->libelle != '') $adr_fac.= $coord_fac->libelle."\n"; 
	if($coord_fac->adr1 != '') $adr_fac.= $coord_fac->adr1."\n";
	if($coord_fac->adr2 != '') $adr_fac.= $coord_fac->adr2."\n";
	if($coord_fac->cp != '') $adr_fac.= $coord_fac->cp." ";
	if($coord_fac->ville != '') $adr_fac.= $coord_fac->ville."\n";
	if($coord_fac->tel1 != '') $adr_fac.= $text_adr_fac_tel." ".$coord_fac->tel1."\n";
	if($coord_fac->fax != '') $adr_fac.= $text_adr_fac_fax." ".$coord_fac->fax."\n";
	if($coord_fac->email != '') $adr_fac.= $text_adr_fac_email." ".$coord_fac->email."\n";
	if($bibli->tva)$adr_fac.=$msg["acquisition_tva"].": ".$bibli->tva."\n";;
	$ourPDF->setFontSize($fs_adr_fac);
	$ourPDF->SetXY($x_adr_fac, $y_adr_fac);
	$ourPDF->MultiCell($l_adr_fac, $h_adr_fac, $adr_fac, 1, 'L', 0);
	
	//Affichage adresse livraison
	$adr_liv = '';
	if($coord_liv->libelle != '') $adr_liv.= $coord_liv->libelle."\n"; 
	if($coord_liv->adr1 != '') $adr_liv.= $coord_liv->adr1."\n";
	if($coord_liv->adr2 != '') $adr_liv.= $coord_liv->adr2."\n";
	if($coord_liv->cp != '') $adr_liv.= $coord_liv->cp." ";
	if($coord_liv->ville != '') $adr_liv.= $coord_liv->ville."\n";
	if($coord_liv->tel1 != '') $adr_liv.= $text_adr_liv_tel." ".$coord_liv->tel1."\n";
	if($coord_liv->email != '') $adr_liv.= $coord_liv->email."\n";
	
	if($adr_liv != '') {
		$adr_liv = $msg['acquisition_adr_liv'].":\n".$adr_liv; 
		$ourPDF->setFontSize($fs_adr_liv);
		$ourPDF->SetXY($x_adr_liv, $y_adr_liv);
		$ourPDF->MultiCell($l_adr_liv, $h_adr_liv, $adr_liv, 1, 'L', 0);
	}
	
	//Affichage tiret pliage 
	$ourPDF->Line(0,105, 3, 105);
	
	//Affichage numero client
	$numero_cli = $text_num_cli." ".$fou->num_cp_client;
	$ourPDF->SetFontSize($fs_num);
	$ourPDF->SetXY($x_num, $y_num);
	$ourPDF->Cell($l_num, $h_num, $numero_cli, 0, 0, 'L', 0);
	$ourPDF->Ln();
	
	//Affichage numero commande
	$numero =  $text_num.$cde->numero;
	$ourPDF->SetFontSize($fs_num);
	$ourPDF->Cell($l_num, $h_num, $numero, 0, 0, 'L', 0);
	$ourPDF->Ln();
	
	//Affichage reference
	if ($cde->reference != '') {
		$ref = $msg['acquisition_cde_ref_dev'].$cde->reference;
		$ourPDF->SetFontSize($fs);
		$ourPDF->Cell($w, $h_tab, $ref, 0, 0, 'L', 0);
		$ourPDF->Ln();
		$ourPDF->Ln();
	}
	
	//Affichage texte before + commentaires
	if ($cde->commentaires_i != '') {
		if ($text_before != '') $text_before.= "\n\n";
		$text_before.= $cde->commentaires_i;
	}
	if ($text_before != '') {
		$ourPDF->SetFontSize($fs);
		$ourPDF->MultiCell($w, $h_tab, $text_before, 0, 'J', 0);
		$ourPDF->Ln();
	}
	
	//Affichage lignes commandes
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

	switch($acquisition_gestion_tva) {
	case '1' :
		$prix.= $msg['acquisition_act_tab_priht']."\n".$msg['acquisition_tva']."\n".$msg['acquisition_rem']; 
		break;
	case '2' :
		$prix.= $msg['acquisition_act_tab_prittc']."\n".$msg['acquisition_tva']."\n".$msg['acquisition_rem'];
		break;
	default :
		$prix.= " ".$msg['acquisition_act_tab_prittc']."\n".$msg['acquisition_rem'];
		break;
	}	
	
	printEntete();
	
	$tab_mnt=array();
	$i=0;
	while (($row = mysql_fetch_object($lignes))) {
	
		$typ = new types_produits($row->num_type);
		$col1 = $typ->libelle;
		if($row->code) $col1.= "\n".$row->code;
		$col2 = $row->libelle;
		$col3 = $row->nb;
		$col4 = number_format(round($row->prix, 2),2,'.','' )." ".$cde->devise;
		if ($acquisition_gestion_tva){
			$col4.= "\n".number_format(round($row->tva,2),2,'.','' )." %";
		}
		$col4.= "\n".number_format(round($row->remise,2),2,'.','' )." %";
		$col5='';
	 	if ($row->date_ech != '0000-00-00') {
	 		$col5 = formatdate($row->date_ech);
	 	}
	 	if($row->num_rubrique) {
			$rub = new rubriques($row->num_rubrique);
			if($rub->num_cp_compta) $col5.= "\n\n".$rub->num_cp_compta;
		}
	
		//Est ce qu'on dépasse ?		
		$h = $h_tab * max( 	$ourPDF->NbLines($w_code, $col1),
					$ourPDF->NbLines($w_lib, $col2),
					$ourPDF->NbLines($w_qte, $col3),
					$ourPDF->NbLines($w_pri, $col4),
					$ourPDF->NbLines($w_dat, $col5) );
							
			$s = $y+$h;		
		
		//Si oui, chgt page
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
		$ourPDF->MultiCell($w_lib, $h_tab, $col2, 0, 'L');
		$ourPDF->SetXY($x_qte, $y);
		$ourPDF->Rect($x_qte, $y, $w_qte, $h);
		$ourPDF->MultiCell($w_qte, $h_tab, $col3, 0, 'R');
		$ourPDF->SetXY($x_pri, $y);
		$ourPDF->Rect($x_pri, $y, $w_pri, $h);
		$ourPDF->MultiCell($w_pri, $h_tab, $col4, 0, 'R');
		$ourPDF->SetXY($x_dat, $y);
		$ourPDF->Rect($x_dat, $y, $w_dat, $h);
		$ourPDF->MultiCell($w_dat, $h_tab, $col5, 0, 'R');
		$y = $y+$h;
	
		$tab_mnt[$i]['q']=$row->nb;
		$tab_mnt[$i]['p']=$row->prix;
		$tab_mnt[$i]['r']=$row->remise;
		$tab_mnt[$i]['t']=$row->tva;
		$i++;	
	}
	$y = $ourPDF->SetY($y);
	
	$tab_tot = calc($tab_mnt,2);
	
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
	
	if ($acquisition_gestion_tva) {
		$ourPDF->Cell($l_tot, $h_tot, $msg['acquisition_total_ht'], 1, 0, 'L',0);
		$ourPDF->Cell($l_tot, $h_tot, number_format($tab_tot['ht'],2,'.','' )." ".$cde->devise, 1, 1, 'R',0);
		$ourPDF->Cell($l_tot, $h_tot, $msg['acquisition_tva'], 1, 0, 'L',0);
		$ourPDF->Cell($l_tot, $h_tot, number_format($tab_tot['tva'],2,'.','' )." ".$cde->devise, 1, 1,'R',0);	 		 	
	}
	$ourPDF->Cell($l_tot, $h_tot, $msg['acquisition_total_ttc'], 1, 0, 'L',0);
	$ourPDF->Cell($l_tot, $h_tot, number_format($tab_tot['ttc'],2,'.','' )." ".$cde->devise, 1, 1, 'R',0);	 	
	
	
	$ourPDF->SetAutoPageBreak(true, $marge_bas);
	$ourPDF->SetX($marge_gauche);
	$ourPDF->Ln();
	
	
	$ourPDF->SetFontSize($fs);
	
	
	//Affichage conditions de paiement
	$text_paiement = $msg['acquisition_mode_pai'];
	if ($fou->num_paiement) {
		$pai = new paiements($fou->num_paiement); 
		$text_paiement.= "$pai->libelle";
	}
	$ourPDF->MultiCell($w, $h_tab, $text_paiement, 0, 'L', 0);
	$ourPDF->Ln();
	
	
	//Affichage texte after
	if ($text_after != '') {
		$ourPDF->MultiCell($w, $h_tab, $text_after, 0, 'J', 0);
		$ourPDF->Ln();
	}
	
	//Affichage signature
	$ourPDF->Ln();
	$ourPDF->SetFontSize($fs_sign);
	$ourPDF->SetX($x_sign);
	$ourPDF->MultiCell($l_sign, $h_sign, $text_sign, 0, 'L', 0);
	
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
			$ourPDF->NbLines($w_dat, $msg['acquisition_act_tab_dateliv']) );
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
	$ourPDF->MultiCell($w_dat, $h_tab, $msg['acquisition_act_tab_dateliv'], 0, 'L');
	$y = $y+$h;
}
?>