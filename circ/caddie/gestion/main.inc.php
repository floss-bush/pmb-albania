<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.6 2010-04-15 12:50:03 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


switch ($quoi) {
	case 'razpointage':
		echo window_title($database_window_title.$msg[empr_caddie_menu]." : ".$msg["empr_caddie_menu_pointage_raz"]);
		$empr_menu_panier_pointage = str_replace('!!sous_menu_choisi!!', $msg["empr_caddie_menu_pointage_raz"], $empr_menu_panier_pointage);
		print $empr_menu_panier_pointage ;
		include ("./circ/caddie/gestion/pointage_raz.inc.php");
		break;
	case 'pointage':
		echo window_title($database_window_title.$msg[empr_caddie_menu]." : ".$msg["empr_caddie_menu_pointage_selection"]);
		$empr_menu_panier_pointage = str_replace('!!sous_menu_choisi!!', $msg["empr_caddie_menu_pointage_selection"], $empr_menu_panier_pointage);
		print $empr_menu_panier_pointage ;
		include ("./circ/caddie/gestion/pointage_selection.inc.php");
		break;
	case 'pointagebarcode':
		echo window_title($database_window_title.$msg[empr_caddie_menu]." : ".$msg["empr_caddie_menu_pointage_barcode"]);
		$empr_menu_panier_pointage = str_replace('!!sous_menu_choisi!!', $msg["empr_caddie_menu_pointage_barcode"], $empr_menu_panier_pointage);
		print $empr_menu_panier_pointage ;
		include ("./circ/caddie/gestion/pointage_barcode.inc.php");
		break;
	case 'selection':
		echo window_title($database_window_title.$msg[empr_caddie_menu]." : ".$msg["empr_caddie_menu_collecte_selection"]);
		$empr_menu_panier_collecte = str_replace('!!sous_menu_choisi!!', $msg["empr_caddie_menu_collecte_selection"], $empr_menu_panier_collecte);
		print $empr_menu_panier_collecte ;
		include ("./circ/caddie/gestion/collecte_selection.inc.php");
		break;
	case 'barcode':
		echo window_title($database_window_title.$msg[empr_caddie_menu]." : ".$msg["empr_caddie_menu_collecte_barcode"]);
		$empr_menu_panier_collecte = str_replace('!!sous_menu_choisi!!', $msg["empr_caddie_menu_collecte_barcode"], $empr_menu_panier_collecte);
		print $empr_menu_panier_collecte ;
		include ("./circ/caddie/gestion/collecte_barcode.inc.php");
		break;
	case 'procs':
		echo window_title($database_window_title.$msg[empr_caddie_menu]." : ".$msg["empr_caddie_menu_gestion_procs"]);
		$empr_menu_panier_gestion = str_replace('!!sous_menu_choisi!!', $msg["empr_caddie_menu_gestion_procs"], $empr_menu_panier_gestion);
		print $empr_menu_panier_gestion ;
		include ("./circ/caddie/gestion/procs.inc.php");
		break;
	case 'remote_procs':
		echo window_title($database_window_title.$msg[empr_caddie_menu]." : ".$msg["remote_procedures_circ_title"]);
		$empr_menu_panier_gestion = str_replace('!!sous_menu_choisi!!', $msg["remote_procedures_circ_title"], $empr_menu_panier_gestion);
		print $empr_menu_panier_gestion ;
		include ("./circ/caddie/gestion/remote_procs.inc.php");
		break;
	case 'panier':
	default:
		echo window_title($database_window_title.$msg[empr_caddie_menu]." : ".$msg["empr_caddie_menu_gestion"]);
		$empr_menu_panier_gestion = str_replace('!!sous_menu_choisi!!', $msg["caddie_menu_gestion_panier"], $empr_menu_panier_gestion);
		print $empr_menu_panier_gestion ;
		include ("./circ/caddie/gestion/panier.inc.php");
		break;
	}
