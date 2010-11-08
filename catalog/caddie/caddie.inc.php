<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: caddie.inc.php,v 1.8 2007-03-10 09:03:17 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// functions particulières à ce module
require_once("./catalog/caddie/caddie_func.inc.php");
require_once("$include_path/templates/cart.tpl.php");
require_once("$include_path/expl_info.inc.php");
require_once("$class_path/caddie.class.php");
require_once("$class_path/serials.class.php");
require_once("$class_path/parameters.class.php") ;
require_once("$class_path/emprunteur.class.php") ;
require_once("$include_path/cart.inc.php");
require_once("$include_path/bull_info.inc.php");

$idcaddie = verif_droit_caddie($idcaddie) ;

switch($sub) {
	case "pointage" :
		echo window_title($database_window_title.$msg[caddie_menu]." : ".$msg["caddie_menu_pointage"]);
		$catalog_layout = str_replace('<!--!!menu_contextuel!! -->', $catalog_menu_panier_pointage, $catalog_layout);
		include('./catalog/caddie/pointage/main.inc.php');
		break;
	case "action" :
		echo window_title($database_window_title.$msg[caddie_menu]." : ".$msg["caddie_menu_action"]);
		$catalog_layout = str_replace('<!--!!menu_contextuel!! -->', $catalog_menu_panier_action, $catalog_layout);
		include('./catalog/caddie/action/main.inc.php');
		break;
	case "collecte" :
		echo window_title($database_window_title.$msg[caddie_menu]." : ".$msg["caddie_menu_collecte"]);
		$catalog_layout = str_replace('<!--!!menu_contextuel!! -->', $catalog_menu_panier_collecte, $catalog_layout);
		include('./catalog/caddie/collecte/main.inc.php');
		break;
	case "gestion" :
	default:
		echo window_title($database_window_title.$msg[caddie_menu]." : ".$msg["caddie_menu_gestion"]);
		$catalog_layout = str_replace('<!--!!menu_contextuel!! -->', $catalog_menu_panier_gestion, $catalog_layout);
		include('./catalog/caddie/gestion/main.inc.php');
		break;
	}

