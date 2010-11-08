<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes_ajax.inc.php,v 1.5 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/mono_display.class.php");

switch($quoifaire){
	
	case 'show_notice':
		show_notice($idnotice);	
	break;

}


/*
 * Affichage de la notice
 */
function show_notice($idnotice){
	
	
	$isbd = new mono_display($idnotice, 6, '', 1, '', '', '',1);	
	$html = "<div class='row' style='padding-top: 8px;'>".$isbd->aff_statut."<h1 style='display: inline;'>".$isbd->header."</h1></div>";
	$html .= "<div class='row'>".$isbd->isbd."</div>";
	
	
	print ajax_http_send_response($html);
}
?>