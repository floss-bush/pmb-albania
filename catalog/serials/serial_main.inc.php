<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serial_main.inc.php,v 1.21 2009-03-10 08:31:00 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// inclusion du template de gestion des périodiques
include("$include_path/templates/serials.tpl.php");

// classes particulières à ce module
require_once("$class_path/serials.class.php");
require_once("./catalog/serials/serial_func.inc.php");
require_once("./catalog/serials/bulletinage/bul_func.inc.php");

// récupération des codes de fonction
if (!count($fonction_auteur)) {
	$fonction_auteur = new marc_list('function');
	$fonction_auteur = $fonction_auteur->table;
}

// récupération des codes langues
if (!count($langue_doc)) {
	$f_lang = new marc_list('lang');
	$langue_doc = $f_lang->table;
} 

echo window_title($database_window_title.$msg[771].$msg[1003].$msg[1001]);

switch($sub) {
	case 'serial_form':
		include('./catalog/serials/serial_form.inc.php');
		break;
	case 'update':
		include('./catalog/serials/serial_update.inc.php');
		break;
	case 'delete':
		include('./catalog/serials/serial_delete.inc.php');
		break;
	case 'search':
		include('./catalog/serials/serial_search.inc.php');
		break;
	case 'view':
		include('./catalog/serials/serial_view.inc.php');
		break;
	case 'bulletinage':
		include('./catalog/serials/bulletinage/bul_main.inc.php');
		break;
	case 'analysis':
		include('./catalog/serials/analysis/analysis_main.inc.php');
		break;
	case 'serial_replace':
		include('./catalog/serials/serial_replace.inc.php');
		break;
	case 'bulletin_replace':
		include('./catalog/serials/bulletinage/bul_replace.inc.php');
		break;
	case 'modele':
		include('./catalog/serials/modele/modele_main.inc.php');//TODO
		break;
	case 'abon':
		include('./catalog/serials/abonnement/abonnement_main.inc.php');//TODO
		break;	
	case 'pointage':
		include('./catalog/serials/pointage/pointage_main.inc.php');//TODO
		break;				
	case 'explnum_form':
		include('./catalog/serials/explnum/serial_explnum_form.inc.php');
		break;
	case 'explnum_update':
		include('./catalog/serials/explnum/serial_explnum_update.inc.php');
		break;
	case 'explnum_delete':
		include('./catalog/serials/explnum/serial_explnum_delete.inc.php');
		break;
	case 'collstate_form':
		include('./catalog/serials/collstate_form.inc.php');
		break;
	case 'collstate_update':
		include('./catalog/serials/collstate_update.inc.php');
		break;	
	case 'collstate_delete':
		include('./catalog/serials/collstate_delete.inc.php');
		break;		
	default:
		echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg["recherche"], $serial_header);
		echo $serial_access_form;
		break;
}

echo $serial_footer;
?>