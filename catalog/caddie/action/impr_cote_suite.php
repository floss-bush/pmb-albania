<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: impr_cote_suite.php,v 1.2 2009-10-26 17:56:23 dbellamy Exp $

$base_path = "../../..";   
$class_path = "$base_path/classes";
$base_noheader = 1;
require_once ("$base_path/includes/init.inc.php");

require_once ("$class_path/fpdf.class.php");
require_once ("$class_path/ufpdf.class.php");
require_once ("$class_path/fpdf_etiquette.class.php");
require_once ("$class_path/caddie.class.php");

if ($pmb_label_construct_script) {
	require_once ("../$pmb_label_construct_script");
} else {
	require_once ("../custom_label_no_script.inc.php");
}

	$myCart = new caddie($idcaddie);
	if ($elt_flag && $elt_no_flag)
		$liste = $myCart->get_cart("ALL");
	if ($elt_flag && !$elt_no_flag)
		$liste = $myCart->get_cart("FLAG");
	if ($elt_no_flag && !$elt_flag)
		$liste = $myCart->get_cart("NOFLAG");

	// Démarrage et configuration du pdf
	$nom_classe = $fpdf . "_Etiquette";
	$pdf = new $nom_classe ($label_grid_nb_per_row, $label_grid_nb_per_col, $page_orientation, $unit , $page_format );
	$pdf->Open();
	$pdf->SetPageMargins($label_grid_from_top, '0', $label_grid_from_left, '0');
	$pdf->SetSticksMargins(0, 0, 0, 0);
	$pdf->SetSticksPadding($label_grid_h_spacing,$label_grid_v_spacing );
	
	//Saut Etiquettes
	$pos = (($first_row-1)*$label_grid_nb_per_row) + ($first_col);
	for ($i=1;$i<$pos;$i++) {
		$pdf->AddStick();
	}

	//Impression etiquettes
	for ($i=0;$i<count($liste) ;$i++) {

		$pdf->AddStick();
		$content_src = $liste[$i];
		foreach($content_type as $step=>$value) {

			eval('print_'.$content_type[$step].'($pdf, $content_value[$step], $content_src); ');

		}
		
	}
		
	$pdf->Output('planche_etiquette.pdf', true);
	
?>	
