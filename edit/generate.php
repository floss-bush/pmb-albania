<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: generate.php,v 1.16 2010-05-05 15:06:04 mbertin Exp $

//modifié 12/2007 François CEROVETTI pour affichage code en clair avec police vectorielle.
// lire exemple de valeurs adaptées à 65 etiquettes AVERY A4 plus bas:

$base_path="..";                            
$base_auth = "";  
$base_title = "PDF";
$base_noheader=1;
require_once ("$base_path/includes/init.inc.php");  

include("$class_path/fpdf.class.php");
include("$class_path/ufpdf.class.php");
include("$class_path/fpdf_etiquette.class.php");

// PARAMETRES
// ----------
//   bibli_name : nom de la bibliothèque
//   cb_first : code barre de début
//   nbr_cb : nombre de codes-barres à produire

// save params
/*
 * Début du commantaire : Si on laisse ça on écrase les anciens paramètres 
$mep_etiq_cb[bibli_name]               =   $bibli_name ;
$mep_etiq_cb[nbr_cb]                   =   $nbr_cb ;
$mep_etiq_cb[ORIENTATION]              =   $ORIENTATION ;
$mep_etiq_cb[CBG_NBR_X_CELLS]          =   $CBG_NBR_X_CELLS;
$mep_etiq_cb[CBG_NBR_Y_CELLS]          =   $CBG_NBR_Y_CELLS ;
$mep_etiq_cb[CBG_LEFT_MARGIN]          =   $CBG_LEFT_MARGIN ;
$mep_etiq_cb[CBG_RIGHT_MARGIN]         =   $CBG_RIGHT_MARGIN ;
$mep_etiq_cb[CBG_TOP_MARGIN]           =   $CBG_TOP_MARGIN ;
$mep_etiq_cb[CBG_BOTTOM_MARGIN]        =   $CBG_BOTTOM_MARGIN ;
$mep_etiq_cb[CBG_INNER_LEFT_MARGIN]    =   $CBG_INNER_LEFT_MARGIN ;
$mep_etiq_cb[CBG_INNER_RIGHT_MARGIN]   =   $CBG_INNER_RIGHT_MARGIN ;
$mep_etiq_cb[CBG_INNER_TOP_MARGIN]     =   $CBG_INNER_TOP_MARGIN ;
$mep_etiq_cb[CBG_INNER_BOTTOM_MARGIN]  =   $CBG_INNER_BOTTOM_MARGIN ;
$mep_etiq_cb[CBG_TEXT_HEIGHT]          =   $CBG_TEXT_HEIGHT ;
$mep_etiq_cb[CBG_TEXT_FONT_SIZE]       =   $CBG_TEXT_FONT_SIZE ;
$mep_etiq_cb[CBG_CB_TEXT_SIZE]         =   $CBG_CB_TEXT_SIZE ;
$mep_etiq_cb[CBG_CB_RES]               =   $CBG_CB_RES ;

$querry = "update parametres set valeur_param='".serialize($mep_etiq_cb)."' where type_param = 'pmb' and sstype_param='param_etiq_codes_barres' ";
$res = mysql_query($querry, $dbh);*/


// exemple de valeurs fonctionnelles pour AVERY 65 étiquettes par page OISEAULIRE Cerovetti
// Constantes non activées, valeurs passées par le formulaire
/*
define("CBG_NBR_X_CELLS",        5);     // Nombre d'étiquettes en largeur sur la page
define("CBG_NBR_Y_CELLS",        13);     // Nombre d'étiquettes en hauteur

// marges, mesures en mm
define("CBG_LEFT_MARGIN",        4);
define("CBG_RIGHT_MARGIN",       4);
define("CBG_TOP_MARGIN",         11);
define("CBG_BOTTOM_MARGIN",      10);

// marges intérieures du bord de l'étiquette au code barre, mesures en mm
define("CBG_INNER_LEFT_MARGIN",   4);
define("CBG_INNER_RIGHT_MARGIN",  4);
define("CBG_INNER_TOP_MARGIN",    1);
define("CBG_INNER_BOTTOM_MARGIN", 1);

// place allouée au nom de la bibliothèque, mesure en mm
define("CBG_TEXT_HEIGHT",         4);
// Taille de la police, en points
define("CBG_TEXT_FONT_SIZE",      11);
// Taille du texte du code-barre, 1 : le plus petit ; 5 : le plus grand
define("CBG_CB_TEXT_SIZE",        1);
// Résolution du code barre. Si vous augmentez ce paramètre, il faudra peut-être
// augmenter la taille de la police. Une valeur faible produit un fichier moins volumineux
define("CBG_CB_RES",              2);
// l'apparence du code barre dépend étroitement de la résolution et de la taille du texte
*/

