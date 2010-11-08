<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: photo_tableau.inc.php,v 1.3 2007-03-10 10:05:50 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/notice_affichage.phototheque.class.php");

function photo_tableau($id,$cart) {
	global $liens_opac;
	global $opac_notices_format;
	global $opac_notices_depliable;
	global $opac_cart_allow;
	global $opac_cart_only_for_subscriber;
	global $opac_notice_affichage_class;
	global $photo_tableau_pos;
	
	$max_tableau_pos=3;
	
	//Début du flux
	if ($id==-1) {
		$retour_aff="<table>";
		$photo_tableau_pos=0;
	}
	
	if ($id==-2) {
		for ($i=$photo_tableau_pos; $i<$max_tableau_pos; $i++) {
			$retour_aff.="<td>&nbsp;</td>";
		}
		if ($photo_tableau_pos<$max_tableau_pos) $retour_aff.="</tr>";
		$retour_aff.="</table>";
	}
	
	if ($id>=0) {
		$current = new notice_affichage_id_photos($id,$liens_opac,$cart);
		$depliable=false;
		$current->do_header();
		
		$current->do_isbd();
		$current->genere_simple($depliable, 'ISBD');
		if (!$photo_tableau_pos) $retour_aff.="<tr>";
		if ($photo_tableau_pos>=$max_tableau_pos) {
			$photo_tableau_pos=0;
			$retour_aff.="</tr>";
		}
		$retour_aff.="<td valign='top'>";
		$retour_aff .= $current->result ;
		$retour_aff.="</td>";
		$photo_tableau_pos++;
	}
	
	return $retour_aff;
}
?>
