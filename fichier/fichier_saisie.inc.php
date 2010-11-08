<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fichier_saisie.inc.php,v 1.2 2010-07-09 14:34:40 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/fiche.class.php");

$fiche = new fiche($idfiche);
switch($act){
	case 'save_and_new':
		$fiche->save();
		print $fiche->show_edit_form();
		break;
	case 'update':
		$fiche->save();		
		break;
	default:
		print $fiche->show_edit_form();
		break;
}