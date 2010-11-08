<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: make_demande.inc.php,v 1.2 2009-10-05 08:25:14 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($base_path.'/includes/templates/demandes.tpl.php');
require_once($base_path.'/classes/demandes.class.php');

$demandes = new demandes();

switch($act){
	
	case 'save':
		$demandes->save();
		print "<div class='save_msg'>".$msg['demandes_save_msg']."</div>";
		$demandes->show_list();
	break;
	default :
		$demandes->show_form();
	break;
}

?>