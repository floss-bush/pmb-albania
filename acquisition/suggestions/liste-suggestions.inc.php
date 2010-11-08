<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liste-suggestions.inc.php,v 1.15 2009-11-04 14:37:54 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// popup d'impression PDF pour liste de suggestions
// reçoit : user_input, statut

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

if ($acquisition_pdfsug_print) {
	require_once($acquisition_pdfsug_print);
} else {
	
	require_once($class_path.'/suggestions.class.php');
	require_once($class_path.'/suggestions_origine.class.php');
	require_once($class_path.'/suggestions_map.class.php');
	require_once($class_path.'/analyse_query.class.php');
	
	
	//paramètres modifiables-----------------------------------------------------------------------------
	
	if (!$acquisition_pdfsug_text_size) $fs = '8';	//Taille de la police 
		else $fs = $acquisition_pdfsug_text_size; 
	
	$format_page = explode('x',$acquisition_pdfsug_format_page);
	if(!$format_page[0]) $largeur_page = '210';			//largeur de page
		else $largeur_page = $format_page[0];
	if(!$format_page[1]) $hauteur_page = '297';		//hauteur de page
		else $hauteur_page = $format_page[1];
	
	if(!$acquisition_pdfsug_orient_page) $orient_page = 'P';		//orientation page (P=portrait, L=paysage)
		else $orient_page = $acquisition_pdfsug_orient_page;
	
	$marges_page = explode(',', $acquisition_pdfsug_marges_page);
	if (!$marges_page[0]) $marge_haut = '10';		//marge haut
		else $marge_haut = $marges_page[0];
	if (!$marges_page[1]) $marge_bas = '20';		//marge bas
		else $marge_bas = $marges_page[1];
	if (!$marges_page[2]) $marge_droite = '10';		//marge droite
		else $marge_droite = $marges_page[2];
	if (!$marges_page[3]) $marge_gauche = '10';		//marge gauche
		else $marge_gauche = $marges_page[3];
	
	$pos_titre = explode(',', $acquisition_pdfsug_pos_titre);
	if (!$pos_titre[0]) $x_titre = '10';			//Distance titre / bord gauche de page
		else $x_titre = $pos_titre[0];
	if (!$pos_titre[1]) $y_titre = '10';			//Distance titre / bord haut de page
		else $y_titre = $pos_titre[1];
	if (!$pos_titre[2]) $l_titre = '100';			//Largeur titre
		else $l_titre = $pos_titre[2];
	if (!$pos_titre[3]) $h_titre = '10';			//Hauteur titre
		else $h_titre = $pos_titre[3];
	if (!$pos_titre[4]) $fs_titre = '16';			//Police titre
		else $fs_titre = $pos_titre[4];
	
	$pos_date = explode(',', $acquisition_pdfsug_pos_date);
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
	
	$pos_tab_ = explode(',', $acquisition_pdfsug_tab_sug);
	if (!$pos_tab[0]) $h_tab = '5';				//Hauteur de ligne table suggestions
		else $h_tab = $pos_tab[0];
	if (!$pos_tab[1]) $fs_tab = '8';			//Police table suggestions
		else $fs_tab = $pos_tab[1];
	$x_tab = $marge_gauche;						//position table suggestions / bord droit page 
	$y_tab = $marge_haut;						//position table suggestions / haut page sur pages 2 et + 
	
	$pos_footer = explode(',', $acquisition_pdfsug_pos_footer);
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
	
	
	$sug_map = new suggestions_map();
	
	
	//On récupère les infos de la liste de suggestions
	if(!$statut) $statut= -1;
	
	$us=stripslashes($user_input);
	
	$mask=$sug_map->getMask_FILED();
	
	if(!$user_input) {
		$q = suggestions::listSuggestions(0, $statut, $num_categ, $mask,0, 0, $aq,'',$sugg_location_id,'', 0, $origine_id, $type_origine);
	} else {
		$aq=new analyse_query(stripslashes($user_input),0,0,0,0);
		$q = suggestions::listSuggestions(0, $statut, $num_categ, $mask, 0, 0, $aq,'',$sugg_location_id, $user_input, 0, $origine_id, $type_origine);
	}
	$res = mysql_query($q, $dbh);
	
	
	$ourPDF->addPage();
	$ourPDF->setFont($pmb_pdf_font);
	
	//Affichage date 
	$date =  formatdate(today());
	$ourPDF->setFontSize($fs_date);
	$ourPDF->SetXY($x_date, $y_date);
	$ourPDF->Cell($l_date, $h_date, $date, 0, 0, 'L', 0);
	
	//Affichage titre
	if($origine_id){
		if($type_origine)
			$req = "select concat(empr_prenom,' ',empr_nom) as nom from empr where id_empr='".$origine_id."'";
	    else $req = "select concat(prenom,' ',nom) as nom from users where userid='".$origine_id."'";
		$res_empr = mysql_query($req,$dbh);
		$empr = mysql_fetch_object($res_empr); 
		$titre =  sprintf($msg['acquisition_sug_list_origine'],$empr->nom);
	} else {
		$titre =  $msg['acquisition_sug_list'].$us;
	}
	$ourPDF->setFontSize($fs_titre);
	$ourPDF->SetXY($x_titre, $y_titre);
	$ourPDF->Cell($l_titre, $h_titre, $titre, 0, 0, 'L', 0);
	
	
	//Affichage lignes suggestions
	$ourPDF->SetAutoPageBreak(false);
	$ourPDF->AliasNbPages();
	
	$ourPDF->SetFontSize($fs_tab);
	$ourPDF->SetFillColor(230);
	$ourPDF->Ln();
	$y = $ourPDF->GetY();
	$ourPDF->SetXY($x_tab,$y);
	
	$x_dat =  $x_tab;
	$w_dat = round($w*10/100);
	$x_tit = $x_dat + $w_dat;
	$w_tit = round($w*30/100);
	$x_edi = $x_tit + $w_tit;
	$w_edi = round($w*20/100);
	$x_aut = $x_edi + $w_edi;
	$w_aut = round($w*20/100);
	$x_sta = $x_aut + $w_aut;
	$w_sta = round($w*10/100);
	$x_cat = $x_sta + $w_sta;
	$w_cat = round($w*10/100);
	 
	
	printEntete();

	while ($row = mysql_fetch_object($res)){
	$lib_statut = $sug_map->getPdfComment($row->statut);
		
	
		if(!$row->num_notice) $lib_cat='';
			else $lib_cat='X'; 
	
	
		$h = $h_tab * max( 	$ourPDF->NbLines($w_dat, $row->date_creation),
					$ourPDF->NbLines($w_tit, $row->titre),
					$ourPDF->NbLines($w_edi, $row->editeur),
					$ourPDF->NbLines($w_aut, $row->auteur),
					$ourPDF->NbLines($w_sta, $lib_statut),
					$ourPDF->NbLines($w_cat, $lib_cat) );
						
		$s = $y+$h;		
		if ($s > ($hauteur_page-$marge_bas)){
	
			$ourPDF->AddPage();
			$ourPDF->SetXY($x_tab, $y_tab);
			$y = $ourPDF->GetY();
			printEntete();
			
		} 
		$ourPDF->SetXY($x_dat, $y);
		$ourPDF->Rect($x_dat, $y, $w_dat, $h);
		$ourPDF->MultiCell($w_dat, $h_tab, $row->date_creation, 0, 'L');
		$ourPDF->SetXY($x_tit, $y);
		$ourPDF->Rect($x_tit, $y, $w_tit, $h);
		$ourPDF->MultiCell($w_tit, $h_tab, $row->titre, 0, 'L');
		$ourPDF->SetXY($x_edi, $y);
		$ourPDF->Rect($x_edi, $y, $w_edi, $h);
		$ourPDF->MultiCell($w_edi, $h_tab, $row->editeur, 0, 'L');
		$ourPDF->SetXY($x_aut, $y);
		$ourPDF->Rect($x_aut, $y, $w_aut, $h);
		$ourPDF->MultiCell($w_aut, $h_tab, $row->auteur, 0, 'L');
		$ourPDF->SetXY($x_sta, $y);
		$ourPDF->Rect($x_sta, $y, $w_sta, $h);
		$ourPDF->MultiCell($w_sta, $h_tab, $lib_statut, 0, 'L');
		$ourPDF->SetXY($x_cat, $y);
		$ourPDF->Rect($x_cat, $y, $w_cat, $h);
		$ourPDF->MultiCell($w_cat, $h_tab, $lib_cat, 0, 'L');
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
	global $x_dat,$x_tit,$x_edi,$x_aut,$x_sta,$x_cat;
	global $w_dat,$w_tit,$w_edi,$w_aut,$w_sta,$w_cat;
	global $hauteur_page, $marge_bas;  

$h = $h_tab * max( 	$ourPDF->NbLines($w_dat, $msg['acquisition_sug_dat_cre']),
			$ourPDF->NbLines($w_tit,$msg['acquisition_sug_tit']),
			$ourPDF->NbLines($w_edi, $msg['acquisition_sug_edi']),
			$ourPDF->NbLines($w_aut, $msg['acquisition_sug_aut']),
			$ourPDF->NbLines($w_sta, $msg['acquisition_sug_etat']),
			$ourPDF->NbLines($w_cat, $msg['acquisition_sug_iscat'])
			 );
	$s = $y+$h;		
	if ($s > ($hauteur_page-$marge_bas)){

		$ourPDF->AddPage();
		$ourPDF->SetXY($x_tab, $y_tab);
		$y = $ourPDF->GetY();
		
	} 
	$ourPDF->SetXY($x_dat, $y);
	$ourPDF->Rect($x_dat, $y, $w_dat, $h, 'FD');
	$ourPDF->MultiCell($w_dat, $h_tab, $msg['acquisition_sug_dat_cre'], 0, 'L');
	$ourPDF->SetXY($x_tit, $y);
	$ourPDF->Rect($x_tit, $y, $w_tit, $h, 'FD');
	$ourPDF->MultiCell($w_tit, $h_tab, $msg['acquisition_sug_tit'], 0, 'L');
	$ourPDF->SetXY($x_edi, $y);
	$ourPDF->Rect($x_edi, $y, $w_edi, $h, 'FD');
	$ourPDF->MultiCell($w_edi, $h_tab, $msg['acquisition_sug_edi'], 0, 'L');
	$ourPDF->SetXY($x_aut, $y);
	$ourPDF->Rect($x_aut, $y, $w_aut, $h, 'FD');
	$ourPDF->MultiCell($w_aut, $h_tab, $msg['acquisition_sug_aut'], 0, 'L');
	$ourPDF->SetXY($x_sta, $y);
	$ourPDF->Rect($x_sta, $y, $w_sta, $h, 'FD');
	$ourPDF->MultiCell($w_sta, $h_tab, $msg['acquisition_sug_etat'], 0, 'L');
	$ourPDF->SetXY($x_cat, $y);
	$ourPDF->Rect($x_cat, $y, $w_cat, $h, 'FD');
	$ourPDF->MultiCell($w_cat, $h_tab, $msg['acquisition_sug_iscat'], 0, 'L');
	$y = $y+$h;
}
?>