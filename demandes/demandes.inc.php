<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes.inc.php,v 1.3 2009-10-13 07:29:33 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/demandes.class.php");
require_once($class_path."/demandes_actions.class.php");
require_once($class_path."/rapport.class.php");
require_once($base_path."/demandes/export_format/report_to_rtf.class.php");


$demande = new demandes($iddemande);
$actions = new demandes_actions($idaction);
$rap = new rapport_demandes($iddemande);

switch($act){
	
	case 'new':
		$demande->show_modif_form();
	break;	
	case 'save':
		$demande->save();
		$demande->show_consult_form();
	break;	
	case 'modif':
		$demande->show_modif_form();
	break;
	case 'suppr_noti':
		$demande->suppr_notice_form();
	break;
	case 'suppr':
		$demande->delete();
		$demande->show_list_form();
	break;
	case 'see_dmde':
		$demande->show_consult_form();
		break;
	case 'save_action':
		$actions->save();
		$demande->show_consult_form();
	break;
	case 'change_state':
		$demande->change_state($state);
		$demande->show_consult_form();
	break;
	case 'attach':
		$demande->show_docnum_to_attach();
	break;	
	case 'save_attach':
		$demande->attach_docnum();
		$demande->show_consult_form();
	break;	
	case 'notice':
		$demande->show_notice_form();
	break;
	case 'upd_notice':
		include($base_path."/demandes/update_notice.inc.php");
		$demande->show_consult_form();
	break;
	case 'rapport':
		$rap->showRapport();
	break;	
	default:
		$demande->show_list_form();
	break;
		
	
}



?>