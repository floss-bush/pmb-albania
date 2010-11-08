<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes_notes.inc.php,v 1.2 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/demandes_notes.class.php");
require_once($class_path."/demandes_actions.class.php");

$notes = new demandes_notes($idnote,$idaction);
$actions = new demandes_actions($idaction);

switch($act){
	
	case 'add_note':
		$notes->show_modif_form();
		break;
	case 'reponse':
		$notes->show_modif_form(true);
		break;
	case 'modif_note':
		$notes->show_modif_form();
		break;
	case 'suppr_note':
		$notes->delete();
		$actions->show_consultation_form();
		break;
		
	
}

?>