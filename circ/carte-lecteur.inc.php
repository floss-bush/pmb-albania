<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: carte-lecteur.inc.php,v 1.9 2007-03-10 09:03:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la largeur de la zone pour le nom
$var = "pdfcartelecteur_largeur_nom";
$largeur_nom = $$var;

// la position en X
$var = "pdfcartelecteur_pos_h";
$pos_x = $$var;

// la position en Y
$var = "pdfcartelecteur_pos_v";
$pos_y = $$var;

// Valable du
$var = "pdfcartelecteur_valabledu";
$valabledu = $$var;
// au
$var = "pdfcartelecteur_valableau";
$valableau = $$var;
// Carte N°
$var = "pdfcartelecteur_carteno";
$carteno = $$var;

// le texte qui apparait juste au dessus du code-barre
$var = "pdfcartelecteur_biblio_name";
eval ("\$bibli_name=\"".$$var."\";");

// PARAMETRES
// ----------
//   cb_first : code barre de début
//   nbr_cb : nombre de codes-barres à produire
$cb_first="100000";
$nbr_cb=1;

define("CBG_NBR_X_CELLS",        4);     // Nombre d'étiquettes en largeur sur la page
define("CBG_NBR_Y_CELLS",        19);     // Nombre d'étiquettes en hauteur

// marges, mesures en mm
define("CBG_LEFT_MARGIN",        6);
define("CBG_RIGHT_MARGIN",       6);
define("CBG_TOP_MARGIN",         13);
define("CBG_BOTTOM_MARGIN",      13);

// marges intérieures du bord de l'étiquette au code barre, mesures en mm
define("CBG_INNER_LEFT_MARGIN",   4);
define("CBG_INNER_RIGHT_MARGIN",  4);
define("CBG_INNER_TOP_MARGIN",    1);
define("CBG_INNER_BOTTOM_MARGIN", 1);

// place allouée au nom de la bibliothèque, mesure en mm
define("CBG_TEXT_HEIGHT",         2);
// Taille de la police, en points
define("CBG_TEXT_FONT_SIZE",      6);
// Taille du texte du code-barre, 1 : le plus petit ; 5 : le plus grand
define("CBG_CB_TEXT_SIZE",        3);
// Résolution du code barre. Si vous augmentez ce paramètre, il faudra peut-être
// augmenter la taille de la police. Une valeur faible produit un fichier moins volumineux
define("CBG_CB_RES",              5);
// l'apparence du code barre dépend étroitement de la résolution et de la taille du texte

// Démarrage et configuration du pdf
$nom_classe=$fpdf."_Etiquette";
$ourPDF=new $nom_classe(CBG_NBR_X_CELLS, CBG_NBR_Y_CELLS);
$ourPDF->Open();
$ourPDF->addPage();
$ourPDF->SetPageMargins(CBG_TOP_MARGIN, CBG_BOTTOM_MARGIN, CBG_LEFT_MARGIN, CBG_RIGHT_MARGIN);

$requete = "SELECT id_empr, empr_cb, empr_nom, empr_prenom, empr_date_adhesion, empr_date_expiration, date_format(empr_date_adhesion, '".$msg["format_date"]."') as aff_empr_date_adhesion, date_format(empr_date_expiration, '".$msg["format_date"]."') as aff_empr_date_expiration FROM empr WHERE id_empr='$id_empr' LIMIT 1 ";
$res = mysql_query($requete, $dbh);
$empr = mysql_fetch_object($res);

$xpos_top = 12;
$ypos_left = 0;

$ourPDF->SetFont($pmb_pdf_font, '', 14);
$ourPDF->SetXY(($pos_x+40 - $largeur_nom/2), $pos_y);
$ourPDF->MultiCell($largeur_nom, 7, $empr->empr_prenom." ".$empr->empr_nom, 0, "C", 0);

$largeur_carteno = 70;
$ourPDF->SetFont($pmb_pdf_font, '', 10);
$ourPDF->SetXY(($pos_x+40 - $largeur_carteno/2), $pos_y+30);
$ourPDF->MultiCell($largeur_carteno, 8, $carteno." ".$empr->empr_cb, 0, "C", 0);

$largeur_valable = 70;
$ourPDF->SetFont($pmb_pdf_font, '', 10);
$ourPDF->SetXY(($pos_x+40 - $largeur_valable/2), $pos_y+35);
$ourPDF->MultiCell($largeur_valable, 8, $valabledu." ".$empr->aff_empr_date_adhesion." ".$valableau." ".$empr->aff_empr_date_expiration, 0, "C", 0);

$xpos = $pos_x + 16 ;
$ypos = $pos_y+16 ;
// code barre
	$ourPDF->SetFont($pmb_pdf_font, '', CBG_TEXT_FONT_SIZE);
	$ourPDF->SetCBFontSize(CBG_CB_TEXT_SIZE);
	$ourPDF->SetCBXRes(CBG_CB_RES);
	$ourPDF->SetCBStyle(BCS_ALIGN_CENTER | BCS_BORDER | BCS_DRAW_TEXT);
	$cbwidth = $ourPDF->GetStickWidth() - CBG_INNER_LEFT_MARGIN - CBG_INNER_RIGHT_MARGIN;
	$cbheight = $ourPDF->GetStickHeight() - CBG_INNER_TOP_MARGIN - CBG_INNER_BOTTOM_MARGIN;
	if ($bibli_name != '') $cbheight -= CBG_TEXT_HEIGHT;
	if ($bibli_name != "") {
		$ourPDF->SetXY($xpos, $ypos + CBG_INNER_BOTTOM_MARGIN);
		$ourPDF->Cell($ourPDF->GetStickWidth(), CBG_TEXT_HEIGHT, $bibli_name, 0, 0, 'C');
		}
	$x = $xpos + CBG_INNER_LEFT_MARGIN;
	$y = $ypos + CBG_INNER_TOP_MARGIN;
	if ($bibli_name != "") $y += CBG_TEXT_HEIGHT;
	$ourPDF->DrawBarcode($empr->empr_cb, $x, $y, $cbwidth, $cbheight, 'c39');

$ourPDF->SetLineWidth(1);
$ourPDF->Rect($pos_x+10, $pos_y+14, 60, 17, "D");


$ourPDF->Output();
?>
