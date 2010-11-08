<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liste_demande.inc.php,v 1.1 2009-10-01 13:29:24 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($base_path.'/includes/templates/demandes.tpl.php');
require_once($base_path.'/classes/demandes.class.php');
require_once($base_path.'/classes/demandes_action.class.php');

print "<script type='text/javascript' src='./includes/javascript/http_request.js'></script>
<script type='text/javascript' src='./includes/javascript/demandes.js'></script>"; 

$demandes = new demandes();
$demandes_action = new demandes_action($iddemande);

switch($sub){
	
	case 'see_action':
		print $demandes_action->show_list_actions();
	break;
	default :
		$demandes->show_list($idetat);
	break;
}
?>