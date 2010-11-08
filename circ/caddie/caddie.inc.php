<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: caddie.inc.php,v 1.3 2008-11-21 12:38:25 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// functions particulières à ce module
require_once("$include_path/templates/empr_cart.tpl.php");
require_once("$class_path/empr_caddie.class.php");
require_once("$class_path/parameters.class.php") ;
require_once("$class_path/emprunteur.class.php") ;
require_once("$include_path/empr_cart.inc.php");
require_once("$include_path/cart.inc.php");
require_once("$base_path/circ/empr/empr_func.inc.php");

$idcaddie = verif_droit_empr_caddie($idcaddie) ;

switch($sub) {
	case "action" :
		include('./circ/caddie/action/main.inc.php');
		break;
	case "gestion" :
	default:
		include('./circ/caddie/gestion/main.inc.php');
		break;
	}

