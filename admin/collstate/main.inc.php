<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.2 2010-07-22 15:14:59 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($sub) {
	case 'emplacement':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["admin_menu_collstate_emplacement"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["admin_menu_collstate_emplacement"]);
		include("./admin/collstate/emplacement.inc.php");
		break;
	case 'support':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["admin_menu_collstate_support"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["admin_menu_collstate_support"]);
		include("./admin/collstate/support.inc.php");
		break;		
	case 'perso':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["admin_menu_collstate_perso"], $admin_layout);
		print $admin_layout;
		include("./admin/collstate/perso.inc.php");
		break;
	case 'statut':
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["admin_menu_collstate_statut"], $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg["admin_menu_collstate_statut"]);
		include("./admin/collstate/statut.inc.php");
		break;
	default:
		$admin_layout = str_replace('!!menu_sous_rub!!', "", $admin_layout);
		print $admin_layout;
		echo window_title($database_window_title.$msg[131].$msg[1003].$msg[1001]);
		include("$include_path/messages/help/$lang/admin_collstate.txt");
		break;
}
