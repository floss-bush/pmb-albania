<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fiche_ajax.inc.php,v 1.1 2010-06-21 09:11:16 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/fiche.class.php");

switch($quoifaire){
	
	case 'show_fiche':
		show_fiche($idfiche);	
	break;
	case 'update':
		update_notice($idfiche);
		break;

}

function show_fiche($idfiche){
	global $liste_ids;
	
	$fic = new fiche($idfiche);
	$fic->liste_ids = explode(",",$liste_ids);
	
	ajax_http_send_response($fic->show_fiche_form());
}

function update_notice($idfiche){
	$fic = new fiche($idfiche);
	
	ajax_http_send_response($fic->show_edit_form());
}