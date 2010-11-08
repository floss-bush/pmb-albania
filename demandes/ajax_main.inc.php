<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax_main.inc.php,v 1.1 2009-10-01 13:29:24 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($categ){
	
	case 'dmde':
		include('./demandes/ajax/demandes_ajax.inc.php');
		break;
	case 'action':
		include('./demandes/ajax/actions_ajax.inc.php');
		break;
	case 'rapport':
		include('./demandes/ajax/rapport_ajax.inc.php');
		break;
}
?>