// Démarrage et configuration du pdf
$nom_classe=$fpdf."_Etiquette";
$pdf=new $nom_classe($CBG_NBR_X_CELLS, $CBG_NBR_Y_CELLS, $ORIENTATION);
$pdf->Open();
$pdf->SetPageMargins($CBG_TOP_MARGIN, $CBG_BOTTOM_MARGIN, $CBG_LEFT_MARGIN, $CBG_RIGHT_MARGIN);
$pdf->SetFont($pmb_pdf_font, '', $CBG_TEXT_FONT_SIZE);
$pdf->SetCBFontSize($CBG_CB_TEXT_SIZE);
$pdf->SetCBXRes($CBG_CB_RES);
$pdf->SetCBStyle(BCS_ALIGN_CENTER | BCS_BORDER | BCS_DRAW_TEXT);
switch ($source) {
	case 'fromfile' :
		$mv=move_uploaded_file($_FILES['userfile']['tmp_name'],"../temp/".basename($_FILES['userfile']['tmp_name']));
		if (!$mv) print "Could not upload file";
		$fname="../temp/".basename($_FILES['userfile']['tmp_name']);
		//ouverture du fichier
		$f = @fopen($fname, 'r');
    	if (!$f) {
    		print "error while opening file ".$fname; 
    		exit();
    	}
		// on charge tout en mémoire, on coupe aux espaces et on met le tout dans un tableau
		$filecontent = fread ($f, filesize($fname));
		if (!$filecontent) {
			print "empty file ".$fname; 
			exit();
		}
		fclose($f);
		unlink($fname);

		$cbarray = preg_split("/[\s]+/", $filecontent, -1, PREG_SPLIT_NO_EMPTY);
		unset($filecontent);

		$nbr_cb = count($cbarray);
		if ($nbr_cb == 0) {
			$fini = true;
			print "no valid barcodes found in file ".$fname; exit();
		} else {
			$i_cb = 0;
			$cb = $cbarray[$i_cb];
		}
		$fini = false;
		break;
	case 'autoinc' :
	default :
		$cb = $cb_first;
		$fini = false;
		if ($nbr_cb < 1) $fini = true;
		break;
}
$cbwidth = $pdf->GetStickWidth() - $CBG_INNER_LEFT_MARGIN - $CBG_INNER_RIGHT_MARGIN;
$cbheight = $pdf->GetStickHeight() - $CBG_INNER_TOP_MARGIN - $CBG_INNER_BOTTOM_MARGIN -  $CBG_TEXT_HEIGHT ;
// if ($bibli_name != '') {
	$cbheight -= $CBG_TEXT_HEIGHT;
 // }

while ( ! $fini) {
	// Ajoute une étiquette
	$pdf->AddStick();

	// texte
	if ($bibli_name != "") {
		$pdf->SetXY($pdf->GetStickX(), $pdf->GetStickY() + $CBG_INNER_TOP_MARGIN);
		$pdf->Cell($pdf->GetStickWidth(), $CBG_TEXT_HEIGHT, stripslashes($bibli_name.$truc), 0, 0, 'C');

	}

	// code barre
	$x = $pdf->GetStickX() + $CBG_INNER_LEFT_MARGIN;
	$y = $pdf->GetStickY() + $CBG_INNER_TOP_MARGIN;
	if ($bibli_name != "") {
		$y += $CBG_TEXT_HEIGHT;
	}
	$pdf->DrawBarcode($cb, $x, $y, $cbwidth, $cbheight, 'c39');
	
	// code barre en clair ( il faut desactiver son affichage ds le fichier class/barecode.php par define("BCS_DRAW_TEXT"      ,  0); au lieu de 128
	$pdf->SetXY($pdf->GetStickX(), $pdf->GetStickY() + $CBG_INNER_TOP_MARGIN + $CBG_TEXT_HEIGHT + $cbheight);
		$pdf->Cell($pdf->GetStickWidth(), $CBG_TEXT_HEIGHT, $cb, 0, 0, 'C');

	// incrémentation et test de fin
	switch ($source) {
		case 'fromfile' :
			$i_cb++;
			if ($i_cb >= $nbr_cb)
			{
				$fini = true;
			}
			else
			{
				$cb = $cbarray[$i_cb];
			}
			break;

		case 'autoinc' :
		default :
			// incrémentation
			$i = strlen($cb) - 1;
			do {
				if ($cb{$i} == "9") {
					$cb{$i} = 0;
					if ($i == 0) {
						$cb = "1".$cb;
					}
				}
				else {
					$cb{$i} = chr(ord($cb{$i}) + 1);
				}
				$i--;
			} while (($i >= 0) && ($cb{$i+1} == 0));
			// test de fin
			if ($pdf->GetNbrSticks() >= $nbr_cb)
			{
				$fini = true;
			}
			break;
	}
}

$pdf->Output('CB'.$cb_first.'-'.($cb_first+$nbr_cb).'.pdf', true);
