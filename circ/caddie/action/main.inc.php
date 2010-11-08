<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.4 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch ($quelle) {
	case 'transfert':
		$empr_menu_panier_action = str_replace('!!sous_menu_choisi!!', $msg["caddie_menu_action_transfert"], $empr_menu_panier_action);
		print $empr_menu_panier_action ;
		include ("./circ/caddie/action/transfert.inc.php");
		break;
	case 'export':
		$empr_menu_panier_action = str_replace('!!sous_menu_choisi!!', $msg["caddie_menu_action_export"], $empr_menu_panier_action);
		print $empr_menu_panier_action ;
		include ("./circ/caddie/action/export.inc.php");
		break;
	case 'supprpanier':
		$empr_menu_panier_action = str_replace('!!sous_menu_choisi!!', $msg["caddie_menu_action_suppr_panier"], $empr_menu_panier_action);
		print $empr_menu_panier_action ;
		include ("./circ/caddie/action/supprpanier.inc.php");
		break;
	case 'supprbase':
		$empr_menu_panier_action = str_replace('!!sous_menu_choisi!!', $msg["caddie_menu_action_suppr_base"], $empr_menu_panier_action);
		print $empr_menu_panier_action ;
		include ("./circ/caddie/action/supprbase.inc.php");
		break;
	case 'edition':
		$empr_menu_panier_action = str_replace('!!sous_menu_choisi!!', $msg["caddie_menu_action_edition"], $empr_menu_panier_action);
		print $empr_menu_panier_action ;
		include ("./circ/caddie/action/edition.inc.php");
		break;
	case 'selection':
		$empr_menu_panier_action = str_replace('!!sous_menu_choisi!!', $msg["caddie_menu_action_selection"], $empr_menu_panier_action);
		print $empr_menu_panier_action ;
		include ("./circ/caddie/action/selection.inc.php");
		break;
	case 'mailing':
		$empr_menu_panier_action = str_replace('!!sous_menu_choisi!!', $msg["caddie_menu_action_mailing"], $empr_menu_panier_action);
		print $empr_menu_panier_action ;
		include ("./circ/caddie/action/mailing.inc.php");
		break;
	default:
		echo window_title($database_window_title.$msg[empr_caddie_menu]." : ".$msg["empr_caddie_menu_action"]);
		$empr_menu_panier_action = str_replace('!!sous_menu_choisi!!', "", $empr_menu_panier_action);
		print $empr_menu_panier_action ;
		print "<br /><br /><b>".$msg["caddie_select_action"]."</b>" ;
		break;
	}
