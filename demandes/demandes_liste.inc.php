<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes_liste.inc.php,v 1.3 2010-02-08 11:28:12 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/demandes.class.php");

$demande = new demandes($iddemande);

switch($act){
	
	case 'new':
		$demande->show_modif_form();
	break;	
	case 'save':
		$demande->save();
		$demande->show_list_form();
	break;
	case 'search':
		$demande->show_list_form();
	break;
	case 'suppr':
		$demande->delete();
		$demande->show_list_form();
	break;
	case 'suppr_noti':
		$demande->suppr_notice_form();
	break;
	case 'change_state':
		$demande->change_state($state);
		$demande->show_list_form();
	break;
	case 'affecter':
		$demande->attribuer();
		$demande->show_list_form();
	break;
	default:
		$demande->show_list_form();
	break;
}
?>