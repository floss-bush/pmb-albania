<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.3 2008-06-04 14:54:18 ohennequin Exp $


if (stristr ( $_SERVER ['REQUEST_URI'], ".inc.php" ))
	die ( "no access" );

require_once ("$base_path/circ/expl/expl_func.inc.php");
require_once ("$base_path/circ/transferts/affichage.inc.php");
require_once ("$class_path/transfert.class.php");

switch ($sub) {
	
	case 'valid' :
		//l'étape de validation
		include ("./circ/transferts/validation.inc.php");
	break;
	
	case 'recep' :
		//l'étape de réception 
		include ("./circ/transferts/reception.inc.php");
	break;
	
	case 'envoi' :
		//l'étape d'envoi 
		include ("./circ/transferts/envoi.inc.php");
	break;
	
	case 'retour' :
		//gestion du retour d'un transfert
		include ("./circ/transferts/retours.inc.php");
	break;
	
	case 'refus' :
		//gestion d'un refus
		include ("./circ/transferts/refuse.inc.php");
	break;

}

?>
